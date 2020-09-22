<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 22-04-2019
 * Time: 02:53 PM
 */

namespace backend\modules\api\modules\v1\controllers;

use backend\models\CustomerModel;
use backend\modules\api\components\RestController;
use backend\modules\booking\models\CustomerBooking;
use backend\modules\booking\models\CustomerOnlineBooking;
use backend\modules\booking\models\TimeWork;
use backend\modules\booking\models\UserRegister;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\setting\models\Dep365CoSo;
use cheatsheet\Time;
use GuzzleHttp\Client;
use yii\db\Transaction;
use yii\filters\ContentNegotiator;
use yii\helpers\Url;
use yii\web\Response;

class BookingController extends RestController
{
    public $modelClass = 'backend\modules\booking\models\CustomerOnlineBooking';

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

        ]);
    }

    public function actionGetCustomer($slug = null)
    {
        $customer = CustomerModel::find()->select(['name', 'phone'])->where(['slug' => $slug])->one();
        return $customer;
    }

    public function actionGetCoSo()
    {
        return Dep365CoSo::find()->select(['id', 'name', 'address'])->published()->all();
    }

    public function actionGetTimeByDay($day = null, $coso = null)
    {
        if ($day == null || $coso == null) {
            return null;
        }
        $d = strtotime(date('d-m-Y', strtotime($day)));
        $now = strtotime(date('d-m-Y'));
//        return $day.'==='.$d;
        if (!checkdate(date('m', $d), date('d', $d), date('Y', $d))) {
            return null;
        }
        $cs = Dep365CoSo::find()->where(['id' => $coso])->published()->one();
        if ($cs == null) {
            return null;
        }
        $return = [];
        $query = TimeWork::find()->published();
        if ($d == $now) {
            $begin = date('h:i', strtotime('08:45 +1 hours'));
            $query->andWhere("time>'{$begin}'");
        }
        $times = $query->orderBy([TimeWork::tableName() . '.sort' => SORT_ASC])->all();
        foreach ($times as $time) {
            $listBookingInTime = CustomerOnlineBooking::find()->where(['booking_date' => $d, 'time_id' => $time->id, 'coso_id' => $cs->id])->orderBy(['id' => SORT_ASC])->all();
            $i = 0;
            if ($listBookingInTime != null) {
                foreach ($listBookingInTime as $bookingInTime) {
                    $name_tmp = $bookingInTime->customer_type == 1 ? $bookingInTime->customerOnlineHasOne->forename : $bookingInTime->userRegisterHasOne->name;
                    $phone_tmp = $bookingInTime->customer_type == 1 ? $bookingInTime->customerOnlineHasOne->phone : $bookingInTime->userRegisterHasOne->phone;
                    $name = substr($name_tmp, strrpos($name_tmp, ' '), strlen($name_tmp));
                    $phone = '*******' . substr($phone_tmp, -3);
                    $return[$time->name][] = [
                        'name' => 'A/C ' . $name,
                        'phone' => $phone,
                    ];
                    $i++;
                }
            }
            if ($i < CustomerOnlineBooking::MAX_BOOKING_IN_TIME) {
                while ($i < CustomerOnlineBooking::MAX_BOOKING_IN_TIME) {
                    $return[$time->name][] = null;
                    $i++;
                }
            }
        }
        return $return;
    }

    public function actionBooking()
    {
        $post = \Yii::$app->request->post();
        $var = [
            'customer_id' => null,
            'customer_name' => isset($post['customer_name']) ? $post['customer_name'] : null,
            'customer_phone' => isset($post['customer_phone']) ? $post['customer_phone'] : null,
            'customer_type' => null,
            'ip' => isset($post['ip']) ? $post['ip'] : null,
            'booking_date' => isset($post['booking_date']) ? strtotime($post['booking_date']) : null,
            'time_id' => null,
            'coso_id' => isset($post['coso_id']) ? $post['coso_id'] : null,
        ];

        if (isset($post['user']) && $post['user'] != null) {
            $customer = Dep365CustomerOnline::find()->where(['slug' => $post['user']])->one();
            if ($customer != null) {
                $var['customer_id'] = $customer->getPrimaryKey();
            }
        }
        if (isset($post['time_id']) && $post['time_id'] != null) {
            $time = TimeWork::find()->where(['name' => $post['time_id']])->published()->one();
            if ($time != null) {
                $var['time_id'] = $time->getPrimaryKey();
            }
        }
        return $this->actionBookingApi($var);
    }

    public static function actionBookingApi($data, $virtual = false)
    {
        $list_step = [
            'customer_id' => 1,
            'customer_name' => 1,
            'customer_phone' => 1,
            'customer_type' => 1,
            'booking_date' => 3,
            'time_id' => 4,
            'coso_id' => 2,
        ];
        $var = [
            'customer_id' => null,
            'customer_name' => null,
            'customer_phone' => null,
            'customer_type' => null,
            'ip' => null,
            'booking_date' => null,
            'time_id' => null,
            'coso_id' => null,
        ];
        foreach ($var as $k => $v) {
            if (!array_key_exists($k, $data)) {
                return [
                'code' => 400,
                'msg' => 'Lỗi dữ liệu!',
                'err' => $k,
                'step' => $list_step[$k]
            ];
            }
            $var[$k] = $data[$k];
        }
        $var['status'] = isset($data['status']) && in_array($data['status'], [0, 1]) ? $data['status'] : 0;
        $transaction = \Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );
        $customer_id = null;
        $customer_type = null;
        if ($var['customer_id'] != null) {
            $customer_id = $var['customer_id'];
            $customer_type = CustomerOnlineBooking::CUSTOMER_FROM_ONLINE;
        } else {
            $customer = new UserRegister();
            $customer->name = $var['customer_name'];
            $customer->phone = $var['customer_phone'];
            $customer->ip = $var['ip'];
            if (!$customer->validate() || !$customer->save()) {
                $transaction->rollBack();
                return [
                    'code' => 403,
                    'err' => $customer->getErrors(),
                    'step' => 1,
                    'msg' => 'Lỗi dữ liệu khách hàng!',
                ];
            }
            $customer_id = $customer->getPrimaryKey();
            $customer_type = $var['customer_type'] == null ? CustomerOnlineBooking::CUSTOMER_FROM_WEBSITE : $var['customer_type'];
        }
        $booking = new CustomerOnlineBooking();
        $booking->user_register_id = $customer_id;
        $booking->customer_type = $customer_type;
        $booking->coso_id = $var['coso_id'];
        $booking->booking_date = $var['booking_date'];
        $booking->time_id = $var['time_id'];
        $booking->status = $var['status'];
        if ($booking->validate()) {
            if (!$booking->save()) {
                $transaction->rollBack();
                return [
                    'code' => 400,
                    'msg' => 'Đặt lịch thất bại!',
                    'step' => 4,
                    'err' => $booking->getErrors()
                ];
            }
            if ($var['customer_id'] != null) {
                $time = TimeWork::find()->where(['id' => $var['time_id']])->one();
                $customer = CustomerBooking::find()->where(['id' => $var['customer_id']])->one();
                $customer->co_so = $booking->coso_id;
                $customer->time_lichhen = strtotime(date('d-m-Y', $booking->booking_date) . ' ' . $time->time);
                if (!$customer->save()) {
                    $transaction->rollBack();
                    return [
                        'code' => 400,
                        'msg' => 'Cập nhật thông tin khách hàng thất bại!',
                        'step' => 2,
                        'err' => $customer->getErrors()
                    ];
                }
            }
            $transaction->commit();
            if ($virtual == false) {
                $client = new Client(['verify' => false]);
                $client->request('POST', SOCKET_URL, [
                    'form_params' => [
                        'handle' => 'dep365-alert',
                        'data' => json_encode([
                            'act' => 'customer-online-booking',
                            'title' => 'Thông báo',
                            'content' => 'Có khách hàng vừa đặt lịch.',
                            'url' => Url::toRoute(['/booking/customer-online-booking'])
                        ])
                    ]
                ]);
            }
            return [
                'code' => 200,
                'msg' => 'Đặt lịch thành công!',
                'data' => ['bookingId' => $booking->getPrimaryKey()]
            ];
        } else {
            $transaction->rollBack();
            return [
                'code' => 403,
                'step' => 4,
                'msg' => 'Lỗi dữ liệu!',
                'err' => $booking->getErrors()
            ];
        }
    }

    public function actionGetBookingInfo($bookingCode)
    {
        $booking = CustomerOnlineBooking::find()->where(['id' => $bookingCode])->one();
        if ($booking == null) {
            return null;
        }
        if ($booking->customer_type == 1) {
            $model = new CustomerModel();
        } else {
            $model = new UserRegister();
        }
        $user = $model->getById($booking->user_register_id);
        return [
            'name' => $user != null ? $user->name : null,
            'phone' => $user != null ? $user->phone : null,
            'coso' => $booking->coSoHasOne != null ? 'Cơ sở ' . $booking->coSoHasOne->name . ' (' . $booking->coSoHasOne->address . ')' : null,
            'time' => $booking->timeWorkHasOne != null ? $booking->timeWorkHasOne->name : null,
            'date' => date('d-m-Y', $booking->booking_date)
        ];
    }
}
