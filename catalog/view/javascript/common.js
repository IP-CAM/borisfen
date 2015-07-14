$(document).ready(function() {

    $( ".full-views" ).click(function() {
        $( ".box.welcome" ).toggleClass( "active", 500 );
        $( ".full-views" ).toggleClass( "active", 0 );
    });
    
    $(document).on('click', '.goToShop', function() {
            location.reload();      
    });
  
        
        
    $('header .navbar-default > .nav > li').hover(function() {
        if($(this).attr('data-height')) {
            var dropdown_height = $(this).attr('data-height');
        } else {
            $(this).attr('data-height', $(this).children('.dropdown-holder').height()+20); 
            var dropdown_height = $(this).attr('data-height');
        }
        $(this).children('.dropdown-holder').height(0);
        $(this).children('.dropdown-holder').animate({'height':dropdown_height}, 500);

    },function() {
        $(this).children('.dropdown-holder').animate({'height':0}, 0);
    });

    
    /* $('header .navbar-default > .nav > li').hover(function() {
        var dropdown_height = $(this).children('.dropdown-holder').height();
        console.log(dropdown_height);
        $('header .navbar-default .dropdown-holder .nav > li a').hover(function() {
            var m_height = $(this).children('img').height();
            if (m_height){
            $(this).parents('.dropdown-holder').css({'height':m_height+60});
            }
        },function() {
            $(this).parents('.dropdown-holder').css({'height':dropdown_height+60});
        });
    }); */
    
	// Remove modal dialogs on click overlay
    if (window.PointerEvent) {
        $(document).on('click', '.modal-backdrop', function () {
            $('.modal .modal-body .close').trigger('click');
        });
    }
	if(detectmob()) {
		$('body').addClass('ios-device');
		$('.mega-col-inner > ul > li > a > span').each(function(){
			if($(this).parent('a').parent('li').children('.dropdown-toggle').html()) {
				
			} else {
				$(this).on('touchstart click', function(){
					window.location.href = $(this).parent('a').attr('href');
				});
			}
		});
		
		$('.dropdown-toggle').each(function(){
			var flag = false;
			$(this).on('touchstart click', function(e){
				if (!flag) {
					flag = true;
				    setTimeout(function(){ flag = false; }, 500);
				    if(!$(this).hasClass('clicked')) {
				    	$(this).addClass('clicked');
				    	$(this).addClass('last-clicked');
						$(this).parent('.parent').parent('ul').children('.parent').children('.dropdown-menu').hide();
						$(this).parent('.parent').parent('ul').children('.parent').children('.dropdown-toggle:not(.last-clicked)').removeClass('clicked');
						$(this).next('.dropdown-menu').show();
						$(this).removeClass('last-clicked');
						e.stopImmediatePropagation();
						e.preventDefault();
						return false;
					} else {
						window.location.href = $(this).attr('href');
					}
				  }
			});
		});
	}
	
	// Show preloader on change page
//    window.onbeforeunload = function() {
//    	EasyPreloader.show();
//	};
	/* Expand debuger */
	$(document).on('click', '#debugTogglePageCaching', function() {
		var dpc = $.cookie('dpc');
		if (dpc == 'on') {
			$.cookie('dpc', 'off', {
				expires: 7,
				path: '/'
			});
		} else {
			$.cookie('dpc', 'on', {
				expires: 7,
				path: '/'
			});
		}
	});
	$(document).on('click', '#debugToggleQueriesCollecting', function() {
		var dqc = $.cookie('dqc');
		if (dqc == 'on') {
			$.cookie('dqc', 'off', {
				expires: 7,
				path: '/'
			});
		} else {
			$.cookie('dqc', 'on', {
				expires: 7,
				path: '/'
			});
		}
	});
	/* Expand debuger */

	/* Global Ajax config */
	$(document).ajaxSend(function() {
		EasyPreloader.show();
	});
	$(document).ajaxComplete(function() {
		EasyPreloader.hide();
	});
	/* Global Ajax config */
	/* Remove added product */
	$(document).on('click', '.prepared-to-remove', function() {
		var product_id = $(this).attr('product-id');
		removeFromCart(product_id);
		$(this).removeClass('prepared-to-remove');
	});
	/* Remove added product */
	/* Remove Marker Added from product when it removed by cart module */
	$(document).on('click', '.removeProductB', function() {
		var product_id = $(this).attr('product-id');
		if (product_id) {
			$('.add-text').each(function() {
				if ($(this).attr('product-id') == product_id) {
					$(this)
					.text($('input[name=button_cart]').val())
					.removeClass('in-cart');
				}
			});
		}
	});
	/* Remove Marker Added from product when it removed by cart module */
	/* Remove button in quickcheckout */
	$(document).on('click', '.removeItem > i', function() {
		$(this).parent('span').parent('.total').parent('tr').children('td.quantity').children('div.quantity').children('.product-qantity').val('-1');
		$(this).parent('span').parent('.total').parent('tr').children('td.quantity').children('div.quantity').children('i.plus').trigger('click');
	});
	/* Remove button in quickcheckout */

	/* Validate input type number */
	$('.number').numeric();
	/* Validate input type number */
	$('select').selectpicker({});

	$('.quantity > .plus').on('click', function(){
        var quantity = parseInt($(this).parent('.quantity').children('input[name=quantity]').val());
        quantity = isNaN(quantity) ? 1 : quantity;
        $(this).parent('.quantity').children('input[name=quantity]').val(quantity + 1);
        
    });
    $('.quantity > .minus').on('click', function(){
        var quantity = parseInt($(this).parent('.quantity').children('input[name=quantity]').val());
        quantity = isNaN(quantity) ? 2 : quantity;
        if(quantity > 1) {
            $(this).parent('.quantity').children('input[name=quantity]').val(quantity - 1); 
        }
        
    });
    /*menu
	$('#menu').lavalamp({
		easing: 'easeOutBack'
	});
	$('#menu').change(function() {
		var v = $(this).val();
		var a = $('#setactive').children('.lavalamp-item').eq(v);
		
		$('#menu').data('active',a).lavalamp('update');
	});*/

callme.init('#callme-button');
yearOld.init('#year-button');

	$( document ).ready( function() {
		var accsess2173 = $.cookie('accsess2173');;
		console.log(accsess2173);
		if ( accsess2173 != 'ok' ) {
			yearOld.open();
		}
	});


/*checkbox*/

$('input[type=checkbox]').not('.not_custom').checkbox();

/* footer fixed */
var footerPadding = parseInt($('footer.footer').css('padding-top')) +
parseInt($('footer.footer').css('padding-bottom')) +
parseInt($('footer.footer').css('border-top-width')) +
        parseInt($('footer.footer').css('border-bottom-width')); //1--> chrome лох
        var footerHeight = parseInt($('footer.footer').height());
        var footerHeightF = parseInt(footerHeight + footerPadding);

        $('#wrapper').css('padding-bottom', footerHeightF);
        $('footer.footer').css('margin-top', (-footerHeightF));

        /* Search */
        $('#search input[name=\'search\']').parent().find('button').on('click', function() {
        	url = $('base').attr('href') + 'index.php?route=product/search';

        	var search = $('header input[name=\'search\']').val();

        	if (search) {
        		url += '&search=' + encodeURIComponent(search);
        	}

        	location = url;
        });

        $('#search input[name=\'search\']').on('keydown', function(e) {
        	if (e.keyCode == 13) {
        		$('header input[name=\'search\']').parent().find('button').trigger('click');
        	}
        });

    // Cart Dropdown
    $(document).on('click', '#cart > .dropdown-toggle', function() {
        //        $('#cart').load('/index.php?route=module/cart #cart > *');
        $('#cart').load('index.php?route=module/cart #cart > *');
    });

    // Notifications.
    $('.success img, .warning img, .attention img, .information:not(body) img').click(function() {
    	$(this).parent().fadeOut('slow', function() {
    		$(this).remove();
    	});
    });

    // Language Dropdown
    $('#language-menu li a').on('click', function() {

    	var languageVal = $(this).children('img').html();
    	$('#language-choice').html(languageVal);

    });

    // Currency
    $('#currency a').on('click', function(e) {
    	e.preventDefault();

    	$('#currency input[name=\'currency_code\']').attr('value', $(this).attr('href'));

    	$('#currency').submit();
    });


    // Navigation - Columns
    $('.main-navbar .dropdown-menu').each(function() {

    	var menu = $('.main-navbar').offset();
    	var dropdown = $(this).parent().offset();

    	var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('.main-navbar').outerWidth());

    	if (i > 0) {
    		$(this).css('margin-left', '-' + (i + 5) + 'px');
    	}

    });

    // Product list bootstrap fixes
    var length = $('#column-left, #column-right').length;

    if (length == 2) {
    	$('.module-layout > div').attr('class', 'col-xs-6');
    } else if (length == 1) {
    	$('.module-layout > div').attr('class', 'col-xs-4');
    } else {
    	$('.module-layout > div').attr('class', 'col-xs-3');
    }


    // product-list
    $(document).on('click', '#list-view', function() {
    	switchToList();
    });

    //	$('#list-view').click(function() {
    //	    switchToList();
    //	});

    // product-grid
    $(document).on('click', '#grid-view', function() {
    	switchToGrid();
    	setTimeout(function() {
    		/* Same height for product lists */
    		var max_product_height = 0;
    		$('#contentRow > .product-layout').each(function() {
    			var product_height = parseInt($(this).height());
    			if (product_height > max_product_height) {
    				max_product_height = product_height;
    			}
    		});
    		$('#contentRow > .product-layout').height(max_product_height + 'px');
    		/* Same height for product lists */
    	}, 2000);
    });
    //	$().click(function() {
    //		switchToGrid();
    //	});

if (localStorage.getItem('display') == 'list') {
	$('#list-view').trigger('click');

} else {
	$('#grid-view').trigger('click');
}

    // tooltips on hover


    $('[data-toggle=\'tooltip\']').tooltip({
    	container: 'body'
    });



});
$(function() {
	$('.product-layout').matchHeight();
});
$(document).on('click', '.colorbox', function(e) {
	e.preventDefault();


	html = '<div id="modal" class="modal">';
	html += '  <div class="modal-dialog">';
	html += '    <div class="modal-content">';
	html += '      <div class="modal-header">';
	html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	html += '        <h4 class="modal-title">' + $(this).attr('alt') + '</h4>';
	html += '      </div>';
	html += '      <div class="modal-body">';
	$.ajax(this.href, {
		async: false,
		success: function(data) {
			html += data;
		}
	});
	html += '    </div';
	html += '    </div';
	html += '  </div>';
	html += '</div>';
	$('body').append(html);
	$('#modal').modal();

});

function detectIos() {
	if(navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i)) {
		return true;
	} else {
		return false;
	}
}

function detectmob() {
	if( navigator.userAgent.match(/Android/i)
		|| navigator.userAgent.match(/webOS/i)
		|| navigator.userAgent.match(/iPhone/i)
		|| navigator.userAgent.match(/iPad/i)
		|| navigator.userAgent.match(/iPod/i)
		|| navigator.userAgent.match(/BlackBerry/i)
		|| navigator.userAgent.match(/Windows Phone/i)
		){
		return true;
	} else {
		return false;
	}
}
function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function rgb2hex(rgb) {
	if(!rgb) {
		return '#000000';
	}
    if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function getLink(link, get){
	var token = $('#current_token').val();
	if(typeof get != 'undefined') {
		if(!token) {
			return '/index.php?route=' + link + '&' + get;
		} else {
			return '/index.php?route=' + link + '&token=' + token + '&' + get;
		}		
	} else {
		if(!token) {
			return '/index.php?route=' + link;
		} else {
			return '/index.php?route=' + link + '&token=' + token;
		}
	}
}


/*preloader*/
var EasyPreloader = {
	quantity: 0,
	show: function() {
		if (this.quantity == 0) {
			html = '<div id="easyPreloader" style="display: none;"><div class="circle"></div><div class="circle1"></div></div><div id="easyPreloaderModal" style="display: none;" class="modal-backdrop fade in"></div>';
			$('body').prepend(html);
			$('#easyPreloader').fadeIn(500);
            //			$('#easyPreloaderModal').fadeIn(500);
        }
        this.quantity++;
    },
    hide: function() {
    	if (this.quantity == 1) {
    		setTimeout(function() {
    			$('#easyPreloader').fadeOut(1500).delay(1000).remove();
    			$('#easyPreloaderModal').fadeOut(1500).delay(1000).remove();
    		}, 500);
    		this.quantity--;
    	} else if (this.quantity > 1) {
    		this.quantity--;
    	} else {
    		console.log('error with quantity of preloaders..');
    	}
    }
};

function addToCart(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;
	$('[onclick="addToCart(\'' + product_id + '\');"]').addClass('in-cart');
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();

			if (json['redirect']) {
				window.location = json['redirect'];
			}

			if (json['success']) {
				$('#cart-total').html(json['total']);
				alert(json['success']);
			}
		}
	});
}

function removeFromCart(product_id) {

}

function addToWishList(product_id) {
	$.ajax({
		url: 'index.php?route=account/wishlist/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();

			if (json['success']) {
				$('#notification').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');

				$('.success').fadeIn('slow');

				$('#wishlist-total').html(json['total']);

				$('html, body').animate({
					scrollTop: 0
				}, 'slow');
			}
		}
	});
}

function addToCompare(product_id) {
	$.ajax({
		url: 'index.php?route=product/compare/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();

			if (json['success']) {
				$('#notification').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');

				$('.success').fadeIn('slow');

				$('#compare-total').html(json['total']);

				$('html, body').animate({
					scrollTop: 0
				}, 'slow');
			}
		}
	});
}

function jv_qiuckorder_show(product_id, quantity) {
	if(!quantity) {
		quantity = 1;
	}
	
	if (product_id > 0) {
		$.ajax({
			url: 'index.php?route=module/jvquickorder/update',
			async: false,
			type: 'post',
			timeout: 5000,
			data: 'product_id=' + product_id + '&quantity=' + quantity,
			dataType: 'json',
			success: function(json) {
				$('.success, .warning, .attention, information, .error').remove();
				$('#myModal').remove();
                //$("head").after(json['output']);
//                $("body").after(json['output']);
				alert(json['output']);
				$('.modal-footer').remove();
//                $('#myModal').modal({
//                	backdrop: true,
//                	keyboard: true
//                });

            }
        });
	}
}

function switchToGrid() {
	$('#grid-view').addClass('active');
	$('#list-view').removeClass('active');
	
	$('#content .product-layout > .clearfix').remove();

    // What a shame bootstrap does not take into account dynamically loaded columns
    cols = $('#column-right, #column-left').length;

    if (cols == 2) {
    	$('#content .product-layout').attr('class', 'product-layout product-grid col-xs-6');
    } else if (cols == 1) {
    	$('#content .product-layout').attr('class', 'product-layout product-grid col-xs-4');
    } else {
    	$('#content .product-layout').attr('class', 'product-layout product-grid  col-xs-3');

    }
//    $('.product-layout')._update();
    localStorage.setItem('display', 'grid');
}

function switchToList() {
	$('#grid-view').removeClass('active');
	$('#list-view').addClass('active');
	
	$('#content .product-layout').attr('class', 'product-layout product-list col-xs-12').attr( 'style', '');
//	$('.product-layout')._update();
	localStorage.setItem('display', 'list');
}

/* Expand Array object */
Array.prototype.exists = function(search) {
	for (var i = 0; i < this.length; i++) {
		if (this[i] == search) return true;
	}
	return false;
};
Object.defineProperty(Array.prototype, 'exists', {
    enumerable:false
});
/* Expand Array object */
/* HS and JCarousel compatibility */
var anchorList = [];
/* HS and JCarousel compatibility */
/* Autocomplete */
(function($) {
	function Autocomplete(element, options) {
		this.element = element;
		this.options = options;
		this.timer = null;
		this.items = new Array();

		$(element).attr('autocomplete', 'off');
		$(element).on('focus', $.proxy(this.focus, this));
		$(element).on('blur', $.proxy(this.blur, this));
		$(element).on('keydown', $.proxy(this.keydown, this));

		$(element).after('<ul class="dropdown-menu"></ul>');
		$(element).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));
	}

	Autocomplete.prototype = {
		focus: function() {
			this.request();
		},
		blur: function() {
			setTimeout(function(object) {
				object.hide();
			}, 200, this);
		},
		click: function(event) {
			event.preventDefault();

			value = $(event.target).parent().attr('data-value');

			if (value && this.items[value]) {
				this.options.select(this.items[value]);
			}
		},
		keydown: function(event) {
			switch (event.keyCode) {
                case 27: // escape
                this.hide();
                break;
                default:
                this.request();
                break;
            }
        },
        show: function() {
        	var pos = $(this.element).position();

        	$(this.element).siblings('ul.dropdown-menu').css({
        		top: pos.top + $(this.element).outerHeight(),
        		left: pos.left
        	});

        	$(this.element).siblings('ul.dropdown-menu').show();
        },
        hide: function() {
        	$(this.element).siblings('ul.dropdown-menu').hide();
        },
        request: function() {
        	clearTimeout(this.timer);

        	this.timer = setTimeout(function(object) {
        		object.options.source($(object.element).val(), $.proxy(object.response, object));
        	}, 200, this);
        },
        response: function(json) {
        	html = '';

        	if (json.length) {
        		for (i = 0; i < json.length; i++) {
        			this.items[json[i]['value']] = json[i];
        		}

        		for (i = 0; i < json.length; i++) {
        			if (!json[i]['category']) {
        				html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
        			}
        		}

                // Get all the ones with a categories
                var category = new Array();

                for (i = 0; i < json.length; i++) {
                	if (json[i]['category']) {
                		if (!category[json[i]['category']]) {
                			category[json[i]['category']] = new Array();
                			category[json[i]['category']]['name'] = json[i]['category'];
                			category[json[i]['category']]['item'] = new Array();
                		}

                		category[json[i]['category']]['item'].push(json[i]);
                	}
                }

                for (i in category) {
                	html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

                	for (j = 0; j < category[i]['item'].length; j++) {
                		html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
                	}
                }
            }

            if (html) {
            	this.show();
            } else {
            	this.hide();
            }

            $(this.element).siblings('ul.dropdown-menu').html(html);
        }
    };

    $.fn.autocomplete = function(option) {
    	return this.each(function() {
    		var data = $(this).data('autocomplete');

    		if (!data) {
    			data = new Autocomplete(this, option);

    			$(this).data('autocomplete', data);
    		}
    	});
    }
})(window.jQuery);
