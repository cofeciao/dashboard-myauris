<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 2/27/2020
 * Time: 5:05 PM
 */

namespace backend\modules\user\models;

use common\models\UserProfile;
use yii\base\BaseObject;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

class UserSubRole extends ActiveRecord
{
    const ROLE_TRUONG_PHONG = 'truong_phong';
    const ROLE_KE_TOAN = 'ke_toan';
    const ROLE_TEAM_LEAD = 'team_lead';
    const ROLE_ONLINE = 'online';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_sub_role}}';
    }

    public static $roles = [
        self::ROLE_TRUONG_PHONG => 'Trưởng Phòng',
        self::ROLE_KE_TOAN => 'Kế Toán',
        self::ROLE_TEAM_LEAD => 'Team Lead',
        self::ROLE_ONLINE => 'Nhân viên Online',
    ];

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['role'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ]
        ];
    }


    public static function is_current_user_is_ketoan()
    {
        $curr_user = \common\models\User::getUserOne(\Yii::$app->user->id);
        if ($curr_user->subroleHasOne != null && $curr_user->subroleHasOne->role == 'ke_toan') {
            return true;
        } else {
            return false;
        }
    }

    public static function is_current_user_is_truongphong()
    {
        $curr_user = \common\models\User::getUserOne(\Yii::$app->user->id);
        if ($curr_user->subroleHasOne != null && $curr_user->subroleHasOne->role == 'truong_phong') {
            return true;
        } else {
            return false;
        }
    }


    public static function is_current_user_is_teamlead()
    {
        $curr_user = \common\models\User::getUserOne(\Yii::$app->user->id);
        if ($curr_user->subroleHasOne != null && $curr_user->subroleHasOne->role == 'team_lead') {
            return true;
        } else {
            return false;
        }
    }

    public static function getListTruongPhong()
    {
        $users = UserProfile::find()->select(['user_sub_role.user_id', 'user_profile.fullname'])->innerJoin('user_sub_role', 'user_sub_role.user_id = user_profile.user_id')->where(['user_sub_role.role' => UserSubRole::ROLE_TRUONG_PHONG]);

        $res = $users->all();
        return $res;
    }
}
