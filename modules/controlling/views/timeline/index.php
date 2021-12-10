<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.07.30., 10:17:46
 * The used disentanglement, and any part of the code
 * index.php own by the author, Bencsik Matyas.
 */

/* @var $getData */
/* @var $users */
/* @var Project $projects */

/* @var Tasks $tasks */

use app\modules\apps\models\Apps;
use app\modules\project\models\Project;
use app\modules\project\models\ProjectMembership;
use app\modules\tasks\models\Tasks;
use app\modules\tasks\models\Worktype;

use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\web\JsExpression;

/*https://www.jqueryscript.net/time-clock/Timeline-Generator-jQuery-Timeline-js.html*/
?>

<?php
$searchDate = isset($getData->searchDate) ? $getData->searchDate : '';

?>

<style>
    .datepicker {
        background: #fff;
    }

    .datepicker table {
        margin: 0 auto;
    }

    .jqtl-event-node {
        /*border: 1px solid #000;*/
        border-radius: 5px!important;
    }

</style>
<link rel="stylesheet" href="/dist/js/jquery.timeline/dist/jquery.timeline.min.css">
<script src="/dist/js/jquery.timeline/dist/jquery.timeline.min.js"></script>

<div class="row">
    <div class="h2">
        Idővonal: <?= $searchDate ?>
    </div>
</div>
<script>
    var sidebar = [];
</script>

<div class="row">
    <div class="col-sm-3">

        <?php

        $validate = [];
        if($this->context->userCan('view_all_task')) {
	        $ts = Tasks::find()->groupBy('date(working_datetime_start)')->asArray()->all();
        } else {
	        $leader = ProjectMembership::findOne(['user_id' => $this->context->userData['id'], 'leader' => true]);
	        if($leader) {
		        $leader = ProjectMembership::find()->where(['user_id' => $this->context->userData['id'], 'leader' => true])->asArray()->indexBy('project_id')->all();

		        $leader = array_keys($leader);
		        $leader[] = 0;

		        $ts = Tasks::find()
			        ->where(['in', 'project_id', array_keys($leader)])
			        ->groupBy('date(working_datetime_start)')
                    ->asArray()->all();
            } else {
		        $ts = Tasks::find()->where(['user_id' => $this->context->userData['id']])->groupBy('date(working_datetime_start)')->asArray()->all();
            }
        }

        foreach ($ts as $elem) {
	        array_push($validate, Apps::getDate($elem["working_datetime_start"]));
        }

        ?>

        <h3><?= Yii::t('app', 'search') ?></h3>
		<?= DatePicker::widget([
			'name' => 'date',
			'type' => DatePicker::TYPE_INLINE,
			'id' => 'date_select',
			'value' => $searchDate,
			'readonly' => true,
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'beforeShowDay' => new JsExpression("function(date) {
                                    let vDate = ".json_encode($validate).";
                                    let hDate = date.getFullYear() + '-' + ((date.getMonth()+1).toString()).padStart(2, '0') + '-'+ (date.getDate().toString()).padStart(2, '0');
                                    
								    if(vDate.includes(hDate)) {
								        return {classes: 'validate'}
								    }
								
								}"),
			],
		]);
		?>
    </div>

	<?php $workTypes = Worktype::find()->asArray()->indexBy('id')->all(); ?>

    <div class="col-sm-9" id="eventTimeline">
        <h3>Feladatok</h3>
        <?php $row = 1 ?>
		<?php foreach ($users as $usr): ?>
            <?php $hasTask = Tasks::find()->where(['user_id' => $usr->id, 'date(working_datetime_start)' => $getData->searchDate])->one(); ?>
            <?php if($hasTask):  ?>
                <script>
                    sidebar.push("" +
                        "<label>" +
                        "<img class='user' src='/uploads/users/<?= $usr->hasPhoto()?$usr->id.".jpg":"user.png" ?>' />" +
                        "<span class='timeline_name'><?= $usr->name ?></span>" +
                        "</label>");
                </script>
                <ul class="timeline-events">
                   <?php foreach ($tasks as $task): ?>
                        <?php if($task->user_id == $usr->id): ?>

                            <li data-timeline-node="{
                                    id:<?= $task['id'] ?>,
                                    start:'<?= $task['working_datetime_start']?>',
                                    end: '<?= $task['working_datetime_end'] ?>',
                                    row: <?= $row ?>,
                                    bgColor: '<?= isset($projects[$task['project_id']]) ? $projects[$task['project_id']]['color'] : "lightgray" ?>',
                                    color: '<?= isset($projects[$task['project_id']]) ? $projects[$task['project_id']]->fontColor : "#000" ?>',
                                    }">
                                <div data-toggle="tooltip"
                                     data-placement="left"
                                     class="tooltip"
                                     data-original-title="<?= isset($projects[$task['project_id']]) ? $projects[$task['project_id']]['name'] : 'Általános' ?> - <?= $workTypes[$task['worktype_id']]['title'] ?></br><?= date("H:i",strtotime($task['working_datetime_start'])) ?> - <?= date("H:i",strtotime($task['working_datetime_end'])) ?></br><?=$task['text'] ?>"
                                     data-html="true">
                                    <?php $href = "/tasks/tasks/view?id=". $task['id']; ?>
                                    <a href="<?= $href ?>">
                                        <span><?= isset($projects[$task['project_id']]) ? $projects[$task['project_id']]['name'] : 'Általános' ?> - <?= $workTypes[$task['worktype_id']]['title'] ?></span>
                                        <span><?php if($task['comment']): ?><i class="fas fa-sticky-note"></i> <?php endif; ?><?= date("H:i",strtotime($task['working_datetime_start'])) ?> - <?= date("H:i",strtotime($task['working_datetime_end'])) ?></span>
                                    </a>
                                </div>
                                <div
                                     data-toggle="header"
                                     data-placement="left"
                                     data-html="true">

                                    <span><?= isset($projects[$task['project_id']]) ? $projects[$task['project_id']]['name'] : 'Általános' ?> - <?= $workTypes[$task['worktype_id']]['title'] ?></span></br>
                                    <span><?php if($task['comment']): ?><i class="fas fa-sticky-note"></i> <?php endif; ?><?= date("H:i",strtotime($task['working_datetime_start'])) ?> - <?= date("H:i",strtotime($task['working_datetime_end'])) ?></span>
                                </div>
                            </li>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php $row++; ?>
            <?php endif; ?>
 		<?php endforeach; ?>
    </div>

</div>
<style>
    .head{
    display: block;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    }
</style>

<script>
    //TimeLine
    let options = {
        type: "bar",
        startDatetime: "<?= $searchDate ?> 08:00",
        endDatetime: "<?= $searchDate ?> 19:00",
        scale: "hour",
//        rows: auto,
        shift: false,
        minGridSize: 60,
        zoom: true,
        debug: false,


        headline: {
            display: false,
        },

        ruler: {
            top: {
                display: true,
                lines: ['hour'],
                height: 30,
                fontSize: 14,
                color: "#777777",
                background: "#FFFFFF",
                locale: "hu-HU",
                format: {}
            },
        },

        sidebar: {
            sticky: true,
            overlay: false,
            list: sidebar,//  an array of items
        },


    };

    $('#eventTimeline').Timeline(options)
        .popover({
            placement: 'auto',
            selector: '[data-toggle="popover"]',
            trigger: 'click'
        })
        .tooltip({
            placement: 'auto',
            selector: '[data-toggle="tooltip"]',
            html: true
        });


     $(function () {
       $('[data-toggle="tooltip"]').tooltip()
    })

    $('.jqtl-ruler-line-item').children('span').text(function (get, dataResponse) {
        let date = new Date(dataResponse);
        return date.getHours();
    });

</script>


<script>

    var url = '<?=  Url::toRoute(['']);?>';

    let searchParams = new URLSearchParams(window.location.search)
    let baseData = searchParams.has('baseData') ? JSON.parse(atob(searchParams.get('baseData'))) : false;
    let date = baseData.searchDate ? baseData['searchDate'] : false;

    let returnData = [];

    $("#date_select").change(function () {
        returnData.searchDate = $(this).val();
        url += "?baseData=" + btoa(JSON.stringify(Object.assign({}, returnData)));
        window.open(url, "_self");
    });

</script>