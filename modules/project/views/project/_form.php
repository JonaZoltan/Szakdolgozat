<?php

use app\modules\partners\models\Partners;
use app\modules\project\models\Area;
use app\modules\project\models\Project;
use app\modules\tasks\models\Worktype;
use kartik\color\ColorInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\project\models\Project */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $projectWorktype app\modules\project\models\ProjectWorktypes */
/* @var $worktype app\modules\tasks\models\Worktype */


?>

<style type="text/css">
    .field-project-members .select2-selection {
        min-height: 55px;
    }
</style>

<div class="project-form">

    <?php $form = ActiveForm::begin([
        'encodeErrorSummary' => false,
    ]); ?>

	<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <div class="card card-outline card-primary">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-4">
	                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($model, 'workTypes')->widget(Select2::class, [
	                    'data' => Worktype::allWorkTypeNames(true),
	                    'language' => 'hu',
	                    'options' => ['placeholder' => Yii::t('app', 'worktype_id'), 'multiple' => true],
	                    'pluginOptions' => [
		                    'allowClear' => true,
	                        ],
                        ]);
                    ?>
                </div>
                <div class="col-sm-4">
	                <?= $form->field($model, 'area_id')->widget(Select2::class, [

		                'data' => Area::allAreaNames(true),
		                'language' => 'hu',
		                'options' => ['placeholder' => Yii::t('app', 'switch')],
		                'pluginOptions' => [
			                'allowClear' => true,
		                ],
	                ]);
	                ?>

	                <?php if($this->context->userCan('create_area')): ?>
                        <a href="javascript:void(0)" data-new-area><i class="fas fa-plus-circle"></i> <?= Yii::t('app', 'create_new_area') ?></a><br>
	                <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
	                <?= $form->field($model, 'text')->textarea(['data-summernote' => true, 'rows' => 6]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <b>Használatban lévő színek:</b><br />
                    <?php $colors = Project::find()->all(); ?>
                    <?php foreach($colors as $color): ?>
                        <span class="badge" style="padding: 5px; background-color: <?= $color->color ?>; color: <?= $color->fontColor ?>;"><?= $color->color ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
	                <?= $form->field($model, 'color')->widget(ColorInput::class, [
		                'size' => 'lg',
		                'options' => ['placeholder' => 'Szín választása ...'],
	                ]); ?>

                    <?php if($model->isNewRecord): ?>
                        <span class="badge label-test" style="padding: 5px; color: black;">Teszt szín</span>
                    <?php else: ?>
                        <span class="badge label-test" style="padding: 5px; background-color: <?= $model->color ?>; color: <?= $model->fontColor ?>;">Teszt szín</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-12">
	                <?= $form->field($model, 'partner_ids')->widget(Select2::class, [
		                'data' => Partners::allPartnerName(true),
		                'language' => 'hu',
		                'options' => ['placeholder' => Yii::t('app', 'switch')],
		                'pluginOptions' => [
			                'multiple' => true,
			                'allowClear' => true
		                ],
	                ]);
	                ?>
                </div>
            </div>

	        <?= $form->field($model, 'archived')->checkBox() ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $("[data-new-area]").click(function () {
        var name = prompt("<?= Yii::t('app', 'title') ?>");
        if (name) {
            $.post("/project/area/create-area", {
                name: name
            }, function (response) {
                $("#project-area_id").append(new Option(name, response.id));
            });
        }
    });
</script>

<script>
    $('#project-color').change(function() {
        $('.label-test').css('background-color', $(this).val());
        $('.label-test').css('color', hexFontColor($(this).val()));
    });
</script>