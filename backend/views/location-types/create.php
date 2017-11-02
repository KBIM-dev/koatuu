<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LocationTypes */

$this->title = Yii::t('events', 'Create {class} Location Types', ['class' => Yii::t('location_types', ucfirst($model->class))]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('events', 'Location Types'), 'url' => ['index', 'class' => $model->class]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-types-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
