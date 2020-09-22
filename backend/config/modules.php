<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 04-Jun-18
 * Time: 11:02 AM
 */

return [
    'filemanager' => [
        'class' => 'backend\modules\filemanager\Filemanager',
    ],
    'user' => [
        'class' => 'backend\modules\user\User',
        'shouldBeActivated' => false,
        'enableLoginByPass' => false,
    ],
    'option' => [
        'class' => 'backend\modules\option\Option',
    ],
    'customer' => [
        'class' => 'backend\modules\customer\Customer',
    ],
    'location' => [
        'class' => 'backend\modules\location\Location',
    ],
    'setting' => [
        'class' => 'backend\modules\setting\Setting',
    ],
    'api' => [
        'class' => 'backend\modules\api\Api',
    ],
    'quytac' => [
        'class' => 'backend\modules\quytac\Quytac',
    ],
    'clinic' => [
        'class' => 'backend\modules\clinic\Clinic',
    ],
    'general' => [
        'class' => 'backend\modules\general\General',
    ],
    'affiliate' => [
        'class' => 'backend\modules\affiliate\Affiliate',
    ],
    'log' => [
        'class' => 'backend\modules\log\Log',
    ],
    'testab' => [
        'class' => 'backend\modules\testab\Testab',
    ],
    'baocao' => [
        'class' => 'backend\modules\baocao\Baocao',
    ],
    'support' => [
        'class' => 'backend\modules\support\Support',
    ],
    'helper' => [
        'class' => 'backend\modules\helper\Helper',
    ],
    'booking' => [
        'class' => 'backend\modules\booking\Booking',
    ],
    'social' => [
        'class' => 'backend\modules\social\Social',
    ],
    'events' => [
        'class' => 'backend\modules\events\Event',
    ],
    'gridview' => [
        'class' => '\kartik\grid\Module',
    ],
    'directsale' => [
        'class' => 'backend\modules\directsale\DirectSale',
    ],
    'test' => [
        'class' => 'backend\modules\test\Test',
    ],
    'screenonline' => [
        'class' => 'backend\modules\screenonline\ScreenOnline',
    ],
    'screens' => [
        'class' => 'backend\modules\screens\Screens',
    ],
    'toothstatus' => [
        'class' => 'backend\modules\toothstatus\ToothStatus',
    ],
    'report' => [
        'class' => 'backend\modules\report\Report',
    ],
    'chi' => [
        'class' => 'backend\modules\chi\Chi',
    ],
    'recommend' => [
        'class' => 'backend\modules\recommend\Recommend',
    ],
    'labo' => [
        'class' => 'backend\modules\labo\Labo',
    ],
    'appmyauris' => [
        'class' => 'backend\modules\appmyauris\AppMyauris',
    ],
    'bacsi' => [
        'class' => 'backend\modules\bacsi\Bacsi',
    ],
    'seo' => [
        'class' => 'backend\modules\seo\Seo',
    ],
    'issue' => [
        'class' => 'backend\modules\issue\Issue',
    ],
    'cskh' => [
        'class' => 'backend\modules\cskh\Cskh',
    ],
];
