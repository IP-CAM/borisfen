<div class="flexible-slideshow">
	<div id="flexSlider-<?php echo $module; ?>" class="flexslider">
		<ul class="flex-slides">
      <?php foreach ($banners as $banner) { ?>
      <li>
        <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>"><img
					src="<?php echo $banner['image']; ?>"
					alt="<?php echo $banner['title']; ?>" /></a>
        <?php } else { ?>
        <img src="<?php echo $banner['image']; ?>"
				alt="<?php echo $banner['title']; ?>" />
        <?php } ?>
        <?php if ($caption) { ?>
        <div class="flex-caption <?php echo $caption_position; ?>">
					<h2><?php echo $banner['title']; ?></h2>
					<p><?php echo $banner['description']; ?></p>
          <?php if ($caption_button) { ?>
          <a href="<?php echo $banner['link']; ?>" class="button"><span><?php echo $caption_button_text; ?></span></a>
          <?php } ?>
        </div>
        <?php } ?>
      </li>
      <?php } ?>
    </ul>
	</div>
  <?php if ($thumbnails) { ?>
  <div id="flexCarousel-<?php echo $module; ?>"
		class="flexCarousel flexslider">
		<ul class="flex-slides">
      <?php foreach ($banners as $banner) { ?>
      <li><img src="<?php echo $banner['thumb']; ?>"
				alt="<?php echo $banner['title']; ?>" /></li>
      <?php } ?>
    </ul>
	</div>
	<script type="text/javascript"><!--
  $(window).load(function() {
    $('#flexCarousel-<?php echo $module; ?>').flexslider({
      selector: ".flex-slides > li",
      slideshow: false,
      animation: "slide",
      directionNav: <?php echo $direction_nav; ?>,
      controlNav: false,
      itemWidth: <?php echo $item_width; ?>,
      maxItems: <?php echo $max_items; ?>,
      minItems: <?php echo $min_items; ?>,
      asNavFor: '#flexSlider-<?php echo $module; ?>'
    });

    $('#flexSlider-<?php echo $module; ?>').flexslider({
      selector: ".flex-slides > li",
      slideshow: <?php echo $slideshow; ?>,
      animation: "<?php echo $animation; ?>",
      direction: "<?php echo $direction; ?>",
      slideshowSpeed: <?php echo $speed; ?>,
      animationSpeed: <?php echo $duration; ?>,
      pauseOnHover: <?php echo $pause; ?>,
      directionNav: <?php echo $direction_nav; ?>,
      controlNav: false,
      touch: <?php echo $touch; ?>,
      mousewheel: <?php echo $mousewheel; ?>,
      sync: "#flexCarousel-<?php echo $module; ?>"
    });
  });
  --></script>
  <?php } else { ?>
  <script type="text/javascript"><!--
  $(window).load(function() {
    $('#flexSlider-<?php echo $module; ?>').flexslider({
      selector: ".flex-slides > li",
      slideshow: <?php echo $slideshow; ?>,
      animation: "<?php echo $animation; ?>",
      direction: "<?php echo $direction; ?>",
      slideshowSpeed: <?php echo $speed; ?>,
      animationSpeed: <?php echo $duration; ?>,
      pauseOnHover: <?php echo $pause; ?>,
      directionNav: <?php echo $direction_nav; ?>,
      controlNav: <?php echo $control_nav; ?>,
      touch: <?php echo $touch; ?>,
      mousewheel: <?php echo $mousewheel; ?>
    });
  });
  --></script>
  <?php } ?>
</div>
