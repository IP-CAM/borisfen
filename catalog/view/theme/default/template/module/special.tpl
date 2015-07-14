<?php
$this->language->load('product/product');
$button_wishlist = $this->language->get('button_wishlist');
$button_compare = $this->language->get('button_compare');
?>
<?php if (($setting['position'] == 'content_top') || ($setting['position'] == 'content_bottom')){
    if ( !empty($products) ) {
?>

<div class="box special">   
    <div class="heading">
        <span><?php echo $heading_title; ?></span>
        <a href="/special" class="all"><span>Все акции</span> »</a>
    </div>
    <div id="scroller_special" class="scroller product-grid">
      <ul class="row jcarousel-skin-opencart scroller" style="padding: 0; list-style: none;">
        <?php foreach ($products as $product) { ?>
		<li <?php if (isset($products) && (count($products) < 5)) { ?>class="col-xs-3" <?php } ?>style="height: 334px; margin-bottom: 30px;">
          <div class="product-thumb transition">
            <div class="image <?php if($product['special_end'] > 0):?>counterBox<?php endif;?>">
                <button type="button" data-toggle="tooltip" title="<?php print $button_wishlist; ?>" onclick="addToWishList('<?php print $product['product_id']; ?>');" class="addToWishList">
                    <i class="fa fa-heart"></i>
                </button>
                <a href="<?php print $product['href']; ?>">
<?php if($product['special_end'] > 0):?>
    <span class="counter" id="counter_<?php print $product['product_id'];?>_special"></span>
    <script>counter($('#counter_<?php print $product['product_id']?>_special'), <?php print $product['special_end']; ?>);</script>
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
                    <div class="product-name"><a href="<?php print $product['href']; ?>"><?php print $product['name']; ?></a><br>
                        <?php if(!empty($product['category'])) { ?>
                            <a href="<?php print $product['category']['url']; ?>"><b><?php print $product['category']['name']; ?></b></a>
                        <?php } ?>
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
                      <?php if(!$product['out_of_stock']) { ?>
                        <a onclick="jv_qiuckorder_show('<?php print $product['product_id']; ?>', $(this).parents('.right').find('input[name=quantity]').first().val());void(0);" class="fastorder">Быстрый заказ</a>
                      <?php } else { ?>
                        <a class="fastorder disabled">Быстрый заказ</a>
                      <?php } ?>
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
		<?php } ?>
	  </ul>
    </div>
</div>
<?php if (isset($products) && (count($products) >= 5)) { ?>
<script type="text/javascript"><!--
    $('#scroller_special ul').jcarousel({
        vertical: false,
        visible: 4,
        scroll: 1,
        animation: 1000
    });
//--></script>
<?php } ?>
<?php
    }
} else {
?>
<div class="box special">  
<div class = "heading"><span><?php echo $heading_title; ?></span></div>
<div class="row widget-inline">
    <div class=" widget">
        <ul class="products-list-mini list-unstyled">
            <?php foreach ($products as $product) { ?>
            <li class="product-thumb transition">
                <a href="<?php echo $product['href']; ?>" class="image pull-left"> <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"  /> </a>
                <div class="product-details">
                    <div class="product-name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                     <div class="quantity">
                        <?php if($product['left_of_stock']):?>
                        <span class="LeftStock"><?php print $product['left_of_stock']; ?></span>
                        <?php endif; ?>
                        <?php if($product['in_stock']):?>
                        <span class="InStock"><?php print $product['in_stock']; ?></span>
                        <?php endif; ?>
                        <?php if($product['out_of_stock']):?>
                        <span class="OutOfStock"><?php print $product['out_of_stock']; ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if($this->config->get('config_display_product_price')) { ?>
                    <p class="price">
                        <?php if (!$product['special']) { ?>
                        <?php echo $product['price']; ?>
                        <?php } else { ?>
                        <span class="price-new"><?php echo $product['special']; ?></span>
                        <span class="price-old"><?php echo $product['price']; ?></span>
                        <?php } ?>
                    </p>
                    <?php } ?>
                    <?php if ($product['rating']) { ?>
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if ($product['rating'] < $i) { ?>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </li>
            <?php } ?>

        </ul>
    </div>
</div>
</div>
<?php } ?>