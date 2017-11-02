<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "communication_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property User[] $users
 */
class CommunicationType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'communication_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('communication_type', 'ID'),
            'name' => Yii::t('communication_type', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
		return $this->hasMany(User::className(), ['id' => 'user_id'])
			->viaTable('user_communication_type', ['communication_type_id' => 'id']);
    }

	public static function listAll($keyField = 'id', $valueField = 'name', $asArray = true)
	{
		$query = static::find();
		if ($asArray) {
			$query->select([$keyField, $valueField])->asArray();
		}

		return ArrayHelper::map($query->all(), $keyField, $valueField);
	}
}
