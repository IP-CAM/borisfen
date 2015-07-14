<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
    <?php } else { ?>
    <?php $cols = 12; ?>
    <?php } ?>  
    <div class="col-xs-<?php echo $cols; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <p><?php echo $text_description; ?></p>
      <form class="form-horizontal">
        <div class="form-group">
          <label class="col-xs-2 control-label" for="input-code"><?php echo $text_code; ?></label>
          <div class="col-xs-10">
            <textarea cols="40" rows="5" placeholder="" id="input-code" class="form-control"><?php echo $code; ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label" for="input-generator"><?php echo $text_generator; ?></label>
          <div class="col-xs-10">
            <input type="text" name="product" value="" placeholder="" id="input-generator" class="form-control" />
            </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label" for="input-link"><?php echo $text_link; ?></label>
          <div class="col-xs-10">
		  <div id="featured-product">
            <textarea  type="hidden" name="link" cols="40" rows="5"  id="input-link" class="form-control"></textarea>
			</div>
          </div>
        </div>
      </form>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=affiliate/tracking/autocomplete&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['link']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product\']').val(item['label']);
		
		
		
		$('textarea[name=\'link\']').val( item['value']);	
	}	
});
//--></script> 
<?php echo $footer; ?>