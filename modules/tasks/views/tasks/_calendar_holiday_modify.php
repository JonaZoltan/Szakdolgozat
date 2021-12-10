<?php

use app\modules\apps\models\Apps;
use app\modules\tasks\models\Holiday;
use app\modules\tasks\models\HolidayExtra;
use app\modules\users\models\User;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $event Holiday */
/** @var $user User */

$currUser = User::current();
$thisWeekHoliday = Holiday::find()
    ->where(['in', 'user_id', $event->user_id])
    ->andWhere(['between', 'date', Apps::getFirstDay($event->date), Apps::getLastDay($event->date)])
    ->orderBy('date')
    ->all();
?>

<style>
    .conflict {
        padding: 10px;
        margin: 10px;
        background-color: #efefef;
        border: 1px solid #d7d7d7;
        border-radius: 5px;
        box-shadow: 2px 2px #8c8c8c;
    }

    .back {
        margin-bottom: 15px;
    }
</style>

<div class="back">
	<?= Html::a('<i class="fas fa-angle-double-left"></i> Vissza a naptárhoz', Url::to(['/tasks/tasks/calendar']), ['class' => 'btn btn-primary']) ?>
</div>

<?php
    /** @var $holiday Holiday */
    /** @var $conflict Holiday */
?>
<?php foreach($thisWeekHoliday as $holiday): ?>

    <?php
        $holidayExtra = HolidayExtra::findOne(['user_id' => $holiday->user_id, 'year' => Apps::getDate($holiday->date, 'Y')]);
        $hasConflict = false;

        if($holidayExtra && Json::decode($holidayExtra->disabled_user)) {
            $hasConflict = Holiday::find()->where(['date' => $holiday->date])->andWhere(['in', 'user_id', array_values(Json::decode($holidayExtra->disabled_user))])->groupBy('user_id')->all();
        }
    ?>

	<?php
        Pjax::begin([
            'id' => 'pjax-holiday_'.$holiday->id,
            'enablePushState' => false,
        ]);
	?>
    <div>
        <?php if(date('Y-m-d') <= $holiday->date): ?>
            <b><?= $user->name ?></b> <i><?= Apps::dateBeautifier($holiday->date) ?> (<?= Yii::t('app', strtolower(Apps::getDate($holiday->date, 'l'))) ?>)</i> szabadság kérelme.
        <?php else: ?>
            <b><?= $user->name ?></b> <i style="color:red"><?= Apps::dateBeautifier($holiday->date) ?> (<?= Yii::t('app', strtolower(Apps::getDate($holiday->date, 'l'))) ?>)</i> szabadság kérelme.
        <?php endif; ?>

        <?php if($holiday->accepted === 1): ?>
            <span class="badge badge-success">Elfogadva</span>
        <?php elseif($holiday->accepted === 2): ?>
            <span class="badge badge-warning">Megtagadva</span>
        <?php endif; ?>
    </div>

    <?php if($hasConflict): ?>
    <div class="conflict">
        <b>Ütközések:</b><br />
        <?php foreach ($hasConflict as $conflict): ?>
            <?= User::findOne($conflict->user_id)->name ?>
            <?php if($conflict->accepted === 0): ?>
            <span class="badge badge-secondary">Kérelmezve</span>
            <?php elseif($conflict->accepted === 1): ?>
                <span class="badge badge-success">Elfogadva</span>
            <?php elseif($conflict->accepted === 2): ?>
                <span class="badge badge-warning">Megtagadva</span>
            <?php endif; ?>
            <br />
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div>
        <?php if(date('Y-m-d') <= $holiday->date || $this->context->userCan(['holiday'])): ?>

            <?php if($this->context->userCan(['holiday'])): ?>
                <?php if($holiday->accepted != 1): ?>
                    <button class="btn btn-outline-success btn-s" onclick="acceptEvent(<?= $holiday->id ?>)">Elfogadás</button>
                <?php endif; ?>

                <?php if($holiday->accepted != 2): ?>
                    <button class="btn btn-outline-warning btn-s" onclick="declineEvent(<?= $holiday->id ?>)">Megtagadás</button>
                <?php endif; ?>
            <?php endif; ?>

            <?php if($this->context->userCan(['holiday']) || ($currUser->id === $holiday->user_id && $holiday->accepted === 0)): ?>
                <button class="btn btn-outline-danger btn-s" onclick="deleteEvent(<?= $holiday->id ?>)">Törlés</button>
            <?php endif; ?>
        <?php endif; ?>
        <small class="float-right">(#<?= $holiday->id ?>)</small>
    </div>
    <hr />
	<?php Pjax::end(); ?>

<?php endforeach; ?>

<script>
    function acceptEvent(eventId) {
        $.get({
            url: '/tasks/tasks/accept-holiday',
            data: {
                eventId: eventId,
            },
            dataType: 'json',
            success: function(data) {
                if(data.success) {
                    /*$('#calendar').evoCalendar('removeCalendarEvent', data.id);

					$("#calendar").evoCalendar("addCalendarEvent", [
						{
							id: data.id,
							name: data.name,
							date: data.date,
							type: data.type,
							color: data.color,
						}
					]);*/

                    $.pjax.reload({container: `#pjax-holiday_${eventId}`});
                    $.pjax.xhr = null;
                } else {
                    alert('Az adott munkavállalónak nincs több szabadsága.');
                }
            }
        });
    }

    function declineEvent(eventId) {
        bootbox.confirm("Biztosan megtagadja a szabadságkérelmet?", function(result) {
            $.get({
                url: '/tasks/tasks/decline-holiday',
                data: {
                    eventId: eventId,
                },
                dataType: 'json',
                success: function(data) {
                    /*$('#calendar').evoCalendar('removeCalendarEvent', data.id);

                    $("#calendar").evoCalendar("addCalendarEvent", [
                        {
                            id: data.id,
                            name: data.name,
                            date: data.date,
                            type: data.type,
                            color: data.color,
                        }
                    ]);*/

                    $.pjax.reload({container: `#pjax-holiday_${eventId}`});
                    $.pjax.xhr = null;
                }
            });
        });
    }

    function deleteEvent(eventId) {
        bootbox.confirm("Biztosan törölni szeretné a szabadságkérelmet?", function(result) {
            if(!result)
                return;

            $.get({
                url: '/tasks/tasks/delete-holiday',
                data: {
                    eventId: eventId,
                },
                success: function(success) {
                    if(success) {
                        //$('#calendar').evoCalendar('removeCalendarEvent', eventId);
                        $(`#pjax-holiday_${eventId}`).remove();
                    }
                }
            });
        });
    }
</script>
