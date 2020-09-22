<?php
namespace backend\modules\helper\controllers;

use backend\models\auth\User;
use backend\models\CustomerModel;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\helper\models\BotTelegram;
use backend\modules\report\models\CustomerBaoCao;
use backend\modules\setting\models\Dep365CoSo;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class TelegramController extends Controller
{
    public function actionIndex()
    {
        BotTelegram::sendMessage();
    }
}
