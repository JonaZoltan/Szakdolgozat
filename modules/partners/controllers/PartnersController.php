<?php

namespace app\modules\partners\controllers;

use app\components\Model;
use app\modules\logs\models\Log;
use app\modules\partners\models\Contact;
use Yii;
use app\modules\partners\models\Partners;
use app\modules\partners\models\SearchPartners;
use app\controllers\BaseController;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnersController implements the CRUD actions for Partners model.
 */
class PartnersController extends BaseController
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
     * Lists all Partners models.
     * @return mixed
     */

    public function actionIndex()
    {
        if(!$this->userCan('view_'.Partners::tableName().''))
            return $this->redirect("/");

        $searchModel = new SearchPartners();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Partners model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
	    $model = $this->findModel($id);
	    $contact = $model->contact;
	    $modelContact = !empty($contact) ? $contact : [new Contact()];

        if(!$this->userCan('view_'.Partners::tableName().''))
            return $this->redirect("/");

        return $this->render('view', [
            'model' => $model,
	        'modelContact' => $modelContact
        ]);
    }

    /**
     * Creates a new Partners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.Partners::tableName().''))
            return $this->redirect("/");

        $model = new Partners();
	    $modelContact = array(new Contact());

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                Log::add("".Partners::tableName().".create", Log::getAddParameters($model, Partners::tableName()));

                self::handleContact($model, $modelContact);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
	            'modelContact' => $modelContact
            ]);
        }
    }

    /**
     * Updates an existing Partners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->userCan('update_'.Partners::tableName().''))
            return $this->redirect("/");

        $model = $this->findModel($id);
		$contact = $model->contact;
        $modelContact = !empty($contact) ? $contact : [new Contact()];

        if ($model->load(Yii::$app->request->post())) {
           //Log::add("".Partners::tableName().".update", Log::getParameters($model, Partners::tableName(), "Milyenadatotmodositott")); // TODO: Log update modositas neve(line name, labors name etc...)

            if($model->save()) {

	            self::handleContact($model, $modelContact);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
	            'modelContact' => $modelContact
            ]);
        }
    }

    /**
     * Deletes an existing Partners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->userCan('delete_'.Partners::tableName().''))
            return $this->redirect("/");

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Partners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Partners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Partners::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	/**
	 * A kapcsolatok CRUD szerinti megfelelő kezelésére szolgál
	 * @param $model
	 * @param $modelContact
	 */
	private static function handleContact($model, $modelContact) : void {
		$oldIds = ArrayHelper::map($modelContact, 'id', 'id');
		$modelContact = Model::createMultiple(Contact::class, $modelContact);
		Model::loadMultiple($modelContact, Yii::$app->request->post());
		$deletedIds = array_diff($oldIds, array_filter(ArrayHelper::map($modelContact, 'id', 'id')));

		if(!empty($deletedIds))
			Contact::deleteAll(['id' => $deletedIds]);
		foreach ($modelContact as $contact) {
			$contact->partner_id = $model->id;
			$contact->save();
		}
	}
}
