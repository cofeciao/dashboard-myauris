<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 18-Oct-18
 * Time: 5:27 PM
 */

namespace common\widgets;

use yii\helpers\Html;
use yii\base\Widget;

class ModavaSwitch extends Widget
{
    public $model;

    public $label = '';

    private $id;

    private $checked = '';

    public function init()
    {
        if (isset($this->model)) {
            $this->id = $this->model->id;
            if ($this->model->status == 1) {
                $this->checked = true;
            }
        }

        if (!empty($this->label)) {
            $this->label = '<label for="switcherySize' . $this->id . '" class="font-medium-2 text-bold-600 ml-1">' . $this->label . '</label>';
        }
    }

    public function run()
    {
        if (empty($this->id)) {
            return false;
        }
        echo Html::tag(
            'div',
            $this->label . '</label>' . Html::checkbox('checkbox', $this->checked, ['class' => 'switchery check-toggle', 'value' => $this->id, 'id' => 'switcherySize' . $this->id, 'data-size' => 'sm'])
        );
    }
}
