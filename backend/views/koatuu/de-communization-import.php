<?php
	/**
	 * @var $this yii\web\View
	 * @var $model common\models\UploadForm
	 * @var $importModel common\models\KoatuuImport
	 */

	use common\models\KoatuuImport;
	use yii\bootstrap\ActiveForm;

	$this->title = Yii::t('location', 'De communization import');
	$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($importModel, 'koatuuColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'A'])
	->label(Yii::t('location', 'Koatuu Column')) ?>

<?= $form->field($importModel, 'locatiuonTypeColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'A'])
	->label(Yii::t('location', 'Locatiuon Type Column')) ?>

<?= $form->field($importModel, 'locatiuonTypeColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'B'])
	->label(Yii::t('location', 'Locatiuon Type Column')) ?>

<?= $form->field($importModel, 'oldNameColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'C'])
	->label(Yii::t('location', 'Old Location Name Column')) ?>

<?= $form->field($importModel, 'newNameColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'D'])
	->label(Yii::t('location', 'New Location Name Column')) ?>

<?= $form->field($importModel, 'VRUColumn')
	->dropDownList(KoatuuImport::COLUMN_LIST, ['value' => 'E'])
	->label(Yii::t('location', 'VRU Design Column')) ?>

<?= $this->render('_upload_form', ['model' => $model, 'form' => $form]) ?>

<?php ActiveForm::end(); ?>