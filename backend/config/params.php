<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 04-Jun-18
 * Time: 10:39 AM
 */

use backend\modules\clinic\controllers\ChupBanhMoiController;
use backend\modules\clinic\controllers\ChupCuiController;
use backend\modules\clinic\controllers\ChupFinalController;
use backend\modules\clinic\controllers\ChupHinhController;
use backend\modules\clinic\controllers\HinhFinalController;
use backend\modules\clinic\controllers\TkncController;
use backend\modules\clinic\controllers\UomRang1Controller;
use backend\modules\clinic\controllers\UomRang2Controller;
use backend\modules\clinic\models\PhongKhamChupBanhMoi;
use backend\modules\clinic\models\PhongKhamChupCui;
use backend\modules\clinic\models\PhongKhamChupFinal;
use backend\modules\clinic\models\PhongKhamChupHinh;
use backend\modules\clinic\models\PhongKhamHinhFinal;
use backend\modules\clinic\models\PhongKhamHinhTknc;
use backend\modules\clinic\models\PhongKhamUomRang1;
use backend\modules\clinic\models\PhongKhamUomRang2;
use backend\modules\clinic\models\UploadAudio;

return [
    'availableLocales' => [
        'vi' => 'Tiếng Việt',
        'en' => 'English',
    ],
    'ly-do-khong-lam' => [
        '1' => 'Tiềm năng',
        '2' => 'Kinh tế',
        '3' => 'Hỏi người thân',
        '4' => 'Sắp xếp thời gian',
        '5' => 'Tham khảo',
        '6' => 'Chủ động liên hệ',
        '7' => 'Có con nhỏ',
        '8' => 'Làm dịch vụ khác',
        '9' => 'Ở xa',
        '10' => 'Khách sẽ quay lại làm sau',
    ],
    'sub-menu' => [
        'news',
        'user',
        'video',
        'website',
        'gallery',
        'exploration',
        'option',
    ],
    'chup-hinh-catagory' => [
        'chup-hinh' => 1,
        'chup-banh-moi' => 2, //Trước khi điều trị
        'chup-cui' => 3,
        'chup-ket-thuc' => 4,
        'thiet-ke-nu-cuoi' => 5,
        'upload-audio' => 6, // only for upload audio
        'uom_rang_1' => 7,
        'uom_rang_2' => 8,
        'hinh_final' => 9,
        'dental-form' => 10,
    ],
    'focus-face' => [
        [
            "id" => 1,
            "label" => "hình chụp thăm khám",
            "type" => 1,
            "link" => "https://cdn.nhakhoamyauris.com/focus/focus-face.png",
        ],
        [
            "id" => 2,
            "label" => "chụp banh môi",
            "type" => 2,
            "link" => "https://cdn.nhakhoamyauris.com/focus/focus-face.png",
        ],
        [
            "id" => 3,
            "label" => "chụp cùi",
            "type" => 3,
            "link" => "https://cdn.nhakhoamyauris.com/focus/focus-face.png",
        ],
        [
            "id" => 4,
            "label" => "chụp kết thúc",
            "type" => 4,
            "link" => "https://cdn.nhakhoamyauris.com/focus/focus-face.png",
        ],
        [
            "id" => 5,
            "label" => "thiết kế nụ cười",
            "type" => 5,
            "link" => "https://cdn.nhakhoamyauris.com/focus/focus-face.png",
        ]
    ],
    'menu-active' => [

    ],
    'create-success' => 'Thêm mới thành công.',
    'create-danger' => 'Thêm mới không thành công. Hãy liên lạc ban quản trị nếu bạn cho rằng đây là lỗi hệ thống. Xin cảm ơn',
    'create-warning' => 'Bạn không thể tạo mới trong mục này.',

    'update-success' => 'Cập nhật thành công.',
    'update-danger' => 'Cập nhật không thành công. Hãy liên lạc ban quản trị nếu bạn cho rằng đây là lỗi hệ thống. Xin cảm ơn',
    'update-warning' => 'Bạn không thể cập nhật mục này.',

    'delete-success' => 'Xóa thành công.',
    'delete-danger' => 'Xóa không thành công. Hãy liên lạc ban quản trị nếu bạn cho rằng đây là lỗi hệ thống. Xin cảm ơn',
    'delete-cancer' => 'Bạn đã không xóa',
    'warning-show-hide' => 'Bạn không thể thực hiện hành động này, hãy liên hệ ban quản trị nếu yêu cầu của bạn là cần thiết. Xin cảm ơn',

    'change-password-success' => 'Bạn đã thay đổi mật khẩu thành công.',
    'change-password-error' => 'Bạn đã thay đổi mật khẩu không thành công, có thể mật khẩu cũ bạn nhập không chính xác.',
    'find-data-not-success' => 'Không tìm thấy dữ liệu trong hệ thống',

    // For API
    'tinh-trang-benh-nhan' => [
        '1' => "Bệnh tim mạch",
        '2' => "Huyết áp",
        '3' => "Đột quỵ",
        '4' => "Hen phế quản",
        '5' => "Đã từng phẫu thuật",
        '6' => "Vấn đề về xoang",
        '7' => "Bệnh gan hoặc thận",
        '8' => "Viêm loét dạ dày",
        '9' => "Viêm gan (A,B,C)",
        '10' => "Dị ứng",
        '11' => "Máu khó đông",
        '12' => "Tiểu đường",
        '13' => "Bệnh động kinh",
        '14' => "Điều trị xạ trị",
        '15' => "U hoặc K",
        '16' => "Hậu sản",
        '17' => "Thai kỳ",
    ],

    'info-config-chup-hinh' => [
        [
            "cate_id" => 1,
            "label" => "Hình chụp thăm khám",
            "folder" => ChupHinhController::FOLDER,
            "name_table_cate" => PhongKhamChupHinh::tableName(),
        ],
        [
            "cate_id" => 2,
            "label" => "Chụp banh môi",
            "folder" => ChupBanhMoiController::FOLDER,
            "name_table_cate" => PhongKhamChupBanhMoi::tableName(),
        ],
        [
            "cate_id" => 3,
            "label" => "Chụp cùi",
            "folder" => ChupCuiController::FOLDER,
            "name_table_cate" => PhongKhamChupCui::tableName(),
        ],
        [
            "cate_id" => 4,
            "label" => "Chụp kết thúc",
            "folder" => ChupFinalController::FOLDER,
            "name_table_cate" => PhongKhamChupFinal::tableName(),
        ],
        [
            "cate_id" => 5,
            "label" => "Thiết kế nụ cười",
            "folder" => TkncController::FOLDER,
            "name_table_cate" => PhongKhamHinhTknc::tableName(),
        ],
        [
            "cate_id" => 6,
            "label" => "Audio",
            "folder" => UploadAudio::FOLDER,
            "name_table_cate" => UploadAudio::tableName(),
        ],
        [
            "cate_id" => 7,
            "label" => "Ướm răng 1",
            "folder" => UomRang1Controller::FOLDER,
            "name_table_cate" => PhongKhamUomRang1::tableName(),
        ],
        [
            "cate_id" => 8,
            "label" => "Ướm răng 2",
            "folder" => UomRang2Controller::FOLDER,
            "name_table_cate" => PhongKhamUomRang2::tableName(),
        ],
        [
            "cate_id" => 9,
            "label" => "Hình Cuối cùng",
            "folder" => HinhFinalController::FOLDER,
            "name_table_cate" => PhongKhamHinhFinal::tableName(),
        ]
    ]
];
