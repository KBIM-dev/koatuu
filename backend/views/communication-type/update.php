<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CommunicationType */

$this->title = Yii::t('communication_type', 'Update {modelClass}: ', [
    'modelClass' => 'Communication Type',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('communication_type', 'Communication Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('communication_type', 'Update');
?>
<div class="communication-type-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
