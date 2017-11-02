<?php

use common\models\Areas;
use common\models\LocationTypes;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CitiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cities', 'Cities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('events', 'Create Cities'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'typeName',
                'filter' => LocationTypes::listAll('cities'),
            ],
            'city_name',
            [
            	'attribute' => 'areaName',
				'label'	=> Yii::t('cities', 'Area')
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
