window.onbeforeunload = function () {
    loading('show');
};
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
    duration: 115500,
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
        var name = $(elem).find('input[name="name"]').val();
        var value = $(elem).find('input[name="value"]').val();
        if (names.indexOf(name) === -1){
            data.value[name] = value;
            names.push(name);
        }
    });
    tObj.data.data = [data];
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
function addMetaBlock(obj)
{
    var fields = '<div class="block-meta-item">' +
            '<div class="row">' +
                '<div class="col-md-5">' +
                    '<div class="cl-form-group">' +
                        '<input type="text" placeholder="Пример: description" class="cl-input" name="name">' +
                    '</div>' +
                '</div>' +
                '<div class="col-md-5">' +
                    '<div class="cl-form-group">' +
                        '<input type="text" placeholder="Пример: smart-описание" class="cl-input" name="value">' +
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
function tagsPreview(type){
    var modal = $('#modal-preview-tags');
    var form = type === 'meta-tags' ? $('#meta-tag-form') : $('#og-tag-form');
    var data = '';
    form.find('.block-meta-item').each(function(idx, obj){
       var elem = $(obj);
       data += type === 'meta-tags'
           ? '<meta name="'+ elem.find('input[name="name"]').val() +'" content="'+ elem.find('input[name="value"]').val() +'">\n'
           : '<meta property="'+ elem.find('input[name="name"]').val() +'" content="'+ elem.find('input[name="value"]').val() +'">\n'
    });

    modal.find('pre code').text(data);
    modal.modal('show');
}