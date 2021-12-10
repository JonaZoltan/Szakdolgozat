<?php

namespace app\modules\importexport\controllers;

use app\controllers\BaseController;
use app\modules\importexport\ImportexportModule;
use yii\web\UploadedFile;

use app\modules\settings\models\Settings;

use app\modules\users\models\User;

use app\modules\groups\models\Membership;

use app\modules\clients\models\Client;
use app\modules\clients\models\ClientContact;
use app\modules\contacts\models\Contact;
use app\modules\contacts\models\ContactData;
use app\modules\clients\models\ClientAttribute;
use app\modules\clients\models\SalesStatus;

use app\modules\addresses\models\Address;
use app\modules\addresses\models\Country;
use app\modules\addresses\models\Settlement;
use app\modules\addresses\models\PublicSpaceType;

use app\modules\products\models\Product;
use app\modules\products\models\Unit;
use app\modules\products\models\ProductGroup;
use app\modules\productcategories\models\ProductCategory;
use app\modules\productcategories\models\ProductCategoryLink;

use Yii;


class DefaultController extends BaseController
{
    private static function errors_to_html($errors) {
        $html = "<ul>";
        foreach ($errors as $key => $arr) {
            if (is_string($arr)) {
                $arr = [ $arr ];
            }
            foreach ($arr as $item) {
                $html .= "<li>" . htmlspecialchars($item) . " <code>" . $key . "</code></li>";
            }
        }
        return $html . "</ul>";
    }
    
    private static function import_table($model_name, $data = [], $operation = "create", $table_info) {
        $primary_key = $model_name::primaryKey();
        //var_dump($primary_key);die();
        $errors = [];
        $success = 0;
        foreach ($data as $record_index => $record) {
            if ($operation === "create") {
                $model = new $model_name;
            } else {
                $filter = [];
                $pk_not_found = false;
                foreach ($primary_key as $pk) {
                    if (!isset($record[$pk])) {
                        $errors[] = "Nem található a kulcs: <code>" . $pk . "</code>";
                        $pk_not_found = true;
                        break;
                    }
                    $filter[$pk] = $record[$pk];
                }
                if ($pk_not_found) {
                    break;
                }
                $model = $model_name::findOne($filter);
                if (!$model) {
                    $errors[] = "Nincs ilyen elem: <code>" . implode(", ", $filter) . "</code>";
                    break;
                }
            }
            // Kulcsok kizárása
            foreach ($record as $key => $value) {
                if (!in_array($key, $primary_key) && $key !== "group_id" && !in_array($key, $table_info["excluded_from_import"])) {
                    if ($value !== NULL && $value !== '') {
                        $model->$key = $value;
                    }
                }
            }
            if (in_array("group_id", array_keys(Yii::$app->db->schema->getTableSchema($model_name::tableName())->columns))) {
                $model->group_id = User::currentGroup()->id;
            }
            if (!$model->save()) {
                $errors[] = "Nem sikerült importálni a(z) " . strval($record_index + 1) . ". elemet a következő ok(ok)ból:<br>"
                    . self::errors_to_html($model->errors);
            } else {
                $success += 1;
                if ($operation === "create" && $model_name === "app\modules\users\models\User") {
                    $membership = new Membership;
                    $membership->group_id = User::currentGroup()->id;
                    $membership->user_id = $model->getPrimaryKey();
                    $membership->save(false);
                }
            }
        }
        // errors to html
        $errors_html = "";
        if (count($errors) > 0) {
            $errors_html .= "<ul>";
            foreach ($errors as $e) {
                $errors_html .= "<li>" . $e . "</li>";
            }
            $errors_html .= "</ul>";
        }
        return [
            "error" => $errors_html,
            "success" => $success,
        ];
    }
    
    private static function field($arr, $name, $default = "") {
        if (isset($arr[$name])) {
            return trim(strval($arr[$name]) ?: $default);
        }
        return trim($default);
    }
    
    private static function create_address(
        $country,
        $zip,
        $settlement,
        $public_space_name,
        $public_space_type,
        $street_nr,
        $parcel_nr = "",
        $building = "",
        $stair = "",
        $floor = "",
        $door = ""
    ) {
        $country = Country::findOne(["name" => $country]);
        if (!$country) {
            $country = Country::findOne(1); // Magyarország (default)
        }
        if (!trim($settlement)) {
            return null; // Nincs neve a településnek
        }
        $settlement_record = Settlement::findOne([
            "group_id" => User::currentGroup()->id,
            "name" => $settlement,
            "country_id" => $country->getPrimaryKey(),
        ]);
        if (!$settlement_record) {
            $settlement_record = new Settlement;
            $settlement_record->country_id = $country->getPrimaryKey();
            $settlement_record->group_id = User::currentGroup()->id;
            $settlement_record->save(false);
        }
        $public_space_type_record = PublicSpaceType::findOne(["name" => $public_space_type]);
        $attrs = [
            "group_id" => User::currentGroup()->id,
            "country_id" => $country->getPrimaryKey(),
            "zip_code" => $zip,
            "settlement_id" => $settlement_record->getPrimaryKey(),
            "settlement_text" => $settlement,
            "public_space_name" => $public_space_name,
            "public_space_type" => $public_space_type_record ? $public_space_type_record->id : NULL,
            "public_space_type_text" => $public_space_type,
            "number" => $street_nr,
            "building" => $building,
            "staircase" => $stair,
            "floor" => $floor,
            "door" => $door,
            "parcel_number" => $parcel_nr,
        ];
        $address = Address::findOne($attrs);
        if (!$address) {
            $address = new Address;
            $address->setAttributes($attrs);
            if (!$address->save()) {
                //var_dump($address->errors);
                return null;
            }
        }
        return $address;
    }
    
    public function actionTest() {
        var_dump(
            self::create_address(
                "Magyarország",
                "49000",
                "Fehérgyarmat",
                "Táncsics Mihály",
                "utca",
                "",
                "ffd"
            )
        );
    }
    
    private static function handle_client_attrs($client_id, $attrs) {
        $client = Client::findOne($client_id);
        if ($client) {
            foreach ($attrs as $key => $value) {
                if (trim($key) && trim($value)) {
                    $attr = ClientAttribute::findOne([
                        "client_id" => $client_id,
                        "name" => trim($key),
                    ]);
                    if (!$attr) {
                        $attr = new ClientAttribute;
                        $attr->name = trim($key);
                        $attr->client_id = $client_id;
                    }
                    $attr->value = trim($value);
                    $attr->save(false);
                }
            }
        }
    }
    
    private static function handle_client_contact($client_id, $name, $position, $phone, $email) {
        $client = Client::findOne($client_id);
        if ($client && trim($name)) {
            $contact_person = null;
            $client_contact = null;
            foreach ($client->clientContacts as $client_contact) {
                $contact = $client_contact->contact;
                if (trim($contact->name) == trim($name)) {
                    $contact_person = $contact;
                    break;
                }
            }
            if (!$contact_person) {
                $contact_person = new Contact;
                $contact_person->name = trim($name);
                $contact_person->group_id = User::currentGroup()->id;
                $contact_person->save(false);
                // Add connection
                $client_contact = new ClientContact;
                $client_contact->client_id = $client->id;
                $client_contact->contact_id = $contact_person->getPrimaryKey();
                $client_contact->save(false);
            }
            $client_contact->position = trim($position) ?: "-";
            if (trim($phone)) {
                $contact_data_attrs = [
                    'type' => 1, // telefonszám
                    'value' => trim($phone),
                    'contact_id' => $contact_person->getPrimaryKey(),
                ];
                $contact_data = ContactData::findOne($contact_data_attrs);
                if (!$contact_data) {
                    $contact_data = new ContactData;
                    $contact_data->setAttributes($contact_data_attrs);
                    $contact_data->save(false);
                }
            }
            if (trim($email)) {
                $contact_data_attrs = [
                    'type' => 2, // email
                    'value' => trim($email),
                    'contact_id' => $contact_person->getPrimaryKey(),
                ];
                $contact_data = ContactData::findOne($contact_data_attrs);
                if (!$contact_data) {
                    $contact_data = new ContactData;
                    $contact_data->setAttributes($contact_data_attrs);
                    $contact_data->save(false);
                }
            }
        }
    }
    
    private static function simplified_import_clients($data) {
        $errors = [];
        $success = 0;
        $group_id = User::currentGroup()->id;
        foreach ($data as $record_index => $record) {
            $model = null;
            if (self::field($record, 'adoszam')) {
                $model = Client::findOne(['tax_number' => self::field($record, 'adoszam'), 'group_id' => $group_id]);
            }
            if (!$model && self::field($record, 'eu_adoszam')) {
                $model = Client::findOne(['eu_tax_number' => self::field($record, 'eu_adoszam'), 'group_id' => $group_id]);
            }
            if (!$model && self::field($record, 'nev') && self::field($record, 'email')) {
                // Név és email cím alapján is megpróbáljuk megtalálni az ügyfelet
                $model = Client::findOne(['name' => self::field($record, 'nev'), 'email' => self::field($record, 'email'), 'group_id' => $group_id]);
            }
            if (!$model && self::field($record, 'nev') && self::field($record, 'telefonszam')) {
                // Név és telefonszám páros alapján is megpróbáljuk megtalálni az ügyfelet
                $model = Client::findOne(['name' => self::field($record, 'nev'), 'phone' => self::field($record, 'telefonszam'), 'group_id' => $group_id]);
            }
            if (!$model) {
                // Se az EU adószám, se a belföldi adószám alapján nem található az ügyfél
                $model = new Client;
            }
            $model->name = self::field($record, 'rovid_nev');
            $model->full_name = self::field($record, 'teljes_nev');
            $model->tax_number = self::field($record, 'adoszam');
            $model->eu_tax_number = self::field($record, 'eu_adoszam');
            $model->phone = self::field($record, 'telefonszam');
            $model->email = self::field($record, 'email_cim');
            $model->bank_account_number = self::field($record, 'bankszamlaszam');
            $model->type_1 = self::field($record, 'tipus_1');
            $model->type_2 = self::field($record, 'tipus_2');
            $model->group_id = $group_id;
            $model->scope_of_activities = self::field($record, 'tevekenysegi_kor');
            $sales_status = trim(self::field($record, 'ertekesitesi_statusz'));
            if ($sales_status) {
                
                $sales_status_record = SalesStatus::findOne([
                    "group_id" => User::currentGroup()->id,
                    "name" => $sales_status,
                ]);
                if (!$sales_status_record) {
                    $sales_status_record = new SalesStatus;
                    $sales_status_record->name = $sales_status;
                    $sales_status_record->group_id = User::currentGroup()->id;
                    $sales_status_record->save(false);
                }
                $model->sales_status_id = $sales_status_record->getPrimaryKey();
            }
            $billing_addr = self::create_address(
                self::field($record, 'szamlazasi_cim_orszag'),
                self::field($record, 'szamlazasi_cim_irsz'),
                self::field($record, 'szamlazasi_cim_telepules'),
                self::field($record, 'szamlazasi_cim_kozterulet_neve'),
                self::field($record, 'szamlazasi_cim_kozterulet_jellege'),
                self::field($record, 'szamlazasi_cim_hazszam'),
                self::field($record, 'szamlazasi_cim_helyrajzi_szam'),
                self::field($record, 'szamlazasi_cim_epulet'),
                self::field($record, 'szamlazasi_cim_lepcsohaz'),
                self::field($record, 'szamlazasi_cim_emelet'),
                self::field($record, 'szamlazasi_cim_ajto')
            );
            $shipping_addr = self::create_address(
                self::field($record, 'szallitasi_cim_orszag'),
                self::field($record, 'szallitasi_cim_irsz'),
                self::field($record, 'szallitasi_cim_telepules'),
                self::field($record, 'szallitasi_cim_kozterulet_neve'),
                self::field($record, 'szallitasi_cim_kozterulet_jellege'),
                self::field($record, 'szallitasi_cim_hazszam'),
                self::field($record, 'szallitasi_cim_helyrajzi_szam'),
                self::field($record, 'szallitasi_cim_epulet'),
                self::field($record, 'szallitasi_cim_lepcsohaz'),
                self::field($record, 'szallitasi_cim_emelet'),
                self::field($record, 'szallitasi_cim_ajto')
            );
            if ($billing_addr) {
                $model->billing_address_id = $billing_addr->getPrimaryKey();
            }
            if ($shipping_addr) {
                $model->shipping_address_id = $shipping_addr->getPrimaryKey();
            }
        
            if (!$model->save()) {
                $errors[] = "Nem sikerült importálni a(z) " . strval($record_index + 1) . ". elemet a következő ok(ok)ból:<br>"
                    . self::errors_to_html($model->errors);
            } else {
                $success += 1;
                // Egyedi jellemzők
                self::handle_client_attrs($model->getPrimaryKey(), [
                    self::field($record, 'egyedi_jellemzo_megnevezes_1') => self::field($record, 'egyedi_jellemzo_ertek_1'),
                    self::field($record, 'egyedi_jellemzo_megnevezes_2') => self::field($record, 'egyedi_jellemzo_ertek_2'),
                    self::field($record, 'egyedi_jellemzo_megnevezes_3') => self::field($record, 'egyedi_jellemzo_ertek_3'),
                ]);
                // Kontaktok
                self::handle_client_contact(
                    $model->getPrimaryKey(),
                    self::field($record, 'ugyfel_kontakt_1_nev'),
                    self::field($record, 'ugyfel_kontakt_1_beosztas'),
                    self::field($record, 'ugyfel_kontakt_1_telefonszam'),
                    self::field($record, 'ugyfel_kontakt_1_email')
                );
                self::handle_client_contact(
                    $model->getPrimaryKey(),
                    self::field($record, 'ugyfel_kontakt_2_nev'),
                    self::field($record, 'ugyfel_kontakt_2_beosztas'),
                    self::field($record, 'ugyfel_kontakt_2_telefonszam'),
                    self::field($record, 'ugyfel_kontakt_2_email')
                );
                self::handle_client_contact(
                    $model->getPrimaryKey(),
                    self::field($record, 'ugyfel_kontakt_3_nev'),
                    self::field($record, 'ugyfel_kontakt_3_beosztas'),
                    self::field($record, 'ugyfel_kontakt_3_telefonszam'),
                    self::field($record, 'ugyfel_kontakt_3_email')
                );
            }  
        }
        // errors to html
        $errors_html = "";
        if (count($errors) > 0) {
            $errors_html .= "<ul>";
            foreach ($errors as $e) {
                $errors_html .= "<li>" . $e . "</li>";
            }
            $errors_html .= "</ul>";
        }
        return [
            "error" => $errors_html,
            "success" => $success,
        ];
    }
    
    private static function handle_product_category($product_id, $cat_1, $cat_2, $cat_3, $cat_4) {
        $product = Product::findOne($product_id);
        $group_id = User::currentGroup()->id;
        $found_category = null;
        if (trim($cat_1)) {
            $cat1_record = ProductCategory::find()->where([
               "group_id" => $group_id,
               "name" => trim($cat_1),
            ])->andWhere("parent_id IS NULL")->one();
            if (!$cat1_record) {
                $cat1_record = new ProductCategory;
                $cat1_record->group_id = $group_id;
                $cat1_record->name = trim($cat_1);
                $cat1_record->parent_id = NULL;
                $cat1_record->save(false);
            }
            if (trim($cat_2)) {
                $cat2_record = ProductCategory::findOne([
                   "group_id" => $group_id,
                   "name" => trim($cat_2),
                   "parent_id" => $cat1_record->getPrimaryKey(),
                ]);
                if (!$cat2_record) {
                    $cat2_record = new ProductCategory;
                    $cat2_record->group_id = $group_id;
                    $cat2_record->name = trim($cat_2);
                    $cat2_record->parent_id = $cat1_record->getPrimaryKey();
                    $cat2_record->save(false);
                }
                if (trim($cat_3)) {
                    $cat3_record = ProductCategory::findOne([
                       "group_id" => $group_id,
                       "name" => trim($cat_3),
                       "parent_id" => $cat2_record->getPrimaryKey(),
                    ]);
                    if (!$cat3_record) {
                        $cat3_record = new ProductCategory;
                        $cat3_record->group_id = $group_id;
                        $cat3_record->name = trim($cat_3);
                        $cat3_record->parent_id = $cat2_record->getPrimaryKey();
                        $cat3_record->save(false);
                    }
                    if (trim($cat_4)) {
                        $cat4_record = ProductCategory::findOne([
                           "group_id" => $group_id,
                           "name" => trim($cat_4),
                           "parent_id" => $cat3_record->getPrimaryKey(),
                        ]);
                        if (!$cat4_record) {
                            $cat4_record = new ProductCategory;
                            $cat4_record->group_id = $group_id;
                            $cat4_record->name = trim($cat_4);
                            $cat4_record->parent_id = $cat3_record->getPrimaryKey();
                            $cat4_record->save(false);
                        }
                        $found_category = $cat4_record;
                    } else {
                        $found_category = $cat3_record;
                    }
                } else {
                    $found_category = $cat2_record;
                }
            } else {
                $found_category = $cat1_record;
            }
        }
        if ($found_category) {
            // Delete all previous category links
            ProductCategoryLink::deleteAll(
                'product_id = ' . strval($product_id)
            );
            // Add link
            $cat_link = new ProductCategoryLink;
            $cat_link->product_id = $product_id;
            $cat_link->product_category_id = $found_category->getPrimaryKey();
            $cat_link->save(false);
            return $found_category->getPrimaryKey();
        }
        return null;
    }
    
    private static function handle_product_group($product_id, $product_group_name, $properties = []) {
        $product = Product::findOne($product_id);
        $group_id = User::currentGroup()->id;
        if (trim($product_group_name)) {
            $product_group = ProductGroup::findOne([
                "group_id" => $group_id,
                "name" => trim($product_group_name),
            ]);
            if (!$product_group) {
                $product_group = new ProductGroup;
                $product_group->group_id = $group_id;
                $product_group->name = trim($product_group_name);
            }
            $props = [];
            foreach ($properties as $p) {
                if ($p && trim($p)) {
                    $props[] = trim($p);
                }
            }
            $product_group->props = json_encode($props);
            $product_group->save(false);
            $product->product_group_id = $product_group->getPrimaryKey();
            $product->save(false);
        }
    }
    
    private static function handle_unit($name) {
        $name = trim($name);
        $unit = Unit::findOne(["name" => $name]);
        if (!$unit) {
            $unit = Unit::findOne(["abbreviation" => $name]);
        }
        if (!$unit) {
            return Unit::findOne(["abbreviation" => "db"]); // db (default)
        }
        return $unit;
    }
    
    private static function simplified_import_products($data) {
        $errors = [];
        $success = 0;
        $group_id = User::currentGroup()->id;
        foreach ($data as $record_index => $record) {
            $model = null;
            if (self::field($record, 'cikkszam')) {
                $model = Product::findOne(['article_number' => self::field($record, 'cikkszam'), 'group_id' => $group_id]);
            }
            if (!$model && self::field($record, 'vonalkod')) {
                $model = Product::findOne(['bar_code' => self::field($record, 'vonalkod'), 'group_id' => $group_id]);
            }
            if (!$model && self::field($record, 'nev')) {
                // se vonalkód se cikkszám alapján nem található, így marad a név
                $model = Product::findOne(['name' => self::field($record, 'nev'), 'group_id' => $group_id]);
            }
            if (!$model) {
                $model = new Product;
            }
            $is_service = trim(strtolower(self::field($record, 'szolgaltatas_e')));
            $model->is_service = ($is_service === 'i' || $is_service === 'igen' || $is_service === '1' || $is_service === 'szolgaltatas') ? 1 : 0;
            $model->group_id = User::currentGroup()->id;
            $model->name = self::field($record, 'nev');
            $model->article_number = self::field($record, 'cikkszam');
            $model->bar_code = self::field($record, 'vonalkod');
            $model->net_unit_price = self::field($record, 'netto_egysegar');
            $model->vat = self::field($record, 'afakulcs', '0');
            $model->currency = self::field($record, 'penznem');
            $model->unit_id = self::handle_unit(self::field($record, 'mertekegyseg'))->id;
            $model->description = self::field($record, 'leiras');
            
            if (!$model->save()) {
                $errors[] = "Nem sikerült importálni a(z) " . strval($record_index + 1) . ". elemet a következő ok(ok)ból:<br>"
                    . self::errors_to_html($model->errors);
            } else {
                $success += 1;
                // Handle category
                self::handle_product_category(
                    $model->getPrimaryKey(),
                    self::field($record, 'fokategoria'),
                    self::field($record, 'alkategoria_1'),
                    self::field($record, 'alkategoria_2'),
                    self::field($record, 'alkategoria_3')
                );
                // Handle product group
                if (trim(self::field($record, 'termekcsoport'))) {
                    self::handle_product_group($model->getPrimaryKey(), trim(self::field($record, 'termekcsoport')), [
                        self::field($record, 'termekcsoport_tulajdonsag_1'),
                        self::field($record, 'termekcsoport_tulajdonsag_2'),
                        self::field($record, 'termekcsoport_tulajdonsag_3'),
                        self::field($record, 'termekcsoport_tulajdonsag_4'),
                        self::field($record, 'termekcsoport_tulajdonsag_5')
                    ]);
                }
            }
        }
        // errors to html
        $errors_html = "";
        if (count($errors) > 0) {
            $errors_html .= "<ul>";
            foreach ($errors as $e) {
                $errors_html .= "<li>" . $e . "</li>";
            }
            $errors_html .= "</ul>";
        }
        return [
            "error" => $errors_html,
            "success" => $success,
        ];
    }
    
    private static function export_table($model_name, $search_model_name) {
        $_model = new $model_name;
        $labels = $_model->attributeLabels();
        $keys = [];
        foreach ($labels as $key => $value) {
            $keys[] = $key;
        }
        $search_model = new $search_model_name;
        $models = $search_model->search([])->query->all();
        $all = [];
        foreach ($models as $model) {
            $record = [];
            foreach ($keys as $key) {
                if ($key !== "group_id") { // a group_id kitiltása
                    $record[$key] = $model->$key;
                }
            }
            $all[] = $record;
        }
        return $all;
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionImport()
    {
        return $this->render('import');
    }
    
    public function actionSimplifiedImport()
    {
        return $this->render('simplified_import');
    }
    
    public function actionExport()
    {
        return $this->render('export');
    }
    
    public function actionBackup() {
        return $this->render('backup');
    }
    
    public function actionExportData($table_index, $csv = "") {
        $table = ImportexportModule::$tables[$table_index];
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $json = self::export_table($table["model"], $table["search_model"]);
        $json = json_encode($json, JSON_PRETTY_PRINT);
        $json = str_replace("\n", "\r\n", $json); // újsorok átalakítása hogy Windowson új jó legyen
        
        if ($csv) {
            $separator = $csv === "csv_semicolon" ? ";" : ",";
            return \Yii::$app->response->sendContentAsFile(self::json_to_csv($json, $separator), $table["name"] . ".csv");
        } else {
            return \Yii::$app->response->sendContentAsFile($json, $table["name"] . ".json");
        }
    }
 
    public static function array2csv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
    {
        $f = fopen('php://memory', 'r+');
        fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($f, array_keys($data[0]), $delimiter);
        foreach ($data as $item) {
            fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
        }
        rewind($f);
        return stream_get_contents($f);
    }
    
    public static function csv2array($str, $separator = ",") {
        $bom = chr(0xEF) . chr(0xBB) . chr(0xBF);
        $str = str_replace($bom, "", $str);
        /*
        $bom = chr(0xEF) . chr(0xBB) . chr(0xBF);
        $str = str_replace($bom, "", $str);
        $str = explode("\n", $str);
        $csv = array_map('str_getcsv', $str);
        array_walk($csv, function(&$a) use ($csv) {
          $a = array_combine($csv[0], $a);
        });
        array_shift($csv); // remove column header
        */
        $Data = str_getcsv($str, "\n"); //parse the rows 
        $rows = [];
        foreach($Data as &$Row) { $rows[] = str_getcsv($Row, $separator); } //parse the items in rows 
        $array = [];
        $header = $rows[0];
        array_shift($rows); // remove column header
        
        foreach ($rows as $row) {
            $array[] = array_combine($header, $row);
            
        }
       
        return $array;
    }
    
    public static function json_to_csv($json, $separator) {
        $array = json_decode($json, true);
        return self::array2csv($array, $separator);
    }
    
    private static function csv_to_json($csv, $separator) {
        return json_encode(self::csv2array($csv, $separator));
    }
    
    public function actionImportData() {
        $table_index = Yii::$app->request->post("table_index", "");
        $operation = Yii::$app->request->post("operation", "create");
        $is_csv = Yii::$app->request->post("csv", "");
        $table = ImportexportModule::$tables[$table_index];
        //var_dump($is_csv);die();
        $file = UploadedFile::getInstanceByName("file");
        $content = file_get_contents($file->tempName);
        
        try {
        
            if ($is_csv) {
                $separator = $is_csv === "csv_semicolon" ? ";" : ",";
                $content = self::csv_to_json($content, $separator);
            }
            // var_dump($content);die();
            $data = json_decode($content, true);
           
            if (!$data) {
                Yii::$app->session->setFlash('error', 'A fájl formátuma nem megfelelő.');
                return $this->redirect(["/importexport/default/import"]);
            }
            
            $result = self::import_table($table["model"], $data, $operation, $table);
            if ($result["error"]) {
                Yii::$app->session->setFlash('error', $result["error"]);
                Yii::$app->session->setFlash('success', $result["success"]); // Hány elemet sikerült importálni
                return $this->redirect(["/importexport/default/import"]);
            }
            Yii::$app->session->setFlash('success', $result["success"]);
            return $this->redirect(["/importexport/default/import"]); // success
        
        } catch (\Exception $e) {
            //var_dump($e);die();
            Yii::$app->session->setFlash('error', 'Nem sikerült importálni az adatokat.');
            return $this->redirect(["/importexport/default/import"]); // success
        }
        
    }
    
    public function actionSimplifiedImportData() {
        $type = Yii::$app->request->post("type", "clients");
        $is_csv = Yii::$app->request->post("csv", "");
        //var_dump($is_csv);die();
        $file = UploadedFile::getInstanceByName("file");
        $content = file_get_contents($file->tempName);
        
        try {
        
            if ($is_csv) {
                $separator = $is_csv === "csv_semicolon" ? ";" : ",";
                $content = self::csv_to_json($content, $separator);
            }
            // var_dump($content);die();
            $data = json_decode($content, true);
           
            if (!$data) {
                Yii::$app->session->setFlash('error', 'A fájl formátuma nem megfelelő.');
                return $this->redirect(["/importexport/default/simplified-import"]);
            }
            
            if ($type === "clients") {
                $result = self::simplified_import_clients($data);
            } else if ($type === "products") {
                $result = self::simplified_import_products($data);
            } else {
                Yii::$app->session->setFlash('error', "Nem sikerült importálni az adatokat.");
                return $this->redirect(["/importexport/default/simplified-import"]);
            }
            if ($result["error"]) {
                Yii::$app->session->setFlash('error', $result["error"]);
                Yii::$app->session->setFlash('success', $result["success"]); // Hány elemet sikerült importálni
                return $this->redirect(["/importexport/default/simplified-import"]);
            }
            Yii::$app->session->setFlash('success', $result["success"]);
            return $this->redirect(["/importexport/default/simplified-import"]); // success
        
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Nem sikerült importálni az adatokat.');
            return $this->redirect(["/importexport/default/import"]); // success
        }
        
    }
    
    public function actionBackupData() {
        $json = [];
        foreach (ImportexportModule::$tables as $index => $table) {
            $json[$table['model']] = self::export_table($table["model"], $table["search_model"]);
        }
        $json = json_encode($json, JSON_PRETTY_PRINT);
        $json = str_replace("\n", "\r\n", $json);
        return \Yii::$app->response->sendContentAsFile($json, "biztonsagi-mentes-" . date('Y-m-d') . ".json");
    }
    
    public function actionChangeBackupText() {
        $settings = Settings::findOne(1);
        $settings->value = Yii::$app->request->post('text', '');
        $settings->save(false);
        return $this->redirect(["/importexport/default/backup"]);
    }
}
