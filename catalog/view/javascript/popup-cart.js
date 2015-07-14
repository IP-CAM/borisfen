var miniCart = {
    incButtonPath: '.cartProductsForm > .cartProduct > .row > .removeTab > .quantity > .plus',
    decButtonPath: '.cartProductsForm > .cartProduct > .row > .removeTab > .quantity > .minus',
    removeButtonPath: '.cartProductsForm > .cartProduct > .row  > .removeTab > .remove',
    url: '/index.php?route=checkout/cart/minicart',
    /* Bind events */
    init: function(){
        $(document).on('click', '' + miniCart.incButtonPath,  function(){
            var _this = this;
            miniCart.increaseQuantity(_this);
        });
        $(document).on('click', '' + miniCart.decButtonPath,  function(){
            var _this = this;
            miniCart.decreaseQuantity(_this);
        });
        $(document).on('click', '' + miniCart.removeButtonPath,  function(){
            var _this = this;
            miniCart.removeProduct(_this);
        });
    },
    /* Increase quantity counter and update product information in session */
    increaseQuantity: function(_this){
        // Increase Quantity
        var curentQuantity = parseInt($(_this).parent('.quantity').children('.quantityInput').val()) + 1;
            $(_this).parent('.quantity').children('.quantityInput').val(curentQuantity);
        var data = this.collectCartInfo();
        this.updateProducts(data);
    },
    /* Decrease quantity counter and update product information in session */
    decreaseQuantity: function(_this){
        // Decrease Quantity
        var curentQuantity = parseInt($(_this).parent('.quantity').children('.quantityInput').val());
        if(curentQuantity > 1) {
            $(_this).parent('.quantity').children('.quantityInput').val(curentQuantity - 1);
        } else {
            return false;
        }
        var data = this.collectCartInfo();
        this.updateProducts(data);
    },
    /* Remove product from miniCart content and update information in session */
    removeProduct: function(_this){
        // Remove product
        confirm('Вы уверены?', function(confirmResult){
            if(confirmResult) {
                $(_this).parent('.removeTab').parent('.row').parent('.cartProduct').remove();
                var data = miniCart.collectCartInfo();
                miniCart.updateProducts(data);
                miniCart.removeMarkerAdded(_this);
            }
        });
    },
    removeMarkerAdded: function(_this) {
    	var product_id = $(_this).parent('.removeTab').parent('.row').parent('.cartProduct').attr('product-id');
    	if(product_id) {
    		$('.add-text').each(function(){
    			if($(this).attr('product-id') == product_id) {
    				$(this)
					.text($('input[name=button_cart]').val())
					.removeClass('in-cart')
					;
    			}
    		});
    		$('[onclick="addToCart(\'' + product_id + '\');"]').removeClass('in-cart');
    	}
    },
    collectCartInfo: function(){
        var data = {
            products: [],
            miniCart: true
        };
        $('.cartProduct').each(function(){
            if(parseInt($(this).attr('product-id'))) {
                var product_id = parseInt($(this).attr('product-id'));
                var product_key = $(this).attr('product-key');
                var quantity = parseInt($(this).children('.row').children('.removeTab').children('.quantity').children('.quantityInput').val());
                var single_price = parseFloat($(this).attr('single-price'));
                data.products.push({
                    product_id: product_id,
                    quantity: quantity,
                    single_price: single_price,
                    product_key: product_key
                });
            }
        });
        return data;
    },
    /* Update product information in session bassed on curent cart content */
    updateProducts: function(data){
    	console.log(data);
    	// Must implement currency converting and special price
        $(data.products).each(function(){
        	var product_total_price = this.single_price * this.quantity;
        	$('.cartProduct[product-key="' + this.product_key + '"] .price .price-new > .onlyNumber').text(product_total_price.toFixed(2));
        });
        $.ajax({
            url: this.url,
            type: 'post',
            dataType: 'json',
            data: data,
            success: function(response){
                if(response.total) {
                    $('#cart-total').html(response['total']);
                } else {
                    if(response.empty) {
                        $('#cart-total').html(response['empty']);
                    }
                    bootbox.hideAll();
                }
            },
            error: function(){
                alert('Internal server error, please contact administration.');
            }
        });
    }
}
$(document).ready(function(){
    miniCart.init(); 
});