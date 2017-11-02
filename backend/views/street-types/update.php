<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StreetTypes */

$this->title = Yii::t('streetTypes', 'Update {modelClass}: ', [
    'modelClass' => 'Street Types',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('streetTypes', 'Street Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('streetTypes', 'Update');
?>
<div class="street-types-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
