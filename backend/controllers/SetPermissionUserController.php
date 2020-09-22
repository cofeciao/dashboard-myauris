<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18-Feb-19
 * Time: 5:35 PM
 */

namespace backend\controllers;

use backend\components\MyController;
use backend\models\Customer;
use backend\modules\customer\models\Dep365CustomerOnline;

class SetPermissionUserController extends MyController
{
    public function actionIndex()
    {
        $online = [90, 92, 110, 108, 107, 106, 96, 97];
        $keys = 0;
        $tram = Dep365CustomerOnline::find()->select('id, permission_user')->where(['permission_user' => 93])->all();
        foreach ($tram as $key => $value) {
            $customer = Customer::findOne($value->id);
            $customer->permission_user = $online[$keys];

            if ($customer->save()) {
            } else {
                var_dump($customer->getErrors());
            }
            $keys++;
            if ($keys == 8) {
                $keys = 0;
            }
        }
    }
}
