<?php echo $header; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
    <?php } else { ?>
    <?php $cols = 12; ?>
    <?php } ?>  
    <div id="content" class="col-xs-<?php echo $cols; ?>"><?php echo $content_top; ?>
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
        <h1><?php echo $heading_title; ?></h1>
        <p><?php echo $text_email; ?></p>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <fieldset>
              <legend><?php echo $text_your_email; ?></legend>
              <div class="form-group required">
                <label class="col-xs-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
                <div class="col-xs-6">
                  <input type="email" name="email" value="" id="input-email" class="form-control" />
                </div>
              </div>
            </fieldset>
            <div class="buttons clearfix">
              <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
              <div class="pull-right">
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
              </div>
            </div>
        </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
	var opts = {
    	text: '<?php echo $error_warning; ?>',
    	type: 'error',
		styling: 'bootstrap3',
       	addclass: 'oc_noty',
        icon: 'picon picon-32 picon-fill-color',
        opacity: .8,
        nonblock: {
        	nonblock: true
        }
	};
	<?php if ($error_warning) { ?>
		$(function () {
		    new PNotify(opts);
		});
	<?php } ?>
</script>

<?php echo $footer; ?>