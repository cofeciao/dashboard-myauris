$(function () {

    formComment = $('#form-comment');
    if($(".comment-list").length > 0) $(".comment-list").scrollTop($(".comment-list")[0].scrollHeight);
    formComment.unbind('beforeSubmit').bind('beforeSubmit', function(e){
        e.preventDefault();
        var form = $(this),
            url = form.attr('action'),
            form_data = new FormData(form[0]);
        form.myLoading({
            opacity: true
        });
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: form_data,
            contentType: false,
            processData: false,
            cache: false
        }).done(res => {
            if(res.code == 200){
                toastr.success(res.msg);
                $.when($('#custom-modal').find('.modal-content').load(urlReloadPjax)).done(function(){
                    if($(".comment-list").length > 0) $(".comment-list").scrollTop($(".comment-list")[0].scrollHeight);
                });
            } else {
                toastr.error(res.msg);
            }
            form.myUnloading();
        }).fail(f => {
            toastr.error('Có lỗi khi bình luận');
            form.myUnloading();
        });
        return false;
    });

});