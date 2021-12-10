<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{

	public function actionIndex()
	{
		// login
		return $this->redirect(Url::to(["/users/users/home"]));
	}


	public function actionError()
	{
		$exception = Yii::$app->errorHandler->exception;

		if($exception->statusCode !== 404) {
			$this->layout = "@app/themes/main/layouts/main";
			return $this->render('error', ['exception' => $exception, '_SERVER' => $_SERVER]);
		}
		if ($exception->statusCode === 404)
		{
			$this->layout = "@app/themes/main/site/siteError";
			return $this->render('error', ['exception' => $exception, '_SERVER' => $_SERVER]);
		}
	}
}
