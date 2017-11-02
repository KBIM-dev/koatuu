<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Street */

$this->title = Yii::t('street', 'Update {modelClass}: ', [
    'modelClass' => 'Street',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('street', 'Streets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('street', 'Update');
?>
<div class="street-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
