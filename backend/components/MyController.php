<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 26-May-18
 * Time: 12:10 PM
 */

namespace backend\components;

use backend\modules\setting\models\Setting;
use backend\modules\user\models\User;
use cheatsheet\Time;
use common\commands\DeleteImageCommand;
use common\commands\ImageCommand;
use common\commands\ImagickCommand;
use Yii;
use yii\base\Module;
use yii\web\Controller;

class MyController extends Controller
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->id != null) {
            $system_maintenance = Setting::getKey('system_maintenance');
            if ($system_maintenance != null && $system_maintenance->value == '1') {
                if (!Yii::$app->user->can(User::USER_DEVELOP)) {
                    return $this->redirect(['/bao-tri']);
                }
            }
        }
        MyComponent::setCookies('id', Yii::$app->user->id);
    }

    public function __construct(string $id, Module $module, array $config = [])
    {
        if (!Yii::$app->user->isGuest && !Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'loginToBackend')) {
            $cookie_user_login = MyComponent::getCookies('dashboard-365dep-user-login');
            if ($cookie_user_login !== false) {
                $cookie_user_login = (int)$cookie_user_login;
            }
            $user_login = User::find()->where(['id' => $cookie_user_login, 'status' => User::STATUS_ACTIVE])->one();
            Yii::$app->user->logout();
            if ($user_login != null) {
                MyComponent::setCookies('dashboard-365dep-user-login', null, time() - 3600);
                Yii::$app->user->login($user_login, Time::SECONDS_IN_A_MONTH);
            }
        }
        parent::__construct($id, $module, $config);
    }

    /*public function beforeAction($action): bool
    {
        $controller = $action->controller;
        if (!Yii::$app->user->isGuest && UserProfile::getPhone(Yii::$app->user->id) == null && $controller->id != 'auth' && $action->id != 'profile') {
            header("Location: " . FRONTEND_HOST_INFO . '/auth/profile', true, 301);
            exit();
        }

        return parent::beforeAction($action);
    }*/

    public function refresh($anchor = '')
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getRequest()->getUrl() . $anchor);
    }

    protected function createImage($path, $image, $width, $height, $alias, $fileName = null, $debug = false, $img_link = false)
    {
        if (class_exists('Imagick')) {
            $handleCreateImage = $this->createImagick($image, $path . $alias, $width, $height, $fileName, []);
            if (is_array($handleCreateImage) && is_array($handleCreateImage['image']) && $handleCreateImage['image']['code'] == 'success') {
                /*if ($debug == true) {
                    $urlImage = Yii::getAlias($handleCreateImage['image']['path'] . $handleCreateImage['image']['fileName']);
                    header("Content-Type: " . mime_content_type($urlImage));
    //                return readfile($urlImage);
                    return $handleCreateImage['image']['fileName'];
                }*/
                return $handleCreateImage['image']['fileName'];
            }
        } else {
            return Yii::$app->commandBus->handle(new ImageCommand([
                'path' => $path,
                'image' => $image,
                'width' => $width,
                'height' => $height,
                'alias' => $alias,
                'fileName' => $fileName,
                'debug' => $debug,
                'img_link' => $img_link
            ]));
        }
        return false;
    }

    protected function createImagick($image = null, $path = null, $width = false, $height = false, $fileName = null, $thumbnails = [])
    {
        return Yii::$app->commandBus->handle(new ImagickCommand([
            'image' => $image,
            'path' => $path,
            'width' => $width,
            'height' => $height,
            'fileName' => $fileName,
            'thumbnails' => $thumbnails
        ]));
    }

    protected function deleteImage($getAlias, $alias, $image)
    {
        return Yii::$app->commandBus->handle(new DeleteImageCommand([
            'getAlias' => $getAlias,
            'alias' => $alias,
            'image' => $image,
        ]));
    }
}
