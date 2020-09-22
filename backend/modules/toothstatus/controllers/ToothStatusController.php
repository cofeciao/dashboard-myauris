<?php

namespace backend\modules\toothstatus\controllers;

use backend\components\MyController;
use backend\modules\toothstatus\models\DichVu;
use backend\modules\toothstatus\models\DoTuoi;
use backend\modules\toothstatus\models\KyThuatRang;
use backend\modules\toothstatus\models\LuaChon;
use backend\modules\toothstatus\models\LuaChonLoaiDichVu;
use backend\modules\toothstatus\models\LuaChonLoaiKyThuat;
use backend\modules\toothstatus\models\TinhTrangRang;
use Yii;
use yii\web\Response;

class ToothStatusController extends MyController
{
    public function actionIndex()
    {
        $this->layout = '@backend/views/layouts/public';
        $step = 1;
        return $this->render('index', [
            'step' => $step
        ]);
    }

    public function actionLoadStep($step = 1)
    {
        if (Yii::$app->request->isAjax) {
            $render = [];
            switch ($step) {
                case 1:
                default:
                    $render['listTinhTrangRang'] = TinhTrangRang::getListTinhTrangRang();
            }
            return $this->renderAjax('_step-' . $step, $render);
        }
    }

    public function actionCheckHasChild()
    {
        if (Yii::$app->request->isAjax) {
            $status = Yii::$app->request->post('status');
            if ($status == null) {
                return 'false';
            }
            $checkHasChild = TinhTrangRang::checkHasChild($status);
            if ($checkHasChild === true) {
                return 'true';
            }
            return false;
        }
    }

    public function actionLoadKyThuatRang()
    {
        if (Yii::$app->request->isAjax) {
            $status = Yii::$app->request->post('status');
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($status == null) {
                return [];
            }
            $listKyThuatRang = KyThuatRang::getListByTinhTrang($status);
            if (!is_array($listKyThuatRang)) {
                return [];
            }
            $data = [];
            foreach ($listKyThuatRang as $kythuatrang) {
                $link_video = $kythuatrang->link_video;
                if ($link_video != null) {
                    $link = null;
                    if (strpos($link_video, 'youtu.be') !== false) {
                        $tmp = explode('youtu.be/', $link_video);
                        if (isset($tmp[1])) {
                            $tmp = explode('&', $tmp[1]);
                        }
                        $link_video = $tmp[0];
                    } elseif (strpos($link_video, 'embed/') !== false) {
                        $tmp = explode('embed/', $link_video);
                        if (isset($tmp[1])) {
                            $tmp = explode('&', $tmp[1]);
                        }
                        $link_video = $tmp[0];
                    } elseif (strpos($link_video, 'youtube.com')) {
                        $tmp = explode('v=', $link_video);
                        if (isset($tmp[1])) {
                            $tmp = explode('&', $tmp[1]);
                        }
                        $link_video = $tmp[0];
                    }
                }
                $data[] = [
                    'id' => $kythuatrang->primaryKey,
                    'name' => $kythuatrang->name,
                    'link_video' => $link_video,
                ];
            }
            return $data;
        }
    }

    public function actionLoadDoTuoi()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $listDoTuoi = DoTuoi::getListDoTuoi();
            if (!is_array($listDoTuoi)) {
                return [];
            }
            $data = [];
            foreach ($listDoTuoi as $dotuoi) {
                $data[] = [
                    'id' => $dotuoi->primaryKey,
                    'name' => $dotuoi->name,
                    'image' => $dotuoi->image != null ?
                        Yii::getAlias('@frontendUrl') . '/uploads/rang/do-tuoi/300x300/' . $dotuoi->image :
                        Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png'
                ];
            }
            return $data;
        }
    }

    public function actionLoadLuaChon()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $lua_chons = LuaChon::getListLuaChon();
            if (!is_array($lua_chons)) {
                return [];
            }
            $data = [];
            foreach ($lua_chons as $lua_chon) {
                $data[] = [
                    'id' => $lua_chon->primaryKey,
                    'name' => $lua_chon->name
                ];
            }
            return $data;
        }
    }

    public function actionLoadDichVu()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $status = Yii::$app->request->post('status');
            $age = Yii::$app->request->post('age');
            $choose = Yii::$app->request->post('choose');
            $loaiDichVu = DichVu::getListByVariable($status, $age, $choose);
            if (!is_array($loaiDichVu) || count($loaiDichVu) <= 0) {
                $get_age = DoTuoi::find()->where(['id' => $age])->published()->one();
                $msg = 'Không tìm thấy dịch vụ phù hợp';
                if ($get_age != null) {
                    $msg .= ' cho tuổi ' . $get_age->name;
                }
                $msg = '<div class="no-item text-center alert alert-warning">' . $msg . '</div>';
                return [$msg];
            }
            $data = [];
            foreach ($loaiDichVu as $dichvu) {
                $list_images = [];
                $customer_image = json_decode($dichvu->customer_image, true);
                foreach ([0, 1, 2] as $k) {
                    $list_images[] = [
                        'before' => [
                            'image' => isset($customer_image[$k]) && !in_array($customer_image[$k], [null, '']) && isset($customer_image[$k]['before']) && !in_array($customer_image[$k]['before'], [null, '']) ? Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/' . $customer_image[$k]['before'] : Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png',
                            'thumb' => isset($customer_image[$k]) && !in_array($customer_image[$k], [null, '']) && isset($customer_image[$k]['before']) && !in_array($customer_image[$k]['before'], [null, '']) ? Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/150x150/' . $customer_image[$k]['before'] : Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png',
                        ],
                        'after' => [
                            'image' => isset($customer_image[$k]) && !in_array($customer_image[$k], [null, '']) && isset($customer_image[$k]['after']) && !in_array($customer_image[$k]['after'], [null, '']) ? Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/' . $customer_image[$k]['after'] : Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png',
                            'thumb' => isset($customer_image[$k]) && !in_array($customer_image[$k], [null, '']) && isset($customer_image[$k]['after']) && !in_array($customer_image[$k]['after'], [null, '']) ? Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/150x150/' . $customer_image[$k]['after'] : Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png',
                        ]
                    ];
                    /*$image = isset($customer_image[$k]) ? $customer_image[$k] : null;
                    $list_images[] = [
                        'image' => $image != null ? Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/' . $image : Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png',
                        'thumb' => $image != null ? Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/150x150/' . $image : Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png',
                    ];*/
                }
                $data[] = [
                    'id' => $dichvu->primaryKey,
                    'name' => $dichvu->name,
                    'price' => $dichvu->price == null ? null : number_format($dichvu->price, 0, '', '.') . ' VND',
                    'star' => $dichvu->star,
                    'list_images' => $list_images
                ];
            }
            return $data;
        }
    }

    public function actionShowBeaf()
    {
        if (Yii::$app->request->isAjax) {
            $post_service = Yii::$app->request->post('service');
            $post_image = Yii::$app->request->post('image');
            if ($post_service == null || $post_image == null) {
                return $this->renderAjax('_error', [
                'error' => 'Lỗi dữ liệu'
            ]);
            }
            $service = DichVu::find()->where(['id' => $post_service])->published()->one();
            if ($service == null) {
                return $this->renderAjax('_error', [
                'error' => 'Không tìm thấy dữ liệu'
            ]);
            }
            $customer_image = json_decode($service->customer_image, true);
            if (!is_array($customer_image) || !isset($customer_image[$post_image]) ||
                !is_array($customer_image[$post_image]) ||
                !isset($customer_image[$post_image]['before']) || in_array($customer_image[$post_image]['before'], [null, '']) ||
                !isset($customer_image[$post_image]['after']) || in_array($customer_image[$post_image]['after'], [null, ''])) {
                return $this->renderAjax('_error', [
                'error' => 'Dữ liệu chưa cập nhật'
            ]);
            }
            return $this->renderAjax('view-beaf', [
                'before' => Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/' . $customer_image[$post_image]['before'],
                'after' => Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/' . $customer_image[$post_image]['after']
            ]);
        }
    }
}
