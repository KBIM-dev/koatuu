<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "regions".
 *
 * @property integer $id
 * @property string $region_name
 *
 * @property Areas[] $areas
 * @property LocationTypes $types
 */
class Regions extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['type_id'], 'integer'],
			[['type_id', 'region_name'], 'required'],
            [['region_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_id' => Yii::t('app', 'Type'),
            'region_name' => Yii::t('app', 'Region Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Areas::className(), ['id_region' => 'id']);
    }

	public static function getRegionNameBy($id){
		$region = self::findOne($id);
		if(isset($region->region_name)){
			return $region->region_name;
		}else{
			return Yii::t('region', 'Region not found');
		}
	}

    public function getTypes() {
        return $this->hasOne(LocationTypes::className(), ['id' => 'type_id']);
    }

    public static function listAll($keyField = 'id', $valueField = 'region_name')
    {
        $query = static::find()->select([$keyField, $valueField])->asArray();

        return ArrayHelper::map($query->all(), $keyField, $valueField);
    }

	public static function getRegionList(){
		return ArrayHelper::map(self::find()
			->select([
				'regions.id',
				'CASE WHEN `location_types`.`type` = 0 THEN \'' . \Yii::t('regions', 'Regions') . '\' ELSE \'' . \Yii::t('regions', 'Cities') . '\' END as types',
				'CASE WHEN `location_types`.`type` = 0 THEN CONCAT(`regions`.`region_name`, \' \', `location_types`.`short_name`) ELSE CONCAT(`location_types`.`short_name`, \' \', `regions`.`region_name`) END as name'
			])
			->leftJoin('location_types', 'location_types.id = regions.type_id')
			->orderBy([
                '`location_types`.`type`' => SORT_DESC,
				'name' => SORT_ASC
			])
			->asArray()
			->all(), 'id', 'name', 'types');
	}
}
