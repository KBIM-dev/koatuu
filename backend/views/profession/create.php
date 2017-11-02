<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Profession */

$this->title = Yii::t('profession', 'Create Profession');
$this->params['breadcrumbs'][] = ['label' => Yii::t('profession', 'Professions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profession-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
