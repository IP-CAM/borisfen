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
    <h2><?php echo $heading_payment; ?></h2>
        <div style="float: left" id="amazon-wallet-widget"></div>
        <div style="clear: both;"></div>
        <div class="buttons">
	  <div class="pull-right">
        <a href="<?php echo $back ?>" class="btn btn-default"><span><?php echo $text_back ?></span></a>
        <a class="btn btn-default" id="confirm-button"><span><?php echo $text_confirm ?></span></a>
		  </div>
    </div>
        <input type="hidden" name="payment_method" value="" class="form-control" />
     <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
    $(document).ready(function(){
        
        $('#continue-button').click(function(){
            $('div.warning').remove();
            
            if ($("input[name='payment_method']").val() == '1') {
                location = '<?php echo $continue ?>';
            } else {
                $('#amazon-wallet-widget').before('<div class="warning"><?php echo $error_payment_method ?></div>');
            }
        });
        
        new CBA.Widgets.WalletWidget({
            merchantId: '<?php echo $merchant_id ?>',
            displayMode: 'edit',
            onPaymentSelect: function(widget){
                $("input[name='payment_method']").val('1');
            }
            
        }).render('amazon-wallet-widget');
        
    });
//--></script>
<?php echo $footer; ?>