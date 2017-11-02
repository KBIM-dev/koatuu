<?php

	use common\models\Koatuu;
	use common\models\Street;
	use yii\helpers\Html;
    use yii\grid\GridView;
    use common\models\Regions;
	use yii\helpers\Url;

	/* @var $this yii\web\View */
    /* @var $searchModel backend\models\AreasSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $title string */
    /* @var $titleRegion string */
$this->title = Yii::t('location', 'Areas');
$this->params['breadcrumbs'][] = ['label' => Yii::t('location', 'Regions'), 'url' => ['koatuu/regions']];
$this->params['breadcrumbs'][] = ['label' => $titleRegion];
?>
<div class="areas-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?php // Html::a(Yii::t('location', 'Import change by de-communization'), ['de-communization-import'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'style' => [
                'margin-top' => $dataProvider->count == 0 ? '20px' : '0px',
            ],
            'class' => 'grid-view'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '30'],
            ],
            [
                'attribute' => 'TE',
                'headerOptions' => ['width' => '50']
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('location', 'Area name'),
                'value'		=>function($model){
                    return Koatuu::AreasName($model->NP,$model->TE, $model->name);
                }
            ],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{details}{street}',
				'headerOptions' => ['width' => '25'],
				'buttons' => [
					'details' => function ($url, $model, $key){
						$res = Koatuu::find()->select("TE, CASE WHEN LOCATE('/', `NU`) > 0 THEN LEFT(`NU`,  LOCATE('/', `NU`) - 1) ELSE NU END as name")
							->where("SUBSTRING(koatuu.TE, 1,5) = '" . substr($model->TE, 0, 5) . "' and SUBSTRING(koatuu.TE, 7,4) != '0000' AND TRIM(`NP`) <> ''")->count();
						if($res) {
							$streetsCount = Street::find()->select("id")->where(['koatuu' => $model->TE])->count();
							return Html::a("<span class='fa fa-bars' aria-hidden='true'></span>", Url::to(['koatuu/cities', 'id' => $model->TE]), [
								'title' => Yii::t('location', 'Details'),
                                'style' => $streetsCount ? 'margin-right: 3px' : '',
							]);
                        }
					},
					'street' => function ($url, $model, $key){

						$streetsCount = Street::find()->select("id")->where(['koatuu' => $model->TE])->count();
						if($streetsCount) {
							return Html::a("<span class='fa fa-road' aria-hidden='true'></span>", Url::to(['street/index', 'koatuu' => $model->TE]), [
								'title' => Yii::t('location', 'Streets'),
							]);
						}
					},
				],
			],
        ],
    ]); ?>
</div>
