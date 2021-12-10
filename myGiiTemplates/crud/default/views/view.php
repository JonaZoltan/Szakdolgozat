<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$tablename = str_replace(" ", "_", strtolower(Inflector::camel2words(StringHelper::basename($generator->modelClass))));

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use <?= $generator->modelClass ?>;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString($tablename) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
        <?= "<?= " ?>$this->context->userCan('update_'.<?= StringHelper::basename($generator->modelClass) ?>::tableName().'')?Html::a(<?= $generator->generateString('update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']):"" ?>
        <?= "<?= " ?>$this->context->userCan('delete_'.<?= StringHelper::basename($generator->modelClass) ?>::tableName().'')?Html::a(<?= $generator->generateString('delete') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('delete_confirm') ?>,
                'method' => 'post',
            ],
        ]):"" ?>
    </p>

    <div class="card card-outline card-primary">
        <div class="card-body">


            <?= "<?= " ?>DetailView::widget([
                'model' => $model,
                'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "                  '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "                  '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
                ],
            ]) ?>

        </div>
    </div>

</div>
