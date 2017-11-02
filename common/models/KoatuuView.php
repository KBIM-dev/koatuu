<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "koatuu_view".
 *
 * @property string $TE
 * @property string $area_name
 * @property string $region_name
 */
class KoatuuView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'koatuu_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TE'], 'required'],
            [['area_name', 'region_name'], 'string'],
            [['TE'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TE' => Yii::t('interests', 'Te'),
            'area_name' => Yii::t('interests', 'Area Name'),
            'region_name' => Yii::t('interests', 'Region Name'),
        ];
    }
}
