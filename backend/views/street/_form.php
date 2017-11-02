<?php

	use common\models\Koatuu;
	use common\models\StreetTypes;
	use kartik\widgets\DepDrop;
	use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Street */
/* @var $form yii\widgets\ActiveForm */
/* @var $koatuu string */

	$displayed = "
    if(count == 0) {
        $(this).parent().css('display', 'none');
        $('#koatuu-hidden').val(value);
    } else {
        $(this).parent().css('display', 'block');
        $('#koatuu-hidden').val('');
         $('.region-block, .area-block, .city-block').addClass('has-error')
    }
";
	$script = <<< JS
	$('#street-cu-form').on('submit', function(e) {
	    var region  = $('#koatuu-hidden').val();
	    console.log("Length = " + region.length);
        if(!region.length) {
            $('.region-block, .area-block, .city-block').addClass('has-error');
             e.preventDefault();
        } else {
            $('.region-block, .area-block, .city-block').removeClass('has-error');
        }
	});
    $('#koatuu-hidden').on('change', function() {
        console.log("Length2 = " + $(this).val().length);
       if($(this).val().length) {
             $('.region-block, .area-block, .city-block').removeClass('has-error');
       } else {
            $('.region-block, .area-block, .city-block').addClass('has-error');
       } 
    });
    $('#city').on('select2:selecting', function() {
        $('.region-block, .area-block, .city-block').removeClass('has-error')
    });
    $('#area').on('select2:closing', function() {
       if(!$('.city-block .koatuu-drop-down').is(':visible')) {
           $('.region-block, .area-block, .city-block').removeClass('has-error');
       } 
    });
    
JS;
	//маркер конца строки, обязательно сразу, без пробелов и табуляции
	$this->registerJs($script, yii\web\View::POS_END);
?>

<div class="street-form">

    <?php $form = ActiveForm::begin([
            'id' => 'street-cu-form',
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_id')->dropDownList(StreetTypes::listAll('id', 'CONCAT(short_name," (", name, ")")')) ?>

    <?php
        if(isset($koatuu)) {
			echo $form->field($model, 'koatuu')->hiddenInput(['maxlength' => true, 'value' => $koatuu])->label(false);
        } else {
    ?>
    <div class="form-group region-block">
       <label class="control-label" for="region"><?= Yii::t('streets', 'Region'); ?>&nbsp;<span style="color: red">*</span></label>
		<?= Select2::widget([
		    'name' => 'region',
			'pluginEvents' => [
				'change' => "function() {
                       /* var val = $(this).val();
                        if (val) {
                            var old_val = $('#koatuu-hidden').val();
                            if (old_val == '' || val.substr(0,2) != old_val.substr(0,2)) {
                                $('#koatuu-hidden').val(val);
                            } 
                        }*/
                         $('.area-block, .city-block').addClass('has-error');
                    }",
			],
            'value' => $model->region,
			'data' => Koatuu::listAllRegionsKoatuu(),
			'pluginOptions' => [
			],
			'options' => [
				'id' => 'region',
				'placeholder' => Yii::t('create-user', 'Select a region'),
			],
		]);
		?>
    </div>
    <div class="form-group area-block">
        <div class="koatuu-drop-down" style="display:block;">
			<?= DepDrop::widget([
				'type' => DepDrop::TYPE_SELECT2,
				'name' => 'area',
				'options' => [
					'id' => 'area',
				],
				'value' => $model->area,
				'data' => Koatuu::listAllAreasKoatuu($model->region),
				'pluginOptions' => [
					'depends' => ['region'],
					'url' => Url::to(['/koatuu/get-areas']),
					'placeholder' => Yii::t('create-user', 'Select an area'),
					'initDepends' => 'region_id', // initial ajax loading will be fired first for parent-1, then child-1, and child-2
					'initialize' => true,
				],
				'pluginEvents' => [
					'change' => "function() {
                                var val = $(this).val();
                                if (val) {
                                    var old_val = $('#koatuu-hidden').val();
                                    if (old_val == '' || val.substr(0,5) != old_val.substr(0,5)) {
                                        $('#koatuu-hidden').val(val);
                                    } 
                                }
                            }",
					'depdrop.change'=>"function(event, id, value, count) { 
                                $displayed
                 
                            }",
				],
			]);
			?>
        </div>
    </div>
    <div class="form-group city-block">
        <div class="koatuu-drop-down" style="display:block;">

			<?= DepDrop::widget([
				'type' => DepDrop::TYPE_SELECT2,
				'name' => 'city',
				'options' => [
					'id' => 'city',
				],
				'value' => $model->city,
				'data' => ArrayHelper::map(Koatuu::listAllCitiesKoatuu($model->koatuu), 'id', 'name'),
				'pluginOptions' => [
					'depends' => ['area'],
					'url' => Url::to(['/koatuu/get-cities']),
					'placeholder' => Yii::t('create-user', 'Select an region'),
				],
				'pluginEvents' => [
					'change' => "function() {
                            var val = $(this).val();
                            if (val) {
                                var old_val = $('#koatuu-hidden').val();
                                if (old_val == '' || val != old_val) {
                                    $('#koatuu-hidden').val(val);
                                } 
                            }
                        }",
					    'depdrop.change'=>"function(event, id, value, count) { 
                                $displayed
                        }",
				],
			]);
			?>
        </div>
    </div>
    <div style="display: none">
        <?= $form->field($model, 'koatuu')->hiddenInput(['id' => 'koatuu-hidden'])->label(false) ?>
    </div>
</div>

    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('street', 'Create') : Yii::t('street', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
