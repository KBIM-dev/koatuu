<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LocationTypes */

$this->title = Yii::t('events', 'Update {modelClass}: ', [
    'modelClass' => 'Location Types',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('events', 'Location Types'), 'url' => ['index', 'class' => $model->class]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('events', 'Update');
?>
<div class="location-types-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
