<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 20-Oct-18
 * Time: 2:36 PM
 */

namespace common\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ModavaCheckbox extends Widget
{
    public $id;

    public $value; //1 hoặc 0

    public $className = 'check-toggle';

    private $for;

    private $label;

    public $data;

    public function init()
    {
        $this->for = $this->className . '-' . $this->id;
        $this->label = '<label class="custom-control-label" for="' . $this->for . '"></label>';
    }

    public function run()
    {
        if ($this->value == 1 || $this->value == 0) {
            return $this->getHtml();
        }
        return false;
    }

    public function getHtml()
    {
        return Html::tag(
            'div',
            Html::checkbox('customCheck', $this->value, ['id' => $this->for,'value' => $this->id, 'data' => $this->data, 'class' => "custom-control-input " . $this->className]) . $this->label,
            [
                'class' => 'custom-control custom-checkbox'
            ]
        );
    }
}
