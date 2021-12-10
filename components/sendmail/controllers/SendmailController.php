<?php

namespace app\components\sendmail\controllers;

use app\modules\logs\models\Log;
use Yii;
use app\components\Sendmail;
use app\components\sendmail\models\SearchSendmail;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\Response;

/**
 * SendmailController implements the CRUD actions for Sendmail model.
 */
class SendmailController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            /*'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => Yii::$app->user->can('admin'),
                    ],
                ],
            ],*/
        ];
    }

    /**
     * Lists all Sendmail models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new SearchSendmail();

        /** Session search */
        $session = Yii::$app->session;
        $params = Yii::$app->request->queryParams;
        if (!$params) $params = $session[ $searchModel::tableName() ]; else $session[ $searchModel::tableName() ] = $params;
        $dataProvider = $searchModel->search($params);
        /** Session search */

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	/**
	 * @param $id
	 * @return \yii\web\Response
	 */
    public function actionResend($id): Response
    {
		$model = Sendmail::findOne($id);
		if($model) {
			$newModel = new Sendmail();
			$newModel->setAttributes($model->getAttributes());
			$newModel->status = null;
			$newModel->completed_time = null;
			$newModel->timestamp = date('Y-m-d H:i:s');
			if($newModel->save()) {
				Yii::$app->session->setFlash('success_resend', true);
			}
		}

    	return $this->redirect('index');
    }
}
