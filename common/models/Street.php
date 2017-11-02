<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "street".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 * @property string $koatuu
 * @property string $koatuuString
 * @property string $streetType
 *
 * @property Koatuu $koatuus
 * @property StreetTypes $type
 * @property User[] $users
 */
class Street extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'street';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type_id', 'koatuu'], 'required'],
            [['type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['name', 'trim'],
            [['koatuu'], 'string', 'max' => 10],
            [['koatuu', 'type_id', 'name'], 'unique', 'targetAttribute' => ['koatuu', 'type_id', 'name'], 'message' => Yii::t('models','The combination of Name, Type ID and Koatuu has already been taken.')],
            [['koatuu'], 'exist', 'skipOnError' => true, 'targetClass' => Koatuu::className(), 'targetAttribute' => ['koatuu' => 'TE']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => StreetTypes::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('street', 'ID'),
            'name' => Yii::t('street', 'Name'),
            'streetType' => Yii::t('street', 'StreetType '),
            'type_id' => Yii::t('street', 'Type'),
            'koatuu' => Yii::t('street', 'Koatuu'),
            'koatuuString' => Yii::t('street', 'City Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKoatuus()
    {
        return $this->hasOne(Koatuu::className(), ['TE' => 'koatuu']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(StreetTypes::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['street_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getStreetType()
    {
        return $this->type->short_name;
    }

    /**
     * @return string
     */
    public function getKoatuuString()
    {
        return $this->koatuus->allAddressSting;
    }

    /**
     * @param null $koatuu
     * @param null $type_id
     * @param string $keyField
     * @param string $valueField
     * @return array
     */
    public static function listAll($koatuu = null, $type_id = null, $keyField = 'id', $valueField = 'name')
    {
        $query = static::find()->select([$keyField, $valueField])->asArray();
        if (!is_null($koatuu)) {
            $query->andWhere(['koatuu' => $koatuu]);
        }
        if (!is_null($type_id)) {
            $query->andWhere(['type_id' => $type_id]);
        }

        return ArrayHelper::map($query->all(), $keyField, $valueField);
    }

	public function getRegion(){
		return is_null($this->koatuu) ? null : str_pad(substr($this->koatuu, 0, 2),10, '0');
	}

	/**
	 * @return null|string
	 */
	public function getArea(){
		return is_null($this->koatuu) ? null : str_pad(substr($this->koatuu, 0, 5),10, '0');
	}

	public function getCity() {
		return $this->koatuu == $this->area ? '' : $this->koatuu;
	}

}
