<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CommunicationType */

$this->title = Yii::t('communication_type', 'Create Communication Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('communication_type', 'Communication Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="communication-type-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
