<?php
/**
 * @var $partner Partners
 * @var $contact Contact
 * @var $lastContactEvent ContactEvent
 */

use app\modules\partners\models\Contact;
use app\modules\partners\models\ContactEvent;
use app\modules\partners\models\Partners;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

?>
<div class="row">
	<div class="col-sm-4">
		<div class="card card-secondary">
			<div class="card-header">
				<h6 class="card-title">Ügyfél adatok</h6>
			</div>
			<div class="card-body">
				<?= DetailView::widget([
					'model' => $partner,
					'attributes' => [
						'name',
						[
							'attribute' => 'user_ids',
							'value' => function ($model){
								return $model->responsiblesNames;
							}
						],
						[
							'attribute' => 'alert_day',
							'value' => function ($model){
								return $model->alert_day.' nap';
							}
						],
					],
				]) ?>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="card card-secondary">
			<div class="card-header">
				<h6 class="card-title">Ügyfél kapcsolat adatok</h6>
			</div>
			<div class="card-body">
				<?= DetailView::widget([
					'model' => $contact,
					'attributes' => [
					    'name',
                        'email',
                        'tel',
                        'position',
					]
				]) ?>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="card card-secondary">
			<div class="card-header">
				<h6 class="card-title mr-1">Legutolsó kapcsolattartás adatai</h6>
				<?php if($lastContactEvent): ?>
                    <?= Html::a('<i class="fas fa-eye"></i>', Url::to(['/partners/contact-event/view', 'id' => $lastContactEvent->id]),[
                            'target' => '_blank'
                    ]) ?>
                <?php endif; ?>
            </div>
			<div class="card-body">
                <?php if(!$lastContactEvent): ?>
                    Nincs
                <?php else: ?>
				<?= DetailView::widget([
					'model' => $lastContactEvent,
					'attributes' => [
						[
							'attribute' => 'user_id',
							'format' => 'raw',
							'value' => function ($model){
								return $model->userName;
							}
						],
						[
							'attribute' => 'partner_id',
							'format' => 'raw',
							'value' => function ($model){
								return $model->partnerName;
							}
						],
						[
							'attribute' => 'contact_id',
							'format' => 'raw',
							'value' => function ($model){
								return $model->contactName;
							}
						],
						[
							'attribute' => 'type',
							'value' => function ($model){
								return $model->typeName;
							}
						],
						'when',
					],
				]) ?>
                <?php endif; ?>
			</div>
		</div>
	</div>
</div>