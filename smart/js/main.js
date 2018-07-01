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
    complete: function(){
        loading('hide');
    },
    success: function (data) {
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