<?php

use yii\helpers\Html;
use yii\grid\GridView;
use nterms\pagesize\PageSize;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('location', 'Regions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regions-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(Yii::t('location', 'Create Regions'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'region_name',
                'label' => Yii::t('location', 'Region name'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '50'],
            ],
        ],
    ]); ?>
</div>
