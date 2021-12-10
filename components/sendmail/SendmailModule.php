<?php

namespace app\components\sendmail;

/**
 * sendmail module definition class
 */
class SendmailModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\components\sendmail\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
