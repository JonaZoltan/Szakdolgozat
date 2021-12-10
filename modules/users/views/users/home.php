<?php

use app\modules\apps\models\Apps;
use app\modules\tasks\models\Holiday;
use app\modules\users\models\User;

$this->title = Yii::t('app', 'yii_base');
$user = User::current();

?>

<?php

    $holidayToday = Holiday::find()
        ->where(['accepted' => 1, 'date' => date('Y-m-d')])
        ->all();

    $holidays = Holiday::find()
        ->where(['accepted' => 1])
        ->andWhere(['between', 'date', date('Y-m-d', strtotime('+1 day')), Apps::getLastDayThisWeek(date('Y-m-d'))])
        ->all();
?>

<div id="user-home">
    <div class="holiday-week">
        <h5>Szabadságon a mai nap:</h5>
        <?php if($holidayToday): ?>
            <?php foreach ($holidayToday as $hToday): ?>
                <span>
                    <b><?= User::findOne($hToday->user_id)->name ?></b><br />
                </span>
            <?php endforeach; ?>
        <?php else: ?>
            <span>
                <i>
                    <i class="far fa-smile" style="color: green"></i>
                    Nincs
                    <i class="far fa-smile" style="color: green"></i>
                </i>
            </span>
        <?php endif; ?>

        <hr />
        <h5>Szabadságon a héten:</h5>
        <?php if($holidays): ?>
            <?php foreach ($holidays as $holiday): ?>
                <span>
                    <b><?= User::findOne($holiday->user_id)->name ?></b> - <?= Apps::dateBeautifier($holiday->date) ?><br />
                </span>
            <?php endforeach; ?>
        <?php else: ?>
            <span>
                <i>
                    <i class="far fa-smile" style="color: green"></i>
                    Nincs
                    <i class="far fa-smile" style="color: green"></i>
                </i>
            </span>
        <?php endif; ?>
    </div>

    <div class="box centered-box">
        <!--<img src="/img/logo.png" />-->
        <img src="/img/TimeMatrix4.png" width="400" />
        <div class="text">
			<?= Yii::t('app', 'welcome_user', ['name' => explode(' ', $this->context->name)[1] ]) ?>
            <div class="subtext">
				<?= Yii::t('app', 'menu_choose') ?>
            </div>
        </div>
    </div>

</div>
