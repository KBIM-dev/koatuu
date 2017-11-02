<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use backend\controllers\EventsController;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\HistoryOfContacts;
/**
 * @var \yii\web\View $this
 * @var common\models\Profile $profile
 * @var \yii\data\ActiveDataProvider $eventsDataProvider
 */
$this->title = empty($profile->user->getFullName()) ? Html::encode($profile->name) : Html::encode($profile->user->getFullName());
$this->params['breadcrumbs'][] = $this->title;

	/**
	 * @var $role \yii\rbac\Role
	 */
$roles = \Yii::$app->authManager->getRolesByUser($profile->user->id);

?>
	<style>
		h1 {
			display: none;
		}
	</style>
	<div class="clearfix"></div>
    <div class="row" style="margin-top: 25px;">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <h3 class="profile-username text-center"><?=$profile->last_name . " " . $profile->name . " " . $profile->middle_name . " "?></h3>
                    <?php if(isset($profile->user->badges) && is_array($profile->user->badges)): ?>
                        <hr>
                        <strong><i class="fa fa-gear margin-r-5"></i> <?=Yii::t('user-profile', 'Badges');?></strong>
                        <p>
							<?php
								foreach($roles as $key=>$role ) { ?>
									<span class="label label-success"><?=$role->description?></span>
							<?php	} ?>
                            <?php foreach($profile->user->badges as $badge) { ?>
                                <?php
                                /**
                                 * @var $badge \common\models\Badge
                                 */
                                ?>
                                <?php if(isset($badge->name)): ?>
                                    <span class="label label-info"><?=$badge->name?></span>
                                <?php endif; ?>
                            <?php } ?>
                        </p>
                    <?php endif; ?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('user-profile','About Me')?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <strong><i class="fa fa-map-marker margin-r-5"></i> <?=Yii::t('user-profile', 'Location');?>
                    </strong>
                    <p class="text-muted">
                        <?=Html::encode(trim(($profile->user->koatuus ? $profile->user->koatuus->allAddressSting : '').' '.$profile->user->addressString))?>
                    </p>
                    <hr>
                    <strong><i class="fa fa-google margin-r-5"></i> <?=Yii::t('user-profile', 'Email');?></strong>
                    <p class="text-muted">
                        <?php if(!empty($profile->user->email)): ?>
                            <?=Html::a(Html::encode($profile->user->email), 'mailto:' . Html::encode($profile->user->email))?>
						<?php else: ?>
							<?= Yii::t('user-profile','Not set') ?>
						<?php endif; ?>
                    </p>
                    <hr>
                    <strong><i class="fa fa-phone margin-r-5"></i> <?=Yii::t('user-profile', 'Phone');?></strong>
                    <p class="text-muted">
                        <?php if(!empty($profile->user->phone)): ?>
                            <?=Html::a(Html::encode('+' . $profile->user->phone), 'tel:+' . Html::encode($profile->user->phone))?>
						<?php else: ?>
							<?= Yii::t('user-profile','Not set') ?>
						<?php endif; ?>
                    </p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
						<a href="#activity" data-toggle="tab">
							<i class="fa fa-info-circle" aria-hidden="true"></i>
							<?=Yii::t('user-profile', 'Info')?>
                        </a>
					</li>
				</ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                       <?= $this->render('tabs/_info_tab', [
                       	'profile' => $profile
					   ])?>
                    </div>
				</div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
