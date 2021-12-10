<?php

use app\modules\partners\models\Contact;
use app\modules\partners\models\ContactEvent;
use app\modules\users\models\User;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\partners\models\ContactEvent */
/* @var $form yii\widgets\ActiveForm */
/* @var $task app\modules\tasks\models\Tasks */
?>
<style>
    .field-contactevent-contact_id .select2-selection {
        min-height: 55px;
    }
</style>
<div class="contact-event-form">

    <?php $form = ActiveForm::begin([
        'encodeErrorSummary' => false,
    ]); ?>

	<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <div class="card card-outline card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
	                <?= $form->field($model, 'contact_id')->widget(Select2::class, [
		                'data' => Contact::allContactNameWithPartnerName(true, $task->project_id??null),
		                'language' => 'hu',
		                'options' => ['placeholder' => Yii::t('app', 'switch')],
                        'pluginOptions' => [
	                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }')
                        ]
	                ]);
	                ?>
                </div>
                <div class="col-sm-6">
	                <?= $form->field($model, 'type')->widget(Select2::class, [
		                'data' => ContactEvent::allTypeNames(),
		                'language' => 'hu',
		                'options' => ['placeholder' => Yii::t('app', 'switch')]
	                ]);
	                ?>
                </div>
            </div>

            <div id="contact-data"></div>

            <div class="row">
                <div class="col-sm-12">
	                <?= $form->field($model, 'when')->widget(DateTimePicker::classname(), [
		                'options' => ['placeholder' => 'Esemény ideje'],
		                'readonly' => true,
		                'pluginOptions' => [
			                'endDate' => date('Y-m-d H:i'),
			                'autoclose' => true
		                ]
	                ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
	                <?= $form->field($model, 'note')->textarea(['data-summernote' => true, 'rows' => 6])  ?>
                </div>
            </div>



        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).on('change', '#contactevent-contact_id', function(){
        var contactId = $(this).val();
        $.get({
            url: '/partners/contact-event/get-contact-data',
            data: {
                id: contactId,
            },
            success: function(data){
                $("#contact-data").html(data);
            }
        })
    })
</script>