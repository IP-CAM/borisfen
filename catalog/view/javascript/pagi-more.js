$(document).ready(function(){
	var page = parseInt($('input[name=current_page]').val());
	var max_page = $('input[name=max_page]').val();
	if(page >= max_page) {
		$('#pagi-more').fadeOut(1);
	}
});
$(document).on('click', '#pagi-more', function(e){
	var category_id = $('input[name=category_id]').val();
	var manufacturer_id = $('input[name=manufacturer_id]').val();
	var search_key = $('input[name=search_key]').val();
	
	var page = parseInt($('input[name=current_page]').val()) + 1;
	$('input[name=current_page]').val(page);
	var max_page = $('input[name=max_page]').val();
	if(page >= max_page) {
		$('#pagi-more').fadeOut(800);
		if(page > max_page) {
			e.preventDefault();
			return false;	
		}
	}
	if(typeof category_id != undefined) {
		var next_page_href = getPageHref(category_id, page, 'category');
	} else if(typeof manufacturer_id != undefined) {
		var next_page_href = getPageHref(manufacturer_id, page, 'manufacturer');
	} else if(typeof search_key != undefined) {
		var next_page_href = getPageHref(search_key, page, 'manufacturer');
	}
	$.ajax({
		url: next_page_href,
		type: 'get',
		success: function(response){
			$(response).find('#content > .row > .product-layout').each(function(){
				$('#content > .row > .product-layout').last().after('<div class="product-layout product-grid col-lg-3 col-md-3 col-sm-3 col-xs-12">' + $(this).html() + '</div>');
			});
		},
		error: function(e1, e2){
			
		}
	});
	e.preventDefault();
	return false;
});
var getPageHref = function(page_id, page, type) {
	var category_href = false;
	var data = {
		page_id: page_id,
		page: page,
		type: type
	};
	$.ajax({
		url: '/index.php?route=common/maintenance/getLink',
		async: false,
		type: 'post',
		dataType: 'json',
		data: data,
		success: function(response){
			if(typeof response.href != 'undefined') {
				category_href = response.href;
			}
		},
		error: function(e1, e2){
			console.log(e1);
			console.log(e2);
		}
	});
	
	return category_href;
}