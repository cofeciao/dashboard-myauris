<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 18-Oct-18
 * Time: 5:02 PM
 */

namespace common\widgets;

use yii\base\Widget;

class ModavaDateTimeRange extends Widget
{
    public $label;

    public $id;


    public function init()
    {
    }

    public function run()
    {
        return $this->getHtml();
    }

    public function getHtml()
    {
        return '
            <div class="form-group">
              <label for="jobTitle2">Event Date - Time :</label>
              <div class="input-group">
                <input type="text" class="form-control datetime" id="jobTitle2">
                <div class="input-group-append">
                  <span class="input-group-text">
                    <span class="ft-calendar"></span>
                  </span>
                </div>
              </div>
            </div>
        ';
    }
}
