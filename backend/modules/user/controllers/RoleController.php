<?php

namespace backend\modules\user\controllers;

use backend\modules\user\components\RbacAuthItemComponents;
use yii\rbac\Item;

class RoleController extends RbacAuthItemComponents
{
    protected $type = Item::TYPE_ROLE;
}
