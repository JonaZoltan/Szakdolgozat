<?php

use app\modules\project\models\Project;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use app\modules\users\models\User;
use kartik\checkbox\CheckboxX;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
use kartik\widgets\Select2;
use kidzen\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use app\modules\partners\models\ContactEvent;
/* @var $modelContactEventAll ContactEvent*/
/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\Tasks */
/* @var $modelAll app\modules\tasks\models\Tasks */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<style>
    span[aria-labelledby*="contact_id"]{
        min-height: 55px;
    }
</style>

<div class="tasks-form">

    <?php $form = ActiveForm::begin([
        'encodeErrorSummary' => false,
	    //'action' => Url::to(['/tasks/tasks/create-day'])
    ]); ?>

	<?= $form->errorSummary($modelAll, ['class' => 'alert alert-danger']); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => User::current()->id])->label(false) ?>

    <div class="row">
        <div class="col-sm-12">

            <!-- Start -->
			<?php DynamicFormWidget::begin([
				'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
				'widgetBody' => '.container-items', // required: css class selector
				'widgetItem' => '.item', // required: css class
				'limit' => 100, // the maximum times, an element can be cloned (default 999)
				'min' => 1, // 0 or 1 (default 1)
				'insertButton' => '.add-item', // css class
				'deleteButton' => '.remove-item', // css class
				'model' => $modelAll[0],
				'formId' => 'w0',
				'formFields' => [

					'user_id',
                    'project_id',
                    'worktype_id',
                    'workplace_id',
                    'date',
                    'start_time',
                    'end_time',
                    'text'
				],
			]); ?>

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> <?= Yii::t('app', 'tasks') ?>
                    <button type="button" class="float-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Új hozzáadása</button>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body container-items">
					<?php foreach ($modelAll as $index => $task): ?>

                        <div class="card card-outline card-secondary item">
                            <div class="card-header">
                                <button type="button" class="float-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                                <button type="button" class="float-right btn btn-outline-success btn-xs add-item copy-item" style="margin-right: 5px;" data-index="<?= $index ?>" onclick="copyItem(<?= $index ?>)"><i class="fas fa-copy"></i></button>

                                <div class="clearfix"></div>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="row">
                                            <div class="col-sm-4">
												<?= $form->field($task, "[{$index}]project_id")->widget(Select2::class, [
													'data' => Project::allProjectNames(true),
													'options' => ['placeholder' => Yii::t('app', 'switch')],
													'pluginOptions' => [
														'allowClear' => true,
													],
												]);
												?>
                                            </div>

                                            <div class="col-sm-4">
												<?= $form->field($task, "[{$index}]worktype_id")->widget(Select2::class, [
													'data' => Worktype::allWorkTypeNames(true, $task->project_id ?? null),
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
                                            <div class="col-sm-4">
												<?= $form->field($task, "[{$index}]workplace_id")->widget(Select2::class, [
													'data' => Workplace::allWorkplaceNames(true),
													'options' => [
													    'placeholder' => Yii::t('app', 'switch'),
														'value' => $task->isNewRecord && !$task->workplace_id ? ($default?$default->id:null) : $task->workplace_id,
                                                    ],
													'pluginOptions' => [
														'allowClear' => true,
													],
												]);
												?>
                                                <?= $default?$default->id:null ?>

												<?php if($this->context->userCan('create_workplace')): ?>
                                                    <a href="javascript:void(0)" data-new-workplace><i class="fas fa-plus-circle"></i> <?= Yii::t('app', 'create_new_workplace') ?></a><br><br>
												<?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-4">
                                                <input type="checkbox" name="isContactEvent" id="isContactEvent"
                                                       data-index="<?= $index ?>" <?= $modelContactEventAll[$index]->isNewRecord &&
                                                !isset($modelContactEventAll[$index]->contact_id) ? : 'checked'?>>
                                                <label for="isContactEvent"><?= Yii::t('app', 'contact_event') ?></label>
                                            </div>
                                        </div>

                                        <div id="contactEventForm" data-index="<?= $index ?>"
                                             class="<?= $modelContactEventAll[$index]->isNewRecord &&
                                             !isset($modelContactEventAll[$index]->contact_id) ? 'd-none' : ''?>">

		                                    <?= Yii::$app->controller->renderpartial('contact_event_form_day', [
			                                    'contactEvent' => $modelContactEventAll,
			                                    'form' => $form,
                                                'index' => $index,
                                                'projectId' => $task->project_id
		                                    ]); ?>

                                        </div>



                                        <div class="row">
                                            <div class="col-sm-4">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?= $form->field($task, "[{$index}]date")->widget(DatePicker::class, [
                                                            'value' => $model->date??date('Y-m-d'),
                                                            'readonly' => true,
                                                            'pluginOptions' => [
                                                                'autoclose' => true,
                                                                'format' => 'yyyy-mm-dd',
                                                                'todayHighlight' => true,
                                                            ]
                                                        ]); ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?= $form->field($task, "[{$index}]start_time")->widget(TimePicker::class, [
                                                            'value' => '',
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
                                                    <div class="col-sm-12">
                                                        <?= $form->field($task, "[{$index}]end_time")->widget(TimePicker::class, [
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

                                            </div>

                                            <div class="col-sm-8">
	                                            <?= $form->field($task, "[{$index}]text")->textarea(['data-summernote' => true, 'rows' => 6]) ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-info-circle"></i> <?= Yii::t('app', 'tasks') ?>
                    <button type="button" class="float-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Új hozzáadása</button>
                    <div class="clearfix"></div>
                </div>
            </div>
			<?php DynamicFormWidget::end(); ?>
            <!-- end -->

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-success']) ?>
        <span class="btn btn-primary" onclick="showDatas()">Rögzítendő adatok megtekintése</span>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    function showDatas() {
        var projectIds = [];
        var worktypeIds = [];
        var workplaceIds = [];
        var date = [];
        var dateStart = [];
        var dateEnd = [];
        var text = [];
        
        $(`select[id$='project_id']`).each(function() {
            projectIds.push($(this).val());
        });

        $(`select[id$='worktype_id']`).each(function() {
            worktypeIds.push($(this).val());
        });

        $(`select[id$='workplace_id']`).each(function() {
            workplaceIds.push($(this).val());
        });

        $(`input[id$='date']`).each(function() {
            date.push($(this).val());
        });

        $(`input[id$='start_time']`).each(function() {
            dateStart.push($(this).val());
        });

        $(`input[id$='end_time']`).each(function() {
            dateEnd.push($(this).val());
        });

        $(`textarea[id$='text']`).each(function() {
            text.push($(this).val());
        });

        $.get({
            url: `/tasks/tasks/load-view`,
            data: {
                projectIds: JSON.stringify(projectIds),
                worktypeIds: JSON.stringify(worktypeIds),
                workplaceIds: JSON.stringify(workplaceIds),
                date: JSON.stringify(date),
                dateStart: JSON.stringify(dateStart),
                dateEnd: JSON.stringify(dateEnd),
                text: JSON.stringify(text),
            },
            success: function(data) {
                $('#task-view-modal').modal("show");
                $('#task-view-modal').find('.modal-body').html(data);
            }
        });


    }
</script>

<script>

    var index = null;
    function copyItem(i) {
        index = i;
    }

    var lastIndex;

    function getLastIndex() {
        // lastIndex = $('.container-items').children('.item:last-child').find('.copy-item').data('index');
        lastIndex = $('.container-items').children('.item').length-1;
    }

    function setContactEventIndexes(index){
        $('.item:last-child').find('.copy-item').attr("onclick", `copyItem(${index})`);
        $('.item:last-child').find('.copy-item').attr("data-index", index);
        var checkbox = $('.item:last-child').find('input[name="isContactEvent"]');
        var divContact = $('.item:last-child').find('div[id^="contactEventForm"]');

        checkbox.data('index', index);
        checkbox.attr('data-index', index);
        divContact.data('index', index);
        divContact.attr('data-index', index);
        if(!checkbox.checked) {

            divContact.attr('class', 'd-none');
            $(`select[id='contactevent-${index}-contact_id']`).attr('disabled', true);
            $(`select[id='contactevent-${index}-type']`).attr('disabled', true);
        }
    }

    function disableAllRemove() {
        $('.remove-item').each(function() {
            $(this).attr('disabled', true);
        });

        $('.item:last-child').find('.remove-item').attr('disabled', false);
    }

    disableAllRemove();

    $('.dynamicform_wrapper').on('afterDelete', function(e, item) {
        getLastIndex();
        //setContactEventIndexes(lastIndex);
        disableAllRemove()
    });

    $('.dynamicform_wrapper').on('beforeInsert', function(e, item) {
        getLastIndex();
    });

    $('.dynamicform_wrapper').on('afterInsert', function(e, item) {
        disableAllRemove();
        var newIndex = lastIndex + 1;

        setContactEventIndexes(newIndex);

        if(index !== null) {
            console.log('Ezt copy: ' + index);
            console.log("Ide copy: " + newIndex);
            console.log("---------------------------");

            //$('.container-items').children('.item:last-child').children('.card-header').children('[onclick]').attr("onclick", `copyNew(${newIndex})`);

            $(`select[id='tasks-${newIndex}-worktype_id']`).val($(`select[id='tasks-${index}-worktype_id']`).val()).trigger('change');
            $(`select[id='tasks-${newIndex}-project_id']`).val($(`select[id='tasks-${index}-project_id']`).val()).trigger('change');
            $(`select[id='tasks-${newIndex}-workplace_id']`).val($(`select[id='tasks-${index}-workplace_id']`).val()).trigger('change');
        } else {
            $(`select[id='tasks-${newIndex}-workplace_id']`).val($(`select[id='tasks-${lastIndex}-workplace_id']`).val()).trigger('change');
        }

        //$(`input[id='tasks-${newIndex}-working_datetime_start']`).datetimepicker('update', $(`input[id='tasks-${lastIndex}-working_datetime_end']`).val());
        //$(`input[id='tasks-${newIndex}-date']`).kvDatepicker('setDate', '2020-10-05');
        $(`input[id='tasks-${newIndex}-date']`).val( $(`input[id='tasks-${lastIndex}-date']`).val() ).trigger('change');
        $(`input[id='tasks-${newIndex}-start_time']`).timepicker('setTime', $(`input[id='tasks-${lastIndex}-end_time']`).val());

        $('[data-summernote]').summernote({
            lang: 'hu-HU',
            height: 150,
            toolbar: [
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['para', ['ul', 'ol']],
            ]
        });

        index = null;
    });
</script>


<script>

        $(document).on('change', "select[id*='-project_id']", function(e){
            // var project = e.params.data.id;
            var worktypeSelect = $(this).parent().parent().parent().find("select[id*='-worktype_id']");
            var contactSelect = $(this).parent().parent().parent().parent().find("select[id*='-contact_id']");
            var projectId = $(this).val();
            if (!projectId){
                projectId = null;
            }

            $.get({
                url: "/tasks/tasks/worktypes-to-project",
                data: {
                    project: projectId,
                },
                success:function (data){
                    var datas = JSON.parse(data);
                    var index;

                    for (var i = 0; i < datas.length; i++){
                        if (datas[i][worktypeSelect.val()])
                            index = i;
                    }
                    datas.unshift(datas.splice(index, 1)[0]);
                    worktypeSelect.empty();
                    datas.forEach(function(id) {
                        Object.keys(id).forEach(function(item) {
                            worktypeSelect.append("<option value=\""+item+"\">"+id[item]+"</option>");
                        });
                    });
                }
            })
            $.get({
                url: "/tasks/tasks/contacts-to-project",
                data: {
                    project: projectId,
                },
                success:function (data){
                    var datas = JSON.parse(data);

                    contactSelect.empty();

                    datas.forEach(function(id) {
                        var text = `<b>${id[1]}</b><br><small>${id[2]}</small></option>`;
                        var option = new Option(text, id[0]);
                        contactSelect.append(option);
                    });
                }
            })
        });

</script>



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
    $(document).on('change', `input[type="checkbox"][id^="isContactEvent"]`, function(){
        var dataIndex = $(this).data('index');
        $(`div[id^="contactEventForm"][data-index="${dataIndex}"]`).toggleClass('d-none');

        var contactId = $(`#contactevent-${dataIndex}-contact_id`);
        contactId.attr('disabled', !contactId.attr('disabled'));
        var contactType = $(`#contactevent-${dataIndex}-type`);
        contactType.attr('disabled', !contactType.attr('disabled'));
    })
    $('label[for="isContactEvent"]').click(function(e) {
        e.preventDefault();
    });
</script>
