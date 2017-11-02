<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

	use kartik\date\DatePicker;
	use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View                    $this
 * @var dektrium\user\models\User       $user
 * @var \common\models\Profile    $profile
 */

?>

<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-9',
        ],
    ],
]); ?>

<?= $form->field($profile, 'last_name') ?>
<?= $form->field($profile, 'name') ?>
<?= $form->field($profile, 'middle_name') ?>

<?= $form->field($profile, 'dateOfBirthString')->widget(DatePicker::className(), [
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


<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
