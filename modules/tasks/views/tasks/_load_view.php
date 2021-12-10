<?php

use app\modules\project\models\Project;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use yii\helpers\Json;

	/**
	 * @var $projectIds
	 * @var $worktypeIds
	 * @var $workplaceIds
	 * @var $date
	 * @var $dateStart
	 * @var $dateEnd
	 * @var $text
	 */

	$projectIds = Json::decode($projectIds);
	$worktypeIds = Json::decode($worktypeIds);
	$workplaceIds = Json::decode($workplaceIds);
	$date = Json::decode($date);
	$dateStart = Json::decode($dateStart);
	$dateEnd = Json::decode($dateEnd);
	$text = Json::decode($text);
?>

<table class="table">
    <tr>
        <th><?= Yii::t('app', 'project_id') ?></th>
        <th><?= Yii::t('app', 'worktype_id') ?></th>
        <th><?= Yii::t('app', 'workplace_id') ?></th>
        <th><?= Yii::t('app', 'working_datetime_start') ?></th>
        <th><?= Yii::t('app', 'working_datetime_end') ?></th>
        <th><?= Yii::t('app', 'working_hours') ?></th>
    </tr>

    <?php $sumWorkingTime = 0; ?>
    <?php foreach ($projectIds as $index => $projectId): ?>

        <?php $pId = Project::findOne($projectId) ?>
        <?php $tId = Worktype::findOne($worktypeIds[$index]) ?>
        <?php $plId = Workplace::findOne($workplaceIds[$index]) ?>

        <tr>
            <td><?= $pId?$pId->name:'-' ?></td>
            <td><?= $tId?$tId->title:'-' ?></td>
            <td><?= $plId?$plId->title:'-' ?></td>
            <td><?= $date[$index] ?> <?= $dateStart[$index]?:'-' ?></td>
            <td><?= $date[$index] ?> <?= $dateEnd[$index]?:'-' ?></td>
            <td>
                <?php if($dateStart[$index] && $dateEnd[$index]): ?>
                    <?php
                        $date1 = new DateTime($dateStart[$index]);
                        $date2 = new DateTime($dateEnd[$index]);
                        $diff = $date2->diff($date1);

                        $time = ($diff -> i + $diff->h*60 + $diff->d*24) / 60;
	                    $sumWorkingTime += $time;
                    ?>

                    <?= $time ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>

    <?php endforeach; ?>

</table>

<span>
    <b>Összes munkaóra:</b> <?= $sumWorkingTime ?>
    <?php if($sumWorkingTime!=8.5 || $sumWorkingTime!=5.5): ?>
        <span style="color: red;"><i class="fas fa-exclamation"></i></span>
    <?php endif; ?>
</span>
