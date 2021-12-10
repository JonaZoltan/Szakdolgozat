<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\users\models\User;
use app\modules\groups\models\Group;

use app\modules\users\models\Capability;

use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\users\models\PermissionSet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-set-form">

    <?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php /* $form->field($model, 'user_id')->widget(Select2::classname(), [
        'data' => User::allGroupUserNames($model->isNewRecord ? User::currentGroup()->id : $model->group->id, true),
        'language' => 'hu',
        'options' => ['placeholder' => 'Válasszon felhasználót ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => false,
        ],
    ]); */
    ?>

    <div class="form-group">
        <label><?= Yii::t('app', 'capability') ?></label>
        <?= Select2::widget([
            'name' => 'capabilities',
            'data' => Capability::allCapabilityNames(true),
            'value' => $model->isNewRecord ? [] : $model->capabilityIds,
            'language' => 'hu',
            'options' => ['placeholder' => Yii::t('app', 'switch')],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => true,
            ],
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
