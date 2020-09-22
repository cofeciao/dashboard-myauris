<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\api\modules\models\query;

use yii\db\ActiveQuery;
use backend\modules\api\modules\models\User;

class UserQuery extends ActiveQuery
{
    public function findUser()
    {
        $this->andWhere(['not in', 'user.id', \Yii::$app->user->id]);
        $this->andWhere(['in', 'created_by', User::getChild(\Yii::$app->user->id)]);
        return $this;
    }

    public function notDeleted()
    {
        $this->andWhere(['!=', 'status', \backend\modules\api\modules\models\User::STATUS_DELETED]);
        return $this;
    }

    /**
     * @return $this
     */
    public function active()
    {
        $this->andWhere(['status' => User::STATUS_ACTIVE]);
        return $this;
    }
}
