<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 26-May-18
 * Time: 12:10 PM
 */

namespace frontend\components;

use yii\web\Controller;
use Yii;


class MyController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function goHome()
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
    }

    public function refresh($anchor = '')
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->request->url . $anchor);
    }

    public static function allowedDomains()
    {
        return [
            'https://www.youtube.com',
        ];
    }

    public function checkLocaleFromIp()
    {
        if (!Yii::$app->request->cookies->has('checkLang')) {
            $ip = Yii::$app->geoip->ip(Yii::$app->request->getUserIP());
            switch ($ip->isoCode) {
                case 'VN':
                    Yii::$app->language = 'vi';
                    break;
                case 'JP':
                    Yii::$app->language = 'jp';
                    break;
                default:
                    Yii::$app->language = 'en';
                    break;
            }
        }
    }

}