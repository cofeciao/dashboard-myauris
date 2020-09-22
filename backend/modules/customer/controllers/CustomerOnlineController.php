<?php

namespace backend\modules\customer\controllers;

use backend\components\GapiComponent;
use backend\components\MtSmsComponent;
use backend\components\MyComponent;
use backend\models\CustomerModel;
use backend\models\Dep365CustomerOnlineRemindCall;
use backend\models\UserTimelineAction;
use backend\modules\clinic\controllers\ChupBanhMoiController;
use backend\modules\clinic\controllers\ChupCuiController;
use backend\modules\clinic\controllers\ChupFinalController;
use backend\modules\clinic\controllers\ChupHinhController;
use backend\modules\clinic\controllers\TkncController;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\DatHen;
use backend\modules\clinic\models\form\FormChupBanhMoi;
use backend\modules\clinic\models\form\FormChupCui;
use backend\modules\clinic\models\form\FormChupFinal;
use backend\modules\clinic\models\form\FormChupHinh;
use backend\modules\clinic\models\form\FormHinhTknc;
use backend\modules\clinic\models\PhongKhamChupBanhMoi;
use backend\modules\clinic\models\PhongKhamChupCui;
use backend\modules\clinic\models\PhongKhamChupFinal;
use backend\modules\clinic\models\PhongKhamChupHinh;
use backend\modules\clinic\models\PhongKhamHinhTknc;
use backend\modules\customer\models\CreateCustomer;
use backend\modules\customer\models\CustomerDatHen;
use backend\modules\customer\models\CustomerOnlineRemindCall;
use backend\modules\customer\models\Dep365CustomerFacebook;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatusTree;
use backend\modules\customer\models\Dep365CustomerOnlineImport;
use backend\modules\customer\models\form\FormImportCustomer;
use backend\modules\customer\models\search\Dep365CustomerOnlineSearch;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\customer\models\Dep365CustomerOnlineNguon;
use backend\modules\customer\models\Dep365CustomerOnlineTree;
use backend\modules\customer\models\Dep365CustomerOnlineBak;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\log\components\VhtCallLogComponent;
use backend\modules\log\data\VhtDataCallLog;
use backend\modules\setting\models\Dep365SettingSmsSend;
use backend\modules\user\models\UserTimelineModel;
use backend\modules\customer\models\Dep365SendSms;
use backend\modules\customer\models\FormSendSms;
use backend\modules\customer\models\FormImport;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\location\models\District;
use backend\modules\location\models\Province;
use backend\modules\setting\models\Setting;
use backend\controllers\CustomerController;
use GuzzleHttp\Exception\ClientException;
use yii\base\InvalidArgumentException;
use backend\modules\user\models\User;
use Box\Spout\Reader\ReaderFactory;
use yii\db\Exception;
use yii\helpers\Console;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\UserProfile;
use yii\bootstrap\ActiveForm;
use yii\base\ErrorException;
use common\helpers\MyHelper;
use Box\Spout\Common\Type;
use yii\web\UploadedFile;
use yii\db\Transaction;
use GuzzleHttp\Client;
use yii\helpers\Json;
use yii\web\Response;
use Yii;

/**
 * CustomerOnlineController implements the CRUD actions for Dep365CustomerOnline model.
 */
class CustomerOnlineController extends CustomerController
{
    public function init()
    {
        parent::init();
        set_time_limit(1200);
    }

    public function actionIndex()
    {
        $searchModel = new Dep365CustomerOnlineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }

        $pageSize = $dataProvider->pagination->pageSize;

        $totalCount = $dataProvider->totalCount;

        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        $edit = Yii::$app->request->get('edit');
        $customer_id = Yii::$app->request->get('customer_id');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'edit' => $edit,
            'customer_id' => $customer_id
        ]);
    }

    public function actionCustomerOnlineSmsSend()
    {
        $searchModel = new Dep365CustomerOnlineSearch();
        $dataProvider = $searchModel->searchSms(Yii::$app->request->queryParams);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }

        $pageSize = $dataProvider->pagination->pageSize;

        $totalCount = $dataProvider->totalCount;

        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('sms', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
        ]);
    }

    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }

    protected function sendSms($customerId, $smsTime = null, $smsLanThu)
    {
        $customerModel = new CustomerModel();
        $customer = $customerModel->getById($customerId);
        if ($customer == null) return false;
        $nameCustomer = $customer->forename == null ? $customer->name : $customer->forename;
        $nameCustomer = mb_convert_case($nameCustomer, MB_CASE_TITLE, "UTF-8");
        $time = date('H:i', $customer->time_lichhen);
        $date = date('d-m-Y', $customer->time_lichhen);
        $mtSms = new MtSmsComponent([
            'data' => [
                'name' => $nameCustomer,
                'sex' => $customer->sex,
                'phone' => $customer->phone,
                'date' => $date,
                'time' => $time
            ]
        ]);
        if ($mtSms->sendSms()) {
            try {
                $sms = new Dep365SendSms([
                    'sms_uuid' => 0,
                    'status' => $mtSms->getStatusCode(),
                    'customer_id' => $customerId,
                    'sms_text' => $mtSms->getMsgContent(),
                    'sms_to' => $customer->phone,
                    'sms_time_send' => $smsTime,
                    'sms_lanthu' => $smsLanThu,
                    'type' => 'mtsms_vht'
                ]);
                $sms->save();
                return $mtSms->getStatusCode();
            } catch (Exception $ex) {
                Yii::warning('Log sms send failed: ' . $ex->getMessage());
                return false;
            }
        } else {
            Yii::warning('Send sms error ' . $mtSms->getStatusCode() . ': ' . $mtSms->getMessage());
            return false;
        }
        /*$uuid = 100;
        $status = 100;
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
                            $uuid = $value->id;
                            $status = $value->status;
                        }
                    }
                }
            }

            $sms = new Dep365SendSms();
            $sms->sms_uuid = $uuid;
            $sms->status = $status;
            $sms->customer_id = $customerId;
            $sms->sms_text = $content;
            $sms->sms_to = $phone;
            $sms->sms_time_send = $smsTime;
            $sms->sms_lanthu = $smsLanThu;
            if (!$sms->save()) {
                return false;
            }
            return $status;
        } catch (ClientException $e) {
            return false;
//            return $e->getRequest();
//            return $e->getResponse();
        }*/
    }

    public function actionGetNguonOnline()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $idAgency = Yii::$app->request->post('idAgency');
            $data = Dep365CustomerOnlineNguon::getAgencyForNguonOnline($idAgency);
            return $data;
        }
    }

    protected function sendSmsMany()
    {
        $url = 'http://sms3.vht.com.vn/ccsms/Sms/SMSService.svc/ccsms/json';

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
        try {
            $request = new \GuzzleHttp\Psr7\Request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ], $this->createJsonSms('OLA', '0906904884'));
            $promise = $client->sendAsync($request)->then(function ($response) {
                echo $response->getBody();
            });
            $promise->wait();
        } catch (ClientException $e) {
            echo $e->getRequest();
            echo $e->getResponse();
        }
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

    public function actionChangeDate()
    {
        $dataDefault = '13-3-2017';
        $customer = Dep365CustomerOnline::find()->where('dep365_customer_online.id >= 15000 and dep365_customer_online.id < 19000')->all();
        foreach ($customer as $key => $item) {
            try {
                if ($item->ngaythang == null || $item->ngaythang == '') {
                    $date = $dataDefault;
                } else {
                    $date = preg_replace('/\s+/', '', $item->ngaythang);
                    try {
                        try {
                            $date = \Yii::$app->formatter->asDate($date, 'd-M-Y');
                            $dataDefault = $date;
                        } catch (InvalidArgumentException $ix) {
                            $date = $dataDefault;
                        }
                    } catch (ErrorException $ex) {
                        $date = $dataDefault;
                    }
                }
            } catch (ErrorException $ex) {
                $date = $dataDefault;
            }

            $date = strtotime($date);
            if ($date < strtotime('1-1-1970')) {
                $date = strtotime($dataDefault);
            }
            $model = Dep365CustomerOnline::findOne($item->id);
            try {
                $model->updateAttributes(['created_at' => $date]);
            } catch (yii\db\Exception $ex) {
                continue;
            }
        }
    }

    public function actionImportCustomerOnline()
    {
        if (Yii::$app->request->isAjax) {
            set_time_limit(false);
            $model = new FormImport();
            if ($model->load(Yii::$app->request->post())) {
                $file = UploadedFile::getInstance($model, 'fileExcel');
                $fileName = $file->baseName . '.' . $file->extension;
                $file->saveAs('uploads/temp/' . $fileName);
                $filePath = Yii::$app->basePath . '\web\uploads\temp\\' . $fileName;
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
                        $model->addErrors(['fileExcel' => 'Vui lòng tải lên 1 file excel']);
                        return $this->render('import-customer-online', [
                            'model' => $model,
                        ]);
                    }
                    $dateKHDefault = '';
                    foreach ($reader->getSheetIterator() as $sheetIndex => $sheet) {
                        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                            if ($rowIndex == 1) {
                                continue;
                            }
                            try {
                                $dataExits = Dep365CustomerOnline::find()->where(['name' => $row[1], 'phone' => '0' . $row[2]])->asArray()->one();
                            } catch (yii\db\Exception $ex) {
                                continue;
                            }
                            if ($dataExits != null) {
                                continue;
                            }

                            //insert to database
                            $customerOnline = new Dep365CustomerOnline();

                            try {
                                $str = explode('/', $row[0]);
                            } catch (ErrorException $ex) {
                                try {
                                    $str = explode('-', $row[0]);
                                } catch (ErrorException $ex) {
                                    $str = [];
                                }
                            }
                            if (count($str) == 3) {
                                $dateKHDefault = $str[1] . '-' . $str[0] . '-' . $str[2];
                            }
                            $customerOnline->ngaythang = $dateKHDefault;
                            if ($row[1] == '' || $row[1] == null) {
                                continue;
                            }
                            $customerOnline->name = $row[1];
                            if ($row[2] == null || $row[2] == '') {
                                continue;
                            }
                            if (strlen($row[2]) == 9) {
                                $customerOnline->phone = '0' . $row[2];
                            } else {
                                $customerOnline->phone = $row[2];
                            }

                            if (mb_strtolower($row[3]) == 'nữ') {
                                $customerOnline->sex = 0;
                            } elseif (mb_strtolower($row[3]) == 'nam') {
                                $customerOnline->sex = 1;
                            } else {
                                $customerOnline->sex = 2;
                            }

                            $status_kh_goi = mb_strtolower($row[4]);
                            if ($status_kh_goi == 'đặt hẹn') {
                                $customerOnline->status = 1;
//                            $customerOnline->time_lichhen = $row[9];
                            }

                            $province = Province::find()->filterWhere(['like', 'name', $row[5]])->one();

                            if ($province) {
                                $customerOnline->province = $province->id;
                            }
                            $district = District::find()->filterWhere(['like', 'name', $row[5]])->one();
                            if ($district) {
                                $customerOnline->province = $district->ProvinceId;
                                $customerOnline->district = $district->id;
                            }

                            $hcm = ['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12',
                                'quận1', 'quận2', 'quận3', 'quận4', 'quận5', 'quận6', 'quận7', 'quận8', 'quận9', 'quận10', 'quận11', 'quận12',
                                'quận 1', 'quận 2', 'quận 3', 'quận 4', 'quận 5', 'quận 6', 'quận 7', 'quận 8', 'quận 9', 'quận 10', 'quận 11', 'quận 12',
                                'bình chánh', 'bình tân', 'bình thạnh', 'cần giờ', 'củ chi', 'gò vấp', 'hóc môn', 'nhà bè', 'phú nhuận',
                                'tân bình', 'tân phú', 'thủ đức'
                            ];
                            $hn = ['ba đình', 'ba vì', 'cầu giấy', 'chương mỹ', 'đan phượng', 'đông anh', 'đống đa', 'gia lâm', 'hà đông', 'hai bà trưng', 'hoài đức', 'hoàn kiếm', 'hoàng mai', 'long biên', 'mê linh', 'mỹ đức', 'phú xuyên', 'phú thọ', 'quốc oai'];

                            if (in_array(mb_strtolower($row[5]), $hcm)) {
                                $customerOnline->province = 79;
                            }
                            if (in_array(mb_strtolower($row[5]), $hn)) {
                                $customerOnline->province = 1;
                            }
                            if (mb_strtolower($row[5]) == 'bình dương') {
                                $customerOnline->province = 74;
                            }

                            try {
                                $customerOnline->note = (string)$row[6];
                            } catch (ErrorException $ex) {
                                $customerOnline->note = '';
                            }


                            $nguonKH = mb_strtolower($row[7]);
                            if ($nguonKH == 'facebook') {
                                $customerOnline->nguon_online = 1;
                            }
                            if ($nguonKH == 'zalo') {
                                $customerOnline->nguon_online = 2;
                            }
                            if ($nguonKH == 'website') {
                                $customerOnline->nguon_online = 3;
                            }
                            if ($nguonKH == 'hotline') {
                                $customerOnline->nguon_online = 4;
                            }

                            switch (mb_strtolower($row[11])) {
                                case 'nga':
                                    $idUser = 90;
                                    break;
                                case 'trâm':
                                    $idUser = 91;
                                    break;
                                case 'lanh':
                                    $idUser = 92;
                                    break;
                                case 'trúc':
                                    $idUser = 93;
                                    break;
                                case 'ly':
                                    $idUser = 94;
                                    break;
                                case 'thơ':
                                    $idUser = 95;
                                    break;
                                case 'thêu':
                                    $idUser = 96;
                                    break;
                                case 'đào':
                                    $idUser = 97;
                                    break;
                                case 'như':
                                    $idUser = 98;
                                    break;
                                default:
                                    $idUser = null;
                                    break;
                            }

                            $customerOnline->permission_user = $idUser;
                            if (!$customerOnline->save()) {
                                var_dump($customerOnline->getErrors());
                                die;
                            }
                        }
                    }
                    $reader->close();
                }
            }

            return $this->renderAjax('import-customer-online', [
                'model' => $model,
            ]);
        }
    }

    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $sendSmsForm = new FormSendSms(['scenario' => 'checkSms']);
            $smsSended = Dep365SendSms::find()->where(['customer_id' => $id])->orderBy(['created_at' => SORT_DESC])->all();

//            if ($sendSmsForm->load(Yii::$app->request->post()) && $sendSmsForm->validate() && $model->getAttribute('status') == 1) {
//                \Yii::$app->response->format = Response::FORMAT_JSON;
//                $content = MyHelper::smsKhongDau($sendSmsForm->sms_text);
//                $customerId = $sendSmsForm->customer_id;
//                $phone = $sendSmsForm->sms_to;
//                $smsLanthu = $sendSmsForm->sms_lanthu;
//                $result = $this->sendSms($customerId, null, $smsLanthu, $content, $phone);
//
//                $class = $result == 0 ? 'bg-success' : 'bg-danger';
//
//                Yii::$app->session->setFlash('alert', [
//                    'body' => $this->smsErrorStatus($result),
//                    'class' => $class,
//                ]);
//                return $this->refresh();
//            }

            if ($model) {
                return $this->renderAjax('@backend/views/layouts/customer_view', [
                    'model' => $model,
                    'sendSmsForm' => $sendSmsForm,
                    'smsSended' => $smsSended,
                ]);
            }
        }
    }

    public function actionSendSms()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $customerId = Yii::$app->request->post('customer_id');
            $sms_lanthu = Yii::$app->request->post('sms_lanthu');
            $result = $this->sendSms($customerId, null, $sms_lanthu);

            if ($result === false) {
                return [
                    'status' => 403,
                    'text' => 'Lỗi gửi SMS. Hãy liên hệ bộ phận kỹ thuật!',
                ];
            }

            $mtSms = new MtSmsComponent();
            return [
                'status' => $result,
                'text' => $mtSms->getMessage($result)
            ];
        }
    }

//    public function actionChangeSmsCustomer()
//    {
//        if (Yii::$app->request->isAjax) {
//            \Yii::$app->response->format = Response::FORMAT_JSON;
//
////            $idSms = Yii::$app->request->post('id');
//
//            $idCustomer = Yii::$app->request->post('idCustomer');
//
//            $customerFind = Dep365CustomerOnline::findOne($idCustomer);
//
//            $coso = new Dep365CoSo();
//            $coso = $coso->getCoSoOne($customerFind->co_so);
//            if ($coso == null) {
//                return [
//                    'status' => 403,
//                    'text' => 'Chưa có dữ liệu',
//                ];
//            }
////            $address = $coso->address;
//
//            $nameCustomer = $customerFind->forename == null ? $customerFind->name : $customerFind->forename;
//            $nameCustomer = mb_convert_case($nameCustomer, MB_CASE_TITLE, "UTF-8");
//
////            $customer = MyHelper::smsKhongDau($nameCustomer);
//            $time = date('H:i', $customerFind->time_lichhen);
//            $date = date('d-m-Y', $customerFind->time_lichhen);
//
//            /*$employees = MyHelper::smsKhongDau(UserProfile::find()->where(['user_id' => $customerFind->created_by])->one()->fullname);
//            $sex = $customerFind->sex == 0 ? 'chi' : 'anh';
//
//            $smsSend = new Dep365SettingSmsSend();
//            $smsChar = $smsSend->getSettingSmsSendOne($idSms)->content;
//
//            $smsChar = str_replace('{$address}', $address, $smsChar);
//            $smsChar = str_replace('{$customer}', $customer, $smsChar);
//            $smsChar = str_replace('{$time}', $time, $smsChar);
//            $smsChar = str_replace('{$date}', $date, $smsChar);
//            $smsChar = str_replace('{$employees}', $employees, $smsChar);
//            $smsChar = str_replace('{$sex}', $sex, $smsChar);*/
//            $mtSms = new MtSmsComponent([
//                'data' => [
//                    'name' => $nameCustomer,
//                    'sex' => $customerFind->sex,
//                    'phone' => $customerFind->phone,
//                    'date' => $date,
//                    'time' => $time
//                ]
//            ]);
//
//            return [
//                'status' => 1,
//                'text' => $mtSms->getMsgContent(),
//            ];
//        }
//    }

    public function actionViewSendSms($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $mtSms = new MtSmsComponent([
                'data' => [
                    'name' => $model->forename == null ? $model->name : $model->forename,
                    'phone' => $model->phone,
                    'sex' => $model->sex,
                    'date' => date('d-m-Y', $model->time_lichhen),
                    'time' => date('H:i', $model->time_lichhen)
                ]
            ]);
            $sms_text = $mtSms->getMsgContent();
//            $sendSmsForm = new FormSendSms(['scenario' => 'checkSms']);
            $smsSended = Dep365SendSms::find()->where(['customer_id' => $id])->orderBy(['created_at' => SORT_DESC])->all();

            if ($model) {
                return $this->renderAjax('view-send-sms', [
                    'model' => $model,
                    'sms_text' => $sms_text,
//                    'sendSmsForm' => $sendSmsForm,
                    'smsSended' => $smsSended,
                ]);
            }
        }
    }

    public function actionValidateSms()
    {
        $model = new FormSendSms();
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionValidateOnline($id = null)
    {
        if (Yii::$app->request->isAjax) {
            if ($id == null) {
                $model = new Dep365CustomerOnline();
            } else {
                $model = $this->findModel($id);
            }
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                $user = new User();
                $roleUser = $user->getRoleName(Yii::$app->user->id);
                if (in_array($roleUser, [
                    User::USER_DATHEN,
                ])) {
                    $model->scenario = Dep365CustomerOnline::SCENARIO_DAT_HEN;
                }
                if (in_array($roleUser, [
                    User::USER_NHANVIEN_ONLINE,
                    User::USER_MANAGER_ONLINE,
                ])) {
                    $model->scenario = Dep365CustomerOnline::SCENARIO_TU_VAN;
                }
                if (in_array($roleUser, [
                    User::USER_ADMINISTRATOR,
                    User::USER_DEVELOP
                ])) {
                    $model->scenario = Dep365CustomerOnline::SCENARIO_ADMIN;
                }

                return ActiveForm::validate($model);
            }
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Dep365CustomerOnline();
            $modelRemindCall = new CustomerOnlineRemindCall();
            $user_timeline = new UserTimelineModel();
            $user_timeline->action = UserTimelineModel::ACTION_THEM;
//            $model->scenario = Dep365CustomerOnline::PHONE_CREATE;

            $user = new User();
            $roleUser = $user->getRoleName(Yii::$app->user->id);
            if ($roleUser == User::USER_NHANVIEN_ONLINE || $roleUser == User::USER_MANAGER_ONLINE) {
                $model->scenario = Dep365CustomerOnline::SCENARIO_TU_VAN;
            }

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->validate()) {
                    if ($model->customer_come != null) {
                        $model->customer_come = strtotime($model->customer_come);
                    }
                    if ($model->time_lichhen != null) {
                        $model->time_lichhen = strtotime($model->time_lichhen);
                        $model->date_lichhen = strtotime(date('d-m-Y', $model->time_lichhen));
                    }
                    if ($model->status == CustomerModel::STATUS_DH) {
                        $user_timeline->action = [UserTimelineModel::ACTION_TAO, UserTimelineModel::ACTION_DAT_HEN];
                    }
                    $transaction = Yii::$app->db->beginTransaction(
                        Transaction::SERIALIZABLE
                    );

                    try {
                        $dathen = $model->status;
                        if ($dathen != Dep365CustomerOnline::STATUS_DH) {
                            $model->tt_kh = null;
                            $model->time_lichhen = null;
                            $model->date_lichhen = null;
                            $model->co_so = null;
                            $model->dat_hen = null;
                        }
                        if ($dathen != CustomerModel::STATUS_FAIL && !($dathen == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN)) {
                            $model->status_fail = null;
                        }
                        if ($model->dat_hen != Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                            $model->dat_hen_fail = null;
                        }
                        $model->permission_user = Yii::$app->user->id;
                        $model->ngaythang = \Yii::$app->formatter->asDate(time(), 'd-M-Y');
                        $model->name = mb_convert_case($model->name, MB_CASE_TITLE, "UTF-8");
                        $model->forename = mb_convert_case($model->forename, MB_CASE_TITLE, "UTF-8");
                        if (!$model->save()) {
                            $transaction->rollBack();
                            return [
                                'status' => 403,
                                'mess' => Yii::$app->params['create-danger'],
                                'error' => $model->getErrors(),
                                'model' => $model
                            ];
                        }
                        if ($model->dat_hen == Dep365CustomerOnline::STATUS_DH) {
                            $modelDatHen = new CustomerDatHen();
                            $modelDatHen->customer_id = $model->primaryKey;
                            $modelDatHen->user_id = $model->permission_user;
                            $modelDatHen->dat_hen_moi_cu = 1;
                            $modelDatHen->dat_hen_co_so = $model->co_so;
                            if (!$modelDatHen->save()) {
                                //                            var_dump($modelDatHen->getErrors());die;
                                $transaction->rollBack();
                            }
                        }

                        /*
                         * NHẮC LỊCH
                         *
                         * NV - TƯ VẤN ONLINE + NV - TRƯỞNG TƯ VẤN ONLINE : chỉ thấy được trạng thái đặt lịch & không thấy lý do khách không đến
                         * => không cần lưu nhắc lịch khi trạng thái đặt lịch là ĐẶT HẸN
                         *
                         * NV - CẬP NHẬT ĐẶT HẸN + ADMIN + DEVELOP : thấy được trạng thái đặt lịch & trạng thái khách đến
                         * => lưu nhắc lịch khi trạng thái đặt lịch != ĐẶT HẸN && dat_hen != DAT_HEN_DEN
                        */
                        if (in_array($roleUser, [User::USER_NHANVIEN_ONLINE, User::USER_MANAGER_ONLINE]) && $model->status != CustomerModel::STATUS_DH) {
                            /* NV - TƯ VẤN ONLINE + NV - TRƯỞNG TƯ VẤN ONLINE & trạng thái đặt lịch != ĐẶT HẸN */
                            $modelRemindCall->customer_id = $model->primaryKey;
                            $modelRemindCall->status = $model->status;
                            $modelRemindCall->status_fail = $model->status_fail;
                            $modelRemindCall->note = $model->note_remind_call;
                            /* Trạng thái KBM => nhắc nhở ngày hôm sau phải gọi lại ngay */
                            $modelRemindCall->remind_call_time = $model->status == CustomerModel::STATUS_KBM ? strtotime(date('d-m-Y', strtotime('+1days'))) : ($model->remind_call_time != null ? strtotime($model->remind_call_time) : null);
                            if (!$modelRemindCall->save()) {
                                $transaction->rollBack();
                                return [
                                    'status' => 403,
                                    'mess' => Yii::$app->params['create-danger'],
                                    'model' => $modelRemindCall
                                ];
                            }
                        } elseif (in_array($roleUser, [User::USER_DATHEN, User::USER_DEVELOP, User::USER_ADMINISTRATOR]) && $model->status == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                            /* NV - CẬP NHẬT ĐẶT HẸN + ADMIN + DEVELOP & trạng thái đặt lịch = ĐẶT HẸN & tráng thái khách đến = KHÔNG ĐẾN */
                            $modelRemindCall->customer_id = $model->primaryKey;
                            $modelRemindCall->status = $model->status;
                            $modelRemindCall->status_fail = $model->status_fail;
                            $modelRemindCall->dat_hen = $model->dat_hen;
                            $modelRemindCall->note = $model->note_remind_call;
                            /* Trạng thái KBM => nhắc nhở ngày hôm sau phải gọi lại ngay */
                            $modelRemindCall->remind_call_time = $model->status == CustomerModel::STATUS_KBM ? strtotime(date('d-m-Y', strtotime('+1days'))) : ($model->remind_call_time != null ? strtotime($model->remind_call_time) : null);
                            if (!$modelRemindCall->save()) {
                                $transaction->rollBack();
                                return [
                                    'status' => 403,
                                    'mess' => Yii::$app->params['create-danger'],
                                    'model' => $modelRemindCall
                                ];
                            }
                        }

                        $bak = new Dep365CustomerOnlineBak();
                        $bak->customer_online_id = $model->primaryKey;
                        foreach ($model->getAttributes() as $key => $value) {
                            if ($key == 'id') {
                                continue;
                            }
                            $bak->setAttribute($key, $value);
                        }
                        $bak->time_edit = time();
                        if (!$bak->save()) {
                            $transaction->rollBack();
                        }

                        $tree = new Dep365CustomerOnlineTree();
                        $tree->customer_online_id = $model->primaryKey;
                        $tree->status_id_new = $model->status;
                        $tree->user_id = $model->created_by;
                        $tree->time_change = $model->created_at;
                        if (!$tree->save()) {
                            $transaction->rollBack();
                        }

                        if ($model->status == CustomerModel::STATUS_FAIL) {
                            $failStatusTree = new Dep365CustomerOnlineFailStatusTree();
                            $failStatusTree->customer_online_id = $model->primaryKey;
                            $failStatusTree->fail_status_id_new = $model->status_fail;
                            $failStatusTree->user_id = $model->created_by;
                            $failStatusTree->time_change = $model->created_at;
                            if (!$failStatusTree->save()) {
                                $transaction->rollBack();
                            }
                        }
                        $user_timeline->customer_id = $model->primaryKey;
                        if (!$user_timeline->save()) {
                            $transaction->rollBack();
                        }
                        if ($dathen == Dep365CustomerOnline::STATUS_DH && $model->time_lichhen != '') {
                            $dayDathen = date('d-m-Y');
                            $from = strtotime($dayDathen);
                            $to = $from + 86399;
                            $query = Dep365CustomerOnlineDathenTime::find()->where(['customer_online_id' => $model->primaryKey]);
                            $query->andWhere(['between', 'time_change', $from, $to]);

                            $khachDH = $query->one();
                            if ($khachDH === null) {
                                $khachDH = new Dep365CustomerOnlineDathenTime();
                            } else {
                                $khachDH->time_lichhen = $model->time_lichhen;
                            }

                            $khachDH->customer_online_id = $model->primaryKey;
                            $khachDH->time_lichhen_new = $model->time_lichhen;
                            $khachDH->date_lichhen_new = strtotime(date('d-m-Y', $khachDH->time_lichhen_new));
                            $khachDH->user_id = Yii::$app->user->id;

                            $khachDH->date_change = strtotime(date('d-m-Y', $model->created_at));
                            $khachDH->time_change = $model->created_at;

                            if (!$khachDH->save()) {
                                $transaction->rollBack();
                            }
                        }

                        $transaction->commit();
                        if ($dathen == Dep365CustomerOnline::STATUS_DH && $model->time_lichhen != '') {
                            //Gửi sms cho khách hàng đặt hẹn
                            $validateSendSmsQuery = Dep365CustomerOnline::find()->where(['phone' => $model->phone]);
                            $validateSendSmsQuery->andWhere(['or', ['date_lichhen' => strtotime(date('d-m-Y'))], ['date_lichhen' => strtotime(date('d-m-Y') . ' +  1 day')]]);
                            $validateSendSms = $validateSendSmsQuery->one();

                            if ($validateSendSms != null) {
                                /*$coso = new Dep365CoSo();
                                $coso = $coso->getCoSoOne($model->co_so);

                                $address = $coso->address;

                                $nameCustomer = $model->forename == null ? $model->name : $model->forename;
                                $nameCustomer = mb_convert_case($nameCustomer, MB_CASE_TITLE, "UTF-8");

                                $customer = MyHelper::smsKhongDau($nameCustomer);
                                $time = date('H:i', $model->time_lichhen);
                                $date = date('d-m-Y', $model->time_lichhen);

                                $employees = MyHelper::smsKhongDau(UserProfile::find()->where(['user_id' => $model->created_by])->one()->fullname);
                                $sex = $model->sex == 0 ? 'chi' : 'anh';

                                $smsSend = new Dep365SettingSmsSend();
                                $smsChar = $smsSend->getSettingSmsSendOne(1)->content;

                                $smsChar = str_replace('{$address}', $address, $smsChar);
                                $smsChar = str_replace('{$customer}', $customer, $smsChar);
                                $smsChar = str_replace('{$time}', $time, $smsChar);
                                $smsChar = str_replace('{$date}', $date, $smsChar);
                                $smsChar = str_replace('{$employees}', $employees, $smsChar);
                                $smsChar = str_replace('{$sex}', $sex, $smsChar);*/
                                $this->sendSms($model->primaryKey, null, 100);
                            }
                        }

                        if ($dathen == CustomerModel::STATUS_DH) {
                            $cache = Yii::$app->cache;
                            $key = 'redis-screen-online';
                            $cache->set($key, [
                                'customer_id' => $model->primaryKey,
                                'status' => UserTimelineModel::ACTION_DAT_HEN
                            ]);
                        } else {
                            $cache = Yii::$app->cache;
                            $key = 'redis-screen-online';
                            $cache->set($key, [
                                'srcOnlTimeline' => UserTimelineModel::ACTION_TAO,
                            ]);
                        }

                        if ($dathen == Dep365CustomerOnline::DAT_HEN_DEN && CONSOLE_HOST == true/* && Yii::$app->request->userIP != '127.0.0.1'*/) {
                            $message = 'Chúc mừng ' . UserProfile::getFullName() . ' đã có một khách đặt hẹn!!!';
                            $client = new Client(['verify' => false]);
                            $client->request('POST', SOCKET_URL, [
                                'form_params' => [
                                    'handle' => 'dep365-alert',
                                    'data' => json_encode([
                                        'act' => 'customer-online-create-new',
                                        'message' => $message
                                    ])
                                ]
                            ]);
                        }
                        return [
                            'status' => 200,
                            'mess' => Yii::$app->params['create-success'],
                        ];
                    } catch (\yii\db\Exception $exception) {
                        $transaction->rollBack();
                        return [
                            'status' => 403,
                            'mess' => Yii::$app->params['create-danger'],
                            'error' => $exception->getMessage(),
                        ];
                    }
                } else {
                    return [
                        'status' => 400,
                        'mess' => 'Lỗi dữ liệu',
                        'error' => $model->getErrors()
                    ];
                }
            }

            return $this->renderAjax('create', [
                'model' => $model,
                'roleUser' => $roleUser
            ]);
        }
    }

    public function actionCreateCustomerFacebook()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Dep365CustomerFacebook();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                try {
                    $model->save();
                    return [
                        'code' => 200,
                        'msg' => Yii::$app->params['create-success']
                    ];
                } catch (Exception $ex) {
                    return [
                        'code' => 403,
                        'msg' => Yii::$app->params['create-danger'],
                        'error' => $ex->getMessage()
                    ];
                }
            }
            return $this->renderAjax('dep365-customer-facebook/create', [
                'model' => $model
            ]);
        }
    }

    public function actionValidateCustomerFacebook($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Dep365CustomerFacebook();
            if ($id != null && trim($id) != '') {
                $model = Dep365CustomerFacebook::find()->where([])->one();
            }
            if ($model->load(Yii::$app->request->post())) {
                return \yii\widgets\ActiveForm::validate($model);
            }
        }
    }

    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $modelRemindCall = CustomerOnlineRemindCall::find()->where(['type' => CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE, 'customer_id' => $id])->published()->orderBy(['id' => SORT_DESC])->one();
            if ($modelRemindCall == null) {
                $modelRemindCall = new CustomerOnlineRemindCall();
            } else {
                $model->remind_call_time = $modelRemindCall->remind_call_time;
                $model->note_remind_call = $modelRemindCall->note;
            }
            $user_timeline = new UserTimelineModel();
            $user_timeline->action = UserTimelineModel::ACTION_CAP_NHAT;
            $user = new User();
            $roleUser = $user->getRoleName(Yii::$app->user->id);
            if ($model->dat_hen == 1 && ($roleUser == User::USER_NHANVIEN_ONLINE || $roleUser == User::USER_MANAGER_ONLINE)) {
//                Yii::$app->response->format = Response::FORMAT_JSON;

                return $this->renderAjax('_error', [
                    'error' => 'Bạn không thể cập nhật khách hàng này.',
                ]);
            }

            if (in_array($roleUser, [
                User::USER_DATHEN,
            ])) {
                $model->scenario = Dep365CustomerOnline::SCENARIO_DAT_HEN;
            }

            if (in_array($roleUser, [
                User::USER_NHANVIEN_ONLINE,
                User::USER_MANAGER_ONLINE,
            ])) {
                $model->scenario = Dep365CustomerOnline::SCENARIO_TU_VAN;
            }

            if (in_array($roleUser, [
                User::USER_ADMINISTRATOR,
                User::USER_DEVELOP
            ])) {
                $model->scenario = Dep365CustomerOnline::SCENARIO_ADMIN;
            }

            $agencyUpdate = ['empty' => 'Empty string'];
            $key = 'redis-update-customer-online-district-' . $id;
            $district = $this->cache->get($key);
            if ($district === false) {
                $district = District::find()->where(['ProvinceId' => $model->province])->orderBy(['name' => SORT_DESC])->all();
                $this->cache->set($key, $district, 86400);
            }

            if ($model->agency_id != null) {
                $agencyUpdate = Dep365CustomerOnlineNguon::getAgencyForNguonOnline($model->agency_id);
            }

            $dathenOld = $model->status;
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->getOldAttribute('status') == 1 && $model->status != 1) {
                    return [
                        'status' => 403,
                        'mess' => 'Bạn không thể cập nhật khách hàng từ đặt hẹn xuống Fail hoặc KBM.',
                    ];
                }
                if ($model->time_lichhen != null) {
                    $model->time_lichhen = strtotime($model->time_lichhen);
                    $model->date_lichhen = strtotime(date('d-m-Y', $model->time_lichhen));
                }

                if ($model->status == CustomerModel::STATUS_FAIL) {
                    $model->note_tinh_trang_kh = null;
                    $model->note_mong_muon_kh = null;
                    $model->note_direct_sale_ho_tro = null;
                }
                if ($model->status == CustomerModel::STATUS_KBM) {
                    $model->note_tinh_trang_kh = null;
                    $model->note_mong_muon_kh = null;
                    $model->note_direct_sale_ho_tro = null;
                    $model->note = null;
                }

                if ($model->validate()) {
                    $dathen = $model->status;

                    if ($dathen != Dep365CustomerOnline::STATUS_DH) {
                        $model->tt_kh = null;
                        $model->time_lichhen = null;
                        $model->date_lichhen = null;
                        $model->co_so = null;
                        $model->dat_hen = null;
                    }
                    if ($dathen != CustomerModel::STATUS_FAIL && !($dathen == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN)) {
                        $model->status_fail = null;
                    }
                    if ($model->dat_hen != Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                        $model->dat_hen_fail = null;
                    }
                    $modelOld = $model->getOldAttributes();

                    $transaction = Yii::$app->db->beginTransaction(
                        Transaction::SERIALIZABLE
                    );
                    try {
                        $model->status = (int)$model->status;
                        $model->nguon_online = (int)$model->nguon_online;
                        $model->province = (int)$model->province;

                        if (!empty($model->customer_come) && ($roleUser == User::USER_DATHEN || $roleUser == User::USER_DEVELOP || $roleUser == User::USER_ADMINISTRATOR)) {
                            $model->dat_hen = (int)$model->dat_hen;
                            $model->customer_come = strtotime($model->customer_come);
                            $model->customer_come_date = strtotime(date('d-m-Y', $model->customer_come));
                        }
                        $model->name = mb_convert_case($model->name, MB_CASE_TITLE, "UTF-8");
                        $model->forename = mb_convert_case($model->forename, MB_CASE_TITLE, "UTF-8");
                        if ($model->permission_user == null) {
                            $model->permission_user = Yii::$app->user->id;
                        } else {
                            $user = new User();
                            $roleUser = $user->getRoleName(Yii::$app->user->id);
                            $userPermissionUser = User::find()->where(['id' => $model->permission_user, 'status' => User::STATUS_ACTIVE])->one();
                            if ($userPermissionUser == null && $model->created_by != null && in_array($roleUser, [User::USER_NHANVIEN_ONLINE, User::USER_MANAGER_ONLINE])) {
                                if (in_array($model->permission_old == null, [null, 0])) $model->permission_old = $model->permission_user;
                                $model->permission_user = Yii::$app->user->id;
                            }
                        }
                        /*
                         * Nếu khách hàng đặt hẹn mà không tới, giờ muốn đặt hẹn lại thì cập nhật lại thòi gian đặt hẹn và trạng thái đặt hẹn
                         */
                        $strtoTimeOfLichHen = $model->time_lichhen;
                        if ($strtoTimeOfLichHen > strtotime(date('d-m-Y')) &&
                            ($roleUser == User::USER_NHANVIEN_ONLINE || $roleUser == User::USER_MANAGER_ONLINE)) {
                            $model->dat_hen = null;
                        }
                        if ($model->save()) {
                            $modelNews = $model->getAttributes();
                            $time_update = $modelNews['updated_at'];
                            unset($modelOld['updated_at']);
                            unset($modelNews['updated_at']);
                            if ($modelNews != $modelOld) {
                                $bak = new Dep365CustomerOnlineBak();

                                foreach ($modelNews as $key => $value) {
                                    if ($key == 'id') {
                                        $bak->customer_online_id = $value;
                                        continue;
                                    }
                                    $bak->setAttribute($key, $value);
                                }

                                $bak->time_edit = time();
                                $bak->updated_at = $time_update;
                                $bak->user_edit = Yii::$app->user->id;
                                if (!$bak->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => 400,
                                        'mess' => Yii::$app->params['update-danger']
                                    ];
                                }

                                if ($modelNews['status'] != $modelOld['status']) {
                                    $tree = new Dep365CustomerOnlineTree();
                                    $tree->customer_online_id = $modelNews['id'];
                                    $tree->status_id = $modelOld['status'];
                                    $tree->status_id_new = $modelNews['status'];
                                    $tree->user_id = Yii::$app->user->id;
                                    $tree->time_change = time();
                                    if (!$tree->save()) {
                                        $transaction->rollBack();
                                        return [
                                            'status' => 400,
                                            'mess' => Yii::$app->params['update-danger']
                                        ];
                                    }
                                }

                                if ($modelNews['status'] != '1') {
                                    $failStatusTree = new Dep365CustomerOnlineFailStatusTree();
                                    $failStatusTree->customer_online_id = $modelNews['id'];
                                    $failStatusTree->fail_status_id = $modelOld['status_fail'];
                                    $failStatusTree->fail_status_id_new = $modelNews['status_fail'];
                                    $failStatusTree->user_id = $model->created_by;
                                    $failStatusTree->time_change = time();
                                    if (!$failStatusTree->save()) {
                                        $transaction->rollBack();
                                        return [
                                            'status' => 400,
                                            'mess' => Yii::$app->params['update-danger']
                                        ];
                                    }
                                }
                                if ($modelNews['status'] == '1') {
                                    $failStatusTree = new Dep365CustomerOnlineFailStatusTree();
                                    $failStatusTree->customer_online_id = $modelNews['id'];
                                    $failStatusTree->fail_status_id = $modelOld['status_fail'];
                                    $failStatusTree->fail_status_id_new = $modelNews['status_fail'];
                                    $failStatusTree->user_id = $model->created_by;
                                    $failStatusTree->time_change = time();
                                    if (!$failStatusTree->save()) {
                                        $transaction->rollBack();
                                        return [
                                            'status' => 400,
                                            'mess' => Yii::$app->params['update-danger']
                                        ];
                                    }
                                }

                                if ($dathen == 1 && $model->time_lichhen != '' && $modelNews['time_lichhen'] != $modelOld['time_lichhen']) {
                                    $dayDathen = date('d-m-Y');
                                    $query = Dep365CustomerOnlineDathenTime::find()->where(['customer_online_id' => $model->primaryKey]);
                                    $query->andWhere(['between', 'time_change', strtotime($dayDathen), strtotime($dayDathen) + 86399]);
                                    $khachDH = $query->one();
                                    if ($khachDH === null) {
                                        $khachDH = new Dep365CustomerOnlineDathenTime();
                                        $khachDH->time_lichhen = $modelOld['time_lichhen'];
                                    } else {
                                        $khachDH->time_lichhen = null;
                                    }

                                    $khachDH->customer_online_id = $model->primaryKey;
                                    $khachDH->time_lichhen_new = $modelNews['time_lichhen'];
                                    $khachDH->date_lichhen_new = strtotime(date('d-m-Y', $khachDH->time_lichhen_new));

                                    if ($modelOld['time_lichhen'] > $modelNews['time_lichhen'] &&
                                        ($roleUser != \common\models\User::USER_DEVELOP && $roleUser != \common\models\User::USER_ADMINISTRATOR && $roleUser != \common\models\User::USER_MANAGER_ONLINE)) {
                                        $transaction->rollBack();
                                        return [
                                            'status' => 403,
                                            'mess' => 'Ngày đặt hẹn mới không thể trước ngày đặt hẹn cũ.',
                                        ];
                                    }
                                    if ($model->created_by == null || $model->created_by == 1 || $model->created_by == '') {
                                        $khachDH->user_id = Yii::$app->user->id;
                                    } else {
                                        $khachDH->user_id = $model->permission_user;
                                    }
                                    $khachDH->date_change = strtotime(date('d-m-Y', time()));
                                    $khachDH->time_change = time();
                                    $user_timeline->action = [UserTimelineModel::ACTION_CAP_NHAT, UserTimelineModel::ACTION_DAT_HEN];
                                    $cache = Yii::$app->cache;
                                    $key = 'redis-screen-online';
                                    $cache->set($key, [
                                        'srcOnlTimeline' => UserTimelineModel::ACTION_CAP_NHAT,
                                    ]);
                                    if (!$khachDH->save()) {
                                        $transaction->rollBack();
                                        return [
                                            'status' => 400,
                                            'mess' => Yii::$app->params['update-danger']
                                        ];
                                    }
                                }
                            }
//                            if($model->getAttribute('status') != $model->getOldAttribute('status') || $model->getAttribute('dat_hen') != $model->getOldAttribute('dat_hen')) {
                            /*
                             * NHẮC LỊCH
                             * (ĐIỀU KIỆN: CÓ THAY ĐỔI STATUS HOẶC DAT_HEN)
                             *
                             * NV - TƯ VẤN ONLINE + NV - TRƯỞNG TƯ VẤN ONLINE :
                             * chỉ thấy được trạng thái đặt lịch & không thấy lý do khách không đến
                             * => cập nhật remind_call_status = STATUS_DISABLED nếu trạng thái đặt lịch = ĐẶT HẸN
                             *
                             *
                             * NV - CẬP NHẬT ĐẶT HẸN + ADMIN + DEVELOP :
                             * thấy được trạng thái đặt lịch & trạng thái khách đến
                             * => lưu nhắc lịch khi trạng thái đặt lịch != ĐẶT HẸN && dat_hen != ĐẶT HẸN ĐẾN
                            */
                            if ((in_array($roleUser, [User::USER_NHANVIEN_ONLINE, User::USER_MANAGER_ONLINE]) && $model->status == CustomerModel::STATUS_DH) ||
                                (in_array($roleUser, [User::USER_DATHEN, User::USER_DEVELOP, User::USER_ADMINISTRATOR]) && $model->status == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN)) {
                                /*
                                 * NV - TƯ VẤN ONLINE + NV - TRƯỞNG TƯ VẤN ONLINE && trạng thái đặt lịch = ĐẶT HẸN
                                 * NV - CẬP NHẬT ĐẶT HẸN + ADMIN + DEVELOP && trạng thái đặt hẹn = ĐẶT HẸN && trạng thái khách đến = ĐẶT HẸN ĐẾN
                                */
                                if ($modelRemindCall->primaryKey != null) {
                                    $modelRemindCall->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                                    if (!$modelRemindCall->save()) {
                                        $transaction->rollBack();
                                        return [
                                            'status' => 403,
                                            'mess' => Yii::$app->params['update-danger'],
                                        ];
                                    }
                                }
                            } elseif (in_array($roleUser, [User::USER_NHANVIEN_ONLINE, User::USER_MANAGER_ONLINE, User::USER_DEVELOP, User::USER_ADMINISTRATOR]) && $model->status != CustomerModel::STATUS_DH) {
                                /* NV - TƯ VẤN ONLINE + NV - TRƯỞNG TƯ VẤN ONLINE + ADMIN + DEVELOP & trạng thái đặt lịch != ĐẶT HẸN */
                                $modelRemindCall->customer_id = $model->primaryKey;
                                $modelRemindCall->status = $model->status;
                                $modelRemindCall->status_fail = $model->status_fail;
                                $modelRemindCall->note = $model->note_remind_call;
                                /* Trạng thái KBM & chưa chọn thời gian nhắc lịch => nhắc nhở ngày hôm sau phải gọi lại ngay */
                                $modelRemindCall->remind_call_time = $model->status == CustomerModel::STATUS_KBM && $model->remind_call_time == null ? strtotime(date('d-m-Y', strtotime('+1days'))) : ($model->remind_call_time != null ? strtotime($model->remind_call_time) : null);
                                if (!$modelRemindCall->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => 403,
                                        'mess' => Yii::$app->params['update-danger'],
                                        'error' => $modelRemindCall->getErrors(),
                                        'data' => $modelRemindCall
                                    ];
                                }
                            } elseif (in_array($roleUser, [User::USER_DATHEN, User::USER_DEVELOP, User::USER_ADMINISTRATOR]) && $model->status == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                                /* NV - CẬP NHẬT ĐẶT HẸN + ADMIN + DEVELOP & trạng thái đặt lịch = ĐẶT HẸN & tráng thái khách đến = KHÔNG ĐẾN */
                                $modelRemindCall->customer_id = $model->primaryKey;
                                $modelRemindCall->status = $model->status;
                                $modelRemindCall->status_fail = $model->status_fail;
                                $modelRemindCall->dat_hen = $model->dat_hen;
                                $modelRemindCall->dat_hen_fail = $model->dat_hen_fail;
                                $modelRemindCall->note = $model->note_remind_call;
                                /* Trạng thái KBM => nhắc nhở ngày hôm sau phải gọi lại ngay */
                                $modelRemindCall->remind_call_time = $model->status == CustomerModel::STATUS_KBM ? strtotime(date('d-m-Y', strtotime('+1days'))) : ($model->remind_call_time != null ? strtotime($model->remind_call_time) : null);

                                if (!$modelRemindCall->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => 403,
                                        'mess' => Yii::$app->params['update-danger'],
                                        'error' => $modelRemindCall->getErrors(),
                                        'data' => $modelRemindCall
                                    ];
                                }
                            }
                            /* END NHẮC LỊCH */
//                            }
                            /*
                             * Tính thời gian để lưu khách  đặt hẹn cũ
                             */
                            // Tính thời gian để được coi là 1 khách cũ (7 ngày)
                            $time = strtotime(date('d-m-Y')) - $model->created_at;
                            $days = floor($time / 86400); // $days > 7

                            $customer_id = $model->primaryKey;
                            //Lấy ra khách hàng đã đặt hẹn trong ngày của khách đang cập nhật
                            $fromDH = strtotime(date('d-m-Y'));
                            $toDH = $fromDH + 86400;
                            $modelDatHenOld = CustomerDatHen::find()->where(['customer_id' => $customer_id])->andWhere(['between', 'created_at', $fromDH, $toDH])->one();

                            //Cách tính đối với khách hàng cũ
                            $timeOld = strtotime(date('d-m-Y')) - $model->updated_at;

                            if ($dathenOld != 1 && $dathen == 1 && $modelDatHenOld === null) {
                                $modelDatHen = new CustomerDatHen();
                                $modelDatHen->customer_id = $customer_id;
                                $modelDatHen->user_id = $model->permission_user;
                                $modelDatHen->dat_hen_co_so = $model->co_so;
                                if ($time < 0) {
                                    $modelDatHen->dat_hen_moi_cu = 1;
                                } else {
                                    $modelDatHen->dat_hen_moi_cu = 2;
                                }
                                if (!$modelDatHen->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => 400,
                                        'mess' => Yii::$app->params['update-danger']
                                    ];
                                }
                                //Gửi sms cho khách hàng đặt hẹn
                                $validateSendSmsQuery = Dep365CustomerOnline::find()->where(['phone' => $model->phone]);
                                $validateSendSmsQuery->andWhere(['or', ['date_lichhen' => strtotime(date('d-m-Y'))], ['date_lichhen' => strtotime(date('d-m-Y') . ' +  1 day')]]);
                                $validateSendSms = $validateSendSmsQuery->one();
                                if ($validateSendSms != null) {
                                    /*$coso = new Dep365CoSo();
                                    $coso = $coso->getCoSoOne($model->co_so);

                                    $address = $coso->address;

                                    $nameCustomer = $model->forename == null ? $model->name : $model->forename;
                                    $nameCustomer = mb_convert_case($nameCustomer, MB_CASE_TITLE, "UTF-8");

                                    $customer = MyHelper::smsKhongDau($nameCustomer);
                                    $time = date('H:i', $model->time_lichhen);
                                    $date = date('d-m-Y', $model->time_lichhen);

                                    $employees = MyHelper::smsKhongDau(UserProfile::find()->where(['user_id' => $model->created_by])->one()->fullname);
                                    $sex = $model->sex == 0 ? 'chi' : 'anh';

                                    $smsSend = new Dep365SettingSmsSend();
                                    $smsChar = $smsSend->getSettingSmsSendOne(1)->content;

                                    $smsChar = str_replace('{$address}', $address, $smsChar);
                                    $smsChar = str_replace('{$customer}', $customer, $smsChar);
                                    $smsChar = str_replace('{$time}', $time, $smsChar);
                                    $smsChar = str_replace('{$date}', $date, $smsChar);
                                    $smsChar = str_replace('{$employees}', $employees, $smsChar);
                                    $smsChar = str_replace('{$sex}', $sex, $smsChar);*/
                                    $this->sendSms($id, null, 100);
                                }
                            } else {
                                if ($dathen == 1) {
                                    if ($time < 0) {
                                        if ($modelDatHenOld === null) {
                                            $modelDatHen = new CustomerDatHen();
                                            $modelDatHen->customer_id = $customer_id;
                                            $modelDatHen->user_id = $model->permission_user;
                                            $modelDatHen->dat_hen_moi_cu = 1;
                                            $modelDatHen->dat_hen_co_so = $model->co_so;
                                            if (!$modelDatHen->save()) {
                                                $transaction->rollBack();
                                                return [
                                                    'status' => 400,
                                                    'mess' => Yii::$app->params['update-danger']
                                                ];
                                            }
                                        }
                                    } else {
                                        if ($days > 7) {
                                            $modelDatHen = new CustomerDatHen();
                                            $modelDatHen->customer_id = $customer_id;
                                            $modelDatHen->user_id = $model->permission_user;
                                            $modelDatHen->dat_hen_moi_cu = 2;
                                            $modelDatHen->dat_hen_co_so = $model->co_so;

                                            if (!$modelDatHen->save()) {
                                                $transaction->rollBack();
                                                return [
                                                    'status' => 400,
                                                    'mess' => Yii::$app->params['update-danger']
                                                ];
                                            }
                                        }
                                    }
                                } else {
                                    if ($modelDatHenOld !== null) {
                                        $modelDatHenOld->delete();
                                        return [
                                            'status' => 400,
                                            'mess' => Yii::$app->params['update-danger']
                                        ];
                                    }
                                }
                            }
                            $user_timeline->customer_id = $model->primaryKey;
                            if (!$user_timeline->save()) {
                                $transaction->rollBack();
                                return [
                                    'status' => 400,
                                    'mess' => Yii::$app->params['update-danger']
                                ];
                            }

                            if ($modelNews['permission_user'] != $modelOld['permission_user']) {
                                try {
                                    Yii::$app->db->createCommand("UPDATE " . Dep365CustomerOnlineDathenTime::tableName() . " SET user_id='" . $modelNews['permission_user'] . "' WHERE customer_online_id='" . $model->primaryKey . "' AND user_id='" . $modelOld['permission_user'] . "'")->execute();
                                } catch (Exception $ex) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => 400,
                                        'mess' => Yii::$app->params['update-danger']
                                    ];
                                }
                            }
//                        if ($dathen == 1 && $days > 7 && $modelDatHenOld === null) {
//                            $modelDatHen = new CustomerDatHen();
//                            $modelDatHen->customer_id = $customer_id;
//                            $modelDatHen->user_id = $model->permission_user;
//                            $modelDatHen->dat_hen_moi_cu = 2;
//                            $modelDatHen->dat_hen_co_so = $model->co_so;
//
//                            if (!$modelDatHen->save()) {
//                                $transaction->rollBack();
//                            }
//                        }
//                        if ($modelDatHenOld !== null && $dathen == 1) {
//                            $modelDatHenOld->customer_id = $customer_id;
//                            $modelDatHenOld->user_id = $model->permission_user;
//                            $modelDatHenOld->dat_hen_moi_cu = 2;
//                            $modelDatHenOld->dat_hen_co_so = $model->co_so;
//                            if (!$modelDatHenOld->save()) {
//                                $transaction->rollBack();
//                            }
//                        }
//                        if ($modelDatHenOld !== null && $dathen != 1) {
//                            $modelDatHenOld->delete();
//                        }

                            $transaction->commit();

                            if ($dathen == Dep365CustomerOnline::STATUS_DH && $modelOld['status'] != Dep365CustomerOnline::STATUS_DH) {
                                $cache = Yii::$app->cache;
                                $key = 'redis-screen-online';
                                $cache->set($key, [
                                    'customer_id' => $model->primaryKey,
                                    'status' => UserTimelineModel::ACTION_DAT_HEN
                                ]);
                            } else {
                                $cache = Yii::$app->cache;
                                $key = 'redis-screen-online';
                                $cache->set($key, [
                                    'srcOnlTimeline' => UserTimelineModel::ACTION_CAP_NHAT,
                                ]);
                            }

                            return [
                                'status' => 200,
                                'mess' => Yii::$app->params['update-success'],
                            ];
                        } else {
                            $transaction->rollBack();
                            return [
                                'status' => 400,
                                'mess' => Yii::$app->params['update-danger']
                            ];
                        }
                    } catch (\yii\db\Exception $exception) {
                        $transaction->rollBack();
                        return [
                            'status' => 400,
                            'mess' => $exception->getMessage(),
                            'error' => $exception,
                        ];
                    }
                } else {
                    return [
                        'status' => 400,
                        'mess' => 'Lỗi dữ liệu',
                        'err' => $model->getErrors()
                    ];
                }
            }

            return $this->renderAjax('update', [
                'model' => $model,
                'district' => $district,
                'agencyUpdate' => $agencyUpdate,
                'roleUser' => $roleUser
            ]);
        }
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
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
                    $user_timeline->action = UserTimelineModel::ACTION_XOA;
                    $user_timeline->customer_id = $id[0];
                    if ($user_timeline->save()) {
                        return [
                            "status" => "success"
                        ];
                    }
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

    protected function smsErrorStatus($status)
    {
        switch ($status) {
            case 0:
                $result = 'Thành công';
                break;
            case 2:
                $result = 'lỗi hệ thống';
                break;
            case 3:
                $result = 'Sai user hoặc mật khẩu';
                break;
            case 4:
                $result = 'Ip không được phép';
                break;
            case 5:
                $result = 'Chưa khai báo brandname/dịch vụ';
                break;
            case 6:
                $result = 'Lặp nội dung';
                break;
            case 7:
                $result = 'Thuê bao từ chối nhận tin';
                break;
            case 8:
                $result = 'Không được phép gửi tin';
                break;
            case 9:
                $result = 'Chưa khai báo template';
                break;
            case 10:
                $result = 'Định dạng thuê bao không đúng';
                break;
            case 11:
                $result = 'Có tham số không hợp lệ';
                break;
            case 12:
                $result = 'Tài khoản không đúng';
                break;
            case 13:
                $result = 'Gửi tin: lỗi kết nối';
                break;
            case 14:
                $result = 'Gửi tin: lỗi kết nối';
                break;
            case 15:
                $result = 'Tài khoản hết hạn';
                break;
            case 16:
                $result = 'Hết hạn dịch vụ';
                break;
            case 17:
                $result = 'Hết hạn mức gửi test';
                break;
            case 18:
                $result = 'Hủy gửi tin (CSKH)';
                break;
            case 19:
                $result = 'Hủy gửi tin (KD)';
                break;
            case 20:
                $result = 'Gateway chưa hỗ trợ Unicode';
                break;
            case 21:
                $result = 'Chưa set giá trả trước';
                break;
            case 22:
                $result = 'Tài khoản chưa kích hoạt';
                break;
            case 25:
                $result = 'Chưa khai báo partner cho user';
                break;
            case 26:
                $result = 'Chưa khai báo GateOwner cho user';
                break;
            case 27:
                $result = 'Gửi tin: gate trả mã lỗi';
                break;
            case 31:
                $result = 'Bạn không thể gửi tin tới số điện thoại 11 số';
                break;
            default:
                $result = 'Hãy liên hệ lập trình viên';
                break;
        }
        return $result;
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

    public function actionCheckCustomerPhone($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if (isset($post['phone']) && $post['phone'] != '') {
                $listUser = CustomerModel::find()->where(['phone' => $post['phone']])->all();
                if (count($listUser) > 0) {
                    $data = [];
                    foreach ($listUser as $user) {
                        $data[] = [
                            'id' => $user->id,
                            'name' => $user->name,
                            'avatar' => ($user->avatar != null && file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $user->avatar) ? Url::to('@web/uploads') . '/avatar/70x70/' . $user->avatar : Url::to('@web/local') . '/default/avatar-default.png'),
                            'birthday' => $user->birthday,
                        ];
                    }
                    return $data;
                }
            }
            return [];
        }
    }

    public function actionImportCustomer($id = null)
    {
        $customer = new Dep365CustomerOnlineImport();
        $formChupHinh = new FormChupHinh();
        $formChupBanhMoi = new FormChupBanhMoi();
        $formChupCui = new FormChupCui();
        $formChupFinal = new FormChupFinal();
        $formHinhTknc = new FormHinhTknc();
        $listChupHinh = $listChupBanhMoi = $listChupCui = $listChupFinal = $listHinhTknc = [];
        if ($id != null || $id != '') {
            $customer = Dep365CustomerOnlineImport::find()->where(['id' => $id])->one();
            if ($customer == null) {
                Yii::$app->session->setFlash('alert', [
                    'class' => 'alert-warning',
                    'body' => 'Không tìm thấy dữ liệu'
                ]);
                return $this->redirect(['import-customer']);
            }
            $checkChupHinhData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][ChupHinhController::FOLDER]);
            foreach ($checkChupHinhData as $chuphinh) {
                if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupHinhController::FOLDER . '/' . $chuphinh->image)) {
                    $listChupHinh[] = [
                        'type' => 'local',
                        'id' => $chuphinh->id,
                        'name' => $chuphinh->image,
                        'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupHinhController::FOLDER . '/' . $chuphinh->image,
                        'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupHinhController::FOLDER . '/thumb/' . $chuphinh->image,
                    ];
                }
            }
            $checkChupBanhMoiData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][ChupBanhMoiController::FOLDER]);
            foreach ($checkChupBanhMoiData as $chuphinh) {
                if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupBanhMoiController::FOLDER . '/' . $chuphinh->image)) {
                    $listChupBanhMoi[] = [
                        'type' => 'local',
                        'id' => $chuphinh->id,
                        'name' => $chuphinh->image,
                        'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupBanhMoiController::FOLDER . '/' . $chuphinh->image,
                        'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupBanhMoiController::FOLDER . '/thumb/' . $chuphinh->image,
                    ];
                }
            }
            $checkChupCuiData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][ChupCuiController::FOLDER]);
            foreach ($checkChupCuiData as $chuphinh) {
                if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupCuiController::FOLDER . '/' . $chuphinh->image)) {
                    $listChupCui[] = [
                        'type' => 'local',
                        'id' => $chuphinh->id,
                        'name' => $chuphinh->image,
                        'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupCuiController::FOLDER . '/' . $chuphinh->image,
                        'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupCuiController::FOLDER . '/thumb/' . $chuphinh->image,
                    ];
                }
            }
            $checkChupFinalData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][ChupFinalController::FOLDER]);
            foreach ($checkChupFinalData as $chuphinh) {
                if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupFinalController::FOLDER . '/' . $chuphinh->image)) {
                    $listChupFinal[] = [
                        'type' => 'local',
                        'id' => $chuphinh->id,
                        'name' => $chuphinh->image,
                        'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupFinalController::FOLDER . '/' . $chuphinh->image,
                        'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupFinalController::FOLDER . '/thumb/' . $chuphinh->image,
                    ];
                }
            }
            $checkHinhTkncData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][TkncController::FOLDER]);
            foreach ($checkHinhTkncData as $chuphinh) {
                if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/' . $chuphinh->image)) {
                    $listHinhTknc[] = [
                        'type' => 'local',
                        'id' => $chuphinh->id,
                        'name' => $chuphinh->image,
                        'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/' . $chuphinh->image,
                        'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/thumb/' . $chuphinh->image,
                    ];
                }
            }
        }
        return $this->render('import-customer', [
            'id' => $id,
            'customer' => $customer,
            'formChupHinh' => $formChupHinh,
            'formChupBanhMoi' => $formChupBanhMoi,
            'formChupCui' => $formChupCui,
            'formChupFinal' => $formChupFinal,
            'formHinhTknc' => $formHinhTknc,
            'listChupHinh' => $listChupHinh,
            'listChupBanhMoi' => $listChupBanhMoi,
            'listChupCui' => $listChupCui,
            'listChupFinal' => $listChupFinal,
            'listHinhTknc' => $listHinhTknc,
        ]);
    }

    public function actionValidateCustomer()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $customer = new Dep365CustomerOnlineImport();
            if ($customer->load(Yii::$app->request->post())) {
                return ActiveForm::validate($customer);
            }
        }
    }

    public function actionValidateFormChupHinh()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $formChupHinh = new FormChupHinh();
            if ($formChupHinh->load(Yii::$app->request->post())) {
                return ActiveForm::validate($formChupHinh);
            }
        }
    }

    public function actionValidateFormChupBanhMoi()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $formChupBanhMoi = new FormChupBanhMoi();
            if ($formChupBanhMoi->load(Yii::$app->request->post())) {
                return ActiveForm::validate($formChupBanhMoi);
            }
        }
    }

    public function actionValidateFormChupCui()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $formChupCui = new FormChupCui();
            if ($formChupCui->load(Yii::$app->request->post())) {
                return ActiveForm::validate($formChupCui);
            }
        }
    }

    public function actionValidateFormChupFinal()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $formChupFinal = new FormChupFinal();
            if ($formChupFinal->load(Yii::$app->request->post())) {
                return ActiveForm::validate($formChupFinal);
            }
        }
    }

    public function actionValidateFormHinhTknc()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $formHinhTknc = new FormHinhTknc();
            if ($formHinhTknc->load(Yii::$app->request->post())) {
                return ActiveForm::validate($formHinhTknc);
            }
        }
    }

    public function actionSubmitImportCustomer($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Dep365CustomerOnlineImport();
            if ($id != null) {
                $model = Dep365CustomerOnlineImport::find()->where(['id' => $id])->one();
            }
            if ($model->load(Yii::$app->request->post())) {
                $avatar = UploadedFile::getInstance($model, 'avatar');
                $model->avatar = $avatar;
                if ($model->validate()) {
                    if ($id != null && $avatar == null) {
                        $model->avatar = $model->getOldAttribute('avatar');
                    }
                    $transaction = Yii::$app->db->beginTransaction(
                        Transaction::SERIALIZABLE
                    );
                    $model->reason_reject = '';
                    if (!$model->save()) {
                        $transaction->rollBack();
                        return [
                            'code' => 400,
                            'msg' => 'Lưu thông tin khách hàng thất bại',
                            'err' => $model->getErrors()
                        ];
                    }

                    if ($avatar != null) {
                        $fileName = $avatar->baseName . '.' . $avatar->extension;
                        $urlAvatar = null;
                        if ($avatar->saveAs(Yii::getAlias('@backend/web') . '/uploads/temp/' . $fileName)) {
                            $filePath = Yii::$app->basePath . '/web/uploads/temp/' . $fileName;
                            $urlAvatar = $this->createImage('@backend/web', $filePath, 200, 200, '/uploads/avatar/200x200/');
                            $this->createImage('@backend/web', $filePath, 70, 70, '/uploads/avatar/70x70/', $urlAvatar);
                        }
                        $model->updateAttributes(['avatar' => $urlAvatar]);
                    }
                    $id = $model->primaryKey;
                    if (strlen($model->co_so) == 1) {
                        $coso = '0' . $model->co_so;
                    } else {
                        $coso = $model->co_so;
                    }

                    $model->updateAttributes(['customer_code' => 'AUR' . $coso . '-' . $id]);

                    $customer_bak = new Dep365CustomerOnlineBak();
                    $customer_bak->customer_online_id = $model->getPrimaryKey();
                    foreach ($model->getAttributes() as $key => $value) {
                        if ($key == 'id') {
                            continue;
                        }
                        $customer_bak->setAttribute($key, $value);
                    }
                    $customer_bak->time_edit = time();
                    if (!$customer_bak->save()) {
                        $transaction->rollBack();
                        return [
                            'code' => 400,
                            'msg' => 'Lưu thông tin khách hàng thất bại!'
                        ];
                    }

                    /* INIT FOLDER GOOGLE DRIVE */
                    $service = GapiComponent::getService();
                    $time = strtotime(date('d-m-Y'));

                    /* CHUP HINH */
                    $checkGDriveFolderChupHinh = PhongKhamChupHinh::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $model->getPrimaryKey()])->one();
                    if ($checkGDriveFolderChupHinh == null) {
                        $gDriveFolderChupHinh = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), ChupHinhController::FOLDER);
                        $checkGDriveFolderChupHinh = new PhongKhamChupHinh();
                        $checkGDriveFolderChupHinh->customer_id = $model->getPrimaryKey();
                        $checkGDriveFolderChupHinh->folder_id = $gDriveFolderChupHinh;
                        $checkGDriveFolderChupHinh->save();
                    } else {
                        $getFolder = GapiComponent::getFile($service, $checkGDriveFolderChupHinh->folder_id);
                        if ($getFolder != null) {
                            $gDriveFolderChupHinh = $checkGDriveFolderChupHinh->folder_id;
                        } else {
                            $gDriveFolderChupHinh = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), ChupHinhController::FOLDER);
                            $checkGDriveFolderChupHinh->folder_id = $gDriveFolderChupHinh;
                            $checkGDriveFolderChupHinh->save();
                        }
                    }

                    /* CHUP BANH MOI */
                    $checkGDriveFolderChupBanhMoi = PhongKhamChupBanhMoi::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $model->getPrimaryKey()])->one();
                    if ($checkGDriveFolderChupBanhMoi == null) {
                        $gDriveFolderChupBanhMoi = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), ChupBanhMoiController::FOLDER);
                        $checkGDriveFolderChupBanhMoi = new PhongKhamChupBanhMoi();
                        $checkGDriveFolderChupBanhMoi->customer_id = $model->getPrimaryKey();
                        $checkGDriveFolderChupBanhMoi->folder_id = $gDriveFolderChupBanhMoi;
                        $checkGDriveFolderChupBanhMoi->save();
                    } else {
                        $getFolder = GapiComponent::getFile($service, $checkGDriveFolderChupBanhMoi->folder_id);
                        if ($getFolder != null) {
                            $gDriveFolderChupBanhMoi = $checkGDriveFolderChupBanhMoi->folder_id;
                        } else {
                            $gDriveFolderChupBanhMoi = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), ChupBanhMoiController::FOLDER);
                            $checkGDriveFolderChupBanhMoi->folder_id = $gDriveFolderChupBanhMoi;
                            $checkGDriveFolderChupBanhMoi->save();
                        }
                    }

                    /* CHUP CUI */
                    $checkGDriveFolderChupCui = PhongKhamChupCui::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $model->getPrimaryKey()])->one();
                    if ($checkGDriveFolderChupCui == null) {
                        $gDriveFolderChupCui = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), ChupCuiController::FOLDER);
                        $checkGDriveFolderChupCui = new PhongKhamChupCui();
                        $checkGDriveFolderChupCui->customer_id = $model->getPrimaryKey();
                        $checkGDriveFolderChupCui->folder_id = $gDriveFolderChupCui;
                        $checkGDriveFolderChupCui->save();
                    } else {
                        $getFolder = GapiComponent::getFile($service, $checkGDriveFolderChupCui->folder_id);
                        if ($getFolder != null) {
                            $gDriveFolderChupCui = $checkGDriveFolderChupCui->folder_id;
                        } else {
                            $gDriveFolderChupCui = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), ChupCuiController::FOLDER);
                            $checkGDriveFolderChupCui->folder_id = $gDriveFolderChupCui;
                            $checkGDriveFolderChupCui->save();
                        }
                    }

                    /* CHUP FINAL */
                    $checkGDriveFolderChupFinal = PhongKhamChupFinal::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $model->getPrimaryKey()])->one();
                    if ($checkGDriveFolderChupFinal == null) {
                        $gDriveFolderChupFinal = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), ChupFinalController::FOLDER);
                        $checkGDriveFolderChupFinal = new PhongKhamChupFinal();
                        $checkGDriveFolderChupFinal->customer_id = $model->getPrimaryKey();
                        $checkGDriveFolderChupFinal->folder_id = $gDriveFolderChupFinal;
                        $checkGDriveFolderChupFinal->save();
                    } else {
                        $getFolder = GapiComponent::getFile($service, $checkGDriveFolderChupFinal->folder_id);
                        if ($getFolder != null) {
                            $gDriveFolderChupFinal = $checkGDriveFolderChupFinal->folder_id;
                        } else {
                            $gDriveFolderChupFinal = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), ChupFinalController::FOLDER);
                            $checkGDriveFolderChupFinal->folder_id = $gDriveFolderChupFinal;
                            $checkGDriveFolderChupFinal->save();
                        }
                    }

                    /* TKNC */
                    $checkGDriveFolderHinhTknc = PhongKhamHinhTknc::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $model->getPrimaryKey()])->one();
                    if ($checkGDriveFolderHinhTknc == null) {
                        $gDriveFolderHinhTknc = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), TkncController::FOLDER);
                        $checkGDriveFolderHinhTknc = new PhongKhamHinhTknc();
                        $checkGDriveFolderHinhTknc->customer_id = $model->getPrimaryKey();
                        $checkGDriveFolderHinhTknc->folder_id = $gDriveFolderHinhTknc;
                        $checkGDriveFolderHinhTknc->save();
                    } else {
                        $getFolder = GapiComponent::getFile($service, $checkGDriveFolderHinhTknc->folder_id);
                        if ($getFolder != null) {
                            $gDriveFolderHinhTknc = $checkGDriveFolderHinhTknc->folder_id;
                        } else {
                            $gDriveFolderHinhTknc = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($model->full_name) . '-' . $model->getPrimaryKey(), date('d-m-Y'), TkncController::FOLDER);
                            $checkGDriveFolderHinhTknc->folder_id = $gDriveFolderHinhTknc;
                            $checkGDriveFolderHinhTknc->save();
                        }
                    }
                    /* INIT FOLDER GOOGLE DRIVE */

                    $transaction->commit();
                    return [
                        'code' => 200,
                        'msg' => 'Lưu thông tin khách hàng thành công!',
                        'id' => $model->getPrimaryKey(),
                    ];
                } else {
                    return [
                        'code' => 400,
                        'msg' => 'Lỗi dữ liệu',
                        'error' => $model->getErrors()
                    ];
                }
            } else {
                return [
                    'code' => '400',
                    'msg' => 'Lỗi load dữ liệu!',
                    'error' => $model->getErrors()
                ];
            }
        } else {
            return [
                'code' => 400,
                'msg' => 'Không được phép sử dụng tính năng!'
            ];
        }
    }

    public function actionListCallLog($id)
    {
        if (Yii::$app->request->isAjax) {
            $customer = Dep365CustomerOnline::find()->where(['id' => $id])->one();
            if ($customer == null) {
                return $this->renderAjax('_error', [
                    'error' => 'Khách hàng không tồn tại'
                ]);
            }
            $VhtCallLogFromNumber = new VhtCallLogComponent([], [
                'from_number' => $customer->phone
            ]);
            $callLogFrom = $VhtCallLogFromNumber->ConnectVht();
            $VhtCallLogToNumber = new VhtCallLogComponent([], [
                'to_number' => $customer->phone
            ]);
            $callLogTo = $VhtCallLogToNumber->ConnectVht();

            $callLogFromItems = $callLogFrom != null ? $callLogFrom->items : [];
            $callLogToItems = $callLogTo != null ? $callLogTo->items : [];

            $callLog = array_merge($callLogFromItems, $callLogToItems);

            return $this->renderAjax('_list-call-log', [
                'callLog' => $callLog,
            ]);
        }
    }

    public function findModel($id)
    {
        if (($model = Dep365CustomerOnline::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
