<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\clinic\models\query;

use backend\modules\user\models\User;
use Yii;
use yii\db\ActiveQuery;

class LichDieuTriQuery extends ActiveQuery
{
    public function published()
    {
        $this->orderBy(['phong_kham_lich_dieu_tri.id' => SORT_DESC]);
        return $this;
    }

    public function coso()
    {
        $user = new User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);

        if ($roleUser != User::USER_DEVELOP) {
            $this->orderBy(['phong_kham_lich_dieu_tri.co_so' => Yii::$app->user->id]);
        }
        return $this;
    }
}
