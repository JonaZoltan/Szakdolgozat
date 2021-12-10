<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

use app\modules\users\models\PermissionSet;
use app\modules\users\models\Capability;

use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\users\models\Permission */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-form">

    <?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>
    
    <?= $form->field($model, 'permission_set_id')->widget(Select2::classname(), [
        'data' => PermissionSet::allGroupPermissionSetNames(true),
        'language' => 'hu',
        'options' => ['placeholder' => Yii::t('app', 'switch')],
        'pluginOptions' => [
            'allowClear' => false,
            'multiple' => false,
        ],
    ]);
    ?>

    <?= $form->field($model, 'capability_id')->widget(Select2::classname(), [
        'data' => Capability::allCapabilityNames(true),
        'language' => 'hu',
        'options' => ['placeholder' => Yii::t('app', 'switch')],
        'pluginOptions' => [
            'allowClear' => false,
            'multiple' => false,
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
