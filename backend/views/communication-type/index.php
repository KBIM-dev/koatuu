<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CommunicationTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('communication_type', 'Communication Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="communication-type-index">


    <p>
        <?= Html::a(Yii::t('communication_type', 'Create Communication Type'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '30'],
            ],
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '50'],
            ],
        ],
    ]); ?>
</div>
