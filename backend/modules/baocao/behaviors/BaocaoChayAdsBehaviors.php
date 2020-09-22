<?php
/**
 * Date: 10/29/19
 * Time: 9:43 AM
 */

namespace backend\modules\baocao\behaviors;

use yii\base\Exception;
use \yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class BaocaoChayAdsBehaviors extends AttributeBehavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsertUpdate',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeInsertUpdate',
        ];
    }


    public function beforeInsertUpdate($event)
    {
//        switch ($event->sender->post_type) {
//            case 1:
//                if (isset($event->sender->appearance) && isset($event->sender->click)) {
//                    if (! empty($event->sender->appearance) && ! empty($event->sender->click)) {
//                        $event->sender->ctr = $event->sender->appearance / $event->sender->click * 100;
//
//                        return $event->sender->ctr;
//                    } else {
//                        return $event->sender->ctr = 0;
//                    }
//                } else {
//                    throw new Exception("Missing attribute");
//                }
//                break;
//            case 3:
//                if (isset($event->sender->appearance) && isset($event->sender->amount_money)) {
//                    if (! empty($event->sender->appearance) && ! empty($event->sender->amount_money)) {
//                        $event->sender->cpv = $event->sender->appearance / $event->sender->amount_money * 100;
//
//                        return $event->sender->cpv;
//                    } else {
//                        return $event->sender->cpv = 0;
//                    }
//                } else {
//                    throw new Exception("Missing attribute");
//                }
//                break;
//        }
    }
}
