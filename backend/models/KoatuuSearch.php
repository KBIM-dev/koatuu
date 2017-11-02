<?php

namespace backend\models;

use common\models\KoatuuView;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Koatuu;
use yii\db\Query;

/**
 * KoatuuSearch represents the model behind the search form about `common\models\Koatuu`.
 */
class KoatuuSearch extends Koatuu
{
	public $name;
	/*public $area_name;
	public $city_name;*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TE', 'NP', 'NU', 'name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 * @param string $type
	 *
	 * @param null $id
	 *
	 * @return ActiveDataProvider
	 */
    public function search($params, $type, $id = null)
    {
    	$query = false;
    	switch($type) {
			case self::KOATUU_REGIONS: {
				$query = Koatuu::find()->select("TE, NP, CASE WHEN LOCATE('/', `NU`) > 0
					THEN 
						LEFT(`NU`,  LOCATE('/', `NU`) - 1)
						 
					ELSE 
						NU 
				END as name")->where(['=', 'SUBSTRING(koatuu.TE, 3,8)', '00000000']);
				break;
			}
			case self::KOATUU_AREAS: {
				$query = Koatuu::find()->select("TE, NP, CASE WHEN LOCATE('/', `NU`) > 0 THEN LEFT(`NU`,  LOCATE('/', `NU`) - 1) ELSE NU END as name")
					->where("SUBSTRING(koatuu.TE, 1,2) = '" . substr($id, 0, 2) . "' and SUBSTRING(koatuu.TE, 6,5) = '00000' and SUBSTRING(koatuu.TE, 4,7) != '0000000'");
				break;
			}
			case self::KOATUU_CITIES: {
				$query = Koatuu::find()->select("TE, NP, CASE WHEN LOCATE('/', `NU`) > 0 THEN LEFT(`NU`,  LOCATE('/', `NU`) - 1) ELSE NU END as name")
					->where("SUBSTRING(koatuu.TE, 1,5) = '" . substr($id, 0, 5) . "' and SUBSTRING(koatuu.TE, 7,4) != '0000' AND TRIM(`NP`) <> ''");
				break;
			}
		}


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSizeLimit'     => 50,
			],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		$dataProvider->setSort([
			'attributes' => [
				'name',
			]
		]);

		$query->andFilterWhere(['like', 'TE', $this->TE])
			->andFilterWhere(['like', 'NP', $this->NP])
			->andFilterWhere(['like', 'NU', $this->name]);



        return $dataProvider;
    }
}
