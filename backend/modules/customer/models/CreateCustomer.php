<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19-Feb-19
 * Time: 11:08 AM
 */

namespace backend\modules\customer\models;

use common\models\UserProfile;
use Yii;
use backend\modules\user\models\User;
use yii\base\BaseObject;
use yii\db\Transaction;
use GuzzleHttp\Client;
use yii\helpers\Json;

class CreateCustomer extends BaseObject implements \yii\queue\JobInterface
{
    public $post;
    public function execute($queue)
    {
    }
}
