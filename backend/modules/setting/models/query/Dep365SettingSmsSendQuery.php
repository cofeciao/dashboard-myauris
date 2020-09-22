<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\setting\models\query;

use backend\modules\setting\models\Dep365SettingSmsSend;
use yii\db\ActiveQuery;

class Dep365SettingSmsSendQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => Dep365SettingSmsSend::STATUS_PUBLISHED]);
        $this->orderBy(['id' => SORT_ASC]);
        return $this;
    }
}
