<?php
/**
 * Created by PhpStorm.
 * User: Bogdan
 * Date: 05.10.2016
 * Time: 17:59
 */

namespace common\components;


use common\models\User;
use yii\base\Component;
use yii\console\Application;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class BeforeAllActions extends Component
{
    public function init()
    {
        if(!Yii::$app instanceof Application) {
            $path = '/' . Yii::$app->request->pathInfo;
            $get = Yii::$app->request->get();
            $url = Url::to(ArrayHelper::merge([$path], $get));

            if($url != Yii::$app->getRequest()->url) {
                return Yii::$app->response->redirect(Url::to($url), 301);
            }
            if(!Yii::$app->user->isGuest) {
                $user_id = Yii::$app->user->id;
                $user = User::findOne($user_id);
                /**@var $user User */
                if($user->getIsBlocked()) {
                    Yii::$app->user->logout();
                }
            }
            $model = \Yii::$app->user->identity;
            if ($model instanceof User) {
                if(\Yii::$app->session->getFlash('warning')) {
                    \Yii::$app->session->removeFlash('warning');
                }
            }
        }
        parent::init();
    }
}