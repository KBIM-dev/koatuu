<?php

use yii\helpers\Html;
use yii\grid\GridView;
	use yii\helpers\Url;
	use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\StreetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $koatuu string */

$this->title = Yii::t('street', 'Streets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="street-index">
    <p>
        <?= Html::a(Yii::t('street', 'Create Street'), Url::to(['street/create', 'koatuu' => $koatuu]), ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
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

            'koatuuString',
            'streetType',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
