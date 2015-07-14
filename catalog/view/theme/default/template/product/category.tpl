<?php print $header; ?>
<div class="container box-flexible">
    <div class="row">
        <div class="col-xs-12">
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php print $breadcrumb['href']; ?>"><?php print $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
<?php if (!$categories) { ?>
    <?php print $column_left; ?>
<?php } ?>

        <?php if ($column_left && $column_right) { ?>
        <?php $cols = 6; ?>
        <?php } elseif (($column_left || $column_right) && !$categories) { ?>
        <?php $cols = 9; ?>
        <?php } else { ?>
        <?php $cols = 12; ?>
        <?php } ?>
        <div id="content" class="category col-xs-<?php print $cols; ?>">
            <?php print $content_top; ?>
            <span xmlns:v="http://rdf.data-vocabulary.org/#">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="<?php print $breadcrumb['href']; ?>" alt="<?php print $breadcrumb['text']; ?>"></a></span>
                <?php } ?>				
            </span>
    <div class="row">
        <div class="col-xs-12 <?php if ($categories) { ?>press-categories<?php } ?>">
            <h1><span><?php print $heading_title; ?></span></h1>
            
            <?php if ($categories) { ?>
                <?php if ($this->config->get('config_subcategory_list')) { ?>
                <div class="catImg">
                    <?php foreach ($categories as $category) { ?>
                            <div class="col-xs-3">
                                <div class="img">
                                    <a href="<?php echo $category['href']; ?>">
                                        <img alt="<?php print $category['name']; ?>" src="<?php echo $category['image']; ?>"/>
                                    </a>
                                </div>
                                <div class="text">
                                    <a href="<?php echo $category['href']; ?>"><?php print $category['name']; ?></a>
                                    <div><?php print $category['short_description']; ?></div>
                                </div>
                            </div>
                    <?php } ?>
                </div>
                <?php } ?>
            <?php } ?>
            
            <?php if ($short_description && $this->config->get('config_short_category_description')) { ?>
            <div class="row categoryDescription">
                <?php if ($thumb && $short_description && $this->config->get('config_short_category_description')) { ?>
                <div class="col-xs-3"><img src="<?php print $thumb; ?>" alt="<?php print $heading_title; ?>" title="<?php print $heading_title; ?>" class="img-thumbnail"/></div>
                <?php } ?>
                <div class="col-xs-<?php if ($thumb && $short_description) { ?>9 <?php } else { ?>12<?php } ?>"><?php print $short_description; ?></div>
            </div>
            <?php } ?>
            
            <?php if (!$categories && $products) { ?>
            <div class="filter-category-holder">
                <div class="input-sort">
                    <label class="control-label" for="input-sort"><?php print $text_sort; ?></label>
                    <select id="input-sort" class="form-control" onchange="location = this.value;">
                    <?php foreach ($sorts as $sorts) { ?>
                        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                    <option value="<?php print $sorts['href']; ?>" selected="selected"><?php print $sorts['text']; ?></option>
                        <?php } else { ?>
                    <option value="<?php print $sorts['href']; ?>"><?php print $sorts['text']; ?></option>
                        <?php } ?>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
               
<?php if ($products && !$categories) { ?>
    <div class="row">
        <div class="col-xs-12 text-left" style="margin-bottom: 10px;">
            <?php if ($pagination) { print $pagination; } ?>
        </div>
    </div>

    <div class="row" id="contentRow">
    <?php foreach ($products as $product) { ?>
    <div class="product-layout product-grid col-xs-4" style="height: 372px;">
        <div class="product-thumb transition">
            <div class="image <?php if($product['special_end'] > 0):?>counterBox<?php endif;?>">
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
                    <div class="product-name"><a href="<?php print $product['href']; ?>"><?php print $product['name']; ?></a>
						<!--<br><b><?php print $product['manufacturer']; ?></b>-->
                    </div>
                    <?php if($this->config->get('config_short_category_description')) { ?>
                    <div class="description"><?php print $product['short_description']; ?></div>
                    <?php } ?> 
                    <div class="rating">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <?php if ($product['rating'] < $i) { ?>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } else { ?>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                        <?php } ?>
                    <?php } ?>
                    </div>
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
    </div>
    <?php
    } ?>
</div>

<div class="row">
    <div class="col-xs-12 text-left ">
    <?php if ($pagination) { print $pagination; } ?>
    </div>
</div>

<?php } elseif (!$categories) { ?>
    <div class="alert alert-danger"><button type="button" onclick="history.go(-1);" class="close" data-dismiss="alert">×</button>Товаров в категории, не найдено.</div>
<?php } ?>

<?php if ($this->config->get('config_full_category_description') && $description) { 
    $str_description = strlen($description);
    if ( $str_description >= '500' ) {
?>
<div class="row bottomD">
    <div class="welcome-box">
        <div class="box welcome">
            <?php print $description; ?>
        </div>
        <span class="full-view"><a onclick="return false;" href="javascript:void(0);" class="full-views"><span class="views">Читать полность</span><span class="hide">Скрыть</span></a></span>
    </div>
</div>
<?php } } ?>

<?php echo $content_bottom; ?>
</div>
<?php print $column_right; ?></div>
</div>
<?php print $footer; ?>