$.fn.myGridView = function (options) {
    var mygridview = new myGridView();
    mygridview.init(options);
    return mygridview;
}
var myGridView = function () {
    this.options = {}, this.init = function (options) {
        console.log('abc', options);
        this.options = $.extend(this.defaults, options);

        this.setHeightContent();
        this.setWidthCol();
        this.setEvents();
    }, this.setHeightContent = function () {
        var content = $(this.options.contentClass) || null;

        if (content != null) {
            var minus = content.attr('data-minus') || null,
                data = minus != null ? JSON.parse(minus) : {},
                hContent = $(window).height() - $(this.options.cpClass).find('table thead').outerHeight();

            for (var k = 0; k < Object.keys(data).length; k++) {
                if (k == 0) {
                    if (data[0] != null && !isNaN(data[0])) hContent -= data[0];
                } else {
                    if ($(data[k]) != undefined && $(data[k]).length > 0) {
                        hContent -= $(data[k]).outerHeight();
                    }
                }
            }
            ;

            content.height(hContent);
        }
    }, this.setWidthCol = function () {
        var i = 0;
        $(this.options.cpClass).find("table thead tr").eq(0).children("th").each(function () {
            var t = null != $(this).attr("width") ? $(this).attr("width") : $(this).outerWidth();
            $("colgroup col:nth-child(" + (i + 1) + ")").css("width", t), i++
        });
    }, this.setEvents = function () {
        var $this = this;

        jQuery(window).on('resize', function () {
            $this.setWidthCol();
        }), jQuery(document).on('pjax:send', function () {
            $($this.options.cpClass).find('tbody').myLoading('Loading...');
        }), jQuery(document).on('pjax:success', function (e) {
            // console.log("#" + e.target.id, $this.options.pjaxId);
            // console.log("#" + e.target.id == $this.options.pjaxId);
            if ("#" + e.target.id == $this.options.pjaxId) {
                $this.setHeightContent();
                $this.setWidthCol();
            }
            $(".ui.dropdown").dropdown();
        }), jQuery($this.options.paneScroll[0]).scroll(function () {
            $($this.options.paneScroll[1]).width($($this.options.paneScroll[0]).width() + $($this.options.paneScroll[0]).scrollLeft());
        }), $($this.options.cpClass + " .filters input, " + this.options.cpClass + " .filters select").each(function () {
            "" != $(this).val() ? $(this).closest("td").append('<button class="btn btn-default btn-close-filter" title="Xóa"><i class="material-icons">clear</i></button>') : $(this).closest("td").find(".btn-close-filter").remove()
        }), $("body").on("click", ".btn-close-filter", function () {
            var t = $(this).closest("td").find("input"),
                e = $(this).closest("td").find("select");
            $($this.options.cpClass).find('tbody').myLoading('Loading...');
            t.length && (t.val(""), t.trigger('change'), $(this).closest("td").find(".btn-close-filter").remove()),
            e.length && (e.prop("selectedIndex", 0), e.trigger('change'), $(this).closest("td").find(".btn-close-filter").remove());
        }), $('body').on('change', '.go-to-page', function () {
            var currentUrl = $(location).attr('href'), arrUrl = currentUrl.split('dp-1-page=');
            if (typeof (arrUrl[1]) !== 'undefined' && arrUrl[1] !== null) {
                currentUrl = arrUrl[0].slice(0, -1);
            }
            $.when($.pjax.reload({
                url: currentUrl + '&dp-1-page=' + $(this).val(),
                method: 'POST',
                container: $this.options.pjaxId
            })).done(function () {
                var currentUrl = $(location).attr('href'),
                    arr = currentUrl.split('dp-1-page='),
                    arr = arr[1].split('&');
                $('.go-to-page').val(arr[0]);
            });
        }), $('body').on('change', '#page-size-widget', function () {
            var currentUrl = $(location).attr('href'),
                pageNum = $(this).val();
            if (pageNum == 0) return false;

            $.get($this.options.urlChangePageSize, {'perpage': pageNum}, function () {
                $.pjax.defaults.timeout = false;
                $.pjax.reload({url: currentUrl, method: 'POST', container: $this.options.pjaxId});
            });
        });
    };

    this.defaults = {
        pjaxId: '#campaign-ajax',
        gridViewClass: '.grid-view',
        cpClass: '.cp-widget',
        contentClass: '.grid-content',
        paneScroll: ['.pane-hScroll', '.pane-vScroll'],
        urlChangePageSize: null,
    };
};