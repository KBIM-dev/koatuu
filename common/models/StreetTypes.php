<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "street_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_name
 *
 * @property Street[] $streets
 */
class StreetTypes extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'street_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['short_name'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('streetTypes', 'ID'),
            'name' => Yii::t('streetTypes', 'Name'),
            'short_name' => Yii::t('streetTypes', 'Short Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStreets()
    {
        return $this->hasMany(Street::className(), ['type_id' => 'id']);
    }

    public static function listAll($keyField = 'id', $valueField = 'short_name')
    {
        $query = static::find()->select([$keyField, $valueField])->asArray();

        return ArrayHelper::map($query->all(), $keyField, $valueField);
    }
}
