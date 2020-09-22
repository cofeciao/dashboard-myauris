<?php

namespace backend\modules\user\controllers;

use backend\modules\user\models\UserSubRole;
use cheatsheet\Time;
use common\commands\SendEmailCommand;
use common\helpers\MyHelper;
use common\models\UserProfile;
use Yii;
use backend\modules\user\models\User;
use backend\modules\user\models\search\UserSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use backend\modules\user\models\RbacAuthAssignment;
use backend\components\MyComponent;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends MyController
{
    public $manager;

    public $userIdentityClass;

    public function init()
    {
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

        $keyDirectsale = 'get-nhan-vien-tu-direct-sale-clinic';
        $cache->delete($keyDirectsale);

        $this->manager = Yii::$app->authManager;

        if ($this->userIdentityClass === null) {
            $this->userIdentityClass = Yii::$app->user->identityClass;
        }
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }
        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
        ]);
    }

    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }

    public function actionView($id)
    {
        if (Yii::$app->request->isAjax && $this->findModel($id)) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new User();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                try {
                    $model->status = User::STATUS_ACTIVE;
                    $pass = MyHelper::randomString(10);
                    $model->setPassword($pass);
                    if ($model->save()) {
                        $model->afterSignup();
                        if (Yii::$app->commandBus->handle(new SendEmailCommand([
                            'subject' => Yii::t('frontend', 'Create Account'),
                            'view' => 'createAccount',
                            'to' => $model->email,
                            'params' => [
                                'user' => $model->username,
                                'pass' => $pass,
                            ]
                        ]))) {
                            return [
                                'status' => 200,
                                'mess' => Yii::$app->params['create-success'],
                            ];
                        } else {
                            return [
                                'status' => 403,
                                'mess' => 'Không thể gửi mail',
                            ];
                        }
                    }
                } catch (\yii\db\Exception $exception) {
                    return [
                        'status' => 400,
                        'mess' => Yii::$app->params['create-danger'],
                        'error' => $exception,
                    ];
                }
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $modelSubrole = UserSubRole::find()->where(['user_id' => $id])->one();
            $modelProfile = UserProfile::findOne($id); //->where(['user_id' => $id])->one();
            if ($modelSubrole == null) {
                $modelSubrole = new UserSubRole();
            }
            if ($modelSubrole->load(Yii::$app->request->post())) {
                $modelSubrole->user_id = $id;
                if ($modelSubrole->validate()) {
                    $modelSubrole->save();
                }
            }
            // Nghia 6/3/2020 -- cap nhat luu ten nhan vien
            if ($modelProfile->load(Yii::$app->request->post())) {
                if ($modelProfile->validate()) {
                    $modelProfile->save();
                }
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                try {
                    if ($model->save()) {
                        $user = new User();
                        $roleUser = (string)$user->getRoleName(Yii::$app->user->id);
                        $role = (string)$model->role_name;

                        if ($user->checkParent($role, $roleUser)) {
                            $role_name = $user->getRoleName($id);
                            $this->removeAssignment($role_name, $id);
                            $this->addAssign($role, $id);
                        } else {
                            return [
                                'status' => 403,
                                'mess' => 'Bạn không có quyền.',
                            ];
                        }
                        $cache = Yii::$app->cache;
                        $key = 'rbac-' . $id;
                        $cache->delete($key);

                        $keyNameDirectSale = 'get-full-name-direct-sale-user-profile-' . $id;
                        $cache->delete($keyNameDirectSale);

                        $keyPermissionUser = 'get-permission-user-' . $id;
                        $cache->delete($keyPermissionUser);

                        $key = 'get-user-created-by-or-update-by-' . $id;
                        $cache->delete($key);

                        return [
                            'status' => 200,
                            'mess' => Yii::$app->params['update-success'],
                        ];
                    }
                } catch (\yii\db\Exception $exception) {
                    return [
                        'status' => 400,
                        'mess' => Yii::$app->params['update-danger'],
                        'error' => $exception->getMessage(),
                    ];
                }
            }

            return $this->renderAjax('update', [
                'model' => $model,
                'modelSubrole' => $modelSubrole,
                'modelProfile' => $modelProfile
            ]);
        }
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            try {
                if ($this->findModel($id)->updateAttributes(['status' => User::STATUS_DELETED])) {
                    $cache = Yii::$app->cache;
                    $key = 'rbac-' . $id;
                    $cache->delete($key);

                    $keyNameDirectSale = 'get-full-name-direct-sale-user-profile-' . $id;
                    $cache->delete($keyNameDirectSale);

                    $keyPermissionUser = 'get-permission-user-' . $id;
                    $cache->delete($keyPermissionUser);

                    $key = 'get-user-created-by-or-update-by-' . $id;
                    $cache->delete($key);
                    return [
                        "status" => "success"
                    ];
                } else {
                    return [
                        "status" => "failure"
                    ];
                }
            } catch (\yii\db\Exception $e) {
                return [
                    "status" => "exception"
                ];
            }
        }

        return $this->redirect(['index']);
    }

    public function actionDeleteMultiple()
    {
        try {
            $action = Yii::$app->request->post('action');
            $selectCheckbox = Yii::$app->request->post('selection');
            if ($action === 'c') {
                if ($selectCheckbox) {
                    foreach ($selectCheckbox as $id) {
                        $this->findModel($id)->delete();
                    }
                    \Yii::$app->session->setFlash('indexFlash', 'Bạn đã xóa thành công.');
                }
            }
        } catch (\yii\db\Exception $e) {
            if ($e->errorInfo[1] == 1451) {
                throw new \yii\web\HttpException(400, 'Failed to delete the object.');
            } else {
                throw $e;
            }
        }
        return $this->redirect(['index']);
    }

    public function actionResetPassword()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
            $pass = MyHelper::randomString(10);
            $model->setPassword($pass);
            try {
                if ($model->save()) {
                    return [
                        "status" => "success",
                        'pass' => $pass,
                    ];
                } else {
                    return [
                        "status" => "failure"
                    ];
                }
            } catch (\yii\db\Exception $e) {
                return [
                    "status" => "exception"
                ];
            }
            return ['id' => $id];
        }
    }

    public function actionLoginWithUser($id = null)
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $user = new User();
            $roleName = $user->getRoleName(Yii::$app->user->id);
            if (!in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                return [
                    'code' => 403,
                    'msg' => 'Bạn không có quyền sử dụng chức năng này'
                ];
            }
            $user = User::find()->where(['id' => $id, 'status' => User::STATUS_ACTIVE])->one();
            if ($user == null) {
                return [
                    'code' => 404,
                    'msg' => 'Người dùng không tồn tại'
                ];
            }
            if (!Yii::$app->authManager->checkAccess($user->id, 'loginToBackend')) {
                return [
                    'code' => 400,
                    'msg' => 'Người dùng không có quyền truy cập backend'
                ];
            }
            MyComponent::setCookies('dashboard-365dep-user-login', Yii::$app->user->id);
            Yii::$app->user->logout();
            Yii::$app->user->login($user, Time::SECONDS_IN_A_MONTH);
            return [
                'code' => 200,
                'msg' => 'Đăng nhập thành công'
            ];
        }
        return $this->redirect(['index']);
    }

    public function removeAssignment(string $role, int $id): bool
    {
        $item = $this->manager->getRole($role);
        $item = $item ?: $this->manager->getPermission($role);
        $this->manager->revoke($item, $id);
        return true;
    }

    public function addAssign(string $role, int $id): bool
    {
        $item = $this->manager->getRole($role);
        $item = $item ?: $this->manager->getPermission($role);
        $this->manager->assign($item, $id);

        return true;
    }


    protected function findModel($id)
    {
        if ($id == 1) {
            Yii::$app->session->setFlash('alert', [
                'body' => 'Bạn không có quyền',
                'class' => 'bg-danger',
            ]);
            return false;
        }

        /*
         * Nếu chia user theo từng admin thì thêm findUser
         */
//        $model = User::find()->where(['id' => $id])->findUser()->one();
        $user = new User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);

        $model = User::find()->where(['id' => $id]);
        $model->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id');
        $model->andWhere(['!=', 'item_name', $roleUser]);


        $model = $model->one();
        if ($model !== null) {
            return $model;
        }

        Yii::$app->session->setFlash('alert', [
            'body' => Yii::$app->params['find-data-not-success'],
            'class' => 'bg-danger',
        ]);

        return false;
    }

    protected function findAssignmentModel(int $id)
    {
        $class = $this->userIdentityClass;
        $user = $class::findIdentity($id);
        if ($user !== null) {
            return new RbacAuthAssignment($user);
        }

        Yii::$app->session->setFlash('alert', [
            'body' => Yii::$app->params['find-data-not-success'],
            'class' => 'bg-danger',
        ]);

        return false;
    }




}
