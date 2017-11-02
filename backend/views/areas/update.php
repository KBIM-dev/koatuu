<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Areas */

$this->title = Yii::t('location', 'Update Areas {modelClass}: ', [
    'modelClass' => $model->area_name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('location', 'Areas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->area_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('location', 'Update');
?>
<div class="areas-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
