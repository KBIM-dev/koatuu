<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property string $city_name
 * @property integer $type_id
 * @property integer $area_id
 *
 * @property Areas $area
 * @property LocationTypes $types
 */
class Cities extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_name', 'type_id', 'area_id'], 'required'],
            [['type_id', 'area_id'], 'integer'],
            [['city_name'], 'string', 'max' => 255],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Areas::className(), 'targetAttribute' => ['area_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationTypes::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_name' => Yii::t('cities', 'City Name'),
            'typeName'  => Yii::t('cities', 'City Type'),
            'areaName'  => Yii::t('cities', 'Area'),
            'type_id' 	=> Yii::t('cities', 'Type'),
            'area_id' 	=> Yii::t('cities', 'Area'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Areas::className(), ['id' => 'area_id']);
    }

    public function getAreaName()
    {
        return $this->area_id ? $this->area->area_name : false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasOne(LocationTypes::className(), ['id' => 'type_id']);
    }

    public function getTypeName(){

        return $this->type_id ? $this->types->name : false;
    }

    /**
     * @param $area_id
     * @param bool $onlyName
     * @return array
     */
    public static function getCityList($area_id, $onlyName = false)
    {
        $result = [];
        if($area_id) {
            $result = self::find()
                ->select([
                    '`cities`.`id`',
                    'CASE WHEN `location_types`.`type` = 0 THEN CONCAT(`cities`.`city_name`, \' \', `location_types`.`short_name`) ELSE CONCAT(`location_types`.`short_name`, \' \', `cities`.`city_name`) END as name'
                ])
                ->where(['area_id' => $area_id])
                ->leftJoin('location_types', 'location_types.id = cities.type_id')
                ->orderBy([
                	'name' => SORT_ASC,
				])
                ->asArray()
                ->all();
            if ($onlyName) {
                $result = ArrayHelper::getColumn($result, 'name');
            } else {
                $result = ArrayHelper::map($result, 'id', 'name');
            }
        }
        return $result;
    }

	public function getFullCityName(){
		$name = "";
		if(isset($this->types)){
			$name .= $this->types->short_name;
		}
		if(isset($this->city_name)){
			$name .= " $this->city_name";
		}
		return $name;
	}
}
