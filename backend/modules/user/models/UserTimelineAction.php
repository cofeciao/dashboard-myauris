<?php


namespace backend\modules\user\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_timeline_action".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class UserTimelineAction extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_timeline_action';
    }

    public function rules()
    {
        return [
            [['name', 'created_at', 'created_by'], 'required'],
            [['created_at','updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string']
        ];
    }
}
