<?php

use common\models\Areas;
use common\models\Cities;
	use common\models\Profession;
	use common\models\Regions;
use common\models\User;
	use kartik\select2\Select2;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */

$script = <<< JS
    $('#user-search-form-for-event').on('beforeSubmit', function (e) {
       $.pjax({
           url:$('#user-search-form-for-event').attr('action') +'?'+ $('#user-search-form-for-event').serialize(), 
           container: '#event-user-pjax'
       }).done(function() { $('html, body').animate({scrollTop: $("#user-search-form-for-event").offset().top}, 0); });
       return false;
    });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, View::POS_READY);

$getAreas = "
	var region_id = $(this).val();
	$.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '".Url::to(['site/get-areas-by-region'])."',
		data: {region_id: region_id},
		success: function(data){
			$('#usersearch-areas_id').text('');
			$('#usersearch-areas_id').val('').change();
			$('#usersearch-areas_id').append('<option></option>');
			$('#usersearch-locs_id').text('');
			$('#usersearch-locs_id').val('').change();
			$('#usersearch-locs_id').append('<option></option>');
			var i = 0;
            for (region_id in data){
                var id = 'optgroup-' + i;
                i++;
                $('#usersearch-areas_id').append('<optgroup id=\"'+id+'\" label=\"' + region_id + '\"></optgroup>');
                for(inner_id in data[region_id]) {
                    $('#'+id).append('<option value=\"' + inner_id + '\">' + data[region_id][inner_id] + '</option>');
                }
            }
		}
	});
	return false;
	";
$setAreasId = "
        var areas_id = $(this).val();
        $('#usersearch-locs_id').text('');
        $('#usersearch-locs_id').val('').change();
        $('#usersearch-location_id').val('');
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '/" . Url::to('site/get-locations') . "',
            data: {area_id: areas_id, for_search: true},
            success: function(data) {
                if (data.status) {
                    $('#usersearch-locs_id').text('');
                    $('#usersearch-locs_id').val('').change();
                    $('#usersearch-locs_id').append('<option></option>');
                    for(inner_id in data.data) {
                        $('#usersearch-locs_id').append('<option value=\"' + inner_id + '\">' + data.data[inner_id] + '</option>');
                    }
                }
            }
        });
    ";
?>
<div class="box box-default collapsed-box">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Yii::t('personal_office','Filter form')?></h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<!-- /.box-header -->
	<?php $form = ActiveForm::begin([
		'action' => ['event-view', 'id' => $id],
		'method' => 'get',
		'options' => [
			//'class' => 'form-inline',
            'id' => 'user-search-form-for-event',
			'role'	=> 'form',
		]
	]); ?>
	<div class="box-body" style="display: none;">
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($searchModel, 'last_name')->textInput() ?>

				<?= $form->field($searchModel, 'username')->textInput() ?>

				<?= $form->field($searchModel, 'middle_name')->textInput() ?>

				<?= $form->field($searchModel, 'phone')->widget(MaskedInput::className(), [ 'mask' => '+38 (099) 999-99-99']) ?>

			</div>
			<!-- /.col -->
			<div class="col-md-4">

				<?php if(Yii::$app->user->can(User::REGIONS_FILTER) || Yii::$app->user->can(User::AREAS_FILTER)): ?>
					<div class="form-group">
						<label class="control-label"><?= $searchModel->getAttributeLabel('region_id') ?></label>
						<p class="form-control-static"><?= Regions::getRegionNameBy($searchModel->region_id);  ?></p>
					</div>
				<?php else: ?>

					<?= $form->field($searchModel, 'region_id')->widget(Select2::className(), [
						'pluginEvents' => [
							"change" => "function() { $getAreas } ",
						],
						'id' => 'regions_category_id',
						'name' => 'region_id',
						'data' => Regions::getRegionList(),
						'theme' => Select2::THEME_BOOTSTRAP,
						'options' => [
							'placeholder' => Yii::t('registration', 'Select regions'),
						],
						'pluginOptions' => [
							'allowClear' => false
						],
					])->label(Yii::t('location', 'Location')); ?>

				<?php endif; ?>

				<?php if( Yii::$app->user->can(User::AREAS_FILTER)): ?>
					<div class="form-group">
						<label class="control-label"><?= $searchModel->getAttributeLabel('areas_id') ?></label>
						<p class="form-control-static"><?= $searchModel->getAreasName(); ?></p>
					</div>
				<?php else: ?>
					<?= $form->field($searchModel, 'areas_id')->widget(Select2::className(), [
						'data' => Areas::getAreasList($searchModel->region_id),
                        'pluginEvents' => [
                            "change" => "function() { $setAreasId } ",
                        ],
						'options' => ['placeholder' => Yii::t('registration', 'Select a areas')],
						'theme' => Select2::THEME_BOOTSTRAP,
						'pluginOptions' => [
							'allowClear' => false
						],
					])->label(false); ?>
				<?php endif; ?>
                <?= $form->field($searchModel, 'locs_id')->widget(Select2::className(), [
                    'options' => ['placeholder' => Yii::t('create-user', 'Select an region')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'data' => Cities::getCityList($searchModel->areas_id),
                ])->label(false);
                ?>

			</div>
			<!-- /.col -->
			<div class="col-md-4">
				<?= $form->field($searchModel, 'email')->textInput() ?>

				<?= $form->field($searchModel, 'profession_id')->dropDownList(Profession::listAll(), ['prompt' => Yii::t('user_invited', "Select profession...")]) ?>

			</div>
			<!-- /.col -->
			</div>
		<!-- /.row -->

	</div>
	<!-- /.box-body -->
	<div class="box-footer" style="display: none;">
		<div class="form-group pull-right">
			<?= Html::submitButton(Yii::t('personal_office', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('personal_office', 'Reset'), ['event-view', 'id' => $id], ['class' => 'btn btn-default']) ?>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
<div class="user-search">

</div>


