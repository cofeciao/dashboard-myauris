<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-May-19
 * Time: 4:25 PM
 */

namespace backend\modules\helper\controllers;

use backend\components\MyController;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\customer\models\Pancake;
use backend\modules\setting\models\Setting;
use common\models\UserProfile;
use Yii;
use yii\base\Exception;

class PancakeController extends MyController
{
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            $user_id = Yii::$app->request->post('id');
            $label_pancake = Yii::$app->request->post('label_pancake');
            return UserProfile::savePancakeLable($user_id, $label_pancake);
        }

        return $this->render('index', [

        ]);
    }

    public function actionData()
    {
        $mUserProfile = new UserProfile();

        if (Yii::$app->request->isAjax) {
            $startDateReport = Yii::$app->request->post('startDateReport');
            $endDateReport = Yii::$app->request->post('endDateReport');


            $startTime = strtotime($startDateReport);
            $endTime = strtotime($endDateReport);

            $listUser = $mUserProfile->getListHasLabelPancake();
            $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
            for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
                $date = date('d/m/Y', $i);
                foreach ($listUser as $user => $user_id) {
                    foreach ($listPage as $id_page => $value) {
                        $this->insertPancake($user_id, $id_page, $date);
                    }
                    sleep(1);
                }
            }
        }

        return $this->render('data', [

        ]);
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

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if ($result === false) {
//            throw new Exception($url);
            throw new Exception(curl_error($ch), curl_errno($ch));
//            $string = "curl \"".$url."\" ";
//            $result =  exec($string);
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

    public function findPancake($user_id, $timeImport, $pageface)
    {
        $panCake = Pancake::find()->where(['user_id' => $user_id, 'date_import' => $timeImport, 'page_facebook' => $pageface])->one();
        if ($panCake) {
            return $panCake->id;
        }
        return false;
    }

    public function findModelPancake($id)
    {
        $pancake = Pancake::findOne($id);
        if ($pancake) {
            return $pancake;
        }
        return false;
    }

    // cron
    public function actionPancakeUpdate()
    {
        $today = date('d-m-Y', time());
        $yesterday = date('d-m-Y', strtotime($today . "-1 days"));
        $yesterday = str_replace('-', '/', $yesterday);
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
}
