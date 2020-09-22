<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\user\models\query;

use yii\db\ActiveQuery;
use backend\modules\user\models\RbacAuthItem;

class RbacAuthItemQuery extends ActiveQuery
{
    public function findPermission()
    {
        $this->andWhere(['rbac_auth_item.type' => [RbacAuthItem::TYPE_PERMISSION_BACKEND, RbacAuthItem::TYPE_PERMISSION_FRONTEND]]);
        return $this;
    }

    public function findRole()
    {
        $this->andWhere(['rbac_auth_item.type' => [RbacAuthItem::TYPE_ROLE_BACKEND, RbacAuthItem::TYPE_ROLE_FRONTEND]]);
        return $this;
    }
}
