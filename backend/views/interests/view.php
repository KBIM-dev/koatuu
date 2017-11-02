<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Interests */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('interests', 'Interests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interests-view">

    <p>
        <?= Html::a(Yii::t('interests', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('interests', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('interests', 'Are you sure you want to delete this item?'),
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
