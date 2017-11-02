<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Badge */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('badge', 'Badges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="badge-view">


    <p>
        <?= Html::a(Yii::t('badge', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('badge', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('badge', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php
        if(empty($model->img)){
            $image = '-';
        } else {
            $image = Html::img($model->img);
        }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'label' => 'Images',
                'format' => 'raw',
                'value' => $image,
            ],
            'description:ntext',
        ],
    ]) ?>

</div>
