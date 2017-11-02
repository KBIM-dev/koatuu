<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Cities */

$this->title = Yii::t('events', '{modelClass}: ', [
    'modelClass' => $model->types->short_name . ' ' .$model->city_name
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('events', 'Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->city_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('events', 'Update');
?>
<div class="cities-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
