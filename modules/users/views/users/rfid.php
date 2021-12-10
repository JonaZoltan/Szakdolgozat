<?php
use app\modules\users\models\User;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id="login-page">
	<div class="wrapper">
		<div class="logo">
			<img src="/img/logo.png" alt="Szitár" />

			<?php
			$form = ActiveForm::begin([
				'id' => 'login-form',
				'options' => [
					"class" => 'form-signin',
				],
				'fieldConfig' => [
					'options' => [
						//   'tag' => false,
					],
				],
			]) ?>

			<?php ActiveForm::end(); ?>
        </div>
	</div>
</div>
