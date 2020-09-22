<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
//    public $basePath = '@webroot/backend';
//    public $baseUrl = '@web/backend';
    public $js = [
        '/vendors/plugins/semantic/js/semantic-dropdown.js',
        '/vendors/js/forms/select/select2.full.min.js',
        '/js/scripts/forms/select/form-select2.min.js',

        '/vendors/js/pickers/dateTime/moment-with-locales.min.js',
        '/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js',
        '/vendors/js/pickers/pickadate/picker.js',
        '/vendors/js/pickers/pickadate/picker.date.js',
        '/vendors/js/pickers/pickadate/picker.time.js',
        '/vendors/js/pickers/daterange/daterangepicker.js',
        '/vendors/js/forms/validation/jquery.validate.min.js',

        '/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
        '/vendors/js/forms/toggle/switchery.min.js',

        'js/scripts/pickers/jqueryDateRangePicker/jquery.daterangepicker.min.js',

        'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js',

        '/vendors/js/extensions/toastr.min.js',

        '/vendors/js/extensions/jquery.knob.min.js',
        '/js/scripts/extensions/knob.js',

        '/js/scripts/ui/scrollable.js',
        'https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js',

        '/tinymce/tinymce.min.js',
        'https://cdn.myauris.vn/assets/my-gridview/my-gridview.js',
        'https://cdn.myauris.vn/js/loctran.js',
        'https://cdn.myauris.vn/js/tamnguyen.js',
        'https://cdn.myauris.vn/js/mycustom.js',
        'https://cdn.myauris.vn/plugins/myDiv/myDiv.js',

        'js/call/StringeeSDK-1.5.1.js',
        'https://cdn.myauris.vn/assets/call/call.js',
        'https://cdn.myauris.vn/assets/editor/editor.js',
        'https://cdn.myauris.vn/assets/loading/myLoading.js',
        '/js/main.js',
    ];

    public $css = [
        'https://fonts.googleapis.com/css?family=Muli:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
        '/vendors/plugins/semantic/css/semantic.min.css',

        'js/scripts/pickers/jqueryDateRangePicker/daterangepicker.css',

        '/vendors/css/extensions/unslider.css',
        '/vendors/css/weather-icons/climacons.min.css',

        '/vendors/css/forms/selects/select2.min.css',

        '/vendors/css/pickers/datetime/bootstrap-datetimepicker.css',
        '/vendors/css/pickers/daterange/daterangepicker.css',
        '/vendors/css/pickers/pickadate/pickadate.css',
        '/vendors/css/file-uploaders/dropzone.min.css',

        '/css/plugins/file-uploaders/dropzone.min.css',

        '/vendors/css/forms/toggle/switchery.min.css',

        'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css',

        '/vendors/css/extensions/toastr.css',
        '/css/plugins/extensions/toastr.css',

        '/css/core/colors/palette-gradient.css',

        '/css/app.css',

        '/css/core/menu/menu-types/vertical-menu.css',
        '/css/core/colors/palette-climacon.css',
        '/fonts/simple-line-icons/style.min.css',
        '/vendors/css/tables/jsgrid/jsgrid-theme.min.css',
        '/vendors/css/tables/jsgrid/jsgrid.min.css',
        'https://cdn.myauris.vn/plugins/myCss/myPlugins.css',
        '/vendors/plugins/fancybox/dist/jquery.fancybox.css',

        '/css/custom.css',

        'https://cdn.myauris.vn/plugins/myDiv/myDiv.css',
        'https://cdn.myauris.vn/assets/call/myCall.css',
        'https://cdn.myauris.vn/assets/editor/editor.css',
        'https://cdn.myauris.vn/assets/loading/myLoading.css',
        'https://cdn.myauris.vn/css/loctran.css',
        'https://cdn.myauris.vn/css/tamnguyen.css',
        'https://cdn.myauris.vn/css/mycustom.css',

    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
