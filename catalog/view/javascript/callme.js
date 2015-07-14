var yearOld = {
    errors: false,
    init: function(selector) {
        $(document).on('click', selector, function() {
            yearOld.open();
        });
        $(document).on('click', '#callback-buttonclose', function() {
            yearOld.close();
        });
        /*
         * Add close event for layout click
         * */
        /*$(document).on('click', '#callback-submit', function() {
            yearOld.submit();
        });*/
    },
    open: function() {
        if ($('#callback').attr('data-loaded') == 1) {
            $('#callBack').fadeIn(1);
            //            $('#callBack > .modal-backdrop').fadeIn(1);
            //            $('#callBack > .bootbox').fadeIn(1);
            $('#ref').trigger('click');
        } else {
            $.ajax({
                url: '/index.php?route=information/contact/year',
                type: 'get',
                success: function(html) {
                    $('body').prepend(html);
                    $('#callBack').fadeIn(1);
                    $('#callback-submit').parents('.modal-footer').show();
                    $('#ref').trigger('click');

                }
            });
        }
    },
    close: function() {
        $('#callBack').fadeOut(1);
    },
    submit: function() {
        data = this.collectData();
        this.errors = this.validate(data);
        if (!this.errors) {
            $.ajax({
                url: '/index.php?route=information/contact/year',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(response) {
                    $('#callBack > *').each(function() {
                        $(this).removeClass('error');
                    });
                    if ((response.status != undefined) && (response.text != undefined)) {
                        console.log('1');
                        $('#callback-submit').parent().append('<span id="callMeStatus" style="display: none;">' + response.text + '</span>');
                        $('#callMeStatus').fadeIn(150);
                        setTimeout(function() {
                            $('#callMeStatus').fadeOut(150);
                            yearOld.close();
                        }, 2000, yearOld);
                    } else if (response.errors) {
                        console.log('2');
                        $(response.errors).each(function(key, value) {
                            console.log(key, value);
                            $('#callback-name' + value).addClass('error');
                        });
                    }
                },
                error: function() {
                    alert('Internal server error :( <br> Please contact system administrator.');
                }
            });
        }
    },
    collectData: function() {
        data = {};
        data.name = $('#callback-name').val();
        data.phone = $('#callback-phone').val().replace(/[^\d\.]/g, "");
        data.comment = $('#callback-comment').val();
        return data;
    },
    validate: function(data) {
        this.errors = false;

        if (data.name.length < 3) {
            this.erros = true;
            $('#callback-name').addClass('error');
        } else {
            $('#callback-name').removeClass('error');
        }


        if (data.phone.length < 6) {
            this.erros = true;
            $('#callback-phone').addClass('error');
        } else {
            $('#callback-phone').removeClass('error');
        }

    }
}



var callme = {
    errors: false,
    init: function(selector) {
        $(document).on('click', selector, function() {
            callme.open();
        });
        $(document).on('click', '#callback-buttonclose', function() {
            callme.close();
        });
        /*
         * Add close event for layout click
         * */
        $(document).on('click', '#callback-submit', function() {
            callme.submit();
        });
    },
    open: function() {
        if ($('#callback').attr('data-loaded') == 1) {
            $('#callBack').fadeIn(1);
            //            $('#callBack > .modal-backdrop').fadeIn(1);
            //            $('#callBack > .bootbox').fadeIn(1);
            $('#ref').trigger('click');
        } else {
            $.ajax({
                url: '/index.php?route=information/contact/callme',
                type: 'get',
                success: function(html) {
                    $('body').prepend(html);
                    $('#callBack').fadeIn(1);
                    $('#callback-submit').parents('.modal-footer').show();
                    $('#ref').trigger('click');

                }
            });
        }
    },
    close: function() {
        $('#callBack').fadeOut(1);
    },
    submit: function() {
        data = this.collectData();
        this.errors = this.validate(data);
        if (!this.errors) {
            $.ajax({
                url: '/index.php?route=information/contact/callme',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(response) {
                    $('#callBack > *').each(function() {
                        $(this).removeClass('error');
                    });
                    if ((response.status != undefined) && (response.text != undefined)) {
                        console.log('1');
                        $('#callback-submit').parent().append('<span id="callMeStatus" style="display: none;">' + response.text + '</span>');
                        $('#callMeStatus').fadeIn(150);
                        setTimeout(function() {
                            $('#callMeStatus').fadeOut(150);
                            callme.close();
                        }, 2000, callme);
                    } else if (response.errors) {
                        console.log('2');
                        $(response.errors).each(function(key, value) {
                            console.log(key, value);
                            $('#callback-name' + value).addClass('error');
                        });
                    }
                },
                error: function() {
                    alert('Internal server error :( <br> Please contact system administrator.');
                }
            });
        }
    },
    collectData: function() {
        data = {};
        data.name = $('#callback-name').val();
        data.phone = $('#callback-phone').val().replace(/[^\d\.]/g, "");
        data.comment = $('#callback-comment').val();
        return data;
    },
    validate: function(data) {
        this.errors = false;

        if (data.name.length < 3) {
            this.erros = true;
            $('#callback-name').addClass('error');
        } else {
            $('#callback-name').removeClass('error');
        }


        if (data.phone.length < 6) {
            this.erros = true;
            $('#callback-phone').addClass('error');
        } else {
            $('#callback-phone').removeClass('error');
        }

    }
}
