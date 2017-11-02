<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('personal_office', 'Users'), 'url' => ['user-invited']];
$this->params['breadcrumbs'][] = $this->title;

		if (extension_loaded('intl')) {
			$model->created_at = Yii::t('user', '{0, date, dd MMMM, YYYY HH:mm}', [$model->created_at]);
		} else {
			$model->created_at = date('Y-m-d G:i:s', $model->created_at);
		}
        $phone = '-';
        if(!empty($model->phone)){
            $phone = Html::a("+$model->phone","tel:+$model->phone");
        }
        $model->username = $model->getFullName();

?>
<div class="user-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            'created_at',
            [
                'attribute'=>'phone',
                'format' => 'raw',
                'value'=>$phone,
            ],
            'areasName',
            'live_locality_name',
            'address',
            'professionName',
        ],
    ]) ?>

</div>
