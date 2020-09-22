var myEditor = function () {
    this.options = {};
    this.cancelSubmit = false;
    this.cancelCancel = false;
    this.editor = null;
    this.init = function (variables) {
        this.setOptions(variables);
        this.setEvent();
    }
    this.checkElement = function () {
        var element = null;
        if (this.options.element !== null) element = $('body').find(this.options.element) || null;
        if (element === null) {
            console.log('myEditor: element not found!');
            return false;
        }
        return true;
    }
    this.setOptions = function (variables) {
        var options = Object.assign({
            element: null,
            callbackBeforeOpen: function () {
            },
            callbackBeforeSubmit: function () {
            },
            callbackAfterSubmit: function () {
            },
            callbackBeforeCancel: function () {
            },
            callbackAfterCancel: function () {
            },
        }, variables);
        if (typeof options.callbackBeforeOpen !== "function") options.callbackBeforeOpen = function () {
        };
        if (typeof options.callbackBeforeSubmit !== "function") options.callbackBeforeSubmit = function () {
        };
        if (typeof options.callbackAfterSubmit !== "function") options.callbackAfterSubmit = function () {
        };
        if (typeof options.callbackBeforeCancel !== "function") options.callbackBeforeCancel = function () {
        };
        if (typeof options.callbackAfterCancel !== "function") options.callbackAfterCancel = function () {
        };
        this.options = options;
    }
    this.setEvent = function () {
        var myeditor = this;
        myeditor.checkElement();
        $('body').on('click', myeditor.options.element + ':not(.editing)', function () {
            $('body').find(myeditor.options.element + '.editing').removeClass('myEdit-element editing').find('.myEdit-content').remove();
            myeditor.editor = $(this);
            var element = $(this),
                value = element.html(),
                options = element.attr('myedit-options') ? JSON.parse(element.attr('myedit-options')) : {},
                type = options.type && ['string', 'number', 'text', 'select'].includes(options.type) ? options.type : 'text',
                editor;
            element.addClass('myEdit-element editing').append('<div class="myEdit-content"><div class="myEdit-data"></div><div class="myEdit-tools"><span class="myEdit-submit"></span><span class="myEdit-cancel"></span></div></div>');
            switch (type) {
                case 'string':
                    element.find('.myEdit-data').append('<input type="text" value="' + value + '"/>');
                    editor = element.find('.myEdit-data > input');
                    break;
                case 'number':
                    element.find('.myEdit-data').append('<input type="number" value="' + value + '"' +
                        (![null, undefined].includes(options.min) ? ' min="' + options.min + '"' : '') +
                        (![null, undefined].includes(options.max) ? ' max="' + options.max + '"' : '') +
                        '/>');
                    editor = element.find('.myEdit-data > input');
                    break;
                case 'select':
                    var dataSelect = options.dataSelect || {},
                        dataChoose = options.dataChoose || null;
                    element.find('.myEdit-data').append('<select></select>');
                    editor = element.find('.myEdit-data > select');
                    Object.keys(dataSelect).map(function (key) {
                        editor.append('<option value="' + key + '" ' + (key == dataChoose ? 'selected' : '') + '>' + dataSelect[key] + '</option>');
                    });
                    break;
                case 'text':
                default:
                    element.find('.myEdit-data').append('<textarea>' + value.replace(/<br>/g, '\n') + '</textarea>');
                    editor = element.find('.myEdit-data > textarea');
                    break;
            }
            try {
                myeditor.options.callbackBeforeOpen();
            } catch (e) {
                console.log('Event BeforeOpen got error, ', e);
            }
            editor.bind('keyup', function (e) {
                switch (e.keyCode) {
                    case 13:
                        if (!editor.is('textarea')) element.find('.myEdit-submit').trigger('click');
                        break;
                    case 27:
                        element.find('.myEdit-cancel').trigger('click');
                        break;
                    default:
                        return true;
                }
            });
        });
        $('body').on('click', '.myEdit-submit', function () {
            var element = $(this).closest(myeditor.options.element);
            myeditor.editor = element;
            $.when(myeditor.options.callbackBeforeSubmit()).done(function () {
                if (myeditor.cancelSubmit === false) {
                    $.when(myeditor.submit(element)).then(function () {
                        try {
                            myeditor.options.callbackAfterSubmit();
                        } catch (e) {
                            console.log('Event AfterSubmit got error', e);
                        }
                    });
                } else myeditor.cancelSubmit = false;
            });
        });
        $('body').on('click', '.myEdit-cancel', function () {
            var element = $(this).closest(myeditor.options.element);
            $.when(myeditor.options.callbackBeforeCancel()).done(function () {
                if (myeditor.cancelCancel === false) {
                    myeditor.cancel(element);
                } else myeditor.cancelCancel = false;
            });
        });
    }
    this.submit = function (element) {
        var options = element.attr('myedit-options') ? JSON.parse(element.attr('myedit-options')) : {},
            type = options.type && ['string', 'number', 'text', 'select'].indexOf(options.type) != -1 ? options.type : 'text';
        switch (type) {
            case 'string':
                var value = element.find('.myEdit-data > input').val();
                element.html(value);
                break;
            case 'select':
                var dataSelect = options.dataSelect || {},
                    value = element.find('.myEdit-data > select').find('option:selected').text().trim(),
                    dataChoose = element.find('.myEdit-data > select').find('option:selected').attr('value').trim();
                if (options.dataChoose) options.dataChoose = dataChoose;
                element.attr('myedit-options', JSON.stringify(options)).html(value);
                break;
            case 'text':
            default:
                var value = element.find('.myEdit-data > textarea').val();
                element.html(value.replace(/\n/g, '<br>'));
                break;
        }
        element.removeClass('myEdit-element editing');
    }
    this.cancel = function (element) {
        element.removeClass('myEdit-element editing').find('.myEdit-content').remove();
    }
}