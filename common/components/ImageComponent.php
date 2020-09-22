<?php
namespace common\components;

use common\helpers\MyHelper;
use yii\base\BaseObject;

class ImageComponent extends BaseObject
{
    public function handleImage($image = null, $width = null, $height = null)
    {
        if ($image == null || !MyHelper::urlImageExists($image)) {
            return null;
        }
    }
}
