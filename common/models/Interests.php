<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "interests".
 *
 * @property integer $id
 * @property string $name
 *
 * @property UserInterests[] $userInterests
 */
class Interests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('interests', 'ID'),
            'name' => Yii::t('interests', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInterests()
    {
        return $this->hasMany(UserInterests::className(), ['interest_id' => 'id']);
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
