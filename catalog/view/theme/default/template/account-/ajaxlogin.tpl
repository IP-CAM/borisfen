<div class="container" style="width: 1000px !important;">
    <div class="row">
        <div class="row">
            <div class="col-xs-6">
                <div class="well">
                    <h2><?php echo $text_i_am_returning_customer; ?></h2>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="ajax2login">
                        <div class="form-group">
                            <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                            <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                            <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" />
                            <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
                        <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary" />
                        <?php if ($redirect) { ?>
                        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .col-xs-6 {
            width: 58%;
        }
        .modal-footer {
            display: none;
        }
    </style>
</div>