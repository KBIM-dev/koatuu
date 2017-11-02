<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Regions;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AreasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('location', 'Areas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="areas-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('location', 'Create area'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'attribute' => 'regionName',
                'label' => Yii::t('location', 'Region name'),
                'filter' => Regions::getRegionList(),
            ],
            [
                'attribute' => 'area_name',
                'label' => Yii::t('location', 'Area name'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '50'],
            ],
        ],
    ]); ?>
</div>
