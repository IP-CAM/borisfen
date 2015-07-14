<div class="box">
    <div class="heading">
        <span><?php echo $testimonial_title; ?></span>
    </div>
    <div class="box-content testimonial">
        <div class="box-product">
            <?php foreach ($testimonials as $testimonial) { ?>
            <div class="row">
                <div class="container">
                    <div class="col-xs-10">
                        <div class="name">
                            <b><?php echo $testimonial['title']; ?></b>
                        </div>
                        <div class="description">
                            <?php echo $testimonial['description'] ; ?>
                        </div>
                    </div>
                    <div class="col-xs-2 text-right">
                        <?php if ($testimonial['rating']) { ?>
                        <img src="catalog/view/theme/default/image/testimonials/stars-<?php echo $testimonial['rating'] . '.png'; ?>" style="margin-top: 2px;" />
                        <?php } ?>
                        <div class="author">
                            <?php if ($testimonial['name']!="") echo $testimonial['name']; else echo $testimonial['name']; ?>
                            <?php echo $testimonial['city']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="col-xs-6"></div>
            <div class="col-xs-6 underT text-right">
                    <a href="<?php echo $showall_url;?>" class="btn-primary"><?php echo $show_all; ?></a> <a href="<?php echo $isitesti; ?>" class="btn-primary"><?php echo $isi_testimonial; ?></a>
            </div>

        </div>
    </div>
</div>

