<?php
	/**
	 * @var $this yii\web\View
	 * @var $model common\models\UploadForm
	 * @var $importModel common\models\KoatuuImport
	 */

	use common\models\KoatuuImport;
	use yii\bootstrap\ActiveForm;

	$this->title = Yii::t('location', 'Change import');
	$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($importModel, 'TEColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'A'])
	->label(Yii::t('location', 'TE Column')) ?>

<?= $form->field($importModel, 'KDSColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'B'])
	->label(Yii::t('location', 'KDS Column')) ?>

<?= $form->field($importModel, 'NPColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'C'])
	->label(Yii::t('location', 'NP Column')) ?>

<?= $form->field($importModel, 'NUColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'D'])
	->label(Yii::t('location', 'NU Column')) ?>

<?= $form->field($importModel, 'VOColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'E'])
	->label(Yii::t('location', 'VO Column')) ?>

<?= $form->field($importModel, 'VRUColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'G'])
	->label(Yii::t('location', 'VRU Design Column')) ?>

<?= $this->render('_upload_form', ['model' => $model, 'form' => $form]) ?>

<?php ActiveForm::end(); ?>