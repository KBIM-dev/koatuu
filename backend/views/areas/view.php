<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Areas */

$this->title = $model->area_name . ' ' . $model->types->short_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('location', 'Areas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="areas-view">
    <p>
        <?= Html::a(Yii::t('location', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('location', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('location', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'regionName',
                'label' => Yii::t('location', 'Region name'),
            ],
            [
                'attribute' => 'area_name',
                'label' => Yii::t('location', 'Area name'),
            ],

        ],
    ]) ?>

</div>
