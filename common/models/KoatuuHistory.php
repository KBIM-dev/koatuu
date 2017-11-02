<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "koatuu_history".
 *
 * @property integer $id
 * @property integer $old_koatuu
 * @property integer $new_koatuu
 * @property string $old_location_name
 * @property string $new_location_name
 * @property string $design_VRU
 * @property integer $time
 */
class KoatuuHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'koatuu_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_koatuu', 'new_koatuu', 'time'], 'integer'],
            [['old_location_name', 'new_location_name', 'design_VRU'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('koatuuHistory', 'ID'),
            'old_koatuu' => Yii::t('koatuuHistory', 'Old Koatuu'),
            'new_koatuu' => Yii::t('koatuuHistory', 'New Koatuu'),
            'old_location_name' => Yii::t('koatuuHistory', 'Old Location Name'),
            'new_location_name' => Yii::t('koatuuHistory', 'New Location Name'),
            'design_VRU' => Yii::t('koatuuHistory', 'Design  Vru'),
            'time' => Yii::t('koatuuHistory', 'Time'),
        ];
    }
}
