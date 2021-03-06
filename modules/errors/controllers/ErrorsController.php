<?php

namespace app\modules\errors\controllers;

use Yii;
use app\modules\errors\models\ErrorReporting;
use app\modules\errors\models\ErrorReportingSearch;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\components\Helpers;
use app\modules\users\models\User;
use app\modules\settings\models\Settings;

/**
 * ErrorsController implements the CRUD actions for ErrorReporting model.
 */
class ErrorsController extends BaseController
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
        ];
    }

    /**
     * Lists all ErrorReporting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ErrorReportingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ErrorReporting model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ErrorReporting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
	public function actionCreate()
	{
		$model = new ErrorReporting();
		$user = User::current();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if (isset($_POST["reply"]) && $_POST["reply"]) {
				$error_reporting = ErrorReporting::findOne($_POST["reply"]);
				if ($error_reporting) {
					$message = $model->message;
					$message = str_replace("\n", "<br>", $message);
					Helpers::email($error_reporting->user->email, "V??lasz a #" . $error_reporting->id . " sz??m?? hibajelent??sre", $message);
					Helpers::email(Settings::getByName("smtp_address"), "V??lasz a #" . $error_reporting->id . " sz??m?? hibajelent??sre", $message);
				}
				Yii::$app->session->setFlash('success', 'true');
				return $this->redirect(['/errors/errors/view', 'id' => $error_reporting->id]);
			} else {
				$model->save();
				$message = "Tisztelt " . Settings::getByName("smtp_name") . "<br><br>";
				$message .= $user->name . " nev?? felhaszn??l?? hibajelent??st k??ldt??tt be.<br>";
				$message .= 'A hibajelent??s tartalm??t az al??bbi linken tekintheti meg:<br><br>';
				$message .= '<a href="http://'.$_SERVER['HTTP_HOST'].'/errors/errors/view?id=' . $model->id . '">Hibajelent??s megtekint??se</a>'; // TODO: ??les eset??n weblink
				$message .= '<br><br>';
				$message .= "??dv??zlettel:<br>";
				$message .= "<b>Szit??r</b>";

				//var_dump(Settings::getByName("smtp_address")); die();
				Helpers::email(Settings::getByName("smtp_address"), "[yii_base] Hibajelent??s #" . $model->id, $message);
				//Helpers::email('gabor.marki@szitar.hu', "Hibajelent??s #" . $model->id, $message);
				return $this->redirect(['thanks', 'id' => $model->id]);
			}
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

    /**
     * Creates a new ErrorReporting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionThanks($id)
    {
        return $this->render('thanks', [
            "id" => $id,
        ]);
    }
    
    /**
     * Updates an existing ErrorReporting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ErrorReporting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ErrorReporting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ErrorReporting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ErrorReporting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
