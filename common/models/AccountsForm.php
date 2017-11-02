<?php
	namespace common\models;

	use common\models\Profile;
	use common\models\User;
	use dektrium\user\traits\ModuleTrait;
	use yii\base\Model;

	class AccountsForm extends Model
	{
		use ModuleTrait;
		/**
		 * Add a new field
		 * @var string
		 */
		public $user_id;
		public $added_id;
		public $regionKoatuu;
		public $areaKoatuu;
        public $cityKoatuu;
        public $koatuu;
		public $badge_ids;
		public $name;
		public $sex;
		public $middle_name;
		public $last_name;
		public $date_of_birth;
		public $dateOfBirthString;
		public $phone;
		public $region;
		public $noPhone;
		public $address;
		public $profession_id;
		public $recruiter_id;
		public $streetName;
		public $streetType;
		public $build;
		public $apartment;
		public $korp;
		public $chat_id;
		public $email;
		public $password;
		public $communication_type_ids;
		public $interest_ids;

		public function __construct($id = null)
		{

			if($id) {
				$user =  User::findOne($id);
				$userProfile = Profile::findOne($id);

				$this->setAttributes([
					'user_id'					=> $user->id,
					'added_id'					=> $user->added_id,
					'regionKoatuu'				=> $user->regionKoatuu,
					'areaKoatuu'				=> $user->areaKoatuu,
                    'cityKoatuu' 			    => $user->koatuu == $user->areaKoatuu ? '' : $user->koatuu,
					'name'						=> $userProfile->name,
					'middle_name'				=> $userProfile->middle_name,
					'last_name'					=> $userProfile->last_name,
					'date_of_birth'				=> $userProfile->date_of_birth,
					'dateOfBirthString'			=> $userProfile->dateOfBirthString,
					'email' 					=> $user->email,
					'sex' 						=> $user->sex,
					'password' 					=> $user->password,
					'communication_type_ids' 	=> $user->communication_type_ids,
					'interest_ids' 				=> $user->interest_ids,
					'phone' 					=> $user->phone,
					'chat_id' 					=> $user->chat_id,
					'address' 					=> $user->address,
					'koatuu' 					=> $user->koatuu,
					'profession_id' 			=> $user->profession_id,
					'recruiter_id' 				=> $user->recruiter_id,
					'region'					=> $user->regionName,
					'noPhone'					=> $user->noPhone,
					'streetName'				=> $user->streetName,
					'streetType'				=> $user->streetType,
					'build'						=> $user->build,
					'apartment'					=> $user->apartment,
					'korp'						=> $user->korp,
				]);
			} else {

			}
			parent::__construct();
		}

		/**
		 * @inheritdoc
		 */
		public function rules()
		{
			$user = $this->module->modelMap[ 'User' ];

			$rules = [ // email rules
				'emailTrim' => [ 'email', 'filter', 'filter' => 'trim' ],
				//'emailRequired' => [ 'email', 'required' ],
				'emailPattern' => [ 'email', 'email' ],
				'emailUnique' => [ 'email', 'validateEmail']]; // password rules
			$rules[] = [ 'phone', 'validatePhone'];
			$rules[] = [ 'phone', 'required', 'when' => function($model){ return  empty($model->noPhone); }];
			$rules[] = [ ['noPhone', 'user_id', 'added_id' ], 'integer'];
			$rules[] = [ [ 'sex', 'region',], 'string'];
			$rules[] = [ 'sex','validateSex', 'skipOnEmpty' => true];
			$rules[] = [ 'password', 'string', 'min' => 6, 'max' => 72, ];
			$rules[] = [ 'password', 'required', 'when' => function($model){return empty($model->user_id);} ];

			$rules[] = [['name', 'middle_name', 'last_name', 'address', 'build', 'apartment', 'korp', 'streetName'], 'trim'];
			$rules[] =  [['last_name', 'middle_name', 'name'], 'string', 'min' => 3, 'max' => 50 ];
			$rules[] =  [['last_name', 'middle_name', 'name'], 'match', 'pattern' => '/^[А-яіІїЄєЇёЁ\'\-\s]+$/u', 'message' => \Yii::t('user','Incorrect format')];

			//$rules = parent::rules();
			$rules[] = [ [ 'name', 'middle_name', 'last_name', 'koatuu', 'communication_type_ids', 'sex', 'dateOfBirthString', 'regionKoatuu', 'streetName', 'build', 'areaKoatuu', 'streetType'], 'required' ];
			$rules[] = [ [ 'chat_id', 'streetType'], 'integer' ];
			$rules[] = [ [ 'interest_ids', 'badge_ids', 'communication_type_ids' ], 'each', 'rule' => ['integer']];
			$rules[] = [ [ 'streetName', 'name', 'middle_name', 'last_name', 'date_of_birth', 'phone', 'address' ], 'string', 'max' => 255 ];
			$rules[] = ['streetName', 'match', 'pattern' => '/^[А-яіІїЄєЇ\'\-0-9\s]+$/u', 'message' => \Yii::t('user','Incorrect format streetName')];
			$rules[] = [ [ 'build' ], 'string', 'max' => 8 ];
            $rules[] = ['build', 'match', 'pattern' => '/(^(\d+\/\d+)$)|(^(\d+)$)|(^(\d+[а-я])$)|(^(\d+\/\d+[а-я])$)|(^(\d+[а-я]\/\d+)$)|(^(\d+[а-я]\/\d+[а-я])$)/u', 'message' => \Yii::t('user','Incorrect format build')];
			$rules[] = [ [ 'dateOfBirthString'], 'string'];
			$rules[] = [ [ 'koatuu', 'regionKoatuu', 'areaKoatuu' ], 'string', 'max' => 10 ];
            $rules[] = [ 'cityKoatuu', 'validateCityKoatuu', 'skipOnEmpty' => false ];
            $rules[] = [ 'region', 'validateRegion' , 'skipOnEmpty' => true];
			$rules[] = [ [ 'apartment' ], 'string', 'max' => 4 ];
			$rules[] = [ [ 'korp' ], 'string', 'max' => 2 ];
			$rules[] = [ [ 'profession_id' ], 'exist', 'skipOnEmpty' => true, 'skipOnError' => true, 'targetClass' => Profession::className(), 'targetAttribute' => [ 'profession_id' => 'id' ] ];
			$rules[] = [ [ 'recruiter_id' ], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => [ 'recruiter_id' => 'id' ] ];

			return $rules;
		}

		/**
		 * @inheritdoc
		 */
		public function attributeLabels()
		{
			return [
				'name' 				=> \Yii::t('registration', 'Name'),
				'build' 			=> \Yii::t('registration', 'Build'),
				'apartment' 		=> \Yii::t('registration', 'Apartment'),
				'korp' 				=> \Yii::t('registration', 'Korp'),
				'middle_name' 		=> \Yii::t('registration', 'Middle Name'),
				'sex' 				=> \Yii::t('registration', 'Sex'),
				'last_name' 		=> \Yii::t('registration', 'Last Name'),
				'date_of_birth' 	=> \Yii::t('registration', 'Date of birth'),
				'streetName' 	    => \Yii::t('registration', 'Street Name'),
				'dateOfBirthString' => \Yii::t('registration', 'Date of birth'),
				'phone' 			=> \Yii::t('registration', 'Phone'),
				'noPhone' 			=> \Yii::t('registration', 'No Phone'),
				'added_id' 			=> \Yii::t('registration', 'Added by'),
				'address' 			=> \Yii::t('registration', 'Address'),
				'potential_id' 		=> \Yii::t('registration', 'Potential'),
				'profession_id' 	=> \Yii::t('registration', 'Profession'),
				'recruiter_id' 		=> \Yii::t('registration', 'Recruiter'),
				'interest_ids' 		=> \Yii::t('registration', 'Interests'),
				'password' 			=> \Yii::t('registration', 'Password'), 'email' => \Yii::t('registration', 'Email'), ];

		}

		public function validateSex($attribute)
		{
			if (!in_array($this->$attribute, ['male', 'female'])) {
				$this->addError($attribute, \Yii::t('user', 'Пол должен быть либо Мужской либо Женский.'));
			}
		}

		public function validateEmail($attribute)
		{
			$user = User::find()->where(['email' => $this->$attribute]);

			if ($user->exists()) {
				if(isset($this->user_id) && $user->one()->id != $this->user_id){
					$this->addError($attribute, \Yii::t('user', 'This email address has already been taken'));
				}elseif(!isset($this->user_id)){
					$this->addError($attribute, \Yii::t('user', 'This email address has already been taken'));
				}else{
					return true;
				}
			}else{
				return true;
			}
		}

        public function validateCityKoatuu($attribute, $params)
        {
            if (empty($this->$attribute) && !empty($this->areaKoatuu)) {
                $notError = Koatuu::find()
                    ->where("SUBSTRING(koatuu.TE, 1,5) = '" . substr($this->koatuu, 0, 5) . "' and SUBSTRING(koatuu.TE, 7,4) != '0000' AND TRIM(`NP`) = 'Р'")->exists();
                $error = Koatuu::find()
                    ->where("SUBSTRING(koatuu.TE, 1,5) = '" . substr($this->koatuu, 0, 5) . "' and SUBSTRING(koatuu.TE, 7,4) != '0000' AND TRIM(`NP`) <> ''")->exists();
                if ($error && $notError) {
                    $this->addError($attribute, \Yii::t('city','city cannot be blank.'));
                }
            }
        }

		public function validatePhone($attribute)
		{
			$user = User::find()->where(['phone' => preg_replace("/[^0-9]/", '', $this->$attribute)]);

			if ($user->exists()) {
				if(isset($this->user_id) && $user->one()->id != $this->user_id){
					$this->addError($attribute, \Yii::t('registration', 'This phone number has already ben taken'));
				}elseif(!isset($this->user_id)){
					$this->addError($attribute, \Yii::t('registration', 'This phone number has already ben taken'));
				}else{
					return true;
				}
			}else{
				return true;
			}
		}

		public function validateRegion($attribute)
		{
			if (empty($this->$attribute)) {
                $this->addError($attribute, \Yii::t('registration', 'The address is required'));
            }else{
                return true;
            }
		}

		/**
		 * @inheritdoc
		 */
		public function formName()
		{
			return 'accounts-form';
		}

		/**
		 * Registers a new user account. If registration was successful it will set flash message.
		 *
		 * @return bool
		 */
		public function save()
		{
			if(!$this->validate()) {
				return false;
			}

			/** @var User $user */
			/** @var Profile $profile */

			if(!empty($this->user_id)){
				$user =  User::findOne($this->user_id);
				$user->scenario = 'update';
			}else{
				$user = \Yii::createObject(User::className());
				$user->scenario = 'create';
			}
			$this->loadAttributes($user);
			if(!$user->save()) {
				if(!empty($user->errors)){
					$this->errors = $user->errors;
				}
				return false;
			} else {
				$this->user_id = $user->id;
				if(!isset($this->user_id)){
					$userRole = \Yii::$app->authManager->getRole('Users');
					\Yii::$app->authManager->assign($userRole, $user->getId());
				}
			}

			return true;
		}

		/**
		 * @inheritdoc
		 */
		public function loadAttributes(User $user)
		{
			// here is the magic happens
			$user->setAttributes([
				'added_id' 		=> $this->added_id,
				'email' 		=> $this->email,
				'sex' 			=> $this->sex,
				'build' 		=> $this->build,
				'apartment' 	=> $this->apartment,
				'korp' 			=> $this->korp,
				'noPhone' 		=> $this->noPhone,
				'password' 					=> $this->password,
				'communication_type_ids' 	=> $this->communication_type_ids,
				'interest_ids' 				=> $this->interest_ids,
				'phone' 	=> preg_replace("/[^0-9]/", '', $this->phone),
				'chat_id' 	=> $this->chat_id,
				'address' 	=> $this->address,
				'koatuu' 	=> $this->koatuu,
				'profession_id' 	=> $this->profession_id,
				'recruiter_id' => $this->recruiter_id,
			]);
            $user->setStreetType($this->streetType);
            $user->setStreetName($this->streetName);
			/** @var Profile $profile */
			if(!empty($user->profile) && $user->profile instanceof Profile){
				$profile = $user->profile;
				$profile->setAttributes([
					'name' 			=> $this->name,
					'middle_name' 	=> $this->middle_name,
					'last_name' 	=> $this->last_name,
					'date_of_birth' => date('Y-m-d', strtotime($this->dateOfBirthString)),
				]);
				$profile->save();
			}else{
				$profile = \Yii::createObject(Profile::className());
				$profile->setAttributes([
					'name' 			=> $this->name,
					'middle_name' 	=> $this->middle_name,
					'last_name' 	=> $this->last_name,
					'date_of_birth' => date('Y-m-d', strtotime($this->dateOfBirthString)),
				]);

				$user->setProfile($profile);
			}
		}
	}