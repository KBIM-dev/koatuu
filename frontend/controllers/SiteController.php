<?php
namespace frontend\controllers;

use common\models\Areas;
use common\models\RegistrationForm;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use dektrium\user\traits\AjaxValidationTrait;
use dektrium\user\traits\EventTrait;
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
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionError()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->getResponse()->redirect('/login')->send();
        } else {
            Yii::$app->getResponse()->redirect('/')->send();
        }

    }

    public function actionLogin()
    {
        Yii::$app->getResponse()->redirect('/login')->send();
    }

    public function actionGetVarDump(){
		echo '<pre>';
		var_dump (1);
		exit;
	}
}
