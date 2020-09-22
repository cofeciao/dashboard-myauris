<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15-Jan-19
 * Time: 2:43 PM
 */

namespace backend\components;

use Yii;

class CustomerController extends MyController
{
    public $cache;

    public function init()
    {
        $this->cache = Yii::$app->cache;
        $endDate = strtotime(date('d-m-Y')) + 86399;
        $key = 'redis-call-success-' . $endDate;
        $key1 = 'redis-new-phone-' . $endDate;
        $key2 = 'redis-khach-dh-' . $endDate;
        $key3 = 'redis-khach-old-fail-to-dathen-' . $endDate;
        $this->cache->delete($key);
        $this->cache->delete($key1);
        $this->cache->delete($key2);
        $this->cache->delete($key3);
        parent::init();
    }
}
