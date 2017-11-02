<?php

namespace backend\controllers;

use Yii;
use common\models\Demons;
use backend\models\DemonsSearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DemonsController implements the CRUD actions for Demons model.
 */
class DemonsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [
							'index', 'update', 'create',
						],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
        ];
    }

	/**
	 * Lists all Demons models.
	 *
	 * @param null $id
	 * @param null $status
	 *
	 * @return mixed
	 */
    public function actionIndex($id = null, $status = null)
    {
    	if(isset($id) && isset($status)){
    		$demon = Demons::findOne($id);
			if($demon != null){
				if($status == 'stop'){
					$demon->enabled = Demons::DAEMON_DISABLED;
				}elseif($status == 'play'){
					$demon->enabled = Demons::DAEMON_ENABLED;
				}
				$demon->save();
				$this->redirect(Url::to(['demons/index']));
			}

		}
        $searchModel = new DemonsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Demons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Demons();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Demons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Demons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Demons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Demons::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
