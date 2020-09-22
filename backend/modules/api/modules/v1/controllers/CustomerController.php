<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 22-04-2019
 * Time: 02:58 PM
 */

namespace backend\modules\api\modules\v1\controllers;

use backend\models\CustomerModel;
use backend\modules\api\components\RestController;
use backend\modules\api\modules\v1\models\Customer;
use backend\modules\api\modules\v1\models\CustomerToken;
use backend\modules\api\modules\v1\models\ThanhToan;
use backend\modules\customer\models\CustomerFeedback;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\affiliate\models\AffiliateCustomerContact;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\setting\models\Setting;
use http\Client\Response;
use http\Message\Body;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class CustomerController extends RestController
{
    public $modelClass = 'backend\modules\customer\models\Dep365CustomerOnline';

    public static function allowedDomains()
    {
        return [
            // '*',                        // star allows all domains
            'http://booking.tm',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

            // For cross-domain AJAX request
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    // restrict access to domains:
                    'Origin' => static::allowedDomains(),
                    'Access-Control-Request-Method' => ['POST', 'GET'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,                 // Cache (seconds)
                ],
            ],

        ]);
    }

    public function actionGet($slug = null)
    {
        $customer = Dep365CustomerOnline::find()->select(['name', 'phone'])->where(['slug' => $slug])->one();
        if ($customer == null) {
            \Yii::$app->response->statusCode = 404;
            return null;
        }
        return $customer;
    }

    public function actionSubmitContact()
    {
        $customer = \Yii::$app->request->post('customer');
        if ($customer != null) {
            $customer = json_decode($customer, true);
            $model = new AffiliateCustomerContact();
            $model->page = $customer['page'];
            if (in_array($model->page, [
                'affiliate-contact',
                'myauris-contact',
            ])) {
                $model->scenario = AffiliateCustomerContact::SCENARIO_AFFILIATE_CONTACT;
            } else if (in_array($model->page, [AffiliateCustomerContact::SCENARIO_MYAURIS_APP])) {
                $model->scenario = AffiliateCustomerContact::SCENARIO_MYAURIS_APP;
            }
            unset($customer['page']);
            $array_key_convert = [
                'fullname' => 'name',
                'content' => 'note'
            ];
            foreach ($customer as $k => $v) {
                if ($model->canSetProperty($k)) {
                    $model->$k = $v;
                } elseif (array_key_exists($k, $array_key_convert)) {
                    $k = $array_key_convert[$k];
                    if ($model->canSetProperty($k)) {
                        $model->$k = $v;
                    }
                }
            }
            if (!$model->validate()) {
                return [
                    'code' => 400,
                    'msg' => \Yii::$app->params['create-danger'],
                    'error' => $model->getErrors()
                ];
            }
            if ($model->checkPhone() > 0) {
                $customer = AffiliateCustomerContact::find()->where(['phone' => $model->phone])->one();
                if (isset($customer)) {
                    try {
                        $customer->updateAttributes([
                            'name' => $model->name,
                            'email' => $model->email,
                            'note' => $model->note
                        ]);
                        return [
                            'code' => 200,
                            'msg' => \Yii::$app->params['create-success'],
                        ];
                    } catch (Exception $ex) {
                        return [
                            'code' => 400,
                            'msg' => Yii::$app->params['create-danger'],
                            'error' => $customer->getErrors()
                        ];
                    }
                }
            }
            if (!$model->save()) {
                return [
                    'code' => 400,
                    'msg' => \Yii::$app->params['create-danger'],
                    'error' => $model->getErrors()
                ];
            }
            return [
                'code' => 200,
                'msg' => \Yii::$app->params['create-success'],
            ];
        }
        return [
            'code' => 400,
            'msg' => \Yii::$app->params['create-danger'],
            'error' => 'Data null',
        ];
    }

    public function actionGetTokenFeedback($token = null)
    {
        $customerToken = CustomerToken::getByToken($token);
        if ($customerToken == null) {
            return [
                'code' => 404,
                'data' => [
                    'msg' => 'Không tìm thấy dữ liệu'
                ]
            ];
        }
        if ($customerToken->customerHasOne->full_name != null) {
            $customer_name = $customerToken->customerHasOne->full_name;
        } elseif ($customerToken->customerHasOne->forename != null) {
            $customer_name = $customerToken->customerHasOne->forename;
        } else {
            $customer_name = $customerToken->customerHasOne->name;
        }
        $sex = '';
        if (array_key_exists($customerToken->customerHasOne->sex, [
            Dep365CustomerOnline::SEX_MAN => 'anh',
            Dep365CustomerOnline::SEX_WOMAN => 'chị'
        ])) {
            $sex = [
                Dep365CustomerOnline::SEX_MAN => 'anh',
                Dep365CustomerOnline::SEX_WOMAN => 'chị'
            ][$customerToken->customerHasOne->sex];
        }
        $code = 200;
        if ($customerToken->status == 1) {
            $code = 208;
        }
        return [
            'code' => $code,
            'data' => [
                'name' => $customer_name,
                'sex' => $sex
            ]
        ];
    }

    public function actionCustomerFeedback($token = null)
    {
        $customerToken = CustomerToken::getByToken($token);
        if ($customerToken == null) {
            return [
                'code' => 404,
                'data' => [
                    'msg' => 'Không tìm thấy dữ liệu'
                ]
            ];
        }
        if ($customerToken->status == 1) {
            return [
                'code' => 200,
                'msg' => 'Đã đánh giá rồi'
            ];
        }
        $feedback = new CustomerFeedback();
        $feedback->setAttributes([
            'rating' => Yii::$app->request->post('rating'),
            'feedback' => Yii::$app->request->post('note'),
            'customer_id' => $customerToken->getAttribute('customer_id'),
            'token_id' => $customerToken->primaryKey
        ]);
        try {
            if (!$feedback->save()) {
                return [
                    'code' => 400,
                    'msg' => 'Lưu đánh giá thất bại (Error 365)',
                    'error' => $feedback->getErrors(),
                    'data' => $feedback
                ];
            }
            $customerToken->updateAttributes([
                'status' => CustomerToken::STATUS_PUBLISHED
            ]);
            return [
                'code' => 200,
                'msg' => 'Thành công'
            ];
        } catch (Exception $ex) {
            return [
                'code' => 400,
                'msg' => 'Có lỗi xảy ra (Error 365)',
                'error' => $ex
            ];
        }
    }

    public function actionGetAnalysticsByCustomer($customer = null)
    {
        $listAccept = Dep365CustomerOnlineCome::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->published()->all();
        $rowCustomer = Dep365CustomerOnline::find()->where([
            'id' => $customer,
            'status' => Dep365CustomerOnline::STATUS_DH,
            'dat_hen' => Dep365CustomerOnline::DAT_HEN_DEN
        ])->andWhere([
            'IN', 'customer_come_time_to', ArrayHelper::map($listAccept, 'id', 'id')
        ])->one();
        if ($rowCustomer == null) {
            return [
                'code' => 404,
                'msg' => 'Không tìm thấy thông tin người dùng'
            ];
        }
        $current_date = date('d-m-Y');
        $from = Yii::$app->request->post('from');
        $to = Yii::$app->request->post('to');
        if (strpos($from, '/') !== false) {
            $from = str_replace('/', '-', $from);
        }
        if (strpos($to, '/') !== false) {
            $to = str_replace('/', '-', $to);
        }
        if ($from == null || strtotime($from) > strtotime($current_date)) {
            $from = $current_date;
        }
        if ($to == null || strtotime($to) > strtotime($current_date)) {
            $to = $current_date;
        }
        $from = strtotime(date('d-m-Y', strtotime($from)));
        $to = strtotime(date('d-m-Y', strtotime($to)));

        $getDatHen = Customer::getCustomerDatHen($rowCustomer->primaryKey, $from, $to);
        $getDatHenKhongDen = Customer::getCustomerDatHenKhongDen($rowCustomer->primaryKey, $from, $to);
        $getDatHenDen = Customer::getCustomerDatHenDen($rowCustomer->primaryKey, $from, $to);
        $getLam = Customer::getCustomerLam($rowCustomer->primaryKey, $from, $to);
        $getKhongLam = Customer::getCustomerKhongLam($rowCustomer->primaryKey, $from, $to);
        $getDoanhThu = ThanhToan::getDoanhThu($rowCustomer->primaryKey, $from, $to);
        $labels = [];
        $datHen = [];
        $datHenKhongDen = [];
        $datHenDen = [];
        $lam = [];
        $khongLam = [];
        $doanhThu = [];

        $labels[] = date('d-m-Y', $from);

        /* Đặt hẹn */
        if (array_key_exists($from, $getDatHen)) {
            $datHen[] = $getDatHen[$from]->count_customer_dat_hen;
        } else {
            $datHen[] = 0;
        }
        /* Đặt hẹn không đến */
        if (array_key_exists($from, $getDatHenKhongDen)) {
            $datHenKhongDen[] = $getDatHenKhongDen[$from]->count_customer_dat_hen;
        } else {
            $datHenKhongDen[] = 0;
        }
        /* Đặt hẹn đến */
        if (array_key_exists($from, $getDatHenDen)) {
            $datHenDen[] = $getDatHenDen[$from]->count_customer_dat_hen;
        } else {
            $datHenDen[] = 0;
        }
        /* Làm dịch vụ */
        if (array_key_exists($from, $getLam)) {
            $lam[] = $getLam[$from]->count_customer_dat_hen;
        } else {
            $lam[] = 0;
        }
        /* Không làm dịch vụ */
        if (array_key_exists($from, $getKhongLam)) {
            $khongLam[] = $getKhongLam[$from]->count_customer_dat_hen;
        } else {
            $khongLam[] = 0;
        }
        /* Doanh thu */
        if (array_key_exists($from, $getDoanhThu)) {
            $doanhThu[] = $getDoanhThu[$from]->tong_tien;
        } else {
            $doanhThu[] = 0;
        }

        while ($from <= $to) {
            $from = $from + 86400;
            $labels[] = date('d-m-Y', $from);
            /* Đặt hẹn */
            if (array_key_exists($from, $getDatHen)) {
                $datHen[] = $getDatHen[$from]->count_customer_dat_hen;
            } else {
                $datHen[] = 0;
            }
            /* Đặt hẹn không đến */
            if (array_key_exists($from, $getDatHenKhongDen)) {
                $datHenKhongDen[] = $getDatHenKhongDen[$from]->count_customer_dat_hen;
            } else {
                $datHenKhongDen[] = 0;
            }
            /* Đặt hẹn đến */
            if (array_key_exists($from, $getDatHenDen)) {
                $datHenDen[] = $getDatHenDen[$from]->count_customer_dat_hen;
            } else {
                $datHenDen[] = 0;
            }
            /* Làm dịch vụ */
            if (array_key_exists($from, $getLam)) {
                $lam[] = $getLam[$from]->count_customer_dat_hen;
            } else {
                $lam[] = 0;
            }
            /* Không làm dịch vụ */
            if (array_key_exists($from, $getKhongLam)) {
                $khongLam[] = $getKhongLam[$from]->count_customer_dat_hen;
            } else {
                $khongLam[] = 0;
            }
            /* Doanh thu */
            if (array_key_exists($from, $getDoanhThu)) {
                $doanhThu[] = $getDoanhThu[$from]->tong_tien;
            } else {
                $doanhThu[] = 0;
            }
        }

        $data = [
            'labels' => $labels,
            'data_report_title' => [
                'dat_hen' => 'Khách đặt hẹn',
                'khong_den' => 'Khách không đến',
                'den' => 'Khách đến',
                'lam_dich_vu' => 'Khách làm dịch vụ',
                'khong_lam_dich_vu' => 'Khách không làm dịch vụ',
                'doanh_thu' => 'Doanh thu'
            ],
            'data_report' => [
                'dat_hen' => $datHen,
                'khong_den' => $datHenKhongDen,
                'den' => $datHenDen,
                'lam_dich_vu' => $lam,
                'khong_lam_dich_vu' => $khongLam,
            ],
            'data_revenue' => [
                'doanh_thu' => $doanhThu
            ]
        ];
        return $data;
    }
}
