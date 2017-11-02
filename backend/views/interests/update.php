<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Interests */

$this->title = Yii::t('interests', 'Update {modelClass}: ', [
    'modelClass' => 'Interests',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('interests', 'Interests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('interests', 'Update');
?>
<div class="interests-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
