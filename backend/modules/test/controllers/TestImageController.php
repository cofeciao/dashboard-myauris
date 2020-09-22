<?php

namespace backend\modules\test\controllers;

use backend\components\MyController;

class TestImageController extends MyController
{
    public function actionIndex()
    {
        $this->layout = '@backend/views/layouts/public';
        return $this->render('index', []);
    }

    public function actionHandleImage($w = null, $h = null)
    {
        $img = \Yii::$app->basePath . '/web/images/default/background-login-default.jpg';
        header('Content-Type: image/jpeg');
        if ($w == null && $h == null) {
            echo file_get_contents($img);
        }
        $info = @pathinfo($img);
        list($width, $height) = @getimagesize($img);
        $dst_x = 0;
        $dst_y = 0;

        if ($w != null && $h != null) {
            /* có kích thước cố định truyền vào */
            /*
             * gán lại dst_w và dst_h theo kích thước truyền vào
             * tính lại src_w - src_h và src_x - src_y cho hình
            */
            $dst_w = $w;
            $dst_h = $h;

            $ratio = $w / $h;
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
            if ($w != null && $h == null) {
                /*
                 * có width mà không có height
                 * lấy tỉ lệ của width truyền vào width của hình
                 * => set height và tính tỉ lệ
                */
                $ratio = $w / $width;
                $h = $height * $ratio;
                $dst_w = $w;
                $dst_h = $h;
                $src_w = $width;
                $src_h = $height;
            } elseif ($w == null && $h != null) {
                /*
                 * có height mà không có width
                 * lấy tỉ lệ của height truyền vào và height của hình
                 * => set width và tính tỉ lệ
                */
                $ratio = $h / $height;
                $w = $width * $ratio;
                $dst_w = $w;
                $dst_h = $h;
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
        $newImg = @imagecreatetruecolor($dst_w, $dst_h);
        @imagealphablending($newImg, false);
        $color = @imagecolortransparent($newImg, @imagecolorallocatealpha($newImg, 0, 0, 0, 127));
        @imagefill($newImg, 0, 0, $color);
        @imagesavealpha($newImg, true);
        $extension = strtoupper($info['extension']);
        if ($extension == 'JPG' || $extension == 'JPEG') {
            $img = @imagecreatefromjpeg($img);
            $type = 'jpeg';
        } elseif ($extension == 'PNG') {
            $img = @imagecreatefrompng($img);
            $type = 'png';
        } else {
            return false;
        }

        @imagesavealpha($img, true);

        @imagecopyresampled($newImg, $img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        if ($type == 'jpeg') {
            @imagejpeg($newImg);
        } else {
            @imagepng($newImg);
        }
    }
}
