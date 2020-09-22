$('body').on('click', '.timeline-year a', function(){
    var year = $(this).closest('.timeline-year').attr('data-year') || null;
    if(year != null){
        $('.timeline-'+ year).slideToggle();
    }
});
