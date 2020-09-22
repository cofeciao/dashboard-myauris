<?php

namespace backend\modules\baocao\controllers;

use backend\components\MyComponent;
use backend\models\baocao\BaocaoChayAdsFaceModel;
use backend\models\CustomerModel;
use backend\modules\baocao\components\BaoCaoFaceAdsComponents;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\baocao\models\FormImport;
use backend\modules\user\models\User;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Yii;
use backend\modules\baocao\models\BaocaoChayAdsFace;
use backend\modules\baocao\models\search\BaocaoChayAdsFaceSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * BaocaoChayAdsFaceController implements the CRUD actions for BaocaoChayAdsFace model.
 */
class BaocaoChayAdsFaceController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new BaocaoChayAdsFaceSearch();
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

    public function actionDongBo()
    {
        if (Yii::$app->request->isAjax) {
            $from = strtotime(date('01-04-2019'));
            $to = strtotime(date('d-m-Y')) - 86400;
            $date = BaocaoChayAdsFaceModel::find()->andWhere(['between', 'ngay_chay', $from, $to])->all();

            foreach ($date as $key => $item) {
                $model = BaocaoChayAdsFaceModel::findOne($item->id);
                list($so_dien_thoai, $goi_duoc, $khach_den, $lich_hen) = self::getData(date('d-m-Y', $model->ngay_chay), $model->location_id, $model->page_chay, $model->san_pham);
                $model->so_dien_thoai = $so_dien_thoai;
                $model->goi_duoc = $goi_duoc;
                $model->khach_den = $khach_den;
                $model->lich_hen = $lich_hen;
                Yii::$app->response->format = Response::FORMAT_JSON;
                if (!$model->save()) {
                    return ['status' => 201, 'msg' => $model->getErrors()];
                }
            }
            return ['status' => 200, 'msg' => 'Đã đồng bộ xong.'];
        }
    }

    public function actionImport()
    {
        $model = new FormImport();

        return $this->renderAjax('import', [
            'model' => $model,
        ]);
    }

    public function actionImportSuccess()
    {
        $model = new FormImport();
        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                $file = UploadedFile::getInstance($model, 'fileExcel');
                $fileName = $file->baseName . '.' . $file->extension;
                $file->saveAs('uploads/temp/' . $fileName);
                $filePath = Yii::$app->basePath . '/web/uploads/temp/' . $fileName;
                $model->fileExcel = $filePath;
                if ($model->validate()) {
                    switch (strtoupper($file->extension)) {
                        case 'XLSX':
                            $reader = ReaderFactory::create(Type::XLSX);
                            break;
                        case 'CSV':
                            $reader = ReaderFactory::create(Type::CSV);
                            break;
                        default:
                            $reader = ReaderFactory::create(Type::XLSX);
                            break;
                    }

                    try {
                        $reader->open($model->fileExcel);
                    } catch (\Exception $exception) {
                        $status = 201;
                        $msg = 'Không mở được file';
                    }

                    foreach ($reader->getSheetIterator() as $sheetIndex => $sheet) {
                        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                            if ($rowIndex == 1) {
                                continue;
                            }
                            if ($row[0] == null) {
                                break;
                            }
                            $date = json_decode(json_encode($row[0]), true);
                            $datetime = new \DateTime($date['date']);
                            $date = $datetime->format('d-m-Y');
                            //var_dump($date);die;
                            $date = strtotime($date);

                            $page_chay = 2;
                            $sanPham = 1;
                            $location_id = 2;

                            $dataExits = BaocaoChayAdsFace::find()->where(['ngay_chay' => $date, 'page_chay' => $page_chay, 'san_pham' => $sanPham, 'location_id' => $location_id, 'don_vi' => Yii::$app->user->id])->one();

                            if ($dataExits == null) {
                                $dataExits = new BaocaoChayAdsFace();
                                $dataExits->ngay_chay = $date;
                            }

                            $dataExits->page_chay = $page_chay;
                            $dataExits->san_pham = $sanPham;
                            $dataExits->location_id = $location_id;
                            $dataExits->so_tien_chay = (string)$row[1];
                            $dataExits->hien_thi = $row[2];
                            $dataExits->tiep_can = $row[3];
                            $dataExits->binh_luan = (int)$row[4];
                            $dataExits->tin_nhan = (int)$row[5];
                            $dataExits->tuong_tac = $dataExits->binh_luan + $dataExits->tin_nhan;
                            $dataExits->don_vi = Yii::$app->user->id;

                            if ($dataExits->save()) {
                                $status = 200;
                                $msg = 'Import thành công';
                            } else {
                                $status = 201;
                                $msg = 'Không lưu được dữ liệu vào database';
                            }
                        }
                    }
                    $reader->close();
                } else {
                    $status = 201;
                    $msg = 'Không kiểm tra được file tải lên';
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status' => $status, 'msg' => $msg];
            }
        }
    }

    public function actionImportAdsFile()
    {
        set_time_limit(false);
        $model = new FormImport();
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'fileExcel');
            $fileName = $file->baseName . '.' . $file->extension;
            $file->saveAs('uploads/temp/' . $fileName);
            $filePath = Yii::$app->basePath . '/web/uploads/temp/' . $fileName;
            $model->fileExcel = $filePath;
            if ($model->validate()) {
                switch (strtoupper($file->extension)) {
                    case 'XLSX':
                        $reader = ReaderFactory::create(Type::XLSX);
                        break;
                    case 'CSV':
                        $reader = ReaderFactory::create(Type::CSV);
                        break;
                    default:
                        $reader = ReaderFactory::create(Type::XLSX);
                        break;
                }
//                var_dump($model->fileExcel);die;
                try {
                    $reader->open($model->fileExcel);
                } catch (\Exception $exception) {
                    $model->addErrors(['fileExcel' => $exception]);
                    return $this->render('import-ads-file', [
                        'model' => $model,
                    ]);
                }

                foreach ($reader->getSheetIterator() as $sheetIndex => $sheet) {
                    foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                        if ($rowIndex == 1) {
                            continue;
                        }
                        $date = json_decode(json_encode($row[0]), true);
                        $date = strtotime($date['date']);
                        $dateNotInt = date('d-m-Y', $date);
                        $date = strtotime($dateNotInt);

                        $dataExits = BaocaoChayAdsFace::find()->where(['ngay_chay' => $date])->one();

                        if ($dataExits == null) {
                            $dataExits = new BaocaoChayAdsFace();
                            $dataExits->ngay_chay = $date;
                        }

                        $dataExits->hien_thi = null;
                        $dataExits->tiep_can = null;
                        $dataExits->tin_nhan = str_replace('.', '', $row[3]) == 0 ? 1 : str_replace('.', '', $row[3]);
                        $dataExits->binh_luan = str_replace('.', '', $row[4]) == 0 ? 1 : str_replace('.', '', $row[4]);
                        $dataExits->so_tien_chay = str_replace('.', '', $row[6]);

                        $dataExits->don_vi = Yii::$app->user->id;

                        $dataExits->page_chay = null;

                        //Tính toán
                        $dataExits->tuong_tac = str_replace('.', '', $row[5]) == 0 ? 1 : str_replace('.', '', $row[5]);
                        $dataExits->so_dien_thoai = BaoCaoFaceAdsComponents::getPhoneCustomerWithDay($dateNotInt);
                        $dataExits->goi_duoc = BaoCaoFaceAdsComponents::getPhoneCallCustomerWithDay($dateNotInt);
                        $dataExits->khach_den = BaoCaoFaceAdsComponents::getKhachDenCustomerWithDay($dateNotInt);
                        $dataExits->lich_hen = BaoCaoFaceAdsComponents::getLichHenCustomerWithDay($dateNotInt);
                        $dataExits->khach_den = BaoCaoFaceAdsComponents::getKhachDenCustomerWithDay($dateNotInt);
                        $dataExits->money_hienthi = null;
                        $dataExits->money_tiepcan = null;
                        if ($dataExits->binh_luan == 0) {
                            $dataExits->money_binhluan = 0;
                        } else {
                            $dataExits->money_binhluan = (int)round($dataExits->so_tien_chay / $dataExits->binh_luan, 0);
                        }
                        if ($dataExits->tin_nhan == 0) {
                            $dataExits->money_tinnhan = 0;
                        } else {
                            $dataExits->money_tinnhan = (int)round($dataExits->so_tien_chay / $dataExits->tin_nhan, 0);
                        }

                        if ($dataExits->tuong_tac == 0) {
                            $dataExits->money_tuongtac = 0;
                        } else {
                            $dataExits->money_tuongtac = (int)round($dataExits->so_tien_chay / $dataExits->tuong_tac, 0);
                        }
                        if ($dataExits->so_dien_thoai == 0) {
                            $dataExits->money_sodienthoai = 0;
                        } else {
                            $dataExits->money_sodienthoai = (int)round($dataExits->so_tien_chay / $dataExits->so_dien_thoai, 0);
                        }
                        if ($dataExits->goi_duoc == 0) {
                            $dataExits->money_goiduoc = 0;
                        } else {
                            $dataExits->money_goiduoc = (int)round($dataExits->so_tien_chay / $dataExits->goi_duoc, 0);
                        }
                        if ($dataExits->lich_hen == 0) {
                            $dataExits->money_lichhen = 0;
                        } else {
                            $dataExits->money_lichhen = (int)round($dataExits->so_tien_chay / $dataExits->lich_hen, 0);
                        }
                        if ($dataExits->khach_den == 0) {
                            $dataExits->money_khachden = 0;
                        } else {
                            $dataExits->money_khachden = (int)round($dataExits->so_tien_chay / $dataExits->khach_den, 0);
                        }

                        if ($dataExits->validate()) {
                            if ($dataExits->save()) {
                                Yii::$app->session->setFlash('alert', [
                                    'body' => Yii::$app->params['create-success'],
                                    'class' => 'bg-success',
                                ]);
                            } else {
                                if (Yii::$app->user->id == 1) {
                                    var_dump($dataExits->getErrors());
                                    die;
                                }
                            }
                        } else {
                            if (Yii::$app->user->id == 1) {
                                var_dump($dataExits->getErrors());
                                die;
                            }
                        }
                    }
                }
                $reader->close();
            }
        }

        return $this->render('import-ads-file', [
            'model' => $model,
        ]);
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

    public function actionValidateChayAdsFace($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new BaocaoChayAdsFace();
            if ($id != null) {
                $model = BaocaoChayAdsFace::find()->where(['id' => $id])->one();
            }
            $model->scenario = BaocaoChayAdsFace::CHAY_ADS;
            if ($model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionCreate()
    {
        $model = new BaocaoChayAdsFace();
        $model->scenario = BaocaoChayAdsFace::CHAY_ADS;

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $day = $model->ngay_chay;
            $ngay_chay = strtotime($day);

            $query = CustomerModel::find()->where(['status' => CustomerModel::STATUS_DH]);
            $query->andWhere('dat_hen is null');
            $query->andWhere(['between', 'time_lichhen', $ngay_chay, $ngay_chay + 86399]);
            $valiDateCustomer = $query->count();
            if ($valiDateCustomer > 0) {
                return [
                    'status' => 403,
                    'mess' => 'Tạo mới thất bại. Cập nhật hết khách đến hoặc chưa đến rồi thao tác lại.',
                    'error' => $model->getErrors(),
                ];
            }

            if (!$model->validate()) {
                return [
                    'status' => 400,
                    'mess' => 'Có lỗi xảy ra',
                    'error' => $model->getErrors()
                ];
            }

            try {
                $location_id = $model->location_id;
                $so_tien_chay = $model->so_tien_chay;
                $hien_thi = $model->hien_thi;
                $tiep_can = $model->tiep_can;
                $binh_luan = $model->binh_luan;
                $tin_nhan = $model->tin_nhan;
                $page_chay = $model->page_chay;
                $sanPham = $model->san_pham;

                $check = BaocaoChayAdsFace::find()->where(['ngay_chay' => $ngay_chay, 'page_chay' => $page_chay, 'san_pham' => $sanPham, 'location_id' => $location_id, 'don_vi' => Yii::$app->user->id])->one();
                if ($check !== null) {
                    $model = $check;
                }

                $model->so_tien_chay = str_replace('.', '', $so_tien_chay);


                $model->don_vi = Yii::$app->user->id;
                $model->hien_thi = str_replace('.', '', $hien_thi);
                $model->tiep_can = str_replace('.', '', $tiep_can);
                $model->binh_luan = str_replace('.', '', $binh_luan);
                $model->tin_nhan = str_replace('.', '', $tin_nhan);
                $model->page_chay = $page_chay;
                $model->ngay_chay = $ngay_chay;
                $model->san_pham = $sanPham;

                //Tính toán
                $model->tuong_tac = (int)$binh_luan + (int)$tin_nhan;

                list($so_dien_thoai, $goi_duoc, $khach_den, $lich_hen) = self::getData($day, $location_id, $model->page_chay, $sanPham);
                $model->so_dien_thoai = $so_dien_thoai;
                $model->goi_duoc = $goi_duoc;
                $model->khach_den = $khach_den;
                $model->lich_hen = $lich_hen;

                if ($model->hien_thi == 0) {
                    $model->money_hienthi = 0;
                } else {
                    $model->money_hienthi = (int)round($model->so_tien_chay / $model->hien_thi, 0);
                }
                if ($model->tiep_can == 0) {
                    $model->money_tiepcan = 0;
                } else {
                    $model->money_tiepcan = (int)round($model->so_tien_chay / $model->tiep_can, 0);
                }
                if ($model->binh_luan == 0) {
                    $model->money_binhluan = 0;
                } else {
                    $model->money_binhluan = (int)round($model->so_tien_chay / $model->binh_luan, 0);
                }
                if ($model->tin_nhan == 0) {
                    $model->money_tinnhan = 0;
                } else {
                    $model->money_tinnhan = (int)round($model->so_tien_chay / $model->tin_nhan, 0);
                }

                if ($model->tuong_tac == 0) {
                    $model->money_tuongtac = 0;
                } else {
                    $model->money_tuongtac = (int)round($model->so_tien_chay / $model->tuong_tac, 0);
                }
                if ($model->so_dien_thoai == 0) {
                    $model->money_sodienthoai = 0;
                } else {
                    $model->money_sodienthoai = (int)round($model->so_tien_chay / $model->so_dien_thoai, 0);
                }
                if ($model->goi_duoc == 0) {
                    $model->money_goiduoc = 0;
                } else {
                    $model->money_goiduoc = (int)round($model->so_tien_chay / $model->goi_duoc, 0);
                }
                if ($model->lich_hen == 0) {
                    $model->money_lichhen = 0;
                } else {
                    $model->money_lichhen = (int)round($model->so_tien_chay / $model->lich_hen, 0);
                }
                if ($model->khach_den == 0) {
                    $model->money_khachden = 0;
                } else {
                    $model->money_khachden = (int)round($model->so_tien_chay / $model->khach_den, 0);
                }

                if ($model->validate()) {
                    if ($model->save()) {
                        return [
                            'status' => 200,
                            'mess' => Yii::$app->params['update-success'],
                            'error' => $model->getErrors(),
                        ];
                    } else {
                        if (Yii::$app->user->id == 1) {
                            var_dump($model->getErrors());
                            die;
                        }
                        return [
                            'status' => 403,
                            'mess' => Yii::$app->params['update-danger'],
                            'error' => $model->getErrors(),
                        ];
                    }
                } else {
                    if (Yii::$app->user->id == 1) {
                        var_dump($model->getErrors());
                        die;
                    }
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
            } catch (\yii\db\Exception $exception) {
                return [
                    'status' => 400,
                    'mess' => 'Lỗi kiểm tra dữ liệu!',
                    'error' => $exception->getMessage(),
                ];
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $model->scenario = BaocaoChayAdsFace::CHAY_ADS;

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                $model->so_tien_chay = str_replace('.', '', $model->so_tien_chay);

                try {
                    $model->don_vi = Yii::$app->user->id;
                    $location_id = $model->location_id;
                    $model->hien_thi = str_replace('.', '', $model->hien_thi);
                    $model->tiep_can = str_replace('.', '', $model->tiep_can);
                    $model->binh_luan = str_replace('.', '', $model->binh_luan);
                    $model->tin_nhan = str_replace('.', '', $model->tin_nhan);

                    $day = $model->ngay_chay;
                    $model->ngay_chay = strtotime($day);

                    //Tính toán
                    $model->tuong_tac = (int)$model->binh_luan + (int)$model->tin_nhan;

                    list($so_dien_thoai, $goi_duoc, $khach_den, $lich_hen) = self::getData($day, $location_id, $model->page_chay, $model->san_pham);
                    $model->so_dien_thoai = $so_dien_thoai;
                    $model->goi_duoc = $goi_duoc;
                    $model->khach_den = $khach_den;
                    $model->lich_hen = $lich_hen;

                    if ($model->hien_thi == 0) {
                        $model->money_hienthi = 0;
                    } else {
                        $model->money_hienthi = (int)round($model->so_tien_chay / $model->hien_thi, 0);
                    }
                    if ($model->tiep_can == 0) {
                        $model->money_tiepcan = 0;
                    } else {
                        $model->money_tiepcan = (int)round($model->so_tien_chay / $model->tiep_can, 0);
                    }
                    if ($model->binh_luan == 0) {
                        $model->money_binhluan = 0;
                    } else {
                        $model->money_binhluan = (int)round($model->so_tien_chay / $model->binh_luan, 0);
                    }
                    if ($model->tin_nhan == 0) {
                        $model->money_tinnhan = 0;
                    } else {
                        $model->money_tinnhan = (int)round($model->so_tien_chay / $model->tin_nhan, 0);
                    }

                    if ($model->tuong_tac == 0) {
                        $model->money_tuongtac = 0;
                    } else {
                        $model->money_tuongtac = (int)round($model->so_tien_chay / $model->tuong_tac, 0);
                    }
                    if ($model->so_dien_thoai == 0) {
                        $model->money_sodienthoai = 0;
                    } else {
                        $model->money_sodienthoai = (int)round($model->so_tien_chay / $model->so_dien_thoai, 0);
                    }
                    if ($model->goi_duoc == 0) {
                        $model->money_goiduoc = 0;
                    } else {
                        $model->money_goiduoc = (int)round($model->so_tien_chay / $model->goi_duoc, 0);
                    }
                    if ($model->lich_hen == 0) {
                        $model->money_lichhen = 0;
                    } else {
                        $model->money_lichhen = (int)round($model->so_tien_chay / $model->lich_hen, 0);
                    }
                    if ($model->khach_den == 0) {
                        $model->money_khachden = 0;
                    } else {
                        $model->money_khachden = (int)round($model->so_tien_chay / $model->khach_den, 0);
                    }

                    if ($model->validate()) {
                        if ($model->save()) {
                            return [
                                'status' => 200,
                                'mess' => Yii::$app->params['update-success'],
                                'error' => $model->getErrors(),
                            ];
                        } else {
                            if (Yii::$app->user->id == 1) {
                                var_dump($model->getErrors());
                                die;
                            }
                            return [
                                'status' => 403,
                                'mess' => Yii::$app->params['update-danger'],
                                'error' => $model->getErrors(),
                            ];
                        }
                    } else {
                        if (Yii::$app->user->id == 1) {
                            var_dump($model->getErrors());
                            die;
                        }
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
                } catch (\yii\db\Exception $exception) {
                    return [
                        'status' => 400,
                        'mess' => 'Lỗi kiểm tra dữ liệu!',
                        'error' => $exception->getMessage(),
                    ];
                }
            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    protected static function getData($day, $location_id, $page_chay, $sanPham)
    {
        $listUser = self::getNhanVienOnline();
        $so_dien_thoai = BaoCaoFaceAdsComponents::getPhoneCustomerInDay($day, $location_id, $page_chay, array_keys($listUser), $sanPham);
        $goi_duoc = BaoCaoFaceAdsComponents::getPhoneCallCustomerWithDay($day, $location_id, $page_chay, array_keys($listUser), $sanPham);
        $khach_den = BaoCaoFaceAdsComponents::getKhachDenCustomerWithDay($day, $location_id, $page_chay, array_keys($listUser), $sanPham);
        $lich_hen = BaoCaoFaceAdsComponents::getLichMoiCustomerWithDay($day, $location_id, $page_chay, array_keys($listUser), $sanPham);
        return [$so_dien_thoai, $goi_duoc, $khach_den, $lich_hen];
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $user = new User();
            $roleUser = $user->getRoleName(Yii::$app->user->id);
            if ($roleUser != \common\models\User::USER_ADMINISTRATOR && $roleUser != \common\models\User::USER_DEVELOP) {
                return [
                    "status" => "failure"
                ];
            }
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
        if (($model = BaocaoChayAdsFace::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected static function getNhanVienOnline()
    {
        $listNv = User::getNhanVienOnline();
        ksort($listNv);
        return $listNv;
    }
}
