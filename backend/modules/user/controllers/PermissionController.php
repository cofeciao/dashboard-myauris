<?php

namespace backend\modules\user\controllers;

use backend\modules\user\components\RbacAuthItemComponents;
use yii\rbac\Item;

/**
 * RbacAuthItemController implements the CRUD actions for RbacAuthItem model.
 */
class PermissionController extends RbacAuthItemComponents
{
    public $type = Item::TYPE_PERMISSION;
}
