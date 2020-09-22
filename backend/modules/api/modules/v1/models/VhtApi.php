<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 23-04-2019
 * Time: 04:44 PM
 */

namespace backend\modules\api\modules\v1\models;

use yii\db\ActiveRecord;

class VhtApi extends ActiveRecord
{
    public static function tableName()
    {
        return 'dep365_send_sms';
    }
}
