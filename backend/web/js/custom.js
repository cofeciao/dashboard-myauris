(function (window, document, $) {
    'use strict';

    // Basic Select2 select
    $(".select2").each(function () {
        var placeholder = $(this).attr('placeholder') || null,
            options = {
                allowClear: true
            };
        if (placeholder != null && placeholder.trim() != '') options.placeholder = placeholder;
        $(this).select2(options);
    });

    /*  Toggle Starts   */
    // $('.switch:checkbox').checkboxpicker();
    // $('#datetimepicker').datetimepicker();

    // $('#switch12').checkboxpicker({
    //     html: true,
    //     offLabel: '<span class="icon-remove">',
    //     onLabel: '<span class="icon-ok">'
    // });

    /*  Toggle Ends   */
    setTimeout(function () {
        $('.alert-dismissible').slideUp();
    }, 5000);
    $(window).ready(function () {
        $('body').myUnloading();
    });

    $('body').on('change', '.load-data-on-change', function () {
        var el = $(this),
            self = el.attr('load-data-self') || null;
        if (self !== null && $(self).length > 0) {
            el = $(self);
        }
        var url_load_data = el.attr('load-data-url') || null,
            element_load_data = el.attr('load-data-element') || null,
            replace = el.attr('load-data-replace') || true,
            self_key = el.attr('load-data-key') || null,
            data_add = el.attr('load-data-data') || {},
            callback = el.attr('load-data-callback') || null,
            method_load = el.attr('load-data-method') || 'POST';
        if (url_load_data === null) {
            console.log('Url load data not found!');
            return false;
        }
        if ($(element_load_data).length <= 0) {
            console.log('Element load data not found!');
            return false;
        }
        if (!$(element_load_data).is('select')) {
            console.log('Element load data must be tag <select>');
            return false;
        }
        if (self_key === null) {
            console.log('Key not defined!');
            return false;
        }
        var data = {};
        data[self_key] = el.val();
        data = Object.assign(data_add, data);
        if(replace === true) {
            $(element_load_data).find('option[value!=""]').remove();
        }
        $.ajax({
            type: method_load,
            url: url_load_data,
            dataType: 'json',
            data: data
        }).done(function (res) {
            if (res.code === 200) {
                if (!["string", "object"].includes(typeof res.data)) {
                    console.log('Invalid data format: "string" or "object"!');
                    return false;
                }
                if (replace === true) {
                    if (typeof res.data === 'string') {
                        $(element_load_data).append(res.data);
                    } else if (typeof res.data === 'object') {
                        Object.keys(res.data).forEach(function (k) {
                            $(element_load_data).append('<option value="' + k + '">' + res.data[k] + '</option>');
                        });
                    }
                }
                if (typeof window[callback] === 'function') {
                    window[callback](res.data);
                } else if (typeof callback === 'string') {
                    try {
                        eval(callback);
                    } catch (e) {
                        console.log('Error callback!');
                    }
                }
            } else {
                console.log('Load data not success with code ' + res.code, res);
            }
        }).fail(function (f) {
            console.log('Load data fail');
        });
    });

})(window, document, jQuery);