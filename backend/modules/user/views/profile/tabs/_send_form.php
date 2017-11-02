<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;

	/**
	 * @var \yii\web\View $this
	 * @var common\models\Profile $profile
	 * @var backend\models\SendSingleMessageForm $sendForm
	 */
?>

	<?php

		$form = ActiveForm::begin([
		'id' => 'send-single-message',
		'action' => Url::to(['/user/profile/send-message']),
		'options' => ['class' => 'form-horizontal']
	]); ?>
	<div class="col-sm-9">
		<?=$form->field($sendForm, 'message')->textarea(
			[
				'id'	=> 'sendsinglemessageform-message',
				'placeholder' => Yii::t('user-profile', 'Enter message...'),
				'style' => 'width: 100%; resize: none;',
				'rows' => 4
			])->label(false)?>

		<?=$form->field($sendForm, 'user_id')->hiddenInput(['value' => $profile->user->id])->label(false)?>
	</div>
	<div class="col-sm-3">
		<?=Html::submitButton(Yii::t('user-profile', 'Send message'), ['name' => 'type', 'value' => 'message', 'class' => 'btn btn-danger pull-right btn-block btn-sm btn-primary', 'data-loading-text' => '<span>'.Yii::t('send', 'Loading...').'</span>',])?>
		<?=Html::submitButton(Yii::t('user-profile', 'Send interview'), ['name' => 'type', 'value' => 'interview', 'class' => 'btn btn-danger pull-right btn-block btn-sm btn-primary', 'data-loading-text' => '<span>'.Yii::t('send', 'Loading...').'</span>',])?>
	</div>
	<?php ActiveForm::end(); ?>
	<div class="clearfix"></div>
<?php
	//ajax send
	if(false) {
		$script = <<< JS
	$('#send-single-message').on('beforeSubmit', function () {
	var response = true;
	var forms = $(this).data('yiiActiveForm');
	var button = forms.submitObject;
    var form = $(this);
    var data = form.serialize();
    $('.btn.btn-primary').button('loading');
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        dataType: 'json', 
        data: data,
        success: function (data) {           
            if (data.status) {               
                alert(data.message);
            } else {
                alert(data.message);
            }
            $('.btn.btn-primary').button('reset');
            return false;
        },
    });
     $('#sendsinglemessageform-message').val("");
    return false;
});
JS;
		//маркер конца строки, обязательно сразу, без пробелов и табуляции
		$this->registerJs($script, yii\web\View::POS_READY);
	}
?>