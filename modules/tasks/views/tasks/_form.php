<?php

use app\modules\project\models\Project;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use app\modules\users\models\User;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\modules\partners\models\ContactEvent;
/* @var $modelContactEvent ContactEvent*/
/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\Tasks */
/* @var $form yii\bootstrap4\ActiveForm */

$user = User::current();
?>

<?php if(isset($task) && $task): ?>
<div class="collision-table" >Mentéshez és az ütköző feladat <b>felülírásához</b> kattintson mégegyszer a <b>Hozzáadás</b> gombra. <br>
    <br>
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-bordered ">
                <thead>
                <tr>
                    <th scope="col">Új feladat leírása:</th>
                    <th scope="col">Kezdete</th>
                    <th scope="col">Vége</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $model->text ?></td>
                    <td><?= $model->start_time ?></td>
                    <td><?= $model->end_time ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php foreach ($task as $t): ?>
        <div class="col-sm-6">
            <table class="table table-bordered ">
                <thead>
                <tr>
                    <th scope="col">Ütköző feladat leírása:</th>
                    <th scope="col">Kezdete</th>
                    <th scope="col">Vége</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $t->text ?></td>
                    <td><?= $t->start_time ?></td>
                    <td><?= $t->end_time ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php endforeach; ?>
    </div>

</div>
    <?php Yii::$app->session->setFlash('xy'); ?>

<?php endif; ?>

<div class="tasks-form">

    <?php $form = ActiveForm::begin([
        'encodeErrorSummary' => false,
    ]);?>

	<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <div class="card card-outline card-primary">
        <div class="card-body">

            <?php if($this->context->userCan('view_all_tasks')): ?>
                <div class="row">
                    <div class="col-sm-6">
		                <?= $form->field($model, 'project_id')->widget(Select2::class, [
			                'data' => Project::allProjectNames(true),
			                'options' => ['placeholder' => Yii::t('app', 'switch')],
			                'pluginOptions' => [
				                'allowClear' => true,
			                ],
		                ]);
		                ?>
                    </div>

                    <div class="col-sm-6">
	                    <?= $form->field($model, 'user_id')->widget(Select2::class, [
		                    'data' => [$user->id => $user->name], //User::allUserLeaderNames(true),
		                    'options' => [
		                        'placeholder' => Yii::t('app', 'switch'),
			                    'value' => $model->user_id?:User::current()->id,
                            ],
		                    'pluginOptions' => [
			                    'allowClear' => true,
		                    ],
	                    ]);
	                    ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'project_id')->widget(Select2::class, [
                            'data' => Project::allProjectNames(true),
                            'options' => ['placeholder' => Yii::t('app', 'switch')],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-sm-6 leader-user">
	                    <?= $form->field($model, 'user_id')->widget(Select2::class, [
		                    'data' => [$user->id => $user->name], //User::allUserLeaderNames(true),
		                    'options' => [
			                    'placeholder' => Yii::t('app', 'switch'),
			                    'value' => $model->user_id?:User::current()->id,
		                    ],
		                    'pluginOptions' => [
			                    'allowClear' => true,
		                    ],
	                    ]);
	                    ?>
                    </div>
                </div>
            <?php endif; ?>


            <div class="row mb-3">
                <div class="col-sm-4">
                <?=  CheckboxX::widget([
	                'name'=>'isContactEvent',
	                'value' => !$modelContactEvent->isNewRecord || isset($modelContactEvent->contact_id),
	                'options'=>['id'=>'isContactEvent'],
	                'pluginOptions'=>[
	                    'threeState'=>false,
                        'iconChecked'=>"<i class='fas fa-check'></i>",
                    ],
	                'labelSettings' => [
	                    'label' => Yii::t('app', 'contact_event'),
                        'position' => CheckboxX::LABEL_RIGHT,
                    ]
                ]); ?>
                </div>
            </div>


            <div id="contactEventForm" class="<?= $modelContactEvent->isNewRecord &&
            !isset($modelContactEvent->contact_id) ? 'd-none' : ''?>">

                <?= Yii::$app->controller->renderpartial('contact_event_form', [
	                'contactEvent' => $modelContactEvent,
                    'form' => $form,
                    'projectId' => $model->project_id
                ]); ?>
            </div>




            <div class="row">
                <div class="col-sm-6">
	                <?= $form->field($model, 'worktype_id')->widget(Select2::class, [
		                'data' => Worktype::allWorkTypeNames(true, $model->project_id ?? null),
		                'options' => ['placeholder' => Yii::t('app', 'switch')],
		                'pluginOptions' => [
			                'allowClear' => true,
		                ],
	                ]);
	                ?>

	                <?php if($this->context->userCan('create_worktype')): ?>
                        <a href="javascript:void(0)" data-new-worktype><i class="fas fa-plus-circle"></i> <?= Yii::t('app', 'create_new_worktype') ?></a><br><br>
	                <?php endif; ?>
                </div>

                <?php $default = Workplace::findOne(['default' => true]); ?>
                <div class="col-sm-6">
	                <?= $form->field($model, 'workplace_id')->widget(Select2::class, [
		                'data' => Workplace::allWorkplaceNames(true),
		                'options' => [
		                    'placeholder' => Yii::t('app', 'switch'),
			                'value' => $model->isNewRecord && !$model->workplace_id ? ($default?$default->id:null) : $model->workplace_id,
                        ],
		                'pluginOptions' => [
			                'allowClear' => true,
		                ],
	                ]);
	                ?>

	                <?php if($this->context->userCan('create_workplace')): ?>
                        <a href="javascript:void(0)" data-new-workplace><i class="fas fa-plus-circle"></i> <?= Yii::t('app', 'create_new_workplace') ?></a><br><br>
	                <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($model, 'date')->widget(DatePicker::class, [
                        //'value' => $model->date??date('Y-m-d'),
	                    'readonly' => true,
	                    'pluginOptions' => [
		                    'autoclose' => true,
		                    'format' => 'yyyy-mm-dd',
		                    'todayHighlight' => true,
	                    ]
                    ]); ?>
                </div>

                <div class="col-sm-4">
                    <?= $form->field($model, 'start_time')->widget(TimePicker::class, [
	                    'pluginOptions' => [
		                    'defaultTime' => false,
		                    'showSeconds' => false,
		                    'showMeridian' => false,
		                    'minuteStep' => 1,
	                    ],
	                    'addonOptions' => [
		                    'asButton' => true,
		                    'buttonOptions' => ['class' => 'btn btn-info']
	                    ]
                    ]); ?>
                </div>

                <div class="col-sm-4">
                    <?= $form->field($model, 'end_time')->widget(TimePicker::class, [
	                    'pluginOptions' => [
		                    'defaultTime' => false,
		                    'showSeconds' => false,
		                    'showMeridian' => false,
		                    'minuteStep' => 1,
	                    ],
	                    'addonOptions' => [
		                    'asButton' => true,
		                    'buttonOptions' => ['class' => 'btn btn-info']
	                    ]
                    ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'verified')->widget(Select2::class, [
                                'data' => [
                                        1 => Yii::t('app', 'rejected'),
                                        2 => Yii::t('app', 'accepted')
                                ],
                                'options' => ['placeholder' => Yii::t('app', 'switch')],
                                'pluginOptions' => [
                                        'allowClear' => true,
                                ],
                            ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($model, 'text')->textarea(['data-summernote' => true, 'rows' => 6]) ?>
                </div>
            </div>

            <?php if($this->context->userCan('view_finance') || $this->context->userCan('is_recognized')): ?>
                <?= $form->field($model, 'recommended_hours')->textInput() ?>

                <?= $form->field($model, 'recognized')->checkBox() ?>

                <?= $form->field($model, 'planned')->checkBox() ?>
            <?php endif; ?>

        </div>
    </div>

    <div class="form-group">

        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',            ]) ?>


    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    $("[data-new-worktype]").click(function () {
        var name = prompt("<?= Yii::t('app', 'title') ?>");
        if (name) {
            $.post("/tasks/worktype/create-worktype", {
                name: name
            }, function (response) {
                $("#tasks-worktype_id").append(new Option(name, response.id));
            });
        }
    });
</script>

<script>
    $("[data-new-workplace]").click(function () {
        var name = prompt("<?= Yii::t('app', 'title') ?>");
        if (name) {
            $.post("/tasks/workplace/create-workplace", {
                name: name
            }, function (response) {
                $("#tasks-workplace_id").append(new Option(name, response.id));
            });
        }
    });
</script>

<script>
    function loadUser(projectId) {
        if(projectId !== 0) {
            $.get({
                url: '/project/project/load-project-user',
                data: {
                    projectId: projectId,
                    userId: <?= $user->id ?>
                },
                success: function (data) {
                    var datas = JSON.parse(data);

                    if (!jQuery.isEmptyObject(datas)) { // Ha nem üres
                        $('option', '#tasks-user_id').remove();
                        $('#tasks-user_id').append('<option></option>');

                        datas.forEach(function (id) {
                            Object.keys(id).forEach(function (item) {
                                $('#tasks-user_id').append("<option value=\"" + item + "\">" + id[item] + "</option>");
                            });
                        });

                        $('#tasks-user_id').val(<?= $model->user_id??$user->id ?>).trigger('change');
                    } else {
                        $('#tasks-user_id').val(<?= $user->id ?>).trigger('change');
                    }
                }
            });
        }
    }

    $('#tasks-project_id').change(function() {
        var projectId = $(this).val();
        loadUser(projectId);
    });

    $(document).ready(function() {
        loadUser($('#tasks-project_id').val());
    });
</script>

<script>
    $(document).ready(function(){
        $('#tasks-project_id').on('select2:select', function(e){
            var project = e.params.data.id;
                console.log(project);

            $.get({
                url: "/tasks/tasks/worktypes-to-project",
                data: {
                    project: project,
                },
                success:function (data){
                    var datas = JSON.parse(data);
                    console.log(datas);

                    $('#tasks-worktype_id').empty();

                    datas.forEach(function(id) {
                        console.log(id);
                        Object.keys(id).forEach(function(item) {
                            console.log(item);
                            $('#tasks-worktype_id').append("<option value=\""+item+"\">"+id[item]+"</option>");
                        });
                    });
                }
            })

            $.get({
                url: "/tasks/tasks/contacts-to-project",
                data: {
                    project: project,
                },
                success:function (data){
                    var datas = JSON.parse(data);
                    $('#contactevent-contact_id').empty();

                    datas.forEach(function(id) {
                        var text = `<b>${id[1]}</b><br><small>${id[2]}</small></option>`;
                        var option = new Option(text, id[0]);
                        $('#contactevent-contact_id').append(option);
                    });
                }
            })
        });
    });
</script>

<script>
    $(document).on('change', '#isContactEvent', function(){
        $('#contactEventForm').toggleClass('d-none');
        var contactId = $('#contactevent-contact_id');
        contactId.attr('disabled', !contactId.attr('disabled'));
        var contactType = $('#contactevent-type');
        contactType.attr('disabled', !contactType.attr('disabled'));
    })
</script>
<!--<script>-->
<!--    function validateTime(e){-->
<!--        $('.startTimeBigger').remove();-->
<!--        var startTime = $('#tasks-start_time');-->
<!--        var endTime = $('#tasks-end_time');-->
<!---->
<!--        if ((startTime.val() != '' && endTime.val() != '') && startTime.val() > endTime.val()) {-->
<!--            $('.alert').attr('style', 'display');-->
<!--            startTime.attr('class', 'form-control is-invalid');-->
<!--            startTime.attr('aria-invalid', 'true');-->
<!--            $('.alert > ul').append('<li class="startTimeBigger">Munkavégzés kezdete nem lehet nagyobb mint a vége</li>');-->
<!--            e.preventDefault();-->
<!--        }-->
<!--    }-->
<!--    $('button[type="submit"], #tasks-start_time, #tasks-end_time').click(function (e){-->
<!--        $('.alert').attr('style', 'display:none');-->
<!--            validateTime(e);-->
<!--        })-->
<!--    $('#tasks-start_time, #tasks-end_time').on('change focusout blur', function (e){-->
<!--            validateTime(e);-->
<!--        })-->
<!--</script>-->