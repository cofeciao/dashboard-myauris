<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16-May-19
 * Time: 2:49 PM
 */

namespace backend\models\doanhthu;

use backend\modules\helper\models\HelperDonHang;
use yii\db\ActiveRecord;
use backend\modules\customer\models\Dep365CustomerOnlineCome;

class DonHangModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phong_kham_don_hang';
    }

    public function getCreatedBy(){
        $user = new Dep365CustomerOnlineCome();
        $userCreatedBy = $user->getUserCreatedBy($this->created_by);
        return ($userCreatedBy) ? $userCreatedBy->fullname : "";
    }
}
