<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.02.16., 11:08:46
 * The used disentanglement, and any part of the code
 * siteError.php own by the author, Bencsik Matyas.
 */


$exception = Yii::$app->errorHandler->exception;
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//var_dump($exception);

?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/css/error.css" type="text/css">
        <title>Error <?=$exception->statusCode?></title>
    </head>
    <body>
    <div class="logo">
        <img src="/img/logo.png" />
    </div>
    <div class="error-handler-container">
        <div class="error-handler">
            <h1>Error</h1>
            <h2><?= $exception->statusCode ?> <?= $exception->getMessage() ? " - ".$exception->getMessage() : ''?></h2>
            <p><?= $actual_link ?></p>
            <?php if($exception->statusCode === 404) : ?>
            <p class="credential">Please retype the URI with the correct form! Or visit The <a href="/">main page</a> and try to get another link to use the function!</p>
            <p class="credential">Send an email to <a href="mailto: support@szitar.hu">support</a>, write about the problem!</p>
            <?php endif; ?>
        </div>
    </div>

    </body>
</html>



