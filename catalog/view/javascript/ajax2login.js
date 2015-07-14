$(document).ready(function(){
	Ajax2login.init('#ajax2login');
});
var Ajax2login = {
	selector: '',
	form: {},
	init: function(selector){
		this.selector = selector;
		
		$(document).on('submit', selector, function(e){
			Ajax2login.form = $(Ajax2login.selector).serializeObject();
			Ajax2login.login();
			e.preventDefault();
		});
	},
	validate: function(){
		var errors = 0;
		for(key in this.form) {
			if(this.form[key].length < 2) {
				errors++;
				$(Ajax2login.selector + ' input[name="' + key + '"]').addClass('error');
			} else {
				$(Ajax2login.selector + ' input[name="' + key + '"]').removeClass('error');
			}
		}
		return (errors < 1);
	},
	login: function(login, password){
		if(this.validate()) {
			$.ajax({
				url: getLink('account/login/ajax2login'),
				data: Ajax2login.form,
				dataType: 'json',
				type: 'post',
				success: function(response){
					if(response.ok) {
						if(window.location.pathname == '/logout'){
							window.location.href = '/';
						} else {
							window.location.reload();
						}
					} else if(response.errors) {
						for(key in response.errors) {
							Ajax2login.error(response.errors[key]);
						}
					}
				}
			});
		} else {
			this.error('Неправильно заполнены поля E-Mail и/или пароль!');
		}
	},
	forgot: function(login){
		$.ajax({
			url: getLink('account/login/ajax2forgot'),
			data: {email: login},
			dataType: 'json',
			type: 'post',
			success: function(response){
				if(response.ok && response.redirect) {
					window.location.href = response.redirect;
				} else if(response.errors) {
					for(key in response.errors) {
						Ajax2login.error(response.errors[key]);
					}
				}
			}
		});
	},
	error: function(error){
		var opts = {
    		text: error,
	        styling: 'bootstrap3',
	        addclass: 'oc_noty',
        	type: 'error',
        	icon: 'picon picon-32 picon-fill-color',
        	opacity: .8,
        	nonblock: {
        		nonblock: true
        	}
	    };
	    new PNotify(opts);
	},
	toForgot: function(){
		
	},
	toLogin: function(){
		
	}
}