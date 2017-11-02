<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use common\models\CommunicationType;
use common\models\Interests;
use common\models\Koatuu;
use common\models\Profession;
use common\models\StreetTypes;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;
	/**
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model backend\modules\user\models\SettingsForm
 */

$this->title = Yii::t('user', 'Account settings');
$this->params['breadcrumbs'][] = $this->title;

$displayed = "
        if(count == 0) {
            $(this).parents().closest('.koatuu-drop-down').css('display', 'none');
        } else {
            $(this).parents().closest('.koatuu-drop-down').css('display', 'block');
        }
";
$setStreet = "
        $('#settings-form-streetname').val('');
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '/site/get-streets',
            data: {koatuu: $('#koatuu-hidden').val(), type_id: $('#settings-form-streetname_type').val()},
            success: function(data) {
                $('#settings-form-streetname').autocomplete({\"source\":data.data});
                $('#settings-form-streetname').prop('disabled', false);
            }
        });
";
$scripts = "
if($('#settings-form-streetname_type').val()) {
$.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/site/get-streets',
        data: {koatuu: $('#koatuu-hidden').val(), type_id: $('#settings-form-streetname_type').val()},
        success: function(data) {
            $('#settings-form-streetname').autocomplete({\"source\":data.data});
        }
    });
}";
if(true) {
$script = <<< JS
$scripts
JS;
    //маркер конца строки, обязательно сразу, без пробелов и табуляции
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'account-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'horizontalCssClasses' => [
                            'wrapper' => 'col-sm-9',
                        ],
                    ],
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                ]); ?>

				<?= $form->field($model, 'last_name') ?>

				<?= $form->field($model, 'name') ?>

				<?= $form->field($model, 'middle_name') ?>

				<?= $form->field($model, 'sex')->dropDownList([
					'female'    => Yii::t('user', 'Female'),
					'male'      => Yii::t('user', 'Male'),
				], ['prompt'=>Yii::t('create-user', 'Select sex')]) ?>

                <hr/>

                <div>
                    <!----------------------------------------NEW---------------------------------------->
                    <div class="form-group" style="margin-bottom: 0px; padding: 0 15px;">
                        <div class="koatuu-drop-down">
                            <?php  echo $form->field($model, 'regionKoatuu')->widget(Select2::classname(), [
                                'pluginEvents' => [
                                    'change' => "function() {
                                        var val = $(this).val();
                                        if (val) {
                                            var old_val = $('#koatuu-hidden').val();
                                            if (old_val == '' || val.substr(0,2) != old_val.substr(0,2)) {
                                                $('#koatuu-hidden').val(val);
                                                 $scripts
                                                $('#settings-form-streetname_type').prop('disabled', false);
                                            } 
                                        }
                                    }",
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                                'data' => Koatuu::listAllRegionsKoatuu(),
                                'options' => [
                                    'id' => 'settings-form-regionkoatuu',
                                    'placeholder' => Yii::t('create-user', 'Select a region'),
                                ],
                            ])->label(Yii::t('create-user', 'Locality')); ?>
                        </div>
                        <div class="koatuu-drop-down" style="display:<?= $model->areaKoatuu ? 'block' : 'none'?>;">
                            <?php
                                echo $form->field($model, 'areaKoatuu')->widget(DepDrop::classname(), [
                                    'type' => DepDrop::TYPE_SELECT2,
                                    'options' => [
                                        'id' => 'settings-form-areakoatuu',
                                        'placeholder' => Yii::t('create-user', 'Select an area'),
                                    ],
                                    'pluginEvents' => [
                                        'change' => "function() {
                                            var val = $(this).val();
                                            if (val) {
                                                var old_val = $('#koatuu-hidden').val();
                                                if (old_val == '' || val.substr(0,5) != old_val.substr(0,5)) {
                                                    $('#koatuu-hidden').val(val);
                                                     $scripts
                                                    $('#settings-form-streetname_type').prop('disabled', false);
                                                } 
                                            }
                                        }",
                                        'depdrop.change'=>"function(event, id, value, count) { 
                                            $displayed
                                        }",
									],
                                    'select2Options' => [
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                        'pluginEvents' => [
                                            "select2:unselect" => "function() { 
                                                var val = $(this).val();
                                                val = val.substr(0,2)
                                                while(val.length < 10){
                                                val=val+'0';
                                                }
                                                $('#koatuu-hidden').val(val);
                                                $scripts
                                            }"
                                        ],
                                    ],
                                    'data' => Koatuu::listAllAreasKoatuu($model->regionKoatuu),
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'depends' => ['settings-form-regionkoatuu'],
                                        'url' => Url::to(['/koatuu/get-areas']),
                                        'placeholder' => Yii::t('create-user', 'Select an area'),
                                        'initDepends' => 'region_id', // initial ajax loading will be fired first for parent-1, then child-1, and child-2
                                        'initialize' => true,
                                    ],
                                ])->label(false);
                            ?>
                        </div>
                        <div class="koatuu-drop-down" style="display:<?= ($model->areaKoatuu != $model->koatuu || count(Koatuu::listAllCitiesKoatuu($model->areaKoatuu)) ) ? 'block' : 'none'?>;">
							<?php
								echo $form->field($model, 'cityKoatuu')->widget(DepDrop::classname(), [
									'type' => DepDrop::TYPE_SELECT2,
									'data' => ArrayHelper::map(Koatuu::listAllCitiesKoatuu($model->koatuu), 'id', 'name'),
									'options' => [
										'placeholder' => Yii::t('create-user', 'Select an region'),
									],
									'pluginOptions' => [
										'allowClear' => true,
										'depends' => ['settings-form-areakoatuu'],
										'url' => Url::to(['/koatuu/get-cities']),
										'placeholder' => Yii::t('create-user', 'Select an region'),
									],
									'select2Options' => [
										'pluginOptions' => [
											'allowClear' => true,
										],
										'pluginEvents' => [
											"select2:unselect" => "function() { 
                                                var val = $(this).val();
                                                val = val.substr(0,5)
                                                while(val.length < 10){
                                                val=val+'0';
                                                }
                                                $('#koatuu-hidden').val(val);
                                                $scripts
                                            }"
										],
									],
									'pluginEvents' => [
										'change' => "function() {
                                            var val = $(this).val();
                                            if (val) {
                                                var old_val = $('#koatuu-hidden').val();
                                                if (old_val == '' || val != old_val) {
                                                    $('#koatuu-hidden').val(val);
                                                     $scripts
                                                    $('#settings-form-streetname_type').prop('disabled', false);
                                                } 
                                            }
                                        }",
										'depdrop.change'=>"function(event, id, value, count) { 
                                        $displayed
                                    }",
									],
								])->label(false);
							?>
                        </div>
                        <div style="display: none">
                            <?= $form->field($model, 'koatuu')->hiddenInput(['id' => 'koatuu-hidden'])->label(false) ?>
                        </div>
                    </div>
                    <!-- --------------------------------------END---------------------------------------- -->
                </div>

                <!----------------------------------------NEW---------------------------------------->
                <div class="form-group required">
                    <label class="control-label col-sm-3"><?= Yii::t('create-user', 'Address') ?></label>
                    <div class="col-sm-2">
                        <?= Select2::widget([
                            'name' => 'settings-form[streetType]',
                            'value' => $model->streetType,
                            'disabled' => !(bool) $model->koatuu,
                            'pluginEvents' => [
                                'change' => 'function() {' . $setStreet . '}',
                            ],
                            'options' => [
                                'placeholder' => Yii::t('create-user', 'type of street'),
                                'id' => 'settings-form-streetname_type'
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                            'data' => StreetTypes::listAll(),
                        ]); ?>

                    </div>

                    <div class="col-sm-7" style="margin-bottom: -15px">
                        <?= $form->field($model, 'streetName', [
							'options' => [
							    'style' => 'margin-right:0',
                            ],
                            'horizontalCssClasses' => [
                                'offset' => '',
                                'wrapper' => '',
                            ]
                        ])->widget(AutoComplete::className(),[
                            'options' => [
                                'disabled' => !(bool) $model->streetType,
                                'class' => 'form-control',
                                'id' => 'settings-form-streetname',
                                'placeholder' => Yii::t('create-user', 'Enter the name of the street')
                            ]
                        ])->label(false)
                        ?>
                    </div>
                </div>
                <?= $form->field($model, 'build')->textInput(['maxlength' => 8]) ?>
                <?= $form->field($model, 'apartment')->textInput(['onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57', 'maxlength' => 4])?>
                <?= $form->field($model, 'korp')->textInput(['onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57', 'maxlength' => 2])?>
                <!-- --------------------------------------END---------------------------------------- -->


                <hr/>

                <?= $form->field($model, 'dateOfBirthString')->widget(DatePicker::className(), [
					'options' => ['placeholder' => Yii::t('registration', 'Enter birth date ...')],
					'readonly' => true,
					'pluginOptions' => [
						'autoclose' => true,
						'todayHighlight' => false,
						'maxViewMode' => 3,
						"endDate" => date('d-m-Y', time()),
						'startDate' => "01-01-1940",
						'startView' => 2
					]]) ?>
                <hr/>

				<?= $form->field($model, 'communication_type_ids')->dropDownList(CommunicationType::listAll(), ['multiple' => true])->label(Yii::t('create-user', 'Communication')) ?>

				<?php
					if ($model->phone == 'admin') {
						echo $form->field($model, 'phone', ['options' => ['value'=> $model->phone]])->hiddenInput()->label(false);
					} else {
						echo $form->field($model, 'phone')->widget(MaskedInput::className(), [ 'mask' => '+99 (999) 999-99-99', ]);
					}
				?>

				<?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'autocomplete' => 'new-password']) ?>

				<?= $form->field($model, 'profession_id')->dropDownList(Profession::listAll(), ['prompt'=>Yii::t('create-user', 'Select profession')])->label(Yii::t('create-user', 'Profession')) ?>

				<?= $form->field($model, 'interest_ids')->dropDownList(Interests::listAll(), ['multiple' => true])->label(Yii::t('create-user', 'Interests')) ?>

				<?= $form->field($model, 'new_password')->passwordInput(['autocomplete' => 'new-password']) ?>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?><br>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <?php if ($model->module->enableAccountDelete): ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Yii::t('user', 'Delete account') ?></h3>
                </div>
                <div class="panel-body">
                    <p>
                        <?= Yii::t('user', 'Once you delete your account, there is no going back') ?>.
                        <?= Yii::t('user', 'It will be deleted forever') ?>.
                        <?= Yii::t('user', 'Please be certain') ?>.
                    </p>
                    <?= Html::a(Yii::t('user', 'Delete account'), ['delete'], [
                        'class'        => 'btn btn-danger',
                        'data-method'  => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure? There is no going back'),
                    ]) ?>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>