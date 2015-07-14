$(document).on('click', 'a.ajaxPage', function(event){
    
    $('body').addClass('sdf');
    var url = $(this).attr('href');
    var childs = $(this).attr('childs');
    if(childs) {
        childs = childs.split(',');
    }
    var data = {inPopup: true, childs: childs};
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        success: function(response){
            $(response).find('.breadcrumb').hide();
            alert(response);
        },
        error: function(r1, r2){
            alert('Internal server error. Please contact system administrator.');
        }
    });
    event.preventDefault();
    return false;
});