<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_interests".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $interest_id
 *
 * @property Interests $interest
 * @property User $user
 */
class UserInterests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_interests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'interest_id'], 'required'],
            [['user_id', 'interest_id'], 'integer'],
            [['interest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Interests::className(), 'targetAttribute' => ['interest_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('interests', 'ID'),
            'user_id' => Yii::t('interests', 'User ID'),
            'interest_id' => Yii::t('interests', 'Interest ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInterest()
    {
        return $this->hasOne(Interests::className(), ['id' => 'interest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
