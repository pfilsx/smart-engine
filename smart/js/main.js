window.onbeforeunload = function () {
    loading('show');
};
$('.cl-tab-nav a').on('click', function(e){
    e.preventDefault();
    $(this).tab('show');
    window.location.hash = $(this).attr('href');
});
var hash = window.location.hash;
$('.cl-tab-nav a[href="' + hash + '"]').tab('show');


function loading(action)
{
    if(action === 'show'){
        $('.lightbox').show();
    } else {
        $('.lightbox').hide();
    }
}
var notify = new AlertNotify({
    enable_fa: true,
    vertical_align: 'top',
    horizontal_align: 'center',
    animateIn: 'fade',
    animateOut: 'fade',
    timeIn: 500,
    timeOut: 1500,
    duration: 5500,
    classPrefix: 'alert-notify'
});
var ajaxTpl = {
    dataType: 'json',
    data: {},
    type: 'POST',
    beforeSend: function(){
        loading('show');
    },
    success: function (data) {
        loading('hide');
        notify.showNotification({
            text: data.message,
            type: data.success ? 'success' : 'error'
        });
    },
    error: function (xhr, err, t) {
        console.log([xhr, err, t]);
        loading('hide');
        notify.showNotification({
            text: xhr.responseText,
            type: 'error'
        });
    }
};
function saveMainTab(){
    var form = $('#main-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'main-tab';
    tObj.data.data = form.serializeArray();
    $.ajax(tObj);
}
function saveMetaTagTab(){
    var form = $('#meta-tag-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'meta-tab';
    var data = {'name': 'meta-tags', 'value': {}};
    var names = [];
    form.find('.block-meta-item').each(function(idx, elem){
        var name = $(elem).find('input[name="name"]').val().trim().toLowerCase();
        var value = $(elem).find('input[name="value"]').val().trim();
        if (name.length > 0 && value.length > 0){
            if (names.indexOf(name) === -1){
                data.value[name] = value;
                names.push(name);
            }
        }
    });
    tObj.data.data = [data];
    $.ajax(tObj);
}
function saveOGTagTab(){
    var form = $('#og-tag-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'og-tab';
    var data = {'name': 'og-tags', 'value': {}};
    var names = [];
    form.find('.block-meta-item').each(function(idx, elem){
        var name = $(elem).find('input[name="name"]').val().trim().toLowerCase();
        var value = $(elem).find('input[name="value"]').val().trim();
        if (name.length > 0 && value.length > 0){
            if (names.indexOf(name) === -1){
                data.value[name] = value;
                names.push(name);
            }
        }
    });
    tObj.data.data = [data];
    $.ajax(tObj);
}
function saveMetricsTab(){
    var form = $('#metrics-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'metrics-tab';
    tObj.data.data = form.serializeArray();
    $.ajax(tObj);
}
function saveRobotsTab(){
    var form = $('#robots-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'robots-tab';
    tObj.data.data = form.find('textarea[name="robots"]').val();
    $.ajax(tObj);
}
function saveCodeTab(){
    var form = $('#code-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'code-tab';
    tObj.data.data = form.serializeArray();
    $.ajax(tObj);
}
function saveTemplateTab(){
    var form = $('#template-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'template-tab';
    tObj.data.path = $('.active .cl-template-item').attr('href').replace('#', '');
    tObj.data.content = $('.cl-template').val();
    $.ajax(tObj);
}
/**
 * Remove meta block
 * @param obj
 */
function removeMetaBlock(obj)
{
    if($(obj).closest('.block-meta').find('.block-meta-item').length > 1) {
        $(obj).closest('.block-meta-item').remove();
    } else {
        $(obj).closest('.block-meta-item').find('input').val('');
    }
}
/**
 * Add new meta-block
 */
function addMetaBlock(obj, type) {
    var fields = '<div class="block-meta-item">' +
            '<div class="row">' +
                '<div class="col-md-5">' +
                    '<div class="cl-form-group">' +
                        '<input type="text" placeholder="Пример: '+ (type === 'og-tags' ? 'og:title' : 'description') +'" class="cl-input" name="name">' +
                    '</div>' +
                '</div>' +
                '<div class="col-md-5">' +
                    '<div class="cl-form-group">' +
                        '<input type="text" placeholder="Пример: '+ (type === 'og-tags' ? 'smart' : 'smart-описание') +'" class="cl-input" name="value">' +
                    '</div>' +
                '</div>' +
                '<div class="col-md-2 text-center">' +
                    '<a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>' +
                '</div>' +
            '</div>' +
        '</div>';
    $(obj).closest('form').find('.block-meta').append(fields);
}
function cancel(type){
    var form = type === 'meta-tags' ? $('#meta-tag-form') : $('#og-tag-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'get-param';
    tObj.data.param = type;
    tObj.success = function(data){
        if (data.success){
            var meta_block = form.find('.block-meta');
            meta_block.html('');
            for (var prop in data.data){
                if (data.data.hasOwnProperty(prop)){
                    meta_block.append('<div class="block-meta-item">' +
                        '<div class="row">' +
                            '<div class="col-md-5">' +
                                '<div class="cl-form-group">' +
                                    '<input type="text" placeholder="Пример: description" class="cl-input" name="name" value="'+ prop +'">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-5">' +
                                '<div class="cl-form-group">' +
                                    '<input type="text" placeholder="Пример: smart-описание" class="cl-input" name="value" value="'+ data.data[prop] +'">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-2 text-center">' +
                                '<a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>' +
                            '</div>' +
                        '</div>' +
                    '</div>');
                }
            }
            loading('hide');
        } else {
            loading('hide');
            notify.showNotification({
                text: data.message,
                type: 'error'
            });
        }
    };
    $.ajax(tObj);
}
function tagsPreview(){
    var modal = $('#modal-preview-tags');
    var meta_form = $('#meta-tag-form');
    var og_form = $('#og-tag-form');
    var charset = $('#main-form').find('input[name="charset"]').val().trim();
    var data;
    if (charset.length > 0){
        data = '<meta charset="'+ charset +'">\n';
    }
    meta_form.find('.block-meta-item').each(function(idx, obj){
       data += _generateMetaElement($(obj), 'meta-tags');
    });
    data += '\n';
    og_form.find('.block-meta-item').each(function(idx, obj){
        data += _generateMetaElement($(obj), 'og-tags');
    });
    modal.find('pre code').text(data);
    modal.modal('show');
}
function _generateMetaElement(elem, type){
    var name = elem.find('input[name="name"]').val().trim();
    var value = elem.find('input[name="value"]').val().trim();
    if (name.length > 0 && value.length > 0){
        if (type === 'meta-tags'){
            return '<meta name="'+ name +'" content="'+ value +'">\n';
        }
        return '<meta property="'+ name +'" content="'+ value +'">\n';
    }
    return '';
}

$(document).on('click', '.cl-template-item', function(e){
    e.preventDefault();
    var elem = $(this);
    var form = $('#template-form');
    var url = form.attr('action');
    var tObj = Object.create(ajaxTpl);
    tObj.url = url;
    tObj.data.action = 'get-css-template';
    tObj.data.path = elem.attr('href').replace('#', '');
    tObj.success = function(data){
        if (!data.success){
            loading('hide');
            notify.showNotification({
                text: data.message,
                type: 'error'
            });
        } else {
            loading('hide');
            $('.cl-template-item').not(elem).closest('li').removeClass('active');
            elem.closest('li').addClass('active');
            $('.cl-template').val(data.content);
        }
    };
    $.ajax(tObj);
});