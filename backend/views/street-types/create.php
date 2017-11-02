<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StreetTypes */

$this->title = Yii::t('streetTypes', 'Create Street Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('streetTypes', 'Street Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="street-types-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
