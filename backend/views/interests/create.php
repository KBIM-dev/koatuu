<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Interests */

$this->title = Yii::t('interests', 'Create Interests');
$this->params['breadcrumbs'][] = ['label' => Yii::t('interests', 'Interests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interests-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
