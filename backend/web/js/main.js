var socket = null;
if (typeof io === 'function') {
    socket = io.connect('https://socket.myauris.vn', {reconnection: true, secure: true});
}
if(typeof fireworks == 'undefined'){
    /* ADD LIB FOR FIREWORK */
    document.write('<link rel="stylesheet" type="text/css" href="https://cdn.myauris.vn/plugins/fireworks/fireworks.css">');
    document.write('<script type="text/javascript" src="https://cdn.myauris.vn/plugins/fireworks/jquery.fireworks.js"></'+'script>');
}
const statusCustom = [
    {Id: 0, Name: ""},
    {Id: 1, Name: "Hoạt động"},
    {Id: 2, Name: "Không hoạt động"},
];

function inHere(div, pxnumber) {
    $('html, body').animate({scrollTop: $(div).offset().top - pxnumber}, 1000);
}

function loading(thu) {
    $(thu).myLoading();
    /*$(thu).block({
        message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Loading...</div>',
        overlayCSS: {
            backgroundColor: '#FFF',
            opacity: 1,
            cursor: 'wait',
            // baseZ: 99,
        },
        css: {
            border: 0,
            padding: 0,
            // baseZ: 99,
            backgroundColor: 'transparent'
        }
    });*/
}

function unLoading(thu) {
    /*$(thu).unblock();*/
    $(thu).myUnloading();
}


function addCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return parts.join(".");
}

function dep365AlertSuccessData(data) {
    var title = data.title || '',
        content = data.content || null,
        url = data.url || null,
        options = {};
    if (url !== null && typeof url === 'string' && url.length > 0) options['onclick'] = function () {
        window.location.href = url;
    };
    toastr.success(content, title, options);
}

function burnFirework(data) {
    if (typeof myFirework === 'function') {
        var myfirework = new myFirework();

        if ($('body').find('#firework') !== undefined && $('body').find('#firework').length > 0) {
            myfirework.destroy();
        }
        var mes = data.message || '',
            message = '<div class="message">' + mes + '</div>';
        $('body').append('<div id="firework" style="position:fixed;bottom:0;right:0;width:50%;height:40%;z-index:-1;opacity:0;">' + message + '</div>');
        $.when(myfirework.init('#firework')).done(function () {
            myfirework.el.animate({
                'z-index': 1,
                'opacity': 1
            }, 500);
            setTimeout(function () {
                jQuery.when(myfirework.el.animate({
                    'z-index': -1,
                    'opacity': 0
                }, 1000)).done(function () {
                    myfirework.destroy();
                });
            }, 5000);
        });
    }
}

if (typeof socket === 'object') {
    socket.on('dep365-alert', function (res) {
        var data = res.data || null;
        if (typeof data !== 'object' || data.length <= 0) return false;
        var act = data.act || null;
        switch (act) {
            case 'customer-online-booking':
                dep365AlertSuccessData(data);
                break;
            case 'customer-online-create-new':
                burnFirework(data);
                break;
            default:
                return false;
        }
    });
}
