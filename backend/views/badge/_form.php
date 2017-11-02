<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model common\models\Badge */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .file-drag-handle.drag-handle-init, .kv-file-remove{
        display: none;
    }
</style>

<div class="badge-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	<?php if($model->isNewRecord || !$model->img){ ?>
    <?=  $form->field($model, 'imageFile')->widget(FileInput::classname(), [
		'options' => ['accept' => 'image/*'],
	]); ?>
	<?php }else{ ?>
		<?=  $form->field($model, 'imageFile')->widget(FileInput::classname(), [
			'options' => ['accept' => 'image/*'],
            'pluginEvents' => [
                "fileclear" => "function(event, key) { $('#deleteImage').val('deleteImage') }",
            ],
			'pluginOptions' => [
				'initialPreview'=>[
					"$model->img"
				],
				'showUpload' => false,
				'initialPreviewAsData'=>true,
				'browseLabel' => '',
				'initialPreviewConfig' => [
					['caption' => ''.$model->name.''],
				],
				'overwriteInitial'=>true,
				'maxFileSize'=>28000,
			],
		]); ?>
	<?php } ?>
    <?= $form->field($model, 'img')->hiddenInput(['value' => $model->img])->label(false); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= Html::hiddenInput('deleteImage', false, ['style' => 'display: none;', 'id'=>'deleteImage'])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('badge', 'Create') : Yii::t('badge', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
