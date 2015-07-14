<?php echo $header; ?>
<div class="container blog-page-category">
    <div class="row">
        <?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $cols = 6; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $cols = 9; ?>
        <?php } else { ?>
        <?php $cols = 12; ?>
        <?php } ?>
        <div class="col-xs-<?php echo $cols; ?> ">
            <div id="content" class="blog-page">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <h1><span><?php echo $heading_title; ?></span></h1>
                
                <div class="pav-filter-blogs">
                    <div class="pav-blogs">
                        <div class="leading-blogs clearfix">
                            <?php if ( empty($secondary_blogs) && !empty($this->request->get['id']) && $this->request->get['id'] == '26' ) { echo 'Записи в категории, не найдены.'; }?>
                            <?php foreach( $secondary_blogs as $key => $blog ) { $key = $key + 1;?>
                            <div class="pavcol1">
                                <?php require( '_item.tpl' ); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if ( $pagination ) { ?>
                        <div class="pav-pagination pagination"><?php echo $pagination;?></div>
                        <?php } ?>
                    </div>
                </div>
                
                <?php if (!empty($products)) { ?>
                    <div class="pav-comment">
                        <div class="heading"><span><?php echo $this->language->get('products_box'); ?></span></div>
                    </div>
                    <div class="row" id="contentRow">
                        <?php foreach ($products as $product) { ?>
                            <div class="product-layout product-grid col-xs-3" style="height: 402px;">
                                <div class="product-thumb transition">
                                    <div class="image">
                                        <button type="button" data-toggle="tooltip" title="В cписок желаемого" onclick="addToWishList('<?php print $product['product_id']; ?>');"
                                                class="addToWishList">
                                            <i class="fa fa-heart"></i>
                                        </button>
                                        <a href="<?php print $product['href']; ?>">
                                            <?php echo $product['promo_tag_top_right']; ?>
                                            <?php echo $product['promo_tag_top_left']; ?>
                                            <?php echo $product['promo_tag_bottom_left']; ?>
                                            <?php echo $product['promo_tag_bottom_right']; ?>
                                            <img src="<?php print $product['thumb']; ?>" alt="<?php print $product['name']; ?>"
                                                 title="<?php print $product['name']; ?>" class="img-responsive"/>
                                        </a>
                                    </div>
                                    <div class="caption">
                                        <div class="list">
                                            <div class="left">
                                                <div class="product-name"><a
                                                        href="<?php print $product['href']; ?>"><?php print $product['name']; ?></a><br>
                                                    <?php if(!empty($product['category'])) { ?>
                                                        <a href="<?php print $product['category']['url']; ?>"><b><?php print $product['category']['name']; ?></b></a>
                                                    <?php } ?>
                                                </div>
                                                <?php if ($this->config->get('config_short_category_description')) { ?>
                                                    <div class="description"><?php print $product['short_description']; ?></div>
                                                <?php } ?>
                                                <div class="rating">
                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                        <?php if ($product['rating'] < $i) { ?>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star-o fa-stack-2x"></i></span>
                                                        <?php } else { ?>
                                                            <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i
                                                                    class="fa fa-star-o fa-stack-2x"></i></span>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                                <?php if ($product['price']
                                                          && $this->config->get('config_display_product_price')
                                                ) { ?>
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
                                                    <a onclick="jv_qiuckorder_show('<?php print $product['product_id']; ?>', $(this).parents('.right').find('input[name=quantity]').first().val());void(0);"
                                                       class="fastorder">Быстрый заказ</a>
                                                    <button type="button"
                                                        <?php
                                                            if (!((!$this->config->get('config_stock_checkout'))
                                                                  && ($product['quantity'] < 1))
                                                            ) {
                                                                ?>
                                                                onclick="addToCart('<?php print $product['product_id']; ?>');"
                                                            <?php } ?>
                                                            class="
                                    addToCart
                                    <?php if ($product['out_of_stock']) { ?> disabled <?php } ?>
                                    <?php if ($product['in_cart']) { ?> in-cart <?php } ?>
                                ">
                                                        <i class="fa fa-shopping-cart"></i>
                                                <span product-id="<?php print $product['product_id']; ?>"
                                                      class="add-text hidden-xs hidden-sm hidden-md"><?php print $product['in_cart'] ?
                                                        $this->language->get('button_in_cart') :
                                                        $this->language->get('button_cart'); ?></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                
                <?php if( !empty($this->request->get['id']) && $this->request->get['id'] != '26' ) { echo $content_top; } ?>
                
                <?php echo $content_bottom; ?>
            </div>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>
<?php echo $footer; ?> 