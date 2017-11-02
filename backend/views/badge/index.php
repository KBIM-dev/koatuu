<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BadgeTagsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('badge', 'Badges');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="badge-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('badge', 'Create Badge'), ['create'], ['class' => 'btn btn-success']) ?>
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

            'name',
            [
                'attribute' => 'img',
                'format' => 'raw',
                'value' => function($data){
                    return $data->img ? Html::img(Url::toRoute($data->img),[
                        'alt' => $data->description,
                        'title' => $data->name,
                        'style' => 'width:350px;',
                    ]) : '-';
                },
            ],

            'description:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '50'],
            ],
        ],
    ]); ?>
</div>
