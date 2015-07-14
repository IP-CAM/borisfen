$(document).ready(function(){
    $('#newsSubscribeForm').submit(function(event){
        subscribeForNews();
        event.preventDefault();
    });
    $(document).on('click', '#newsSubscribeButton', function(event){
        subscribeForNews();
        event.preventDefault();
    });
});
var subscribeForNews = function(){
    var email = $('#newsSubscribeInput').val();
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var text_error = 'Вы ввели неверный Email, либо вы уже подписаны.';
    var text_ok = 'Вы успешно подписались на рассылку новостей';
    if(re.test(email)) {
        $.ajax({
            url: '/index.php?route=module/newssubscription/subscribe',
            dataType: 'json',
            type: 'post',
            data: {
                email: email
            },
            success: function(response){
                if(response) {
                    alert(text_ok);
                } else {
                    alert(text_error);
                }
            }
        });
    } else {
        alert(text_error);
    }
    return false;
}