<?php
namespace backend\controllers;

use common\models\Cities;
use common\models\Koatuu;
use common\models\Regions;
use common\models\RegistrationForm;
use common\models\Street;
use common\models\User;
use dektrium\user\traits\AjaxValidationTrait;
use dektrium\user\traits\EventTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
	use AjaxValidationTrait;
	use EventTrait;
    /**
     * Event is triggered after creating RegistrationForm class.
     * Triggered with \dektrium\user\events\FormEvent.
     */
    const EVENT_BEFORE_REGISTER = 'beforeRegister';

    /**
     * Event is triggered after successful registration.
     * Triggered with \dektrium\user\events\FormEvent.
     */
    const EVENT_AFTER_REGISTER = 'afterRegister';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['get-areas-by-region', 'get-cities-by-areas', 'registration', 'login', 'error',
							'get-regions', 'get-cities-by-areas-for-type-head', 'get-locations', 'get-streets', 'get-var-dump2'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'get-var-dump'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'get-locations' => ['post'],
                    'get-streets' => ['post']
                ],
            ],
        ];
    }

    public function actionError()
    {
        if(Yii::$app->user->isGuest) {
            return Yii::$app->getResponse()->redirect('/login')->send();
        } else {
            if (Yii::$app->request->pathInfo == 'login') {
                if (Yii::$app->user->can('adminAccess')) {
                    return Yii::$app->response->redirect(Url::to(['user/admin/index']))->send();
                } else {
                    return Yii::$app->response->redirect(Url::to(['profile/index']))->send();
                }
            }
            $exception = Yii::$app->errorHandler->exception;
            if ($exception !== null) {
                $name = $exception->statusCode . ' ' .$exception->getMessage();
                return $this->render('error', ['exception' => $exception, 'name' => $name]);
            }
        }
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegistration($mdId = null)
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->can('adminAccess')) {
                Yii::$app->response->redirect(Url::to(['user/admin/index']))->send();
            } else {
                Yii::$app->response->redirect(Url::to(['profile/index']))->send();
            }
        }
        $recruiter_id = null;
		$this->layout = '/registration-layout';
        /** @var RegistrationForm $model */
        $model = \Yii::createObject(RegistrationForm::className());
        $event = $this->getFormEvent($model);
        $this->performAjaxValidation($model);
        if ($mdId) {
            $recruiter = $this->findModelUserByMd5Id($mdId);
            if ($recruiter) {
                $recruiter_id = $recruiter->id;
            } else {
                $recruiter_id = null;
            }
        }
        $this->trigger(self::EVENT_BEFORE_REGISTER, $event);

        if (\Yii::$app->request->isPost && !\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post()) && $model->register()) {
            $this->trigger(self::EVENT_AFTER_REGISTER, $event);

            Yii::$app->session->setFlash('info', \Yii::t('user', 'Your account has been created'));

            $this->redirect(['/login']);
        }
        return $this->render('registration', [
            'model' => $model,
            'recruiter_id' => $recruiter_id
        ]);
    }

	/**
	 * @return string
	 */
    public function actionGetRegions() {
		if(isset(Yii::$app->request->post()['region_id'])) {
			$region_id = Yii::$app->request->post()['region_id'];
			$result = [];
			//TODO:: замінить назви табличок на Table::tablename()
			$resultResponse = ArrayHelper::map(Regions::find()
			->select([
				'`regions`.`id`',
				'CASE WHEN `location_types`.`type` = 0 THEN \'' . Yii::t('regions', 'Areas') . '\' ELSE \'' . Yii::t('regions', 'Cities') . '\' END as types',
				'CASE WHEN `location_types`.`type` = 0 THEN CONCAT(`regions`.`region_name`, \' \', `location_types`.`short_name`) ELSE CONCAT(`location_types`.`short_name`, \' \', `regions`.`region_name`) END as name'
			])
			->leftJoin('location_types', 'location_types.id = regions.type_id')
			->orderBy([
				'name' => SORT_ASC
			])
			->asArray()
			->all(), 'id', 'name', 'types');
			if(is_array($resultResponse) && count($resultResponse) > 0) {
				$result = $resultResponse;
			}
			return json_encode($result);
		} else {
			return json_encode([]);
		}
	}

	/**
	 * @return string
	 */
    public function actionGetAreasByRegion() {
        if(isset(Yii::$app->request->post()['region_koatuu'])) {
			$region_koatuu = Yii::$app->request->post()['region_koatuu'];
            $result = [];
           /* $resultResponse = Areas::getAreasList(29);
			echo '<pre>';
			var_dump($resultResponse);*/
			$resultResponse = Koatuu::listAllAreas($region_koatuu);
			/*var_dump($resultResponse);
			exit;*/
			if(is_array($resultResponse) && count($resultResponse) > 0) {
                $result = $resultResponse;
            }
            return json_encode($result);
        } else {
            return json_encode([]);
        }
    }

	/**
	 * @return string
	 */
	public function actionGetCitiesByAreas() {
		if(isset(Yii::$app->request->post()['area_id'])) {
			$area_id = Yii::$app->request->post()['area_id'];
			$result = [];
			$resultResponse = Cities::getCityList($area_id);
			if(is_array($resultResponse) && count($resultResponse) > 0) {
				$result = $resultResponse;
			}
			return json_encode($result);
		} else {
			return json_encode([]);
		}
	}

	public function actionGetLocations() {
        Yii::$app->response->format = Response::FORMAT_JSON;
		if(isset(Yii::$app->request->post()['areas_koatuu']) && is_numeric(Yii::$app->request->post()['areas_koatuu']) ) {
			$areas_koatuu = Yii::$app->request->post()['areas_koatuu'];
			$result = [];
            //$areaModel = Areas::findOne($area_id);
			$resultResponse = Koatuu::listAllCities($areas_koatuu);
			//$resultResponse = Cities::getCityList($area_id);
            $is_city = false;
            $is_finish = true;
            if (!isset(Yii::$app->request->post()['for_search'])) {
                if(count($resultResponse) > 0) {
                    $result = $resultResponse;
                    $is_finish = false;
                }
                if(Koatuu::isCity($areas_koatuu)) {
                    $is_city = true;
                } else {
                    $is_finish = false;
                    $result = array_values($resultResponse);
                }
            } else {
                $result = $resultResponse;
            }
            return [
			    'status' => true,
                'data' => $result,
                'is_city' => $is_city,
                'is_finish' => $is_finish,
            ];
		} else {
			return ['status' => false];
		}
	}

    public function actionGetStreets() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(isset(Yii::$app->request->post()['koatuu']) && isset(Yii::$app->request->post()['type_id'])) {
            $koatuu = Yii::$app->request->post()['koatuu'];
            $type_id = Yii::$app->request->post()['type_id'];
            $result = array_values(Street::listAll($koatuu, $type_id));
            return [
                'status' => true,
                'data' => $result,
            ];
        } else {
            return ['status' => false];
        }
    }
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionGetVarDump(){
        $models = Street::find()->all();
        foreach ($models as $model) {
            if ($model instanceof Street) {
                $existModel = Street::findOne(['name' => $model->name, 'type_id' => $model->type_id, 'koatuu' => $model->koatuu]);
                if ($existModel instanceof Street && $existModel->id != $model->id) {
                    Yii::$app->db->createCommand("UPDATE `user` SET `street_id` = '$existModel->id' WHERE `street_id` = '$model->id';")->execute();
                    $model->delete();
                }
            }
        }
    }

	public function actionGetVarDump2(){
    	$models = Street::findAll();
        foreach ($models as $model) {
            if ($model instanceof Street) {
                $existModel = Street::findOne(['name' => $model->name, 'type_id' => $model->type_id, 'koatuu' => $model->koatuu]);
                if ($existModel instanceof Street && $existModel->id != $model->id) {
                    Yii::$app->db->createCommand("UPDATE `user` SET `street_id` = '$existModel->id' WHERE `street_id` = '$model->id';")->execute();
                    $model->delete();
                }
            }
    	}
	}

    protected function findModelUserByMd5Id($id)
    {
        if (($model = User::findOne(['md5(id)' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
