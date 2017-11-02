<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Regions */

$this->title = $model->region_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('location', 'Regions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regions-view">
    <p>
        <?= Html::a(Yii::t('location', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('location', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('location', 'Are you sure you want to delete this region?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'region_name',
                'label' => Yii::t('location', 'Name')
            ],
        ],
    ]) ?>

</div>
