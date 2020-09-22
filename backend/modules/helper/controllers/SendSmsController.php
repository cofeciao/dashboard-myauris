<?php


namespace backend\modules\helper\controllers;

use backend\components\MyController;
use backend\models\CustomerModel;
use backend\modules\helper\components\HelperComponent;
use backend\modules\helper\models\SendSmsCustomer;
use Yii;
use yii\web\Response;

class SendSmsController extends MyController
{
    public function actionIndex()
    {
        $model = new SendSmsCustomer();
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionSendSms()
    {
        $model = new SendSmsCustomer();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $query = CustomerModel::find()->select('id, phone');
            $date_create_from = strtotime($model->date_create_from);
            $date_create_to = strtotime($model->date_create_to);
            if ($date_create_from !== false && $date_create_to !== false) {
                $query->where(['between', 'ngay_tao', $date_create_from, $date_create_to]);
            }

            $date_dathen_from = strtotime($model->date_dathen_from);
            $date_dathen_to = strtotime($model->date_dathen_to);
            if ($date_dathen_from !== false && $date_dathen_to !== false) {
                $query->where(['between', 'date_lichhen', $date_dathen_from, $date_dathen_to]);
            }

            $status = $model->status;
            if ($status != false) {
                $query->andWhere(['status' => $status]);
            }

            $den_or_khong_den = $model->den_or_khong_den;
            if ($den_or_khong_den != false) {
                $query->andWhere(['dat_hen' => $den_or_khong_den]);
            }

            $who_create = $model->who_create;
            if ($who_create != false) {
                $query->andWhere(['is_customer_who' => $who_create]);
            }
            $data = $query->all();

            $arrPhone = [];
            $success = 0;
            $fail = 0;
            foreach ($data as $key => $item) {
                $phone = trim($item['phone']);

                if (in_array($item['phone'], $arrPhone)) {
                    continue;
                }

                if (strpos($phone, '+84') !== false) {
                    $phone = substr($phone, 3);
                    $phone = '0' . $phone;
                }
                $res = $this->SendSmsOne($item['id'], 100, $model->content, $phone);
                array_push($arrPhone, $item['phone']);
                if ($res['status'] == 0) {
                    $success += 1;
                } else {
                    $fail += 1;
                }
            }

            $status = 200;
            return ['status' => $status, 'total' => count($data), 'success' => $success, 'fail' => $fail];
        }
    }

    public function actionValidateSms()
    {
        if (Yii::$app->request->isAjax) {
            $model = new SendSmsCustomer();
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post())) {
                return \yii\widgets\ActiveForm::validate($model);
            }
        }
    }

    protected function SendSmsOne($customerId, $sms_lanthu, $content, $phone)
    {
        $result = HelperComponent::sendSms($customerId, $sms_lanthu, $content, $phone);

        if ($result === false) {
            return [
                'status' => 403,
                'text' => 'Lỗi gửi SMS. Hãy liên hệ bộ phận kỹ thuật!',
            ];
        }

        return [
            'status' => $result,
            'text' => HelperComponent::smsErrorStatus($result),
        ];
    }
}
