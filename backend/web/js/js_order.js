function addLyDoChietKhau() {
    $('.chiet-khau-order').each(function () {
        var el = $(this);
        if (!el.hasClass('handled')) {
            var content = el.attr('data-content'),
                td = el.closest('td'),
                id_ipt = el.attr('id').replace('chiet_khau_order', 'ly_do_chiet_khau') || null,
                name_ipt = el.attr('name').replace('chiet_khau_order', 'ly_do_chiet_khau') || null,
                name_content = el.closest('.form-group').attr('class').replace('chiet_khau_order', 'ly_do_chiet_khau').replace('has-success', '').replace('has-error', '') || null;
            if (name_ipt !== null) {
                var div = $('<div><textarea type="text" class="form-control" rows="3" tabindex="1" placeholder="Lý do chiết khấu" data-content=""></textarea><div class="ly-do-tools"><button type="button" class="btn btn-success btn-submit-ly-do"><span class="fa fa-check"></span></button></div></div>'),
                    btn_handle = $('<button type="button" class="btn btn-primary btn-handle-ly-do"><span class="fa fa-edit"></span><span class="fa fa-times"></span><span class="fa fa-plus"></span></button>');
                if (![null, undefined, ''].includes(content)) btn_handle.addClass('has-lydo');
                div.addClass(name_content + ' ly-do-chiet-khau-content').children('textarea').addClass('form-control ly-do-chiet-khau').attr({
                    name: name_ipt,
                    id: id_ipt
                }).val(content);
                td.addClass('d-flex td-list-cell position-relative flex-wrap').append(btn_handle);
                td.append(div);
                el.addClass('handled');
            }
        }
    });
}

function checkChietKhauOrder(el) {
    if ([null, undefined].includes(el) || el.length <= 0) return false;
    var chiet_khau_theo = el.find('.chiet-khau-theo-order').val() || type_curency,
        val = (el.find('.chiet-khau-order').val() || "").replace(/\./g, '') || 0;
    val = parseInt(val);
    if (![type_curency, type_percent].includes(chiet_khau_theo)) chiet_khau_theo = type_curency;
    if (val < 0) el.find('.chiet-khau-order').val(0);
    if (chiet_khau_theo == type_percent && val > 100) el.find('.chiet-khau-order').val(100);
    handleChietKhau();
}

function handleChietKhau() {
    if ([null, undefined].includes(type_curency) || [null, undefined].includes(type_percent)) return false;
    var tong = 0,
        chietkhau = 0,
        tamtinh,
        khuyenmai = 0,
        total;
    $('.order-content.order-list .multiple-input-list tbody').children('tr').each(function () {
        var thanhtien = ($(this).find('.thanh-tien').val() || "").replace(/\./g, '') || 0,
            chiet_khau_order = ($(this).find('.chiet-khau-order').val() || "").replace(/\./g, '') || 0,
            chiet_khau_theo_order = $(this).find('.chiet-khau-theo-order').val().toString() || type_curency;
        thanhtien = parseInt(thanhtien);
        chiet_khau_order = parseInt(chiet_khau_order);
        if (![type_curency, type_percent].includes(chiet_khau_theo_order)) chiet_khau_theo_order = type_curency;
        if (thanhtien !== null) {
            tong += thanhtien;
            if (chiet_khau_order > 0) {
                if (chiet_khau_theo_order == type_curency) chietkhau += chiet_khau_order;
                else chietkhau += parseInt(thanhtien * chiet_khau_order / 100);
            }
        }
    });
    tamtinh = tong - chietkhau;
    if (tamtinh < 0) tamtinh = 0;
    $('#tong').html(tong == 0 ? '-' : addCommas(tong));
    $('#chiet-khau').html(chietkhau == 0 ? '-' : addCommas(chietkhau));
    $('#tam-tinh').html(tamtinh == 0 ? '-' : addCommas(tamtinh));
    $('.khuyen-mai-content').each(function () {
        var km_price = $(this).find('.khuyenmai_price').val().replace(/\./g, '') || 0,
            km_type = $(this).find('.khuyenmai_type').val().toString() || type_curency,
            km_trigia;
        km_price = parseInt(km_price);
        if (![type_curency, type_percent].includes(km_type)) km_type = type_curency;
        if (km_price > 0) {
            if (km_type == type_curency) km_trigia = km_price;
            else km_trigia = parseInt(tamtinh * km_price / 100);
            $(this).find('.khuyen-mai-info').html('(Trị giá: <span>' + addCommas(km_trigia) + '</span>)');
            khuyenmai += km_trigia;
        } else {
            $(this).find('.khuyen-mai-info').html('');
        }
    });

    total = tamtinh - khuyenmai;
    /*coupon*/
    let couponPriceInput = parseInt($('.coupon-price-input ').val())
    if (couponPriceInput != 0 && !isNaN(couponPriceInput)) {
        total -= couponPriceInput;
    }
    /*end coupon*/
    if (total < 0) total = 0;
    $('#phongkhamdonhang-chiet_khau').val(khuyenmai + chietkhau);
    $('#thanh-tien').html(total == 0 ? '-' : addCommas(total));
}

function getPriceKhuyenMai(el, id) {
    return new Promise(function (resolve) {
        if ([null, undefined].includes(urlGetPriceKhuyenMai) || [null, undefined].includes(type_curency)) resolve();
        if ([null, undefined].includes(id)) {
            el.closest('.khuyen-mai-content').find('.khuyenmai_price').val(0);
            el.closest('.khuyen-mai-content').find('.khuyenmai_type').val(type_curency);
            resolve();
        } else {
            $.post(urlGetPriceKhuyenMai, {id: id}, function (res) {
                if (res.code === 200) {
                    el.closest('.khuyen-mai-content').find('.khuyenmai_price').val(res.price);
                    el.closest('.khuyen-mai-content').find('.khuyenmai_type').val(res.type);
                    resolve();
                } else {
                    toastr.error(res.msg, 'Thông báo');
                    resolve();
                }
            }, 'json').fail(function (f) {
                console.log('Có lỗi xảy ra khi lấy dữ liệu khuyến mãi', f);
                toastr.error('Có lỗi xảy ra khi lấy dữ liệu khuyến mãi', 'Thông báo');
                resolve();
            });
        }
    });
}

$('body').on('keyup', '.on-keyup', function () {
    var order_discount = $(this).val().replace(/[^0-9]/gi, '');
    order_discount = order_discount.replace(/\./g, '');
    if (order_discount.trim() == '')
        order_discount = 0;
    $(this).val(addCommas(parseInt(order_discount)));
    handleChietKhau();
})
    .on('change paste keyup', '.chiet-khau-order', function () {
        checkChietKhauOrder($(this).closest('tr'));
    })
    .on('change', '.chiet-khau-theo-order', function () {
        checkChietKhauOrder($(this).closest('tr'));
    })
    .on('change', '.khuyen-mai', function () {
        var val = $(this).val() || null;
        getPriceKhuyenMai($(this), val).then(function () {
            handleChietKhau();
        });
    })
    .on('click', '.btn-handle-ly-do', function () {
        var td = $(this).closest('.td-list-cell');
        if (!td.hasClass('open')) {
            $('.td-list-cell').not(td).each(function () {
                if ($(this).hasClass('open')) {
                    var lydo = td.find('.chiet-khau-order').attr('data-content');
                    $(this).removeClass('open').find('.ly-do-chiet-khau').val(lydo);
                }
            });
            td.addClass('open').find('.ly-do-chiet-khau').focus();
        } else {
            var lydo = td.find('.chiet-khau-order').attr('data-content');
            td.removeClass('open').find('.ly-do-chiet-khau').val(lydo);
        }
    })
    .on('click', '.btn-submit-ly-do', function () {
        var el = $(this),
            td = el.closest('.td-list-cell'),
            lydo = td.find('.ly-do-chiet-khau').val();
        el.closest('.td-list-cell').removeClass('open').find('.chiet-khau-order').attr('data-content', lydo);
        if ([null, undefined, ''].includes(lydo)) {
            td.find('.btn-handle-ly-do').removeClass('has-lydo');
        } else {
            td.find('.btn-handle-ly-do').addClass('has-lydo');
        }
    })
    .on('click', '.btn-checkcode', function (e) {
        e.preventDefault();
        let coupon_code_input = $(this).parents('.coupon-row').find('.coupon-code');
        let error_coupon_code = coupon_code_input.next();
        let val_coupon_code = coupon_code_input.val();
        if (val_coupon_code == '') {
            error_coupon_code.html('Mã coupon không được để trống');
        } else {
            error_coupon_code.html('');
        }
        console.log(val_coupon_code);
        let url_api = $(this).attr('href');
        console.log(url_api)

        let data = {id: val_coupon_code};
        $.ajax({
            data: data,
            url: url_api,
            type: 'GET',
            dataType: "json",
            contentType: "application/json",
            success: function (res) {
                console.log(res.data.id)
                if (res.data.id != undefined) {
                    $('.info-coupon .coupon-name .text').html(res.data.couponHasOne.name)
                    $('.info-coupon .coupon-price .text').html(addCommas(res.data.couponHasOne.giaban))
                    $('.info-coupon .coupon-price-input').val(res.data.couponHasOne.giaban)
                    let status = 'Chưa sửa dụng';
                    if (res.data.status == 0) {
                        status = 'Đã sử dụng.'
                    }
                    $('.info-coupon .coupon-status .text').html(status);
                    $('.info-customer .customer-name .text').html(res.data.customerHasOne.name)
                    $('.info-customer .customer-phone .text').html(res.data.customerHasOne.phone)
                    $('.info-customer .customer-email .text').html(res.data.customerHasOne.email)
                    let status_order = 'Chưa thanh toán';
                    if ([2, 3, 4].includes(res.data.orderHasOne.status)) {
                        status_order = 'Đã thanh toán';
                    }
                    $('.info-coupon .coupon-payment-status .text').html(status_order)
                }
                handleChietKhau()
                $('.info-coupon-general').show();
            },
            error: function (res) {
                console.log(res)
            }
        })

    })
    .on('click', '.btn-clear-coupon', function (e) {
        e.preventDefault();
        clear_coupon_input();
        handleChietKhau()
    })


function clear_coupon_input() {
    $('.info-coupon .coupon-name .text').html('');
    $('.info-coupon .coupon-price .text').html('');
    $('.info-coupon .coupon-price-input').val(0);
    $('#form-don-hang .coupon-code').val('');
    $('.info-coupon .coupon-status .text').html('');
    $('.info-customer .customer-name .text').html('')
    $('.info-customer .customer-phone .text').html('')
    $('.info-customer .customer-email .text').html('')
    $('.info-coupon .coupon-payment-status .text').html('')
}
