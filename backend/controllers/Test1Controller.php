<?php

namespace backend\controllers;

use backend\modules\clinic\controllers\ChupBanhMoiController;
use tpmanc\imagick\Imagick;
use Yii;
use backend\models\Test;
use backend\models\search\TestSearch;
use backend\components\MyController;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TestController implements the CRUD actions for Test model.
 */
class Test1Controller extends MyController
{
    public function actionIndex()
    {
        /*$url = Yii::$app->basePath . '/web/uploads/tmp/icon-nha-thau.png';
        $handleImage = $this->createImage('@backend/web', $url, null, null, '/uploads/customer/nguyen-ba-dung-41243/' . ChupBanhMoiController::FOLDER . '/', 'icon-nha-thau-nqusj-1584676143.png');
        var_dump($handleImage);
        die;*/
        return $this->render('index', [
        ]);
    }

    public function actionPerpage($perpage)
    {
        $cookies = Yii::$app->response->cookies;
        if ($cookies->add(new \yii\web\Cookie([
            'name' => 'pageSize',
            'value' => $perpage,
        ]))) ;
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
        $model = new Test();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                if ($model->save()) {
                    return $this->refresh();
                } else {
                }
            } catch (\yii\db\Exception $exception) {
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Test model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('formFlash', 'Bạn đã cập nhật thành công.');
            if (Yii::$app->request->referrer) {
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                return $this->goHome();
            }
        }

        return $this->render('update', [
            'model' => $model,
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
                } else {
                    echo 0;
                }
            } catch (\yii\db\Exception $exception) {
                echo -1;
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
        if (($model = Test::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
