<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Areas */

$this->title = Yii::t('location', 'Create Areas');
$this->params['breadcrumbs'][] = ['label' => Yii::t('location', 'Areas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="areas-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
