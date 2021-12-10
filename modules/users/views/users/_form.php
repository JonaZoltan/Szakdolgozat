<?php

use app\modules\worktimes\models\RfidLog;
use kartik\file\FileInput;
use kartik\select2\Select2;

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

use app\modules\users\models\PermissionSet;

use app\modules\users\models\User;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\users\models\User */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<style type="text/css">
    .select2-selection {
        min-height: 55px;
    }
</style>

<div class="user-form">

    <?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>

    <div class="row">
        <div class="col-sm-12">
	        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
	        <?= $form->field($model, 'imageFile')->widget(FileInput::classname(), [
		        'options'=> [
			        'accept'=>['image/jpeg'],
			        // 'multiple'=>true
		        ],
		        'pluginOptions'=>[
			        'allowedFileExtensions'=>['jpg','jpeg','JPG','JPEG'],
			        'showRemove' => true,
			        'showUpload' => false,
			        'showCaption' => false,
			        'fileActionSettings' => [
				        'showZoom' => false,
				        'showRemove' => false,
				        'showDrag' => false
			        ],
			        'initialPreviewConfig' => $model->hasPhoto() ? [
				        [
					        'url' => '/users/users/delete-photo',
					        'key' => $model->id
				        ]
			        ] : [],
			        'initialPreview'=> $model->hasPhoto() ? [$model->photoUrl()] : [],
			        'initialPreviewAsData'=>true,
		        ]
	        ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
	        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
	        <?= $form->field($model, 'permission_set_id')->widget(Select2::classname(), [
		        'data' => PermissionSet::allGroupPermissionSetNames(true),
		        'language' => 'hu',
		        'options' => ['placeholder' => Yii::t('app', 'switch')],
		        'pluginOptions' => [
			        'allowClear' => true,
			        'multiple' => false,
		        ],
	        ]);
	        ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
	        <?= $form->field($model, 'rfid')->textInput(["maxlength" => true]) ?>
        </div>
    </div>

	<?php

        $unregCards = RfidLog::find()
            ->leftJoin('user', 'user.rfid = rfid_log.rfid')
            ->where(["user.id" => null])
            ->orderBy('timestamp ASC')
            ->indexBy('rfid')->asArray()
            ->all();

        $cards = [];
	    foreach ($unregCards as $rfid => $unregCard) {
            $cards[$rfid] = "<b>".$unregCard['rfid']."</b><br /><small>@ ".$unregCard['timestamp']."</small>";
        }

	?>
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label">Nem regisztrált kártyák:</label>
	        <?= Select2::widget([
	            'id' => 'unregCard',
	            'name' => 'unregCard',
		        'data' => $cards,
		        'options' => ['placeholder' => Yii::t('app', 'switch')],
		        'pluginOptions' => [
			        'allowClear' => true,
			        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		        ],
	        ]);
	        ?>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-sm-12">
	        <?= $form->field($model, 'suspended')->checkbox() ?>
        </div>
    </div>
    
    <?php if (User::current()->is_admin): ?>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'is_admin')->checkbox() ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $('#unregCard').change(function() {
        var rfid = $(this).val();
        var userRfid = $('#user-rfid').val();

        if(!userRfid || (userRfid && rfid && confirm("Biztosan átcseréled az RFID-t?"))) {
            $('#user-rfid').val(rfid);
        } else {
            $('#unregCard').val('');
        }
    });
</script>
