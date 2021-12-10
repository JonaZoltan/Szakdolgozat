<?php

    use app\modules\apps\models\Apps;
    use yii\helpers\Url;

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Quick menu -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

	    <?php if($user): ?>
		    <?php if($user->getQuickMenu() != null): ?>
			    <?php foreach ($items = Apps::getAllQuickMenu() as $key => $item): ?>
				    <?php if( (isset($item['capability']) ? $this->context->userCan($item['capability']) : $this->context->userCan("view_".$key)) && in_array($key, $user->getQuickMenu())): ?>
                        <li class="nav-item d-none d-sm-inline-block">
                            <a href="<?= Url::to([ (strpos($item['url'], "/") === false) ? "/".$item['url']."/default/index" : "/".$item['url'] ])?>" class="nav-link" data-toggle="tooltip" data-placement="bottom" title="<?= Yii::t('app', $item['translate']) ?>">
                                <i class="fas <?= $item['fa-icon'] ?>"></i>
                            </a>
                        </li>
				    <?php endif; ?>
			    <?php endforeach; ?>
		    <?php endif; ?>
	    <?php endif; ?>

	    <?php if($this->context->is_admin): ?>
            <li class="nav-item d-none d-sm-inline-block">
                <span style="font-size: 25px;">
                    |
                </span>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/clearassets.php" class="nav-link" data-toggle="tooltip" data-placement="bottom" title="Ideiglenes filok törlése!">
                    <i class="fas fa-bug"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/apps/migrate/up" class="nav-link" data-toggle="tooltip" data-placement="bottom" title="Migrate UP">
                    <i class="fas fa-database"></i>
                </a>
            </li>
	    <?php endif;  ?>
    </ul>
    <!-- Quick menu -->

    <!-- SEARCH FORM -->
    <!--<form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>-->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <?php require_once('_user_menu.inc.php') ?>
    </ul>
</nav>