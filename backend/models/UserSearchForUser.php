<?php

namespace backend\models;

use common\models\User;
use dektrium\user\Finder;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearchForUser extends User
{
    /** @var string */
    public $username;
    public $region;
    public $regionName;
    public $area;
    public $areaName;

    /** @var string */
    public $email;

    /** @var string */
    public $registration_ip;

    /** @var Finder */
    protected $finder;

    /**
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct(Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'fieldsSafe' => [['username', 'email', 'registration_ip', 'region', 'regionName', 'area', 'areaName'], 'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'username'        => Yii::t('user', 'Username'),
            'email'           => Yii::t('user', 'Email'),
            'registration_ip' => Yii::t('user', 'Registration IP'),
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = $this->finder->getUserQuery()->joinWith(['profile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->validate())) {
            return $dataProvider;
        }
        $this->load($params);

		//implement filters for region manager, areas manager
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

		$query->andFilterWhere([
			'id' 				=> $this->id,
			'updated_at' 		=> $this->updated_at,
		]);
        if (!empty($this->region)) {
            $query->andWhere("SUBSTRING(user.koatuu, 1,2) = SUBSTRING('$this->region', 1,2)");
        }
        if (!empty($this->area)) {
            $query->andWhere("SUBSTRING(user.koatuu, 1,5) = SUBSTRING('$this->area', 1,5)");
        }

		//don`t show current account in search result
		if(!Yii::$app->user->isGuest){
			$query->andWhere(['not', ['`user`.`id`' => Yii::$app->user->id]]);
		}

		if(!empty($this->username)){
			$this->username = trim($this->username);
			$t_username = explode(' ',$this->username);
			if(is_array($t_username) && count($t_username) >=2 ){
				$sq = '';
				if(isset($t_username[1])){

					if(iconv_strlen(trim($t_username[1])) == 1){
						$sq .= ' SUBSTRING( `profile`.`name`, 1, 1)   LIKE "' . $t_username[1] . '" ';
					}else{
						$sq .= ' `profile`.`name` LIKE "%' . $t_username[1] . '%" ';
					}
				}
				if(isset($t_username[2])){
					if(!empty($sq)){
						$sq .= ' AND ';
					}
					if(iconv_strlen(trim($t_username[2])) == 1){
						$sq .= ' SUBSTRING( `profile`.`middle_name`, 1, 1)   LIKE "' . $t_username[2] . '" ';
					}else{
						$sq .= ' `profile`.`middle_name` LIKE "%' . $t_username[2] . '%" ';
					}
				}

				if(isset($t_username[0])){
					if(!empty($sq)){
						$sq .= ' AND ';
					}
					if(iconv_strlen(trim($t_username[0])) == 1){
						$sq .= ' SUBSTRING( `profile`.`last_name`, 1, 1)   LIKE "' . $t_username[0] . '" ';
					}else{
						$sq .= ' `profile`.`last_name` LIKE "%' . $t_username[0] . '%" ';
					}
				}

				if(!empty($sq)){
					$query->andWhere($sq);
				}
			}else{
				$query->andWhere(' `profile`.`name` LIKE "%' . $this->username . '%" '
					.' OR `profile`.`last_name` LIKE "%' . $this->username . '%"'
					.' OR `profile`.`middle_name` LIKE "%' . $this->username . '%"'
					.' OR `user`.`username` LIKE "%' . $this->username . '%"'
				);
			}

		}

		$query->andFilterWhere(['like', 'email', trim($this->email)])
            ->andFilterWhere(['like','registration_ip', trim($this->registration_ip)]);

        return $dataProvider;
    }
}