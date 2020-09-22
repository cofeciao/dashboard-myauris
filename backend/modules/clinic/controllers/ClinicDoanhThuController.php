<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\search\CustomerDoanhThuSearch;
use backend\modules\clinic\models\search\PhongKhamDonHangSearch;
use backend\modules\user\models\User;
use backend\modules\user\models\UserTimelineModel;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ClinicDoanhThuController implements the CRUD actions for PhongKhamDonHangWThanhToan model.
 */
class ClinicDoanhThuController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new CustomerDoanhThuSearch();
        $sum_don_hang = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $sum_don_hang);

        $dataProviderSave = $dataProvider->query->all();
        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }

        $pageSize = $dataProvider->pagination->pageSize;

        $totalCount = $dataProvider->totalCount;

        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        // tinh tong thuc thu
        $so_tien_thuc_thu = $searchModel->handleSumThanhToan(Yii::$app->request->queryParams, false);
        $so_tien_hoan_coc = $searchModel->handleSumThanhToan(Yii::$app->request->queryParams, true);
        $sum_thuc_thu = $so_tien_thuc_thu - $so_tien_hoan_coc;

        $sum_thuc_thu_the = $searchModel->handleSumThucThuChiTiet(Yii::$app->request->queryParams, false);
        $sum_thuc_thu_tien_mat = $searchModel->handleSumThucThuChiTiet(Yii::$app->request->queryParams, true);


        $sum_thanh_toan_the = $searchModel->handleSumThanhToanChiTiet(Yii::$app->request->queryParams, false);
        $sum_thanh_toan_tien_mat = $searchModel->handleSumThanhToanChiTiet(Yii::$app->request->queryParams, true);

        $sum_dat_coc_the = $searchModel->handleSumDatCocChiTiet(Yii::$app->request->queryParams, false);
        $sum_dat_coc_tien_mat = $searchModel->handleSumDatCocChiTiet(Yii::$app->request->queryParams, true);

        $sum_tien_truoc_chiet_khau = $searchModel->handleSumThanhTienTruocChietKhau(Yii::$app->request->queryParams);
        $sum_tien_chiet_khau = $searchModel->handleSumChietKhau(Yii::$app->request->queryParams);

        $sum_tien_hoan_coc = $searchModel->handleSumHoanCoc(Yii::$app->request->queryParams);
        $sum_tien_hoan_coc_the = $searchModel->handleSumHoanCocChiTiet(Yii::$app->request->queryParams, false);
        $sum_tien_hoan_coc_tien_mat = $searchModel->handleSumHoanCocChiTiet(Yii::$app->request->queryParams, true);

        $sum_tra_gop = $searchModel->handleSumTraGop(Yii::$app->request->queryParams);

        $exportDoanhThuSum = [
            'sum_thuc_thu' => $sum_thuc_thu,
            'sum_don_hang' => $sum_don_hang,
            'sum_thuc_thu_the' => $sum_thuc_thu_the,
            'sum_thuc_thu_tien_mat' => $sum_thuc_thu_tien_mat,
            'sum_thanh_toan_the' => $sum_thanh_toan_the,
            'sum_thanh_toan_tien_mat' => $sum_thanh_toan_tien_mat,
            'sum_dat_coc_the' => $sum_dat_coc_the,
            'sum_dat_coc_tien_mat' => $sum_dat_coc_tien_mat,
            'sum_tien_truoc_chiet_khau' => $sum_tien_truoc_chiet_khau,
            'sum_tien_chiet_khau' => $sum_tien_chiet_khau,
            'sum_tien_hoan_coc' => $sum_tien_hoan_coc,
            'sum_tien_hoan_coc_the' => $sum_tien_hoan_coc_the,
            'sum_tien_hoan_coc_tien_mat' => $sum_tien_hoan_coc_tien_mat,
            'sum_tra_gop' => $sum_tra_gop,
        ];

        Yii::$app->session->set("exportDoanhThuSum", $exportDoanhThuSum);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'sum_thuc_thu' => $sum_thuc_thu,
            'sum_don_hang' => $sum_don_hang,
            'sum_thuc_thu_the' => $sum_thuc_thu_the,
            'sum_thuc_thu_tien_mat' => $sum_thuc_thu_tien_mat,
            'sum_thanh_toan_the' => $sum_thanh_toan_the,
            'sum_thanh_toan_tien_mat' => $sum_thanh_toan_tien_mat,
            'sum_dat_coc_the' => $sum_dat_coc_the,
            'sum_dat_coc_tien_mat' => $sum_dat_coc_tien_mat,
            'sum_tien_truoc_chiet_khau' => $sum_tien_truoc_chiet_khau,
            'sum_tien_chiet_khau' => $sum_tien_chiet_khau,
            'sum_tien_hoan_coc' => $sum_tien_hoan_coc,
            'sum_tien_hoan_coc_the' => $sum_tien_hoan_coc_the,
            'sum_tien_hoan_coc_tien_mat' => $sum_tien_hoan_coc_tien_mat,
            'sum_tra_gop' => $sum_tra_gop,
        ]);
    }

    public function actionExport()
    {
        set_time_limit(0);
        $session = Yii::$app->session;
        if (!$session->has("exportDoanhThu") && !$session->has("exportDoanhThuSum")) {
            echo "NO DATA export Doanh Thu";
            die;
        }

        $data = Yii::$app->session->get("exportDoanhThu");
        $model = new PhongKhamDonHangSearch();
        $aData = [];
        foreach ($data as $item) {
            $model->setAttributes($item); // gang vo PhongKhamDonHang

            if ($model->customerOnlineHasOne == null) {
                $dt_customer = "";
            } else {
                $dt_customer = ($model->customerOnlineHasOne->full_name != null) ? $model->customerOnlineHasOne->full_name : $model->customerOnlineHasOne->name;
            }
            $mWthanhtoan = new PhongKhamDonHangWThanhToan();

            // dt_tong_tien_thuc_thu
            $dt_tong_tien_thuc_thu = $mWthanhtoan->getDatCocByOrder($model->id) + $mWthanhtoan->getThanhToanByOrder($model->id);
            // dt_thuc_thu_the
            $dt_thuc_thu_the = $mWthanhtoan->getDatCocByOrderChiTiet($model->id, false) + $mWthanhtoan->getThanhToanByOrderChiTiet($model->id, false);
            // dt_thuc_thu_tien_mat
            $dt_thuc_thu_tien_mat = $mWthanhtoan->getDatCocByOrderChiTiet($model->id, true) + $mWthanhtoan->getThanhToanByOrderChiTiet($model->id, true);
            // dt_tong_thanh_toan
            $dt_tong_thanh_toan = $mWthanhtoan->getThanhToanByOrder($model->id);
            // dt_thanh_toan_the
            $dt_thanh_toan_the = $mWthanhtoan->getThanhToanByOrderChiTiet($model->id, false);
            // dt_thanh_toan_tien_mat
            $dt_thanh_toan_tien_mat = $mWthanhtoan->getThanhToanByOrderChiTiet($model->id, true);
            // dt_tong_dat_coc
            $dt_tong_dat_coc = $mWthanhtoan->getDatCocByOrder($model->id);
            // dt_dat_coc_the
            $dt_dat_coc_the = $mWthanhtoan->getDatCocByOrderChiTiet($model->id, false);
            // dt_dat_coc_tien_mat
            $dt_dat_coc_tien_mat = $mWthanhtoan->getDatCocByOrderChiTiet($model->id, true);
            // dt_hoan_coc
            $dt_hoan_coc = $mWthanhtoan->getHoanCocByOrder($model->id);
            // dt_hoan_coc_the
            $dt_hoan_coc_the = $mWthanhtoan->getHoanCocByOrderChiTiet($model->id, false);
            // dt_hoan_coc_tien_mat
            $dt_hoan_coc_tien_mat  = $mWthanhtoan->getHoanCocByOrderChiTiet($model->id, true);
            // dt_hoan_coc
            $dt_tra_gop = $mWthanhtoan->getTraGopByOrder($model->id);
            // dt_co_so
            $dt_co_so = $this->getCoSo($model);
            // dt_sale_pk
            $dt_sale_pk = ($model->customerOnlineHasOne) ? $model->customerOnlineHasOne->getDirectSaleName() : "";
            // dt_created_by
            $userCreatedBy = $mWthanhtoan->getUserCreatedBy($model->created_by);

            $dt_created_by = ($userCreatedBy) ? $userCreatedBy->fullname : "";

            $aData[] = [
                'dt_customer' => $dt_customer,
                'dt_order_code' => $model->order_code,
                'dt_trang_thai' => $model->showHoanThanh(),
                'dt_trang_thai_hoan_thanh' => $model->showHoanThanhThanhToan(),
                'dt_dich_vu' => $model->getThongTinGoiDichVu(true),
                'dt_tong_tien_truoc_chiet_khau' => ($model->thanh_tien !== 0) ? $model->thanh_tien : "",
                'dt_tong_tien_chiet_khau' => ($model->chiet_khau !== 0) ? $model->chiet_khau : "",
                'dt_chi_tiet_chiet_khau' => $model->getChiTietChietKhau(true),
                'dt_tong_tien_hd' => $model->thanh_tien - $model->chiet_khau,
                'dt_khach_hang_no' => $model->thanh_tien - $model->chiet_khau - $dt_tong_tien_thuc_thu,
                'dt_tong_tien_thuc_thu' => $dt_tong_tien_thuc_thu,
                'dt_thuc_thu_the' => ($dt_thuc_thu_the !== 0) ? $dt_thuc_thu_the : "",
                'dt_thuc_thu_tien_mat' => ($dt_thuc_thu_tien_mat !== 0) ? $dt_thuc_thu_tien_mat : "",
                'dt_tong_thanh_toan' => $dt_tong_thanh_toan,
                'dt_thanh_toan_the' => $dt_thanh_toan_the,
                'dt_thanh_toan_tien_mat' => $dt_thanh_toan_tien_mat,
                'dt_tong_dat_coc' => $dt_tong_dat_coc,
                'dt_dat_coc_the' => $dt_dat_coc_the,
                'dt_dat_coc_tien_mat' => $dt_dat_coc_tien_mat,
                'dt_tra_gop' => ($dt_tra_gop !== 0) ? $dt_tra_gop : "",
                'dt_hoan_coc' => ($dt_hoan_coc !== 0) ? $dt_hoan_coc : "",
                'dt_hoan_coc_the' => ($dt_hoan_coc_the !== 0) ? $dt_hoan_coc_the : "",
                'dt_hoan_coc_tien_mat' => ($dt_hoan_coc_tien_mat !== 0) ? $dt_hoan_coc_tien_mat : "",
                'dt_huy_dich_vu' => $model->checkHuyDichVu(),
                'dt_chi_tiet_giao_dich' => $model->getChiTietThanhToan(true),
                'dt_co_so' => $dt_co_so,
                'dt_sale_pk' => $dt_sale_pk,
                'dt_bac_si_mai' => $model->getThongTinBacSiLichDieuTri(true, PhongKhamLichDieuTri::THAO_TAC_MAI),
                'dt_bac_si_lap' => $model->getThongTinBacSiLichDieuTri(true, PhongKhamLichDieuTri::THAO_TAC_LAP),
                'dt_bac_si_loi' => $model->getThongTinBacSiLichDieuTri(true, PhongKhamLichDieuTri::THAO_TAC_LOI),
                'dt_bac_si_khac' => $model->getThongTinBacSiLichDieuTri(true, PhongKhamLichDieuTri::THAO_TAC_KHAC),

                'dt_tro_thu_mai' => $model->getThongTinTroThuLichDieuTri(true, PhongKhamLichDieuTri::THAO_TAC_MAI),
                'dt_tro_thu_lap' => $model->getThongTinTroThuLichDieuTri(true, PhongKhamLichDieuTri::THAO_TAC_LAP),
                'dt_tro_thu_loi' => $model->getThongTinTroThuLichDieuTri(true, PhongKhamLichDieuTri::THAO_TAC_LOI),
                'dt_tro_thu_khac' => $model->getThongTinTroThuLichDieuTri(true, PhongKhamLichDieuTri::THAO_TAC_KHAC),
                'dt_created_at' => Yii::$app->formatter->format($model->created_at, 'datetime'),
                'dt_created_by' => $dt_created_by,
            ];
        }

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $this->exportBody($sheet, $aData);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="BAO_CAO_DOANH_THU.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            ob_end_clean(); // https://github.com/PHPOffice/PhpSpreadsheet/issues/217
            $writer->save('php://output');
            exit(); // neu ko co se bị loi yii\web\HeadersAlreadySentException: Headers already sent.
            //            echo "<script>window.close();</script>";
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function getCoSo($model)
    {
        if ($model->co_so) {
            return $model->co_so;
        } else {
            if ($model->customerOnlineHasOne) {
                return $model->customerOnlineHasOne->co_so;
            }
        }
        return "";
    }

    public function exportBody(&$sheet, $aData)
    {
        $row = 1; // hang trong excel
        $arrayColumn = $this->getArrayColumn();
        $listField = CustomerDoanhThuSearch::getlistField();
        $arrayAttributeNumber = [
            'dt_tong_tien_truoc_chiet_khau',
            'dt_tong_tien_chiet_khau',
            'dt_tong_tien_hd',
            'dt_khach_hang_no',
            'dt_tong_tien_thuc_thu',
            'dt_thuc_thu_the',
            'dt_thuc_thu_tien_mat',
            'dt_tong_thanh_toan',
            'dt_thanh_toan_the',
            'dt_thanh_toan_tien_mat',
            'dt_tong_dat_coc',
            'dt_dat_coc_the',
            'dt_dat_coc_tien_mat',
            'dt_hoan_coc',
            'dt_hoan_coc_the',
            'dt_hoan_coc_tien_mat',
            'dt_tra_gop',
        ];
        $alphas = $this->createColumnsArray('AG'); // range('A', 'Z');
        $exportDoanhThuSum = Yii::$app->session->get("exportDoanhThuSum");

        $exportDoanhThuThoiGian = Yii::$app->session->get("exportDoanhThuThoiGian");

        // lay ki tu cuoi cung
        $endarray = $arrayColumn;
        end($endarray);

        $sheet->mergeCells('A' . $row . ':' . key($endarray) . $row);
        $sheet->setCellValue('A' . $row, 'PHÒNG KHÁM NHA KHOA MYAURIS');
        $sheet->getStyle('A' . $row . ':' . key($endarray) . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //
        $row++;
        $sheet->mergeCells('A' . $row . ':' . key($endarray) . $row);

        if ($exportDoanhThuThoiGian['from'] != null) {
            if ($exportDoanhThuThoiGian['to'] == null || $exportDoanhThuThoiGian['type'] == 'date') {
                $sheet->setCellValue('A' . $row, 'BẢNG TỔNG HỢP DOANH THU NGÀY HÓA ĐƠN ' . $exportDoanhThuThoiGian['from']);
            } else {
                $sheet->setCellValue('A' . $row, 'BẢNG TỔNG HỢP DOANH THU NGÀY HÓA ĐƠN TỪ ' . $exportDoanhThuThoiGian['from'] . " ĐẾN " . $exportDoanhThuThoiGian['to']);
            }
        }

        $sheet->getStyle('A' . $row . ':' . key($endarray) . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //
        $row++;
        $sheet->setCellValue('A' . $row, 'STT');
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $rowHeader = $row;

        // chieu cao header
        $sheet->getRowDimension($rowHeader)->setRowHeight(30);
        $sheet->getStyle('A' . $rowHeader . ':' . key($endarray) . $rowHeader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['#' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A' . $rowHeader . ':' . key($endarray) . $rowHeader)->applyFromArray($styleArray);


        $row++;
        $rowHeaderSum = $row;
        // row tinh tong
        $sheet->getRowDimension($rowHeaderSum)->setRowHeight(20);
        $sheet->getStyle('A' . $rowHeaderSum . ':' . key($endarray) . $rowHeaderSum)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $rowHeaderSum . ':' . key($endarray) . $rowHeaderSum)->getNumberFormat()->setFormatCode('#,##0'); // format number

        // FOREACH SET TINH TONG

        foreach ($arrayColumn as $alphabetic => $name_attribute) {
            if ($name_attribute == "dt_tong_tien_truoc_chiet_khau") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_tien_truoc_chiet_khau']);
            }
            if ($name_attribute == "dt_tong_tien_chiet_khau") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_tien_chiet_khau']);
            }

            if ($name_attribute == "dt_tong_tien_hd") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_don_hang']);
            }
            if ($name_attribute == "dt_khach_hang_no") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_don_hang'] - $exportDoanhThuSum['sum_thuc_thu']);
            }
            if ($name_attribute == "dt_tong_tien_thuc_thu") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_thuc_thu']);
            }
            if ($name_attribute == "dt_thuc_thu_the") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_thuc_thu_the']);
            }
            if ($name_attribute == "dt_thuc_thu_tien_mat") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_thuc_thu_tien_mat']);
            }
            if ($name_attribute == "dt_tong_thanh_toan") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_thanh_toan_the'] + $exportDoanhThuSum['sum_thanh_toan_tien_mat']);
            }
            if ($name_attribute == "dt_thanh_toan_the") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_thanh_toan_the']);
            }
            if ($name_attribute == "dt_thanh_toan_tien_mat") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_thanh_toan_tien_mat']);
            }
            if ($name_attribute == "dt_tong_dat_coc") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_dat_coc_the'] + $exportDoanhThuSum['sum_dat_coc_tien_mat']);
            }
            if ($name_attribute == "dt_dat_coc_the") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_dat_coc_the']);
            }
            if ($name_attribute == "dt_dat_coc_tien_mat") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_dat_coc_tien_mat']);
            }
            if ($name_attribute == "dt_hoan_coc") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_tien_hoan_coc']);
            }
            if ($name_attribute == "dt_hoan_coc_the") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_tien_hoan_coc_the']);
            }
            if ($name_attribute == "dt_hoan_coc_tien_mat") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_tien_hoan_coc_tien_mat']);
            }
            if ($name_attribute == "dt_tra_gop") {
                $sheet->setCellValue($alphabetic . $rowHeaderSum, $exportDoanhThuSum['sum_tra_gop']);
            }
        } //  END FOREACH SET STYLE


        $row++;
        foreach ($alphas as $key => $value) {
            $sheet->getStyle($value)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        }

        // FOREACH SET STYLE
        foreach ($arrayColumn as $alphabetic => $name_attribute) {
            if (in_array($name_attribute, $arrayAttributeNumber, true)) {
                $sheet->getStyle($alphabetic)->getNumberFormat()->setFormatCode('#,##0'); // format number
            }
            if ($name_attribute == "dt_chi_tiet_giao_dich") {
                $sheet->getColumnDimension($alphabetic)->setWidth(45);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_chi_tiet_giao_dich']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_customer") {
                $sheet->getColumnDimension($alphabetic)->setWidth(25);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_customer']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_dich_vu") {
                $sheet->getColumnDimension($alphabetic)->setWidth(40);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_dich_vu']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

            if ($name_attribute == "dt_tong_tien_truoc_chiet_khau") {
                $sheet->getColumnDimension($alphabetic)->setWidth(25);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tong_tien_truoc_chiet_khau']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tong_tien_chiet_khau") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tong_tien_chiet_khau']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_chi_tiet_chiet_khau") {
                $sheet->getColumnDimension($alphabetic)->setWidth(45);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_chi_tiet_chiet_khau']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

            if ($name_attribute == "dt_order_code") {
                $sheet->getColumnDimension($alphabetic)->setWidth(15);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_order_code']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_trang_thai") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_trang_thai']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_trang_thai_hoan_thanh") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_trang_thai_hoan_thanh']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tong_tien_hd") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tong_tien_hd']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_khach_hang_no") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_khach_hang_no']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tong_tien_thuc_thu") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tong_tien_thuc_thu']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_thuc_thu_the") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_thuc_thu_the']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_thuc_thu_tien_mat") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_thuc_thu_tien_mat']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tong_thanh_toan") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tong_thanh_toan']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_thanh_toan_the") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_thanh_toan_the']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_thanh_toan_tien_mat") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_thanh_toan_tien_mat']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tong_dat_coc") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tong_dat_coc']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_dat_coc_the") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_dat_coc_the']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_dat_coc_tien_mat") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_dat_coc_tien_mat']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_hoan_coc") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_hoan_coc']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_hoan_coc_the") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_hoan_coc_the']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_hoan_coc_tien_mat") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_hoan_coc_tien_mat']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tra_gop") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tra_gop']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_huy_dich_vu") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_huy_dich_vu']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_co_so") {
                $sheet->getColumnDimension($alphabetic)->setWidth(10);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_co_so']);
                $sheet->getStyle($alphabetic)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_sale_pk") {
                $sheet->getColumnDimension($alphabetic)->setWidth(15);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_sale_pk']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_bac_si_mai") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_bac_si_mai']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_bac_si_lap") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_bac_si_lap']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_bac_si_loi") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_bac_si_loi']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_bac_si_khac") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_bac_si_khac']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tro_thu_mai") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tro_thu_mai']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tro_thu_lap") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tro_thu_lap']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tro_thu_loi") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tro_thu_loi']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_tro_thu_khac") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_tro_thu_khac']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_created_at") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_created_at']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            if ($name_attribute == "dt_created_by") {
                $sheet->getColumnDimension($alphabetic)->setWidth(20);
                $sheet->setCellValue($alphabetic . $rowHeader, $listField['dt_created_by']);
                $sheet->getStyle($alphabetic . $rowHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        } //  END FOREACH SET STYLE

        // DO DU LIEU
        foreach ($aData as $indexData => $rowData) {
            // STT
            $indexData++;
            $sheet->setCellValue('A' . $row, $indexData);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // DU LIEU
            foreach ($arrayColumn as $alphabetic => $name_attribute) {
                $sheet->setCellValue($alphabetic . $row, $rowData[$name_attribute]);
            }
            $row++;
        }
    }


    public function getArrayColumn()
    {
        //        $alphas = range('B', 'Z');
        $alphas = $this->createColumnsArray('AZ');
        unset($alphas[0]);
        $arr = CustomerDoanhThuSearch::getlistField();
        $listColumn = $setListColumn = [];
        foreach ($arr as $key => $val) {
            $listColumn[$key] = MyComponent::getCookies($key) !== false ? MyComponent::getCookies($key) : 1;
        }
        foreach ($listColumn as $key => $value) {
            if ($value != 0) {
                $setListColumn[] = $key;
            }
        }
        $alphas = array_slice($alphas, 0, count($setListColumn));
        return array_combine($alphas, $setListColumn);
    }

    // A => ZZ https://stackoverflow.com/questions/14278603/php-range-from-a-to-zz
    public function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column) {
                return $columns;
            }
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }


    public function actionTest()
    {
        //        $wThanhToan = ThanhToanModel::find();
        //        $wThanhToan->select('DISTINCT(phong_kham_don_hang_id)');
        //        $wThanhToan->andFilterWhere(['tam_ung' => 1]);
        //        $list = $wThanhToan->all();
        //        $ArrayDonHangID = [];
        //        foreach ($list as $item){
        //            $ArrayDonHangID[] = $item->phong_kham_don_hang_id;
        //        }
        //        print_r($ArrayDonHangID);
        //        die;
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

    protected function findModel($id)
    {
        if (($model = PhongKhamDonHangWThanhToan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            $customer = ThanhToanModel::findOne(['id' => $id]);
            try {
                $user = new User();
                $roleUser = $user->getRoleName(\Yii::$app->user->id);
                if (!in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                    return [
                        'status' => 'failure'
                    ];
                }
                if ($this->findModel($id)->delete()) {
                    $user_timeline = new UserTimelineModel();
                    $user_timeline->action = [UserTimelineModel::ACTION_XOA, UserTimelineModel::ACTION_THANH_TOAN];
                    $user_timeline->customer_id = $customer->customer_id;
                    if (!$user_timeline->save()) {
                        $transaction->rollBack();
                    }
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

    protected function findModelOrder($id)
    {
        return PhongKhamDonHang::find()
            ->select([
                "phong_kham_don_hang.*",
                "(SELECT SUM(" . PhongKhamDonHangWOrder::tableName() . ".thanh_tien) FROM " . PhongKhamDonHangWOrder::tableName() . " WHERE " . PhongKhamDonHangWOrder::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id) AS dh_thanh_tien",
                "(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::DAT_COC . "') AS dat_coc",
                "(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::THANH_TOAN . "') AS thanh_toan"
            ])
            ->leftJoin(PhongKhamDonHangWOrder::tableName(), PhongKhamDonHangWOrder::tableName() . '.phong_kham_don_hang_id=' . PhongKhamDonHang::tableName() . '.id')
            ->where([PhongKhamDonHang::tableName() . '.id' => $id])
            ->groupBy(PhongKhamDonHang::tableName() . '.id')->one();
    }

    public function actionConfirm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post('data');
            $data = json_decode($data);
            $model = PhongKhamDonHang::findOne($data->id);
            $confirm = $model->getAttribute('confirm');
            if ($model !== null) {
                if ($confirm == 1) {
                    $confirm = 0;
                } else {
                    $confirm = 1;
                }
                try {
                    $model->updateAttributes([
                        'confirm' => $confirm,
                        'confirm_by' => \Yii::$app->user->identity->id,
                        'confirm_at' => time()
                    ]);

                    return [
                        'code' => 200,
                    ];
                } catch (Exception $exception) {
                    return [
                        'code' => 400,
                    ];
                }
            }
            return [
                'code' => 400,
            ];
        }
    }
}
