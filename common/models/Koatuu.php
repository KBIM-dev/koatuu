<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "koatuu".
 *
 * @property string $TE
 * @property string $allAddressSting
 * @property array $allAddressArray
 * @property string $NP
 * @property string $NU
 */
class Koatuu extends ActiveRecord
{
	public $name;

	const KOATUU_REGIONS = 'regions';
	const KOATUU_AREAS 	 = 'areas';
	const KOATUU_CITIES  = 'cities';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'koatuu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TE', 'NU'], 'required'],
            [['NU'], 'string'],
            [['TE'], 'string', 'max' => 10],
            [['NP'], 'string', 'max' => 1],
			[['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TE' => Yii::t('interests', 'KOATUU'),
            'NP' => Yii::t('interests', 'Np'),
            'NU' => Yii::t('interests', 'Nu'),
        ];
    }

    public function getAllAddressSting() {
        return implode(' ', $this->allAddressArray);
    }

    public function getAllAddressArray() {
        $cache = Yii::$app->cache;
        $key = "allAdressArr_$this->TE";
        $string = $cache->get($key);
        if ($string === false) {
            $string = [];
            $connection = Yii::$app->getDb();
            $sql = "SELECT CASE WHEN LOCATE('/', NU) > 0 THEN LEFT(NU,  LOCATE('/', NU) - 1) ELSE NU END as name FROM koatuu where SUBSTRING(koatuu.TE, 1,2) = SUBSTRING('$this->TE', 1,2) and SUBSTRING(koatuu.TE, 3,8) = '00000000'";
            $name = $connection->createCommand($sql)->queryOne()['name'];
            $string[] = self::RegionName($name);

            $sql = "SELECT NP, TE, CASE WHEN LOCATE('/', NU) > 0 THEN LEFT(NU,  LOCATE('/', NU) - 1) ELSE NU END as name FROM koatuu where SUBSTRING(koatuu.TE, 1,5) = SUBSTRING('$this->TE', 1,5) and SUBSTRING(koatuu.TE, 6,5) = '00000'";
            $tmp = $connection->createCommand($sql)->queryOne()['name'];
            if ($name != $tmp) {
                $name = $tmp;
                $NP = $connection->createCommand($sql)->queryOne()['NP'];
                $TE = $connection->createCommand($sql)->queryOne()['TE'];
                $string[] = self::AreasName($NP, $TE, $name);
            }


            $sql = "SELECT NP, CASE WHEN LOCATE('/', NU) > 0 THEN LEFT(NU,  LOCATE('/', NU) - 1) ELSE NU END as name FROM koatuu where koatuu.TE = '$this->TE'";
            $tmp = $connection->createCommand($sql)->queryOne()['name'];
            if ($name != $tmp) {
                $name = $tmp;
                $NP = $connection->createCommand($sql)->queryOne()['NP'];
                $string[] = self::CityName($NP, $name);
            }
            $cache->set($key, $string);
        }
        return $string;
    }

	/**
	 * @return array
	 */
    public static function listAllRegions(){
		$connection = Yii::$app->getDb();

		$sql = "SELECT * FROM koatuu where SUBSTRING(koatuu.TE, 3,8) = '00000000'";
		$results = $connection->createCommand($sql)->queryAll();
		$results = ArrayHelper::map($results, 'TE', 'NU');;
		$regions = [];
		$cities  = [];
		foreach($results as $key=>$result){
			if($region = stristr($result, '/', true) ){
				$regions[$key] = str_replace('АВТОНОМНА РЕСПУБЛІКА', 'А.Р.', str_replace('ОБЛАСТЬ', ' обл.',$region));
			}else{
				if($city = str_replace('М.', '', $result)){
					$city = "м. ".$city;
					$cities[$key] = $city;
				}
			}
		}

		$result = [
			'Міста' =>	$cities,
			'Області' => $regions
		];
		return $result;
	}

	public static function listAllRegionsKoatuu() {
		$resultsTemp = Koatuu::find()->select("TE, NP, CASE WHEN LOCATE('/', `NU`) > 0
					THEN 
						LEFT(`NU`,  LOCATE('/', `NU`) - 1)
						 
					ELSE 
						NU 
				END as name")->where(['=', 'SUBSTRING(koatuu.TE, 3,8)', '00000000'])->asArray()->all();

		$cities = [];
		$regions = [];
		$resultsTemp = ArrayHelper::map($resultsTemp, 'TE', 'name');
		$result = [];
		foreach($resultsTemp as $key => $value) {
			$regionName = self::RegionName($value);
			if(stripos($regionName, 'м. ') !== false) {
				$cities[$key] = $regionName;
			} else {
				$regions[$key] = $regionName;
			}
		}
		if($cities) {
			$result['Міста'] = $cities;
		}
		$result['Області'] = $regions;
		return $result;
	}

	/**
	 * @param $koatuu_region
	 *
	 * @return array
	 */
	public static function listAllAreas($koatuu_region){
		$connection = Yii::$app->getDb();
		$koatuu_region = substr($koatuu_region, 0, 2);
    	$sql = "SELECT * FROM koatuu where SUBSTRING(koatuu.TE, 1,2) = '$koatuu_region' and SUBSTRING(koatuu.TE, 6,5) = '00000' and SUBSTRING(koatuu.TE, 4,7) != '0000000'";
		$regions = $connection->createCommand($sql)->queryAll();
		$results = ArrayHelper::map($regions, 'TE', 'NU');
		$format=[];
		$cities=[];
		$regions_formatted=[];

		foreach($results  as $key => $result){
			if($koatuu_region == 80 || $koatuu_region == 85){
				$format[$key] = "р-н. $result";
			}else{
				if(strpos($result, '/')!== false){
					$result = stristr($result, '/', true);
					$result = str_replace('РАЙОН', '', $result);
					$regions_formatted[$key] = "р-н. $result";
				}else{
					$cities[$key] = "м. $result";
				}
			}
		}
		if($koatuu_region == 80){
			$type = "Райони міста Київ";
		}elseif($koatuu_region == 85){
			$type = "Райони міста Севастополь";
		}else{
			$type = "Міста обласного значення";
			$type1 = "Райони";
		}
		if(!empty($type1)){
			return [ "$type"=> $cities, "$type1" => $regions_formatted];
		}else{
			return [ "$type"=> $format];
		}

	}

	public static function listAllAreasKoatuu($koatuu_region) {
		/*$connection = Yii::$app->getDb();*/
		$koatuu_region = substr($koatuu_region, 0, 2);
		$results = Koatuu::find()->select("TE, NP, CASE WHEN LOCATE('/', `NU`) > 0 THEN LEFT(`NU`,  LOCATE('/', `NU`) - 1) ELSE NU END as NU")
			->where("SUBSTRING(koatuu.TE, 1,2) = '" .$koatuu_region . "' and SUBSTRING(koatuu.TE, 6,5) = '00000' and SUBSTRING(koatuu.TE, 4,7) != '0000000'")
			->asArray()->all();
		/*$regions = $connection->createCommand($sql)->queryAll();*/
		$format=[];
		$cities=[];
		$regions_formatted=[];
		foreach($results  as $result){
			$name = self::AreasName($result['NP'], $result['TE'], $result['NU']);
			if($koatuu_region == 80 || $koatuu_region == 85){
				$format[$result['TE']] = $name;
			}else{
				if(strpos($name, 'р-н.') !== false) {
					$regions_formatted[$result['TE']] = $name;
				} else {
					$cities[$result['TE']] = $name;
				}
			}
		}
		$type = "";
		if($koatuu_region == 80){
			$type = "Райони міста Київ";
		}elseif($koatuu_region == 85){
			$type = "Райони міста Севастополь";
		}else{
			$type = "Міста обласного значення";
			$type1 = "Райони";
		}
		if(!empty($type1)){
			return [ "$type"=> $cities, "$type1" => $regions_formatted];
		}else{
			return [ "$type"=> $format];
		}
	}
	/**
	 * @param $koatuu_areas
	 *
	 * @return array
	 */
	public static function listAllCities($koatuu_areas){

		$connection = Yii::$app->getDb();
		$koatuu_areas = substr($koatuu_areas, 0, 5);
		$sql = "SELECT * FROM koatuu where SUBSTRING(koatuu.TE, 1,5) = '$koatuu_areas' and SUBSTRING(koatuu.TE, 7,4) != '0000'";
		$regions = $connection->createCommand($sql)->queryAll();
		return ArrayHelper::map($regions, 'TE', 'NU');
	}

	public static function listAllCitiesKoatuu($koatuu_areas) {

		$resultTemp = Koatuu::find()->select("TE, NP, CASE WHEN LOCATE('/', `NU`) > 0 THEN LEFT(`NU`,  LOCATE('/', `NU`) - 1) ELSE NU END as NU")
			->where("SUBSTRING(koatuu.TE, 1,5) = '" . substr($koatuu_areas, 0, 5) . "' and SUBSTRING(koatuu.TE, 7,4) != '0000' AND TRIM(`NP`) <> ''")->asArray()->all();
		$result = [];
		foreach($resultTemp as $value) {
			$name = self::CityName($value['NP'], $value['NU']);
			$result[] = ['id' => $value['TE'], 'name' => $name];
		}
		return $result;
	}

	/**
	 * @param $koatuu
	 *
	 * @return bool
	 */
	public static function isCity($koatuu){
		/**
		 * @var $result self
		 */
		$result = self::find()->where(['TE' => $koatuu])->one();
		if(strpos($result->NU, '/') !== false){
			return false;
		}else{
			return true;
		}
	}

    public static function mb_ucfirst($string, $enc = 'UTF-8'){
        $tempArr = explode(' ',$string);
        $result = [];
        foreach ($tempArr as $item) {
            $results = [];
            $tempArrs = explode('-',$item);
            foreach ($tempArrs as $items) {
                $results[] = mb_strtoupper(mb_substr($items, 0, 1, $enc), $enc).mb_strtolower(mb_substr($items, 1, mb_strlen($items, $enc), $enc));
            }
            $result[] = implode('-', $results);
        }
        return implode(' ', $result);
    }

    /**
     * @param $result
     * @return string
     */
    public static function RegionName($result)
    {
        $pos = strripos($result, '.');
        if ($pos === false) {
            $result = self::mb_ucfirst($result);
            $result = str_replace('Область', 'обл.', $result);
        } else {
            $tempArr = explode('.', $result);
            $result = mb_strtolower($tempArr[0]) . '. ' . self::mb_ucfirst($tempArr[1]);
        }
        return $result;
    }

    /**
     * @param $NP
     * @param $TE
     * @param $name
     * @return string
     */
    public static function AreasName($NP, $TE, $name)
    {
        $type = '';
        $typeEnd = '';
        switch ($NP){
            case 'М':
                $type = 'м.';
                break;
            case 'Т':
                $type = 'смт.';
                break;
            case 'С':
                $type = 'с.';
                break;
            case 'Щ':
                $type = 'с-ще.';
                break;
            case 'Р':
                $type = 'р-н.';
                break;
        }
        if (empty($type) && substr($TE,2,2) == '10') {
            $type = 'м.';
        }
        $name = self::mb_ucfirst($name);
        $name = str_replace('Район','р-н.',$name);

        return trim($type.' '.$name.' '.$typeEnd);
    }

    /**
     * @param $NP
     * @param $name
     * @return string
     */
    public static function CityName($NP, $name)
    {
        $type = '';
        $typeEnd = '';
        switch ($NP){
            case 'М':
                $type = 'м.';
                break;
            case 'Т':
                $type = 'смт.';
                break;
            case 'С':
                $type = 'с.';
                break;
            case 'Щ':
                $type = 'с-ще.';
                break;
            case 'Р':
                $typeEnd = 'р-н.';
                break;
        }
        return trim($type.' '.self::mb_ucfirst($name).' '.$typeEnd);
    }
}
