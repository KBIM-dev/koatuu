<?php

namespace backend\models;

use common\models\Areas;
use common\models\Profession;
use common\models\Regions;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 * @property string from_reg_date
 * @property string to_reg_date
 * @property string last_name
 * @property string middle_name
 * @property integer events_id
 * @property integer is_parent
 * @property integer by_in_team
 * @property integer is_participant
 * @property integer in_events
 * @property integer is_in_team
 */
class UserSearch extends User
{
	public $potentialName;
	public $professionName;
	public $middle_name;
	public $last_name;
	public $streetType;
    public $region;
    public $regionName;
    public $area;
    public $areaName;
    public $city;
    //for Events
    public $is_in_team;
    public $in_events;
    public $is_participant;
    public $by_in_team;
    public $is_parent;
    public $events_id;
    public $from_reg_date;
    public $to_reg_date;
    public $interest;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['username', 'middle_name', 'last_name', 'address', 'build', 'apartment', 'korp', 'streetName', 'email'], 'trim'],
            [
            	['id', 'created_at',
				'updated_at', 'potentialName', 'region_id',
				'professionName', 'areas_id',
					'recruiter_id', 'profession_id', 'region_id', 'locs_id', 'added_id'], 'integer'],
			[['username', 'middle_name', 'last_name', 'address', 'live_locality_name', 'region', 'area','areasName', 'regionName', 'city'],'string'],
            ['build', 'match', 'pattern' => '/(^(\d+\/\d+)$)|(^(\d+)$)|(^(\d+[а-я])$)|(^(\d+\/\d+[а-я])$)|(^(\d+[а-я]\/\d+)$)|(^(\d+[а-я]\/\d+[а-я])$)/u', 'message' => Yii::t('user','Incorrect format build')],
			[['email'],'email'],
            [['korp'], 'string', 'max' => 2 ],
            [['apartment'], 'string', 'max' => 4 ],
            [['is_in_team', 'is_participant', 'by_in_team', 'is_parent', 'events_id','in_events', 'sex', 'streetType', 'streetName'], 'safe'],
            [[ 'auth_key', 'password_hash', 'phone', 'interest'], 'safe'],
            ['from_reg_date', 'filter', 'filter' => function ($value) {
                if(!empty($value)){
                    $value = strtotime($value, time());
                }
                return $value;
            }],
            ['to_reg_date', 'filter', 'filter' => function ($value) {
                if(!empty($value)){
                    $value = strtotime($value, time());
                }
                return $value;
            }],
        ];
    }
	public function attributes()
	{
		return array_merge(
			parent::attributes(),
			[ 'interest', 'region_id', 'last_name', 'middle_name', 'locs_id', 'is_in_team', 'is_participant', 'by_in_team', 'is_parent', 'events_id', 'in_events', 'from_reg_date', 'to_reg_date', 'region', 'area','city', 'regionName', 'areaName', 'streetType', 'streetName']
        );
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

	public function setRegion_id($region_id){
		$this->region_id = $region_id;
	}

	public function attributeLabels()
	{
		$attribute = parent::attributeLabels();
		return ArrayHelper::merge($attribute, [
			'interest'   		=> \Yii::t('user', 'Interests'),
			'is_in_team'   		=> \Yii::t('event', 'Is In Team'),
			'is_participant'   	=> \Yii::t('event', 'Is Participant'),
			'by_in_team'   	=> \Yii::t('event', 'By In Team'),
			'is_parent'   	=> \Yii::t('event', 'Is Parent'),
			'events_id'   	=> \Yii::t('event', 'Events Id'),
			'in_events'   	=> \Yii::t('event', 'Just In This Event'),
		]);
	}

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = User::find()->joinWith(['profession', 'areas', 'region', 'profile']);

        // add conditions that should always apply here

		if(!Yii::$app->user->isGuest){
			$currentUser = User::findOne(Yii::$app->user->id);
			if(Yii::$app->user->can(User::NO_FILTER)){

			}elseif(Yii::$app->user->can(User::REGIONS_FILTER) && isset($currentUser->regionKoatuu)){

				$this->region =  $currentUser->regionKoatuu;
				$ar = $currentUser->koatuus->getAllAddressArray();
                $this->regionName = array_shift($ar);

			}elseif(Yii::$app->user->can(User::AREAS_FILTER) && isset($currentUser->areaKoatuu)){

                $ar = $currentUser->koatuus->getAllAddressArray();
				$this->region =  $currentUser->regionKoatuu;
                $this->regionName = array_shift($ar);
				$this->area = $currentUser->areaKoatuu;
				$this->areaName = array_shift($ar);

			}else{
				$this->recruiter_id	= $currentUser->id;
			}
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
				'pageParam' 		=> 'user-page',
				'pageSizeParam'     => 'per-page',
                'pageSizeLimit'     => [3, 5000],
            ],
        ]);


        $this->load($params);

        if (!$this->validate()) {

            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		/**
		 * Настройка параметров сортировки
		 * Важно: должна быть выполнена раньше $this->load($params)
		 */
		$dataProvider->setSort([
			'attributes' => [
				'id',
				'professionName' => [
					'asc' 	=> 	[Profession::tableName().'.name' => SORT_ASC],
					'desc' 	=> 	[Profession::tableName().'.name' => SORT_DESC],
				],
				'areasName' => [
					'asc' 	=> 	[Areas::tableName().'.area_name' => SORT_ASC],
					'desc' 	=> 	[Areas::tableName().'.area_name' => SORT_DESC],
					],
				'regionName' => [
					'asc' 	=> 	[Regions::tableName().'.region_name' => SORT_ASC],
					'desc' 	=> 	[Regions::tableName().'.region_name' => SORT_DESC],
				],
				'country_id'
			]
		]);

        // grid filtering conditions
        $query->andFilterWhere([
            'user.id' 				=> $this->id,
            'created_at' 		=> $this->created_at,
            'updated_at' 		=> $this->updated_at,
            'recruiter_id' 		=> $this->recruiter_id,
            'email' 			=> $this->email,
            'phone' 			=> preg_replace("/[^0-9]/", '', $this->phone),
            'profession_id' 	=> $this->profession_id,
            'areas_id' 			=> $this->areas_id,
            'sex' 				=> $this->sex,
            'apartment' 		=> $this->apartment,
            'korp' 				=> $this->korp,
        ]);

		if (!empty($this->interest)) {
			$query->leftJoin('user_interests', '`user_interests`.`user_id` = `user`.`id`');
			$query->andWhere(['user_interests.interest_id' => $this->interest]);
		}

        // For Event
        if (!empty($this->is_in_team) || !empty($this->is_participant) || !empty($this->in_events)) {
            $query->leftJoin('events_user', '`events_user`.`user_id` = `user`.`id`');
            $query->andWhere(['events_user.events_id' => $this->events_id]);
        }
        if(!empty($this->is_in_team)){
            $query->andWhere("`events_user`.`is_crewman` = $this->is_in_team");
        }
        if(!empty($this->is_participant)){
            $query->andWhere("`events_user`.`participant` = $this->is_participant");
        }
        if(!empty($this->by_in_team)){
            $query->andWhere("`recruiter_id` = $this->by_in_team");
        }
        if(!empty($this->is_parent)){
            $query->andWhere("(SELECT COUNT(`userp`.`id`) FROM `user` AS userp WHERE `user`.`id` = `userp`.`recruiter_id`) > 0");
        }

        if (!empty($this->region)) {
            $query->andWhere("SUBSTRING(user.koatuu, 1,2) = SUBSTRING('$this->region', 1,2)");
        }
        if (!empty($this->area)) {
            $query->andWhere("SUBSTRING(user.koatuu, 1,5) = SUBSTRING('$this->area', 1,5)");
        }
        if (!empty($this->city)) {
            $query->andWhere("user.koatuu = $this->city");
        }
        if (!empty($this->added_id)) {
            $query->andWhere("user.added_id = $this->added_id");
        }

        if (!empty($this->streetName)) {
            $query->andWhere("user.street_id = $this->streetName");
        }


        // End For Event

		if(!empty($this->address)){
			$query->andWhere(' `address` LIKE "%' . addslashes($this->address) . '%"');
		}

		//don`t show current account in search result
		if(!Yii::$app->user->isGuest){
			$query->andWhere(['not', ['`user`.`id`' => Yii::$app->user->id]]);
		}

		if(!empty($this->username)){
			$query->andWhere('`profile`.`name` LIKE "%' . addslashes($this->username) . '%" '
				.' OR `user`.`username` LIKE "%' . addslashes($this->username) . '%"');
		}
		if(!empty($this->last_name)){
			$query->andWhere(' `last_name` LIKE "%' . addslashes($this->last_name) . '%"');
		}
		if(!empty($this->middle_name)){
			$query->andWhere(' `middle_name` LIKE "%' . addslashes($this->middle_name) . '%"');
		}
		if(!empty($this->region_id)){
			$query->andFilterWhere(['=', '`regions`.`id`', $this->region_id]);
		}

        if(!empty($this->from_reg_date)){
            $query->andFilterWhere(['>=', 'created_at', $this->from_reg_date]);
        }
        if(!empty($this->to_reg_date)){
            $query->andFilterWhere(['<=', 'created_at', $this->to_reg_date]);
        }

		if(!empty($this->build)){
			$query->andFilterWhere(['like', 'build', $this->build]);
		}

        return $dataProvider;
    }

    public function getTim($ids){
		$query = User::find()->where(['id'=>$ids]);

		// add conditions that should always apply here
		if(!Yii::$app->user->isGuest){
			$currentUser = User::findOne(Yii::$app->user->id);
			if(Yii::$app->user->can(User::NO_FILTER)){

			}elseif(Yii::$app->user->can(User::REGIONS_FILTER) && isset($currentUser->region_id)){

				$this->region_id =  $currentUser->region_id;

			}elseif(Yii::$app->user->can(User::AREAS_FILTER) && isset($currentUser->areas_id)){

				$this->region_id =  $currentUser->region_id;
				$this->areas_id = $currentUser->areas_id;

			}else{
				$this->recruiter_id	= $currentUser->id;
			}
		}

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' 			=> 5,
				'pageParam' 		=> 'tim-page',
				'pageSizeParam'     => 'tim-per-page',
				'pageSizeLimit'     => [5, 500],
			],
		]);

		$dataProvider->setSort([
			'attributes' => [
				'id',
				'professionName' => [
					'asc' 	=> 	[Profession::tableName().'.name' => SORT_ASC],
					'desc' 	=> 	[Profession::tableName().'.name' => SORT_DESC],
				],
				'areasName' => [
					'asc' 	=> 	[Areas::tableName().'.area_name' => SORT_ASC],
					'desc' 	=> 	[Areas::tableName().'.area_name' => SORT_DESC],
				],
				'regionName' => [
					'asc' 	=> 	[Regions::tableName().'.region_name' => SORT_ASC],
					'desc' 	=> 	[Regions::tableName().'.region_name' => SORT_DESC],
				],
				'country_id'
			]
		]);

		return $dataProvider;
	}

	public function getParticipants($event_id, $user_id){
		$query = User::find()->joinWith(['eventsUser'])->where(['recruiter_id' => $user_id]);

		// add conditions that should always apply here
		if(!Yii::$app->user->isGuest){
			$currentUser = User::findOne(Yii::$app->user->id);
			if(Yii::$app->user->can(User::NO_FILTER)){

			}elseif(Yii::$app->user->can(User::REGIONS_FILTER) && isset($currentUser->region_id)){

				$this->region_id =  $currentUser->region_id;

			}elseif(Yii::$app->user->can(User::AREAS_FILTER) && isset($currentUser->areas_id)){

				$this->region_id =  $currentUser->region_id;
				$this->areas_id = $currentUser->areas_id;

			}else{
				$this->recruiter_id	= $currentUser->id;
			}
		}

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageParam' 		=> 'user-participant-page',
				'pageSizeParam'     => 'per-page',
				'pageSizeLimit'     => [3, 5000],
			],
		]);


		if (!$this->validate()) {

			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		/**
		 * Настройка параметров сортировки
		 * Важно: должна быть выполнена раньше $this->load($params)
		 */
		$dataProvider->setSort([
			'attributes' => [
				'id',
				'professionName' => [
					'asc' 	=> 	[Profession::tableName().'.name' => SORT_ASC],
					'desc' 	=> 	[Profession::tableName().'.name' => SORT_DESC],
				],
				'areasName' => [
					'asc' 	=> 	[Areas::tableName().'.area_name' => SORT_ASC],
					'desc' 	=> 	[Areas::tableName().'.area_name' => SORT_DESC],
				],
				'regionName' => [
					'asc' 	=> 	[Regions::tableName().'.region_name' => SORT_ASC],
					'desc' 	=> 	[Regions::tableName().'.region_name' => SORT_DESC],
				],
				'country_id',
			]
		]);

		// grid filtering conditions
		$query->andWhere([
			'events_id'  => $event_id,
			'participant' => 1
		]);

		if(!empty($this->live_locality_name)){
			$query->andWhere(' `live_locality_name` LIKE "%' . addslashes($this->live_locality_name) . '%"');
		}

		if(!empty($this->locs_id)){
			$query->andWhere("`loc_id` = $this->locs_id OR `location_id` = $this->locs_id");
		}

		if(!empty($this->address)){
			$query->andWhere(' `address` LIKE "%' . addslashes($this->address) . '%"');
		}

		//don`t show current account in search result
		if(!Yii::$app->user->isGuest){
			$query->andWhere(['not', ['`user`.`id`' => Yii::$app->user->id]]);
		}

		return $dataProvider;
	}
}
