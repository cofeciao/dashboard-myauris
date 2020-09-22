<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 06-Jul-18
 * Time: 4:42 PM
 */

return [
    'khu-vuc'       => [
        '1' => 'Miền Bắc',
        '2' => 'Miền Trung',
        '3' => 'Miền Nam',
    ],

    //Adsword begin ---------------------------
    //this array for select option metadata
    'meta_name'     => [
        '1' => [
            'amount_money' => 'Số tiền',
            'appearance'   => 'Hiển thị',
            'click'        => 'Click',
            'ctr'          => 'Ctr',
            'location'     => 'TOP',
        ],
        '2' => [
            'amount_money' => 'Số tiền',
            'appearance'   => 'Hiển thị',
            'click'        => 'Click',
            'cpc'          => 'Cpc',
        ],
        '3' => [
            'amount_money' => 'Số tiền',
            'appearance'   => 'Hiển thị',
            'cpv'          => 'Cpv',
            'views'        => 'Lượt xem',
            'view_rate'    => 'Tỉ lệ xem',
        ],
    ],//-------------------------

    //this array for admin dashboard input
    'adsword-type'  => [
        '1' => 'Keyword',
        '2' => 'Banner',
        '3' => 'Youtube',
    ], //---------------------

    //this array for admin dashboard input
    'adsword-map'   => [
        ['id' => '1', 'name' => 'Keyword',],
        ['id' => '2', 'name' => 'Banner',],
        ['id' => '3', 'name' => 'Youtube',],
    ],//------------------------

    //array for table-keyword.php
    /*
            'keywords'     => 'Keywords',
            'list_links'   => 'Trang',
            'channels'     => 'Kênh',
    */
    'table-keyword' => [
        1 => 'keywords',
        2 => 'list_links',
        3 => 'channels',
    ],//-------------
    //array for behaviors
    'meta_post'     => [
        1 => 'ctr',
        2 => 'cpv',
    ],


    //Adsword end----------------------------------------------

];
