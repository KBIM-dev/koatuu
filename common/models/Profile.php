<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace common\models;

use dektrium\user\traits\ModuleTrait;
use dektrium\user\models\Profile as BaseProfile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "profile".
 *
 * @property string  $dateOfBirthString
 * @property string  $date_of_birth
 * @property string  $middle_name
 * @property string  $last_name
 * @property  \common\models\User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class Profile extends BaseProfile
{
    use ModuleTrait;
    /** @var \dektrium\user\Module */
    protected $module;


    /**
     * @inheritdoc
     */
    public function rules()
    {
		$rules = parent::rules();
		return ArrayHelper::merge($rules, [
			[['last_name', 'middle_name', 'date_of_birth', 'dateOfBirthString'], 'string', 'max' => 255],
			[['last_name', 'name'], 'required'],

		]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
		$attribute = parent::attributeLabels();
		return ArrayHelper::merge($attribute, [
			'last_name'      => \Yii::t('user', 'Last Name'),
			'middle_name'    => \Yii::t('user', 'Middle Name'),
			'date_of_birth'  => \Yii::t('user', 'Date of birth'),
			'dateOfBirthString'  => \Yii::t('user', 'Date of birth'),
		]);
    }

	public function getUser()
	{
		return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
	}

    public function afterSave($insert, $changedAttributes)
    {
        if($this->user->username != $this->user->getFullShortName()) {
            $this->user->username = $this->user->getFullShortName();
            $this->user->save();
        }

        return parent::afterSave($insert, $changedAttributes);
    }

	public function getDateOfBirthString(){
        return !empty($this->date_of_birth) ? date('d.m.Y', strtotime($this->date_of_birth)) : null;

	}

	public function setDateOfBirthString($data){
		if(!empty($data)){
			$this->date_of_birth = date('Y-m-d', strtotime($data));
		}else{
			$this->date_of_birth = null;
		}
	}
}
