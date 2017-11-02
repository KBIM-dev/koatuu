<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Profession */

$this->title = Yii::t('profession', 'Update {modelClass}: ', [
    'modelClass' => 'Profession',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('profession', 'Professions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('profession', 'Update');
?>
<div class="profession-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
