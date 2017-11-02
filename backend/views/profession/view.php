<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Profession */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('profession', 'Professions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profession-view">


    <p>
        <?= Html::a(Yii::t('profession', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('profession', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('profession', 'Are you sure you want to delete this item?'),
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
