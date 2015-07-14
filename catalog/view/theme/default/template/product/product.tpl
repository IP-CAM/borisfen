<?php print $header; ?>
<script src="/catalog/view/javascript/countdown.js" type="text/javascript"></script>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li>
            <a href="<?php print $breadcrumb['href']; ?>">
                <?php print $breadcrumb['text']; ?>
            </a>
        </li>
        <?php } ?>
    </ul>
    <span xmlns:v="http://rdf.data-vocabulary.org/#">
        <?php foreach ($mbreadcrumbs as $mbreadcrumb) { ?>
        <span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="<?php print $mbreadcrumb['href']; ?>"
           alt="<?php print $mbreadcrumb['text']; ?>"></a></span>
           <?php } ?>              
       </span>

       <span itemscope itemtype="http://schema.org/Product">
        <meta itemprop="url" content="<?php $mlink = end($breadcrumbs);
        print $mlink['href']; ?>">
        <meta itemprop="name" content="<?php print $heading_title; ?>">
        <meta itemprop="model" content="<?php print $model; ?>">
        <meta itemprop="manufacturer" content="<?php print $manufacturer; ?>">

        <?php if ($thumb) { ?>
        <meta itemprop="image" content="<?php print $thumb; ?>">
        <?php } ?>

        <?php if ($images) {
          foreach ($images as $image) {
              ?>
              <meta itemprop="image" content="<?php print $image['thumb']; ?>">
              <?php
          }
      } ?>

      <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
          <?php if ($this->config->get('config_display_product_price')) { ?>
          <meta itemprop="price" content="<?php print ($seo_special ? $seo_special : $seo_price); ?>"/>
          <?php } ?>
          <meta itemprop="priceCurrency" content="<?php print $this->currency->getCode(); ?>"/>
          <link itemprop="availability"
          href="http://schema.org/<?php print (($quantity > 0) ? "InStock" : "OutOfStock") ?>"/>
      </span>

      <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
          <meta itemprop="reviewCount" content="<?php print $review_no; ?>">
          <meta itemprop="ratingValue" content="<?php print $rating; ?>">
      </span>
  </span>

    <div class="row">
    <?php print $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-xs-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-xs-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-xs-12'; ?>
    <?php } ?>
    <div id="content" class="<?php print $class; ?>">
        <?php print $content_top; ?>
        <div class="row">
            <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-xs-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-xs-12'; ?>
            <?php } else { ?>
            <?php $class = 'col-xs-12'; ?>
            <?php } ?>
            <div class="<?php print $class; ?>">
                <div class="row">
                    <div class="col-xs-4 bigImg" id="bi-gallery-content">
<?php if ($thumb || $images) { ?>
                        <ul class="thumbnails bigImgUl">
                            <li class="image gallery">
                                <a class="thumbnail gallery gallery-element" href="<?php print $popup; ?>" title="<?php print $heading_title; ?>">
<?php if($special_end > 0):?>
    <span class="counter" id="counter_<?php print $product_id;?>"></span>
    <script>counter($('#counter_<?php print $product_id?>'), <?php print $special_end?>);</script>
<?php endif;?>
                                    <?php print $promo_tag_product_top_right; ?>
                                    <img src="<?php print $thumb; ?>" title="<?php print $heading_title; ?>" alt="<?php if ( !empty($custom_alt) ) { print $custom_alt; } else { print $heading_title; } ?> фото цена"/>
                                </a>
                            </li>
                        </ul>
                        <div id="addImgScroll" class="jcarousel-skin-opencart scroller">
                            <ul class="image-additional thumbnails">
                                <?php if ($images && (count($images) > 1)) { ?>
                                <?php foreach ($images as $image) { ?>
                                <?php if ($image_name == $image['name']) continue; ?>
                                <li class="image-additional gallery">
                                    <a class="thumbnail gallery gallery-element" href="<?php print $image['popup']; ?>"
                                       title="<?php print $heading_title; ?>">
                                       <img src="<?php print $image['thumb']; ?>" title="<?php print $heading_title; ?>"
                                       alt="<?php print $custom_alt; ?> фото цена"/>
                                   </a>
                               </li>
                               <?php } ?>
                               <?php } ?>
                            </ul>
                        </div>
<?php if (isset($images) && (count($images) > 4)) { ?>
    <script src="/catalog/view/javascript/jquery/jquery.jcarousel.min.js"></script>
<?php } ?>
</div>
<?php if (isset($images) && (count($images) > 3)) { ?><script>
    $(document).ready(function () {
        $('#addImgScroll ul').jcarousel({
            vertical: false,
            visible: 3,
            scroll: 1,
            auto: 5,
            wrap: 'circular'
        });
    });
</script><?php } ?>
<?php } ?>
        <div class="col-xs-8">
            <div class="row product-info">
                <div class="col-xs-7">
                    <h1><?php print $heading_title; ?></h1>
                    <div class="h-category"><?php //print $category['name']; ?></div>
                    <div class="list-quantity">
                        <?php if ($left_of_stock): ?>
                            <span class="LeftStock"><i class="fa fa-exclamation"></i> <?php print $left_of_stock; ?></span>
                        <?php endif; ?>
                        <?php if ($in_stock): ?>
                            <span class="InStock"><i class="fa fa-check"></i> <?php print $in_stock; ?></span>
                        <?php endif; ?>
                        <?php if ($out_of_stock): ?>
                            <span class="OutOfStock"><i class="fa fa-times"></i> <?php print $out_of_stock; ?></span>
                        <?php endif; ?>
                    </div>
                    <button type="button" onclick="addToWishList('<?php print $product_id; ?>');" class="addToWishList"><span>Добавить в желаемое</span><i class="fa fa-heart"></i></button>
                    <?php if ($price && $this->config->get('config_display_product_price')) { ?>
                    <ul class="list-unstyled priceW">
                        <?php if (!$special) { ?>
                        <li class="price"><span><?php print $price; ?></span></li>
                        <?php } else { ?>
                        <li class="price"><span class="price-new"><?php print $special; ?></span><span class="price-old"><?php print $price; ?></span></li>
                        <?php } ?>
                    </ul>
                    <?php
                        if($quantity == 0){ echo '<span id="redprice">Цену уточняйте у менеджера</span>'; }
                    ?>
                    <?php } ?>
                <div id="product">
                    <?php print $product_option_tpl; ?>
                    <?php print $product_series_tpl; ?>
                    <div class="quantity-cartBut">
                        <div class="quantity">
                            <label><?php print $text_qty; ?></label>
                            <span class="minus"><i class="fa fa-minus"></i></span>
                            <input class="form-control quantityInput" type="text" name="quantity" size="2" value="<?php print $minimum; ?>" />
                            <span class="plus"><i class="fa fa-plus"></i></span>
                            <input type="hidden" name="product_id" size="2" value="<?php print $product_id; ?>"/>
                        </div>
                        <div class="cartBut">
                            <button class="buyButton <?php if ((!$this->config->get('config_stock_checkout')) && ($quantity < 1)) { ?>disabled<?php } ?>" type="button" <?php if (!((!$this->config->get('config_stock_checkout')) && ($quantity < 1))) { ?> id="button-cart" <?php } ?> >
                                <span>Купить</span>
                            </button>
                        </div>
                    </div>
                    <div class="fastOrder-box">
                        <button id="fast-order-button" class="fastOrder" type="button">Быстрый заказ</button>
                        <?php if($quantity > 0){ ?>
                            <script>
                                $(document).on('click', '#fast-order-button', function () {
                                    var quantity = $('.quantity input[name=quantity]').val();
                                    if (!quantity) quantity = 1;
                                    jv_qiuckorder_show('<?php print $product_id; ?>', quantity);
                                });
                            </script>
                        <?php } ?>
                    </div>
                </div>
                    <div class="whole-saleBox hidden">
                        <span>При покупке свыше <b><?php echo $this->config->get('config_whole_sale_price_quantity'); ?></b> единица товара, либо общей суммы заказа от <b><?php echo $this->config->get('config_whole_sale_price_price'); ?> грн.</b>, цена за единицу товара будет составлять: <b><?php print $whole_sale_price; ?></b>.</span>
                    </div>
                </div>
                <div class="col-xs-5">
                    <ul class="list-unstyled characteristics">
                        <?php if (isset($attribute_groups)) { ?>
                        <?php foreach ($attribute_groups as $attribute_group) { ?>
                        <?php foreach ($attribute_group['attribute'] as $attribute) {
									if($attribute['attribute_id'] == 23) continue;?>
                        <li><span><?php print $attribute['name']; ?></span><?php print $attribute['text']; ?></li>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                        <!--<li><span>Вес:</span> <?php print $weight; ?> кг.</li>-->
                        <?php if ( !empty($sku) ) {?>
                        <li><span>Номер товара:</span> <?php print $sku; ?></li>
                        <?php } ?>
                        <?php if ($manufacturer && false) { ?>
                        <li><span><?php print $text_manufacturer; ?></span> <a href="<?php print $manufacturers; ?>"><?php print $manufacturer; ?></a></li>
                        <?php } ?>
                        <li><span>Рейтинг:</span> <?php R_stars::show('rating', $rating, false, $this->request->get['product_id']); ?></li>
                        <li><span>Рассказать друзьям:</span> 
                            <div class="socHolder">
                                <div class="share42init" data-title="<?php print $heading_title; ?>" data-description="<?php print strip_tags(html_entity_decode($description)); ?>" data-path="/catalog/view/theme/default/image/share" data-image="<?php print $popup; ?>"></div>
                                <script type="text/javascript" src="/catalog/view/javascript/share42.js"></script>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
                </div>
                <div class="description-product">
                    <div class="h2">Описание:</div>
                    <?php print $description; ?>
                </div>
            </div>
        </div>
        <?php print $product_related_tpl; ?>
        <div class="row tam-gde-bottom">
            <div class="col-xs-4">
                <?php print $content_bottom; ?>
            </div>
            <div class="col-xs-8">
                <?php print $product_review_tpl; ?>
            </div>
        </div>
        <div class="tabsW hide">
    <?php if ($tags) { ?>
            <p><?php print $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
            <?php if ($i < (count($tags) - 1)) { ?>
                <a href="<?php print $tags[$i]['href']; ?>"><?php print $tags[$i]['tag']; ?></a>,
            <?php } else { ?>
                <a href="<?php print $tags[$i]['href']; ?>"><?php print $tags[$i]['tag']; ?></a>
            <?php } ?>
        <?php } ?>
            </p>
    <?php } ?>
        </div>
    </div>
        <?php print $column_right; ?>
    </div>
</div>
        <script type="text/javascript"><!--
            $('select[name="profile_id"], input[name="quantity"]').change(function () {
                $.ajax({
                    url: 'index.php?route=product/product/getRecurringDescription',
                    type: 'post',
                    data: $('input[name="product_id"], input[name="quantity"], select[name="profile_id"]'),
                    dataType: 'json',
                    beforeSend: function () {
                        $('#profile-description').html('');
                    },
                    success: function (json) {
                        $('.success, .warning, .attention, information, .error').remove();

                        if (json['success']) {
                            $('#profile-description').html(json['success']);
                        }
                    }
                });
            });
            //--></script>

            <script type="text/javascript"><!--


                $('#button-cart').click(function () {
                    var data = $('.product-info input[type=text], .product-info input[type=hidden], .product-info input[type=radio]:checked, .product-info input[type=checkbox]:checked, .product-info select, .product-info textarea');
                    console.log(data);
                    $.ajax({
                        url: 'index.php?route=checkout/cart/add',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function (json) {
                            $('.alert, .text-danger').remove();

                            if (json['error']) {
                                if (json['error']['option']) {
                                    for (i in json['error']['option']) {
                                        $('#option-' + i).after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                                    }
                                }
                                if (json['error']['profile']) {
                                    $('select[name="profile_id"]').after('<span class="text-danger">' + json['error']['profile'] + '</span>');
                                }
                            }

                            if (json['success']) {
//                 $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

$('#cart-total').html(json['total']);

alert(json['success']);
//                 $('html, body').animate({ scrollTop: 0 }, 'slow'); 
}
}
});
});
//--></script>
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
    new AjaxUpload('#button-option-<?php print $option['product_option_id']; ?>', {
        action: 'index.php?route=product/product/upload',
        name: 'file',
        autoSubmit: true,
        responseType: 'json',
        onSubmit: function (file, extension) {
            $('#button-option-<?php print $option['product_option_id']; ?>').after('<img src="catalog/view/theme/<?php print $this->config->get('config_template'); ?>/image/loading.gif" class="loading" style="padding-left: 5px;" />');
            $('#button-option-<?php print $option['product_option_id']; ?>').prop('disabled', true);
        },
        onComplete: function (file, json) {
            $('#button-option-<?php print $option['product_option_id']; ?>').prop('disabled', false);

            $('.text-danger').remove();

            if (json['success']) {
                alert(json['success']);

                $('input[name=\'option[<?php print $option['product_option_id']; ?>]\']').prop('value', json['file']);
            }

            if (json['error']) {
                $('#option-<?php print $option['product_option_id']; ?>').after('<div class="text-danger">' + json['error'] + '</div>');
            }

            $('.loading').remove();
        }
    });
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>



<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <ol class="indicator"></ol>
    <div onclick="$('#blueimp-gallery .close').trigger('click');" class="light-holder"></div>
</div>
<script type="text/javascript">
    $(document).on('click', 'a.gallery-element', function (event) {
        event = event || window.event;
        var target = event.target || event.srcElement;
        var link = target.src ? target.parentNode : target;
        var options = {
            index: link,
            event: event,
            fullScreen: false,
            thumbnailIndicators: true,
            carousel: false,
            startSlideshow: false,
            closeOnSwipeUpOrDown: true
        }
        var unique_links = [];
        var links = document.querySelectorAll('a.gallery-element');
        $(links).each(function () {
            var _this = this
            var exists = false;
            $(unique_links).each(function () {
                if ($(_this).attr('href') == $(this).attr('href')) {
                    exists = true;
                }
            });
            if (!exists) {
                unique_links.push(this);
            }
        });
        blueimp.Gallery(unique_links, options);
    });
</script>
<?php print $footer; ?>
