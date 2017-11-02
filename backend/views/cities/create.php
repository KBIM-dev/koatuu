<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Cities */

$this->title = Yii::t('events', 'Create Cities');
$this->params['breadcrumbs'][] = ['label' => Yii::t('events', 'Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
