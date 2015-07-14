<div class="panel-body">

<div class="row">

    <div class="col-xs-6">

        <h2><?php echo $text_new_customer; ?></h2>
        <p><?php echo $text_checkout; ?></p>

        <div class="radio" for="register">
        <?php if ($account == 'register') { ?>
        <input type="radio" name="account" value="register" id="register" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="account" value="register" id="register" />
        <?php } ?>
        <label><?php echo $text_register; ?></label></div>
        
        <?php if ($guest_checkout) { ?>
            <div class="radio" for="guest">
            <?php if ($account == 'guest') { ?>
            <input type="radio" name="account" value="guest" id="guest" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="account" value="guest" id="guest" />
            <?php } ?>
            <label><?php echo $text_guest; ?></label>
            </div>
        <?php } ?>

        <p><?php echo $text_register_account; ?></p>
        <input type="button" value="<?php echo $button_continue; ?>" id="button-account" class="btn btn-primary" />

    </div> <!-- col-xs-6-->


    <div id="login" class="col-xs-6">

        <h2><?php echo $text_returning_customer; ?></h2>
        <p><?php echo $text_i_am_returning_customer; ?></p>
		<div class="form-group">
        <label class="control-label" for="email"><?php echo $entry_email; ?></label>
        <input type="text" name="email" value="" class="form-control" />
</div>
<div class="form-group">
        <label class="control-label" for="password"><?php echo $entry_password; ?></label>
        <input type="password" name="password" value="" class="form-control" />
		</div>
        <br />
        <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
        <br />
        <input type="button" value="<?php echo $button_login; ?>" id="button-login" class="btn btn-primary" />
    </div> <!-- col-xs-6-->

</div>
<div class="panel-body">