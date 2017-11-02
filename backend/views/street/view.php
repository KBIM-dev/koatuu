<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Street */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('street', 'Streets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="street-view">

    <p>
        <?= Html::a(Yii::t('street', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('street', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('street', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'streetType',
            'name',
            'koatuuString',
        ],
    ]) ?>

</div>
