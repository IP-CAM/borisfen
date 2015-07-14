<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  
  <div class="po_gallery">
  <h1><?php echo $heading_title; ?></h1>
  
  <div class="album-info">
         <div style="margin-bottom: 15px;">
            <?php echo $description; ?>
        </div>
  	<!--<div class="left">
    	
        <?php if ($thumb) { ?>
        
        <div class="image <?php echo $type; ?>"><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox" rel="colorbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>
        
        <?php } ?>
        
          
    </div>-->
    
    <div class="right">
<?php if ($images) { ?>
          <div class="image-additional">
            <?php foreach ($images as $image) { ?>
            <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" onclick="return hs.expand(this, galleryOptions)" class="highslide thumbnail"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
          </div>
          <?php } ?>
      
    </div>
  </div>
    
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('.colorbox').colorbox({
	overlayClose: true,
	opacity: 0.5
});
//--></script> 
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').slideUp('slow');
		
	$('#review').load(this.href);
	
	$('#review').slideDown('slow');
	
	return false;
});			

$('#review').load('index.php?route=gallery/album/review&album_id=<?php echo $album_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=gallery/album/write&album_id=<?php echo $album_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#review-title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#review-title').after('<div class="success">' + data.success + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<script src="<?php print SCRIPT_FOLDER; ?>highslide/highslide-full.js"></script>
                  <script type="text/javascript">
                  hs.graphicsDir = '/catalog/view/theme/default/image/highslide/';
                  hs.showCredits = 0;
                  hs.padToMinWidth = true;
                              
                  var galleryOptions = {
                  	slideshowGroup: 'gallery',
                  	wrapperClassName: 'dark',
                  	//outlineType: 'glossy-dark',
                  	dimmingOpacity: 0.8,
                  	align: 'center',
                  	transitions: ['expand', 'crossfade'],
                  	fadeInOut: true,
                  	wrapperClassName: 'borderless floating-caption',
                  	marginLeft: 100,
                  	marginBottom: 80,
                  	numberPosition: 'caption'
                  };
                  if (hs.addSlideshow) hs.addSlideshow({
                      slideshowGroup: 'gallery',
                      interval: 5000,
                      repeat: false,
                      useControls: false,
                      overlayOptions: {
                      	className: 'text-controls',
                  		position: 'bottom center',
                  		relativeTo: 'viewport',
                  		offsetY: -60
                  	},
                  	thumbstrip: {
                  		position: 'bottom center',
                  		mode: 'horizontal',
                  		relativeTo: 'viewport'
                  	}

                  });
                  hs.Expander.prototype.onInit = function() {
                  	hs.marginBottom = (this.slideshowGroup == 'gallery') ? 150 : 15;
                  }

                  // focus the name field
                  hs.Expander.prototype.onAfterExpand = function() {

                  	if (this.a.id == 'contactAnchor') {
                  		var iframe = window.frames[this.iframe.name],
                  			doc = iframe.document;
                      	if (doc.getElementById("theForm")) {
                          	doc.getElementById("theForm").elements["name"].focus();
                      	}

                  	}
                  }
                  </script>
<?php echo $footer; ?>