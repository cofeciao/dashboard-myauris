$(document).ready(function () {
    'use strict';

    $('.block-page').on('click', function (event) {
        if (!event.ctrlKey && !event.metaKey) {
            $.blockUI({
                message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Loading...</div>',
                overlayCSS: {
                    backgroundColor: '#FFF',
                    opacity: 0.9,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        }
    });

    $('.swal-modal').on('click', function () {
        $.blockUI({
            message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    });

    $('.block-menu-left').on('click', function (event) {
        if (!event.ctrlKey && !event.metaKey) {
            $('body').myLoading({
                fixed: true
            });
            /*$.blockUI({
                message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Loading...</div>',
                overlayCSS: {
                    backgroundColor: '#FFF',
                    opacity: 0.9,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });*/
        }
    });
    // $('.scroll-horizontal').DataTable({
    //     "scrollX": true
    // });

    $('.datetime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'DD/MM/YYYY hh:mm:ss'
        }
    });

    $('.ui.dropdown').dropdown({
        forceSelection: false
    });
    $('body').on('click', '.clear-value', function(e) {
        e.preventDefault();
        $(this).closest('.input-group').find('input').val('');
    });
    $('body').on('click', '.clear-option', function(e){
        e.preventDefault();
        var selectedVal = $(this).closest('.input-group').find('option:selected').val();
        $(this).closest('.input-group').find('option:selected').removeAttr('selected'); //.prop('selected', false);
        $(this).closest('.input-group').find('.ui.dropdown').dropdown('restore default text')
                                                            .dropdown('remove selected', selectedVal);
    });

    tinymce.init({
        selector: 'textarea#content, textarea#content1, textarea#content2,textarea#content3, textarea#content4',
        height: 350,
        width: "",
        plugins: [
            "codemirror advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools code fullscreen"
        ],
        toolbar1: "undo redo bold italic underline strikethrough cut copy paste| alignleft aligncenter alignright alignjustify bullist numlist outdent indent blockquote searchreplace | styleselect formatselect fontselect fontsizeselect ",
        toolbar2: "table | hr removeformat | subscript superscript | charmap emoticons ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft | link unlink anchor image media | insertdatetime preview | forecolor backcolor print fullscreen code ",
        image_advtab: true,
        menubar: false,
        toolbar_items_size: 'small',

        relative_urls: false,
        remove_script_host: false,
        filemanager_title: "Media Manager",
        external_filemanager_path: homeUrl() + "/5F4143DD0785DD1BC9590C016B6EFB53/",
        external_plugins: {"filemanager": homeUrl() + "/5F4143DD0785DD1BC9590C016B6EFB53/plugin.min.js"},
    });

    tinymce.init({
        selector: 'textarea#desc, textarea#desc1, textarea#desc2, textarea#desc3, textarea#desc4',
        height: 250,
        width: "",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools code fullscreen"
        ],
        toolbar1: "undo redo bold italic underline | alignleft aligncenter alignright alignjustify bullist numlist outdent indent blockquote link unlink anchor image media | preview | forecolor backcolor fullscreen code",
        image_advtab: true,
        menubar: false,
        toolbar_items_size: 'small',
        relative_urls: false,
        remove_script_host: false,
        filemanager_title: "Media Manager",
        // external_filemanager_path: homeUrl() + "/5F4143DD0785DD1BC9590C016B6EFB53/",
        // external_plugins: {"filemanager": homeUrl() + "/5F4143DD0785DD1BC9590C016B6EFB53/plugin.min.js"},

    });
});
