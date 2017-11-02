<?php

namespace backend\controllers;

use common\models\UploadForm;
use developeruz\db_rbac\behaviors\AccessBehavior;
use Yii;
use common\models\Badge;
use backend\models\BadgeTagsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BadgeController implements the CRUD actions for Badge model.
 */
class BadgeController extends Controller
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
            'as AccessBehavior' => [
                'class' => AccessBehavior::className(),
            ],
        ];
    }

    /**
     * Lists all Badge models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BadgeTagsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Badge model.
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
     * Creates a new Badge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Badge();
		$file = new UploadForm();
		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $file->imageFile = UploadedFile::getInstance ($model, 'imageFile');
            if ($file->imageFile) {
                $model->img = $file->uploadImage();
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }

		}
		return $this->render('create', [
			'model' => $model,
		]);

    }

    /**
     * Updates an existing Badge model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		$file = new UploadForm();
		if ($model->load(Yii::$app->request->post())) {
			//if we have new image upload it and remove old
            if (Yii::$app->request->post('deleteImage') == 'deleteImage'){
                $model->removeImage($model->img);
                $model->img = null;
            }
			if(isset($_FILES['Badge']['name']['imageFile']) && $_FILES['Badge']['name']['imageFile'] != ''){
				$file->imageFile = UploadedFile::getInstance ($model, 'imageFile');
				$oldImg = $model->img;
                if ($model->img = $file->uploadImage()) {
                    if ($oldImg) {
                        $model->removeImage($oldImg);
                    }
                }
			}
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}else{
				return $this->render('update', [
					'model' => $model,
				]);
			}
		}else{
			return $this->render('update', [
				'model' => $model,
			]);
		}
    }

    /**
     * Deletes an existing Badge model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->img) {
            $file = \Yii::getAlias('@backend/web').$model->img;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Badge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Badge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Badge::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
