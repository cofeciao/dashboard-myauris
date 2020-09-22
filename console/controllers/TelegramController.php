<?php
namespace console\controllers;

use backend\modules\helper\models\BotTelegram;
use yii\console\Controller;
use Yii;

class TelegramController extends Controller
{
    public function actionIndex()
    {
        // Yii::warning("Nghia: Telegram work");
        BotTelegram::sendMessage(); //@NGHIA co thoi gian se phat trien them
    }
}
