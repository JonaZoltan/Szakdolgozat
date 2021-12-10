<?php

use app\modules\project\models\Project;
use app\modules\users\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\project\models\ProjectMembership */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="project-membership-form">

    <?php $form = ActiveForm::begin([
        'encodeErrorSummary' => false,
    ]); ?>

	<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <div class="card card-outline card-primary">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6">
	                <?= $form->field($model, 'user_id')->widget(Select2::class, [
		                'data' => User::allUserNames(true),
		                'language' => 'hu',
		                'options' => ['placeholder' => Yii::t('app', 'switch')],
		                'pluginOptions' => [
			                'allowClear' => true,
		                ],
	                ]);
	                ?>
                </div>

                <div class="col-sm-6">
	                <?= $form->field($model, 'project_id')->widget(Select2::class, [
		                'data' => Project::allProjectLeaderNames(true),
		                'language' => 'hu',
		                'options' => ['placeholder' => Yii::t('app', 'switch')],
		                'pluginOptions' => [
			                'allowClear' => true,
		                ],
	                ]);
	                ?>
                </div>
            </div>

	        <?php if($this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized'])): ?>
            <div class="row">
                <div class="col-sm-12">
		            <?= $form->field($model, 'financing')->textInput() ?>
                </div>
            </div>
	        <?php endif; ?>

            <div class="row">
                <div class="col-sm-6">
	                <?= $form->field($model, 'leader')->checkbox() ?>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
