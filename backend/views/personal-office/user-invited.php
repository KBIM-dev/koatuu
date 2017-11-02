<?php

use common\models\User;
use yii\helpers\Html;
	use yii\grid\GridView;
	use nterms\pagesize\PageSize;
	use yii\helpers\Url;
	use kartik\export\ExportMenu;

	/* @var $this yii\web\View */
	/* @var $searchModel backend\models\UserSearch */
	/* @var $dataProvider yii\data\ActiveDataProvider */

	$this->title = Yii::t('personal_office', 'Users');
	$this->params['breadcrumbs'][] = $this->title;

?>
<div class="clearfix"></div>
<p>	<?= $this->render('_search', ['model' => $searchModel]);?></p>
<div class="clearfix"></div>
<div class="user-index">
	<?php if ($dataProvider->count) {?>
		<div>
			<?php //if user can`t send to all don`t show button and check box ?>
			<?php if(Yii::$app->user->can("personal-office/send-invited-users")): ?>
				<div class="pull-left">
					<?= Html::button(Yii::t('personal-office', 'Send message'), ['class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#send-message-modal']) ?>
				</div>
			<?php endif; ?>
			<?php
				$printColumn = [
					['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'recruiter',
                        'format'	=> 'raw',
                        'label'		=> Yii::t('personal_office', 'Recruiter'),
                        'value'		=> function($model){
                            /**
                             * @var $model \common\models\User
                             */
                            if(isset($model->recruiter)) {
                                return $model->recruiter->getFullName();
                            } else {
                                return Yii::t('user', '(don\'t set)');
                            }
                        }
                    ],
					[
						'attribute' => 'username',
						'format'	=> 'raw',
						'label'		=> Yii::t('personal_office', 'Name'),
						'value'		=> function($model){
							/**
							 * @var $model \common\models\User
							 */
							if(!empty($model->getFullName())) {
								return $model->getFullName();
							}elseif(!empty($model->username)){
								return $model->username;
							}else{
                                return '';
                            }
						}
					],
					[
						'attribute' => 'regionName',
						'format'	=> 'raw',
						'label'		=> Yii::t('personal_office', 'Region Name'),
						'value'		=> function($model){
                            /**
                             * @var $model \common\models\User
                             */
							return $model->getLocationForPrintString();
						}
					],

                    [
                        'attribute' => 'koatuu',
                        'format'	=> 'raw',
                    ],
                    [
                        'attribute' => 'professionName',
                        'format'	=> 'raw',
                    ],
                    [
                        'attribute' => 'email',
                        'format'	=> 'raw',
                    ],
					[
						'attribute' => 'phone',
						'format'	=> 'raw',
						'label'		=> Yii::t('personal_office', 'Phone'),
						'value'		=> function($model){
                            /**
                             * @var $model \common\models\User
                             */
							return '+'.$model->phone.' ';
						}
					],
				];
                if(Yii::$app->user->can(User::ADMIN_ACCESS_ROLE)) {
                    array_push($printColumn, [
                        'attribute' => 'addedName',
                        'format'	=> 'raw',
                        'label'		=> Yii::t('personal_office', 'Added Name'),
                        'value'		=> function($model){
                            /**
                             * @var $model \common\models\User
                             */
                            if(!empty($model->added)) {
                                return $model->added->fullName;
                            }else{
                                return '';
                            }
                        }
                    ]);
                }
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
                'batchSize' => Yii::$app->request->get('per-page') ? Yii::$app->request->get('per-page') : 20,
				'columns' => $printColumn,
				'target' => ExportMenu::TARGET_BLANK,
				'showConfirmAlert' => false,
				'dropdownOptions' => [
					'label' => Yii::t('export', 'Export All'),
					'class' => 'btn btn-default',
					'style' => 'margin-left:10px;'
				],
                'folder' => Yii::getAlias('@backend/web/tmp'),
				'filename' => $this->title,
				'showColumnSelector' => true,
			])?>
			<div class="pull-right"  style="padding-top: 6px;">
				<?= PageSize::widget([
					'label'=> Yii::t('personal_office', 'Items'),
					'labelOptions' => [
						'style' => 'margin-right: 10px; float:left;',
					],
					'sizes' => [
						'20' => 20,
						'50' => 50,
						'100' => 100,
						'200' => 200,
						'500' => 500,
						'1000' => 1000,
						'2000' => 2000,
						'5000' => 5000,
					],
				]); ?>
			</div>
		</div>
		<div class='clearfix'></div>
	<?php }?>
	<?php
		$column =  [
			['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'username',
				'format'	=> 'raw',
				'label'		=> Yii::t('personal_office', 'Info'),
				'value'		=> function($model){
					return $this->render('_info_grid',['model' => $model]);
				}
			],
            'koatuu',
			[
				'format'	=> 'raw',
				'label'		=> Yii::t('personal_office', 'Contacts'),
				'value'		=> function($model){
					return $this->render('_contacts_grid',['model' => $model]);
				}
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{new_action1} {edit}',
				'buttons' => [
					'new_action1' => function ($url, $model) {
						return "<div class='text-center'>".Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['user/profile/show', 'id'=> $model->id]), [
							'title' => Yii::t('personal_office', 'Detail view'),
						])."</div>";
					},
                    'edit' => function ($url, $model) {
                        return Yii::$app->user->can('user/admin') ? "<div class='text-center'>".Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['user/admin/update', 'id'=> $model->id]), [
                            'title' => Yii::t('personal_office', 'Detail view'),
                        ])."</div>" : '';
                    }
				],
			],
		];
		if(Yii::$app->user->can("personal-office/send-invited-users")) {
			array_unshift($column, ['class' => 'yii\grid\CheckboxColumn']);
		}?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel,
		'id' => 'personal_office_grid',
		'filterSelector' => 'select[name="per-page"]',
        'pager'=>[
            'firstPageLabel' => '<i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i>',
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
            'lastPageLabel' => '<i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i>',
            'activePageCssClass' => 'active',
        ],
		'layout'=>"{summary}<div class='clearfix'></div><div class=\"box\"><div class=\"box-body\">{items}</div><div class=\"box-footer clearfix\">{pager}</div></div>",
		'tableOptions' => [
			'class' => 'table table-bordered'
		],
		'options' => [
			'style' => [
				'margin-top' => $dataProvider->count == 0 ? '20px' : '0px',
			],
		],
		'columns' => $column
	]);
	?>

	<!-- Modal -->
	<div id="send-message-modal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?= Yii::t('personal-office', 'Send message for all selected recipients')?></h4>
				</div>
				<div class="modal-body">
					<form class="form-group" action="<?=Url::to(['/personal-office/send-invited-users'])?>" method="POST" id="message-send-form">
						<label for="msg-modal"><?= Yii::t('personal-office', 'Message:')?></label>
						<textarea class="form-control" rows="5" id="msg-modal"></textarea>
						<button type="submit" data-need-mark="0" class="btn btn-success modal-button-send">
							<?= Yii::t('personal-office', 'Send info message')?>
						</button>
						<button type="submit" data-need-mark="1" class="btn btn-info modal-button-send">
							<?= Yii::t('personal-office', 'Send a survey ')?>
						</button>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>

