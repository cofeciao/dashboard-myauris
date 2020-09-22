<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-May-19
 * Time: 4:25 PM
 */

namespace backend\modules\helper\controllers;

use backend\components\MyController;
use backend\models\CustomerModel;
use backend\models\Dep365CustomerOnlineRemindCall;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\PhongKhamChupBanhMoi;
use backend\modules\clinic\models\PhongKhamChupCui;
use backend\modules\clinic\models\PhongKhamChupFinal;
use backend\modules\clinic\models\PhongKhamChupHinh;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamHinhTknc;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamLichDieuTriTree;
use backend\modules\customer\models\Dep365CustomerOnlineBak;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatusTree;
use backend\modules\customer\models\Dep365CustomerOnlineTree;
use backend\modules\user\models\UserTimelineModel;
use common\commands\DeleteImageCommand;
use Yii;
use yii\db\Transaction;
use yii\web\Response;

class DelCustomerController extends MyController
{
    public function actionIndex()
    {
        return $this->render('index', [

        ]);
    }

    public function actionDelCustomerAbout()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $id = \Yii::$app->request->post('id');
            if (!is_numeric($id)) {
                return ['status' => 200, 'msg' => 'Nhập ID khách hàng bạn cần xóa.'];
            }
            $customer = CustomerModel::findOne($id);
            if ($customer === null) {
                return ['status' => 200, 'msg' => 'Không tồn tại khách hàng này'];
            }

            $transaction = \Yii::$app->db->beginTransaction(
                Transaction::SERIALIZABLE
            );
            $customer_slug = $customer->slug;
            $msg = 'Bạn đã xóa thành công';
            if (!$customer->delete()) {
                $msg = 'Không xóa được tại bảng Customer';
                $transaction->rollBack();
            }

            $customerBak = Dep365CustomerOnlineBak::find()->where(['customer_online_id' => $id])->all();
            foreach ($customerBak as $item) {
                $customerBakId = Dep365CustomerOnlineBak::findOne($item->id);
                if (!$customerBakId->delete()) {
                    $msg = 'Không xóa được tại bảng Customer Bak';
                    $transaction->rollBack();
                }
            }

            $customerDatHen = Dep365CustomerOnlineDathenTime::find()->where(['customer_online_id' => $id])->all();
            foreach ($customerDatHen as $item) {
                $customerDatHenId = Dep365CustomerOnlineDathenTime::findOne($item->id);
                if (!$customerDatHenId->delete()) {
                    $msg = 'Không xóa được tại bảng Customer đặt hẹn time';
                    $transaction->rollBack();
                }
            }

            $customerRemindCall = Dep365CustomerOnlineRemindCall::find()->where(['customer_id' => $id])->all();
            foreach ($customerRemindCall as $item) {
                $customerRemindCallId = Dep365CustomerOnlineRemindCall::findOne($item->id);
                if (!$customerRemindCallId->delete()) {
                    $msg = 'Không xóa được tại bảng Customer Remind Call';
                    $transaction->rollBack();
                }
            }

            $customerOnlineTree = Dep365CustomerOnlineTree::find()->where(['customer_online_id' => $id])->all();
            foreach ($customerOnlineTree as $item) {
                $customerOnlineTreeId = Dep365CustomerOnlineTree::findOne($item->id);
                if (!$customerOnlineTreeId->delete()) {
                    $msg = 'Không xóa được tại bảng Customer Online Tree';
                    $transaction->rollBack();
                }
            }

            $customerStatusFailFree = Dep365CustomerOnlineFailStatusTree::find()->where(['customer_online_id' => $id])->all();
            foreach ($customerStatusFailFree as $item) {
                $customerStatusFailFreeId = Dep365CustomerOnlineFailStatusTree::findOne($item->id);
                if (!$customerStatusFailFreeId->delete()) {
                    $msg = 'Không xóa được tại bảng Customer Fail Status Tree';
                    $transaction->rollBack();
                }
            }

            $phongKhamChupBanhMoi = PhongKhamChupBanhMoi::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamChupBanhMoi as $item) {
                $phongKhamChupBanhMoiId = PhongKhamChupBanhMoi::findOne($item->id);
                if (!$phongKhamChupBanhMoiId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám chụp banh môi';
                    $transaction->rollBack();
                }
            }

            $phongKhamChupCui = PhongKhamChupCui::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamChupCui as $item) {
                $phongKhamChupCuiId = PhongKhamChupCui::findOne($item->id);
                if (!$phongKhamChupCuiId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám chụp cùi';
                    $transaction->rollBack();
                }
            }

            $phongKhamChupFinal = PhongKhamChupFinal::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamChupFinal as $item) {
                $phongKhamChupFinalId = PhongKhamChupFinal::findOne($item->id);
                if (!$phongKhamChupFinalId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám chụp final';
                    $transaction->rollBack();
                }
            }

            $phongKhamChupHinh = PhongKhamChupHinh::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamChupHinh as $item) {
                $phongKhamChupHinhId = PhongKhamChupHinh::findOne($item->id);
                if (!$phongKhamChupHinhId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám chụp hình';
                    $transaction->rollBack();
                }
            }

            $phongKhamHinhTknc = PhongKhamHinhTknc::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamHinhTknc as $item) {
                $phongKhamHinhTkncId = PhongKhamHinhTknc::findOne($item->id);
                if (!$phongKhamHinhTkncId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám hình thiết kế nụ cười';
                    $transaction->rollBack();
                }
            }

            $phongKhamDonHang = PhongKhamDonHang::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamDonHang as $item) {
                $phongKhamDonHangId = PhongKhamDonHang::findOne($item->id);
                if (!$phongKhamDonHangId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám đơn hàng';
                    $transaction->rollBack();
                }
            }

            $phongKhamDonHangTree = PhongKhamDonHangTree::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamDonHangTree as $item) {
                $phongKhamDonHangTreeId = PhongKhamDonHangTree::findOne($item->id);
                if (!$phongKhamDonHangTreeId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám đơn hàng tree';
                    $transaction->rollBack();
                }
            }

            $phongKhamDonHangOder = PhongKhamDonHangWOrder::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamDonHangOder as $item) {
                $phongKhamDonHangOderId = PhongKhamDonHangWOrder::findOne($item->id);
                if (!$phongKhamDonHangOderId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám đơn hàng Order';
                    $transaction->rollBack();
                }
            }

            $phongKhamDonHangThanhToan = PhongKhamDonHangWThanhToan::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamDonHangThanhToan as $item) {
                $phongKhamDonHangThanhToanId = PhongKhamDonHangWThanhToan::findOne($item->id);
                if (!$phongKhamDonHangThanhToanId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám đơn hàng thanh toán';
                    $transaction->rollBack();
                }
            }

            $phongKhamLichDieuTri = PhongKhamLichDieuTri::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamLichDieuTri as $item) {
                $phongKhamLichDieuTriId = PhongKhamLichDieuTri::findOne($item->id);
                if (!$phongKhamLichDieuTriId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám lịch điều trị';
                    $transaction->rollBack();
                }
            }

            $phongKhamLichDieuTriTree = PhongKhamLichDieuTriTree::find()->where(['customer_id' => $id])->all();
            foreach ($phongKhamLichDieuTriTree as $item) {
                $phongKhamLichDieuTriTreeId = PhongKhamLichDieuTriTree::findOne($item->id);
                if (!$phongKhamLichDieuTriTreeId->delete()) {
                    $msg = 'Không xóa được tại bảng Phòng khám lịch điều trị tree';
                    $transaction->rollBack();
                }
            }

            $userTimeLine = UserTimelineModel::find()->where(['customer_id' => $id])->all();
            foreach ($userTimeLine as $item) {
                $userTimeLineId = UserTimelineModel::findOne($item->id);
                if (!$userTimeLineId->delete()) {
                    $msg = 'Không xóa được tại bảng User TimeLine';
                    $transaction->rollBack();
                }
            }

            $remindCall = Dep365CustomerOnlineRemindCall::find()->where(['customer_id' => $id])->all();
            foreach ($remindCall as $item) {
                if (!$item->delete()) {
                    $msg = 'Không xóa được tại bảng Nhắc lịch';
                    $transaction->rollBack();
                }
            }

            $customerImage = CustomerImages::find()->where(['customer_id' => $id])->all();
            foreach ($customerImage as $item) {
                if (!$item->delete()) {
                    $msg = 'Không xóa được tại bảng Customer id';
                    $transaction->rollBack();
                }
            }

            $this->deleteFileAndFolder(Yii::getAlias('@backend/web') . '/uploads/customer/' . $customer_slug . '-' . $id . '/');

            $transaction->commit();

            return ['status' => 200, 'msg' => $msg];
        }
    }

    protected function deleteFileAndFolder($target)
    {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                $this->deleteFileAndFolder($file);
            }
            rmdir($target);
        } elseif (is_file($target)) {
            unlink($target);
        }
    }

    protected function deleteImageApi($getAlias, $alias, $image)
    {
        return Yii::$app->commandBus->handle(new DeleteImageCommand([
            'getAlias' => $getAlias,
            'alias' => $alias,
            'image' => $image,
        ]));
    }
}
