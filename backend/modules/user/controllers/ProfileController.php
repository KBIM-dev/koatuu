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
use dektrium\user\controllers\ProfileController as BaseProfileController;
use developeruz\db_rbac\behaviors\AccessBehavior;
use dektrium\user\Finder;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
/**
 * ProfileController shows users profiles.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class ProfileController extends BaseProfileController
{
    /** @var Finder */
    protected $finder;


    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                    	'allow' => true,
						'actions' => ['index', 'is-voted'],
						'roles' => ['@']
					],
                    [
                    	'allow' => true,
						'actions' => ['show'],
						'roles' => ['@']
					],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'is-voted' => ['POST'],
                ],
            ],
			'as AccessBehavior' => [
				'class' => AccessBehavior::className(),
			],
        ];
    }

    /**
     * Shows user's profile.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($id)
    {
        $tree = User::getUserTree($id);
        $tree = User::generateTree($tree['all'], $tree['id']);
        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('show', [
            'profile' => $profile,
            'tree' => $tree,
        ]);
    }
}
