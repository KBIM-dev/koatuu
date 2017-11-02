<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\StreetTypes */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('streetTypes', 'Street Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="street-types-view">

    <p>
        <?= Html::a(Yii::t('streetTypes', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('streetTypes', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('streetTypes', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'short_name',
            'name',
        ],
    ]) ?>

</div>
