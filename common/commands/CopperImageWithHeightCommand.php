<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 09-Aug-18
 * Time: 9:29 AM
 */

namespace common\commands;

use trntv\bus\interfaces\SelfHandlingCommand;
use yii\base\BaseObject;
use common\helpers\MyHelper;

class CopperImageWithHeightCommand extends BaseObject implements SelfHandlingCommand
{
    public $getAlias = '@backend/web';
    /*
     * Đường dẫn hình ảnh truyền vào.
     */
    public $alias = '/uploads/';

    /*
     * Đường dẫn lưu hình ảnh.
     */
    public $pathAlias;

    /*
     * Chiều rộng hình ảnh
     */
    public $width;

    /*
     * Chiều dài hình ảnh
     */
    public $height;

    /*
     * Hình ảnh khi upload
     */
    public $image = null;

    /*
     *
     */
    public $quality = 10;

    /*
     * Thông tin hình ảnh.
     */
    public $infoImage;

    public $fileName;

    public function init()
    {
        $this->infoImage = @pathinfo($this->image);
        $this->pathAlias = \Yii::getAlias($this->getAlias) . $this->alias;
        $this->image = str_replace(FRONTEND_HOST_INFO . \Yii::$app->getUrlManager()->baseUrl, \Yii::getAlias($this->getAlias), $this->image);

        if (!file_exists($this->pathAlias)) {
            mkdir($this->pathAlias, 0777, true);
        }

        $this->fileName = $this->fileName ?? $this->getName($this->infoImage['filename']) . '.' . $this->infoImage['extension'];
    }

    public function handle($command)
    {
        if (!MyHelper::urlImageExists($command->image) || $command->image == null) {
            return false;
        }
        if (!isset($command->infoImage['extension'])) {
            return false;
        }

        //Không lấy được width
        if (!@getimagesize($command->image)) {
            return false;
        }
        list($width, $height) = @getimagesize($command->image);

        if ($width > $command->width) {
            $reduced_width = ($width - $command->width);
            //now convert the reduced width to a percentage and round it to 2 decimal places
            $reduced_radio = round(($reduced_width / $width) * 100, 2);

            //ALL GOOD! let's reduce the same percentage from the height and round it to 2 decimal places
            $reduced_height = round(($height / 100) * $reduced_radio, 2);
            //reduce the calculated height from the original height
            $after_height = $height - $reduced_height;

            if ($command->infoImage['extension'] == 'jpg' || $command->infoImage['extension'] == 'jpeg' || $command->infoImage['extension'] == 'JPG' || $command->infoImage['extension'] == 'JPEG') {
                $img = @imagecreatefromjpeg($command->image);
            } elseif ($command->infoImage['extension'] == 'png' || $command->infoImage['extension'] == 'PNG') {
                $img = @imagecreatefrompng($command->image);
            } else {
                return false;
            }

            $imgResized = @imagescale($img, $command->width, $after_height, $command->quality);

            $imgCrop = @imagecrop($imgResized, ['x' => 0, 'y' => 0, 'width' => $command->width, 'height' => $command->height]);

            if ($imgCrop !== false) {
                $aliasImage = $command->pathAlias . $command->fileName;
                @imagejpeg($imgCrop, $aliasImage);
            }
            @imagedestroy($img);
            @imagedestroy($imgCrop);

            return $command->fileName;
        } else {
            return false;
        }
    }

    public function getName($name)
    {
        return MyHelper::createAlias($name) . '-' . MyHelper::randomStringLowercase(2);
    }
}
