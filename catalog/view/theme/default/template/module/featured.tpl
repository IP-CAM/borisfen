<?php
$this->language->load('product/product');
$button_wishlist = $this->language->get('button_wishlist');
$button_compare = $this->language->get('button_compare');
?>
<?php if (($setting['position'] == 'content_top') || ($setting['position'] == 'content_bottom')){ ?>
<div class="box featured">   
<div class="heading"><span><?php echo $heading_title; ?></span></div>
<div class="row product-layout product-grid">
    <?php foreach ($products as $product) { ?>
    <div class="col-xs-3">
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
                        <!-- <button type="button" data-toggle="tooltip" title="<?php print $button_wishlist; ?>" onclick="addToWishList('<?php print $product['product_id']; ?>');" class="addToWishList">
                            <i class="fa fa-heart"></i>
                        </button>
                        <button type="button" data-toggle="tooltip" title="<?php print $button_compare; ?>" onclick="addToCompare('<?php print $product['product_id']; ?>');" class="addToCompare">
                            <i class="fa fa-exchange"></i>
                        </button> -->
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
    </div>
    <?php } ?>
</div>
</div> 

<?php } else { ?>
    
<div class="box featured">  
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