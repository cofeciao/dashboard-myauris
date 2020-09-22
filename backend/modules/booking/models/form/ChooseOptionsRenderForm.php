<?php

namespace backend\modules\booking\models\form;

class ChooseOptionsRenderForm extends \yii\base\Model
{
    const OPTION_RENDER_NEW = 'render-new';
    const OPTIONS_RENDER_SOME = 'render-some';
    const RANGE_OPTIONS = [
        self::OPTION_RENDER_NEW => 'Tạo mới trong 2 tháng tới',
        self::OPTIONS_RENDER_SOME => 'Tạo mới theo ngày cố định'
    ];

    public $options = self::OPTIONS_RENDER_SOME;

    public function rules()
    {
        return [
            [['options'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'options' => \Yii::t('backend', 'Phương thức')
        ];
    }
}
