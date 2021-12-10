<?php
namespace derekisbusy\popper;

use yii\web\AssetBundle;

class PopperAsset extends AssetBundle
{
    public $sourcePath = '@vendor/derekisbusy/yii2-popper';
    
    public $js = [
        YII_ENV_DEV ? 'js/popper.js' : 'js/popper.min.js',
    ];
}