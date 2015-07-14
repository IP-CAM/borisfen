<?php if ($this->config->get('config_additional_products_list') && $products) { ?>
    <div class="relatedW jcarousel-skin-opencart scroller">
        <div class="heading"><span><?php echo $tab_related; ?></span></div>
        <ul class="row related" style="padding: 0px;">
            <?php $i = 0; ?>
            <?php foreach ($products as $product) { ?>
    <li <?php if (count($products) < 5) { ?>class="col-xs-3"<?php } ?>>
        <div class="product-thumb transition">
            <div class="image">
                <button type="button" data-toggle="tooltip" title="<?php print $button_wishlist; ?>" onclick="addToWishList('<?php print $product['product_id']; ?>');" class="addToWishList">
                    <i class="fa fa-heart"></i>
                </button>
                <a href="<?php print $product['href']; ?>">
<?php if($product['special_end'] > 0):?>
    <span class="counter" id="counter_<?php print $product['product_id'];?>"></span>
    <script>counter($('#counter_<?php print $product['product_id']?>'), <?php print $product['special_end']; ?>);</script>
<?php endif;?>
                  <?php echo $product['promo_tag_top_right']; ?>
                  <?php echo $product['promo_tag_top_left']; ?>
                  <?php echo $product['promo_tag_bottom_left']; ?>
                  <?php echo $product['promo_tag_bottom_right']; ?>
                  <img src="<?php print $product['thumb']; ?>" alt="<?php print $product['name']; ?>" title="<?php print $product['name']; ?>" class="img-responsive" />
                </a>
            </div>
            <div class="caption">
                <div class="list">
                <div class="left">
                    <div class="product-name">
                        <a href="<?php print $product['href']; ?>"><?php print $product['name']; ?></a><br>
                        <?php print $product['manufacturer']; ?>
                    </div>
                    <?php if($this->config->get('config_short_category_description')) { ?>
                    <div class="description"><?php print $product['short_description']; ?></div>
                    <?php } ?> 
                    <?php if ($product['price'] && $this->config->get('config_display_product_price')) { ?>
                    <p class="price">
                        <?php if (!$product['special']) { ?>
                        <?php print $product['price']; ?>
                        <?php } else { ?>
                        <span class="price-old"><?php print $product['price']; ?></span>
                        <span class="price-new"><?php print $product['special']; ?></span>
                        <?php } ?>
                    </p>
                    <?php } ?>
                </div>
                <div class="right">
                  <div class="button-group">
                        <a onclick="jv_qiuckorder_show('<?php print $product['product_id']; ?>', $(this).parents('.right').find('input[name=quantity]').first().val());void(0);" class="fastorder">Быстрый заказ</a>
                        <button type="button" 
                        	<?php 
                        	if(
                        		!(
									(
										!$this->config->get('config_stock_checkout')
									) && (
										$product['quantity'] < 1
									)
								)
							) { ?>
							onclick="addToCart('<?php print $product['product_id']; ?>');" 
							<?php } ?> 
							class="
								addToCart 
								<?php if($product['out_of_stock']) { ?> disabled <?php } ?>
								<?php if($product['in_cart']) { ?> in-cart <?php } ?>
							">
                            <i class="fa fa-shopping-cart"></i>
                            <span product-id="<?php print $product['product_id']; ?>" class="add-text hidden-xs hidden-sm hidden-md"><?php print $product['in_cart']?$this->language->get('button_in_cart'):$this->language->get('button_cart'); ?></span>
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </li>
                <?php $i++; ?>
            <?php } ?>
        </ul>
    </div>

    <?php if (count($products) >= 5) { ?> <!-- Карусель -->
        <script>
            $(document).ready(function () {
                $('.relatedW ul').jcarousel({
                    vertical: false,
                    visible: 4,
                    scroll: 1,
                    auto: 5,
                    //animation: 1000,
                    wrap: 'circular'
                });
            });
        </script>
    <?php } ?>
<?php } ?>