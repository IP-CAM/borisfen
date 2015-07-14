<div id="banner<?php echo $module; ?>" class="box banner">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>" class="item"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
    <?php } else { ?>
        <span class="item"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" class="img-responsive" /></span>
    <?php } ?>
    <?php } ?>
</div>
<script>
    $(document).ready(function() {
        $("#banner0").owlCarousel({
            autoPlay: 4000, //Set AutoPlay to 3 seconds
            items : 1,
            pagination: true,
            navigation: false
        });
    });
</script>