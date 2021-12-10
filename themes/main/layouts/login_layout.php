<?php
use app\modules\settings\models\VersionHistory;
use app\modules\users\models\User;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\Breadcrumbs;

$user = User::current();

/*from BaseController*/
$context = $this->context;
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?=$this->title?></title>
		<?php $this->head() ?>

        <!-- CSS -->
        <!-- bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <!-- bootstrap -->

        <!-- Main/Theme -->
        <link rel="stylesheet" href="/dist/css/theme.min.css">
        <link rel="stylesheet" href="/dist/css/main.css">
        <link rel="stylesheet" href="/dist/css/dashboard.css">
        <!-- Main/Theme -->

        <!-- GridStack - Dashboard -->
        <link rel="stylesheet" href="/dist/css/gridstack.min.css" type="text/css" />
        <!-- GridStack - Dashboard end -->

        <!-- Perfect scrollbar -->
        <link rel="stylesheet" href="/dist/css/perfect-scrollbar.css" type="text/css" />
        <!-- Perfect scrollbar end -->

        <!-- Font -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
        <!-- Font -->
        <!-- CSS END -->

        <!-- JS -->
        <!-- jQuery -->
        <script src="/dist/js/jquery-3.5.1.min.js"></script>
        <!-- jQuery -->

        <!-- bootstrap -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <!-- bootstrap -->

        <!-- Perfect scrollbar -->
        <script src="/dist/js/perfect-scrollbar.js"></script>
        <!-- Perfect scrollbar end -->

        <!-- Cookie -->
        <script src="/dist/js/js.cookie.min.js"></script>
        <!-- Cookie end -->

        <!-- Main/Theme -->
        <script src="/dist/js/theme.js"></script>
        <!-- Main/Theme -->

        <!-- GridStack - Dashboard -->
        <script src="/dist/js/gridstack.all.js"></script>
        <!-- GridStack - Dashboard end -->

        <!-- Font icon JS -->
        <script src="/dist/js/fontawesome/all.js"></script>
        <!-- Font icon JS -->
        <!-- JS end -->

        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
    </head>

    <body class="hold-transition login-page">
	<?php $this->beginBody() ?>

    <!-- Main -->
	<?= $content ?>
    <!-- Main -->

    <!-- JS 2.0 -->
    <script src="/dist/js/dashboard.js"></script>
    <script src="/dist/js/login.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <!-- JS 2.0 end -->
	<?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage() ?>