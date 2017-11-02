<?php
	/**
	 * @var $this yii\web\View
	 * @var $model common\models\UploadForm
	 * @var $importModel common\models\KoatuuImport
	 */

	use common\models\KoatuuImport;
	use kartik\widgets\FileInput;
	use yii\bootstrap\ActiveForm;

?>


	<?php echo $form->field($model, 'importFile')->widget(FileInput::classname(), [
		'options' => ['multiple' => false],
		'pluginOptions' => ['previewFileType' => 'any']
	])->label(Yii::t('koatuu',"Import File")); ?>
