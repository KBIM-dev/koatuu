<?php


namespace backend\modules\user\controllers;

use dektrium\user\controllers\SettingsController as BaseSettingsController;
use developeruz\db_rbac\behaviors\AccessBehavior;
use backend\modules\user\models\SettingsForm;
use dektrium\user\Finder;
use common\models\Profile;
use common\models\User;
use dektrium\user\Module;
use dektrium\user\traits\AjaxValidationTrait;
use dektrium\user\traits\EventTrait;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsController extends BaseSettingsController
{
    use AjaxValidationTrait;
    use EventTrait;


    public $defaultAction = 'profile';

    /** @var Finder */
    protected $finder;

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                    'delete'     => ['post'],
                ],
            ],
			'as AccessBehavior' => [
				'class' => AccessBehavior::className(),
			],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'networks', 'disconnect', 'delete'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['confirm'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {

    	return $this->redirect(['/profile/account/edit']);

        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }


        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }
        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Displays page where user can update account settings (username, email or password).
     *
     * @return string|\yii\web\Response
     */
    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $model = \Yii::createObject(SettingsForm::className());
        $event = $this->getFormEvent($model);

        if (\Yii::$app->request->isAjax || \Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if (!isset($post["settings-form"]["koatuu"])) {
                $post["settings-form"]["koatuu"] = null;
            }
            if (!isset($post["settings-form"]["regionKoatuu"])) {
                $post["settings-form"]["regionKoatuu"] = null;
            }
            if (!isset($post["settings-form"]["areaKoatuu"])) {
                $post["settings-form"]["areaKoatuu"] = null;
            }
            if (!isset($post["settings-form"]["cityKoatuu"])) {
                $post["settings-form"]["cityKoatuu"] = null;
            }
            if (\Yii::$app->request->isAjax && $model->load($post)) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                \Yii::$app->response->data   = ActiveForm::validate($model);
                \Yii::$app->response->send();
                \Yii::$app->end();
            }
        }

        $this->trigger(self::EVENT_BEFORE_ACCOUNT_UPDATE, $event);
        if (\Yii::$app->request->isPost){
            if (!isset($post["settings-form"]["loc_id"])) {
                $post["settings-form"]["loc_id"] = null;
            }
            if ($model->load($post) && $model->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
                $this->trigger(self::EVENT_AFTER_ACCOUNT_UPDATE, $event);
                return $this->refresh();
            }
        }

       /* return $this->render('account', [
            'model' => $model,
        ]);*/
		return $this->render('account', [
			'model' => $model,
		]);
    }

    /**
     * Attempts changing user's email address.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = $this->finder->findUserById($id);

        if ($user === null || $this->module->emailChangeStrategy == Module::STRATEGY_INSECURE) {
            throw new NotFoundHttpException();
        }

        $event = $this->getUserEvent($user);

        $this->trigger(self::EVENT_BEFORE_CONFIRM, $event);
        $user->attemptEmailChange($code);
        $this->trigger(self::EVENT_AFTER_CONFIRM, $event);

        return $this->redirect(['account']);
    }

    /**
     * Displays list of connected network accounts.
     *
     * @return string
     */
    public function actionNetworks()
    {
        return $this->render('networks', [
            'user' => \Yii::$app->user->identity,
        ]);
    }

    /**
     * Disconnects a network account from user.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDisconnect($id)
    {
        $account = $this->finder->findAccount()->byId($id)->one();

        if ($account === null) {
            throw new NotFoundHttpException();
        }
        if ($account->user_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        $event = $this->getConnectEvent($account, $account->user);

        $this->trigger(self::EVENT_BEFORE_DISCONNECT, $event);
        $account->delete();
        $this->trigger(self::EVENT_AFTER_DISCONNECT, $event);

        return $this->redirect(['networks']);
    }

    /**
     * Completely deletes user's account.
     *
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionDelete()
    {
        if (!$this->module->enableAccountDelete) {
            throw new NotFoundHttpException(\Yii::t('user', 'Not found'));
        }

        /** @var User $user */
        $user  = \Yii::$app->user->identity;
        $event = $this->getUserEvent($user);

        \Yii::$app->user->logout();

        $this->trigger(self::EVENT_BEFORE_DELETE, $event);
        $user->delete();
        $this->trigger(self::EVENT_AFTER_DELETE, $event);

        \Yii::$app->session->setFlash('info', \Yii::t('user', 'Your account has been completely deleted'));

        return $this->goHome();
    }
}
