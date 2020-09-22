<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 14-Jan-19
 * Time: 11:13 AM
 */

namespace backend\modules\clinic\models;

use backend\modules\clinic\models\query\ClinicQuery;

class Customer extends Clinic
{
    const DA_DEN = 1;

    public static function find()
    {
        return new ClinicQuery(get_called_class());
    }

    public function getKhachDen()
    {
        return self::find()->where(['da_den' => self::DA_DEN])->published()->all();
    }
}
