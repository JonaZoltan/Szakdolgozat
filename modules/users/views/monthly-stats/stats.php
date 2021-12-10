<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.03.31., 14:17:46
 * The used disentanglement, and any part of the code
 * stats.php own by the author, Bencsik Matyas.
 */
/**@var $baseData*/
/**@var $sumHours */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\modules\formschemes\models\FormSchemes*/
/* @var $text */

//@todo Implement in this 'timematrix' project.

/** @var \app\modules\users\models\User $users */

use app\modules\tasks\models\Tasks;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'certificate_of_accomplishment');

$getData = json_decode(base64_decode($baseData));
$searchDate = isset($getData->searchDate) ? $getData->searchDate : '';
//var_dump($getData); die();

?>


<h1><?= Html::encode($this->title) ?></h1>
<hr>


<div class="row">
    <div class="col-sm-2">
		<?= \kartik\widgets\DatePicker::widget([
			'name' => 'dp_5',
			'id' => 'date_select',
			'type' => \kartik\date\DatePicker::TYPE_INLINE,
			'value' => $searchDate,
			'pluginOptions' => [
				'format' => 'yyyy-mm',
				'startView' => 'month',
				'minViewMode' => 'months'
			]
		]) ?>
    </div>
    <div class="col-sm-10">

	    <?= HTML::textarea('text', $model->text, ['cols' => 150, 'rows' => 9, 'class' => 'pdf-text form-control', 'id' => 'pdf-id', ])?>

    </div>

</div>
<div class="row">

	    <?php foreach($users as $user): ?>
    <div class="col-sm-4">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <img class="user-picture"  src="<?= $user->photoUrl() ?>" alt="">
	                    <?= $user->name ?>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Havi munkaóra: <?= round($sumHours[$user->id]/60, 1) ?></p>
					    <?= Html::a('<i class="fas fa-file-pdf"></i> PDF', Url::to(['/users/monthly-stats/pdf', 'id' => $user->id, 'baseData' => $baseData]), ['class' => 'btn btn-primary pdf-button']) ?>
                    </div>
                </div>
    </div>
	    <?php endforeach; ?>

</div>


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

<script>
    $('.pdf-button').on('click', function (e){
        // console.log(e);
        // e.preventDefault();
        let text = $('#pdf-id').val();
        let href = $(this).attr('href');
        let newHref = href + '&text=' + text;
        console.log(href);

        $(this).attr('href', newHref);
    });


</script>
