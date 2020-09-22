<?php


namespace backend\modules\test\controllers;

use backend\components\MyController;
use common\components\GapiTextToSpeechComponent;
use common\helpers\MyHelper;
use GuzzleHttp\Client;
use yii\helpers\Url;
use Yii;

class TestController extends MyController
{
    public function actionNguyenTest($text)
    {
//        $tokenPath = Url::to('@backend/modules') . '/clinic/token/token-text-to-speech.json';
        $gapi = new GapiTextToSpeechComponent();
//        $gapi->setAccessToken($tokenPath);

        $audio = $gapi->test($text, 'uploads/temp/audio');
        if ($audio != null) {
            header('Content-Type: audio/mpeg');
            header('Content-Disposition: filename="' . $audio . '"');
            header('Cache-Control: no-cache');
            header("Content-Transfer-Encoding: chunked");
            readfile(Url::to('@backend/web') . '/uploads/temp/audio/' . $audio);
            die();
        }
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function actionNguyenTest1()
    {
        $client = new Client(['verify' => false]);
        $conn = $client->request('POST', SOCKET_URL, [
            'form_params' => [
                'handle' => 'dep365-alert',
                'message' => 'Có khách hàng vừa đặt lịch.',
                'data' => json_encode([
                    'act' => 'customer-online-booking',
                    'title' => 'Thông báo'
                ])
            ]
        ]);
        var_dump($conn->getBody()->getContents());
        die;
    }
}
