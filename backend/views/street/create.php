<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Street */
/* @var $koatuu string */

$this->title = Yii::t('street', 'Create Street');
$this->params['breadcrumbs'][] = ['label' => Yii::t('street', 'Streets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="street-create">

    <?= $this->render('_form', [
        'model' => $model,
        'koatuu' => $koatuu,
    ]) ?>

</div>
