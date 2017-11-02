<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "badge".
 *
 * @property integer $id
 * @property string $img
 * @property string $name
 * @property string $description
 *
 */
class Badge extends \yii\db\ActiveRecord
{

	public $imageFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'badge';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['description'], 'string'],
            [['img', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('badge', 'ID'),
            'img' => Yii::t('badge', 'Img'),
            'imageFile' => Yii::t('badge', 'Img'),
            'name' => Yii::t('badge', 'Name'),
            'description' => Yii::t('badge', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
		return $this->hasMany(User::className(), ['id' => 'user_id'])
			->viaTable('user_badge', ['badge_id' => 'id']);
    }

	public static function listAll($keyField = 'id', $valueField = 'name', $asArray = true)
	{
		$query = static::find();
		if ($asArray) {
			$query->select([$keyField, $valueField])->asArray();
		}

		return ArrayHelper::map($query->all(), $keyField, $valueField);
	}

	/**
	 * @param $url = /upload/time()_badge.[png|jpeg]
	 *
	 * @return bool
	 */
	public function removeImage($url){
		$catalog = Yii::getAlias('@frontend/web');
		$file = $catalog.$url;
		if(file_exists($catalog.$url)){
			return unlink($file);
		}
		return false;
	}

}
