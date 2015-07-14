<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
    <?php } else { ?>
    <?php $cols = 12; ?>
    <?php } ?>
    <div id="content" class="col-xs-<?php echo $cols; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="search-content"> 
        <div class="form-inline search-inline row" role="form">
          <div class="form-group col-xs-3">
            <?php if ($search) { ?>
            <input class="form-control default" type="text" name="search" value="<?php echo $search; ?>" />
            <?php } else { ?>
            <input class="form-control" type="text" name="search" value="<?php echo $search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
            <?php } ?>
          </div>
          <div class="form-group col-xs-3">

            <select name="category_id" class="form-control" data-size="10">
              <option value="0"><?php echo $text_category; ?></option>
              <?php foreach ($categories as $category_1) { ?>
              <?php if ($category_1['category_id'] == $category_id) { ?>
              <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
              <?php } ?>
              <?php foreach ($category_1['children'] as $category_2) { ?>
              <?php if ($category_2['category_id'] == $category_id) { ?>
              <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
              <?php } ?>
              <?php foreach ($category_2['children'] as $category_3) { ?>
              <?php if ($category_3['category_id'] == $category_id) { ?>
              <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
              <?php } ?>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
          <div class="buttons col-xs-3">
            <input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary" /> 
          </div>
        </div>
        <div class="form-inline search-inline row">
          <div class="col-xs-4">
            <label class="checkbox" for="sub_category">
              <?php if ($sub_category) { ?>
              <input type="checkbox" name="sub_category" value="1" id="sub_category" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="sub_category" value="1" id="sub_category" />
              <?php } ?>
              <?php echo $text_sub_category; ?> 
            </label>
          </div>
          <div class="col-xs-4">
            <label class="checkbox" for="description">
              <?php if ($description) { ?>
              <input type="checkbox" name="description" value="1" id="description" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="description" value="1" id="description" />
              <?php } ?>
              <?php echo $entry_description; ?>
            </label>
          </div>
        </div>
      </div>
      <?php if ($products) { ?>
      <p id="compare-total"><a href="<?php echo $compare; ?>"> <?php echo $text_compare; ?></a></p>
      
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
    <div class="product-layout product-grid col-xs-3" style="height: 372px;">
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
                    <div class="product-name"><a href="<?php print $product['href']; ?>"><?php print $product['name']; ?></a><br>
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
    <?php } ?>
</div>

      
      <div class="row">         
        <div class="col-xs-12 text-left ">
          <?php 
          if ($pagination){
            print $pagination; 
          }
          ?>
        </div>
      </div>   

      
      
      <?php } else { ?>
      <div class="alert alert-danger"><button type="button" onclick="history.go(-1);" class="close" data-dismiss="alert">×</button><?php echo $text_empty; ?></div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
      <?php echo $column_right; ?></div>
    </div>
    <?php echo $footer; ?> 
    <script type="text/javascript"><!--
      $('#button-search').bind('click', function() {
       url = 'index.php?route=product/search';
       
       var search = $('#content input[name=\'search\']').prop('value');
       
       if (search) {
        url += '&search=' + encodeURIComponent(search);
      }

      var category_id = $('#content select[name=\'category_id\']').prop('value');
      
      if (category_id > 0) {
        url += '&category_id=' + encodeURIComponent(category_id);
      }
      
      var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');
      
      if (sub_category) {
        url += '&sub_category=true';
      }
      
      var filter_description = $('#content input[name=\'description\']:checked').prop('value');
      
      if (filter_description) {
        url += '&description=true';
      }

      location = url;
    });

      $('#content input[name=\'search\']').bind('keydown', function(e) {
       if (e.keyCode == 13) {
        $('#button-search').trigger('click');
      }
    });

      $('select[name=\'category_id\']').bind('change', function() {
       if (this.value == '0') {
        $('input[name=\'sub_category\']').prop('disabled', true);
      } else {
        $('input[name=\'sub_category\']').prop('disabled', false);
      }
    });

      $('select[name=\'category_id\']').trigger('change');
    --></script>