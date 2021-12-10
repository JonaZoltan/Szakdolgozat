<?php

namespace app\modules\settings\controllers;

use app\modules\settings\models\SettingsForm;
use app\modules\settings\models\Settings;

use app\controllers\BaseController;

use Yii;

/**
 * Default controller for the `settings` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new SettingsForm;
        
        foreach (["smtp_name","smtp_address","smtp_host","smtp_port",
                  "smtp_security","smtp_username","smtp_password", "version"] as $attr) {
            $model->$attr = Settings::getByName($attr);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach (["smtp_name","smtp_address","smtp_host","smtp_port",
                      "smtp_security","smtp_username","smtp_password", "version"] as $attr) {
                $model->$attr = Settings::setByName($attr, $model->$attr);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionImpressum() {
        return $this->render('impressum', [
            
        ]);
    }
    
    public function actionUpdateImpressum() {
        $text = Yii::$app->request->post("text", "");
        Settings::setByName("impressum", $text);
        return $this->redirect(["/settings/default/impressum"]);
    }
}
