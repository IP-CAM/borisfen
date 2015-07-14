<!-- Quick Checkout v4.0.5 by Dreamvention.com quickcheckout/login.tpl -->
<?php $count = $data['option']['login']['display'] + $data['option']['register']['display'] + $data['option']['guest']['display'];
$width = ($count) ? (100 - $count)/$count : 0; ?>
<?php  if($settings['general']['login_style'] == 'popup') { ?>
<div id="option_login_popup_trigger_wrap"style="display:<?php echo ($count)? '' : 'none'; ?>" >
  <span id="option_register_popup" style="display:<?php if(!$data['option']['register']['display']){ echo 'none'; } ?>;">
    <?php if ($account == 'register') { ?>
    <input type="radio" name="account" value="register" id="register" checked="checked" data-refresh="1"  autocomplete='off' />
    <?php } else { ?>
    <input type="radio" name="account" value="register" id="register"  data-refresh="1"  autocomplete='off' />
    <?php } ?>
    <label for="register"><?php echo $data['option']['register']['title']; ?></label>
  </span>
  <span id="option_guest_popup" style="display:<?php if(!$data['option']['guest']['display']){ echo 'none'; } ?>;">
    <?php if ($account == 'guest') { ?>
    <input type="radio" name="account" value="guest" id="guest" checked="checked"  data-refresh="1"  autocomplete='off'/>
    <?php } else { ?>
    <input type="radio" name="account" value="guest" id="guest"  data-refresh="1"  autocomplete='off'/>
    <?php } ?>
    <label for="guest"><?php echo $data['option']['guest']['title']; ?></label>
  </span>
  <a class="button btn btn-primary" id="option_login_popup_trigger" style="display:<?php if(!$data['option']['login']['display']){ echo 'none'; } ?>" >Login</a>
</div>
<div class="box-popup-wrap" id="option_login_popup_wrap">
  <div id="option_login_popup" class="box-popup" style="display:<?php if(!$data['option']['login']['display']){ echo 'none'; } ?> ;">
    <div class="heading"><span><?php echo $text_returning_customer; ?></span></div>
    <div class="box-content">
      <div class="block-row email form-group">
        <label for="login_email"><?php echo $entry_email; ?></label>
        <input type="text" class="form-control"name="email" value="" id="login_email" />
      </div>
      <div class="block-row password form-group">
        <label for="login_password"><?php echo $entry_password; ?></label>
        <input type="password" name="password" value="" id="login_password"/>
      </div>
      <div class="block-row button-login form-group">
        <input type="button" value="<?php echo $button_login; ?>" id="button_login_popup" class="button btn btn-primary" />
        <a id="remeber_password" href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a> </div>
        <div class="clear" ></div>
      </div>
      <div class="close">x</div>
    </div>
  </div>

  <script><!--
    $(function(){
     if($.isFunction($.fn.uniform)){
      $(" .styled, input:radio.styled").uniform().removeClass('styled');
    }
    
    $(document).on('click', '#option_login_popup_trigger', function(){
      $('#option_login_popup_wrap').show()												   
      
    })
    

    $(document).on('click', '#option_login_popup .close', function(){
      $('#option_login_popup_wrap').hide()														   
      
    })
  });
    //--></script>
    <?php }else{ ?>
    <div id="login_wrap" class="row">
      <div id="option_login" class="box box-border col-xs-4" style="display:<?php if(!$data['option']['login']['display']){ echo 'none'; } ?> ;/* width: <?php echo $width; ?>%*/">
        <div class="heading"><span><?php echo $text_returning_customer; ?></span></div>
        <div class="box-content">
          <div class="block-row email form-group">
            <label for="login_email"><?php echo $entry_email; ?></label>
            <input type="text" class="form-control"name="email" value="" id="login_email" />
          </div>
          <div class="block-row password form-group">
            <label for="login_password"><?php echo $entry_password; ?></label>
            <input type="password" class="form-control" name="password" value="" id="login_password"/>
          </div>
          <div class="block-row button-login form-group row">
            <div class="container">
              <input type="button" value="<?php echo $button_login; ?>" id="button_login" class="button btn btn-primary pull-left" />
              <a id="remeber_password" href="<?php echo $forgotten; ?>" class="pull-right"><?php echo $text_forgotten; ?></a> </div>
            </div>
          </div>
        </div>
        <div class="col-xs-4 col-xs-offset-8">
          <div id="option_register " class=" col-xs-5 <?php if ($account == 'register') { ?> selected <?php } ?>" style="display:<?php if(!$data['option']['register']['display']){ echo 'none'; } ?>; ">
           <label for="register" class="radio">  <?php if ($account == 'register') { ?>
            <input type="radio" name="account" value="register" id="register" checked="checked" data-refresh="1"  autocomplete='off' />
            <?php } else { ?>
            <input type="radio" name="account" value="register" id="register"  data-refresh="1"  autocomplete='off' />
            <?php } ?>
            <?php echo $data['option']['register']['title']; ?></label>
          </div>
          <?php if ($guest_checkout) { ?>
          <div id="option_guest" class="col-xs-7 <?php if ($account == 'guest') { ?> selected <?php } ?>" style="display:<?php if(!$data['option']['guest']['display']){ echo 'none'; } ?>; /*width: <?php echo $width; ?>%*/">
            <label for="guest" class="radio">
              <?php if ($account == 'guest') { ?>
              <input type="radio" name="account" value="guest" id="guest" checked="checked"  data-refresh="1"  autocomplete='off'/>
              <?php } else { ?>
              <input type="radio" name="account" value="guest" id="guest"  data-refresh="1"  autocomplete='off'/>
              <?php } ?>
              <?php echo $data['option']['guest']['title']; ?></label>
            </div>
            <?php } ?>
          </div>
        </div>


        <?php  }?>