jQuery(document).ready(function($){
    R_stars.init();
    $(document).on('mouseout', '.rating', function(e){
    	if(!$(this).hasClass('notSelectable')) {
	        var curentRaiting = $(this).children('.rating-radio-div').children('.curentRating').val();
	        console.log(curentRaiting);
	        $(this).children('.rating-radio-div').children('label').each(function(i, v) {
	            if(i < curentRaiting) {
	                $(this).children('i').first('i').removeClass('fa-star-o');
	                $(this).children('i').first('i').addClass('fa-star');
	            } else {
	                $(this).children('i').first('i').removeClass('fa-star');
	                $(this).children('i').first('i').addClass('fa-star-o');
	            }
	        });
    	}
    });
    $('.rating-radio-div > label').on({
        mouseenter: function () {
        	if(!$(this).hasClass('notSelectable')) {
    	        var curentIndex = $(this).index();
    	        $(this).parent('.rating-radio-div').children('label').each(function(i, v) {
    	            if(i <= curentIndex) {
    	                $(this).children('i').first('i').removeClass('fa-star-o');
    	                $(this).children('i').first('i').addClass('fa-star');
    	            }
    	        });
        	}
        },
        mouseleave: function () {
        	if(!$(this).hasClass('notSelectable')) {
    	        $(this).parent('.rating-radio-div').children('label').each(function(i, v) {
    	            $(this).children('i').first('i').removeClass('fa-star');
    	            $(this).children('i').first('i').addClass('fa-star-o');
    	        });
        	}
        }
    });
    $('.rating-radio-div > label').on('click', function(e){
    	if(!$(this).hasClass('notSelectable')) {
	        var currentRaiting = $(this).index(),
	        	product_id     = $(this).parents('.rating.dynamic').children('input[name=rating_product_id]').val(),
	        	default_color  = R_stars.default_color,
	        	hovered_color  = R_stars.hovered_color;
	        
	        if(product_id) {
	        	if(!R_stars.voteExists(product_id)) {
	        		R_stars.addVote(currentRaiting + 1, product_id);
	        	} else {
	        		R_stars.replaceVote(currentRaiting + 1, product_id);
	        	}
	        }
	        $(this).parent().children('.curentRating').val(currentRaiting+1);
	        $(this).parent('.rating-radio-div').children('label').each(function(i, v) {
	            if(i <= currentRaiting) {
	                $(this).children('i').first('i').removeClass('fa-star-o');
	                $(this).children('i').first('i').addClass('fa-star');
	            } else {
	                $(this).children('i').first('i').removeClass('fa-star');
	                $(this).children('i').first('i').addClass('fa-star-o');
	            }
	        });
    	}
    });
});

var R_stars = {
	vote_add_uri:      '',
	vote_replace_uri:  '',
	vote_validate_uri: '',
	default_color:     '',
	hovered_color:     '',
	
	init: function(){
		this.vote_add_uri	   = getLink('product/product/voteAdd');
		this.vote_replace_uri  = getLink('product/product/voteReplace');
		this.vote_validate_uri = getLink('product/product/voteValidate');
		
		//this.default_color = rgb2hex($('.rating.dynamic').find('.fa.fa-stack-1x.fa-star').css('color'));
		//if(!this.default_color) {
		//	this.default_color = '#999';
		//}
		//this.hovered_color = rgb2hex($('#button-cart').css('background-color'));
		//if(!this.hovered_color) {
		//	this.hovered_color = '#000';
		//}
	},
	voteExists: function(product_id) {
		var rating = $.cookie('vote_' + product_id);
		if(rating) {
			return true;
		} else {
			return false;
		}
	},
	addVote: function(rating, product_id){
		this.vote_add_uri;
		var data = {
			rating: rating,
			product_id: product_id,
		};
		$.ajax({
			url: R_stars.vote_add_uri,
			dataType: 'json',
			type: 'post',
			async: false,
			data: data,
			success: function(response){
				console.log(response);
				R_stars.saveRatingKey(response.key, response.product_id);
			}, 
			error: function(e1, e2){
				console.log(e1);
				console.log(e2);
			}
		});
	},
	replaceVote: function(rating, product_id){
		this.vote_replace_uri;
		var data = {
			rating: rating,
			product_id: product_id,
		};
		$.ajax({
			url: R_stars.vote_replace_uri,
			dataType: 'json',
			type: 'post',
			data: data,
			success: function(response){
				console.log(response);
			}, 
			error: function(e1, e2){
				console.log(e1);
				console.log(e2);
			}
		});
	},
	saveRatingKey: function(key, product_id) {
		$.cookie('vote_' + product_id, key, {
			expires: 31,
			path: '/'
		});
	}
}