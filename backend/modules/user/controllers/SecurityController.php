<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace backend\modules\user\controllers;

use common\models\User;
use dektrium\user\controllers\SecurityController as BaseSecurityController;
use common\models\LoginForm;
use dektrium\user\Module;
use dektrium\user\traits\AjaxValidationTrait;
use dektrium\user\traits\EventTrait;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SecurityController extends BaseSecurityController
{
    use AjaxValidationTrait;
    use EventTrait;

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['login', 'auth', 'blocked', 'check-answer'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['auth', 'logout'], 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' 			=> ['post'],
                    'check-answer' 		=> ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $loginModel */
        $loginModel = \Yii::createObject(LoginForm::className());
        $event = $this->getFormEvent($loginModel);


        $this->performAjaxValidation($loginModel);
        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

		if (\Yii::$app->request->isPost && $loginModel->load(\Yii::$app->getRequest()->post())) {
            if ($loginModel->validate()) {
                if ($loginModel->login()) {
                    $this->trigger(self::EVENT_AFTER_LOGIN, $event);
                    if (\Yii::$app->user->can('adminAccess')) {
                        return \Yii::$app->response->redirect(Url::to(['admin/index']));
                    } else {
                        return \Yii::$app->response->redirect(Url::to(['profile/index']));
                    }
                }
            }
		}

        return $this->render('login', [
            'loginModel'  			=> $loginModel,
            'module' => $this->module,
        ]);
    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $event = $this->getUserEvent(Yii::$app->user->identity);

        $this->trigger(self::EVENT_BEFORE_LOGOUT, $event);

        Yii::$app->getUser()->logout();

        $this->trigger(self::EVENT_AFTER_LOGOUT, $event);

        return $this->goHome();
    }

}
