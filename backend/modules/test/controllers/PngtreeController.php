<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12-Mar-19
 * Time: 3:00 PM
 */

namespace backend\modules\test\controllers;

use backend\components\MyController;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\CookieJar;
use yii\helpers\Url;

class PngtreeController extends MyController
{
    public function actionIndex()
    {
        $url = 'https://pngtree.com/';

        $cookieFile = Url::to('@web/uploads/cookie/cookie_jar.txt');

        $cookieJar = new FileCookieJar($cookieFile, $url);
        $httpClient = new GuzzleClient();

        $response = $httpClient->request(
            'POST',
            $url,
            [
                'form_params' => [
                    'username' => 'thietkenucuoi.auris@gmail.com',
                    'password' => 'thietke123'
                ],
                'cookies' => $cookieJar
            ]
        );

        var_dump($response);
        die;
        return $this->render('index', [

        ]);
    }
}
