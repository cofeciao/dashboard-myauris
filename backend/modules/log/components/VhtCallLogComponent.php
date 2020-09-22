<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 20-May-19
 * Time: 2:19 PM
 */

namespace backend\modules\log\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use yii\base\Component;
use yii\helpers\Url;

class VhtCallLogComponent extends Component
{
    private $url = 'https://acd-api.vht.com.vn/rest/cdrs?sort_by=acd_cdr_id&sort_type=desc';

    public function __construct(array $config = [], array $params = [])
    {
        if (count($params) > 0) {
            foreach ($params as $key => $item) {
                if ($key == 'sort_by' || $key == 'sort_type') {
                    continue;
                }
                $this->url .= '&' . $key . '=' . $item;
            }
        }
        parent::__construct($config);
    }

    public function ConnectVht()
    {
        $api_key = 'fc5e75859bb425deb8ae3d36ddcd36bb';
        $api_secret = '4e45b1e32eacc36c9cf766dff0d91372';
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($api_key . ':' . $api_secret)
            ]
        ]);
        if (CONSOLE_HOST == false/*\Yii::$app->request->getUserIP() == '127.0.0.1'*/) {
            $client = new Client([
                'verify' => Url::to('@backend/modules/clinic/token/cacert.pem'),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($api_key . ':' . $api_secret)
                ],
            ]);
        }


        try {
            $response = $client->request('GET', $this->url, []);

            $body = $response->getBody();
//            var_dump(json_decode($body));die;
            return json_decode($body);
        } catch (ClientException $e) {
            return null;
            var_dump($e);
            die;
        }
    }
}
