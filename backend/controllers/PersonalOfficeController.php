<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use developeruz\db_rbac\behaviors\AccessBehavior;
/**
 * PersonalOfficeController implements the CRUD actions for User model.
 */
class PersonalOfficeController extends Controller
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
                    'send-to-all' => ['POST'],
                    'send-invited-users' => ['POST'],
                ],
            ],
			'as AccessBehavior' => [
				'class' => AccessBehavior::className(),
			],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionUserInvited()
    {
        $searchModel = new UserSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		if(isset(Yii::$app->request->queryParams["UserSearch"])){
			foreach(Yii::$app->request->queryParams["UserSearch"] as $attributeName => $attributeValue){
				$searchModel->setAttribute($attributeName, $attributeValue);
			}
		}
        return $this->render('user-invited', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
