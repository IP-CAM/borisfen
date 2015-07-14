<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-xs-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-xs-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-xs-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
        <h1><?php echo $heading_title; ?></h1>
        <?php if ($products) { ?>
        <p><a href="<?php echo $compare; ?>" id="compare-total"> <?php echo $text_compare; ?>   </a></p>
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
<div class="row" id="contentRow">
    <?php foreach ($products as $product) { ?>
    <div class="product-layout product-grid col-xs-3" style="height: 404px;">
        <div class="product-thumb transition">
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
                    <div class="product-name">
                        <a href="<?php print $product['href']; ?>"><?php print $product['name']; ?></a><br>
                        <b><?php print $product['manufacturer']; ?></b>
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
    </div>
    <?php } ?>
</div>
<div class="row">         
    <div class="col-xs-12 text-left ">
    <?php if ($pagination){
            print $pagination; 
        } ?>
    </div>
</div>        
    
    <?php } else { ?>
    <p><?php echo $text_empty; ?></p>
    <div class="buttons">
        <div class="pull-left"><a href="javascript: history.go(-1)" class="btn btn-default">Вернуться назад</a></div>
    </div>
    <?php } ?>
    
    <br>
    
    <?php echo $content_bottom; ?>  </div>
    <?php echo $column_right; ?></div>
  </div>
  <?php echo $footer; ?> 