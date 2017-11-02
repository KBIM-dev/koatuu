<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CitiesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cities-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'city_name') ?>

    <?= $form->field($model, 'type_id') ?>

    <?= $form->field($model, 'area_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('events', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('events', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
