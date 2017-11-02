<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LocationTypes */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('events', 'Location Types'), 'url' => ['index', 'class' => $model->class]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-types-view">



    <p>
        <?= Html::a(Yii::t('events', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('events', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('events', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'type:boolean',
            'name',
            'short_name',
        ],
    ]) ?>

</div>
