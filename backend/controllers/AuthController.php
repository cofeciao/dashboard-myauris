<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use backend\components\Email;
use backend\components\MyComponent;
//use backend\components\MyController;
use yii\web\Controller;
use backend\models\auth\AuthenticationLoginForm;
use backend\models\auth\AuthForm;
use backend\models\auth\ChangePassWordForm;
use backend\models\auth\RequestPasswordResetForm;
use backend\models\auth\ResetPasswordForm;
use backend\models\auth\UserAuth;
use backend\models\auth\UserReliableEquipment;
use backend\models\AuthSmsModel;
use backend\models\LoginForm;
use backend\models\UserSettings;
use backend\modules\log\models\Dep365SendSms;
use backend\modules\setting\models\Setting;
use backend\modules\user\models\User;
use cheatsheet\Time;
use common\commands\SendEmailCommand;
use common\helpers\MyHelper;
use common\models\UserProfile;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class AuthController extends Controller
{
    const ACTION_LOGIN = ['choose-user' => '_list-user', 'login' => '_login', 'authentication' => '_authentication'];

    public function init()
    {
        parent::init();
        $cache = Yii::$app->cache;
        $key = 'resdis-get-nhanvien-tu-van-filter';
        $cache->delete($key);

        $key_direct = 'resdis-get-nhanvien-direct-sale';
        $cache->delete($key_direct);

        $key_chay_ads = 'redis-get-nhan-vien-chay-advertising';
        $cache->delete($key_chay_ads);

        $key_bacsi = 'redis-get-ekip-bac-si';
        $cache->delete($key_bacsi);

        $key_active = 'resdis-get-nhanvien-tu-van-active';
        $cache->delete($key_active);
    }

    public function actionIndex()
    {
        return $this->redirect('auth/profile');
    }

    public function actionPermission()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->user->id;
            $user = new User();
            $roleUser = $user->getRoleName($id);
            Yii::$app->response->format = Response::FORMAT_JSON;
            $roleNumber = 0;
            if ($roleUser == User::USER_MANAGER_ONLINE || $roleUser == User::USER_NHANVIEN_ONLINE || $roleUser == User::USER_ADMINISTRATOR || $roleUser == User::USER_DEVELOP) {
                $roleNumber = 1;
            }
            return ['status' => '200', 'role' => $roleNumber];
        }
    }

    public function actionProfile()
    {
        $user = new User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);
        $model = UserProfile::findOne(Yii::$app->user->id);
        if ($roleUser == User::USER_MANAGER_ONLINE || $roleUser == User::USER_NHANVIEN_ONLINE) {
            $model->scenario = UserProfile::SCENARIO_PANCAKE;
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // upload avatar
            $avatar = UploadedFile::getInstance($model, 'avatar');
            $oldAvatar = $model->getOldAttribute('avatar');
            if ($avatar != null) {
                $fileName = MyHelper::createAlias($avatar->baseName) . '.' . $avatar->extension;
                if (!$avatar->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName)) {
                    return [
                        'code' => 403,
                        'msg' => 'Upload file thất bại!',
                    ];
                }
                $urlImage = Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName;
                $model->avatar = $this->createImage('@backend/web', $urlImage, 200, 200, '/uploads/user/avatar/200x200/', null, true, true);
                $this->createImage('@backend/web', $urlImage, 70, 70, '/uploads/user/avatar/70x70/', $model->avatar, true, true);
                if ($oldAvatar != null) {
                    $this->deleteImage(Yii::getAlias('@backend/web'), '/uploads/user/avatar/200x200/', $oldAvatar);
                    $this->deleteImage(Yii::getAlias('@backend/web'), '/uploads/user/avatar/70x70/', $oldAvatar);
                }
            } else {
                $model->avatar = $oldAvatar;
            }

            // upload background login
            $cover = UploadedFile::getInstance($model, 'cover');
            $oldCover = $model->getOldAttribute('cover');
            if ($cover != null) {
                $fileName = MyHelper::createAlias($cover->baseName) . '.' . $cover->extension;
                if (!$cover->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName)) {
                    return [
                        'code' => 403,
                        'msg' => 'Upload file thất bại!',
                    ];
                }
                $urlImage = Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName;
                $model->cover = $this->createImage('@backend/web', $urlImage, null, null, '/uploads/user/cover/', null, true, true);
                if ($oldCover != null) {
                    $this->deleteImage(Yii::getAlias('@backend/web'), '/uploads/user/cover/', $oldCover);
                }
            } else {
                $model->cover = $oldCover;
            }

            if ($model->save()) {
                \Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['update-success'],
                    'class' => 'bg-success',
                ]);
            }
            return $this->refresh();
        }
        return $this->render('profile', [
            'model' => $model
        ]);
    }

    public function actionChangePassWord()
    {
        $id = \Yii::$app->user->id;
        $model = new ChangePassWordForm($id);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->changePassword()) {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['change-password-success'],
                    'class' => 'bg-success',
                ]);
            } else {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['change-password-error'],
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        }

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

//            $this->layout = '@backend/views/layouts/login';
            $model = new RequestPasswordResetForm();

            if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
                return [
                    'code' => 400,
                    'msg' => 'Thất bại. Vui lòng liên hệ bộ phận kỹ thuật',
                ];
            }

            Email::sendEmail($model->email);
            return [
                'code' => 200,
                'msg' => 'Thành công. Vui lòng kiểm tra email và làm theo hướng dẫn.',
            ];
        }

        return $this->render('requestPasswordResetFormToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $this->layout = '@backend/views/layouts/login';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $exception) {
            return $this->redirect('/auth/login');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->resetPassword()) {
                Yii::$app->session->setFlash('alert', [
                    'body' => 'Thành công. Vui lòng đăng nhập lại',
                    'class' => 'bg-success',
                ]);
            } else {
                Yii::$app->session->setFlash('alert', [
                    'body' => 'Thất bại. Vui lòng liên hệ bộ phận kỹ thuật',
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionError()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/auth/login');
        } else {
            $exception = Yii::$app->errorHandler->exception;
            if ($exception !== null) {
                return $this->render('error', ['exception' => $exception]);
            }
        }
    }

    /*public function actionLogin($step = null, $user = null)
    {
//        MyComponent::setCookies('listUserReliable', []);
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!MyComponent::hasCookies('listUser')) {
            MyComponent::setCookies('listUser', []);
        }
        $listUser = MyComponent::getCookies('listUser');
        if (!is_array($listUser)) $listUser = [];

        if (!MyComponent::hasCookies('listUserReliable')) {
            MyComponent::setCookies('listUserReliable', []);
        }
        $listUserReliable = MyComponent::getCookies('listUserReliable');
        if (!is_array($listUserReliable)) $listUserReliable = [];

        if ($step == null || !array_key_exists($step, self::ACTION_LOGIN)) $step = 'choose-user';
        $view = self::ACTION_LOGIN[$step];
        $params = [];
        switch ($step) {
            case 'authentication':
                $userLogin = User::find()->where(['or', ['username' => $user], ['email' => $user]])->joinWith(['userProfile'])->one();
                if ($userLogin == null) {
                    Yii::$app->session->setFlash('alert', [
                        'class' => 'alert-warning',
                        'body' => 'Không tìm thấy người dùng'
                    ]);
                    return $this->redirect(['login']);
                }
                $rowAuth = AuthSmsModel::find()->where(['user_id' => $userLogin->primaryKey, 'type' => AuthSmsModel::CUSTOMER_LOGIN])->andWhere(['>', 'expire_at', time()])->andWhere(['<>', 'used', AuthSmsModel::USED])->orderBy(['id' => SORT_DESC])->one();

                if ($rowAuth == null) {
                    Yii::$app->session->setFlash('alert', [
                        'class' => 'alert-warning',
                        'body' => 'Vui lòng đăng nhập'
                    ]);
                    return $this->redirect(['login']);
                }
                $model = new AuthenticationLoginForm();
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->validate()) {
                        if ($model->pin != $rowAuth->pin) {
                            Yii::$app->session->setFlash('alert', [
                                'class' => 'alert-warning',
                                'body' => 'Mã đăng nhập không chính xác'
                            ]);
                            return $this->refresh();
                        } else {
                            if (Yii::$app->user->login($userLogin, Time::SECONDS_IN_A_MONTH)) {
                                $rowAuth->updateAttributes(['used' => AuthSmsModel::USED]);
                                $this->saveLogLogin(Yii::$app->user->getId());
                                if (!Yii::$app->user->can('loginToBackend')) {
                                    Yii::$app->user->logout();
                                    return false;
                                }
                                $listUser[$userLogin->username] = [
                                    'name' => $userLogin->userProfile->fullname,
                                    'email' => $userLogin->email,
                                ];
                                MyComponent::setCookies('listUser', $listUser);
                                return $this->redirect(['reliable-equipment']);
                            }
                        }
                    }
                }
                $params['model'] = $model;
                $params['user'] = $user;
                $params['userLogin'] = [
                    'name' => $userLogin->userProfile->fullname,
                    'email' => $userLogin->email
                ];
                break;
            case 'login':
                $userLogin = null;
                if ($user != null && array_key_exists($user, $listUser)) $userLogin = $listUser[$user];
                $model = new LoginForm();
                if ($userLogin != null) $model->username = $user;
                $params['model'] = $model;
                $params['userLogin'] = $userLogin;
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->validate()) {
                        $userLogin = User::find()->where(['or', ['username' => $model->username], ['email' => $model->username]])->joinWith(['userProfile'])->one();
                        $userSettings = UserSettings::find()->where(['user_id' => $userLogin->primaryKey])->one();
                        if (in_array($userLogin->id, $listUserReliable) || $userSettings == null || ($userSettings->auth_message != UserSettings::ACCEPT_AUTH_MESSAGE && $userSettings->auth_mail != UserSettings::ACCEPT_AUTH_MAIL)) {
                            if ($userSettings != null && ($userSettings->auth_message != UserSettings::ACCEPT_AUTH_MESSAGE || $userSettings->auth_mail != UserSettings::ACCEPT_AUTH_MAIL)) {
                                $this->saveLogLogin($userLogin->id);
                            }
                            if (Yii::$app->user->login($userLogin, Time::SECONDS_IN_A_MONTH)) {
                                return $this->redirect(['site/index']);
                            } else {
                                Yii::$app->session->setFlash('alert', [
                                    'class' => 'alert-warning',
                                    'body' => 'Đăng nhập thất bại'
                                ]);
                                return $this->refresh();
                            }
                        } else {
                            $check = false;
                            $pin = rand(100000, 999999);
                            if ($this->saveLoginPin($userLogin->primaryKey, $pin)) {
                                if ($userSettings->auth_message) if ($this->sendSms($userLogin->primaryKey, $pin)) $check = true;
                                if ($userSettings->auth_mail) if ($this->sendMail($userLogin->primaryKey, $pin)) $check = true;
                                if ($check == true) {
                                    return $this->redirect(['login', 'step' => 'authentication', 'user' => $model->username]);
                                } else {
                                    Yii::$app->session->setFlash('alert', [
                                        'class' => 'alert-warning',
                                        'body' => 'Gửi mã đăng nhập thất bại'
                                    ]);
                                    return $this->refresh();
                                }
                            } else {
                                Yii::$app->session->setFlash('alert', [
                                    'class' => 'alert-warning',
                                    'body' => 'Gửi mã đăng nhập thất bại'
                                ]);
                                return $this->refresh();
                            }
                        }
                    }
                }
                break;
            case 'choose-user':
            default:
                if (count($listUser) <= 0) return $this->redirect(['login', 'step' => 'login']);
                $params['listUser'] = $listUser;
        }

        return $this->render('login', [
            'view' => $view,
            'params' => $params,
        ]);
    }*/

    public function actionLogin()
    {
//        MyComponent::setCookies('listUserReliable', []);
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        $modelAuth = new AuthForm();
        $modelResetPass = new RequestPasswordResetForm();
        return $this->render('login', [
            'model' => $model,
            'modelAuth' => $modelAuth,
            'modelResetPass' => $modelResetPass,
        ]);
    }

    public function actionValidateLogin()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post())) {
                return \yii\bootstrap\ActiveForm::validate($model);
            }
        }
    }

    public function actionSubmitLogin()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $auth = "";
            $model = new LoginForm();
//            $model->scenario = LoginForm::SCENARIO_SUBMIT_LOGIN;
            if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
                return [
                    'code' => 400,
                    'msg' => 'Lỗi dữ liệu',
                    'error' => $model->getErrors()
                ];
            }

            if (!MyComponent::hasCookies('listUserReliable')) {
                MyComponent::setCookies('listUserReliable', []);
            }
            $listUserReliable = MyComponent::getCookies('listUserReliable');
            if (!is_array($listUserReliable)) {
                $listUserReliable = [];
            }
            $user = User::find()->andWhere(['OR', ['username' => $model->username], ['email' => $model->username]])->one();
            $userLogin = User::find()->where(['or', ['username' => $model->username], ['email' => $model->username]])->andWhere(['status' => User::STATUS_ACTIVE])->joinWith(['userProfile'])->one();
            $userSettings = null;
            if ($userLogin != null) {
                $userSettings = UserSettings::find()->where(['user_id' => $userLogin->primaryKey])->one();
            }

            if (in_array($userLogin->id, $listUserReliable) || $userSettings == null || ($userSettings->auth_message != UserSettings::ACCEPT_AUTH_MESSAGE && $userSettings->auth_mail != UserSettings::ACCEPT_AUTH_MAIL)) {
                if ($userSettings != null && ($userSettings->auth_message != UserSettings::ACCEPT_AUTH_MESSAGE || $userSettings->auth_mail != UserSettings::ACCEPT_AUTH_MAIL)) {
                    $this->saveLogLogin($userLogin->id);
                }
                if (!Yii::$app->user->login($userLogin, Time::SECONDS_IN_A_MONTH)) {
                    return [
                        'code' => 403,
                        'msg' => 'Đăng nhập thất bại'
                    ];
                } else {
                    if (!Yii::$app->user->can('loginToBackend')) {
                        Yii::$app->user->logout();
                        return [
                            'code' => 403,
                            'msg' => 'Bạn không có quyền truy cập trang'
                        ];
                    }
                    $msg = 'Đăng nhập thành công.';
                }
            } else {
                $auth = 'true';
                $check = false;
                $pin = rand(100000, 999999);
                if ($this->saveLoginPin($userLogin->primaryKey, $pin)) {
                    if ($userSettings->auth_message) {
                        if ($this->sendSms($userLogin->primaryKey, $pin)) {
                            $check = true;
                        }
                    }
                    if ($userSettings->auth_mail) {
                        if ($this->sendMail($userLogin->primaryKey, $pin)) {
                            $check = true;
                        }
                    }
                    if ($check == true) {
                        $msg = 'Chúng tôi đã gửi mã xác thực cho bạn. Vui lòng kiểm tra email/điện thoại!';
                    } else {
                        return [
                            'code' => 400,
                            'msg' => 'Gửi mã xác thực đăng nhập thất bại'
                        ];
                    }
                } else {
                    return [
                        'code' => 400,
                        'msg' => Yii::t('backend', 'Có lỗi xảy ra, vui lòng thử lại sau')
                    ];
                }
            }
            return [
                'code' => 200,
                'msg' => $msg,
                'data' => [
                    'username' => $user->username,
                    'name' => $user->userProfile != null && $user->userProfile->fullname != null ? $user->userProfile->fullname : $user->username,
                    'avatar' => (
                    $user->userProfile != null && $user->userProfile->avatar != null && file_exists(Yii::getAlias('@backend/web') . '/uploads/user/avatar/200x200/' . $user->userProfile->avatar)
                        ? Yii::getAlias('@frontendUrl') . '/uploads/user/avatar/200x200/' . $user->userProfile->avatar
                        : Yii::getAlias('@frontendUrl') . '/images/default/avatar-default.png'
                    ),
                    'background' => (
                    $user->userProfile != null && $user->userProfile->cover != null && file_exists(Yii::getAlias('@backend/web') . '/uploads/user/cover/' . $user->userProfile->cover)
                        ? Yii::getAlias('@frontendUrl') . '/uploads/user/cover/' . $user->userProfile->cover
                        : Yii::getAlias('@frontendUrl') . '/images/default/background-login-default.jpg'
                    ),
                    'auth' => $auth
                ]
            ];
        }
    }

    public function actionValidateAuth()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new AuthForm();
            if ($model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionSubmitAuth()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new AuthForm();
            if (!$model->load(Yii::$app->request->post())) {
                return [
                    'code' => 400,
                    'msg' => 'Lỗi dữ liệu'
                ];
            }
            if (!$model->validate()) {
                return [
                    'code' => 400,
                    'msg' => 'Lỗi dữ liệu',
                    'error' => $model->getErrors()
                ];
            }
            $auth = UserAuth::find()->joinWith(['userHasOne'])->where(['pin' => $model->auth, 'used' => UserAuth::NOT_USED])->andWhere(['OR', [User::tableName() . '.username' => $model->username], [User::tableName() . '.email' => $model->username]])->one();
            if ($auth == null) {
                return [
                    'code' => 400,
                    'msg' => 'Mã xác thực không tồn tại hoặc đã hết hạn',
                ];
            }
            if (!$model->login()) {
                return [
                    'code' => 400,
                    'msg' => 'Đăng nhập thất bại'
                ];
            }
            $auth->updateAttributes([
                'used' => UserAuth::USED
            ]);
            return [
                'code' => 200,
                'msg' => 'Đăng nhập thành công'
            ];
        }
    }

    public function actionResendPin($user)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $code = 200;
            $msg = 'Chúng tôi đã gửi mã xác thực cho bạn. Vui lòng kiểm tra email/điện thoại!';
            $userLogin = User::find()->where(['or', ['username' => $user], ['email' => $user]])->one();
            if ($userLogin == null) {
                $code = 400;
                $msg = 'Không tìm thấy người dùng';
            } else {
                $check = false;
                $pin = rand(100000, 999999);
                if ($this->saveLoginPin($userLogin->primaryKey, $pin)) {
                    $userSettings = UserSettings::find()->where(['user_id' => $userLogin->primaryKey])->one();
                    if ($userSettings->auth_message) {
                        if ($this->sendSms($userLogin->primaryKey, $pin)) {
                            $check = true;
                        }
                    }
                    if ($userSettings->auth_mail) {
                        if ($this->sendMail($userLogin->primaryKey, $pin)) {
                            $check = true;
                        }
                    }
                    if ($check != true) {
                        $code = 403;
                        $msg = 'Gửi lại mã xác thực thất bại!';
                    }
                } else {
                    $code = 403;
                    $msg = 'Gửi lại mã xác thực thất bại!';
                }
            }
            return [
                'code' => $code,
                'msg' => $msg
            ];
        }
    }

    public function actionLogout()
    {
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
        return $this->goHome();
    }

    protected function createJsonSms($content, $phone)
    {
        $brandname = '';
        $api_key = '';
        $api_secret = '';

        $cache = Yii::$app->cache;
        $key = 'redis-get-vht-send-sms';
        $setting = $cache->get($key);
        if ($setting === false) {
            $setting = Setting::find()->where(['in', 'id', [1, 2, 3]])->all();
            $cache->set($key, $setting);
        }

        foreach ($setting as $value) {
            if ($value->id == 1) {
                $brandname = $value->value;
            }
            if ($value->id == 2) {
                $api_key = $value->value;
            }
            if ($value->id == 3) {
                $api_secret = $value->value;
            }
        }
        $param = [
            'submission' => [
                'api_key' => $api_key,
                'api_secret' => $api_secret,
                'sms' => [
                    [
                        'id' => '0',
                        'brandname' => $brandname,
                        'text' => $content,
                        'to' => $phone,
                    ]
                ],
            ],
        ];
        return json_encode($param);
    }

    protected function saveLoginPin($userId, $pin)
    {
        $sms = UserAuth::find()->where(['user_id' => $userId])->one();
        if ($sms == null) {
            $sms = new UserAuth();
            $sms->user_id = $userId;
            $sms->type = UserAuth::TYPE_USER_LOGIN;
        }
        $sms->pin = $pin;
        $sms->used = 0;
        if ($sms->save()) {
            return true;
        } else {
            var_dump($sms->getErrors());
            return false;
        }
    }

    protected function sendSms($userId, $pin)
    {
        $status = 100;
        $user = User::find()->where(['id' => $userId])->one();
        $phone = $user->userProfile->phone;
        if ($user == null || $phone == null || $phone == '') {
            return false;
        }
        $content = 'Mã đăng nhập vào hệ thống của bạn: ' . $pin;
        $url = 'http://sms3.vht.com.vn/ccsms/Sms/SMSService.svc/ccsms/json';

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        try {
            $response = $client->request('POST', $url, [
                'body' => $this->createJsonSms($content, $phone)
            ]);

            $body = $response->getBody();
            $body = json_decode($body);
            foreach ($body as $key => $items) {
                foreach ($items as $keys => $values) {
                    foreach ($values as $keyss => $item) {
                        foreach ($item as $keysss => $value) {
                            $status = $value->status;
                        }
                    }
                }
            }

            if ($status != 0) {
                return false;
            }
            return true;
        } catch (ClientException $e) {
            return false;
//            return $e->getRequest();
//            return $e->getResponse();
        }
    }

    protected function sendMail($userId, $pin)
    {
        $user = User::find()->where(['id' => $userId])->one();
        $content = 'Mã đăng nhập vào hệ thống của bạn: ' . $pin;
        return \Yii::$app->commandBus->handle(new SendEmailCommand([
            'to' => $user->email,
            'subject' => 'Đăng nhập vào hệ thống',
            'body' => $content
        ]));
    }

    protected function saveLogLogin($userId)
    {
        try {
            $ip = Yii::$app->geoip->ip($_SERVER['REMOTE_ADDR']);
            $modelReliable = new UserReliableEquipment();
            $modelReliable->user_id = $userId;
            $modelReliable->computer_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $modelReliable->city = isset($ip->city) ? $ip->city : null;
            $modelReliable->country = isset($ip->country) ? $ip->country : null;
            $modelReliable->lat = isset($ip->lat) ? $ip->lat : null;
            $modelReliable->lng = isset($ip->ln) ? $ip->ln : null;
            $modelReliable->isoCode = isset($ip->isoCode) ? $ip->isoCode : null;
            return $modelReliable->save();
        } catch (Exception $ex) {
            return false;
        }
    }

    public function actionReliableEquipment($accept = null)
    {
        $this->layout = 'login';

        if (!MyComponent::hasCookies('listUserReliable')) {
            MyComponent::setCookies('listUserReliable', []);
        }
        $listUserReliable = MyComponent::getCookies('listUserReliable');
        if (!is_array($listUserReliable)) {
            $listUserReliable = [];
        }
        if (in_array(Yii::$app->user->getId(), $listUserReliable)) {
            return $this->redirect(['site/index']);
        }

        if ($accept != null) {
            $listUserReliable[] = Yii::$app->user->getId();
            MyComponent::setCookies('listUserReliable', $listUserReliable);
            return $this->redirect(['site/index']);
        }
        return $this->render('reliable-equipment', [
        ]);
    }
}
