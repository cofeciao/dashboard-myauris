<?php

namespace backend\modules\location\widgets;

use yii\base\Widget;

class CheckboxWidget extends Widget
{
    public $model;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        parent::run(); // TODO: Change the autogenerated stub
        return $this->render('checkboxWidget', [
            'model' => $this->model,
        ]);
    }
}
