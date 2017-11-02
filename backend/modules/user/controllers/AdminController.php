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

use backend\models\UserSearchForUser;
use common\models\AccountsForm;
use common\models\CommunicationType;
use common\models\Profession;
use dektrium\user\controllers\AdminController as BaseAdminController;
use common\models\Profile;
use common\models\User;
use dektrium\user\Module;
use developeruz\db_rbac\behaviors\AccessBehavior;
use Yii;
use yii\base\ExitException;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * AdminController allows you to administrate users.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class AdminController extends BaseAdminController
{

    public function behaviors()
    {
        return [
            'as AccessBehavior' => [
                'class' => AccessBehavior::className(),
            ],
        ];
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel  = \Yii::createObject(UserSearchForUser::className());
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var User $user */
        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'create',
        ]);

		$profile = \Yii::createObject(Profile::className());
		$accountForm = new AccountsForm();

        $profId = Profession::find()->where(['name' => 'Не вказано'])->one();

        if ($profId) {
            $user->profession_id =  $profId->id;
        }
        $communicationId = CommunicationType::find()->where(['name' => 'Не вказано'])->one();
        if ($communicationId instanceof CommunicationType) {
            $user->communication_type_ids =  [$communicationId];
        }

        $user->added_id = Yii::$app->user->id;

        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($accountForm);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);

		$save = true;
        if (\Yii::$app->request->isPost && !\Yii::$app->request->isAjax && $accountForm->load(\Yii::$app->request->post()) && $save = $accountForm->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been created'));
            $this->trigger(self::EVENT_AFTER_CREATE, $event);
            return $this->redirect(['update', 'id' => $accountForm->user_id]);
        }elseif(!empty($accountForm->errors) || !$save){
			\Yii::$app->getSession()->setFlash('error', \Yii::t('user', 'Create user fail'));
		}
        return $this->render('_account', [
            'isNewRecord' => true,
            'user' => $user,
			'profile' => $profile,
			'accountForm' => $accountForm,
        ]);
    }

    /**
     * Updates an existing User model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $accountForm = new AccountsForm($id);

        $user = $this->findModel($id);
		$profile = $user->profile;

		if ($profile == null) {
			$profile = \Yii::createObject(Profile::className());
			$profile->link('user', $user);
		}

        if (\Yii::$app->request->isAjax || \Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            if (!isset($post["accounts-form"]["koatuu"])) {
                $post["accounts-form"]["koatuu"] = null;
            }
            if (!isset($post["accounts-form"]["regionKoatuu"])) {
                $post["accounts-form"]["regionKoatuu"] = null;
            }
            if (!isset($post["accounts-form"]["areaKoatuu"])) {
                $post["accounts-form"]["areaKoatuu"] = null;
            }
            if (!isset($post["accounts-form"]["cityKoatuu"])) {
                $post["accounts-form"]["cityKoatuu"] = null;
            }
            if (\Yii::$app->request->isAjax && $accountForm->load($post)) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                \Yii::$app->response->data   = ActiveForm::validate($accountForm);
                \Yii::$app->response->send();
                \Yii::$app->end();
            }
        }

        //$this->trigger(self::EVENT_BEFORE_UPDATE, $event);
        if (\Yii::$app->request->isPost && !\Yii::$app->request->isAjax){
            if (!isset($post["accounts-form"]["loc_id"])) {
                $post["accounts-form"]["loc_id"] = null;
            }

            if ($accountForm->load($post) && $accountForm->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Account details have been updated'));
               // $this->trigger(self::EVENT_AFTER_UPDATE, $event);
				return $this->refresh();
            }elseif(!empty($accountForm->errors)){
				\Yii::$app->getSession()->setFlash('error', \Yii::t('user', 'Account updated fail'));
				return $this->refresh();
			}
        }

        return $this->render('_account', [
            'isNewRecord' => false,
            'user' 		=> $user,
            'profile' 	=> $profile,
			'accountForm' => $accountForm
        ]);
    }

    /**
     * Updates an existing profile.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdateProfile($id)
    {
    	return $this->redirect(['update', 'id' => $id]);

        Url::remember('', 'actions-redirect');
        $user    = $this->findModel($id);
        $profile = $user->profile;

        if ($profile == null) {
            $profile = \Yii::createObject(Profile::className());
            $profile->link('user', $user);
        }

        $event   = $this->getProfileEvent($profile);

        $this->performAjaxValidation($profile);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);

        if ($profile->load(\Yii::$app->request->post()) && $profile->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Profile details have been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }

        return $this->render('_profile', [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Shows information about user.
     *
     * @param int $id
     *
     * @return string
     */
    public function actionInfo($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);

        return $this->render('_info', [
            'user' => $user,
        ]);
    }

    /**
     * If "dektrium/yii2-rbac" extension is installed, this page displays form
     * where user can assign multiple auth items to user.
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAssignments($id)
    {
        if (!isset(\Yii::$app->extensions['dektrium/yii2-rbac'])) {
            throw new NotFoundHttpException();
        }
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);

        return $this->render('_assignments', [
            'user' => $user,
        ]);
    }

    /**
     * Confirms the User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function actionConfirm($id)
    {
        $model = $this->findModel($id);
        $event = $this->getUserEvent($model);

        $this->trigger(self::EVENT_BEFORE_CONFIRM, $event);
        $model->confirm();
        $this->trigger(self::EVENT_AFTER_CONFIRM, $event);

        \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been confirmed'));

        return $this->redirect(Url::previous('actions-redirect'));
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($id == \Yii::$app->user->getId()) {
            \Yii::$app->getSession()->setFlash('danger', \Yii::t('user', 'You can not remove your own account'));
        } else {
            $model = $this->findModel($id);
            $event = $this->getUserEvent($model);
            $this->trigger(self::EVENT_BEFORE_DELETE, $event);
            $model->delete();
            $this->trigger(self::EVENT_AFTER_DELETE, $event);
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been deleted'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Blocks the user.
     *
     * @param int $id
     *
     * @return Response
     */
    public function actionBlock($id)
    {
        if ($id == \Yii::$app->user->getId()) {
            \Yii::$app->getSession()->setFlash('danger', \Yii::t('user', 'You can not block your own account'));
        } else {
            $user  = $this->findModel($id);
            $event = $this->getUserEvent($user);
            if ($user->getIsBlocked()) {
                $this->trigger(self::EVENT_BEFORE_UNBLOCK, $event);
                $user->unblock();
                $this->trigger(self::EVENT_AFTER_UNBLOCK, $event);
                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been unblocked'));
            } else {
                $this->trigger(self::EVENT_BEFORE_BLOCK, $event);
                $user->block();
                $this->trigger(self::EVENT_AFTER_BLOCK, $event);
                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been blocked'));
            }
        }

        return $this->redirect(Url::previous('actions-redirect'));
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $user = $this->finder->findUserById($id);
        if ($user === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }

        return $user;
    }

    /**
     * Performs AJAX validation.
     *
     * @param array|Model $model
     *
     * @throws ExitException
     */
    protected function performAjaxValidation($model)
    {
        if (\Yii::$app->request->isAjax && !\Yii::$app->request->isPjax) {
            if ($model->load(\Yii::$app->request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                echo json_encode(ActiveForm::validate($model));
                \Yii::$app->end();
            }
        }
    }
}
