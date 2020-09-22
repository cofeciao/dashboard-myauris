<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 18-Oct-18
 * Time: 4:06 PM
 */

namespace common\widgets;

use yii\base\Widget;

class ModavaDate extends Widget
{
    public $label;

    public $idDate;

    private $labelPri;

    public function init()
    {
        if (empty($this->label)) {
            $this->labelPri = '';
        } else {
            $this->labelPri = '<label for="' . $this->idDate . '">' . $this->label . ' :</label>';
        }
    }

    public function run()
    {
        if (empty($this->idDate)) {
            return false;
        }
        return $this->getHtml();
    }

    public function getHtml()
    {
        return '
                <div class="form-group">
                    ' . $this->labelPri . '
                    <input type="datetime" class="form-control" id="' . $this->idDate . '">
                </div>
            ';
    }
}
