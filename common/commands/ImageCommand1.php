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

class ImageCommand1 extends BaseObject implements SelfHandlingCommand
{
    public $path = '@frontend/web';
    /*
     * Đường dẫn hình ảnh truyền vào.
     */
    public $alias = '/uploads/';

    /*
     * Đường dẫn lưu hình ảnh.
     */
    private $pathAlias;

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

    public $quality = 10;

    /*
     * Thông tin hình ảnh.
     */
    public $infoImage;

    public $fileName;

    public $crop = true;

    public $ratioOffset = true;

    public $debug = false;

    public $img_link = false;

    public function init()
    {
        if ($this->img_link == true) {
            $http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
            $img = str_replace($http, '', $this->image);
            $pathImg = \Yii::getAlias('@backend') . $img;
            $this->infoImage = @pathinfo($pathImg);
            $this->pathAlias = \Yii::getAlias($this->path) . $this->alias;
        } else {
            $this->infoImage = @pathinfo($this->image);
            $this->pathAlias = \Yii::getAlias($this->path) . $this->alias;
            /*Trả về đường dẫn tương đối của hình ảnh*/
            $this->image = str_replace(FRONTEND_HOST_INFO . \Yii::$app->getUrlManager()->baseUrl, \Yii::getAlias($this->path), $this->image);
        }

        if (!file_exists($this->pathAlias)) {
            mkdir($this->pathAlias, 0777, true);
        }

        $this->fileName = $this->fileName != null ? $this->fileName : $this->getName($this->infoImage['filename']) . '.' . $this->infoImage['extension'];
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
        /* lấy width và height */
        list($width, $height) = @getimagesize($command->image);
        /* vị trí dst_x và dst_y luôn = 0 */
        $dst_x = 0;
        $dst_y = 0;

        if ($command->width != null && $command->height != null) {
            /* có kích thước cố định truyền vào */
            /*
             * gán lại dst_w và dst_h theo kích thước truyền vào
             * tính lại src_w - src_h và src_x - src_y cho hình
            */
            $dst_w = $command->width;
            $dst_h = $command->height;

            $ratio = $command->width / $command->height;
            $height_tmp = $width / $ratio;
            if ($height_tmp > $height) {
                $src_x = ($width - ($height * $ratio)) / 2;
                $src_y = 0;
                $src_w = $height * $ratio;
                $src_h = $height;
            } else {
                $src_x = 0;
                $src_y = ($height - $height_tmp) / 2;
                $src_w = $width;
                $src_h = $height_tmp;
            }
        } else {
            /*
             * src_x và src_y luôn = 0 (luôn lấy theo tỉ lệ hình, chỉ resize lại hình theo width hoặc height truyền vào [nếu có])
             * không có kích thước cố định truyền vào
            */
            $src_x = 0;
            $src_y = 0;
            if ($command->width != null && $command->height == null) {
                /*
                 * có width mà không có height
                 * lấy tỉ lệ của width truyền vào width của hình
                 * => set height và tính tỉ lệ
                */
                $ratio = $command->width / $width;
                $command->height = $height * $ratio;
                $dst_w = $command->width;
                $dst_h = $command->height;
                $src_w = $width;
                $src_h = $height;
            } elseif ($command->width == null && $command->height != null) {
                /*
                 * có height mà không có width
                 * lấy tỉ lệ của height truyền vào và height của hình
                 * => set width và tính tỉ lệ
                */
                $ratio = $command->height / $height;
                $command->width = $width * $ratio;
                $dst_w = $command->width;
                $dst_h = $command->height;
                $src_w = $width;
                $src_h = $height;
            } else {
                /*
                 * không có width và height
                 * => kích thước hình như hình gốc, vị trí như hình gốc
                */
                $dst_w = $width;
                $dst_h = $height;
                $src_w = $width;
                $src_h = $height;
            }
        }




        /*$command->height = $command->height != null ? $command->height : $height;
        $command->width = $command->width != null ? $command->width : $width;


        $ratio_w = ($command->width / $width);
        $ratio_h = ($command->height / $height);
        $ratio = ($ratio_w < $ratio_h) ? $ratio_w : $ratio_h;

        $thumb_w_resize = $width * $ratio;
        $thumb_h_resize = $height * $ratio;

        $thumb_w_offset = ($command->width - $thumb_w_resize) / 2.0;
        $thumb_h_offset = ($command->height - $thumb_h_resize) / 2.0;

        $x = 0;
        $y = 0;

        if ($command->ratioOffset) {
            $thumb_w_resize = $command->width;
            $thumb_h_resize = $command->height;

            $thumb_w_offset = 0;
            $thumb_h_offset = 0;
        }

        if ($command->crop) {
            $r = $width / $height;
            if ($width > $height) {
                $w = $width * abs($r - $command->width / $command->height);
                $x = $w / 2;
                $width = ceil($width - $w);
            } else {
                $h = $height * abs($r - $command->width / $command->height);
                $y = $h / 2;
                $height = ceil($height - $h);
            }
        }*/

        /*
         * imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
         * Nói cách khác, hình ảnh được ghép sẽ lấy một khu vực hình chữ nhật từ $src_image chiều rộng $src_w và chiều cao $src_h ở vị trí ( $src_x, $src_y ) và đặt nó trong một khu vực hình chữ nhật $dst_image có chiều rộng $dst_w và chiều cao $dst_h ở vị trí ( $dst_x, $dst_y ).
         * cần có:
         * $dst_x
         * $dst_y
         * $src_x
         * $src_y
         * $dst_w
         * $dst_h
         * $src_w
         * $src_h
        */
        /*return json_encode([
            'dst_x' => $dst_x,
            'dst_y' => $dst_y,
            'src_x' => $src_x,
            'src_y' => $src_y,
            'dst_w' => $dst_w,
            'dst_h' => $dst_h,
            'src_w' => $src_w,
            'src_h' => $src_h,
        ]);*/
        /* tạo khung cho hình ảnh mới với width  */
        $newPng = @imagecreatetruecolor($dst_w, $dst_h);
        @imagealphablending($newPng, false);
        $color = @imagecolortransparent($newPng, @imagecolorallocatealpha($newPng, 0, 0, 0, 127));
        @imagefill($newPng, 0, 0, $color);
        @imagesavealpha($newPng, true);
        $aliasImage = $command->pathAlias . $command->fileName;
        $extension = strtoupper($extension);
        if ($extension == 'JPG' || $extension == 'JPEG') {
            $img = @imagecreatefromjpeg($command->image);
            $type = 'jpeg';
        } elseif ($extension == 'PNG') {
            $img = @imagecreatefrompng($command->image);
            $type = 'jpg';
        } else {
            return false;
        }

        @imagesavealpha($img, true);

        @imagecopyresampled($newPng, $img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        if ($command->debug == false) {
            header("Content-Type: image/". $type);
        }
        @imagepng($newPng, $aliasImage);

        @imagedestroy($img);
        @imagedestroy($newPng);

        return $command->fileName;
    }

    public function getName($name)
    {
        return MyHelper::createAlias($name) . '-' . MyHelper::randomStringLowercase() . '-' . time();
    }
}
