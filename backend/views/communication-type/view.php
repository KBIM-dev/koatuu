<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CommunicationType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('communication_type', 'Communication Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="communication-type-view">

    <p>
        <?= Html::a(Yii::t('communication_type', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('communication_type', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('communication_type', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
    ]) ?>

</div>
