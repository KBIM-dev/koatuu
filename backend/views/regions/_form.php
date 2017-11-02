<?php

	use common\models\LocationTypes;
	use kartik\select2\Select2;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

	/* @var $this yii\web\View */
	/* @var $model common\models\Regions */
	/* @var $form yii\widgets\ActiveForm */
?>

<div class="regions-form">

	<?php $form = ActiveForm::begin(); ?>

	<?=$form->field($model, 'type_id')->widget(Select2::className(), [
		'data' => LocationTypes::listAll('regions'),
		'options' => ['placeholder' => Yii::t('location', 'Select a type')],
		'pluginOptions' => [
			'allowClear' => false
		],
	])->label(Yii::t('location', 'Type'));
	?>
	<?= $form->field($model, 'region_name')->textInput(['maxlength' => true])->label(Yii::t('location', 'Name of region')) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('location', 'Create') : Yii::t('location', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
