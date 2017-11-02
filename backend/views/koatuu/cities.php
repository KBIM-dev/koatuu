<?php

use common\models\Areas;
use common\models\Koatuu;
use common\models\LocationTypes;
	use common\models\Street;
	use yii\helpers\Html;
use yii\grid\GridView;
	use yii\helpers\Url;

	/* @var $this yii\web\View */
/* @var $searchModel backend\models\CitiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $id string */
/* @var $titleRegion string */
/* @var $titleArea string */
/* @var $id_region string */

$this->title = Yii::t('cities', 'Cities');
$this->params['breadcrumbs'][] = ['label' => Yii::t('location', 'Regions'), 'url' => ['koatuu/regions']];
$this->params['breadcrumbs'][] = ['label' => $titleRegion, 'url' => ['koatuu/areas', 'id' => str_pad(substr($id,0,2),10,'0')]];
$this->params['breadcrumbs'][] = ['label' => $titleArea];
?>
<div class="cities-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /*Html::a(Yii::t('events', 'Create Cities'), ['create'], ['class' => 'btn btn-success'])*/ ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '30']
            ],
            [
                'attribute' => 'TE',
                'headerOptions' => ['width' => '50']
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('location', 'City name'),
                'value'		=>function($model){
                    $type = '';
                    $typeEnd = '';
                    if ($model instanceof Koatuu) {
                        switch ($model->NP){
                            case 'М':
                                $type = 'м.';
                                break;
                            case 'Т':
                                $type = 'смт.';
                                break;
                            case 'С':
                                $type = 'с.';
                                break;
                            case 'Щ':
                                $type = 'с-ще.';
                                break;
                            case 'Р':
                                $typeEnd = 'р-н.';
                                break;
                        }

                    }
                    return trim($type.' '.Koatuu::mb_ucfirst($model->name).' '.$typeEnd);
                }
            ],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{street}',
				'headerOptions' => ['width' => '25'],
				'buttons' => [
					'street' => function ($url, $model, $key){

						$res = Street::find()->select("id")->where(['koatuu' => $model->TE])->count();
						if($res) {
							return Html::a("<span class='fa fa-road' aria-hidden='true'></span>", Url::to(['street/index', 'koatuu' => $model->TE]), [
								'title' => Yii::t('location', 'Streets'),
								'style' => ['margin-left' => '3px'],
							]);
						}
					},
				],
			],
        ],
    ]); ?>
</div>
