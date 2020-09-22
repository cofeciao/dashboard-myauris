<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 16-Nov-18
 * Time: 11:49 AM
 */

namespace backend\modules\user\components;

use backend\modules\user\models\User;
use Yii;
use backend\modules\user\models\RbacAuthItem;
use backend\modules\user\models\search\RbacAuthItemSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\rbac\Item;
use yii\web\Response;
use backend\components\MyComponent;

class RbacAuthItemComponents extends MyController
{
    public $searchClass = [
        'class' => RbacAuthItemSearch::class,
    ];

    protected $type;

    public function actionIndex()
    {
        $user = new User();
        $roleName = $user->getRoleName(Yii::$app->user->id);
        $searchModel = Yii::createObject($this->searchClass);
        $searchModel->type = $this->type;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        unset($dataProvider->allModels['loginToBackend']);
        unset($dataProvider->allModels[$roleName]);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 20;
        }
        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'totalPage' => $totalPage,
        ]);
    }

    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }

    public function actionView($id)
    {
        if ($this->findModel($id)) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new RbacAuthItem();
            $model->type = $this->type;

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                if ($model->save()) {
                    return [
                        'status' => 200,
                        'mess' => Yii::$app->params['create-success'],
                    ];
                } else {
                    return [
                        'status' => 403,
                        'mess' => Yii::$app->params['create-danger'],
                        'error' => $model->getErrors(),
                    ];
                }
            }

            return $this->renderAjax('create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing RbacAuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                if ($model->validate()) {
                    try {
                        $model->save();
                        return [
                            'status' => 200,
                            'mess' => Yii::$app->params['update-success'],
                        ];
                    } catch (\yii\db\Exception $exception) {
                        return [
                            'status' => 400,
                            'mess' => Yii::$app->params['update-danger'],
                            'error' => $exception->getMessage(),
                        ];
                    }
                } else {
                    return [
                        'status' => 403,
                        'mess' => Yii::$app->params['update-danger'],
                        'error' => $model->getErrors(),
                    ];
                }
            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            $name = Yii::$app->request->post('name');
            $model = $this->findModel($name);
            if (!$model) {
                Yii::$app->session->setFlash('alert', [
                    'body' => 'Bạn không có quyền',
                    'class' => 'bg-danger',
                ]);
                return $this->refresh();
            }

            try {
                if (Yii::$app->getAuthManager()->remove($model->item)) {
                    return 'success';
                } else {
                    return 'failure';
                }
            } catch (\yii\db\Exception $e) {
                return $this->redirect('/site');
            }
        }

        return $this->redirect(['index']);
    }

    public function actionPermissionForRole()
    {
        if (Yii::$app->request->isAjax) {
            $result = [];
            $name = Yii::$app->request->get('name');
            $permission = Yii::$app->authManager->getPermissions();
            $permission_user = array_keys(Yii::$app->authManager->getPermissionsByRole($name));

            unset($permission['loginToBackend']);
            unset($permission_user['loginToBackend']);

            foreach ($permission as $name) {
                if (in_array($name->name, $permission_user)) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $result[] = [
                    'name' => $name->name,
                    'description' => $name->description,
                    'selected' => $selected
                ];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }
    }

    public function actionPermissionChange()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $itemAssign = [];
        $name = Yii::$app->request->post('name');
        $item = Yii::$app->request->post('item');

        if ($item != null && count($item) != 0) {
            foreach ($item as $value) {
                $itemAssign[] = $value;
            }
        }

        $permission_user = Yii::$app->authManager->getPermissionsByRole($name);
        unset($permission_user["loginToBackend"]);
        $permission_user = array_keys($permission_user);

        $model = $this->findModel($name);

        if (count($itemAssign) > count($permission_user)) {
            $itemAdd = array_diff($itemAssign, $permission_user);
            if ($model->addChildren($itemAdd)) {
                return 1;
            }
        } elseif (count($itemAssign) < count($permission_user)) {
            $itemRemove = array_diff($permission_user, $itemAssign);
            if ($model->removeChildren($itemRemove)) {
                return 1;
            }
        }
        return 0;
    }

    public function actionAssign(string $id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        if ($model->addChildren($items)) {
            return true;
        }
    }

    public function actionRemove(string $id): array
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        if ($model->removeChildren($items)) {
            return true;
        }
    }

    protected function findModel($name)
    {
        $auth = Yii::$app->authManager;

        $user = new User();

        if (!$user->checkParent($name, $user->getRoleName(Yii::$app->user->id))) {
            Yii::$app->session->setFlash('alert', [
                'body' => 'Bạn không có quyền.',
                'class' => 'bg-danger',
            ]);
            return false;
        }

        $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($name) : $auth->getPermission($name);

        if (empty($item)) {
            return false;
        }
        if ($item->name == 'loginToBackend' || $item->name == $user->getRoleName(Yii::$app->user->id)) {
            header("Location: " . FRONTEND_HOST_INFO, true, 301);
            exit();
        }

        return new RbacAuthItem($item);
    }
}
