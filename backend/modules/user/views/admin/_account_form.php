<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\widgets\ActiveForm $form
 * @var common\models\User $user
 * @var \common\models\Profile    $profile
 * @var \common\models\AccountsForm    $accountForm
 */
    use common\models\Badge;
    use common\models\CommunicationType;
	use common\models\Interests;
    use common\models\Koatuu;
    use common\models\Profession;
    use common\models\StreetTypes;
    use common\models\User;
    use kartik\checkbox\CheckboxX;
    use kartik\select2\Select2;
    use kartik\widgets\DepDrop;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use yii\jui\AutoComplete;
    use yii\widgets\MaskedInput;
    use kartik\date\DatePicker;



$setStreet = "
        $('#accounts-form-streetname').val('');
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '/site/get-streets',
            data: {koatuu: $('#koatuu-hidden').val(), type_id: $('#accounts-form-streetname_type').val()},
            success: function(data) {
                $('#accounts-form-streetname').autocomplete({\"source\":data.data});
                $('#accounts-form-streetname').prop('disabled', false);
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
if($('#accounts-form-streetname_type').val()) {
$.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/site/get-streets',
        data: {koatuu: $('#koatuu-hidden').val(), type_id: $('#accounts-form-streetname_type').val()},
        success: function(data) {
            $('#accounts-form-streetname').autocomplete({\"source\":data.data});
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


<?= $form->field($accountForm, 'last_name') ?>

<?= $form->field($accountForm, 'name') ?>

<?= $form->field($accountForm, 'middle_name') ?>

<?= $form->field($accountForm, 'sex')->dropDownList([
	'female' => Yii::t('user', 'Female'),
	'male' => Yii::t('user', 'Male'),
], ['prompt' => Yii::t('create-user', 'Select sex')]) ?>

<hr/>

<div>
    <!----------------------------------------NEW---------------------------------------->
    <div style="margin-bottom: 0px;">
            <div class="koatuu-drop-down">
                <?php
					echo $form->field($accountForm, 'regionKoatuu')->widget(Select2::classname(),[
						'pluginEvents' => [
							'change' => "function() {
                                var val = $(this).val();
                                if (val) {
                                    var old_val = $('#koatuu-hidden').val();
                                    if (old_val == '' || val.substr(0,2) != old_val.substr(0,2)) {
                                        $('#koatuu-hidden').val(val);
                                        $scripts
                                        $('#accounts-form-streetname_type').prop('disabled', false);
                                    } 
                                }
                            }",
						],
						'pluginOptions' => [
							'allowClear' => true,
						],
						'data' => Koatuu::listAllRegionsKoatuu(),
						'options' => [
							'placeholder' => Yii::t('create-user', 'Select a region'),
						],
					])->label(Yii::t('create-user', 'Locality'));
                ?>
            </div>
            <div class="koatuu-drop-down" style="display:<?= $user->areaKoatuu ? 'block' : 'none'?>;">
				<?php
					echo $form->field($accountForm, 'areaKoatuu')->widget(DepDrop::classname(),[
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
                                        $('#accounts-form-streetname_type').prop('disabled', false);
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
						'data' => Koatuu::listAllAreasKoatuu($user->regionKoatuu),
						'value' => $accountForm->areaKoatuu,
						'pluginOptions' => [
							'allowClear' => true,
							'depends' => ['accounts-form-regionkoatuu'],
							'url' => Url::to(['/koatuu/get-areas']),
							'placeholder' => Yii::t('create-user', 'Select an area'),
							'initDepends' => 'region_id', // initial ajax loading will be fired first for parent-1, then child-1, and child-2
							'initialize' => true,
						],
					])->label(false);
				?>
            </div>
            <div class="koatuu-drop-down city-drop-down" style="display:<?= ($user->areaKoatuu != $user->koatuu || count(Koatuu::listAllCitiesKoatuu($user->areaKoatuu)) ) ? 'block' : 'none'?>;">
				<?php
					echo $form->field($accountForm, 'cityKoatuu')->widget(DepDrop::classname(),[
						'type' => DepDrop::TYPE_SELECT2,
						'data' => ArrayHelper::map(Koatuu::listAllCitiesKoatuu($user->koatuu), 'id', 'name'),
						'value' => $accountForm->koatuu,
						'options' => [
							'placeholder' => Yii::t('create-user', 'Select an region'),
						],
						'pluginOptions' => [
							'allowClear' => true,
							'depends' => ['accounts-form-areakoatuu'],
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
                                        $('#accounts-form-streetname_type').prop('disabled', false);
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
				<?= $form->field($accountForm, 'koatuu')->hiddenInput(['id' => 'koatuu-hidden'])->label(false) ?>
            </div>
    </div>
    <!-- --------------------------------------END---------------------------------------- -->
</div>
<!----------------------------------------NEW---------------------------------------->
<div class="form-group required">
    <label class="control-label col-sm-3"><?= Yii::t('create-user', 'Address') ?></label>
    <div class="col-sm-2">
        <?= $form->field($accountForm, 'streetType', [
			'horizontalCssClasses' => [
				'offset' => '',
				'wrapper' => '',
			],
            'options' => [
                'style' => '',
            ]
		])->widget(Select2::className(), [
            'disabled' => !(bool) $accountForm->koatuu,
            'pluginEvents' => [
                'change' => 'function() {' . $setStreet . '}',
            ],
            'options' => [
                'placeholder' => Yii::t('create-user', 'type of street'),
                'id' => 'accounts-form-streetname_type'
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
            'data' => StreetTypes::listAll(),
        ])->label(false); ?>

    </div>

    <div class="col-sm-7" style="margin-bottom: -15px">
        <?= $form->field($accountForm, 'streetName', [
            'horizontalCssClasses' => [
                'offset' => '',
                'wrapper' => '',
            ],
        ])->widget(AutoComplete::className(),[
            'options' => [
                'disabled' => !(bool) $accountForm->streetType,
                'class' => 'form-control',
                'id' => 'accounts-form-streetname',
                'placeholder' => Yii::t('create-user', 'Enter the name of the street'),
            ]
        ])->label(false)
        ?>
    </div>
</div>
    <?= $form->field($accountForm, 'build')->textInput(['maxlength' => 8]) ?>
    <?= $form->field($accountForm, 'apartment')->textInput(['onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57', 'maxlength' => 4])?>
    <?= $form->field($accountForm, 'korp')->textInput(['onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57', 'maxlength' => 2])?>
<!-- --------------------------------------END---------------------------------------- -->

<hr/>

<?= $form->field($accountForm, 'dateOfBirthString')->widget(DatePicker::className(), [
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

<?= $form->field($accountForm, 'recruiter_id')->widget(Select2::className(), [
    'options' => ['placeholder' => Yii::t('create-user', "Not recruited")],
    'pluginOptions' => [
        'allowClear' => true
    ],
    'theme' => Select2::THEME_BOOTSTRAP,
    'data' => User::listAllWithPhoneAddress(isset($user->id) ? [$user->id] : []),
]) ?>

<?php
If (Yii::$app->user->can(User::ADMIN_ACCESS_ROLE)){
   echo $form->field($accountForm, 'added_id')->widget(Select2::className(), [
       'options' => ['placeholder' => Yii::t('create-user', "Not added")],
       'pluginOptions' => [
           'allowClear' => true
       ],
       'theme' => Select2::THEME_BOOTSTRAP,
       'data' => User::listAll('id', 'username', false, isset($user->id) ? [$user->id] : []),
   ]);
} else {
    echo "<div style='display: none;'>".$form->field($user, 'added_id')->hiddenInput()."</div>";
}
 ?>

<?= $form->field($accountForm, 'communication_type_ids')
    ->dropDownList(CommunicationType::listAll(), ['multiple' => true])
    ->label(Yii::t('create-user', 'Communication')) ?>

<?= $form->field($accountForm, 'phone')->widget(MaskedInput::className(), [
	'mask' => '+99 (999) 999-99-99',
	'options' => [
	        'id'    => 'user-phone',
		'readonly'  => ($user->phone && $user->phone{0} == '0') || $user->noPhone,
		'class'     => 'form-control',
	]
])->label(Yii::t('create-user', 'Phone')) ?>


<?= $form->field($accountForm, 'noPhone')->widget(CheckboxX::classname(),
	[
		'autoLabel' => false,
		'pluginOptions' => ['threeState' => false],
		'pluginEvents' => [
			"change" => "function() { $('#user-phone').prop('readonly', !$('#user-phone').prop('readonly')); }",
		],
	]
)->label(Yii::t('registration', 'No Phone')) ?>


<?= $form->field($accountForm, 'email')->widget(MaskedInput::className(), [
    'clientOptions' => [
        'alias' => 'email',
    ],
]) ?>

<?= $form->field($accountForm, 'profession_id')->dropDownList(Profession::listAll(), ['prompt' => Yii::t('create-user', 'Select profession')])->label(Yii::t('create-user', 'Profession')) ?>

<?= $form->field($accountForm, 'interest_ids')->dropDownList(Interests::listAll(), ['multiple' => true])->label(Yii::t('create-user', 'Interests')) ?>

<?= $form->field($accountForm, 'password')->passwordInput(["autocomplete" => "new-password"]) ?>

<?= $form->field($accountForm, 'user_id')->hiddenInput(['value' => $accountForm->user_id])->label(false) ?>

<?= $form->field($accountForm, 'badge_ids')->dropDownList(Badge::listAll(), ['multiple' => true])->label(Yii::t('create-user', 'Badges')) ?>

