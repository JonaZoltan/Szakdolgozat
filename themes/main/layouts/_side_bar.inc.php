<?php

use app\modules\tasks\models\Tasks;
use app\modules\users\models\User;
use yii\helpers\Url;

$adminIcon = '<i class="fas fa-user-lock"></i>';

?>
<aside class="main-sidebar sidebar-light-white elevation-4">
    <a href="/" class="brand-link elevation-4">
        <img src="/img/TimeMatrix4.png">
    </a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar Menu -->
		<nav class="mt-2">
            <!-- Cím -->
                <!-- <li class="nav-header">EXAMPLES</li> -->
            <!-- Cím -->

            <!-- Aktív lenyíló menü -->
                <!--
                <li class="nav-item has-treeview menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index2.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index3.html" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li>
                    </ul>
                </li>
                -->
            <!-- Aktív lenyíló menü -->

            <!-- Lenyíló menü -->
                <!--
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Layout Options
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">6</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/layout/top-nav.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Top Navigation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Top Navigation + Sidebar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/boxed.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Boxed</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Sidebar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-topnav.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Navbar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-footer.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Footer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/collapsed-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Collapsed Sidebar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                -->
            <!-- Lenyíló menü -->

            <!-- Azonali link -->
                <!--
				<li class="nav-item">
					<a href="pages/widgets.html" class="nav-link">
						<i class="nav-icon fas fa-th"></i>
						<p>
							Widgets
							<span class="right badge badge-danger">New</span>
						</p>
					</a>
				</li>
                -->
            <!-- Azonali link -->

            <!-- Multi level -->
                <!--
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="fas fa-circle nav-icon"></i>
						<p>Level 1</p>
					</a>
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-circle"></i>
						<p>
							Level 1
							<i class="right fas fa-angle-left"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="#" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Level 2</p>
							</a>
						</li>
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>
									Level 2
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="far fa-dot-circle nav-icon"></i>
										<p>Level 3</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="far fa-dot-circle nav-icon"></i>
										<p>Level 3</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="far fa-dot-circle nav-icon"></i>
										<p>Level 3</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Level 2</p>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="fas fa-circle nav-icon"></i>
						<p>Level 1</p>
					</a>
				</li>
                -->
            <!-- Multi level -->

            <!-- COLOR labels -->
                <!--
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon far fa-circle text-danger"></i>
						<p class="text">Important</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon far fa-circle text-warning"></i>
						<p>Warning</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon far fa-circle text-info"></i>
						<p>Informational</p>
					</a>
				</li>
                -->
            <!-- COLOR labels -->

			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= Url::to(['/tasks/tasks/create']) ?>" class="nav-link nav-link-target">
                        <i class="fas fa-plus-circle"></i>
                        <p><?= Yii::t('app', 'task') ?></p>
                    </a>
                </li>

				<?php $hasTask = Tasks::find()->where(['user_id' => User::current()->id, 'date(working_datetime_start)' => date('Y-m-d')])->one() ?>
				<?php if(!$hasTask): ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/tasks/tasks/create-day']) ?>" class="nav-link nav-link-target">
                            <i class="fas fa-plus-circle"></i>
                            <p><?= Yii::t('app', 'create_day_tasks') ?></p>
                        </a>
                    </li>
				<?php else: ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/tasks/tasks/update-day', 'id' => $hasTask->id]) ?>" class="nav-link nav-link-target">
                            <i class="fas fa-plus-circle"></i>
                            <p><?= Yii::t('app', 'update_day_tasks') ?></p>
                        </a>
                    </li>
				<?php endif; ?>

                <li class="nav-item">
                    <a href="<?= Url::to(['/partners/contact-event/create']) ?>" class="nav-link nav-link-target">
                        <i class="fas fa-plus-circle"></i>
                        <p><?= Yii::t('app', 'contact_event') ?></p>
                    </a>
                </li>

                <!-- Alap modul -->
                    <li class="nav-item has-treeview menu-group">
                        <a href="#" class="menu-section nav-link">
                            <span class="icon-group"><i class="fas fa-house-user"></i></span>
                            <p>
                                <?= Yii::t('app', 'basic_module') ?>
                                <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                            </p>
                        </a>

	                    <?php
	                    if ($context->userCanOne(["users", "logins", "permissiongroups", "permissions"])): ?>
                        <!-- Felhasználók -->
                            <ul class="nav nav-treeview">
                                <li class="nav-item has-treeview menu">
                                    <a href="#" class="nav-link">
                                        <span class="icon"><i class="fas fa-user-circle"></i></span>
                                        <p>
                                            <?= Yii::t('app', 'users') ?>
                                            <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <?php if ($context->userCan("users")): ?>
                                            <li class="nav-item">
                                                <a href="<?= Url::to(["/users/users/index"]) ?>" class="nav-link">
                                                    <p><?= Yii::t('app', 'all_user') ?></p>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($context->userCan("permissiongroups")): ?>
                                            <li class="nav-item">
                                                <a href="<?= Url::to(["/users/permissionsets/index"]) ?>" class="nav-link">
                                                    <p><?= Yii::t('app', 'permission_set') ?></p>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($context->userCan("logins")): ?>
                                            <li class="nav-item">
                                                <a href="<?= Url::to(["/users/logins/index"]) ?>" class="nav-link">
                                                    <p><?= Yii::t('app', 'sessions') ?></p>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($context->is_admin): ?>
                                            <li class="nav-item">
                                                <a href="<?= Url::to(["/users/permissions/index"]) ?>" class="nav-link">
                                                    <p><?= Yii::t('app', 'permissions') ?> <span class="admin-tooltip"><?= $adminIcon ?></span></p>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($context->is_admin): ?>
                                            <li class="nav-item">
                                                <a href="<?= Url::to(["/users/capabilities/index"]) ?>" class="nav-link">
                                                    <p><?= Yii::t('app', 'capabilitys') ?> <span class="admin-tooltip"><?= $adminIcon ?></span></p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            </ul>
                        <!-- Felhasználók end -->
                        <?php endif; ?>

	                    <?php if ($context->userCan("logs")): ?>
                        <!-- Naplózás -->
                            <ul class="nav nav-treeview">
                                <li class="nav-item has-treeview menu">
                                    <a href="#" class="nav-link">
                                        <span class="icon"><i class="fas fa-book"></i></span>
                                        <p>
                                            <?= Yii::t('app', 'logs') ?>
                                            <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?= Url::to(["/logs/logs/index"]) ?>" class="nav-link">
                                                <p><?= Yii::t('app', 'logs_entires') ?></p>
                                            </a>
                                        </li>

                                        <?php if ($context->is_admin): ?>
                                            <li class="nav-item">
                                                <a href="<?= Url::to(["/logs/events/index"]) ?>" class="nav-link">
                                                    <p><?= Yii::t('app', 'events') ?> <span class="admin-tooltip"><?= $adminIcon ?></span></p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            </ul>
                        <!-- Naplózás end -->
                        <?php endif; ?>

	                    <?php if ($context->is_admin): ?>
                        <!-- Beállítások -->
                            <ul class="nav nav-treeview">
                                <li class="nav-item has-treeview menu">
                                    <a href="#" class="nav-link">
                                        <span class="icon"><i class="fas fa-users-cog"></i></span>
                                        <p>
						                    <?= Yii::t('app', 'settings') ?>
                                            <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?= Url::to(["/settings/versionhistory/index"]) ?>" class="nav-link">
                                                <p><?= Yii::t('app', 'version_history') ?> <span class="admin-tooltip"><?= $adminIcon ?></span></p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="<?= Url::to(["/settings/default/index"]) ?>" class="nav-link">
                                                <p><?= Yii::t('app', 'system_settings') ?> <span class="admin-tooltip"><?= $adminIcon ?></span></p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        <!-- Beállítások end -->
	                    <?php endif; ?>

                        <!-- Hibajelentések -->
                        <ul class="nav nav-treeview">
                            <li class="nav-item has-treeview menu">
                                <a href="#" class="nav-link">
                                    <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
                                    <p>
					                    <?= Yii::t('app', 'error_reports') ?>
                                        <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= Url::to(["/errors/errors/create"]) ?>" class="nav-link">
                                            <p><?= Yii::t('app', 'send_error_report') ?></p>
                                        </a>
                                    </li>

                                    <?php if($context->is_admin): ?>
                                        <li class="nav-item">
                                            <a href="<?= Url::to(["/errors/errors/index"]) ?>" class="nav-link">
                                                <p><?= Yii::t('app', 'error_reports') ?> <span class="admin-tooltip"><?= $adminIcon ?></span></p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="<?= Url::to(["/errors/subjects/index"]) ?>" class="nav-link">
                                                <p><?= Yii::t('app', 'subjects') ?> <span class="admin-tooltip"><?= $adminIcon ?></span></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        </ul>
                        <!-- Hibajelentések end -->
                    </li>
                <!-- Alap modul -->
                <!--Project modul-->
                <li class="nav-item has-treeview menu-group">
                        <a href="#" class="menu-section nav-link">
                        <span class="icon-group"><i class="fas fa-tasks"></i></span>
                        <p>
							<?= Yii::t('app', 'Projektek') ?>
                            <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
	                    <?php if ($context->userCan('view_project')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(["/project/project/index"]) ?>" class="nav-link">
                                <span class="admin-tooltip"><?= $adminIcon ?></span> <p><?= Yii::t('app', 'Projekt') ?></p>
                            </a>
                        </li>
	                    <?php endif; ?>
	                    <?php if ($context->userCan('view_project_membership')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(["/project/project-membership/index"]) ?>" class="nav-link">
                                <i class="fas fa-user-plus"></i> <p><?= Yii::t('app', 'project_membership') ?></p>
                            </a>
                        </li>
	                    <?php endif; ?>
	                    <?php if ($context->userCan('view_tasks')): ?>
                        <li class="nav-item">
                                <a href="<?= Url::to(["/tasks/tasks/index"]) ?>" class="nav-link">
                                    <i class="fas fa-exclamation-circle"></i> <p><?= Yii::t('app', 'tasks') ?></p>
                                </a>
                            </li>
	                    <?php endif; ?>
	                    <?php if ($context->userCan('view_area')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['/project/area/index']) ?>" class="nav-link">
                                <i class="fas fa-layer-group"></i> <p><?= Yii::t('app', 'Terület') ?></p>
                            </a>
                        </li>
	                    <?php endif; ?>
	                    <?php if ($context->userCan('view_worktype')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['/tasks/worktype/index']) ?>" class="nav-link">
                                <i class="fas fa-code-branch"></i> <p><?= Yii::t('app', 'Munkatípus') ?></p>
                            </a>
                        </li>
	                    <?php endif; ?>
	                    <?php if ($context->userCan('view_workplace')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['/tasks/workplace/index']) ?>" class="nav-link">
                                <i class="fas fa-swatchbook"></i> <p><?= Yii::t('app', 'Munkavégzés helye') ?></p>
                            </a>
                        </li>
	                    <?php endif; ?>
                    </ul>
                </li>
                <!--Project modul end -->
                <!--Partnerek modul-->
                <li class="nav-item has-treeview menu-group">
                    <a href="#" class="menu-section nav-link">
                        <span class="icon-group"><i class="fas fa-users"></i></i></span>
                        <p>
							<?= Yii::t('app', 'Clients') ?>
                            <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
	                    <?php if ($context->userCan('view_project')): ?>
                            <li class="nav-item">
                                <a href="<?= Url::to(["/partners/partners/index"]) ?>" class="nav-link">
                                    <i class="fas fa-user-friends"></i> <p><?= Yii::t('app', 'partners') ?></p>
                                </a>
                            </li>
	                    <?php endif; ?>
	                    <?php if ($context->userCan('view_project_membership')): ?>
                            <li class="nav-item">
                                <a href="<?= Url::to(["/partners/contact-event/index"]) ?>" class="nav-link">
                                    <i class="fas fa-comments"></i> <p><?= Yii::t('app', 'contact_event') ?></p>
                                </a>
                            </li>
	                    <?php endif; ?>
                    </ul>
                </li>
                <!--Partnerek modul end -->
                <!--Szabadság modul -->
                <li class="nav-item has-treeview menu-group">
                    <a href="#" class="menu-section nav-link">
                        <span class="icon-group"><i class="fas fa-laptop-house"></i></span>
                        <p>
							<?= Yii::t('app', 'Szabadság') ?>
                            <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= Url::to(["/tasks/holiday-extra/index"]) ?>" class="nav-link">
                                <i class="fas fa-calendar-alt"></i> <p><?= Yii::t('app', 'Éves szabadságok') ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= Url::to(["/tasks/tasks/calendar"]) ?>" class="nav-link">
                                <i class="fas fa-calendar"></i> <p><?= Yii::t('app', 'Szabadságtervező/Naptár') ?></p>
                            </a>
                        </li>
                    </ul>
                </li>

				<?php if($context->userCanOne(['asd']) || true): ?>
                <li class="nav-item has-treeview menu-group">
                    <a href="#" class="menu-section nav-link">
                        <i class="fas fa-hands-helping"></i>
                        <p>
							<?= Yii::t('app', 'Döntéstámogatás') ?>
                             <span class="arrow"><i class="right fas fa-angle-left"></i></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= Url::to(["/controlling/project-stat/index"]) ?>" class="nav-link">
                                <i class="fas fa-signal"></i> <p>Projekt Statisztika</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= Url::to(["/controlling/sum-list/sum-list"]) ?>" class="nav-link">
                                <i class="fas fa-table"></i> <p>Összesítő táblázat</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= Url::to(["/controlling/timeline/index"]) ?>" class="nav-link">
                                <i class="fas fa-stream"></i> <p>Timeline</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= Url::to(["/users/monthly-stats/index"]) ?>" class="nav-link">
                                <i class="fas fa-file"></i> <p>Teljesítési igazolások</p>
                            </a>
                        </li>

                        <?php if($context->is_admin): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(["/formschemes/form-schemes/index"]) ?>" class="nav-link">
                                <i class="fas fa-quote-right"></i> <p>Sémák</p>
                            </a>
                        </li>
                        <?php endif; ?>

	                    <?php if ($context->userCan('view_finance')): ?>
                            <li class="nav-item">
                                <a href="<?= Url::to(["/controlling/cost-list/cost-list"]) ?>" class="nav-link">
                                    <i class="fas fa-money-bill-wave"></i> <p>Költségelemzés</p>
                                </a>
                            </li>
	                    <?php endif; ?>
	                    <?php endif; ?>
                    </ul>
                </li>
                    <!--Project modul End-->



            </ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>