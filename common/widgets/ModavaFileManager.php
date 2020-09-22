<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 18-Oct-18
 * Time: 11:43 AM
 */

namespace common\widgets;

use yii\base\Widget;

class ModavaFileManager extends Widget
{
    public $idImage;

    public $idModal;

    /*
     * Kiểu modal
     */
    public $modalTheme = 'bg-primary white';

    /*
     * Nội dung header
     */
    public $header;

    public function init()
    {
        $this->header = \Yii::t('backend', 'File Manager');
    }

    public function run()
    {
        return $this->getHtml();
    }

    public function getHtml()
    {
        return '
        <div class="modal fade text-left" id="' . $this->idModal . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel20"
          aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="modal-header ' . $this->modalTheme . '">
                  <h4 class="modal-title" id="myModalLabel20">' . $this->header . '</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <iframe src="' . FRONTEND_HOST_INFO . '/5F4143DD0785DD1BC9590C016B6EFB53/dialog.php?type=2&field_id=' . $this->idImage . '"
                        style="width: 100%; height: 500px;"></iframe>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">' . \Yii::t("backend", "Close") . '</button>
                </div>
              </div>
           </div>
        </div>
        ';
    }
}
