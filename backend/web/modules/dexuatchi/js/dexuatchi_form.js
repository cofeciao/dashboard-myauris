$(document).ready(function () {
    $('.on-keyup').on('change paste keyup', function () {
        var v = $(this).val().replace(/\./g, '');
        v = v.replace(/[a-zA-Z]/, '');
        $(this).val(addCommas(v));
    }).trigger('change');

    var i = 0;
    $('body').on('focus', '.form-group.has-error input[type="text"]', function () {
        $(this).closest('.form-group').removeClass('has-error').find('.help-block').html('');
    }).on('click', '.cc-block.cc-single .btn-upload-tmp', function () {
        var tmp = $('.btn-upload.btn-upload-single.tmp').children().clone(),
            g_btn_upload = $(this).closest('.g-btn-upload');
        tmp.append('<span class="delete-file"><i class="fa fa-times"></i></span>');
        tmp.find('.field-formhoso-files').removeClass('field-formhoso-files').addClass('field-formhoso-files-' + i)
            .children('label').attr('for', 'formhoso-files-' + i)
            .next('input').attr('name', 'FormHoSo[files][' + i + ']')
            .next('input').attr({
            'name': 'FormHoSo[files][' + i + ']',
            'id': 'formhoso-files-' + i,
            'class': 'ipt-upload'
        });
        tmp.find('.field-formhoso-i').removeClass('field-formhoso-i').addClass('field-formhoso-i-' + i)
            .children('label').attr('for', 'formhoso-i-' + i)
            .next('input').attr({
            'name': 'FormHoSo[i][' + i + ']',
            'id': 'formhoso-i-' + i,
            'value': i
        }).val(i);
        tmp.insertBefore(g_btn_upload);
        tmp.find('.ipt-upload').trigger('click');
        i++;
    })
        .on('click', '.cc-block.cc-single .delete-file', function () {
            var file = $(this).closest('.g-file'),
                file_id = $(this).attr('data-hoso') || null;
            if (file_id !== null) {
                var c = confirm('Bạn muốn xoá hồ sơ này?');
                console.log(c);
                if (c) {
                    $.get(urlDeleteFile, {
                        id: file_id,
                        id_de_xuat: id_de_xuat
                    }, res => {
                        if (res.code === 200) {
                            toastr.success(res.msg);
                            file.remove();
                        } else {
                            toastr.error(res.msg);
                        }
                    }, 'json').fail(f => {
                        toastr.error('Có lỗi xảy ra');
                    });
                }
            } else {
                file.remove();
            }
        })
        .on('change', '.cc-block.cc-single .ipt-upload', function () {
            var input = this,
                group = $(this).closest('.form-group'),
                review = group.find('.review');
            group.removeClass('has-error').find('.help-block-error').html('');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var file = input.value,
                        ext = getFileExtension(file);
                    group.addClass('has-image');
                    if (ext !== undefined && ['png', 'jpg', 'jpeg', 'webp'].includes(ext)) {
                        review.children('img').attr('src', e.target.result);
                    } else {
                        var icon = getUrlIconByExt(ext);
                        review.children('img').attr('src', icon);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                group.removeClass('has-image');
                review.children('img').attr('src', '');
            }
        })
        .on('change', '#dexuatchi-danh_muc_chi', function () {
            var danhmucchi = $(this).val() || null;
            $('#dexuatchi-nhom_chi').find('option[value!=""]').remove();
            if (danhmucchi != null) {
                $.ajax({
                    type: 'POST',
                    url: urlLoadNhomChi,
                    dataType: 'json',
                    data: {
                        danhmucchi: danhmucchi
                    }
                }).done(res => {
                    console.log(res);
                    if (res.code === 200) {
                        $('#dexuatchi-nhom_chi').append(res.data).trigger('change');
                    } else {
                        toastr.error(res.msg);
                    }
                }).fail(f => {
                    console.log(f);
                    toastr.error('Có lỗi xảy ra khi load Nhóm chi');
                });
            }
        }).on('change', '#dexuatchi-nhom_chi', function () {
        var nhomchi = $(this).val() || null;
        $('#dexuatchi-khoan_chi').find('option[value!=""]').remove();
        if (nhomchi != null) {
            $.ajax({
                type: 'POST',
                url: urlLoadKhoanChi,
                dataType: 'json',
                data: {
                    nhomchi: nhomchi
                }
            }).done(res => {
                console.log(res);
                if (res.code === 200) {
                    $('#dexuatchi-khoan_chi').append(res.data).trigger('change');
                } else {
                    toastr.error(res.msg);
                }
            }).fail(f => {
                console.log(f);
                toastr.error('Có lỗi xảy ra khi load khoản chi');
            });
        }
    }).on('change', '#dexuatchi-method_payment', function () {
        var cur_method = $(this).val() || null;
        var chuyen_khoan = $('.chuyen-khoan');
        if (cur_method !== 'chuyen-khoan') {
            chuyen_khoan.hide();
        } else {
            chuyen_khoan.show();
        }
    })
        .on('click', '.input-group-addon.clear-option', function () {
            $('this').closest('.input-group').find('.select-dropdown').trigger('change');
        })
        .on('beforeSubmit', '#form-de-xuat-chi', function (e) {
            e.preventDefault();
            $('.g-list-file').find('[class*="field-formhoso-files-"]').removeClass('has-error').find('.help-block-error').html('');
            var form = $(this),
                url = form.attr('action'),
                form_data = new FormData(form[0]);
            form.myLoading({
                opacity: true,
            });
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: form_data,
                cache: false,
                processData: false,
                contentType: false
            }).done(res => {
                if (res.code === 200) {
                    toastr.success(alertSuccess, null, {
                        onHidden: function () {
                            /*window.location.reload();*/
                        }
                    });
                    if (res.url_redirect !== undefined) {
                        window.location.href = res.url_redirect;
                    } else {
                        form.myUnloading();
                    }
                } else {
                    form.myUnloading();
                    toastr.error(res.msg);
                    if (![null, undefined].includes(res.fileError)) {
                        $.each(res.fileError, function (k, v) {
                            form.find('.field-formhoso-files-' + k).addClass('has-error').find('.help-block').addClass('help-block-error').html(v);
                        });
                    }
                }
            }).fail(f => {
                form.myUnloading();
                toastr.error('Đã xảy ra lỗi');
            });
            return false;
        });
    // $('.list-cell__status input').on('change', function (e) {
    //     console.log($(this).val());
    // })
    $('#form-de-xuat-chi').on('beforeDeleteRow', function (e, row, index) {
        // console.log(row);
        // console.log(index);
        if (row.hasClass('disabled')) return false;
        let data = row.find('input,textarea').serializeArray();
        // console.log(data);
        if (row.find('.list-cell__id input').val() == '') {
            console.log('true cell id');
            row.remove();
        } else {
            $.ajax({url: urlDeleteTieuChi, method: "POST", data: data}).done(function (res) {


                if (res.code === 200) {
                    toastr.success(res.msg, 'Success');
                    row.remove();
                } else {
                    toastr.error(res.msg, 'Error');
                }
            })

        }
        return false
    }).on('beforeAddRow', function (e, row) {
        return !row.hasClass('disabled');
    }).on('afterAddRow', function (e) {
        createDatetimepicker();
    });
    var error_form_de_xuat_chi = {};
    $('body').on('afterValidate', '#form-de-xuat-chi', function (event, messages) {
        var has_error = false;
        // await
        Object.keys(messages).forEach(function (k) {
            if (messages[k].length > 0) {
                var key = Object.keys(messages[k][0])[0],
                    value = messages[k][0][key][0];
                if (has_error === false) {
                    has_error = true;
                }
                if (![null, undefined, ''].includes(key) && ![null, undefined, ''].includes(value)) {
                    error_form_de_xuat_chi[key] = value;
                }
            }
        });
        return !has_error;
    }).on('afterValidateAttribute', '#form-de-xuat-chi', function (event, el) {
        if (![null, undefined, ''].includes(error_form_de_xuat_chi[el.id])) {
            $(el.input).closest('.form-group').removeClass('has-success').addClass('has-error').find('.help-block').html(error_form_de_xuat_chi[el.id]);
            delete error_form_de_xuat_chi[el.id];
        }
    });

    function createDatetimepicker() {
        $('.thoigian_tieuchi').each(function (e) {
            if ($(this).hasClass('true')) {
                $(this).prop('readonly', true);
            } else {
                $(this).prop('readonly', true).datetimepicker({
                    autoclose: true,
                    format: 'dd-mm-yyyy HH:ii',
                    todayBtn: true,
                    pickerPosition: 'bottom-right',
                })
            }
        })
    }

    //views
    $('body').on('click', '.approve.btn', function (e) {
        e.preventDefault();
        $('body').myLoading({opacity: true});
        let url = $(this).attr('href');
        console.log(url);
        let data = {id: $(this).data('id'), status: $(this).data('status')};
        console.log(data);

        $.post(url, data, function (result) {
            console.log(result)
            $('#custom-modal').modal('toggle');
            if (result == 1) {

                toastr.success(resultSuccess, tit);
                unLoading('.content-wrapper');
            }
            if (result == 0) {
                toastr.error(resultDanger, tit);
                unLoading('.content-wrapper');
            }
            window.location.href = urlReloadPjax;
        });
    })
    //fancybox (view)
    var images_fancybox = $('a.grouped_images_view');
    images_fancybox.fancybox();
    $('body').on('click', 'a.grouped_images_view', function (e) {
        e.preventDefault();
        let find = $('body').find('.grouped_images_view');
    })


    function getFileExtension(fileName) {
        var re = /(?:\.([^.]+))?$/;
        return re.exec(fileName)[1];
    }

    function urlExists(url) {
        try {
            var http = new XMLHttpRequest();
            http.open('HEAD', url, false);
            http.send();
            return http.status !== 404;
        } catch (e) {
            return false;
        }
    }

    function getUrlIconByExt(ext, size) {
        if ([null, undefined, ''].includes(size) || [32, 64, 128, 256].includes(size)) size = 128;
        var extension = {
                'doc': 'word',
                'docx': 'word',
                'xls': 'excel',
                'xlsx': 'excel',
                'ppt': 'powerpoint',
                'pptx': 'powerpoint',
                'zip': 'zip',
                'rar': 'zip',
                'pdf': 'pdf',
                'txt': 'txt',
            },
            file = Object.keys(extension).includes(ext) ? extension[ext] : 'file',
            url = '/images/icons/' + size + 'x' + size + '/' + file + '.png';
        if (!urlExists(url)) {
            return '/images/icons/' + size + 'x' + size + '/file.png';
        } else {
            return url;
        }
    }

    createDatetimepicker();

    function init_tieuchi() {
        $('.true').prop('readonly', true);
    }

    init_tieuchi();

// Created by STRd6
// MIT License
// jquery.paste_image_reader.js
    (function ($) {
        var defaults;
        $.event.fix = (function (originalFix) {
            return function (event) {
                event = originalFix.apply(this, arguments);
                if (event.type.indexOf("copy") === 0 || event.type.indexOf("paste") === 0) {
                    event.clipboardData = event.originalEvent.clipboardData;
                }
                return event;
            };
        })($.event.fix);
        defaults = {
            callback: $.noop,
            matchType: /image.*/
        };
        return ($.fn.pasteImageReader = function (options) {
            if (typeof options === "function") {
                options = {
                    callback: options
                };
            }
            options = $.extend({}, defaults, options);
            return this.each(function () {
                var $this, element;
                element = this;
                $this = $(this);
                return $this.bind("paste", function (event) {
                    var clipboardData, found;
                    found = false;
                    clipboardData = event.clipboardData;
                    return Array.prototype.forEach.call(clipboardData.types, function (type, i) {
                        var file, reader;
                        if (found) {
                            return;
                        }
                        if (
                            type.match(options.matchType) ||
                            clipboardData.items[i].type.match(options.matchType)
                        ) {
                            file = clipboardData.items[i].getAsFile();
                            reader = new FileReader();
                            reader.onload = function (evt) {
                                return options.callback.call(element, {
                                    dataURL: evt.target.result,
                                    event: evt,
                                    file: file,
                                    name: file.name
                                });
                            };
                            reader.readAsDataURL(file);
                            return (found = true);
                        }
                    });
                });
            });
        });
    })(jQuery);
    var dataURL, filename;
    $("html").pasteImageReader(function (results) {
        filename = results.filename, dataURL = results.dataURL;
        var img = document.createElement("img");
        img.src = dataURL;
        var $imgActive = $(".img_active");
        if ($imgActive.length !== 0) {
            var last_target = $(".g-list-file");
            /*$imgActive
                .css({
                    backgroundImage: "url(" + dataURL + ")",
                    backgroundRepeat: 'no-repeat',
                });
            $imgActive.find('input').val(dataURL);*/
            if (last_target.length <= 1 || last_target.last().css('background-image') !== "none") {
                var input = '<input class="image-base64" name="image_base64[]" type="hidden" value="' + dataURL + '">';
                var delete_btn = '<span class="delete-file"><i class="fa fa-times"></i></span>';
                // var image_tmp = '<a class="grouped_images_view" rel="group1" href="' + dataURL + '"><img class="hoso-image" src="' + dataURL + '"></a>';
                var image_target = $('<div class="review span4 image_target"></div>').css({
                    backgroundImage: "url(" + dataURL + ")",
                    backgroundRepeat: 'no-repeat',
                });
                image_target.html(delete_btn + input);
                console.log(image_target);
                image_target.insertBefore($('.g-btn-upload'));
                /*$('.g-list-file').append('<div class="span4 image_target" style="background-image:url("' + dataURL + '")">\n' +
                    ' <span class="delete-file"><i class="fa fa-times"></i></span>' +
                    ' <input class="image-base64" name="image_base64[]" type="hidden">\n' +
                    ' </div>');*/
            }
            return $imgActive;
        }
    }).on('click', function (e) {
        $('.img_active').removeClass('img_active');
    });

    $(function () {
        $(document).on("click", '.g-list-file', function () {
            var $this = $(this);
            $(".img_active").removeClass("img_active");
            $this.addClass("img_active");

        }).on('click', '.delete-file', function (e) {
            $(this).parent().remove();
        });
    });
    $('#dexuatchi-nguoi_trien_khai').select2({
        placeholder: 'Người Triển Khai',
        allowClear: true,
    });
    $('#dexuatchi-coso').select2({
        placeholder: 'Cơ sở',
        allowClear: true,
        multiple: true,
    });

})