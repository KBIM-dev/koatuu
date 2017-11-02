<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LocationTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('events', 'Location Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-types-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?= /** @var string $type */
			Html::a(Yii::t('events', 'Create Location Types'), ['create', 'class' => $class], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'short_name',
            'type:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
