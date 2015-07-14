<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="po_gallery">
    <?php foreach ($products as $product) { ?>
    	<div class="album po-gallery-font-<?php echo $font_size ?>" style="width:<?php echo $apr; ?>% ; height:<?php echo $alheight; ?>px;">
        	<div class="<?php echo $type; ?>-<?php echo $bgwidth; ?>">    
            	<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb'] ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a>
            </div>
            <a href="<?php echo $product['href']; ?>"><?php echo $product['name'] ?></a>
            <?php if($product['rating']) { ?>
            <div class="rating"><img src="catalog/view/theme/default/image/po-stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
            <?php } ?>
        </div>
    <?php } ?>
    </div>
  </div>
</div>
