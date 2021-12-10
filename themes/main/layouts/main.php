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
    <?= Html::csrfMetaTags() ?>
	<?php $this->head() ?>

    <!-- CSS -->
        <!-- bootstrap -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <!-- bootstrap -->

        <!-- Main/Theme -->
            <link rel="stylesheet" href="/dist/css/theme.min.css">
            <link rel="stylesheet" href="/dist/css/main.css">
        <!-- Main/Theme -->

        <!-- Perfect scrollbar -->
            <link rel="stylesheet" href="/dist/css/perfect-scrollbar.css" type="text/css" />
        <!-- Perfect scrollbar end -->

        <!-- Toaster -->
            <link rel="stylesheet" href="/dist/css/toastr.min.css">
        <!-- Toaster end -->

        <!-- Font -->
            <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
        <!-- Font -->

        <!-- Summernote -->
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <!-- Summernote end -->
    <!-- CSS END -->

    <!-- JS -->
        <!-- jQuery -->
            <script src="/dist/js/jquery-3.5.1.min.js"></script>
        <!-- jQuery -->

            <script src="/dist/js/popper.min.js"></script>
            <script src="https://unpkg.com/tooltip.js"></script>

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

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed <?= isset($_COOKIE['slided']) ? "sidebar-collapse" : "" ?>">
<?php $this->beginBody() ?>
    <div class="wrapper">

        <!-- Navigation(right) bar -->
	    <?php require_once('_right_side_bar.inc.php') ?>
        <!-- Navigation(right) bar -->

        <!-- Navigation bar -->
	    <?php require_once('_side_bar.inc.php') ?>
        <!-- Navigation bar -->

        <div class="content-wrapper">
            <!--
	        <?php if (Yii::$app->controller->id . "/" . Yii::$app->controller->action->id != "users/home"):  ?>
                <?= Html::a('<i class="fas fa-angle-double-left"></i> '.Yii::t('app', 'back'),
                    Url::toRoute(Yii::$app->request->referrer ?: '/'),
                    ['class' => 'btn btn-primary btn-back']) ?>
	        <?php endif; ?>
	        -->

            <!-- Breadcrumbs -->
            <?= Breadcrumbs::widget([
                'homeLink' => [
                    'label' => '<i class="fas fa-home"></i> '.Yii::t('app', 'home'),
                    'url' => ['/'],
                    'template' => "\n\t<li class=\"breadcrumb-item\"><b>{link}</b></li>\n"
                ],
	            'itemTemplate' => "\n\t<li class=\"breadcrumb-item\"><b>{link}</b></li>\n",
                'activeItemTemplate' => "\t<li class=\"breadcrumb-item active\">{link}</li>\n",
                'encodeLabels' => false,
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- Breadcrumbs -->

            <!-- Main -->
            <?= $content ?>
            <!-- Main -->

	        <?php include("_footer.inc.php") ?>
        </div>
    </div>

    <div id="task-view-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rögzítendő adatok megtekintése</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?= Yii::t('app', 'close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'close') ?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="calendar-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Szabadság kérelem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?= Yii::t('app', 'close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <!-- JS 2.0 -->
        <!-- Summernote -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <!-- Summernote end -->
        <script src="/dist/js/main.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

        <!-- Bootbox -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.js"></script>
        <!-- Bootbox end -->

        <!-- Toaster -->
            <script src="/dist/js/toastr.min.js"></script>
        <!-- Toaster end -->
    <!-- JS 2.0 end -->
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>