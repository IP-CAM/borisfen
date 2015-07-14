<?php if ($review_status) { ?>
    <div id="tab-review">
        <div class="box-heading"><?php echo $comments_on_product; ?></div>
        <form class="form-horizontal">
            <div id="review-form">
                <fieldset class="row" id="toggleR">
                    <div class="form-group col-xs-12 required">
                        <label class="col-xs-2 control-label" for="firstname"><?php print $entry_name; ?>:</label>
                        <div class="col-xs-10">
                            <input type="text" name="name" value="" class="form-control ">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 required">
                        <label class="col-xs-2 control-label" for="firstname"><?php print $entry_rating; ?>:</label>
                        <div class="col-xs-10">
                            <?php R_stars::show(); ?>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 required">
                        <label class="col-xs-2 control-label" for="firstname">Ваш отзыв:</label>
                        <div class="col-xs-10">
                            <textarea class="form-control" name="text"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="addimage" value=""/>
                    <script>
                        $(document).ready(function () {
                            $('#ref').click(function () {
                                $('#captcha').attr('src', 'index.php?route=product/product/captcha&rand=' + Math.round((Math.random() * 10000 )));
                            });
                        });
                    </script>
                    <script src = "catalog/controller/voting/voting.js" type = "text/javascript" ></script>
                    <div class="form-group col-xs-12">
                        <div class="col-xs-10 pull-right text-right">
                            <a id="button-review" class="btn btn-primary"><?php print $button_continue; ?></a>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="review" >
                <?php if ($reviews_array) { ?>
                    <?php foreach ($reviews_array as $review) { ?>
                        <div class="review-list">
                            <div class="author">
                                <span class="rating">
                                    <?php R_stars::show('rating', $review['rating'], true); ?>
                                </span>
                                <b><?php print $review['author']; ?></b>
                                <span class="pull-right date"><?php print $review['date_added']; ?></span>
                            </div>
                            <?php if ($review['text']) { ?>
                                <div class="text"><?php print preg_replace('/\s\s+/', "<br>", $review['text']); ?></div>
                                <?php if ($review['answer']) { ?>
                                    <div class="answer">
                                        <div class="answer-box"><?php print '<b>' . $text_answer . '</b> ' . preg_replace('/\s\s+/', "<br>", $review['answer']); ?></div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-xs-12 text-left"><?php print $pagination; ?></div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-xs-12"><?php print $text_no_reviews; ?></div>
                    </div>
                <?php } ?>
            </div>
        </form>
    </div>
<?php } ?>
<script type="text/javascript"><!--
    $('#button-review').bind('click', function () {
        $.ajax({
            url: '/index.php?route=product/product/write&product_id=<?php print $product_id; ?>',
            type: 'post',
            dataType: 'json',
            data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&addimage=' + encodeURIComponent($('input[name=\'addimage\']').val()) + '&good=' + encodeURIComponent($('textarea[name=\'good\']').val()) + '&bads=' + encodeURIComponent($('textarea[name=\'bads\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=rating]:checked').val() ? $('input[name=rating]:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
            beforeSend: function () {
                $('.alert-success, .alert-warning').remove();
                $('#button-review').prop('disabled', true);
                $('#review-title').after('<div class="attention"><img src="catalog/view/theme/<?php print $this->config->get('config_template'); ?>/image/loading.gif" alt="" /> <?php print $text_wait; ?></div>');
            },
            complete: function () {
                $('#button-review').prop('disabled', false);
                $('.attention').remove();
            },
            success: function (data) {
                if (data['error']) {
                    alert(data['error']);
                }
                if (data['success']) {
                    alert(data['success']);
                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'good\']').val('');
                    $('textarea[name=\'bads\']').val('');
                    $('input[name=\'addimage\']').val('');
                    $('textarea[name=\'text\']').val('');

                    $('input[name=\'captcha\']').val('');
                }
            }
        });
    });
    //--></script>