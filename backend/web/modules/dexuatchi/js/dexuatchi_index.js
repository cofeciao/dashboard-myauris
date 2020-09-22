$(document).ready(function () {
    var customPjax = new myGridView();
    customPjax.init({
        pjaxId: '#custom-pjax',
        urlChangePageSize: urlChangePageSize,
    })


    $('body').on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var id = JSON.parse($(this).attr("data-id"));
        var table = $(this).closest('tr');
        var currentUrl = $(location).attr('href');
        Swal.fire({
            title: data_title,
            text: data_text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    cache: false,
                    data: {
                        "id": id
                    },
                    url: urlDelete,
                    dataType: "json",
                    success: function (data) {
                        if (data.status == 'success') {
                            toastr.success(deleteSuccess, tit);
                            table.slideUp("slow");
                            $.pjax.reload({
                                url: currentUrl,
                                method: 'POST',
                                container: customPjax.options.pjaxId
                            });
                        }
                        if (data.status == 'failure' || data.status == 'exception')
                            toastr.error('Xóa không thành công', 'Thông báo');
                    }
                });
            }
        });
    });

    //print btn
    $('body').on('click', '.print-dexuat', function (e) {
        e.preventDefault();
        let url = $(this).data('href');
        window.open(url, '', 'width=800,height=600,left=0,top=0,toolbar=0,scrollbars=0,status=0');
        /*$.ajax({
            type: "POST",
            url: url,
            success: function (res) {
                console.log(res);
            }
        });*/
    }).on('click', '.print-phieuthu', function (e) {
        e.preventDefault();
        // alert('phieuthu');
    });

    //views
    $('body').on('click', '.approve.btn', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        console.log(url);
        let data = {id: $(this).data('id'), status: $(this).data('status')};
        console.log(data);

        $.post(url, data, function (result) {
            $('#custom-modal').modal('toggle');
            if (result == 1) ;
        });
    });

    var reloadall = function () {

        //Khoan chi select dropdown
        $(document).find('.select_khoanchi').select2({
            placeholder: 'Khoản chi',
            allowClear: true,
            ajax: {
                url: urlListKhoanChi,
                dataType: 'json',
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    return {
                        results: data
                    };
                },
                data: function (params) {
                    // console.log(params);
                    // Query parameters will be ?search=[term]&type=public
                    return {
                        q: params.term,
                        type: 'public'
                    };
                }
            }
        })
        $(document).find('.select_khoanchi').on('change', function (e) {
            var table = $(this).parents('.table-key');
            let id_dxc = table.data('key');
            var data = {dexuat_id: id_dxc, khoanchi_id: $(this).val()};
            $.ajax({
                data: data,
                method: 'GET',
                url: urlUpdateKhoanChi,
                success: function (res) {
                    console.log(res);
                    if (res.code === 200) {
                        toastr.success(resultSuccess, res.message);
                    } else {
                        toastr.error(resultDanger, res.message);

                    }
                }

            })
        });

        //inspectioner nguoi nghiem thu
        function templateSelectInspectionerItem(state) {
            if (!state.id) {
                return state.text;
            }
            var bo_phan;
            let name_bo_phan = state.bo_phan === null ? '' : state.bo_phan;
            if (state.bo_phan === null) {
                bo_phan = '';
            } else {
                bo_phan = ' [' + name_bo_phan + '] ';

            }
            state.text += bo_phan;
            var $state = $(
                '<span> ' + state.text + ' </span>'
            );
            return $state;
        }

        $(document).find('.select_inspectioner').select2({
            placeholder: 'Người nghiệm thu',
            allowClear: true,
            // minimumInputLength: 2,
            ajax: {
                url: urlListInspectioner,
                dataType: 'json',
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data
                    };
                },
                data: function (params) {
                    // console.log(params);
                    // Query parameters will be ?search=[term]&type=public
                    return {
                        q: params.term,
                        type: 'public'
                    };
                }
            },
            // templateResult: templateSelectInspectionerItem,
        })
        $(document).find('.select_inspectioner').on('change', function (e) {
            var table = $(this).parents('.table-key');
            let id_dxc = table.data('key');
            var data = {dexuat_id: id_dxc, inspectioner_id: $(this).val()};
            $.ajax({
                data: data,
                method: 'GET',
                url: urlUpdateInspectioner,
                success: function (res) {
                    console.log(res);
                    if (res.code === 200) {
                        toastr.success(resultSuccess, res.message);
                    } else {
                        toastr.error(resultDanger, res.message);

                    }
                }

            })
        });
        $(document).find('.filter_select select').select2({
            placeholder: 'Empty',
            allowClear: false,
        });
        $(document).find('.bootstrap-slider-money input').on('focus', function (e) {
            e.preventDefault();

            let popup_slider = $(document).find('.popup-slider');
            let parent = popup_slider.parent()
            let current_input_val = $(this).val();
            let old_value = [null, undefined, [], ''].includes(current_input_val) ? [0, 0] : current_input_val;
            if (!Array.isArray(old_value)) {
                old_value = old_value.split(':');
                // console.log(old_value);
                old_value = $.map(old_value, function (val) {
                    return parseInt(val);
                })
            }
            popup_slider.toggle()
            let min = parent.data('min');
            let max = parent.data('max');
            popup_slider.find('.range-slider').slider({
                min: min,
                max: max,
                step: 10000,
                value: old_value,
                focus: true,
                tooltip_position: 'top',
                ticks: [min, max],
                ticks_labels: [min.toLocaleString("vi", {
                    style: 'currency',
                    currency: 'VND'
                }), max.toLocaleString("vi", {style: 'currency', currency: 'VND'})],
                ticks_snap_bounds: 30,
                formatter: function (value) {
                    return value.toLocaleString("vi", {
                        style: 'currency',
                        currency: 'VND'
                    });
                },
                ticks_tooltip: true,
            });
            $(document).on('click', '.success-range', function (e) {
                e.preventDefault();
                let parents = $(document).find('.popup-slider');
                let range_slider = $(document).find('.span2.range-slider');
                var value = range_slider[0].value[0] + ':' + range_slider[0].value[1]
                // console.log(value);
                let money_input_filter = parents.siblings();
                money_input_filter.val(value).trigger('change');
            })
            $(document).on('click', '.cancel-range', function (e) {
                e.preventDefault();
                $(this).parents('.popup-slider').hide();
            })
        })/*
        $(document).on('click', '.bootstrap-slider-money .popup-slider', function (e) {
            e.stopPropagation();
        })*/
        /* $('document').on('click', function (e) {

             let popup = $(this).find('.popup-slider');
             if (e.target == document.getElementById("slider-money")) {
                 console.log(e.target);
                 console.log(popup);
                 popup.hide();
             }

         })
 */
    }
    reloadall();
    $('body').on('change', '.check-toggle', function () {
        var id = $(this).val();
        $('.content-wrapper').myLoading({
            opacity: true,
            size: 'sm'
        });
        var currentUrl = $(location).attr('href');
        $.post(urlLeaderAccept, {id: id}, function (result) {
            if (result == 1) {
                toastr.success(resultSuccess, tit);
                unLoading('.content-wrapper');
            }
            if (result == 0) {
                toastr.error(resultDanger, tit);
                unLoading('.content-wrapper');
            }
            $.pjax.reload({
                url: currentUrl,
                method: 'POST',
                container: customPjax.options.pjaxId
            });
        });
    });
    $('body').on('change', '.check-toggle-unacceptable', function (e) {
        e.preventDefault()
        e.stopPropagation()
        //id of dexuatchi
        var id = $(this).val();
        console.log(id);

        /*$('.content-wrapper').myLoading({
            opacity: true,
            size: 'sm'
        });*/
        var currentUrl = $(location).attr('href');
        $.post(urlLeaderAccept, {id: id, check: 'uncheck'}, function (result) {
            if (result == 1) {
                toastr.success(resultSuccess, tit);
                unLoading('.content-wrapper');
            }
            if (result == 0) {
                toastr.error(resultDanger, tit);
                unLoading('.content-wrapper');
            }
            $.pjax.reload({
                url: currentUrl,
                method: 'POST',
                container: customPjax.options.pjaxId
            });
        });
        // $('.check-toggle').trigger('click')
    })
    $('body').on('click', '.edittable-accept', function (e) {
        e.preventDefault()
        e.stopPropagation()
    })
    var myeditor = new myEditor();
    myeditor.init({
        element: '.edittable-accept', /* id or class of element */
        callbackBeforeSubmit: function () {

            var currentUrl = $(location).attr('href');
            var dataOption = myeditor.editor.find('.myEdit-data > select').val(),
                id = myeditor.editor.closest('tr').attr('data-key'),
                option = myeditor.editor.attr('data-option'),
                //data-optione: Người nghiệm thu ( inspectioner )
                data = {id: id, inspectioner: dataOption, option: option, check: 'check'}
            $.ajax({
                url: urlLeaderAccept,
                type: 'POST',
                dataType: 'json',
                data: data,
            }).done(function (data) {
                if (data === 1) {
                    toastr.success(resultSuccess, tit);
                    unLoading('.content-wrapper');
                    $.pjax.reload({
                        url: currentUrl,
                        method: 'POST',
                        container: customPjax.options.pjaxId
                    });
                } else {
                    toastr.error(resultDanger, tit);
                    unLoading('.content-wrapper');
                }
            })
        }, /* callbackBeforeSubmit */
        callbackAfterSubmit: function () {
        }, /* callbackAfterSubmit */
        callbackBeforeCancel: function () {
        }, /* callbackBeforeCancel */
        callbackAfterCancel: function () {
        }, /* callbackAfterCancel */
        callbackBeforeOpen: function () {
            myeditor.editor.find('.myEdit-data > select').select2({});
        }, /* callbackAfterCancel */
    });
    // TODO:: pjax success load too many time. Find another event.
    jQuery(document).on('pjax:success', function (e) {
        // console.log("#" + e.target.id, $this.options.pjaxId);
        // console.log("#" + e.target.id == $this.options.pjaxId);
        reloadall()
    })
});
