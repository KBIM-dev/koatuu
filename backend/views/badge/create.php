<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Badge */

$this->title = Yii::t('badge', 'Create Badge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('badge', 'Badges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="badge-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
