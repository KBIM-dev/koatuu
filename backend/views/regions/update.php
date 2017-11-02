<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Regions */

$this->title = Yii::t('location', 'Update {modelClass}: ', [
    'modelClass' => 'Regions',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('location', 'Regions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->region_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('location', 'Update');
?>
<div class="regions-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
