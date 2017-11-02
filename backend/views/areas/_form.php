<?php

	use common\models\LocationTypes;
	use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Regions;
/* @var $this yii\web\View */
/* @var $model common\models\Areas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="areas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=$form->field($model, 'id_region')->widget(Select2::classname(), [
        'data' => Regions::getRegionList(),
        'options' => ['placeholder' => Yii::t('location', 'Select a region')],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ])->label(Yii::t('location', 'Region'));
    ?>
	<?=$form->field($model, 'type_id')->widget(Select2::classname(), [
		'data' => LocationTypes::listAll('areas'),
		'options' => ['placeholder' => Yii::t('location', 'Select type')],
		'pluginOptions' => [
			'allowClear' => false
		],
	])->label(Yii::t('location', 'Type'));?>
    <?= $form->field($model, 'area_name')->textInput(['maxlength' => true])->label(Yii::t('location', 'Area name')); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('location', 'Create') : Yii::t('location', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
