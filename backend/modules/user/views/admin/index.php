<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use common\models\User;
use dektrium\user\models\UserSearch;
use yii\data\ActiveDataProvider;
use kartik\export\ExportMenu;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 * @var User $model
 */

$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', [
    'module' => Yii::$app->getModule('user'),
]) ?>

<?= $this->render('/admin/_menu') ?>

<?php Pjax::begin() ?>
<?php
	//Define action button
	$template = '{update}&nbsp;&nbsp;';
	if(Yii::$app->user->can('permit')){
		$template .= '{permit}&nbsp;&nbsp;';
	}
	if(Yii::$app->user->can('deleteUser')){
		$template .= '{delete}';
	}
    $gridColumns = [
        [
            'attribute' => 'username',
            'format'	=> 'raw',
        ],
        'email:email',
        [
            'attribute' => 'registration_ip',
            'value' => function ($model) {
                return $model->registration_ip == null
                    ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                    : $model->registration_ip;
            },
            'format' => 'html',
        ],
        'created_at:datetime',
        [
            'header' => Yii::t('user', 'Confirmation'),
            'value' => function ($model) {
                if ($model->isConfirmed) {
                    return '<div class="text-center">
                                    <span class="text-success">' . Yii::t('user', 'Confirmed') . '</span>
                                </div>';
                } else {
                    return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                    ]);
                }
            },
            'format' => 'raw',
            'visible' => Yii::$app->getModule('user')->enableConfirmation,
        ],
        [
            'header' => Yii::t('user', 'Block status'),
            'value' => function ($model) {
                if ($model->isBlocked) {
                    return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                    ]);
                } else {
                    return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                    ]);
                }
            },
            'format' => 'raw',
        ],
        ['class' => 'yii\grid\ActionColumn',
         'template' => $template,
         'buttons' =>
             [
                 'permit' => function ($url, $model) {
                     return Html::a('<span class="glyphicon glyphicon-wrench"></span>', Url::to(['/permit/user/view', 'id' => $model->id]), [
                         'title' => Yii::t('yii', 'Change user role')
                     ]); },
             ]
        ],
    ];
?>

<?=ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'fontAwesome' => true,
    'exportConfig' => [
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_PDF => false,
        ExportMenu::FORMAT_CSV => false,
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_EXCEL => false,
    ],
    'columns' => array_slice($gridColumns, 0, sizeof($gridColumns)-1, true),
    'target' => ExportMenu::TARGET_BLANK,
    'batchSize' => Yii::$app->request->get('per-page') ? Yii::$app->request->get('per-page') : 20,
    'folder' => Yii::getAlias('@backend/web/tmp'),
    'showConfirmAlert' => false,
    'dropdownOptions' => [
        'label' => Yii::t('export', 'Export All'),
        'class' => 'btn btn-default'
    ],
    'filename' => $this->title,
    'showColumnSelector' => false,
])?>
<?= GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'filterModel'   =>  $searchModel,
    'layout'        =>  "{items}\n{pager}",
    'pager'=>[
        'firstPageLabel' => '<i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i>',
        'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
        'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
        'lastPageLabel' => '<i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i>',
        'activePageCssClass' => 'active',
    ],
    'columns' => $gridColumns,
]); ?>

<?php Pjax::end() ?>
