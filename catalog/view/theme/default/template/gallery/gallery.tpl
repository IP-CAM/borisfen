<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  
  <?php if ($albums) { ?>
  <div class="po_gallery">
  
  <h1>Рестораны</h1>
  


  	<?php foreach ($albums as $album) { ?>
        <div class="album po-gallery-font-<?php echo $font_size ?>" style="width:<?php echo $apr; ?>% ; height:<?php echo $alheight; ?>px;">
        	<div class="<?php echo $type; ?>-<?php echo $bgwidth ?>">    
            	<a href="<?php echo $album['href']; ?>"><img src="<?php echo $album['thumb'] ?>" title="<?php echo $album['name']; ?>" alt="<?php echo $album['name']; ?>" /></a>
            </div>
            <a href="<?php echo $album['href']; ?>"><?php echo $album['name'] ?></a>
            <?php if($album['rating']) { ?>
            	<div class="rating"><img src="catalog/view/theme/default/image/po-stars-<?php echo $album['rating']; ?>.png" alt="<?php echo $album['reviews']; ?>" /></div>
            <?php } ?>
        </div>
    <?php } ?>
    
    <div class="pagination"><?php echo $pagination; ?></div>
        
  </div>
  <?php } ?>
  
  <?php echo $content_bottom; ?></div>

<?php echo $footer; ?>