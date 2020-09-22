<?php

namespace backend\modules\testab\controllers;

use backend\components\MyComponent;
use backend\modules\testab\models\AbCampaign;
use backend\modules\testab\models\search\AbCampaignSearch;
use backend\modules\user\models\User;
use function GuzzleHttp\Psr7\str;
use Yii;
use backend\modules\testab\models\Campaign;
use backend\modules\testab\models\search\CampaignSeach;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;

/**
 * ChienDichController implements the CRUD actions for Campaign model.
 */
class ChienDichController extends MyController
{
    public function actionIndex($idCD = null)
    {
        $id = '';

        $searchModelCD = new CampaignSeach();

        $dataProviderCD = $searchModelCD->search(Yii::$app->request->queryParams);

        $dataProviderCD->pagination->pageSize = 10;

        $models = $dataProviderCD->getModels();

        $first = reset($models);

        if ($first != false) {
            $id = $first->id;
        }

        $searchModel = new AbCampaignSearch();

        if ($idCD != null) {
            $id = $idCD;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

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
            'searchModelCD' => $searchModelCD,
            'dataProviderCD' => $dataProviderCD,
            'id' => $id,
            'totalPage' => $totalPage,
        ]);
    }

    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }

    public function actionView($id)
    {
        $view = $this->findModelCampaign($id);
        if ($view) {
            $searchModelCD = new CampaignSeach();
            $dataProviderCD = $searchModelCD->search(Yii::$app->request->queryParams);
            $dataProviderCD->pagination->pageSize = 100;
            $models = $dataProviderCD->getModels();
            $first = reset($models);
            $id = $first->id;

            $chienDich = Campaign::find()->where(['id' => $id])->one();

            return $this->render('view', [
                'model' => $view,
                'searchModelCD' => $searchModelCD,
                'dataProviderCD' => $dataProviderCD,
                'id' => $view->id,
                'chienDich' => $chienDich
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionCreate()
    {
        $checkCampaign = AbCampaign::find()->where('end_date is null')->all();
        if ($checkCampaign != null) {
            Yii::$app->session->setFlash('alert', [
                'body' => 'Bạn không thể tạo mới khi chiến dịch cũ chưa kết thúc',
                'class' => 'bg-danger',
            ]);
            return $this->redirect(['index']);
        }

        $model = new Campaign();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->save();
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-success'],
                    'class' => 'bg-success',
                ]);
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-danger'],
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateAjax()
    {
        if (Yii::$app->request->isAjax) {
            $checkCampaign = AbCampaign::find()->where('end_date is null')->all();
            if ($checkCampaign != null) {
                return $this->renderAjax('create-ajax', [
                    'error' => 'Bạn không thể tạo mới khi chiến dịch cũ chưa kết thúc',
                ]);
            }

            $model = new Campaign();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                if ($model->save()) {
                    return [
                        'status' => 200,
                        'mess' => Yii::$app->params['create-success'],
                        'error' => $model->getErrors(),
                    ];
                } else {
                    return [
                        'status' => 403,
                        'mess' => Yii::$app->params['create-danger'],
                        'error' => $model->getErrors(),
                    ];
                }
            }

            return $this->renderAjax('create-ajax', [
                'model' => $model,
            ]);
        }
    }

    public function actionCheckValidationAbcampaign($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new AbCampaign();
        if ($model->load(Yii::$app->request->post())) {
            $model->id = $id;
            $listInteger = ['chiphi_thucchay', 'comment', 'tin_nhan', 'hien_thi', 'tiep_can', 'nguoi_xem_1', 'nguoi_xem_50'];
            foreach ($listInteger as $i) {
                $model->$i = trim(str_replace('.', '', $model->$i));
            }
            return ActiveForm::validate($model);
        }
    }

    public function actionCreateCampaign($cid)
    {
        $model = new AbCampaign();

        $searchModelCD = new CampaignSeach();
        $dataProviderCD = $searchModelCD->search(Yii::$app->request->queryParams);
        $dataProviderCD->pagination->pageSize = 50;
        $models = $dataProviderCD->getModels();
        $first = reset($models);
        $id = $cid;

        $chienDich = Campaign::find()->where(['id' => $id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $listInteger = ['chiphi_thucchay', 'comment', 'tin_nhan', 'hien_thi', 'tiep_can', 'nguoi_xem_1', 'nguoi_xem_50'];
            foreach ($listInteger as $i) {
                $model->$i = trim(str_replace('.', '', $model->$i));
            }
            $model->tan_suat = str_replace('.', '', $model->tan_suat);
            $model->tan_suat = str_replace(',', '.', $model->tan_suat);

            $model->tong_tuong_tac = (int)$model->comment + (int)$model->tin_nhan;

            if ($model->chiphi_thucchay != '') {
                if ($model->tong_tuong_tac != 0) {
                    $model->gia_tuong_tac = $model->chiphi_thucchay / $model->tong_tuong_tac;
                }
                if ($model->hien_thi != 0) {
                    $model->gia_hien_thi = $model->chiphi_thucchay / $model->hien_thi;
                }
                if ($model->tiep_can != 0) {
                    $model->gia_tiep_can = $model->chiphi_thucchay / $model->tiep_can;
                }
                if ($model->nguoi_xem_1 != 0) {
                    $model->gia_10s = $model->chiphi_thucchay / $model->nguoi_xem_1;
                }
                if ($model->nguoi_xem_50 != 0) {
                    $model->gia_50phantram = $model->chiphi_thucchay / $model->nguoi_xem_50;
                }
            }
            $model->tong_tuong_tac = (string)$model->tong_tuong_tac;
            if ($model->validate()) {
                try {
                    $model->campaign_id = $cid;
                    if ($model->btn_form == 'end') {
                        $model->end_date = time();
                    }
                    $model->save();
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['create-success'],
                        'class' => 'bg-success',
                    ]);
                } catch (\yii\db\Exception $exception) {
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['create-danger'],
                        'class' => 'bg-danger',
                    ]);
                }
                return $this->refresh();
            } else {
                $error = '';
                foreach ($model->getErrors() as $k => $v) {
                    $error .= $v[0] . '<br/>';
                }
                Yii::$app->session->setFlash('alert', [
                    'body' => $error,
                    'class' => 'bg-danger',
                ]);
            }
        }

        $model->tan_suat = str_replace('.', ',', $model->tan_suat);

        return $this->render('create-campaign', [
            'model' => $model,
            'searchModelCD' => $searchModelCD,
            'dataProviderCD' => $dataProviderCD,
            'id' => $id,
            'chienDich' => $chienDich
        ]);
    }

    public function actionCreateCampaignAjax($cid)
    {
        if (Yii::$app->request->isAjax) {
            $model = new AbCampaign();

            $chienDich = Campaign::find()->where(['id' => $cid])->one();

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                $listInteger = ['chiphi_thucchay', 'comment', 'tin_nhan', 'hien_thi', 'tiep_can', 'nguoi_xem_1', 'nguoi_xem_50'];
                foreach ($listInteger as $i) {
                    $model->$i = trim(str_replace('.', '', $model->$i));
                }
                $model->tan_suat = str_replace('.', '', $model->tan_suat);
                $model->tan_suat = str_replace(',', '.', $model->tan_suat);

                $model->tong_tuong_tac = (int)$model->comment + (int)$model->tin_nhan;

                if ($model->chiphi_thucchay != '') {
                    if ($model->tong_tuong_tac != 0) {
                        $model->gia_tuong_tac = $model->chiphi_thucchay / $model->tong_tuong_tac;
                    }
                    if ($model->hien_thi != 0) {
                        $model->gia_hien_thi = $model->chiphi_thucchay / $model->hien_thi;
                    }
                    if ($model->tiep_can != 0) {
                        $model->gia_tiep_can = $model->chiphi_thucchay / $model->tiep_can;
                    }
                    if ($model->nguoi_xem_1 != 0) {
                        $model->gia_10s = $model->chiphi_thucchay / $model->nguoi_xem_1;
                    }
                    if ($model->nguoi_xem_50 != 0) {
                        $model->gia_50phantram = $model->chiphi_thucchay / $model->nguoi_xem_50;
                    }
                }
                $model->tong_tuong_tac = (string)$model->tong_tuong_tac;
                if ($model->validate()) {
                    $model->campaign_id = $cid;
                    if ($model->btn_form == 'end') {
                        $model->end_date = time();
                    }

                    if ($model->save()) {
                        return [
                            'status' => 200,
                            'mess' => Yii::$app->params['create-success'],
                            'error' => $model->getErrors(),
                        ];
                    } else {
                        return [
                            'status' => 403,
                            'mess' => Yii::$app->params['create-danger'],
                            'error' => $model->getErrors(),
                        ];
                    }
                } else {
                    $error = '';
                    foreach ($model->getErrors() as $k => $v) {
                        $error .= $v[0] . '<br/>';
                    }

                    return [
                        'status' => 400,
                        'mess' => 'Lỗi kiểm tra dữ liệu!',
                        'error' => $error,
                    ];
                }
            }

            $model->tan_suat = str_replace('.', ',', $model->tan_suat);

            return $this->renderAjax('create-campaign-ajax', [
                'model' => $model,
                'chienDich' => $chienDich
            ]);
        }
    }

    /**
     * Updates an existing Campaign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->save();
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['update-success'],
                    'class' => 'bg-success',
                ]);
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body' => $exception->getMessage(),
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateAjax($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                if ($model->save()) {
                    return [
                        'status' => 200,
                        'mess' => Yii::$app->params['update-success'],
                        'error' => $model->getErrors(),
                    ];
                } else {
                    return [
                        'status' => 403,
                        'mess' => Yii::$app->params['update-danger'],
                        'error' => $model->getErrors(),
                    ];
                }
            }

            return $this->renderAjax('update-ajax', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateCampaign($id)
    {
        $model = $this->findModelCampaign($id);
        $user = new User();
        $roleName = $user->getRoleName(Yii::$app->user->id);
        $readonly = null;
        if ($roleName == User::USER_DEVELOP || $roleName == User::USER_ADMINISTRATOR) {
            $readonly = false;
        }

        if ($model->end_date != null && $roleName != \common\models\User::USER_ADMINISTRATOR && $roleName != \common\models\User::USER_DEVELOP) {
            Yii::$app->session->setFlash('alert', [
                'body' => 'Bạn không thể cập nhật chiến dịch này nữa.',
                'class' => 'bg-danger',
            ]);
            return $this->redirect(['index']);
        }

        $searchModelCD = new CampaignSeach();
        $dataProviderCD = $searchModelCD->search(Yii::$app->request->queryParams);
        $dataProviderCD->pagination->pageSize = 50;
        $models = $dataProviderCD->getModels();
        $first = reset($models);
        $id = $model->campaign_id;

        $chienDich = Campaign::find()->where(['id' => $id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $listInteger = ['chiphi_thucchay', 'comment', 'tin_nhan', 'hien_thi', 'tiep_can', 'nguoi_xem_1', 'nguoi_xem_50'];
            foreach ($listInteger as $i) {
                $model->$i = str_replace('.', '', $model->$i);
            }
            $model->tan_suat = str_replace('.', '', $model->tan_suat);
            $model->tan_suat = str_replace(',', '.', $model->tan_suat);

            $model->tong_tuong_tac = (int)$model->comment + (int)$model->tin_nhan;

            if ($model->chiphi_thucchay != '') {
                if ($model->tong_tuong_tac != 0) {
                    $model->gia_tuong_tac = $model->chiphi_thucchay / $model->tong_tuong_tac;
                }
                if ($model->hien_thi != 0) {
                    $model->gia_hien_thi = $model->chiphi_thucchay / $model->hien_thi;
                }
                if ($model->tiep_can != 0) {
                    $model->gia_tiep_can = $model->chiphi_thucchay / $model->tiep_can;
                }
                if ($model->nguoi_xem_1 != 0) {
                    $model->gia_10s = $model->chiphi_thucchay / $model->nguoi_xem_1;
                }
                if ($model->nguoi_xem_50 != 0) {
                    $model->gia_50phantram = $model->chiphi_thucchay / $model->nguoi_xem_50;
                }
            }
            $model->tong_tuong_tac = (string)$model->tong_tuong_tac;

            if ($model->validate()) {
                try {
                    if ($model->btn_form == 'end') {
                        $model->end_date = time();
                    }
                    $model->save();
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['update-success'],
                        'class' => 'bg-success',
                    ]);
                } catch (\yii\db\Exception $exception) {
                    Yii::$app->session->setFlash('alert', [
                        'body' => $exception->getMessage(),
                        'class' => 'bg-danger',
                    ]);
                }
                return $this->refresh();
            } else {
                $error = '';
                foreach ($model->getErrors() as $k => $v) {
                    $error .= $v[0] . '<br/>';
                }
                Yii::$app->session->setFlash('alert', [
                    'body' => $error,
                    'class' => 'bg-danger',
                ]);
            }
        }

        $model->tan_suat = str_replace('.', ',', $model->tan_suat);

        return $this->render('update-campaign', [
            'model' => $model,
            'searchModelCD' => $searchModelCD,
            'dataProviderCD' => $dataProviderCD,
            'id' => $id,
            'chienDich' => $chienDich,
            'readonly' => $readonly
        ]);
    }

    public function actionUpdateCampaignAjax($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModelCampaign($id);
            $user = new User();
            $roleName = $user->getRoleName(Yii::$app->user->id);
            $readonly = null;
            if ($roleName == User::USER_DEVELOP || $roleName == User::USER_ADMINISTRATOR) {
                $readonly = false;
            }

            if ($model->end_date != null && $roleName != \common\models\User::USER_ADMINISTRATOR && $roleName != \common\models\User::USER_DEVELOP) {
                return $this->renderAjax('_error', [
                    'error' => Yii::$app->params['update-danger'],
                ]);
            }

            $id = $model->campaign_id;

            $chienDich = Campaign::find()->where(['id' => $id])->one();

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                $listInteger = ['chiphi_thucchay', 'comment', 'tin_nhan', 'hien_thi', 'tiep_can', 'nguoi_xem_1', 'nguoi_xem_50'];
                foreach ($listInteger as $i) {
                    $model->$i = str_replace('.', '', $model->$i);
                }
                $model->tan_suat = str_replace('.', '', $model->tan_suat);
                $model->tan_suat = str_replace(',', '.', $model->tan_suat);

                $model->tong_tuong_tac = (int)$model->comment + (int)$model->tin_nhan;

                if ($model->chiphi_thucchay != '') {
                    if ($model->tong_tuong_tac != 0) {
                        $model->gia_tuong_tac = $model->chiphi_thucchay / $model->tong_tuong_tac;
                    }
                    if ($model->hien_thi != 0) {
                        $model->gia_hien_thi = $model->chiphi_thucchay / $model->hien_thi;
                    }
                    if ($model->tiep_can != 0) {
                        $model->gia_tiep_can = $model->chiphi_thucchay / $model->tiep_can;
                    }
                    if ($model->nguoi_xem_1 != 0) {
                        $model->gia_10s = $model->chiphi_thucchay / $model->nguoi_xem_1;
                    }
                    if ($model->nguoi_xem_50 != 0) {
                        $model->gia_50phantram = $model->chiphi_thucchay / $model->nguoi_xem_50;
                    }
                }
                $model->tong_tuong_tac = (string)$model->tong_tuong_tac;

                if ($model->validate()) {
                    if ($model->btn_form == 'end') {
                        $model->end_date = time();
                    }
                    if (Yii::$app->user->can(User::USER_DEVELOP) && $model->btn_form == 'restart') {
                        $model->end_date = null;
                    }

                    if ($model->save()) {
                        return [
                            'status' => 200,
                            'mess' => Yii::$app->params['update-success'],
                            'error' => $model->getErrors(),
                        ];
                    } else {
                        return [
                            'status' => 403,
                            'mess' => Yii::$app->params['update-danger'],
                            'error' => $model->getErrors(),
                        ];
                    }
                } else {
                    $error = '';
                    foreach ($model->getErrors() as $k => $v) {
                        $error .= $v[0] . '<br/>';
                    }

                    return [
                        'status' => 400,
                        'mess' => 'Lỗi kiểm tra dữ liệu!',
                        'error' => $error,
                    ];
                }
            }

            $model->tan_suat = str_replace('.', ',', $model->tan_suat);

            return $this->renderAjax('update-campaign-ajax', [
                'model' => $model,
                'chienDich' => $chienDich,
                'readonly' => $readonly
            ]);
        }
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
            $checkCampaign = AbCampaign::find()->where(['campaign_id' => $model->id])->one();
            if ($checkCampaign != null) {
                return [
                    "status" => "failure"
                ];
            }
            try {
                if ($model->delete()) {
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

    public function actionDeleteCampaign()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            $checkCampaign = $this->findModelCampaign($id);
            if (!Yii::$app->user->can(User::USER_DEVELOP) && $checkCampaign->end_date != null) {
                return [
                'status' => 'failure'
            ];
            }
            try {
                if ($checkCampaign->delete()) {
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

    protected function findModel($id)
    {
        if (($model = Campaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelCampaign($id)
    {
        if (($model = AbCampaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
