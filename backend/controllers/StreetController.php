<?php

namespace backend\controllers;

use common\models\StreetTypes;
use Yii;
use common\models\Street;
use backend\models\StreetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * StreetController implements the CRUD actions for Street model.
 */
class StreetController extends Controller
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
        ];
    }

    /**
     * Lists all Street models.
     * @return mixed
     */
    public function actionIndex($koatuu = null)
    {
		$searchModel = new StreetSearch();
    	if($koatuu) {
			$params = ['StreetSearch' => ['koatuu' => $koatuu]];
			$dataProvider = $searchModel->search($params);
		} else {
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		}

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'koatuu' => $koatuu,
        ]);
    }

    /**
     * Displays a single Street model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Street model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($koatuu = null)
    {
        $model = new Street();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'koatuu' => $koatuu,
            ]);
        }
    }

    /**
     * Updates an existing Street model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->save();
            } else {
                $existModel = Street::findOne(['name' => $model->name, 'type_id' => $model->type_id, 'koatuu' => $model->koatuu]);
                if ($existModel instanceof Street) {
                    Yii::$app->db->createCommand("UPDATE `user` SET `street_id` = '$existModel->id' WHERE `street_id` = '$model->id';")->execute();
                    $model->delete();
                    $model = $existModel;
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Street model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionGetStreet() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->request->isPost) {
            if (Yii::$app->request->post('depdrop_parents')) {
                $parents = Yii::$app->request->post('depdrop_parents');
                $type_id = empty($parents[0]) ? null : $parents[0];
                $area = empty($parents[1]) ? null : $parents[1];
                $city = empty($parents[2]) ? null : $parents[2];
                $region = empty($parents[3]) ? null : $parents[3];
                $koatuu = $city ? $city : ($area ? $area : $region);
                if($type_id != null && $koatuu != null) {
                    $result = [];
                    $list =  Street::listAll($koatuu, $type_id);
                    foreach ($list as $i => $value) {
                        $result[] = ['id' => $i, 'name' => $value];
                    }
                    return ['output' => $result];
                }
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    /**
     * Finds the Street model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Street the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Street::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
