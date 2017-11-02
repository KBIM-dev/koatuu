<?php

    use common\models\Interests;
    use common\models\Koatuu;
    use common\models\Profession;
    use common\models\Street;
    use common\models\StreetTypes;
    use common\models\User;
    use kartik\select2\Select2;
    use kartik\widgets\DepDrop;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\ActiveForm;
    use yii\widgets\MaskedInput;

	/* @var $this yii\web\View */
/* @var $model backend\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
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
		'action' => ['user-invited'],
		'method' => 'get',
		'options' => [
			//'class' => 'form-inline',
			'role'	=> 'form'
		]
	]);?>
	<div class="box-body" style="display: none;">
		<div class="row">
			<div class="col-md-4">

                <?php if(Yii::$app->user->can(User::ADMIN_ACCESS_ROLE)) {
                    echo $form->field($model, 'added_id')->widget(Select2::className(), [
                        'options' => ['placeholder' => ""],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'data' => User::listAll('id', 'username', false, []),
                    ]);
                } ?>

				<?= $form->field($model, 'last_name')->textInput() ?>

				<?= $form->field($model, 'username')->textInput() ?>

				<?= $form->field($model, 'middle_name')->textInput() ?>

				<?= $form->field($model, 'phone')->widget(MaskedInput::className(), [ 'mask' => '+38 (099) 999-99-99']) ?>

			</div>
			<!-- /.col -->
			<div class="col-md-4">

				<?php if((Yii::$app->user->can(User::REGIONS_FILTER) || Yii::$app->user->can(User::AREAS_FILTER)) && !Yii::$app->user->can(User::NO_FILTER)): ?>
					<div class="form-group">
						<label class="control-label"><?= Yii::t('location','Location') ?></label>
						<p class="form-control-static"><?= $model->regionName;  ?></p>
                        <?= $form->field($model, 'region')->hiddenInput(['id' => 'region'])->label(false)?>
					</div>
				<?php else: ?>
                    <div class="form-group">
                        <label class="control-label"><?= Yii::t('location','Location') ?></label>
                        <?= Select2::widget([
                            'model' => $model,
                            'attribute' => 'region',
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
							'data' => Koatuu::listAllRegionsKoatuu(),
                            'options' => [
                                'id' => 'region',
                                'placeholder' => Yii::t('create-user', 'Select a region'),
                            ],
                        ]); ?>
                    </div>

				<?php endif; ?>

				<?php if( Yii::$app->user->can(User::AREAS_FILTER) && !Yii::$app->user->can(User::NO_FILTER)): ?>
					<div class="form-group">
						<p class="form-control-static"><?= $model->areaName ?></p>
                        <?= $form->field($model, 'area')->hiddenInput(['id' => 'area'])->label(false)?>
					</div>
                <?php else: ?>
                    <div class="form-group">
                        <?= DepDrop::widget([
                            'type' => DepDrop::TYPE_SELECT2,
							'model' => $model,
                            'attribute' => 'area',
							'name' => 'area',
                            'options' => [
                                'id' => 'area',
                                'placeholder' => Yii::t('create-user', 'Select an area'),
                            ],
                            'pluginOptions' => [
                                //'allowClear' => true,
                                'depends' => ['region'],
                                'url' => Url::to(['/koatuu/get-areas']),
                                'placeholder' => Yii::t('create-user', 'Select an area'),
                                'initDepends' => 'region', // initial ajax loading will be fired first for parent-1, then child-1, and child-2
                                'initialize' => false,
                            ],
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true,]],
							'data' => Koatuu::listAllAreasKoatuu($model->region),

                        ]); ?>
                    </div>
				<?php endif; ?>
                <div class="form-group">
                    <?= DepDrop::widget([
                        'type' => DepDrop::TYPE_SELECT2,
						'model' => $model,
						'attribute' => 'city',
                         'value' => $model->city,
                        'name' => 'city',
						'data' => ArrayHelper::map(Koatuu::listAllCitiesKoatuu($model->area), 'id', 'name'),
						'pluginEvents' => [
							'depdrop.change'=>'function(event, id, value, count) {
							        var lOk = false;
							        if (count != 0) {
                                        if(!($("#city").html().indexOf(\'р-н.\') + 1)) {
                                            lOk = true;
                                        }
							        }
							                                            
                                    if((count == 0 && value != null) || lOk) {
                                       $(\'#accounts-form-streetname_type\').prop(\'disabled\', false);
                                    } else {
                                       $(\'#accounts-form-streetname_type\').prop(\'disabled\', true);
                                       $(\'#accounts-form-streetname_type\').val(\'\').trigger(\'change.select2\');
                                    }
                                }',
						],
                        'options' => [
                            'id' => 'city',
                            'placeholder' => Yii::t('create-user', 'Select an region'),
                        ],
                        'select2Options'=>[
                            'pluginOptions'=>['allowClear'=>true],
							'pluginEvents' => [
								"select2:select" => "function() {
								    $('#accounts-form-streetname_type').prop('disabled', false); 
                                }",
								"select2:unselect" => "function() { 
                                    var lOk = true;
                                    if(!($(\"#city\").html().indexOf('р-н.') + 1)) {
                                        lOk = false;
                                    }
                                    if (lOk) {
                                        $('#accounts-form-streetname_type').prop('disabled', true);
								    }
								    $('#accounts-form-streetname_type').val('').trigger('change.select2');
                                }"
							],
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'depends' => ['area'],
                            'url' => Url::to(['/koatuu/get-cities']),
                            'placeholder' => Yii::t('create-user', 'Select an region'),
                        ],

                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-4" style="padding-left: 0;">
                        <?= Select2::widget([
                            'name' => 'UserSearch[streetType]',
                            'model' => $model,
                            'attribute' => 'streetType',
                            'value' => $model->streetType,
                            'disabled' => !((bool) $model->area || (bool) $model->city),
                            'options' => [
                                'placeholder' => Yii::t('create-user', 'type of street'),
                                'id' => 'accounts-form-streetname_type'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'data' => StreetTypes::listAll(),
                        ]); ?>

                    </div>

                    <div class="col-sm-8" style="padding-right: 0">
                        <?= DepDrop::widget([
                            'type' => DepDrop::TYPE_SELECT2,
                            'model' => $model,
							'disabled' => !(bool) $model->streetType,
                            'data' => Street::listAll($model->city ? $model->city : ($model->area ? $model->area : $model->region), $model->streetType),
                            'attribute' => 'streetName',
                            'options' => [
                                'placeholder' => Yii::t('create-user', 'Enter the name of the street'),
                            ],
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'depends' => ['accounts-form-streetname_type', 'area', 'city', 'region'],
                                'url' => Url::to(['/street/get-street']),
                                'placeholder' => Yii::t('create-user', 'Enter the name of the street'),
                            ],

                        ]);
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?= $form->field($model, 'build')->textInput(['maxlength' => 8]) ?>
                <?= $form->field($model, 'apartment')->textInput(['onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57 || event.keyCode == 13', 'maxlength' => 4])?>
                <?= $form->field($model, 'korp')->textInput(['onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57 || event.keyCode == 13', 'maxlength' => 2])?>

				<?= $form->field($model, 'sex')->dropDownList([
					'female' => Yii::t('user', 'Female'),
					'male' => Yii::t('user', 'Male'),
				], ['prompt' => Yii::t('create-user', 'Select sex')]) ?>

				<?= $form->field($model, 'interest')->dropDownList(
					Interests::listAll(), ['prompt' => Yii::t('create-user', 'Select interests')]) ?>
			</div>
			<!-- /.col -->
			<div class="col-md-4">
				<?= $form->field($model, 'email')->textInput() ?>

                <?= $form->field($model, 'profession_id')->widget(Select2::classname(), [
                    'data' => Profession::listAll(),
                    'options' => ['placeholder' => Yii::t('user_invited', "Select profession...")],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>

			</div>
			<!-- /.col -->
			</div>
		<!-- /.row -->

	</div>
	<!-- /.box-body -->
	<div class="box-footer" style="display: none;">
		<div class="form-group pull-right">
			<?= Html::submitButton(Yii::t('personal_office', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('personal_office', 'Reset'), ['user-invited'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
<div class="user-search">










</div>
