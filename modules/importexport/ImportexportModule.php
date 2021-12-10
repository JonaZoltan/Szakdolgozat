<?php

namespace app\modules\importexport;

use app\modules\users\models\UsersSearch;


/**
 * importexport module definition class
 */
class ImportexportModule extends \yii\base\Module
{
    public static $tables = [
        // BASE
        /*
        [
            "name" => "Csoportok",
            "model" => 'app\modules\groups\models\Group',
            'search_model' => 'app\modules\groups\models\GroupSearch',
            'module' => 'base',
        ],
        [
            "name" => "Előfizetések",
            "model" => 'app\modules\groups\models\Subscription',
            'search_model' => 'app\modules\groups\models\SubscriptionSearch',
            'module' => 'base',
        ],
        [
            "name" => "Tagságok",
            "model" => 'app\modules\groups\models\Membership',
            'search_model' => 'app\modules\groups\models\MembershipSearch',
            'module' => 'base',
        ],
        */
        [
            "name" => "Felhasználók",
            "model" => 'app\modules\users\models\User',
            'search_model' => 'app\modules\users\models\UserSearch',
            "excluded_from_import" => [],
            'module' => 'base',
        ],
        [
            "name" => "Munkamenetek",
            "model" => 'app\modules\users\models\Login',
            'search_model' => 'app\modules\users\models\LoginSearch',
            "excluded_from_import" => [],
            'module' => 'base',
        ],
        [
            "name" => "Jogosultságok",
            "model" => 'app\modules\users\models\Permission',
            'search_model' => 'app\modules\users\models\PermissionSearch',
            "excluded_from_import" => [],
            'module' => 'base',
        ],
        [
            "name" => "Jogosultsági körök",
            "model" => 'app\modules\users\models\PermissionSet',
            'search_model' => 'app\modules\users\models\PermissionSetSearch',
            "excluded_from_import" => [],
            'module' => 'base',
        ],
        /*
        [
            "name" => "Képességek",
            "model" => 'app\modules\users\models\Capability',
            'search_model' => 'app\modules\users\models\CapabilitySearch',
            'module' => 'base',
        ],
        */
        
        // CRM
        [
            "name" => "Ügyfelek",
            "model" => 'app\modules\clients\models\Client',
            'search_model' => 'app\modules\clients\models\ClientSearch',
            "excluded_from_import" => ["number"],
            'module' => 'crm',
        ],
        [
            "name" => "Ügyfel jellemzők",
            "model" => 'app\modules\clients\models\ClientAttribute',
            'search_model' => 'app\modules\clients\models\ClientAttributeSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        [
            "name" => "Ügyfel kontaktok",
            "model" => 'app\modules\clients\models\ClientContact',
            'search_model' => 'app\modules\clients\models\ClientContactSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        
        [
            "name" => "Beszélgetések",
            "model" => 'app\modules\conversations\models\Conversation',
            'search_model' => 'app\modules\conversations\models\ConversationSearch',
            "excluded_from_import" => ["number"],
            'module' => 'crm',
        ],
        [
            "name" => "Üzenetek",
            "model" => 'app\modules\conversations\models\ConversationMessage',
            'search_model' => 'app\modules\conversations\models\ConversationMessageSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        
        [
            "name" => "Kontaktok",
            "model" => 'app\modules\contacts\models\Contact',
            'search_model' => 'app\modules\contacts\models\ContactSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        [
            "name" => "Elérhetőségek",
            "model" => 'app\modules\contacts\models\ContactData',
            'search_model' => 'app\modules\contacts\models\ContactDataSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        
        [
            "name" => "Termékek",
            "model" => 'app\modules\products\models\Product',
            'search_model' => 'app\modules\products\models\ProductSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        [
            "name" => "Termékekcsoportok",
            "model" => 'app\modules\products\models\ProductGroup',
            'search_model' => 'app\modules\products\models\ProductGroupSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        [
            "name" => "Készlet nyilvántartás",
            "model" => 'app\modules\products\models\Stock',
            'search_model' => 'app\modules\products\models\StockSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        
        [
            "name" => "Termékkategóriák",
            "model" => 'app\modules\productcategories\models\ProductCategory',
            'search_model' => 'app\modules\productcategories\models\ProductCategorySearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        [
            "name" => "Termékkategória kapcsolatok",
            "model" => 'app\modules\productcategories\models\ProductCategoryLink',
            'search_model' => 'app\modules\productcategories\models\ProductCategoryLinkSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        
        [
            "name" => "Kedvezmények",
            "model" => 'app\modules\products\models\DiscountRule',
            'search_model' => 'app\modules\products\models\DiscountRuleSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],

        [
            "name" => "Értékesítési folyamatok",
            "model" => 'app\modules\documents\models\SalesProcess',
            'search_model' => 'app\modules\documents\models\SalesProcessSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],        
        [
            "name" => "Bizonylatok",
            "model" => 'app\modules\documents\models\CommercialDocument',
            'search_model' => 'app\modules\documents\models\CommercialDocumentSearch',
            "excluded_from_import" => ["number"],
            'module' => 'crm',
        ],
        [
            "name" => "Bizonylat tételek",
            "model" => 'app\modules\documents\models\CommercialDocumentItem',
            'search_model' => 'app\modules\documents\models\CommercialDocumentItemSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        
        [
            "name" => "Helyek",
            "model" => 'app\modules\locations\models\ClientLocation',
            'search_model' => 'app\modules\locations\models\ClientLocationSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        [
            "name" => "Létesítmények",
            "model" => 'app\modules\locations\models\Facility',
            'search_model' => 'app\modules\locations\models\FacilitySearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        
        [
            "name" => "Címek",
            "model" => 'app\modules\addresses\models\Address',
            'search_model' => 'app\modules\addresses\models\AddressSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        [
            "name" => "Települések",
            "model" => 'app\modules\addresses\models\Settlement',
            'search_model' => 'app\modules\addresses\models\SettlementSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        [
            "name" => "Kampány",
            "model" => 'app\modules\clients\models\Campaign',
            'search_model' => 'app\modules\clients\models\CampaignSearch',
            "excluded_from_import" => [],
            'module' => 'crm',
        ],
        
        // PRODUCTION
        [
            "name" => "Gépek",
            "model" => 'app\modules\machines\models\Machine',
            'search_model' => 'app\modules\machines\models\MachineSearch',
            "excluded_from_import" => [],
            'module' => 'production',
        ],
        [
            "name" => "Szenzoros adatok",
            "model" => 'app\modules\machines\models\StrobyAdatgyujto',
            'search_model' => 'app\modules\machines\models\StrobyAdatgyujtoSearch',
            "excluded_from_import" => [],
            'module' => 'production',
        ],
    ];
    
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\importexport\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
