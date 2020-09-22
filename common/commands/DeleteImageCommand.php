<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 09-Aug-18
 * Time: 10:44 AM
 */

namespace common\commands;

use common\helpers\MyHelper;
use function GuzzleHttp\Psr7\str;
use trntv\bus\interfaces\SelfHandlingCommand;
use yii\base\BaseObject;

class DeleteImageCommand extends BaseObject implements SelfHandlingCommand
{
    public $getAlias = '@backend/web';

    public $image;

    public $alias;

    public $imagePath;

    public function init()
    {
        $this->imagePath = \Yii::getAlias($this->getAlias) . $this->alias . $this->image;
    }

    public function handle($command)
    {
        if (!MyHelper::urlImageExists($command->image)) {
            return false;
        }

        if (@getimagesize($command->imagePath)) {
            unlink($command->imagePath);
            return true;
        } else {
            return false;
        }
    }
}
