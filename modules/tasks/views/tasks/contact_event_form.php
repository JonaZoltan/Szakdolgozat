<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.07.28., 16:47:46
 * The used disentanglement, and any part of the code
 * contact_event_form.php own by the author, Bencsik Matyas.
 */
/* @var $contactEvent ContactEvent*/
/* @var $form */
/* @var $projectId */

use app\modules\partners\models\Contact;
use app\modules\partners\models\ContactEvent;
use kartik\select2\Select2;
use yii\web\JsExpression;

?>
<style>
    .field-contactevent-contact_id .select2-selection {
        min-height: 55px;
    }
</style>
<div class="card">
    <div class="card-body card-outline card-secondary">
        <div class="row">
            <div class="col-sm-6">
	            <?= $form->field($contactEvent, 'contact_id')->widget(Select2::class, [
		            'data' => Contact::allContactNameWithPartnerName(true, $projectId),
		            'language' => 'hu',
		            'disabled' => $contactEvent->isNewRecord && !isset($contactEvent->contact_id),
		            'options' => ['placeholder' => Yii::t('app', 'switch')],
		            'pluginOptions' => [
			            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		            ],

	            ]);
	            ?>
            </div>
            <div class="col-sm-6">
	            <?= $form->field($contactEvent, 'type')->widget(Select2::class, [
		            'data' => ContactEvent::allTypeNames(),
		            'language' => 'hu',
		            'disabled' => $contactEvent->isNewRecord && !isset($contactEvent->contact_id),
		            'options' => ['placeholder' => Yii::t('app', 'switch')]
	            ]);
	            ?>
            </div>
        </div>
    </div>
</div>