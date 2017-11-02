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
 */
    use common\models\Badge;
    use common\models\CommunicationType;
	use common\models\Interests;
    use common\models\Koatuu;
    use common\models\Profession;
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
        $('#create-user-street').val('');
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '/site/get-streets',
            data: {koatuu: $('#koatuu-hidden').val(), type_id: $('#create-user-street_type').val()},
            success: function(data) {
                $('#create-user-street').autocomplete({\"source\":data.data});
                console.log(data);
            }
        });
";

$displayed = "
        if(count == 0) {
            $(this).parent().css('display', 'none');
        } else {
            $(this).parent().css('display', 'block');
        }
";
?>

<?= $form->field($user, 'recruiter_id')->widget(Select2::className(), [
    'options' => ['placeholder' => Yii::t('create-user', "Not recruited")],
    'pluginOptions' => [
        'allowClear' => true
    ],
    'theme' => Select2::THEME_BOOTSTRAP,
    'data' => User::listAllWithPhoneAddress(isset($user->id) ? [$user->id] : []),
]) ?>

<?php
If (Yii::$app->user->can(User::ADMIN_ACCESS_ROLE)){
   echo $form->field($user, 'added_id')->widget(Select2::className(), [
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

<?= $form->field($user, 'email')->widget(MaskedInput::className(), [
    'clientOptions' => [
        'alias' => 'email',
    ],
]) ?>

<?= $form->field($user, 'sex')->dropDownList([
    'female' => Yii::t('user', 'Female'),
    'male' => Yii::t('user', 'Male'),
], ['prompt' => Yii::t('create-user', 'Select sex')]) ?>


<?= $form->field($user, 'phone')->widget(MaskedInput::className(), [
    'mask' => '+99 (999) 999-99-99',
    'options' => [
        'readonly' => ($user->phone && $user->phone{0} == '0') || $user->noPhone,
        'class' => 'form-control',
    ]
])->label(Yii::t('create-user', 'Phone')) ?>


<?= $form->field($user, 'noPhone')->widget(CheckboxX::classname(),
    [
        'autoLabel' => false,
        'pluginOptions' => ['threeState' => false],
        'pluginEvents' => [
            "change" => "function() { $('#user-phone').prop('readonly', !$('#user-phone').prop('readonly')); }",
        ],
    ]
)->label(Yii::t('registration', 'No Phone')) ?>

<?= $form->field($user, 'password')->passwordInput(["autocomplete" => "off"]) ?>
<div style="padding-bottom: 15px;" >
    <!----------------------------------------NEW---------------------------------------->
    <div class="form-group" style="margin-bottom: 0px;">
        <label class="control-label col-sm-3"><?= Yii::t('create-user', 'Locality') ?></label>
        <div class="col-sm-9">
            <div class="koatuu-drop-down">
                <?= Select2::widget([
                    'name' => 'region',
                    'pluginEvents' => [
                        'change' => "function() {
                            var val = $(this).val();
                            if (val) {
                                var old_val = $('#koatuu-hidden').val();
                                if (old_val == '' || val.substr(0,2) != old_val.substr(0,2)) {
                                    $('#koatuu-hidden').val(val);
                                } 
                            }
                        }",
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                    ],
                    'value' => $user->regionKoatuu,
                    'data' => Koatuu::listAllRegionsKoatuu(),
                    'options' => [
                        'id' => 'region',
                        'placeholder' => Yii::t('create-user', 'Select a region'),
                    ],
                ]);
                ?>
            </div>
            <div class="koatuu-drop-down" style="padding-top: 15px; display:<?= $user->areaKoatuu ? 'block' : 'none'?>;">
                <?= DepDrop::widget([
                    'type' => DepDrop::TYPE_SELECT2,
                    'name' => 'area',
                    'options' => [
                        'id' => 'area',
                        'placeholder' => Yii::t('create-user', 'Select an area'),
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
                    'data' => Koatuu::listAllAreasKoatuu($user->regionKoatuu),
                    'value' => $user->areaKoatuu,
                    'pluginOptions' => [
                        'allowClear' => false,
                        'depends' => ['region'],
                        'url' => Url::to(['/koatuu/get-areas']),
                        'placeholder' => Yii::t('create-user', 'Select an area'),
                        'initDepends' => 'region_id', // initial ajax loading will be fired first for parent-1, then child-1, and child-2
                        'initialize' => true,
                    ],
                ]);
                ?>
            </div>
            <div class="koatuu-drop-down" style="padding-top: 15px; display:<?= $user->areaKoatuu != $user->koatuu ? 'block' : 'none'?>;">
                <?= DepDrop::widget([
                    'type' => DepDrop::TYPE_SELECT2,
                    'name' => 'city',
                    'data' => ArrayHelper::map(Koatuu::listAllCitiesKoatuu($user->koatuu), 'id', 'name'),
                    'value' => $user->koatuu,
                    'options' => [
                        'id' => 'city',
                        'placeholder' => Yii::t('create-user', 'Select an region'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
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
            <div style="display: none">
				<?= $form->field($user, 'koatuu')->hiddenInput(['id' => 'koatuu-hidden'])->label(false) ?>
            </div>
        </div>
    </div>
      <!-- --------------------------------------END---------------------------------------- -->
</div>

<?= $form->field($user, 'profession_id')->dropDownList(Profession::listAll(), ['prompt' => Yii::t('create-user', 'Select profession')])->label(Yii::t('create-user', 'Profession')) ?>
<?= $form->field($user, 'communication_type_ids')->dropDownList(CommunicationType::listAll(), ['multiple' => true])->label(Yii::t('create-user', 'Communication')) ?>
<?= $form->field($user, 'interest_ids')->dropDownList(Interests::listAll(), ['multiple' => true])->label(Yii::t('create-user', 'Interests')) ?>
<?= $form->field($user, 'badge_ids')->dropDownList(Badge::listAll(), ['multiple' => true])->label(Yii::t('create-user', 'Badges')) ?>
