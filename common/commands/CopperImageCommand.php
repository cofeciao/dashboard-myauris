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

class CopperImageCommand extends BaseObject implements SelfHandlingCommand
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
    public $height = null;

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

        $this->fileName = $this->fileName != null ?: $this->getName($this->infoImage['filename']);
    }

    public function handle($command)
    {
        if (!MyHelper::urlImageExists($command->image) || $command->image == null) {
            return false;
        }
        if (isset($command->infoImage['extension'])) {
            $extension = $command->infoImage['extension'];
        } else {
            return false;
        }

        //Không lấy được width
        if (!@getimagesize($command->image)) {
            return false;
        }
        list($width, $height) = @getimagesize($command->image);

        if ($width > $command->width) {
            if ($command->height == null) {
                //get the reduced width
                $reduced_width = ($width - $command->width);
                //now convert the reduced width to a percentage and round it to 2 decimal places
                $reduced_radio = round(($reduced_width / $width) * 100, 2);

                //ALL GOOD! let's reduce the same percentage from the height and round it to 2 decimal places
                $reduced_height = round(($height / 100) * $reduced_radio, 2);
                //reduce the calculated height from the original height
                $after_height = $height - $reduced_height;
            } else {
                $after_height = $command->height;
            }
            //Now detect the file extension
            //if the file extension is 'jpg', 'jpeg', 'JPG' or 'JPEG'
            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG') {
                //then return the image as a jpeg image for the next step
                $img = @imagecreatefromjpeg($command->image);
            } elseif ($extension == 'png' || $extension == 'PNG') {
                //then return the image as a png image for the next step
                $img = @imagecreatefrompng($command->image);
            } else {
                //show an error message if the file extension is not available
                echo 'Định dạng hình ảnh không được hỗ trợ.';
            }

            //HERE YOU GO :)
            //Let's do the resize thing
            //imagescale([returned image], [width of the resized image], [height of the resized image], [quality of the resized image]);
            $imgResized = @imagescale($img, $command->width, $after_height, $command->quality);

            //now save the resized image with a suffix called "-resized" and with its extension.
            $aliasImage = $command->pathAlias . $command->fileName . '.' . $extension;


            @imagejpeg($imgResized, $aliasImage);
            //Finally frees any memory associated with image
            //**NOTE THAT THIS WONT DELETE THE IMAGE
            @imagedestroy($img);
            @imagedestroy($imgResized);

            return $command->fileName . '.' . $extension;
        } else {
            //get the reduced width
            $reduced_width = ($width - $command->width);
            //now convert the reduced width to a percentage and round it to 2 decimal places
            $reduced_radio = round(($reduced_width / $width) * 100, 2);

            //ALL GOOD! let's reduce the same percentage from the height and round it to 2 decimal places
            $reduced_height = round(($height / 100) * $reduced_radio, 2);
            //reduce the calculated height from the original height
            $after_height = $height - $reduced_height;

            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG') {
                //then return the image as a jpeg image for the next step
                $img = @imagecreatefromjpeg($command->image);
            } elseif ($extension == 'png' || $extension == 'PNG') {
                //then return the image as a png image for the next step
                $img = @imagecreatefrompng($command->image);
            } else {
                //show an error message if the file extension is not available
                echo 'Định dạng hình ảnh không được hỗ trợ.';
            }

            $imgResized = @imagescale($img, $width, $after_height, $command->quality);

            $aliasImage = $command->pathAlias . $command->fileName . '.' . $extension;


            @imagejpeg($imgResized, $aliasImage);

            @imagedestroy($img);
            @imagedestroy($imgResized);

//            return \Yii::$app->getUrlManager()->baseUrl . $command->alias . $file . '.' . $extension;
            return $command->fileName . '.' . $extension;
        }
    }

    public function getName($name)
    {
        return MyHelper::createAlias($name) . '-' . MyHelper::randomStringLowercase(2);
    }
}
