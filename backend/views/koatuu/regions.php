<?php

use common\models\Koatuu;
use yii\helpers\Html;
use yii\grid\GridView;
use nterms\pagesize\PageSize;
	use yii\helpers\Url;

	/* @var $this yii\web\View */
/* @var $searchModel backend\models\KoatuuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('location', 'Regions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regions-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
		<?= Html::a(Yii::t('location', 'Import change by de-communization'), ['de-communization-import'], ['class' => 'btn btn-success']) ?>
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
                'label' => Yii::t('location', 'Region name'),
                'value'		=>function($model){
                    return  Koatuu::RegionName($model->name);

                }
            ],
			[
                'class' => 'yii\grid\ActionColumn',
                'template' => '{details}',
                'headerOptions' => ['width' => '25'],
                'buttons' => [
                    'details' => function ($url, $model, $key) {
                        return Html::a("<span class='fa fa-bars' aria-hidden='true'></span>", Url::to(['koatuu/areas', 'id' => $model->TE]), [
                            'title' => Yii::t('accounts', 'Details'),
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
