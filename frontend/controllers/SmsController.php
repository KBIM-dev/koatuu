<?php
namespace frontend\controllers;

use common\models\CommunicationType;
use common\models\Events;
use common\models\EventsUser;
use common\models\HistoryOfContacts;
use common\models\User;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SmsController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionSet()
    {
        return false;
    }

    public function actionUnset()
    {
        return false;
    }

    public function actionHook()
    {
        $get = Yii::$app->request->get();
        if(isset($get['receiver']) && isset($get['sender']) && isset($get['when']) && isset($get['text'])) {
            $wrongSMS = false;
            $message = Yii::t('sms', 'Wrong SMS');
            $wrongIds = [];
            $receiver   = $get['receiver'];
            $sender     = $get['sender'];
            $when       = $get['when'];
            $text       = $get['text'];
            return '';
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSend()
    {
        return false;
    }


    public static function sendConfirmMessage($id) {
        return false;
    }


    /**
     * @param $id User->id|integer
     * @param $message
     * @return mixed
     */
    public static function sendSingleMessage($id, $message)
    {
        $user = User::findOne($id);
        if($user) {
            $phone = (string)$user->phone;
            if(strlen($phone) && $phone{0} != 0) {
                $client = new SendSingleTextualSms(new BasicAuthConfiguration(Yii::$app->params['SMS_USER'], Yii::$app->params['SMS_PASSWORD']));
                $requestBody = new SMSTextualRequest();
                $requestBody->setFrom(Yii::$app->params['SMS_NUMBER']);
                $requestBody->setTo([$phone]);
                $requestBody->setText($message);

                $response = $client->execute($requestBody);
                return true;
            }
        }
        return false;
    }
}
