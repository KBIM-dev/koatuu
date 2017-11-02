<?php
	use common\models\Events;
	use yii\bootstrap\Html;
	use yii\grid\GridView;
	use yii\helpers\Url;

	$this->title = Yii::t('events', "My events");

?>
<?= GridView::widget([
	'dataProvider' => $eventDataProvider,
	'tableOptions' => [
		'class' => 'table table-bordered'
	],
	'layout'=>"{summary}<div class='clearfix'></div><div class=\"box\"><div class=\"box-body\">{items}</div><div class=\"box-footer clearfix\">{pager}</div></div>",
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
		[
			'attribute' => 'name',
			'format' => 'raw',
			'value' => function($model){
				return Html::a($model->name, ['personal-office/event-view','id'=>$model->id], ['style' => 'font-weight: bold;']);
			}
		],
		[
			'label' => Yii::t('events', "Invited by me"),
			'format' => 'raw',
			'value' => function($model){
				/**
				 * @var $model Events
				 */
				$result = $model::getInvitedCountByUser(Yii::$app->user->id, $model->id);
				return $result;
			}
		],
		'end_date:date',
		[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{event_view}',
			'buttons'=>[
				'event_view'=>function ($url, $model) {
					$event_view = Url::to(['personal-office/event-view','id'=>$model->id]); //$model->id для AR
					return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $event_view,
						['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
				}
			],
		],
	],
]); ?>
