<?php

	use common\models\Interests;
	use common\models\Koatuu;
	use common\models\StreetTypes;
	use kartik\date\DatePicker;
	use kartik\select2\Select2;
	use common\models\CommunicationType;
	use common\models\Profession;
	use kartik\widgets\DepDrop;
	use yii\bootstrap\ActiveForm;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\jui\AutoComplete;
	use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\RegistrationForm */
$setStreet = "
        $('#register-form-streetname').val('');
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '/site/get-streets',
            data: {koatuu: $('#koatuu-hidden').val(), type_id: $('#register-form-streetname_type').val()},
            success: function(data) {
                $('#register-form-streetname').autocomplete({\"source\":data.data});
                $('#register-form-streetname').prop('disabled', false);
                console.log(data);
            }
        });
";

$displayed = "
        if(count == 0) {
            $(this).parents().closest('.koatuu-drop-down').css('display', 'none');
        } else {
            $(this).parents().closest('.koatuu-drop-down').css('display', 'block');
        }
";
$scripts = "
if($('#register-form-streetname_type').val()) {
$.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/site/get-streets',
        data: {koatuu: $('#koatuu-hidden').val(), type_id: $('#register-form-streetname_type').val()},
        success: function(data) {
            $('#register-form-streetname').autocomplete({\"source\":data.data});
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
$this->title = Yii::t('registration', 'Registration');
?>
<div class="registration-index">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div class="center-block" style="display: block; max-width: 500px;">
        <p class="text-center"><?= Yii::t('registration', 'Please fill out the following fields to signup:') ?></p>
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin([
                    'id' => 'form-registration',
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
            ]); ?>

            <?php
                echo $form->field($model, 'last_name')->textInput(['autofocus' => true, 'style' => 'text-transform: capitalize;']);
                echo "<p class='registration-help-text'><span class='fa fa-info-circle' aria-hidden='true'></span>" . Yii::t('registration', 'Example: Petrenko') . "</p>";
            ?>

            <?php
                echo $form->field($model, 'name')->textInput(['style' => 'text-transform: capitalize;']);
                echo "<p class='registration-help-text'><span class='fa fa-info-circle' aria-hidden='true'></span>" . Yii::t('registration', 'Example: Petro') . "</p>";
            ?>

            <?php
                echo $form->field($model, 'middle_name')->textInput(['style' => 'text-transform: capitalize;']);
                echo "<p class='registration-help-text'><span class='fa fa-info-circle' aria-hidden='true'></span>" . Yii::t('registration', 'Example: Petrovich') . "</p>";
            ?>

            <div>
				<?= $form->field($model, 'sex')->widget(Select2::className(), [
					'id' => 'regions_sex',
					'name' => 'sex',
					'data' => ['male' => Yii::t('user', "Male"), 'female'=> Yii::t('user', "Female")],
					'options' => [
						'placeholder' => Yii::t('registration', 'Select sex'),
					],
				])->label(Yii::t('create-user', 'Sex')) ?>
            </div>

            <div>
                <div class="koatuu-drop-down">
                    <?php
                    echo $form->field($model, 'regionKoatuu')->widget(Select2::classname(),[
                        'pluginEvents' => [
                            'change' => "function() {
                                var val = $(this).val();
                                if (val) {
                                    var old_val = $('#koatuu-hidden').val();
                                    if (old_val == '' || val.substr(0,2) != old_val.substr(0,2)) {
                                        $('#koatuu-hidden').val(val);
                                        $scripts
                                        $('#register-form-streetname_type').prop('disabled', false);
                                    } 
                                }
                            }",
                        ],
                        'pluginOptions' => [
                            'allowClear' => false,
                        ],
                        'data' => Koatuu::listAllRegionsKoatuu(),
                        'options' => [
                            'placeholder' => Yii::t('create-user', 'Select a region'),
                        ],
                    ])->label(Yii::t('create-user', 'Locality'));
                    ?>
                </div>
                <div class="koatuu-drop-down" style="display:<?= $model->areaKoatuu ? 'block' : 'none'?>;">
                    <?php
                    echo $form->field($model, 'areaKoatuu')->widget(DepDrop::classname(),[
                        'type' => DepDrop::TYPE_SELECT2,
                        'options' => [
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
                                        $('#register-form-streetname_type').prop('disabled', false);
                                    } 
                                }
                            }",
                            'depdrop.change'=>"function(event, id, value, count) { 
                                $displayed
                            }",
                        ],
                        'data' => Koatuu::listAllAreasKoatuu($model->regionKoatuu),
                        'value' => $model->areaKoatuu,
                        'pluginOptions' => [
                            'allowClear' => false,
                            'depends' => ['register-form-regionkoatuu'],
                            'url' => Url::to(['/koatuu/get-areas']),
                            'placeholder' => Yii::t('create-user', 'Select an area'),
                            'initDepends' => 'region_id', // initial ajax loading will be fired first for parent-1, then child-1, and child-2
                            'initialize' => true,
                        ],
                    ])->label(false);
                    ?>
                </div>

                <div class="koatuu-drop-down" style="display:<?= $model->cityKoatuu ? 'block' : 'none'?>;">
                    <?php
                    echo $form->field($model, 'cityKoatuu')->widget(DepDrop::classname(),[
                        'type' => DepDrop::TYPE_SELECT2,
                        'data' => ArrayHelper::map(Koatuu::listAllCitiesKoatuu($model->koatuu), 'id', 'name'),
                        'value' => $model->koatuu,
                        'options' => [
                            'placeholder' => Yii::t('create-user', 'Select an region'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'depends' => ['register-form-areakoatuu'],
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
                                        $scripts
                                        $('#register-form-streetname_type').prop('disabled', false);
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

            <!----------------------------------------NEW---------------------------------------->
            <div class="form-group required">
                <div class="col-sm-12 required" style="padding: 0">
                    <label class="control-label"><?= Yii::t('create-user', 'Address') ?></label>
                </div>
                <div class="no-padding col-sm-5" style="padding-left: 0">
                    <?= Select2::widget([
                        'name' => 'register-form[streetType]',
                        'value' => $model->streetType,
                        'disabled' => !(bool) $model->koatuu,
                        'pluginEvents' => [
                            'change' => 'function() {' . $setStreet . '}',
                        ],
                        'options' => [
                            'placeholder' => Yii::t('create-user', 'type of street'),
                            'id' => 'register-form-streetname_type'
                        ],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                        'data' => StreetTypes::listAll(),
                    ]); ?>
                </div>

                <div class="no-padding col-sm-7" style="padding: 0">
                    <?= $form->field($model, 'streetName', [
                        'horizontalCssClasses' => [
                            'offset' => '',
                            'wrapper' => '',
                        ]
                    ])->widget(AutoComplete::className(),[
                        'options' => [
                            'disabled' => !(bool) $model->streetType,
                            'class' => 'form-control',
                            'id' => 'register-form-streetname',
                            'placeholder' => Yii::t('create-user', 'Enter the name of the street')
                        ]
                    ])->label(false)
                    ?>
                    <?= "<p class='registration-help-text'><span class='fa fa-info-circle' aria-hidden='true'></span>" . Yii::t('registration', 'Example: Khreschatyk') . "</p>"?>
                </div>
            </div>
            <?= $form->field($model, 'build')->textInput(['maxlength' => 8]) ?>
            <?= $form->field($model, 'apartment')->textInput(['onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57', 'maxlength' => 4])?>
            <?= $form->field($model, 'korp')->textInput(['onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57', 'maxlength' => 2])?>
            <!-- --------------------------------------END---------------------------------------- -->

			<?= $form->field($model, 'date_of_birth')->widget(DatePicker::className(), [
				'options' => ['placeholder' => Yii::t('registration', 'Enter birth date ...')],
				'readonly' => true,
				'pluginOptions' => [
					'autoclose' => true,
					'todayHighlight' => false,
					'maxViewMode' => 2,
					"endDate" => date('d-m-Y', time()),
					'startDate' => "01-01-1940",
					'startView' => 2
				]]) ?>

			<?= $form->field($model, 'communication_type_ids')->checkboxList(CommunicationType::listAll()) ?>

			<?= $form->field($model, 'phone')->widget(MaskedInput::className(), [ 'mask' => '+38 (099) 999-99-99']);?>

			<?php echo $form->field($model, 'email')->widget(MaskedInput::className(), [
				'clientOptions' => [
					'alias' => 'email',
				],]) ;
				echo "<p class='registration-help-text'><span class='fa fa-info-circle' aria-hidden='true'></span>"
					. Yii::t('registration', 'For enter e-mail select english language') . "</p>";
			?>

			<?= $form->field($model, 'profession_id')->radioList(Profession::listAll()) ?>

            <?= $form->field($model, 'recruiter_id')->hiddenInput(['value' => $recruiter_id])->label(false) ?>

			<?= $form->field($model, 'interest_ids')->checkboxList(Interests::listAll()) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'confirm_password')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('registration', 'registration'), ['class' => 'btn btn-primary', 'name' => 'registration-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>

