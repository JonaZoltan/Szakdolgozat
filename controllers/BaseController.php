<?php

namespace app\controllers;

use app\modules\users\models\Capability;


use yii\web\Controller;
use yii\web\YiiAsset;

use Yii;

class BaseController extends Controller {
	use userCan;


	/**
	 * @var Capability from tablbe
	 */
	public $capabilities;
	/**
	 * @var
	 */
	public $userData;

	/**
	 * @var
	 */
	public $is_admin;
	/**
	 * @var
	 */
	public $name;


	/**
	 *
	 */
	public function init() {
    	YiiAsset::register(Yii::$app->view);
        parent::init();
        $this->layout = "@app/themes/main/layouts/main";
        $this->enableCsrfValidation = false;
		$this->userData = Yii::$app->getRequest()->getCookies()->getValue('user_data');
		if($this->userData) {
			$this->name = $this->userData['name'];
			$this->is_admin = $this->userData['is_admin'];
			if(!$this->userData['is_admin']) {
				$this->capabilities = $this->getCapabilities();
			}
		}

		/*if((Yii::$app->getRequest()->getCookies()->getValue('login') == null || Yii::$app->getRequest()->getCookies()->getValue('user_data') == null) && $this->id !== "migrate") {
			header("Location: /");
		}*/
	}
    

}