<div class="cartPopup">
    <?php if(isset($cart_products)) { ?>
    <form method="post" action="#" name="cart_products" class="cartProductsForm">
        <?php foreach ($cart_products as $product) { ?>
        <div class="cartProduct" product-id="<?php print $product['product_id']; ?>" product-key="<?php print $product['key']; ?>" single-price="<?php print $product['single_price']; ?>">
        <!--<div class="cartProduct" product-id="<?php print $product['product_id']; ?>">-->
            <div class="row">
                <div class="col-xs-3">
                    <a href="<?php echo $product['href']; ?>"><img src="<?php print $product['image']; ?>" title="<?php print $product['name']; ?>" class="img-responsive img-thumbnail"></a>
                </div>
                <div class="col-xs-4 product-name">
                    <a href="<?php echo $product['href']; ?>"><?php print $product['name']; ?></span></a>
                    
                    <?php if(isset($product['option']) && !empty($product['option'])) { ?>
                    <div class="productOptions">
                        <?php foreach ($product['option'] as $option) { ?>
                        <div>
                            <small> - <?php print $option['name']; ?>: <?php print $option['option_value']; ?></small>
                        </div> 
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-xs-3 price">
                    <span class="price-new"><?php print $product['price']; ?></span>
                </div>
                <div class="col-xs-2 removeTab">
                    <div class="quantity">
                        <label><?php print $entry_quantity; ?>:</label>
                        <input class="form-control quantityInput" type="text" name="quantity" size="3" value="<?php print $product['quantity']; ?>">
                        <span class="plus"><i class="fa fa-angle-up"></i></span>
                        <span class="minus"><i class="fa fa-angle-down"></i></span>
                    </div>
                    <div class="remove">
                        <i class="fa fa-times"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="cartFooter row">
            <div class="">

                <div class="col-xs-6 text-left">
                    <a href="/index.php?route=checkout/checkout" class="btn btn-primary"><?php print $this->language->get('button_checkout'); ?></a>
                </div> 
                <div class="col-xs-6 text-right"><a class="goToShop" data-dismiss="modal" aria-hidden="true"><?php print $this->language->get('button_button_shopping'); ?></a></div>
            </div>
        </div>
    </form>
    <?php } ?>
</div>
<script>
    $( document ).ready(function() {
        $('.cartPopup').parent().parent().parent().parent().addClass('cartPopupW');
    });
</script>