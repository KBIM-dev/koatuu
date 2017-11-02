<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use yii\bootstrap\Tabs;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LoginForm $loginModel
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<?=$this->render('/_alert', ['module' => Yii::$app->getModule('user')])?>

<div class="login-box">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?=Html::encode($this->title)?></h3>
        </div>
        <div class="panel-body">
            <?= $this->render('login-tab/_login_password', [
                'model' => $loginModel,
                'module' => $module
            ]) ?>
        </div>
    </div>

    <?php if($module->enableConfirmation): ?>
        <p class="text-center">
            <?=Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend'])?>
        </p>
    <?php endif ?>
    <?php if($module->enableRegistration): ?>
        <p class="text-center">
            <?=Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/registration'])?>
        </p>
    <?php endif ?>
    <?=Connect::widget([
        'baseAuthUrl' => ['/user/security/auth'],
    ])?>
</div>
