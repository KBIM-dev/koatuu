<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "areas".
 *
 * @property integer $id
 * @property integer $id_region
 * @property string $area_name
 * @property integer $type_id
 *
 * @property Regions $idRegion
 * @property LocationTypes $types
 */
class Areas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'areas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_region', 'type_id'], 'integer'],
			[['type_id', 'id_region', 'area_name'], 'required'],
            [['area_name'], 'string', 'max' => 50],
            [['id_region'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['id_region' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_region' => Yii::t('app', 'Id Region'),
            'type_id' => Yii::t('app', 'Type'),
            'area_name' => Yii::t('app', 'Area Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRegion()
    {
        return $this->hasOne(Regions::className(), ['id' => 'id_region']);
    }

    public function getRegionName(){
        return (isset($this->idRegion) && isset($this->idRegion->region_name)) ? $this->idRegion->region_name : '';
    }

    public function getTypes() {
		return $this->hasOne(LocationTypes::className(), ['id' => 'type_id']);
	}


	public static function listAll($keyField = 'id', $valueField = 'area_name')
	{
		$query = static::find()->select([$keyField, $valueField])->asArray();

		return ArrayHelper::map($query->all(), $keyField, $valueField);
	}

    /**
     * @param $region_id
     * @return array
     */
    public static function getAreasList($region_id)
    {
        $result = [];
        if($region_id) {
            $result = ArrayHelper::map(self::find()
                ->select([
                    '`areas`.`id`',
                    'CASE WHEN `location_types`.`type` = 0 THEN \'' . \Yii::t('areas', 'Areas') . '\' ELSE \'' . \Yii::t('asd', 'Cities regional value') . '\' END as types',
                    'CASE WHEN `location_types`.`type` = 0 THEN CONCAT(`areas`.`area_name`, \' \', `location_types`.`short_name`) ELSE CONCAT(`location_types`.`short_name`, \' \', `areas`.`area_name`) END as name'
                ])
                ->leftJoin('location_types', 'location_types.id = areas.type_id')
                ->where(['areas.id_region' => $region_id])
                ->orderBy([
                    '`location_types`.`type`' => SORT_DESC,
                    'area_name' => SORT_ASC
                ])
                ->asArray()
                ->all(),
                'id', 'name', 'types');

        }
        return $result;
    }
}
