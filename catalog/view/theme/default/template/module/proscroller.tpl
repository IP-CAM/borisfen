<div class="box proscroller">
  <div class="heading"><span><?php print $heading_title; ?></span></div> 
  <div class="row">
    <div class="col-xs-12">
    <?php if (isset($position) && $position == 'column'){ ?>
    <style type="text/css">
    #scroller_ <?php print $module ; ?> .jcarousel-skin-opencart .jcarousel-container-horizontal
    {
        padding: 0px 0px;
    }
    </style>	
    <?php } ?>
    <div id="scroller_<?php print $module; ?>" class="scroller product-grid">
      <ul class="jcarousel-skin-opencart scroller">
        <?php foreach ($products as $product) { ?>
		<li>
          <div class="product-thumb transition">
           <div class="topThumb">
               <div class="product-name"><a href="<?php print $product['href']; ?>"><?php print $product['name']; ?></a></div>
                <div class="quantity">
                        <?php if($product['left_of_stock']):?>
                        <span class="LeftStock"><i class="fa fa-exclamation"></i> <?php print $product['left_of_stock']; ?></span>
                        <?php endif; ?>
                        <?php if($product['in_stock']):?>
                        <span class="InStock"><i class="fa fa-check"></i> <?php print $product['in_stock']; ?></span>
                        <?php endif; ?>
                        <?php if($product['out_of_stock']):?>
                        <span class="OutOfStock"><i class="fa fa-times"></i> <?php print $product['out_of_stock']; ?></span>
                        <?php endif; ?>
                    </div>
           </div>
            <div class="image">
                <a href="<?php print $product['href']; ?>">
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
                    <div class="product-name"><a href="<?php print $product['href']; ?>"><?php print $product['name']; ?></a></div>
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
                    <div class="model">
                    Артикул: <?php print $product['model']; ?>
                    </div>
                    <?php if ($product['price'] && $this->config->get('config_display_product_price')) { ?>
                    <p class="price">
                        <?php if (!$product['special']) { ?>
                        <?php print $product['price']; ?>
                        <?php } else { ?>
                        <span class="price-new"><?php print $product['special']; ?></span>
                        <span class="price-old"><?php print $product['price']; ?></span>
                        <?php } ?>
                    </p>
                    <?php } ?>
                </div>
                <div class="right">
                  <div class="button-group">
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
                         <a class="btn-primary" href="<?php print $product['href']; ?>">Подробнее</a>
                         <!--
                        <button type="button" data-toggle="tooltip" title="<?php print $button_wishlist; ?>" onclick="addToWishList('<?php print $product['product_id']; ?>');" class="addToWishList">
                            <i class="fa fa-heart"></i>
                        </button>
                        <button type="button" data-toggle="tooltip" title="<?php print $button_compare; ?>" onclick="addToCompare('<?php print $product['product_id']; ?>');" class="addToCompare">
                            <i class="fa fa-exchange"></i>
                        </button>
                        -->
                    </div>
                </div>
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
               <div class="description">
                    <div class="descriptionInner">
                    <?php if($this->config->get('config_short_category_description')) { ?>
                    <?php print $product['short_description']; ?>
                    <?php } ?>
                    </div>     
               </div>
            </div>
        </div>
        </li>
		<?php } ?>
	  </ul>
    </div>
    <script type="text/javascript">
    jQuery.easing['Effect']=function(p,t,b,c,d){
        t /= d;
    	t--;
    	return -c * (t*t*t*t - 1) + b;
    };
    </script>
    <script type="text/javascript"><!--
    function mycarousel_initCallback(carousel)
    {
    	<?php if ($disableauto) {?>
        carousel.buttonNext.bind('click', function() {
            carousel.startAuto(0);
        });
    
        carousel.buttonPrev.bind('click', function() {
            carousel.startAuto(0);
        });
    	<?php } ?>
    	
    	<?php if ($hoverpause) {?>
        carousel.clip.hover(function() {
            carousel.stopAuto();
        }, function() {
            carousel.startAuto();
        });<?php } ?>
    };
    //--></script>

    <script type="text/javascript"><!--
    $('#scroller_<?php print $module; ?> ul').jcarousel({
        vertical: false,
        initCallback: mycarousel_initCallback,
        visible: <?php print $visible; ?>,
        scroll: <?php print $scroll; ?>,
        auto: <?php print $autoscroll; ?>,
        animation: <?php print $animationspeed; ?>,
        <?php print $type; ?>
    });
    //--></script>
    </div>
    </div>
</div>