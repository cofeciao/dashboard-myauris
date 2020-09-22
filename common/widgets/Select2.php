<?php

namespace common\widgets;

use Yii;
use yii\helpers\Html;
use yii\base\Widget;

class Select2 extends Widget
{
    public $attribute;

    public $model;

    public $data;

    public $prompt = '';

    public function run()
    {
        parent::run();
        $this->renderSelect2();
    }

    public function renderSelect2()
    {
        return Html::dropDownList($this->attribute, $this->data, ['class' => 'select2', 'multiple' => 'multiple']);
    }
}
