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

<link rel="stylesheet" type="text/css" href="/dist/css/calendar2/main.css"/>
<script src="/dist/js/calendar2/main.js"></script>
<script src="/dist/js/calendar2/locales/hu.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'hu',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                //right: 'dayGridMonth,timeGridWeek,timeGridDay'
                right: 'dayGridMonth'
            },
            initialDate: '2021-02-01',
            firstDay: 1, // Hétfő
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            selectMirror: true,
            select: function(arg) {
                var title = prompt('Event Title:');
                if (title) {
                    calendar.addEvent({
                        title: title,
                        start: arg.start,
                        end: arg.end,
                        allDay: arg.allDay
                    })
                }
                calendar.unselect()
            },
            eventClick: function(arg) {
                if (confirm('Are you sure you want to delete this event?')) {
                    arg.event.remove()
                }
            },
            eventDragStart: function(arg) {
                console.log(arg);
            },
            eventDrop: function(arg) {
                console.log(arg);
            },
            editable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            events: [
                {
                    title: 'All Day Event',
                    start: '2021-02-01'
                },
                {
                    title: 'Long Event',
                    start: '2021-02-07',
                    end: '2021-02-10'
                },
                {
                    groupId: 999,
                    title: 'Repeating Event',
                    start: '2021-02-09'
                },
                {
                    groupId: 999,
                    title: 'Repeating Event',
                    start: '2021-02-16'
                },
                {
                    title: 'Conference',
                    start: '2021-02-11',
                    end: '2021-02-13'
                },
                {
                    title: 'Meeting',
                    start: '2021-02-12T10:30:00',
                    end: '2021-02-12T12:30:00'
                },
                {
                    title: 'Lunch',
                    start: '2021-02-12T12:00:00'
                },
                {
                    title: 'Meeting',
                    start: '2021-02-12T14:30:00'
                },
                {
                    title: 'Happy Hour',
                    start: '2021-02-12T17:30:00'
                },
                {
                    title: 'Dinner',
                    start: '2021-02-12T20:00:00'
                },
                {
                    title: 'Birthday Party',
                    start: '2021-02-13T07:00:00'
                },
                {
                    title: 'Click for Google',
                    url: 'http://google.com/',
                    start: '2021-02-28'
                }
            ]
        });

        calendar.render();
    });

</script>

<div id="calendar"></div>

