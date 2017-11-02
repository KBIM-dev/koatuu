<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "location_types".
 *
 * @property integer $id
 * @property string $class
 * @property integer $type
 * @property string $name
 * @property string $short_name
 *
 * @property Cities[] $cities
 * @property Regions[] $regions
 */
class LocationTypes extends ActiveRecord
{

	const TYPE_CITY = 1;
	const CLASS_CITY = 'cities';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'short_name', 'class'], 'required'],
            [['class'], 'string'],
            [['type'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['short_name'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        Yii::t('location_types', 'cities');
        Yii::t('location_types', 'areas');
        Yii::t('location_types', 'regions');

        return [
            'id' => Yii::t('location_types', 'ID'),
            'class' => Yii::t('location_types', 'Type'),
            'type' => Yii::t('location_types', 'Is City'),
            'name' => Yii::t('location_types', 'Name'),
            'short_name' => Yii::t('location_types', 'Short Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(Cities::className(), ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasMany(Regions::className(), ['type_id' => 'id']);
    }

	public static function listAll($class)
	{
		Yii::t('location-types', 'cities');
		Yii::t('location-types', 'regions');
		Yii::t('location-types', 'areas');
		Yii::t('location-type', 'cities');
		Yii::t('location-type', 'regions');
		Yii::t('location-type', 'areas');
		$class_type = $class;
		return ArrayHelper::map(static::find()->
        select([
            'id',
            'name',
            'CASE WHEN `type` = 1 THEN \''.Yii::t('location-types', 'Settlements of {class} importance', ['class'=>Yii::t('location-types',$class_type)]).'\' ELSE \''.Yii::t('location-type',$class_type).'\' END as type'
        ])->asArray()->where(['class' => $class])->orderBy(['type' => SORT_ASC])->all(), 'id', 'name', 'type');
	}

	/**
	 * @return array
	 */
	public static function getCitiesShortType(){
		return ArrayHelper::map(self::find()->select(['id', 'short_name'])->where(['type' => self::TYPE_CITY, 'class'=>self::CLASS_CITY])->asArray()->all(), 'id', 'short_name');
	}
}
