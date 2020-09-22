<?php

namespace backend\modules\chi\models\query;

use backend\modules\chi\models\Deadline;

/**
 * This is the ActiveQuery class for [[DeXuatChi]].
 *
 * @see DeXuatChi
 */
class DeadlineQuery extends \yii\db\ActiveQuery
{
    public function notExpired()
    {
        return $this->andWhere([
            'AND',
            ['<', Deadline::tableName() . '.thoi_gian_bat_dau', time()],
            ['>', Deadline::tableName() . '.thoi_gian_ket_thuc', time()]
        ]);
    }
}
