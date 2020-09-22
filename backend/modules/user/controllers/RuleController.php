<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 13-Dec-18
 * Time: 5:19 PM
 */

namespace backend\modules\user\controllers;

use backend\modules\user\models\BizRuleModel;
use backend\modules\user\models\User;
use Yii;
use backend\components\MyController;

class RuleController extends MyController
{

    public function actionCreateRuleCustomerOnline()
    {
        $model = new BizRuleModel();
        $auth = Yii::$app->authManager;

        //Chi user tao moi co quyen cap nhat.
        $model->name = 'customerCustomer-onlineUpdate';
        $model->actionUpdate = 'customerCustomer-onlineUpdate';
//        $model->name = 'customerCustomer-onlineDelete';
//        $model->actionUpdate = 'customerCustomer-onlineDelete';
        $model->rolesParent = User::USER_NHANVIEN_ONLINE;
        $model->className = 'backend\modules\user\rbac\CustomerOnlineRule';

        $model->save();
        $updateOwn = $auth->createPermission($model->name);
//        $updateOwn = $auth->getPermission('customerCustomer-onlineDelete');

        $updateOwn->ruleName = $model->name;
        $auth->add($updateOwn);

//        $updateAction = $auth->createPermission($model->actionUpdate);
//        $auth->addChild($updateOwn, $updateAction);
//
//
//        $roles = $auth->createPermission($model->rolesParent);
//
//        $auth->addChild($roles, $updateOwn);
    }
}
