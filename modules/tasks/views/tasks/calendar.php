<?php

use app\components\Helpers;
use app\modules\apps\models\Apps;
use app\modules\tasks\models\Holiday;
use app\modules\tasks\models\HolidayExtra;
use app\modules\users\models\User;
use yii\helpers\Json;
use yii\widgets\Pjax;

$user = User::current();

?>

<link rel="stylesheet" type="text/css" href="/dist/css/calendar/evo-calendar.css"/>
<link rel="stylesheet" type="text/css" href="/dist/css/calendar/evo-calendar.royal-navy.css"/>
<link rel="stylesheet" type="text/css" href="/dist/css/calendar/myCalendar"/>


<div id="calendar"></div>

<?php
    $days = HolidayExtra::findOne(['year' => date('Y'), 'user_id' => User::current()->id]);
?>

<?php if($days): ?>
    <hr />
    <b>Fennmaradó napok:</b> <?= $days->freeDay ?><br />
    <b>Kivett napok:</b> <?= $days->acceptedDay ?><br />
    <b>Elutasított napok:</b> <?= $days->declinedDay ?><br />
    <b>Elbírálás alatt:</b> <?= $days->reviewDay ?><br />
<?php endif; ?>

<script src="/dist/js/calendar/evo-calendar.js"></script>

<?php

$holidays = Holiday::find()->all();
$result = [];

foreach ($holidays as $holiday) {
	$userHoliday = User::findOne($holiday->user_id);
	$holidayExtra = HolidayExtra::findOne(['user_id' => $holiday->user_id, 'year' => Apps::getDate($holiday->date, 'Y')]);
	$hasConflict = false;

	if($holidayExtra && Json::decode($holidayExtra->disabled_user)) {
		$hasConflict = Holiday::find()->where(['date' => $holiday->date])->andWhere(['in', 'user_id', array_values(Json::decode($holidayExtra->disabled_user))])->all()?true:false;
	}

	array_push($result, [
		'id' => $holiday->id,
		'name' => $userHoliday->name.($hasConflict?" &#9679;":""),
		'date' => Apps::getDate($holiday->date, 'm/d/y'),
		'type' => "holiday_".$holiday->accepted,
		'color' => !$holiday->accepted ? "gray" : ( $holiday->accepted == 2 ? "orange" : "green" ),
	]);
}

$result = Json::encode($result);
?>

<script>
    $(document).ready(function() {
        var holidays = <?= Json::encode(Apps::HOLIDAY); ?>;
        var selectedEvent = null;

        $('#calendar').evoCalendar({
            theme: 'Royal Navy',
            settingName: "Szabadság",
            language: 'hu',
            firstDayOfWeek: 1,
            format: 'yyyy. mm. dd.',
            titleFormat: 'yyyy MM',
            eventHeaderFormat: 'yyyy MM dd.',
        });

        $('#calendar').evoCalendar('addCalendarEvent', <?= $result ?>);

        /* Munkaszüneti napok */
        $.each(holidays, function(index, value) {
            if(value.length > 0) {
                $.each(value, function(i, v) {
                    $('#calendar').evoCalendar('addCalendarEvent', [
                        {
                            id: `mn_${v}`,
                            name: 'Munkaszüneti nap',
                            date: v,
                            type: 'hol',
                            color: 'red'
                        }
                    ]);
                });
            }
        });

        /** Tiszacsécse */
        $('#calendar').evoCalendar('addCalendarEvent', [
            {
                id: `mn_07/23/2021`,
                name: 'Tiszacsécsei nap',
                date: '07/23/2021',
                type: 'hol',
                color: "#146082"
            }
        ]);
        /** Tiszacsécse */
        /* Munkaszüneti napok */

        $('#calendar').on('selectEvent', function(event, activeEvent) {
            selectedEvent = activeEvent.id;

            //window.open(`/tasks/tasks/modify-calendar?eventId=${selectedEvent}`, '_blank');
            window.location.href = `/tasks/tasks/modify-calendar?eventId=${selectedEvent}`;

            /*$.get({
                url: '/tasks/tasks/modify-calendar',
                data: {
                    eventId: selectedEvent,
                },
                success: function(data) {
                    $('#calendar').hide();

                    $('#calendar-modal').find('.modal-body').html(data);
                    $('#calendar-modal').modal('show');
                }
            });*/
        });
    });
</script>

<script>
    $(document).ready(function() {
        addBtnInitalize();

        $('.month').click(function() {
            addBtnInitalize();
        });

        $('.chevron-arrow-left').parent('button').click(function() {
            addBtnInitalize();
        });

        $('.chevron-arrow-right').parent('button').click(function() {
            addBtnInitalize();
        });
    });

    function addBtnInitalize() {
        // addBtn
        $('.day').dblclick(function() {
            var userId = <?= $user->id ?>;
            var userName = "<?= $user->name ?>";
            var date = $('#calendar').evoCalendar('getActiveDate');

            var dateDay = new Date(date).getDay();
            if(dateDay == 6 || dateDay == 0) {
                if(!confirm('Biztosan hétvégére kérsz szabadságot?'))
                    return;
            }

            $.get({
                url: `/tasks/tasks/create-holiday`,
                data: {
                    userId: userId,
                    date: date,
                },
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
                        $("#calendar").evoCalendar("addCalendarEvent", [
                            {
                                id: data.id,
                                name: userName+(data.conflict?" &#9679;":""),
                                date: date,
                                type: "holiday",
                                color: "gray",
                            }
                        ]);
                    } else {
                        alert(data.error);
                    }
                },
            });
        });
    }
</script>

<script>
    $(document).ready(function() {
        /** Hash alapján modal megnyitása */
        var thisHash = window.location.hash;
        if(thisHash !== null) {
            var hash = thisHash.split("_");
            if(hash.length === 2 && hash[0] === '#event') {
                //$(`[onclick="viewTask(${hash[1]})"]`).click();

                window.location.href = `/tasks/tasks/modify-calendar?eventId=${hash[1]}`;
                /*$.get({
                    url: '/tasks/tasks/modify-calendar',
                    data: {
                        eventId: hash[1],
                    },
                    success: function(data) {
                        $('#calendar-modal').find('.modal-body').html(data);
                        $('#calendar-modal').modal('show');
                    }
                });*/
            }
        }
        /** Hash alapján modal megnyitása end */
    });
</script>