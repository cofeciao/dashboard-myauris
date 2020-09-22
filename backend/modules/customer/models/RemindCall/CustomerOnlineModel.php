<?php

namespace backend\modules\customer\models\RemindCall;

use backend\models\CustomerModel;
use yii\behaviors\AttributesBehavior;
use yii\db\ActiveRecord;

class CustomerOnlineModel extends CustomerModel
{
    const SCENARIO_DAT_HEN_LAI = 'dat-hen-lai';

    public $total;//tổng lịch hẹn theo id face_fanpage

    public function rules()
    {
        return [
            [['co_so', 'time_lichhen'], 'required', 'on' => self::SCENARIO_DAT_HEN_LAI],
            [['status', 'status_fail', 'dat_hen_fail', 'dat_hen'], 'safe'],
            [['reason_reject'], 'string'],
        ];
    }
}
