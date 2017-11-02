<?php

namespace backend\controllers;

use backend\models\CitiesSearch;
use backend\models\KoatuuSearch;
use backend\models\RegionsSearch;
use backend\models\AreasSearch;
use common\models\Koatuu;
use common\models\KoatuuImport;
use common\models\UploadForm;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\UploadedFile;

class KoatuuController extends \yii\web\Controller
{
	public function actionRegions()
	{
		$searchModel = new KoatuuSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, Koatuu::KOATUU_REGIONS);
		$dataProvider->pagination->pageSize = 50;
		return $this->render('/koatuu/regions', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

    public function actionAreas($id) // id region
    {
		$searchModel = new KoatuuSearch();
		$titleRegion = Yii::$app->db->createCommand("SELECT CASE WHEN LOCATE('/', NU) > 0 THEN LEFT(NU,  LOCATE('/', NU) - 1) ELSE NU END as name FROM koatuu where TE=RPAD(SUBSTRING('$id', 1,2),10,'0')")->queryOne()['name'];
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, Koatuu::KOATUU_AREAS, $id);
		$dataProvider->pagination->pageSize = 50;
        $titleRegion = Koatuu::RegionName($titleRegion);
		return $this->render('areas', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'titleRegion' => $titleRegion,
		]);
    }

    public function actionCities($id)
    {
		$searchModel =  new KoatuuSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, Koatuu::KOATUU_CITIES, $id);
		$dataProvider->pagination->pageSize = 50;
		$titleArea = Yii::$app->db->createCommand("SELECT CASE WHEN LOCATE('/', NU) > 0 THEN LEFT(NU,  LOCATE('/', NU) - 1) ELSE NU END as name FROM koatuu where TE=RPAD(SUBSTRING('$id', 1,5),10,'0')")->queryOne()['name'];
        $titleRegion = Yii::$app->db->createCommand("SELECT CASE WHEN LOCATE('/', NU) > 0 THEN LEFT(NU,  LOCATE('/', NU) - 1) ELSE NU END as name FROM koatuu where TE=RPAD(SUBSTRING('$id', 1,2),10,'0')")->queryOne()['name'];
        $titleRegion = Koatuu::RegionName($titleRegion);
		return $this->render('/koatuu/cities', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'id' => $id,
			'titleArea' => Koatuu::mb_ucfirst($titleArea),
            'titleRegion' => $titleRegion,
		]);
    }

    public function actionGetAreas() {
		Yii::$app->response->format = Response::FORMAT_JSON;
		if(Yii::$app->request->isPost) {
			if (Yii::$app->request->post('depdrop_parents')) {
				$parents = Yii::$app->request->post('depdrop_parents');
				$region_id = $parents[0];
				$result = "";
                if (!empty($region_id)) {
                    $result = [];
                    $resultsTemp =  Koatuu::listAllAreasKoatuu($region_id);
                    foreach($resultsTemp as $key => $value) {
                        $result[$key] = false;
                        foreach($value as $keyInner => $valueInner) {
                            $result[$key][] = ['id' => $keyInner, 'name' => $valueInner];
                        }
                    }
                }
				return ['output'=>$result, 'selected' => ''];
			}
		}
		return ['status' => false];
	}

	public function actionGetCities() {
		Yii::$app->response->format = Response::FORMAT_JSON;
		if(Yii::$app->request->isPost) {
			if (Yii::$app->request->post('depdrop_parents')) {
				$parents = Yii::$app->request->post('depdrop_parents');
				$area_id = empty($parents[0]) ? null : $parents[0];
				if($area_id != null) {
					$result =  Koatuu::listAllCitiesKoatuu($area_id);
					if (count($result) == 0) {
                        $result = '';
                    }
					return ['output' => $result];
				}
			}
		}
		return ['output' => '', 'selected' => ''];
	}

	public function actionDeCommunizationImport(){
		$model = new UploadForm();
		$model->scenario = UploadForm::SCENARIO_IMPORT;
		$importModel = new KoatuuImport();
		$importModel->scenario = KoatuuImport::SCENARIO_DE_COMUNISATION_IMPORT;

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $importModel->load(Yii::$app->request->post()) ) {
			$model->importFile = UploadedFile::getInstance ($model, 'importFile');
			if ($model->importFile) {
				$pathToFile = $model->uploadImportFile();
				if($pathToFile){
					$importModel->fullPathToFile = $pathToFile;
					$result = $importModel->deCommunisationImport();
					if($result){
						\Yii::$app->getSession()->setFlash('success', \Yii::t('location', 'Import successful'));
					}else{
						\Yii::$app->getSession()->setFlash('error', \Yii::t('location', 'Import fail'));
					}
				}
			}
			return $this->refresh();
		}
		return $this->render('de-communization-import', ['model' => $model, 'importModel' => $importModel]);
	}

	public function actionChangeImport(){
		$model = new UploadForm();
		$model->scenario = UploadForm::SCENARIO_IMPORT;
		$importModel = new KoatuuImport();
		$importModel->scenario = KoatuuImport::SCENARIO_CHANGE_IMPORT;
		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $importModel->load(Yii::$app->request->post()) ) {
			$model->importFile = UploadedFile::getInstance ($model, 'importFile');
			if ($model->importFile) {
				$pathToFile = $model->uploadImportFile();
				if($pathToFile){
					$importModel->fullPathToFile = $pathToFile;
					$result = $importModel->changeImport();
					if($result){
						\Yii::$app->getSession()->setFlash('success', \Yii::t('location', 'Import successful'));
					}else{
						\Yii::$app->getSession()->setFlash('error', \Yii::t('location', 'Import fail'));
					}
				}
			}
			return $this->refresh();
		}
		return $this->render('change-import', ['model' => $model, 'importModel' => $importModel]);
	}
}
