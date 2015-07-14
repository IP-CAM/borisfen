<?php echo $header; ?>
<div class="container">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-ok-sign"></i> <?php echo $success; ?></div>
    <?php } ?>
    <div class="row">
    <?php echo $column_left; ?>
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
        <div class="row">
            <div class="col-xs-6">
                <div class="well">
                <h2><?php echo $text_register; ?></h2>
                <p><?php echo $text_register_account; ?></p>
                <a href="<?php echo $register; ?>" class="btn btn-primary"><?php echo $text_register; ?></a></div>
            </div>
            <div class="col-xs-6">
                <div class="well">
                    <h2><?php echo $text_i_am_returning_customer; ?></h2>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                        <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control default" />
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                        <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control default" />
                        <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
                      </div>
                      <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary" />
                      <?php if ($redirect) { ?>
                      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                      <?php } ?>
                    </form>
                </div>
            </div>
        </div>
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