<?php

namespace backend\modules\toothstatus\controllers;

use backend\modules\user\models\User;
use Yii;
use backend\modules\toothstatus\models\TinhTrangRang;
use backend\modules\toothstatus\models\search\SearchTinhTrangRang;
use backend\components\MyController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;
use yii\web\UploadedFile;

/**
 * TinhTrangRangController implements the CRUD actions for TinhTrangRang model.
 */
class TinhTrangRangController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new SearchTinhTrangRang();
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
        $model = new TinhTrangRang();

        $user = new User();
        $roleName = $user->getRoleName(Yii::$app->user->id);

        if(in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR]) || Yii::$app->user->can('bacsiBacsiUpdate')){
            $model->scenario = TinhTrangRang::SCENARIO_BACSI;
        }

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');

            if ($image != null) {
                $fileName = $image->baseName . '.' . $image->extension;
                $image->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName);

                $image = $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 150, 150, '/uploads/rang/tinh-trang-rang/150x150/', null, true, true);

                if ($image != false) {
                    $model->image = $image;
                    $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 300, 300, '/uploads/rang/tinh-trang-rang/300x300/', $image, true, true);
                    $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 600, 600, '/uploads/rang/tinh-trang-rang/600x600/', $image, true, true);
                }
                $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/tmp/', $fileName);
            }

            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-success'],
                    'class' => 'bg-success',
                ]);
            }

            return $this->refresh();
        } elseif (!\Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->get());
        }
        if (is_array($model->ky_thuat)) {
            $model->ky_thuat = ArrayHelper::map($model->ky_thuat, 'name', 'name');
        }

        return $this->render('create', [
            'model' => $model,
            'roleName' => $roleName
        ]);
    }

    /**
     * Updates an existing TinhTrangRang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $user = new User();
        $roleName = $user->getRoleName(Yii::$app->user->id);

        if(in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR]) || Yii::$app->user->can('bacsiBacsiUpdate')){
            $model->scenario = TinhTrangRang::SCENARIO_BACSI;
        }

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            $oldImage = $model->getOldAttribute('image');

            if ($image != null) {
                $fileName = $image->baseName . '.' . $image->extension;
                $image->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName);

                $image = $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 150, 150, '/uploads/rang/tinh-trang-rang/150x150/', null, true, true);

                if ($image != false) {
                    $model->image = $image;
                    $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 300, 300, '/uploads/rang/tinh-trang-rang/300x300/', $image, true, true);
                    $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 600, 600, '/uploads/rang/tinh-trang-rang/600x600/', $image, true, true);
                }
                if ($oldImage != null) {
                    $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/rang/tinh-trang-rang/150x150/', $oldImage);
                    $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/rang/tinh-trang-rang/300x300/', $oldImage);
                    $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/rang/tinh-trang-rang/600x600/', $oldImage);
                }
                $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/tmp/', $fileName);
            } else {
                $model->image = $oldImage;
            }

            if ($model->save() && $model->validate()) {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-success'],
                    'class' => 'bg-success',
                ]);
            }

            return $this->refresh();
        } elseif (!\Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->get());
        }
        if (is_array($model->kyThuatHasMany)) {
            $model->ky_thuat = ArrayHelper::map($model->kyThuatHasMany, 'name', 'name');
        }

        return $this->render('update', [
            'model' => $model,
            'roleName' => $roleName
        ]);
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            try {
                if ($this->findModel($id)->delete()) {
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

    public function actionShowHide()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');

            $model = $this->findModel($id);
            try {
                if ($model->status == 1) {
                    $model->status = 0;
                } else {
                    $model->status = 1;
                }
                if ($model->save()) {
                    echo 1;
                }
            } catch (\yii\db\Exception $exception) {
                echo 0;
            }
        }
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

    protected function findModel($id)
    {
        if (($model = TinhTrangRang::find()->where([TinhTrangRang::tableName().'.id' => $id])->joinWith(['kyThuatHasMany'])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
