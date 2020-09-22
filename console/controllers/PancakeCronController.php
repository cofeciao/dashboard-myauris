<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-May-19
 * Time: 4:25 PM
 */
namespace console\controllers;

use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\customer\models\Pancake;
use backend\modules\helper\models\BotTelegram;
use backend\modules\setting\models\Setting;
use common\models\UserProfile;
use Yii;
use yii\console\Controller;
use yii\base\Exception;

class PancakeCronController extends Controller
{
    public function actionIndex()
    {
//        $today = date('d-m-Y', time());
//        $yesterday = date('d-m-Y',strtotime($today . "-1 days"));
//        $yesterday = str_replace('-', '/', $yesterday);
//        $string =  exec("curl \"https://pages.fm/api/v1/pages/166536310081768/statistics/customer_engagements?user_id=52ef2e8e-fa59-494a-857a-06dc7ea2c0bd&&date_range=30/12/2019%20-%2030/12/2019&access_token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiIwM2RhNTQ5ZC02NjE3LTRkNTktODIzZC1mYTc3ZjdlMzU4ZTIiLCJpYXQiOjE1Nzc2OTA1NDgsImZiX25hbWUiOiJQaOG6oW0gVGjDoG5oIE5naMSpYSIsImZiX2lkIjoiNTQwNzQxNjEyNzgxMDkyIiwiZXhwIjoxNTg1NDY2NTQ4fQ.xrqrdTPqTVzT2lxMfl7JNh-JWvpzkQ-Opx0W9wDYVtA\"");

//        BotTelegram::sendMessage();

        $yesterday = date('d/m/Y', time()); // chuyen lai thanh hom nay
        $mUserProfile = new UserProfile();
        $listUser = $mUserProfile->getListHasLabelPancake();
        $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
        foreach ($listUser as $user => $user_id) {
            foreach ($listPage as $id_page => $value) {
                $this->insertPancake($user_id, $id_page, $yesterday);
            }
            sleep(1);
        }
    }


    public function insertPancake($user_id, $id_fanpage, $date_from)
    {
        $setting = Setting::find()
            ->where(['key_value' => 'access_token_pancake'])
            ->one();
        $access_token = "";
        if ($setting !== null) {
            $access_token = $setting->value;
        }
        $user = UserProfile::findOne($user_id);
        if (!$user) {
            return [
                'status' => 400,
                'msg' => "user_id"
            ];
        }
        $Fanpage = Dep365CustomerOnlineFanpage::findOne($id_fanpage);
        if (!$Fanpage) {
            return [
                'status' => 400,
                'msg' => "Wrong Fanpage id_fanpage : ".$id_fanpage
            ];
        }
        $page_facebook = $Fanpage->id_facebook;
        $label_pancake = $user->label_pancake;

        $url = "https://pages.fm/api/v1/pages/".$page_facebook."/statistics/customer_engagements?user_id=".$label_pancake."&&date_range=".$date_from."%20-%20".$date_from."&access_token=".$access_token;
//        $url = str_replace(" ", "",  $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HEADER, 0);

        // https://stackoverflow.com/questions/24701132/php-curl-script-works-from-browser-doesnt-work-as-cron-job
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        print_r($result);

        if ($result === false) {
//            throw new Exception($url);
            //throw new Exception(curl_error($ch), curl_errno($ch));
            $string = "curl \"".$url."\" ";
            $result =  exec($string);
        }

        curl_close($ch);


        $arr = explode('/', $date_from);
        $dayImport = $arr[0] . '-' . $arr[1] . '-' . $arr[2]; //date("Y");

        $array_result = json_decode($result, true);

        if (is_array($array_result) && $array_result['success'] === true) {
            $data = $array_result['data']['series'][3]['data'];
            if (is_array($data)) {
                $num = $data[0];
                $pc = $this->findPancake($user_id, strtotime($dayImport), $id_fanpage);

                if (!$pc) {
                    $pancake = new Pancake();
                } else {
                    $pancake = $this->findModelPancake($pc);
                }
                $pancake->user_id = $user_id;
                $pancake->number_pancake = $num;
                $pancake->page_facebook = $id_fanpage;
                $pancake->date_import = strtotime($dayImport);
                $pancake->save(false);

                return [
                    'status' => 200,
                ];
            } else {
                return [
                    'status' => 400,
                    'msg' => "is_array(data) false user_id = ".$user_id
                ];
            }
        } else {
            return [
                'status' => 400,
                'msg' => "success false"
            ];
        }
    }

    public function findModelPancake($id)
    {
        $pancake = Pancake::findOne($id);
        if ($pancake) {
            return $pancake;
        }
        return false;
    }

    public function findPancake($user_id, $timeImport, $pageface)
    {
        $panCake = Pancake::find()->where(['user_id' => $user_id, 'date_import' => $timeImport, 'page_facebook' => $pageface])->one();
        if ($panCake) {
            return $panCake->id;
        }
        return false;
    }
}
