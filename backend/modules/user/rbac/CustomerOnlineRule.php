<?php
/**
 * Created by PhpStorm.
 * User: mongd
 * Date: 14-Aug-18
 * Time: 10:27 PM
 */

namespace backend\modules\user\rbac;

use backend\modules\user\models\User;
use yii\rbac\Rule;
use backend\modules\customer\models\Dep365CustomerOnline;

class CustomerOnlineRule extends Rule
{
    public $name = 'userOwnCustomerOnline';

    /*
     * $user = id of user
     * $item - object Permission
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            $model = $params['model'];
        } else {
            $id = \Yii::$app->request->get('id');
            $model = Dep365CustomerOnline::findOne($id);
        }
        $userLogin = new User();
        $userRole = $userLogin->getRoleName(\Yii::$app->user->id);

        if ($model && $userRole == User::USER_NHANVIEN_ONLINE) {
            return $model->permission_user == $user;
        }

        return true;
    }
}
