<?php echo $header; ?>
<div class="container blog-page-full">
<div class="row">
<?php echo $column_left; ?>
<?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
<?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
<?php } else { ?>
    <?php $cols = 12; ?>
<?php } ?>
<div id="content" class="col-xs-<?php echo $cols; ?>">
<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
</ul>
<?php echo $content_top; ?>
<h1><span><?php echo $heading_title; ?></span></h1>

<div class="col-xs-12">
<div class="row">
<div class="pav-blog">
    <div class="description"><?php echo $description; ?></div>

    <div class="blog-content">
        <?php if ($config->get('blog_show_created')) { ?>
            <div><span class="created"><span><?php echo $blog['created']; ?></span></span></div>
        <?php } ?>

        <?php if ($blog['thumb_large']) { ?>
            <img src="<?php echo $blog['thumb_large']; ?>" title="<?php echo $blog['title']; ?>" class="image"/>
        <?php } ?>

        <?php echo $content; ?>
        <?php if ($blog['video_code']) { ?>
            <div class="pav-video"><?php echo html_entity_decode($blog['video_code'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
    </div>
</div>

<div class="pav-comment">
    <?php if ($config->get('blog_show_comment_form')) { ?>
        <?php if ($config->get('comment_engine') == 'diquis') { ?>
            <div id="disqus_thread"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = '<?php echo $config->get('diquis_account');?>'; // required: replace example with your forum shortname

                /* * * DON'T EDIT BELOW THIS LINE * * */
                (function () {
                    var dsq = document.createElement('script');
                    dsq.type = 'text/javascript';
                    dsq.async = true;
                    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by
                    Disqus.</a></noscript>
            <a href="http://disqus.com" class="dsq-brlink">comments powered by <span
                    class="logo-disqus">Disqus</span></a>
        <?php } elseif ($config->get('comment_engine') == 'facebook') { ?>
            <div id="fb-root"></div>
            <script>(function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {
                        return;
                    }
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $config->get("facebook_appid");?>";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-comments" data-href="<?php echo $link; ?>"
                 data-num-posts="<?php echo $config->get("comment_limit"); ?>"
                 data-width="<?php echo $config->get("facebook_width") ?>">
            </div>
        <?php } else { ?>

        <?php if (count($comments)) { ?>
            <div class="heading"><span><?php echo $this->language->get('text_list_comments'); ?></span></div>
            <div class="pave-listcomments">
                <?php foreach ($comments as $comment) {
                    $default = ''; ?>
                    <div class="comment-item clearfix" id="comment<?php echo $comment['comment_id']; ?>">

                        <img src="<?php echo
                            "http://www.gravatar.com/avatar/" . md5(strtolower(trim($comment['email']))) . "?d="
                            . urlencode($default) . "&s=60" ?>" align="left"/>

                        <div class="comment-wrap">
                            <div class="comment-meta">
                                <span class="comment-postedby"><span><?php echo $comment['user']; ?></span>,</span>
                                <span class="comment-created"><span><?php echo $comment['created']; ?></span></span>
                            </div>
                            <?php echo $comment['comment']; ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="pagination">
                    <?php echo $pagination; ?>
                </div>
            </div>
        <?php } ?>

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

            <div class="heading"><span><?php echo $this->language->get("text_leave_a_comment"); ?></span></div>
            <form action="<?php echo $comment_action; ?>" method="post" id="comment-form">
                <fieldset class="row">
                    <div class="message" style="display:none"></div>
                    <div class="form-group required row">
                        <label class="col-xs-3 control-label text-right"
                               for="comment-user"><?php echo $this->language->get('entry_name'); ?></label>

                        <div class="col-xs-7"><input class="form-control" type="text" name="comment[user]" value=""
                                                     id="comment-user"/></div>
                    </div>
                    <div class="form-group required row">
                        <label class="col-xs-3 control-label text-right"
                               for="comment-email"><?php echo $this->language->get('entry_email'); ?></label>

                        <div class="col-xs-7"><input class="form-control" type="text" name="comment[email]" value=""
                                                     id="comment-email"/></div>
                    </div>
                    <div class="form-group required row">
                        <label class="col-xs-3 control-label text-right"
                               for="comment-comment"><?php echo $this->language->get('entry_comment'); ?></label>

                        <div class="col-xs-7"><textarea class="form-control" name="comment[comment]"
                                                        id="comment-comment"></textarea></div>
                    </div>
                    <?php if ($config->get('enable_recaptcha')) { ?>
                        <div class="form-group required  row">
                            <label class="col-xs-3 control-label text-right"><?php echo $entry_captcha; ?></label>

                            <div class="col-xs-2"><input type="text" class="form-control" name="captcha"
                                                         value="<?php echo $captcha; ?>" size="10"/></div>
                            <div class="col-xs-3 captcha"><img src="index.php?route=pavblog/blog/captcha" alt=""
                                                               id='captcha'/></div>
                            <div class="col-xs-2 p">
                                <button class="btn btn-primary pull-right" type="submit">
                                    <span><?php echo $this->language->get('text_submit'); ?></span>
                                </button>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $('#captcha').before('<img src="/catalog/view/theme/default/image/refresh.png" id="ref" />');
                                $('#ref').click(function () {
                                    $('#captcha').attr('src', 'index.php?route=product/product/captcha&rand=' + Math.round((Math.random() * 10000 )));
                                });
                            });
                        </script>
                    <?php } ?>
                    <input type="hidden" name="comment[blog_id]" value="<?php echo $blog['blog_id']; ?>"/>
                </fieldset>
            </form>
            <script type="text/javascript">
                $("#comment-form .message").hide();
                $("#comment-form").submit(function () {
                    $.ajax({
                        type: "POST",
                        url: $("#comment-form").attr("action"),
                        data: $("#comment-form").serialize(),
                        dataType: "json",
                    }).done(function (data) {
                        if (data.hasError) {
                            $("#comment-form .message").html(data.message).show();
                        } else {
                            location.href = '<?php echo str_replace("&amp;","&",$link);?>';
                        }
                    });
                    return false;
                });

            </script>
        <?php } ?>
    <?php } ?>
</div>
</div>


<?php echo $content_bottom; ?>
</div>

<div class="col-xs-3 hide">
    <div class="pav-blog">
        <div class="blog-bottom">
            <?php if (!empty($samecategory)) { ?>
                <div class="pavcol2">
                    <div class="title"><span><?php echo $this->language->get('text_in_same_category'); ?></span></div>
                    <ul class="">
                        <?php foreach ($samecategory as $item) { ?>
                            <li><a href="<?php echo $this->url->link('pavblog/blog', "id=" . $item['blog_id']); ?>"><i
                                        class="fa fa-angle-right"></i> <?php echo $item['title']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>
<?php echo $column_right; ?>
</div>
</div>
<?php echo $footer; ?> 