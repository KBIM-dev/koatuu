<?php
	namespace common\models;

	use common\models\User;
	use common\models\Profile;
	use dektrium\user\traits\ModuleTrait;
	use yii\base\Model;

	class RegistrationForm extends Model
	{
		use ModuleTrait;
		/**
		 * Add a new field
		 * @var string
		 */
		public $name;
		public $sex;
		public $middle_name;
		public $last_name;
		public $date_of_birth;
        public $regionKoatuu;
        public $areaKoatuu;
        public $cityKoatuu;
		public $phone;
		public $noPhone;
		public $address;
		public $profession_id;
		public $potential_id;
		public $recruiter_id;
        public $streetName;
        public $streetType;
        public $build;
        public $apartment;
		public $korp;
		public $chat_id;
		public $email;
		public $password;
		public $confirm_password;
		public $communication_type_ids;
		public $interest_ids;
		public $koatuu;

		/**
		 * @inheritdoc
		 */
		public function rules()
		{
			$user = $this->module->modelMap[ 'User' ];

			$rules[] = [['name', 'middle_name', 'last_name', 'address', 'build', 'apartment', 'korp', 'streetName'], 'trim'];
			$rules = [ // email rules
				'emailTrim' => [ 'email', 'filter', 'filter' => 'trim' ],
				//'emailRequired' => [ 'email', 'required' ],
				'emailPattern' => [ 'email', 'email' ],
				'emailUnique' => [ 'email', 'unique', 'targetClass' => $user, 'message' => \Yii::t('user', 'This email address has already been taken') ], // password rules
				'passwordRequired' => [ 'password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword ], 'passwordLength' => [ 'password', 'string', 'min' => 6, 'max' => 72 ], ];
			$rules[] = [ 'phone', 'validatePhone'];
			$rules[] = [ 'noPhone', 'integer'];
			$rules[] = [ 'sex', 'string'];
			$rules[] = [ 'sex','validateSex', 'skipOnEmpty' => true];
			$rules[] = [ 'confirm_password', 'required' ];
			$rules[] = [ 'confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => \Yii::t('validMessage', "Passwords don't match"), ];
			$rules[] =  [['last_name', 'middle_name', 'name'], 'string', 'min' => 3, 'max' => 50 ];
			$rules[] =  [['last_name', 'middle_name', 'name'], 'match', 'pattern' => '/^[А-яіІїЄєЇёЁ\'\-\s]+$/u', 'message' => \Yii::t('user','Incorrect format')];
			//$rules = parent::rules();
			$rules[] = [ [ 'name', 'middle_name', 'last_name', 'koatuu', 'profession_id', 'date_of_birth', 'communication_type_ids', 'phone', 'sex', 'streetName', 'koatuu', 'regionKoatuu', 'areaKoatuu', 'build'], 'required' ];
			$rules[] = [ [ 'chat_id', 'streetType' ], 'integer' ];
			$rules[] = [ [ 'interest_ids' ], 'each', 'rule' => ['integer']];
			$rules[] = [ [ 'communication_type_ids' ], 'safe' ];
			$rules[] = [ [ 'streetName', 'name', 'middle_name', 'last_name', 'date_of_birth', 'date_of_birth', 'phone', 'address' ], 'string', 'max' => 255 ];
			$rules[] = [ [ 'build' ], 'string', 'max' => 8 ];
			$rules[] = ['streetName', 'match', 'pattern' =>  '/^[А-яіІїЄєЇ\'\-0-9\s]+$/u', 'message' => \Yii::t('user','Incorrect format streetName')];
            $rules[] = ['build', 'match', 'pattern' => '/(^(\d+\/\d+)$)|(^(\d+)$)|(^(\d+[а-я])$)|(^(\d+\/\d+[а-я])$)|(^(\d+[а-я]\/\d+)$)|(^(\d+[а-я]\/\d+[а-я])$)/u', 'message' => \Yii::t('user','Incorrect format build')];
            $rules[] = [ [ 'koatuu', 'regionKoatuu', 'areaKoatuu' ], 'string', 'max' => 10 ];
            $rules[] = [ 'cityKoatuu', 'validateCityKoatuu', 'skipOnEmpty' => false ];
			$rules[] = [ [ 'apartment' ], 'string', 'max' => 4 ];
			$rules[] = [ [ 'korp' ], 'string', 'max' => 2 ];
			$rules[] = [ [ 'profession_id' ], 'exist', 'skipOnError' => true, 'targetClass' => Profession::className(), 'targetAttribute' => [ 'profession_id' => 'id' ] ];
			$rules[] = [ [ 'recruiter_id' ], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => [ 'recruiter_id' => 'id' ] ];

			return $rules;
		}

		/**
		 * @inheritdoc
		 */
		public function attributeLabels()
		{
			return [ 'name' => \Yii::t('registration', 'Name'),
				'middle_name' => \Yii::t('registration', 'Middle Name'),
				'sex' => \Yii::t('registration', 'Sex'),
				'last_name' => \Yii::t('registration', 'Last Name'),
				'date_of_birth' => \Yii::t('registration', 'Date of birth'),
				'phone' => \Yii::t('registration', 'Phone'),
				'noPhone' => \Yii::t('registration', 'No Phone'),
				'address' => \Yii::t('registration', 'Address'),
				'potential_id' => \Yii::t('registration', 'Potential'),
				'profession_id' => \Yii::t('registration', 'Profession'),
				'recruiter_id' => \Yii::t('registration', 'Recruiter'),
				'communication_type_ids' => \Yii::t('registration', 'Communication Types'),
				'interest_ids' => \Yii::t('registration', 'Interests'),
				'confirm_password' => \Yii::t('registration', 'Confirm Password'),
				'password' => \Yii::t('registration', 'Password'), 'email' => \Yii::t('registration', 'Email'), ];

		}

		public function validateSex($attribute, $params)
		{
			if (!in_array($this->$attribute, ['male', 'female'])) {
				$this->addError($attribute, 'Пол должен быть либо Мужской или Женский.');
			}
		}

		public function validatePhone()
		{
			$user = User::find()->where(['phone' => preg_replace("/[^0-9]/", '', $this->phone)]);

			if ($user->exists()) {
				$this->addError('phone', \Yii::t('registration', 'This phone number has already ben taken'));
			}else{
				return true;
			}
		}

		/**
		 * @inheritdoc
		 */
		public function formName()
		{
			return 'register-form';
		}

        public function validateCityKoatuu($attribute, $params)
        {
            if (empty($this->$attribute) && !empty($this->areaKoatuu)) {
                $error = Koatuu::find()
                    ->where("SUBSTRING(koatuu.TE, 1,5) = '" . substr($this->koatuu, 0, 5) . "' and SUBSTRING(koatuu.TE, 7,4) != '0000' AND TRIM(`NP`) <> ''")->exists();
                if ($error) {
                    $this->addError($attribute, \Yii::t('city','city cannot be blank.'));
                }
            }
        }

		/**
		 * Registers a new user account. If registration was successful it will set flash message.
		 *
		 * @return bool
		 */
		public function register()
		{
			if(!$this->validate()) {
				return false;
			}

			/** @var User $user */
			/** @var Profile $profile */
			$user = \Yii::createObject(User::className());
			$user->setScenario('register');
			$this->loadAttributes($user);
			if(!$user->save()) {
				return false;
			} else {
				$userRole = \Yii::$app->authManager->getRole('Users');
				\Yii::$app->authManager->assign($userRole, $user->getId());
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
				'email' 	=> $this->email,
				'sex' 		=> $this->sex,
                'build' 		=> $this->build,
                'apartment' 	=> $this->apartment,
                'korp' 			=> $this->korp,
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
			$profile = \Yii::createObject(Profile::className());
			$profile->setAttributes([
				'name' 			=> $this->name,
				'middle_name' 	=> $this->middle_name,
				'last_name' 	=> $this->last_name,
				'date_of_birth' => date('Y-m-d', strtotime($this->date_of_birth)),
			]);
			$user->setProfile($profile);
		}
	}