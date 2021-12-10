<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.03.30., 16:38:46
 * The used disentanglement, and any part of the code
 * month_sum_pdf.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'timematrix' project.

use app\modules\apps\models\Apps;
/** @var  $sumHours */
/** @var  $data */
/** @var  $getData */
/** @var  $workTypes */
/** @var  $projects */
/** @var $model */
$sumHours = 0;
//
?>

<img src="img/logo.png" alt="logo" width="100px">
<h3 align="center"><u>Teljesítési igazolás</u></h3>

<p class="fonts-size"><b>Munkavállaló neve: </b><?= $data['username'] ?></p>
<p class="fonts-size"><b>Teljesítési időszak: </b><?= Apps::getFirstDay($getData->searchDate) ?> - <?= Apps::getLastDay($getData->searchDate) ?></p>

<hr>
<?php $sumVerifiedHours = 0 ?>
<?php foreach($data['tasks'] as $projectId => $item): ?>

	<?php $sumProjectHour = 0 ?>

<?php if($projectId != 0): ?>
    <h5><u><?= $projects[$projectId]['name'] ?></u></h5>
<?php else: ?>
    <h5><u>Általános</u></h5>
<?php endif; ?>

    <table class="fonts-size" style="width: 100%; padding: 0; border-inline: 1px solid black">
        <thead>
        <tr>
            <th class="fonts-size" style="padding: 0; border-bottom: 1px solid black"><b>Munkatípus</b></th>
            <th class="fonts-size" style="padding: 0; border-bottom: 1px solid black"><b>Munkaóra</b></th>
            <th class="fonts-size" style="padding: 0; border-bottom: 1px solid black"><b>Elfogadott munkaóra</b></th>

        </tr>
        </thead>
        <tbody>
		<?php foreach ($item as $workTypeId => $hours): ?>
            <tr>
                <td class="fonts-size" style="padding: 0; border-bottom: 1px solid black"><?= $workTypes[$workTypeId]['title'] ?></td>
                <td class="fonts-size" style="padding: 0; border-bottom: 1px solid black"><?= round($hours["Workhour"]/60, 1) ?></td>
                <td class="fonts-size" style="padding: 0; border-bottom: 1px solid black"><?= round($hours["Verified"]/60, 1) ?></td>
				<?php $sumProjectHour += round($hours["Workhour"]/60, 1)?>
				<?php $sumHours += round($hours["Workhour"]/60, 1)?>
				<?php $sumVerifiedHours += round($hours["Verified"]/60, 1)?>
            </tr>
		<?php endforeach; ?>
        <tr>
            <td class="fonts-size" style="padding: 0"><b>Összesen:</b></td>
            <td class="fonts-size" style="padding: 0"><?= $sumProjectHour ?></td>
            <td class="fonts-size" style="padding: 0"><?= $sumVerifiedHours ?></td>
            <?php $sumVerifiedHours = 0; ?>
        </tr>

        </tbody>
    </table>

<?php endforeach; ?>

<div>

    <p class="fonts-size"><b>Összes munkaóra: </b>
        <span><b> <?= $sumHours ?></b></span>
    </p>
</div>


<?php if($data['holidays']): ?>

    <p class="fonts-size"><b>Szabadnapok: </b><br>
	<?= implode(" | ",$data['holidays']) ?>
    </p>
<?php endif; ?>


<p class="fonts-size" style="padding-top: 40px; text-align: justify">
    <?= $data['text'] ?>

</p>


<div class="fonts-size" style="position: absolute; bottom: 60px">
    <p>
        <b>Nyíregyháza, <br>
        <?= $today = date("Y-m-d") ?></b>
    </p>
</div>


<div style="position: absolute; bottom: 60px; right: 60px; text-align: center; float: right">
    .......................................................<br>
    Aláírás

</div>

<?php //var_dump($data); ?>
