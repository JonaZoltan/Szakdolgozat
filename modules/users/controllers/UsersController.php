<?php

namespace app\modules\users\controllers;

use app\modules\users\models\forms\QuickMenuForm;
use Yii;
use app\modules\users\models\User;
use app\modules\groups\models\Membership;
use app\modules\users\models\UserSearch;
use app\modules\users\models\Login;
use app\controllers\BaseController;
use yii\caching\Cache;
use yii\helpers\Json;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\modules\users\models\forms\LoginForm;
use app\modules\users\models\forms\ForgotPasswordForm;
use app\modules\users\models\forms\SettingsForm;
use app\modules\users\models\forms\ResetPasswordForm;

use app\modules\logs\models\Log;

use app\components\Helpers;

use app\modules\groups\models\Group;
use yii\web\UploadedFile;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends BaseController
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

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'foreColor' => 0x146082
            ],
        ];
    }

	private static function handle_uploaded_photo($model) {
		$image = UploadedFile::getInstance($model, 'imageFile');
		//var_dump($image);die();
		if (!empty($image)) {

			$exploded = explode(".", $image->name);
			$extension = end($exploded);
			$filename = strval($model->id) . "." . $extension;
			$image->saveAs('uploads/users/' . $filename);
			return $filename;
		}
		return NULL;
	}

	public function actionDeletePhoto()
	{
		$key = Yii::$app->request->post("key", "");
		if ($key) {
			$file_path = 'uploads/users/' . $key . ".jpg";
			if (file_exists($file_path)) {
				unlink($file_path);
			}

		}
		return "{}"; // empty json response
	}

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin() {
        $this->layout = '@app/themes/main/layouts/login_layout';
        
        $user = User::current();
        if ($user) {
            return $this->redirect(["/users/users/home"]);
        }
        
        $model = new LoginForm;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = $model->login(!!intval($model->remember));
	        Log::add("felhasznalo.bejelentkezes", [
		        "nev" => $user->name,
		        "email" => $user->email,
	        ]);


            return $this->redirect(["/users/users/home"]);
        }
        
        return $this->render('login', [
            "model" => $model,
        ]);
    }
    
    public function actionHome() {

        $user = User::current();
        if (!$user) {
            return $this->redirect(["/users/users/login"]);
        }

        return $this->render('home');
    }

    public function actionHash() {
    	$userId = 1;
	    $user = User::findOne($userId);
	    $user->password_hash = password_hash("123456", PASSWORD_DEFAULT);

	    var_dump($user->password_hash);

	    echo "UPD";
    }

    public function actionResetPassword($token) {
        $this->layout = '@app/themes/main/layouts/login_layout';
        
        $model = new ResetPasswordForm;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // login
            $decrypted = Helpers::decrypt($model->token);
            $user = User::findOne($decrypted["user_id"]);
            $user->password_hash = password_hash($model->password, PASSWORD_DEFAULT);
            $user->save(false);
            Yii::$app->session->setFlash('success', "true");
            return $this->render("resetpassword");
        }
        
        return $this->render('resetpassword', [
            "model" => $model,
            'token' => $token,
        ]);
    }
    
    public function actionLogoutPage() {
        $user = User::current();
        if (!$user) {
            return $this->redirect(["/users/users/login"]);
        }
        $this->layout = '@app/themes/main/layouts/login_layout';
        return $this->render("logout");
    }
    
    public function actionLogout() {
        $this->layout = '@app/themes/main/layouts/login';
        $user = User::current();
        if ($user) {
        	if (!$user->is_admin) {
		        Log::add("felhasznalo.kijelentkezes", [
			        "nev" => $user->name,
			        "email" => $user->email,
		        ]);
	        }
            $user->logout();
        }
    }

	/**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$this->userCanRedirect('users');

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = User::current();
        
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
        	if($model->save()) {
		        self::handle_uploaded_photo($model);
		        Log::add("felhasznalo.uj", [
			        "felhasznalo" => $user->name,
			        "email" => $model->email,
		        ]);

		        // Jelszóbeállító e-mail küldése a felhasználónak
		        self::sendPasswordResetEmail($model->getPrimaryKey(), true); // első email, ezért GDPR csatolva

		        return $this->redirect(['index']);
	        }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    private static function sendPasswordResetEmail($user_id, $GDPR = false) {
        $user = User::findOne($user_id);

        if ($user) {
            $message = "Tisztelt " . $user->name . "<br><br>";
            $message .= "Az alábbi linkre kattintva állíthat be jelszót a Szitár-fiókjához.<br><br>";
            $message .= '<a href="' . Yii::$app->urlManager->createAbsoluteUrl([
                '/users/users/reset-password',
                'token' => Helpers::encrypt([
                    "type" => "reset_password",
                    "expiration" => time() + 60 * 60 * 24, // 24 hour
                    "user_id" => $user->getPrimaryKey(),
                ])
            ]) . ($GDPR ? '&gdpr=1' : '') . '" target="_blank">Jelszó beállítása</a><br><br>';
            $message .= "Üdvözlettel:<br>";
            $message .= "<b>Szitár</b>";
            Helpers::email($user->email, "Jelszó beállítása", $message, Helpers::PRIORITY_HIGH, $GDPR ? Yii::getAlias('@app') . '/pdf/adatkezelesi-tajekoztato.pdf' : null);
        }
    }
    
    public function actionSendPasswordResetEmail() {
        $user_id = Yii::$app->request->post("user_id", "");
        self::sendPasswordResetEmail($user_id);
    }
    
    public function actionSettings() {
        $user = User::current();
        $model = new SettingsForm;
        $model->name = $user->name;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->name = $model->name;
            $user->save(false);
            if ($model->password) {
                $user->password_hash = password_hash($model->password, PASSWORD_DEFAULT);
                $user->save(false);
                Yii::$app->session->setFlash('password_changed', "true");
            }
            return $this->redirect(["settings"]);
        }
        
        return $this->render("settings", [
            "model" => $model,
        ]);
    }

	public function actionQuickMenu() {
    	$user = User::current();
		$model = new QuickMenuForm;
		$model->quickmenu = $user->quickmenu;

		if ($model->load(Yii::$app->request->post())) {

			$save_json = empty($model->quickmenu) ? null : Json::encode($model -> quickmenu);
			$user->quickmenu = $save_json;

			if($user->save(false)) {
				Yii::$app->session->setFlash('json_saved', "true");
				Yii::$app->cache->flush();
			}
			return $this->redirect(["quick-menu"]);
		}

		return $this->render("quickmenu", [
			"model" => $model,
		]);
	}

	public function actionForgotPassword() {
        $this->layout = '@app/themes/main/layouts/login_layout';
        
        $model = new ForgotPasswordForm;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->session->setFlash('sent', "true");
            self::sendPasswordResetEmail(User::findOne(["email" => $model->email])->id);
            return $this->redirect(["forgot-password"]);
        }
        
        return $this->render("forgotpassword", [
            "model" => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
	        if($model->save()) {
		        self::handle_uploaded_photo($model);

		        return $this->redirect(['view', 'id' => $model->id]);
	        }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionBackToHomepage() {
		$user = User::current();
		$user->homepage_mode = 'normal';
		$user->save(false);

		Yii::$app->cache->flush();

		return $this->redirect("/users/users/home");
	}
}
