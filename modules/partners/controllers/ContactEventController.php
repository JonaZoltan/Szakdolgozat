<?php

namespace app\modules\partners\controllers;

use app\modules\logs\models\Log;
use app\modules\partners\models\Contact;
use app\modules\partners\models\Partners;
use app\modules\project\models\Project;
use app\modules\tasks\models\Tasks;
use Yii;
use app\modules\partners\models\ContactEvent;
use app\modules\partners\models\SearchContactEvent;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactEventController implements the CRUD actions for ContactEvent model.
 */
class ContactEventController extends BaseController
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
     * Lists all ContactEvent models.
     * @return mixed
     */

    public function actionIndex()
    {
        if(!$this->userCan('view_'.ContactEvent::tableName().''))
            return $this->redirect("/");

        $searchModel = new SearchContactEvent();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContactEvent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->userCan('view_'.ContactEvent::tableName().''))
            return $this->redirect("/");

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ContactEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.ContactEvent::tableName().''))
            return $this->redirect("/");

        $model = new ContactEvent();

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                Log::add("".ContactEvent::tableName().".create", Log::getAddParameters($model, ContactEvent::tableName()));

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ContactEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->userCan('update_'.ContactEvent::tableName().''))
            return $this->redirect("/");

        $model = $this->findModel($id);
        $task = Tasks::findOne($model->task_id);

        if ($model->load(Yii::$app->request->post())) {
            Log::add("".ContactEvent::tableName().".update", Log::getParameters($model, ContactEvent::tableName(), "Milyenadatotmodositott")); // TODO: Log update modositas neve(line name, labors name etc...)

            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
	            'task' => $task
            ]);
        }
    }

    /**
     * Deletes an existing ContactEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->userCan('delete_'.ContactEvent::tableName().''))
            return $this->redirect("/");

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ContactEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContactEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContactEvent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionGetContactData($id){
    	$contact = Contact::findOne($id);
		$partner = Partners::findOne($contact->partner_id);
		$lastContactEvent = $partner->lastContactEvent;
    	return $this->renderAjax('_contact_data', [
		    'contact' => $contact,
		    'partner' => $partner,
		    'lastContactEvent' => $lastContactEvent
	    ]);
    }
}
