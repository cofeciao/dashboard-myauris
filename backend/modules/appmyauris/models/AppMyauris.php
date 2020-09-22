<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27-Apr-19
 * Time: 9:38 AM
 */

namespace backend\modules\appmyauris\models;

use yii\base\Model;

class AppMyauris extends Model
{
    const SETTING_APP_KEY_CONTENT = 'setting_app_myauris_key_content';

    public $content;

    public function rules()
    {
        return [
            [['content'], 'string'],
            [['content'], 'safe'],
        ];
    }
}
