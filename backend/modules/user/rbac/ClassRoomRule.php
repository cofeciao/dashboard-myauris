<?php
/**
 * Created by PhpStorm.
 * User: mongd
 * Date: 14-Aug-18
 * Time: 10:27 PM
 */

namespace backend\modules\user\rbac;

use yii\rbac\Rule;
use backend\modules\classroom\models\ClassRoom;

class ClassRoomRule extends Rule
{
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            $model = $params['model'];
        } else {
            $id = \Yii::$app->request->get('id');
            $model = \Yii::$app->controller->findModel($id);
        }

        return $model->created_by == $user;
    }
}
