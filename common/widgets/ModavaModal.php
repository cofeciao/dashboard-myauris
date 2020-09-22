<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 18-Oct-18
 * Time: 11:12 AM
 */

namespace common\widgets;

use yii\base\Widget;

class ModavaModal extends Widget
{
    /*
     * Id modal
     */
    public $id;
    /*
     * Kiểu modal
     */
    public $modalTheme = 'bg-primary white';
    /*
     * Kích cỡ modal
     * Bao gồm như: modal-xs, modal-sm, modal-lg, modal-xl
     * Để trống thì ở chế độ default.
     */
    public $modalSize = '';

    /*
     * Nội dung header
     */
    public $header = 'Modal';

    /*
     * Nội dung truyền vào Modal
     */
    public $content;


    public function run()
    {
        if (empty($this->id)) {
            return false;
        }
        return $this->getHtml();
    }

    public function getHtml()
    {
        return '
        <div class="modal fade text-left" id="' . $this->id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel20"
          aria-hidden="true">
            <div class="modal-dialog ' . $this->modalSize . '" role="document">
              <div class="modal-content">
                <div class="modal-header ' . $this->modalTheme . '">
                  <h4 class="modal-title" id="myModalLabel20">' . $this->header . '</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  ' . $this->content . '
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-outline-primary">Save changes</button>
                </div>
              </div>
           </div>
        </div>
        ';
    }
}
