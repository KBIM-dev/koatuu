<?php
namespace common\models;


use dektrium\user\helpers\Password;
use dektrium\user\models\User as BaseUser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use \voskobovich\behaviors\ManyToManyBehavior;
use yii\helpers\Html;
use yii\helpers\Url;
use developeruz\db_rbac\interfaces\UserRbacInterface;

/**
 * Class User
 * @package common\models
 *
 *   Database fields:
 * @property integer $areas_id
 * @property integer $phone
 * @property integer $recruiter_id
 * @property string $address
 * @property string $regionName
 * @property string $addedName
 * @property string $regionNameMigration
 * @property string $fullName
 * @property string $areasName
 * @property string $locCitiesName
 * @property string $location_id
 * @property string $loc_id
 * @property integer $profession_id
 * @property integer $region_id
 * @property integer $street_id
 * @property integer $chat_id
 * @property integer $noPhone
 * @property integer $streetType
 * @property boolean $login_request_answer
 * @property integer $login_request_time
 * @property string $sex
 * @property string $koatuu
 * @property string $streetName
 * @property string $regionKoatuu
 * @property string $areaKoatuu
 * @property string $addressString
 * @property integer $korp
 * @property integer $apartment
 * @property string $build
 * @property integer $added_id
 *
 * * Defined relations:
 * @property Profile   			    $profile
 * @property Street                 $street
 * @property Koatuu   			    $koatuus
 * @property Areas   			    $areas
 * @property Regions  			    $region
 * @property Profession   		    $profession
 * @property User   			    $recruiter
 * @property User                   $added
 * @property Cities   			    $locCities
 * @property Cities   			    $locationCities
 * @property Badge   			    $badges
 * @property CommunicationType[]	$communicationTypes
 * @property Interests[]			$interests
 * @property array                  $communication_type_ids
 * @property array                  $interest_ids
 */
class User extends BaseUser implements UserRbacInterface
{
	const ADMIN_ACCESS_ROLE 	= 'adminAccess';

	//filter setting for user search models
	const AREAS_FILTER 			= 'areasFilter';
	const REGIONS_FILTER 		= 'regionsFilter';
	const NO_FILTER 			= 'allUserFilter';

	public $info;
    public $noPhone;

    /**
	 * Set relation to glossary
	 */


	public function scenarios()
	{
		$scenarios = parent::scenarios();
		// add field to scenarios
		$scenarios['create'][]   = 'chat_id';
		$scenarios['update'][]   = 'chat_id';
		$scenarios['register'][] = 'chat_id';
		$scenarios['create'][]   = 'recruiter_id';
		$scenarios['update'][]   = 'recruiter_id';
		$scenarios['register'][] = 'recruiter_id';
		$scenarios['create'][]   = 'added_id';
		$scenarios['update'][]   = 'added_id';
		$scenarios['register'][] = 'added_id';
		$scenarios['create'][]   = 'areas_id';
		$scenarios['update'][]   = 'areas_id';
		$scenarios['register'][] = 'areas_id';
		$scenarios['create'][]   = 'profession_id';
		$scenarios['update'][]   = 'profession_id';
		$scenarios['register'][] = 'profession_id';
		$scenarios['create'][]   = 'communication_type_ids';
		$scenarios['update'][]   = 'communication_type_ids';
		$scenarios['register'][] = 'communication_type_ids';
		$scenarios['create'][]   = 'badge_ids';
		$scenarios['update'][]   = 'badge_ids';
		$scenarios['register'][] = 'badge_ids';
		$scenarios['create'][]   = 'phone';
		$scenarios['update'][]   = 'phone';
		$scenarios['register'][] = 'phone';
		$scenarios['create'][]   = 'noPhone';
		$scenarios['update'][]   = 'noPhone';
		$scenarios['register'][] = 'noPhone';
		$scenarios['create'][]   = 'address';
		$scenarios['update'][]   = 'address';
		$scenarios['register'][] = 'address';
		$scenarios['create'][]   = 'sex';
		$scenarios['update'][]   = 'sex';
		$scenarios['register'][] = 'sex';
		$scenarios['create'][]   = 'interest_ids';
		$scenarios['update'][]   = 'interest_ids';
		$scenarios['register'][] = 'interest_ids';
		$scenarios['create'][]   = 'koatuu';
		$scenarios['update'][]   = 'koatuu';
		$scenarios['register'][] = 'koatuu';
		$scenarios['create'][]   = 'korp';
		$scenarios['update'][]   = 'korp';
		$scenarios['register'][] = 'korp';
		$scenarios['create'][]   = 'apartment';
		$scenarios['update'][]   = 'apartment';
		$scenarios['register'][] = 'apartment';
		$scenarios['create'][]   = 'build';
		$scenarios['update'][]   = 'build';
		$scenarios['register'][] = 'build';
		return $scenarios;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$rules = [
			'emailPattern'  => ['email', 'email', 'skipOnEmpty' => true],
			'emailLength'   => ['email', 'string', 'skipOnEmpty' => true, 'max' => 255],
			'emailUnique'   => ['email','unique', 'skipOnEmpty' => true, 'message' => \Yii::t('user', 'This email address has already been taken')],
			'emailTrim'     => ['email', 'trim'],
			// password rules
			'passwordRequired' => ['password', 'required', 'on' => ['register', 'create']],
			'passwordLength'   => ['password', 'string', 'min' => 6, 'max' => 72, 'on' => ['register', 'create']],
		];
		return ArrayHelper::merge($rules, [
			[['communication_type_ids',], 'required'],
			[['communication_type_ids', 'badge_ids', 'interest_ids'], 'each', 'rule' => ['integer']],
			['phone', 'validatePhone', 'skipOnEmpty' => false, 'on' => ['create', 'update', 'register']],
			['noPhone', 'integer', 'skipOnEmpty' => true, 'on' => ['create', 'update', 'register']],
            ['location_id', 'validateLocationId', 'skipOnEmpty' => false, 'on' => ['create', 'update', 'register']],
			['loc_id', 'validateLocId', 'skipOnEmpty' => false, 'on' => ['create', 'update', 'register']],
			[['chat_id', 'recruiter_id', 'added_id', 'areas_id', 'profession_id', 'loc_id', 'street_id', 'korp', 'apartment'], 'integer'],
			[['phone', 'address', 'sex', 'koatuu'], 'string', 'max' => 255],
            [['build'], 'string', 'max' => 8],
			[['sex'], 'validateSex', 'skipOnEmpty' => true],
            [['street_id'], 'exist', 'skipOnError' => true, 'targetClass' => Street::className(), 'targetAttribute' => ['street_id' => 'id']],
			[['areas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Areas::className(), 'targetAttribute' => ['areas_id' => 'id']],
			[['profession_id'], 'exist', 'skipOnEmpty' => true, 'skipOnError' => true, 'targetClass' => Profession::className(), 'targetAttribute' => ['profession_id' => 'id']],
			[['recruiter_id'], 'exist', 'skipOnError' => true, 'targetClass' => self::className(), 'targetAttribute' => ['recruiter_id' => 'id']],
			[['added_id'], 'exist', 'skipOnError' => true, 'targetClass' => self::className(), 'targetAttribute' => ['added_id' => 'id']],

		]);
	}

	public function behaviors()
	{
		$behaviors = parent::behaviors();

		return ArrayHelper::merge($behaviors, [
			[
				'class' => ManyToManyBehavior::className(),
				'relations' => [
					'communication_type_ids' => 'communicationTypes',
					'badge_ids' 			 => 'badges',
					'interest_ids' 			 => 'interests',
				],
			],
		]);
	}

	public function validateSex($attribute, $params)
	{
		if (!in_array($this->$attribute, ['male', 'female'])) {
			$this->addError($attribute, 'Пол должен быть либо Мужской или Женский.');
		}
	}

	public function validateLocId($attribute, $params){
		//if isset local_id, location_id must be empty
		if(!empty($this->$attribute)){
			$this->location_id = null;
		}
	}

	/**
	 * This function use for validate attribute
	 * location_id and as a filter for this attribute
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateLocationId($attribute, $params){
        if(!empty($this->$attribute)){
            $this->loc_id = null;
            $shortNameList = LocationTypes::getCitiesShortType();
            $shortNameListString = implode("|", $shortNameList);
            $shortNameListString = str_replace(".",'\.', $shortNameListString);
            $re = '/^('.$shortNameListString.')\s/is';
            if(!preg_match($re, trim($this->location_id), $matches)){
                $this->addError($attribute, \Yii::t('user', 'Wrong location type, string must begin as ({shortNames})',[
                    'shortNames' => '"'.implode('", "', $shortNameList).'"'
                ]));
            }
        } else {
            if ($this->areas_id) {
                $modelAreas = Areas::findOne($this->areas_id);
                if ($modelAreas->types->type == 0) {
                    $this->addError($attribute, \Yii::t('user', 'Please set city name'));
                }
            }
        }
	}

	public function validatePhone()
	{
        if ($this->noPhone) {
            return true;
        }
        $error = false;
        if(!$this->phone) {
            $this->addError('phone', \Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => \Yii::t('create-user', 'Phone')]));
        }
        if ($this->phone != 'admin') {
            $phone = preg_replace("/[^0-9]/", '', $this->phone);
            $user = User::find()->where(['phone' => $phone])->andWhere(['not', ['id' => $this->id]]);
            if (strlen($phone) != 12) {
                $error = true;
            }
        } else {
            $user = User::find()->where(['phone' => $this->phone])->andWhere(['not', ['id' => $this->id]]);
        }
		if ($user->exists()) {
			$this->addError('phone', \Yii::t('registration', 'This phone number has already ben taken'));
		}elseif($error) {
            $this->addError('phone', \Yii::t('registration', 'Incorrect phone format'));
        }else{
			return true;
		}
	}

	/**
	 * Getter for convert attribute location_id to string
	 *
	 * @return string
	 */
	public function getLocationIdString(){
		if(isset($this->location_id)){
			$city = Cities::findOne($this->location_id);
			if($city){
				return $city->getFullCityName();
			}
		}
	}

	public function getAddressString()
	{
		$result = '';
		if ($this->street) {
			$result = Yii::t(
				'user',
				'{street_type} {street}, {build} {korp} {apartment}',
				[
					'street_type' => $this->street->type->short_name,
					'street' => $this->street->name,
					'build' => $this->build ? $this->build : '',
					'korp' => $this->korp ? Yii::t('user', ' korp. {korp}', ['korp' => $this->korp]) : '',
					'apartment' => $this->apartment ? Yii::t('user', ' ap. {apartment}', ['apartment' => $this->apartment]) : '',
				]
			);
		}

		return $result;
	}

	public function getLocationStringMigration(){
        $result[] = $this->regionNameMigration;
        $result[] = $this->areasNameMigration;
        $result[] = $this->locCitiesNameMigration;
        $temp = explode(' ', $this->location_id);
        $result[] = array_pop($temp);
        $result = trim(implode(' ', $result));
        $result = str_replace('  ', ' ', $result);
        $result = explode(' ', $result);
        return $result;
    }


    public function getLocationForPrintString($delimiter = ' ', $part = false){
        $result = [];
        if (isset($this->koatuus)) {
            $arr = $this->koatuus->allAddressArray;
            if ($part) {
                if (isset($arr[2])) {
                    $result[] = $arr[2];
                }
            } else {
                if (isset($arr[0])) {
                    $result[] = $arr[0];
                }
                if (isset($arr[1])) {
                    $result[] = $arr[1];
                }
            }
        }
        return implode($delimiter, $result);
    }

	/**
	 * Setter for attribute location_id from string locationIdString
	 */
	public function setLocationIdString(){
		$shortNameList = LocationTypes::getCitiesShortType();
		$shortNameListString = implode("|", $shortNameList);
		$shortNameListString = str_replace(".",'\.', $shortNameListString);
		$re = '/^('.$shortNameListString.')\s/is';
		if(preg_match($re, trim($this->location_id), $matches)){
			//if isset location type in string
			if(isset($matches[1]) && isset($matches[0])){
				//get param for search in cities table
				$cityName = str_replace($matches[0],"",$this->location_id);
				$cityTypeId = array_search($matches[1], $shortNameList);
				$areasId = $this->areas_id;
				//create sql query
				$query = Cities::find()->select(["id"])->where(['city_name' => $cityName, 'type_id' => $cityTypeId, 'area_id' => $areasId]);
				/**
				 * @var $result Cities
				 */
				if($query->exists()){
					//if location id exist change value on his id
					$result = $query->one();
					$this->location_id = $result->id;
				}else{
					//if location don`t exist create it and set his id in location_id
					$result = new Cities();
					$result->area_id = $areasId;
					$result->type_id = $cityTypeId;
					$result->city_name = $cityName;
					if($result->save()){
						$this->location_id = $result->id;
					}
				}
			}
		}
	}

	/**
	 * Creates new user account. It generates password if it is not provided by user.
	 *
	 * @return bool
	 */
	public function create()
	{
		if ($this->getIsNewRecord() == false) {
			throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
		}

		$transaction = $this->getDb()->beginTransaction();

		try {
			$this->confirmed_at = time();
			$this->password = $this->password == null ? Password::generate(8) : $this->password;

			$this->trigger(self::BEFORE_CREATE);

			if (!$this->save()) {
				$transaction->rollBack();
				return false;
			}
			//set default role to admin access
			$userRole = \Yii::$app->authManager->getRole('Users');
			\Yii::$app->authManager->assign($userRole, $this->getId());

			//$this->mailer->sendWelcomeMessage($this, null, true);
			$this->trigger(self::AFTER_CREATE);

			$transaction->commit();

			return true;
		} catch (\Exception $e) {
			$transaction->rollBack();
			\Yii::warning($e->getMessage());
			return false;
		}
	}

	public function beforeSave($insert)
	{
        if($this->phone != 'admin'){
            $this->phone = preg_replace("/[^0-9]/", '', $this->phone);
        }
        if ($this->phone != $this->getOldAttribute('phone')) {
            $this->chat_id = null;
        }
		if (strlen($this->phone) && $this->phone{0} == '0') {
			$this->noPhone = 1;
		}
        if ($this->noPhone) {
            if ($this->id) {
                $this->phone = str_pad($this->id, 12, '0', STR_PAD_LEFT);
            } else {
                $result = \Yii::$app->db->createCommand('SHOW TABLE STATUS where name = \'user\'')->queryOne();
                $this->phone = str_pad($result["Auto_increment"], 12, '0', STR_PAD_LEFT);
            }
        }
		if($this->koatuu == ''){
			$this->koatuu = null;
		}
        $shortNameList = LocationTypes::getCitiesShortType();
        $shortNameListString = implode("|", $shortNameList);
        $shortNameListString = str_replace(".",'\.', $shortNameListString);
        $re = '/^('.$shortNameListString.')\s/is';
        if(preg_match($re, trim($this->location_id), $matches) && isset($matches[1]) && isset($matches[0])) {
            //get param for search in cities table
            $cityName = str_replace($matches[0],"",$this->location_id);
            $cityTypeId = array_search($matches[1], $shortNameList);
            $areasId = $this->areas_id;
            //create sql query
            $query = Cities::find()->select(["id"])->where(['city_name' => $cityName, 'type_id' => $cityTypeId, 'area_id' => $areasId]);
            /**
             * @var $result Cities
             */
            if($query->exists()){
                //if location id exist change value on his id
                $result = $query->one();
                $this->location_id = $result->id;
            }else{
                //if location don`t exist create it and set his id in location_id
                $result = new Cities();
                $result->area_id = $areasId;
                $result->type_id = $cityTypeId;
                $result->city_name = $cityName;
                if($result->save()){
                    $this->location_id = $result->id;
                }
            }
        }

		return parent::beforeSave($insert);
	}

    public function afterFind()
	{
        $cache = Yii::$app->cache;
        if (isset($_SERVER) && isset($_SERVER["SERVER_NAME"])) {
            $pre = $_SERVER["SERVER_NAME"];
        } else {
            $pre = '';
        }
		if(!isset($this->username) && isset($this->profile)){
            $key_FullShortName = $pre.'FullShortName_'.$this->id;
            $fullShortName = $cache->get($key_FullShortName);
            if($fullShortName === false) {
                $fullShortName = $this->getFullShortName();
                $cache->set($key_FullShortName, $fullShortName, 28800);
            }
			$this->username = $fullShortName;
		}
		if ($this->phone{0} == '0') {
            $this->noPhone = 1;
        }

		if(isset($this->location_id)){
            $key_location_id = $pre.'location_id_'.$this->location_id;
            $location_id = $cache->get($key_location_id);
            if($location_id === false) {
                $city = Cities::findOne($this->location_id);
                if($city){
                    $location_id = $city->getFullCityName();
                }
                $cache->set($key_location_id, $location_id, 28800);
            }
            $this->location_id = $location_id;
		}
		parent::afterFind ();
	}

	public function getUserName()
	{
		if(isset($this->username)){
			return $this->username;
		}elseif ( isset($this->profile)){
			return $this->getFullName();
		}else{
			return \Yii::t('user', 'Username not set');
		}

	}

	public function getFullName(){
		$fullName = '';
		if(isset($this->profile->last_name)){
			$fullName .= ' ';
			$fullName .= $this->profile->last_name;
		}
		if(isset($this->profile->name)){
			$fullName .= ' ';
			$fullName .= $this->profile->name;
		}
		if(isset($this->profile->middle_name)){
			$fullName .= ' ';
			$fullName .= $this->profile->middle_name;
		}
		return $fullName;
	}

	public function getFullShortName(){
		$fullName = '';
		if(isset($this->profile->last_name)){
			$fullName .= ' ';
			$fullName .= $this->profile->last_name;
		}
		if(isset($this->profile->name)){
			$fullName .= ' ';
			$fullName .= mb_substr($this->profile->name, 0, 1);
		}
		if(isset($this->profile->middle_name)){
			$fullName .= ' ';
			$fullName .= mb_substr($this->profile->middle_name, 0, 1);
		}
		return trim($fullName);
	}

	public function getAreas()
	{
		return $this->hasOne(Areas::className(), ['id' => 'areas_id']);
	}

	public function getAreasName(){
		if(isset($this->areas->area_name)){
            $result = $this->areas->area_name;
            if($this->areas->types) {
                if ($this->areas->types->type) {
                    $result = $this->areas->types->short_name .' '.$result;
                }else {
                    $result = $result .' '. $this->areas->types->short_name;
                }
            }
            return $result;
		}else{
			return null;
		}
	}

    public function getAreasNameMigration(){
        if(isset($this->areas->area_name)){
            return $this->areas->area_name;
        }else{
            return null;
        }
    }

	public function getRegion_id(){
		if(isset($this->areas) && isset($this->areas->id_region)){
			return $this->areas->id_region;
		}
	}

	public function getRegion(){
		return $this->hasOne(Regions::className(), ['id' => 'id_region'])->via('areas');
	}

    public function getKoatuus(){
        return $this->hasOne(Koatuu::className(), ['TE' => 'koatuu']);
    }

    public function getRegionKoatuu(){
    	return is_null($this->koatuu) ? null : str_pad(substr($this->koatuu, 0, 2),10, '0');
    }

    public function getAreaKoatuu(){
        return is_null($this->koatuu) ? null : str_pad(substr($this->koatuu, 0, 5),10, '0');
    }

	public function getRegionName(){
		if(isset($this->region->region_name)){
            $result = $this->region->region_name;
            if ($this->region->types->type) {
                $result = $this->region->types->short_name .' '.$result;
            }else {
                $result = $result .' '. $this->region->types->short_name;
            }
			return $result;
		}else{
			return NULL;
		}
	}

    public function getRegionNameMigration(){
        if(isset($this->region->region_name)){
            $result = $this->region->region_name;
            if ($this->region->types->type) {
                $result = $this->region->types->short_name .$result;
            }
            return $result;
        }else{
            return NULL;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocCities(){
        return $this->hasOne(Cities::className(), ['id' => 'loc_id']);
    }

    public function getLocCitiesName(){
        if(isset($this->locCities)){
            $result = $this->locCities->city_name;
            if ($this->locCities->types->type) {
                $result = $this->locCities->types->short_name .' '.$result;
            }else {
                $result = $result .' '. $this->locCities->types->short_name;
            }
            return $result;
        }else{
            return NULL;
        }
    }

    public function getLocCitiesNameMigration(){
        if(isset($this->locCities)){
            return $this->locCities->city_name;
        }else{
            return NULL;
        }
    }

	public function getRecruiter()
	{
		return $this->hasOne(self::className(), ['id' => 'recruiter_id']);
	}

	public function getAdded()
	{
		return $this->hasOne(self::className(), ['id' => 'added_id']);
	}

	public function getAddedName()
	{
        if(isset($this->added->username)){
            return $this->added->username;
        }else{
            return null;
        }
	}

	public function getRecruiterName()
	{
		if(isset($this->recruiter->username)){
			return $this->recruiter->username;
		}else{
			return null;
		}
	}

	public function getProfession()
	{
		return $this->hasOne(Profession::className(), ['id' => 'profession_id']);
	}

	public function getProfessionName(){
		if(isset($this->profession->name)){
			return $this->profession->name;
		}else{
			return null;
		}
	}

    public function getStreet()
    {
        return $this->hasOne(Street::className(), ['id' => 'street_id']);
    }

    public function getStreetName()
    {
        return isset($this->street) ? $this->street->name : '';
    }

    public function getStreetType()
    {
        return isset($this->street) ? $this->street->type_id : '';
    }

    public function setStreetType($id)
    {
        $this->streetType = $id;
    }

    public function setStreetName($name)
    {
        if (is_null($name)) {
            $this->street_id = $name;
        } else {
            $model = Street::findOne(['name' => trim($name), 'koatuu' => $this->koatuu, 'type_id' => $this->streetType]);
            if (!$model instanceof Street) {
                $model = new Street();
                $model->koatuu = $this->koatuu;
                $model->type_id = $this->streetType;
                $model->name = trim($name);
                $model->save();
            }
            $this->street_id = $model->id;
        }
    }

	public function getCommunicationTypes()
	{
		return $this->hasMany(CommunicationType::className(), ['id' => 'communication_type_id'])
			->viaTable('user_communication_type', ['user_id' => 'id']);
	}

	public function getInterests()
	{
		return $this->hasMany(Interests::className(), ['id' => 'interest_id'])
			->viaTable('user_interests', ['user_id' => 'id']);
	}

	public function getBadges()
	{
		return $this->hasMany(Badge::className(), ['id' => 'badge_id'])
			->viaTable('user_badge', ['user_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProfile()
	{
		return $this->hasOne(Profile::className(), ['user_id' => 'id']);
	}

	/**
	 * @inheritdoc
	 * TODO: add translation for new attribute
	 */
	public function attributeLabels()
	{
		$attribute = parent::attributeLabels();
		return ArrayHelper::merge($attribute, [
			'areas_id'      			=> \Yii::t('user', 'Arias'),
			'sex'      					=> \Yii::t('user', 'Sex'),
			'recruiter_id'      		=> \Yii::t('user', 'Recruiter'),
			'areasName'      			=> \Yii::t('user', 'Arias'),
			'regionName'      			=> \Yii::t('user', 'Region'),
			'region_id'      			=> \Yii::t('user', 'Region'),
			'professionName'      		=> \Yii::t('user', 'Profession'),
			'profession_id'    			=> \Yii::t('user', 'Profession'),
			'communication_type_ids'  	=> \Yii::t('user', 'Communication Type'),
			'badge_ids'  				=> \Yii::t('user', 'Badges'),
			'interest_ids'  			=> \Yii::t('user', 'Interests'),
			'phone'  					=> \Yii::t('user', 'Phone'),
			'address'  					=> \Yii::t('user', 'Address'),
			'region'  					=> \Yii::t('user', 'Region'),
			'middle_name'  				=> \Yii::t('user', 'Middle Name'),
			'last_name'  				=> \Yii::t('user', 'Last Name'),
			'recruiterName'  			=> \Yii::t('user', 'Recruiter Name'),
			'added_id'  			    => \Yii::t('user', 'Added Id'),
			'addedName'  			    => \Yii::t('user', 'Added Name'),
			'fullName'  			    => \Yii::t('user', 'Full Name'),
			'email'  			        => \Yii::t('user', 'email'),
            'apartment'      			=> \Yii::t('user', 'Apartment'),
            'build'      				=> \Yii::t('user', 'Build'),
            'korp'      				=> \Yii::t('user', 'Korp'),
		]);
	}

	/**
	 * @param $chat_id
	 *
	 * @return bool
	 */
	public static function chatIdExist($chat_id){
		$query = self::find()->where(['chat_id' => $chat_id])->andWhere('confirmed_at is not null');
		return $query->exists();
	}

    /**
     * @return array|self[]
     */
    public static function getAdmins() {
        $query = self::find()->select('id')->where(['username' => 'admin'])->asArray();
        $result = $query->all();
        if (!count($result)){
            $result = false;
        }
        return $result;
    }

    /**
     * @param $user_id
     * @param bool $all
     * @return false|[]
     */
    public static function getFollowers($user_id, $all=false)
    {
        if ($all) {
            $results = ArrayHelper::map(self::find()->select(['id', 'recruiter_id'])->asArray()->all(), 'id','id','recruiter_id');
            $result = self::getAllFollowers($results, $user_id);
        } else {
            $result = ArrayHelper::getColumn(self::find()->select('id')->where(['recruiter_id' => $user_id])->asArray()->all(), 'id');
        }
        if (!count($result)) {
            $result = false;
        }

        return $result;
    }

    protected static function getAllFollowers($all, $parentId = 0){
        $result = [];
        if(isset($all[$parentId])){
            foreach ($all[$parentId] as $key){
                $result[] = $key;
                $result = array_merge($result, self::getAllFollowers($all, $key));
            }
        }
        return $result;
    }

    /**
     * @param $chat_id
     *
     * @return self|bool
     */
    public static function getUserByChatId($chat_id){
        $query = self::find()->where(['chat_id' => $chat_id]);
        return $query->exists() ? $query->one() : false;

    }

    /**
     * @param $phone
     * @return array|bool|null|User
     */
    public static function getUserByPhone($phone){
		$query = self::find()->where(['phone' => $phone]);
        return $query->exists() ? $query->one() : false;
	}

    /**
     * @param $auth_key
     * @return self|bool
     */
    public static function findIdentityByAuthKey($auth_key){
		return self::findOne(['auth_key' => $auth_key]);
	}

	/**
	 * @param integer $chat_id
	 *
	 * @return bool|string
	 */
	public static function getAuthKeyByChatId($chat_id){
		$user = self::findOne(['chat_id' => $chat_id]);
        return (isset($user) && isset($user->auth_key)) ? $user->auth_key : false;
	}


	/**
	 * @param string $keyField
	 * @param string $valueField
	 * @param bool $asArray
	 * @param array $exceptIds
	 *
	 * @return array
	 */
	public static function listAll($keyField = 'id', $valueField = 'username', $asArray = false, $exceptIds = []){

		if(!\Yii::$app->user->isGuest){
			$currentUser = User::findOne(\Yii::$app->user->id);
			if(\Yii::$app->user->can(User::NO_FILTER)){

			}elseif(\Yii::$app->user->can(User::REGIONS_FILTER) && isset($currentUser->regionKoatuu)){
                $region_id = $currentUser->koatuu;
			}elseif(\Yii::$app->user->can(User::AREAS_FILTER) && isset($currentUser->areaKoatuu)){
                $areas_id = $currentUser->koatuu;
			}else{
				$recruiter_id	= $currentUser->id;
			}
		}

		$query = static::find();

		if(isset($recruiter_id)){
			$query->where(['recruiter_id' => $recruiter_id]);
		}elseif(isset($areas_id)){
            $query->andWhere("SUBSTRING(user.koatuu, 1,5) = SUBSTRING('$areas_id', 1,5)");
		}elseif(isset($region_id)){
            $query->andWhere("SUBSTRING(user.koatuu, 1,2) = SUBSTRING('$region_id', 1,2)");
		}

		if(count($exceptIds)){
			$query->andWhere(['not',[''.User::tableName().'.`id`'=>$exceptIds]]);
		}

		if($asArray){
			$query->select([$keyField, $valueField])->asArray();
		}

		return ArrayHelper::map($query->all(), $keyField, $valueField);
	}


	public static function listAllWithPhoneAddress($exceptIds = []){
        $cache = Yii::$app->cache;
        if (isset($_SERVER) && isset($_SERVER["SERVER_NAME"])) {
            $pre = $_SERVER["SERVER_NAME"];
        } else {
            $pre = '';
        }
        $key = $pre.'listAllWithPhoneAddress_';
        if(!\Yii::$app->user->isGuest){
            $currentUser = User::findOne(\Yii::$app->user->id);
            if(\Yii::$app->user->can(User::NO_FILTER)){

            }elseif(\Yii::$app->user->can(User::REGIONS_FILTER) && isset($currentUser->regionKoatuu)){
                $region_id = $currentUser->koatuu;
            }elseif(\Yii::$app->user->can(User::AREAS_FILTER) && isset($currentUser->areaKoatuu)){
                $areas_id = $currentUser->koatuu;
            }else{
                $recruiter_id	= $currentUser->id;
            }
        }
        $query = static::find();
        if(isset($recruiter_id)){
            $query->where(['recruiter_id' => $recruiter_id]);
            $key .= $recruiter_id;
        }elseif(isset($areas_id)){
            $query->andWhere("SUBSTRING(user.koatuu, 1,5) = SUBSTRING('$areas_id', 1,5)");
            $key .= $areas_id;
        }elseif(isset($region_id)){
            $query->andWhere("SUBSTRING(user.koatuu, 1,2) = SUBSTRING('$region_id', 1,2)");
            $key .= $region_id;
        }

		if(count($exceptIds)){
            $key .= implode(',', $exceptIds);
			$query->andWhere(['not',[''.User::tableName().'.`id`'=>$exceptIds]]);
		}
        $result = $cache->get($key);
        if($result === false) {
            $resultTmp = $query->all();
            /**
             * @var $resultTmp self[]
             */
            $result = [];
            foreach ($resultTmp as $user) {
                $result[$user->id] = "$user->fullName ( $user->phone ) " . (isset($user->koatuus) ? $user->koatuus->allAddressSting : "");
            }
            $cache->set($key, $result, 600);
        }
		return $result;
	}

	/**
	 * @param null $id
	 * @param array|null $onlyShow
	 *
	 * @return array
	 */
	public static function getUserTree($id = null, $onlyShow = null){
	    //TODO: добавить условия по юзверю
	    $allUser = User::find()
            ->select(['id', 'COALESCE(TRIM(CONCAT(profile.last_name, " ", profile.name, " ", profile.middle_name)), phone) as username', 'COALESCE(recruiter_id, 1) as recruiter_id'])
            ->innerJoin('profile', 'profile.user_id = user.id')->asArray()->all();
        $all = [];
        foreach($allUser as $user) {
            if ($user['id'] == $user['recruiter_id']) {
                $all[0][$user['id']] = $user['username'];
            } else {
                if ($onlyShow) {
                    if (in_array($user['id'], $onlyShow)) {
                        if(in_array($user['recruiter_id'],$onlyShow)){
                            $all[$user['recruiter_id']][$user['id']] = $user['username'];
                        } else {
                            $all[1][$user['id']] = $user['username'];
                        }
                    }
                } else {
                    $all[$user['recruiter_id']][$user['id']] = $user['username'];
                }
            }
        }
        if (!$id) {
            $id = \Yii::$app->user->id == 1 ? 0 : \Yii::$app->user->id;
        }
		return ['all' => $all, 'id' => $id];
	}

    public static function generateTree($all, $parentId = 0, $existIds = []){
        $results = [];
        if(isset($all[$parentId])){
            foreach ($all[$parentId] as $key=>$item){
                $children = [];
                if (!in_array($key, $existIds)) {
                    $existIds[] = $key;
                    $children = self::generateTree($all, $key, $existIds);
                }
                $result = [
                    'key' 		=> $key,
                    'title'     => Html::a($item,['/user/profile/show', 'id' => $key]),
                ];
				
                if ($children) {
                    $result['folder'] = true;
                    $result['children'] = $children;
                }
                $results[] = $result;
            }
        }
        return $results;
    }

	/**
	 * Костиль на переводи значения поля атрибута sex
	 * для плагина переводов
	 */
    public static function customTranslatesVar(){
	Yii::t('user', "male");
	Yii::t('user', "female");
	}
}
