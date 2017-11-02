<?php

	use common\models\Areas;
	use common\models\LocationTypes;
	use common\models\Regions;
	use kartik\select2\Select2;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;

$getAreas = "
var region_id = $(this).val();
$.ajax({
    type: 'POST',
    dataType: 'JSON',
    url: '".Url::to(['site/get-areas-by-region'])."',
    data: {region_id: region_id},
    success: function(data){
        $('#cities-area_id').text('');
        $('#cities-area_id').val('').change();
        $('#cities-area_id').append('<option></option>');
		var i = 0;
        for (region_id in data){
			var id = 'optgroup-' + i;
			i++;
			console.log(id);
			$('#cities-area_id').append('<optgroup id=\"'+id+'\" label=\"' + region_id + '\"></optgroup>');
			for(inner_id in data[region_id]) {
				$('#'+id).append('<option value=\"' + inner_id + '\">' + data[region_id][inner_id] + '</option>');
			}
		}
    }
});
return false;
";

/* @var $this yii\web\View */
/* @var $model common\models\Cities */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cities-form">

    <?php $form = ActiveForm::begin(); ?>

	<label class="control-label" for="regions_category_id"><?= Yii::t('cities', 'Regions')?></label>
	<?= Select2::widget([
	    'value' => $model->isNewRecord ? '' : $model->area->idRegion->id,
		'pluginEvents' => [
			"change" => "function() { $getAreas } ",
		],
		'id' => 'regions_category_id',
		'name' => 'region_id',
		'data' => ArrayHelper::map(Regions::find()
			->select([
				'regions.id',
				'CASE WHEN `location_types`.`type` = 0 THEN \'' . Yii::t('regions', 'Regions') . '\' ELSE \'' . Yii::t('asd', 'Cities') . '\' END as types',
				'CASE WHEN `location_types`.`type` = 0 THEN CONCAT(`regions`.`region_name`, \' \', `location_types`.`short_name`) ELSE CONCAT(`location_types`.`short_name`, \' \', `regions`.`region_name`) END as name'
			])
			->leftJoin('location_types', 'location_types.id = regions.type_id')
			->orderBy([
				'`location_types`.`type`' => SORT_DESC,
				'region_name' => SORT_ASC
			])
			->asArray()
			->all(), 'id', 'name', 'types'),
		'options' => [
			'placeholder' => Yii::t('registration', 'Select regions'),
            'required' => true,
		],
	]); ?>

	<?= $form->field($model, 'area_id')->widget(Select2::className(), [
		'id' => 'create_cities_select',
		'options' => ['placeholder' => Yii::t('registration', 'Select areas')],
		'data' => Areas::getAreasList($model->isNewRecord ? false : $model->area->idRegion->id),
		'pluginOptions' => [
			'allowClear' => false
		],
	]); ?>

	<?=$form->field($model, 'type_id')->widget(Select2::classname(), [
		'data' => LocationTypes::listAll('cities'),
		'options' => ['placeholder' => Yii::t('location', 'Select type')],
		'pluginOptions' => [
			'allowClear' => false
		],
	])->label(Yii::t('location', 'Type'));?>

	<?= $form->field($model, 'city_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('events', 'Create') : Yii::t('events', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
