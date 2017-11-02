<?php
	namespace common\models;

	use Yii;
	use yii\base\Model;
	use yii\helpers\Inflector;
	use yii\web\UploadedFile;

	/**
	 * This is the model class for table "areas".
	 *
	 * @property UploadedFile $imageFile
	 * @property UploadedFile $importFile
	 */
	class UploadForm extends Model
	{
		public $imageFile;
		public $importFile;
		const SCENARIO_IMPORT = 'import';


		public function rules()
		{
			return [
				[['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif, jpeg', 'on' => self::SCENARIO_DEFAULT ],
				[['importFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls', 'on' => self::SCENARIO_IMPORT ],
			];
		}

		public function upload()
		{
			if ($this->validate()) {
				$this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
				return true;
			} else {
				return false;
			}
		}

		public function uploadImage()
		{
			$catalog = \Yii::getAlias('@backend/web/uploads');

			if(!file_exists($catalog)){
				mkdir($catalog);
				chmod($catalog, 0777);
			}
            $catalog .= '/badge';
			if(!file_exists($catalog)){
				mkdir($catalog);
				chmod($catalog, 0777);
			}

			if ($this->validate()) {
				$pathToFile = '/uploads/badge/' . time() . '_badge.' . $this->imageFile->extension;
				$fullPathToFile = $catalog.'/' . time() . '_badge.' . $this->imageFile->extension;
				$this->imageFile->saveAs($fullPathToFile);

				return $pathToFile;
			} else {
				return false;
			}
		}

		public function uploadImportFile($returnFullPath = true)
		{
			$catalog = \Yii::getAlias('@backend/web/uploads');

			if(!file_exists($catalog)){
				mkdir($catalog);
				chmod($catalog, 0777);
			}
			$catalog .= '/import_koatuu';
			if(!file_exists($catalog)){
				mkdir($catalog);
				chmod($catalog, 0777);
			}

			if ($this->validate()) {

				$pathToFile = '/uploads/import_koatuu/' . time() . '_'.Inflector::slug( $this->importFile->baseName, '_', true ).'.'.$this->importFile->extension;
				$fullPathToFile = $catalog.'/' . time() . '_'.Inflector::slug( $this->importFile->baseName, '_', true ).'.'.$this->importFile->extension;
				$this->importFile->saveAs($fullPathToFile);
				if($returnFullPath){
					return $fullPathToFile;
				}else{
					return $pathToFile;
				}
			} else {
				return false;
			}
		}
	}