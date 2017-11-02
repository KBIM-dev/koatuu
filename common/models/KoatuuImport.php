<?php

namespace common\models;

use PHPExcel_IOFactory;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class KoatuuImport extends Model
{

	const SCENARIO_DE_COMUNISATION_IMPORT = 'deComunisationImport';
	const SCENARIO_CHANGE_IMPORT = 'changeImport';

	public $importFilePath;
	public $fullPathToFile;
//deComunisation import
	public $koatuuColumn;
	public $locatiuonTypeColumn;
	public $oldNameColumn;
	public $newNameColumn;
	// Change import
	public $TEColumn;
	public $KDSColumn;
	public $NPColumn;
	public $NUColumn;
	public $VOColumn; // 3 - Change name 4 - Delete location
	//Common column
	public $VRUColumn;

	const COLUMN_LIST = ['A'=>'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E'=> 'E', 'F' => 'F', 'G'=>'G'];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['importFile', 'koatuuColumn', 'locatiuonTypeColumn', 'oldNameColumn', 'newNameColumn', 'VRUColumn'], 'required', 'on' => self::SCENARIO_DE_COMUNISATION_IMPORT],
            [['importFile', 'TEColumn', 'KDSColumn', 'NPColumn', 'NUColumn', 'VOColumn', 'VRUColumn'], 'required', 'on' => self::SCENARIO_CHANGE_IMPORT],
            [['koatuuColumn', 'locatiuonTypeColumn', 'oldNameColumn', 'newNameColumn', 'VRUColumn'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'importFile' => Yii::t('location', 'Select file'),
        ];
    }

    public function formName()
	{
		return 'koatuu-import';
	}

	/**
	 * @return bool|int
	 */
    public function deCommunisationImport(){
		$result = false;
    	if(!empty($this->fullPathToFile) && file_exists($this->fullPathToFile)){
			$value = "";
			$objPHPExcel = PHPExcel_IOFactory::load($this->fullPathToFile);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			if(!empty($sheetData)){
				if(isset($sheetData[1][$this->koatuuColumn]) && is_string($sheetData[1][$this->koatuuColumn])){
					unset($sheetData[1]);
				}
				foreach($sheetData as $key=>$data){
					if(!empty($data[$this->koatuuColumn])){
						if(strlen(strval($data[$this->koatuuColumn])) < 10){
							$data[$this->koatuuColumn] = '0'.$data[$this->koatuuColumn];
						}

						$model = Koatuu::findOne(['TE' => $data[$this->koatuuColumn]]);

						if(!empty($model) && isset($data[$this->oldNameColumn]) && isset($data[$this->newNameColumn]) && isset($data[$this->VRUColumn])){
							if(!empty($data[$this->newNameColumn])){
								$model->NU = $data[$this->newNameColumn];
								if($model->save()){
									if(!empty($value)){
										$value .=", ";
									}
									$value .= " ('".addslashes($model->TE)."', '".addslashes($model->TE)."', '".addslashes($data[$this->oldNameColumn])."', '".addslashes($data[$this->newNameColumn])."', '".addslashes($data[$this->VRUColumn])."', ".time().")";
								}
							}
						}
					}
				}
				if(!empty($value)){
					$sql = "INSERT INTO `koatuu_history` (`old_koatuu`, `new_koatuu`, `old_location_name`, `new_location_name`, `design_VRU`, `time`)"
						." VALUES $value ";
					$connection = Yii::$app->getDb();
					$command = $connection->createCommand($sql);

					$result = $command->execute();
				}
				/*foreach($sheetData as $key => $raw){
					$value .= " (".addslashes($raw[$this->koatuuColumn]).", '".addslashes($raw[$this->locatiuonTypeColumn])."', '".addslashes($raw[$this->newNameColumn])."')";
					if(isset($sheetData[$key+1])){
						$value .=",";
					}
				}
				$sql = "INSERT INTO koatuu (TE, NP, NU) VALUES $value ON DUPLICATE KEY UPDATE NP=VALUES(NP), NU=VALUES(NU);";

				$connection = Yii::$app->getDb();
				$command = $connection->createCommand($sql);

				$result = $command->execute();*/
			}
			unlink($this->fullPathToFile);
		}
		return $result;

	}

	/**
	 * @return bool|int
	 */
	public function changeImport(){
		$result = false;
		if(!empty($this->fullPathToFile) && file_exists($this->fullPathToFile)){
			$value = "";
			$objPHPExcel = PHPExcel_IOFactory::load($this->fullPathToFile);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			if(!empty($sheetData)){
				if(isset($sheetData[1][$this->koatuuColumn]) && is_string($sheetData[1][$this->TEColumn])){
					unset($sheetData[1]);
				}

				foreach($sheetData as $key=>$data){
					$change = "";
					if(isset($data[$this->TEColumn]) && strlen(strval($data[$this->TEColumn])) < 10){
						$data[$this->TEColumn] = '0'.$data[$this->TEColumn];
					}
					if(isset($data[$this->KDSColumn]) && strlen(strval($data[$this->KDSColumn])) < 10){
						$data[$this->KDSColumn] = '0'.$data[$this->KDSColumn];
					}

					if(isset($data[$this->VOColumn])) {
						switch( $data[ $this->VOColumn ] ) {
							case 1:
								break;
							case 2:
								break;
							case 3:// Change name
								if(!empty($data[ $this->TEColumn ])) {
									$model = Koatuu::findOne([ 'TE' => $data[ $this->TEColumn ] ]);
									if(!empty($model) && !empty($data[ $this->NUColumn ])) {
										$change = "('".addslashes($model->TE)."', '".addslashes($model->TE)."', '".addslashes($model->NU)."', '".addslashes($data[ $this->NUColumn ])."', '".addslashes($data[ $this->VRUColumn ])."', ".time().")";
										$model->NU = $data[ $this->NUColumn ];
										$result = $model->save();
									}
								}
								break;
							case 4://Delete location
								if(!empty($data[ $this->KDSColumn ]) && !empty($data[ $this->TEColumn ])) {

									$parentModel = Koatuu::findOne([ 'TE' => $data[ $this->TEColumn ] ]);

									if(!empty($parentModel)){

										$uq = User::find()->where(['koatuu' => $data[ $this->KDSColumn ] ]);
										if($uq->exists()){
											User::updateAll(['koatuu' => $data[ $this->TEColumn ]], ['koatuu' => $data[ $this->KDSColumn ]]);
										}

										$sq = Street::find()->where(['koatuu' => $data[ $this->KDSColumn ] ]);
										if($sq->exists()){
											//TODO:: Fix where streets have same name but diff id
											Street::updateAll(['koatuu' => $data[ $this->TEColumn ]], ['koatuu' => $data[ $this->KDSColumn ]]);
										}

									}

									$model = Koatuu::findOne([ 'TE' => $data[ $this->KDSColumn ] ]);
									if(!empty($model) && !empty($parentModel)) {
										$change = "('".addslashes($data[ $this->KDSColumn ])."', '".$data[ $this->TEColumn ]."', '".addslashes($model->NU)."','".$parentModel->NU."', '".addslashes($data[ $this->VRUColumn ])."', ".time().")";

										$result = $model->delete();
									}
								}
								break;
						}

						if($result && !empty($change)) {
							if(!empty($value)) {
								$value .= ", ";
							}
							$value .= $change;
						}
					}
				}
				if(!empty($value)){
					$sql = "INSERT INTO `koatuu_history` (`old_koatuu`, `new_koatuu`, `old_location_name`, `new_location_name`, `design_VRU`, `time`)"
						." VALUES $value ";
					$connection = Yii::$app->getDb();
					$command = $connection->createCommand($sql);
					$result = $command->execute();
				}
			}
			unlink($this->fullPathToFile);
		}
		return $result;
	}

}
