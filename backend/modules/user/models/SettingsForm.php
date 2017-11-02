<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace backend\modules\user\models;

use common\models\Areas;
use common\models\Koatuu;
use common\models\LocationTypes;
use dektrium\user\helpers\Password;
use dektrium\user\Mailer;
use dektrium\user\models\Token;
use dektrium\user\Module;
use dektrium\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property \common\models\User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsForm extends Model
{
    use ModuleTrait;

    /** @var string */
    public $email;
    public $region;
    public $regionName;
    public $area;
    public $areaName;
    public $city;
    public $regionKoatuu;
    public $areaKoatuu;
    public $cityKoatuu;
    public $koatuu;
    public $streetName;
    public $streetType;
    public $build;
    public $apartment;
    public $korp;
    public $address;
    public $sex;
    public $phone;
    public $profession_id;
    public $communication_type_ids;
    public $interest_ids;

	public $last_name;
	public $name;
	public $middle_name;
	public $dateOfBirthString;

    /** @var string */
    public $username;

    /** @var string */
    public $new_password;

    /** @var string */
    public $current_password;

    /** @var Mailer */
    protected $mailer;

    /** @var User */
    private $_user;

    /** @return User */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }

    /** @inheritdoc */
    public function __construct(Mailer $mailer, $config = [])
    {
        $this->mailer = $mailer;
        $this->setAttributes([
			'dateOfBirthString' 	=> isset($this->user->profile->dateOfBirthString) ? $this->user->profile->dateOfBirthString : null,
			'middle_name' 			=> isset($this->user->profile->middle_name) ? $this->user->profile->middle_name : null,
			'last_name' 			=> isset($this->user->profile->last_name) ? $this->user->profile->last_name : null,
            'name' 					=> isset($this->user->profile->name) ? $this->user->profile->name : null,
            'sex' 					=> $this->user->sex,
            'interest_ids' 			=> $this->user->interest_ids,
            'username' 				=> $this->user->username,
            'koatuu' 				=> $this->user->koatuu,
            'regionKoatuu' 			=> $this->user->regionKoatuu,
            'areaKoatuu' 			=> $this->user->areaKoatuu,
            'cityKoatuu' 			=> $this->user->koatuu == $this->user->areaKoatuu ? '' : $this->user->koatuu,
            'address' 				=> $this->user->address,
            'streetName'			=> $this->user->streetName,
            'streetType'			=> $this->user->streetType,
            'build'					=> $this->user->build,
            'apartment'				=> $this->user->apartment,
            'korp'					=> $this->user->korp,
            'phone' 				=> $this->user->phone,
            'profession_id' 		=> $this->user->profession_id,
            'communication_type_ids'=> $this->user->communication_type_ids,
            'email'    				=> $this->user->unconfirmed_email ?: $this->user->email,
        ], false);
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [

        	[['name', 'middle_name', 'last_name', 'address', 'build', 'apartment', 'korp', 'streetName'], 'trim'],
        	[['address', 'phone', 'communication_type_ids', 'interest_ids', 'streetType'], 'safe'],
        	[['sex', 'koatuu', 'last_name', 'name', 'middle_name', 'dateOfBirthString', 'streetName', 'regionKoatuu', 'areaKoatuu', 'cityKoatuu'], 'string'],
        	[['profession_id', ], 'integer'],
        	[['phone', 'communication_type_ids', 'name', 'last_name', 'middle_name', 'sex', 'regionKoatuu', 'areaKoatuu', 'streetName', 'build', 'koatuu', 'dateOfBirthString'], 'required'],
            [['korp'], 'string', 'max' => 2 ],
            ['cityKoatuu', 'validateCityKoatuu', 'skipOnEmpty' => false],
            [['apartment'], 'string', 'max' => 4 ],
            [['build'], 'string', 'max' => 8 ],
			[['last_name', 'middle_name', 'name'], 'string', 'min' => 3, 'max' => 50 ],
		    [['last_name', 'middle_name', 'name'], 'match', 'pattern' => '/^[А-яіІїЄєЇёЁ\'\-\s]+$/u', 'message' => \Yii::t('user','Incorrect format')],
			['streetName', 'match', 'pattern' =>  '/^[А-яіІїЄєЇ\'\-0-9\s]+$/u', 'message' => \Yii::t('user','Incorrect format streetName')],
            ['build', 'match', 'pattern' => '/(^(\d+\/\d+)$)|(^(\d+)$)|(^(\d+[а-я])$)|(^(\d+\/\d+[а-я])$)|(^(\d+[а-я]\/\d+)$)|(^(\d+[а-я]\/\d+[а-я])$)/u', 'message' => Yii::t('user','Incorrect format build')],
            //'usernameRequired' => ['username', 'required'],
            'usernameTrim' => ['username', 'filter', 'filter' => 'trim'],
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 50],
            //'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailPattern' => ['email', 'email'],
            'emailUsernameUnique' => [['email', 'username'], 'unique', 'when' => function ($model, $attribute) {
                return $this->user->$attribute != $model->$attribute;
            }, 'targetClass' => $this->module->modelMap['User']],
            'newPasswordLength' => ['new_password', 'string', 'max' => 72, 'min' => 6],
			//TODO: uncommit if need enter confirm password before save your profile setting
            //'currentPasswordRequired' => ['current_password', 'required'],
            'currentPasswordValidate' => ['current_password', function ($attr) {
                if (!Password::validate($this->$attr, $this->user->password_hash)) {
                    $this->addError($attr, Yii::t('user', 'Current password is not valid'));
                }
            }],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'dateOfBirthString' => Yii::t('user', 'Date of birth'),
            'middle_name'      	=> Yii::t('user', 'Middle Name'),
            'last_name'      	=> Yii::t('user', 'Last Name'),
            'name'      		=> Yii::t('user', 'Name'),
            'email'            	=> Yii::t('user', 'Email'),
            'sex'              	=> Yii::t('user', 'Sex'),
            'phone'            	=> Yii::t('user', 'Phone'),
            'username'         	=> Yii::t('user', 'Username'),
            'new_password'     	=> Yii::t('user', 'New password'),
            'current_password' 	=> Yii::t('user', 'Current password'),
            'apartment' 		=> Yii::t('user', 'Apartment'),
            'build' 			=> Yii::t('user', 'Build'),
			'korp' 				=> Yii::t('user', 'Korp'),
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'settings-form';
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
     */
    public function validateLocationId($attribute){
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

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $this->user->scenario 			= 'settings';
            $this->user->username 			= $this->username;
			$this->user->profile->dateOfBirthString 	= $this->dateOfBirthString;
			$this->user->profile->middle_name 			= $this->middle_name;
			$this->user->profile->last_name 			= $this->last_name;
			$this->user->profile->name 		= $this->name;
            $this->user->sex 				= $this->sex;
            $this->user->koatuu 		    = $this->koatuu;
            $this->user->address 			= $this->address;
            $this->user->phone 				= $this->phone;
            $this->user->profession_id 		= $this->profession_id;
            $this->user->interest_ids 		= $this->interest_ids;
            $this->user->communication_type_ids = $this->communication_type_ids;
            $this->user->password 	= $this->new_password;
            $this->user->build 		= $this->build;
            $this->user->apartment 	= $this->apartment;
            $this->user->korp 		= $this->korp;
            $this->user->setStreetType($this->streetType);
            $this->user->setStreetName($this->streetName);
            if ($this->email == $this->user->email && $this->user->unconfirmed_email != null) {
                $this->user->unconfirmed_email = null;
            } elseif ($this->email != $this->user->email) {
                switch ($this->module->emailChangeStrategy) {
                    case Module::STRATEGY_INSECURE:
                        $this->insecureEmailChange();
                        break;
                    case Module::STRATEGY_DEFAULT:
                        $this->defaultEmailChange();
                        break;
                    case Module::STRATEGY_SECURE:
                        $this->secureEmailChange();
                        break;
                    default:
                        throw new \OutOfBoundsException('Invalid email changing strategy');
                }
            }
            return $this->user->save() && $this->user->profile->save();
        }

        return false;
    }

    /**
     * Changes user's email address to given without any confirmation.
     */
    protected function insecureEmailChange()
    {
        $this->user->email = $this->email;
        Yii::$app->session->setFlash('success', Yii::t('user', 'Your email address has been changed'));
    }

    /**
     * Sends a confirmation message to user's email address with link to confirm changing of email.
     */
    protected function defaultEmailChange()
    {
        $this->user->unconfirmed_email = $this->email;
        /** @var Token $token */
        $token = Yii::createObject([
            'class'   => Token::className(),
            'user_id' => $this->user->id,
            'type'    => Token::TYPE_CONFIRM_NEW_EMAIL,
        ]);
        $token->save(false);
        $this->mailer->sendReconfirmationMessage($this->user, $token);
        Yii::$app->session->setFlash(
            'info',
            Yii::t('user', 'A confirmation message has been sent to your new email address')
        );
    }

    /**
     * Sends a confirmation message to both old and new email addresses with link to confirm changing of email.
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function secureEmailChange()
    {
        $this->defaultEmailChange();
        /** @var Token $token */
        $token = Yii::createObject([
            'class'   => Token::className(),
            'user_id' => $this->user->id,
            'type'    => Token::TYPE_CONFIRM_OLD_EMAIL,
        ]);
        $token->save(false);
        $this->mailer->sendReconfirmationMessage($this->user, $token);

        // unset flags if they exist
        $this->user->flags &= ~User::NEW_EMAIL_CONFIRMED;
        $this->user->flags &= ~User::OLD_EMAIL_CONFIRMED;
        $this->user->save(false);

        Yii::$app->session->setFlash(
            'info',
            Yii::t(
                'user',
                'We have sent confirmation links to both old and new email addresses. You must click both links to complete your request'
            )
        );
    }
}
