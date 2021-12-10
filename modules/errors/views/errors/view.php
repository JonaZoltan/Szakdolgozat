<?php

use app\modules\errors\models\ErrorReportingMessage;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

use app\modules\errors\models\ErrorReporting;

/* @var $this yii\web\View */
/* @var $model app\modules\errors\models\ErrorReporting */

$this->title = Yii::t('app', 'error_report').' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'error_reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-reporting-view">
    <h1><?= Html::encode($this->title) ?></h1>

	<?php if (Yii::$app->session->getFlash('success_error_report')): ?>
        <div class="alert alert-success" role="alert"><i class="fas fa-check-circle"></i> <?= Yii::t('app', 'report_recorded') ?></div>
	<?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            [
                'attribute' => 'subject',
                'value' => function ($model) {
                    return $model->subjectModel->name;
                }
            ],
            'user_agent',
        ],
    ]) ?>
</div>

<style>
    .message-container {
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .messages {
        padding: 10px;
    }

    .messages .sender {
        border-bottom: 1px solid #e0e0e0;
    }

    .message-owner {
        background-color: #f9f9f9;
    }
</style>

<?php $messages = $model->messages; ?>
<?php if($messages): ?>

    <div class="message-container">
        <?php foreach ($messages as $message): ?>
	        <?php /** @var $message ErrorReportingMessage */ ?>
            <?php
	            $addClass = "";
                if($message->user->id != $this->context->userData['id'])
                    $addClass = "message-owner";
            ?>

            <div class="messages <?= $addClass ?>">
                <p class="sender">
                    <?= Html::a($message->user->name, Url::toRoute(['/users/users/view', 'id' => $message->user->id]), ['target' => '_blank']) ?>
                    <small>- <?= $message->timestamp ?></small>
                </p>
                <p class="text">
                    <?= $message->text ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<div class="error-reporting-subject-form">

    <?php
    $error_reporting_model = new ErrorReporting;
    $form = ActiveForm::begin([
        'action' => Url::to(['/errors/errors/create'])
    ]); ?>
    
    <input type="hidden" name="reply" value="<?=$model->id?>" />
    
    <?php if (Yii::$app->session->getFlash('success', '')): ?>
    <div class="alert alert-success" role="alert"><i class="fas fa-check-circle"></i> <?= Yii::t('app', 'email_sent', ['email' => $model->user->email]) ?></div>
    <?php endif; ?>
    
    <?= $form->field($error_reporting_model, 'subject')->hiddenInput(['value'=>1])->label(false) ?>

    <?= $form->field($error_reporting_model, 'message')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($error_reporting_model->isNewRecord ? Yii::t('app', 'send_reply') : Yii::t('app', 'send_reply'), ['class' => $error_reporting_model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
