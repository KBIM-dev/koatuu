<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProfessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('profession', 'Professions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profession-index">


    <p>
        <?= Html::a(Yii::t('profession', 'Create Profession'), ['create'], ['class' => 'btn btn-success']) ?>
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
