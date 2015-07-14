<?php
header("HTTP/1.1 301 Moved Permanently");
header("Location: /index.php?route=checkout/checkout");
exit();
?>
<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($attention) { ?>
  <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-xs-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-xs-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-xs-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?>
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
      </h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-center"><?php echo $column_image; ?></td>
                <td class="text-left"><?php echo $column_name; ?></td>
                <td class="text-left"><?php echo $column_model; ?></td>
                <td class="text-left"><?php echo $column_quantity; ?></td>
                <td class="text-right"><?php echo $column_price; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td class="text-center"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?></td>
                <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                  <?php if (!$product['stock']) { ?>
                  <span class="text-danger">***</span>
                  <?php } ?>
                  <?php if ($product['option']) { ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <br />
                  <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                  <?php } ?>
                  <?php } ?>

                  <?php if ($product['reward']) { ?>
                  <br />
                  <small><?php echo $product['reward']; ?></small>
                  <?php } ?>

                  <?php if($product['recurring']) { ?>
                    <br />
                    <span class="label label-info"><?php echo $text_recurring_item; ?></span>
                    <small><?php echo $product['profile_description']; ?></small>
                  <?php } ?>
                </td>
                <td class="text-left"><?php echo $product['model']; ?></td>
                <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                    <span class="input-group-btn">
                    <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
                    <a href="<?php echo $product['remove']; ?>" title="<?php echo $button_remove; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a></span></div></td>
                <td class="text-right"><?php echo $product['price']; ?></td>
                <td class="text-right"><?php echo $product['total']; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($vouchers as $vouchers) { ?>
              <tr>
                <td></td>
                <td class="text-left"><?php echo $vouchers['description']; ?></td>
                <td class="text-left"></td>
                <td class="text-left"><input type="text" name="" value="1" size="1" disabled="disabled" class="input-mini" />
                  <a href="<?php echo $vouchers['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>"><i class="fa fa-times"></i></a></td>
                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
  <?php if ($coupon_status || $voucher_status || $reward_status || $shipping_status) { ?>
  <h2><?php echo $text_next; ?></h2>
    <p><?php echo $text_next_choice; ?></p>


        <div class="panel-group" id="accordion">

            <!-- Coupon -->
            <?php if ($coupon_status) { ?>
<div class="panel panel-default" id="use_coupon">
  <div class="panel-heading">
    <h4 class="panel-title"><a href="#coupon" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"> <?php echo $text_use_coupon; ?> <i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="coupon" class="panel-collapse collapse <?php echo ($next == 'coupon' ? 'in' : ''); ?>">
    <div class="panel-body content">
             
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" >
       <label class="col-xs-2 control-label" for="use_coupon"><?php echo $entry_coupon; ?></label>
      <div class="input-group">
        <input type="text" name="coupon" value="<?php echo $coupon; ?>"  id="use_coupon" class="form-control" />                 
	    <input type="hidden" name="next" value="coupon" class="form-control" id="use_coupon" /> 

        <span class="input-group-btn">
       <input type="submit" value="<?php echo $button_coupon; ?>" class="btn btn-primary" />
        </span></div>
      </form>

                </form>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Voucher -->
            <?php if ($voucher_status) { ?>
<div id="use_voucher" class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><a href="#voucher" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_use_voucher; ?><i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="voucher" class="panel-collapse collapse <?php echo ($next == 'voucher' ? 'in' : ''); ?>">
    <div class="panel-body">
               <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
				  
			<label class="col-xs-2 control-label" for="use_voucher"><?php echo $entry_voucher; ?></label>
      <div class="input-group">
        <input type="text" name="voucher" value="<?php echo $voucher; ?>"  id="use_voucher" class="form-control" />
		 <input type="hidden" name="next" value="voucher"  id="use_voucher" />

        <span class="input-group-btn">
           <input type="submit" value="<?php echo $button_voucher; ?>" class="btn btn-primary" />
        </span> </div>

                </form>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Reward -->
            <?php if ($reward_status) { ?>
<div id="use_reward" class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><a href="#reward" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_use_reward; ?><i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="reward" class="panel-collapse collapse <?php echo ($next == 'reward' ? 'in' : ''); ?>">
    <div class="panel-body">
                 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <label class="col-xs-2 control-label" for="use_reward"><?php echo $entry_reward; ?></label>
      <div class="input-group">
        <input type="text" name="reward" value="<?php echo $reward; ?>" placeholder="<?php echo $entry_reward; ?>" id="use_reward" class="form-control" />
       <input type="hidden" name="next" value="reward" id="use_reward" />
        <span class="input-group-btn">
          <input type="submit" value="<?php echo $button_reward; ?>"  class="btn btn-primary" />
        </span> </div>
                </form>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Shipping -->
            <?php if ($shipping_status) { ?>
<div id="shipping_estimate" class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><a href="#shipping" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><?php echo $text_shipping_estimate; ?> <i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="shipping" class="panel-collapse collapse <?php echo ($next == 'shipping' ? 'in' : ''); ?>">
    <div class="panel-body">
      <form class="form-horizontal" id="form-shipping">
        <div class="form-group required">
          <label class="col-xs-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
          <div class="col-xs-10"><select name="country_id" class="form-control">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
			  </div>
        </div>
        <div class="form-group required">
          <label class="col-xs-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
          <div class="col-xs-10"><select name="zone_id" class="form-control">
            </select></div>
        </div>
        <div class="form-group required">
          <label class="col-xs-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
          <div class="col-xs-10"><input type="text" name="postcode" value="<?php echo $postcode; ?>" class="form-control" /> </div>
        </div>
      <input type="button" value="<?php echo $button_quote; ?>" id="button-quote" class="btn btn-primary" />
	   </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <?php } ?>


      <br />
      <div class="row">
        <div class="col-xs-4 col-xs-offset-8">
          <table class="table table-bordered">
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>
      <div class="buttons">
        <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
        <div class="pull-right"><a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a></div>
      </div>
      	</div>
		 <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$('input[name=\'next\']').bind('change', function() {
	$('.cart-module > div').hide();
	
	$('#' + this.value).show();
});
//--></script>

<?php if ($shipping_status) { ?>
<script>
$(document).on('click','#button-quote',function(e){
	$.ajax({
		url:'index.php?route=checkout/cart/quote',
		type:'post',
		data:$('#form-shipping').serialize(),
		dataType:'json',		
		beforeSend:function(){
			$('#button-quote').attr('disabled',true);
			$('#button-quote').after('<i class="fa fa-loading"></i>');
		},
		complete:function(){
			$('#button-quote').removeAttr('disabled');
			$('.icon-loading').remove();
		},		
		success:function(json){
			$('.help-inline.error').remove();
			$('.error').removeClass('error');

			if(json['error']){
				if(json['error']['warning']){
					$('#notification').html('<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a>'+json['error']['warning']+'</div>');

					$('.alert').delay(8000).fadeTo(2000,0,function(){
						$('.alert').remove();
					});
				}	

				if(json['error']['country']){
					$('select[name="country_id"]').after('<span class="help-inline error">'+json['error']['country']+'</span>').closest('.control-group').addClass('error');
				}	
				
				if(json['error']['zone']){
					$('select[name="zone_id"]').after('<span class="help-inline error">'+json['error']['zone']+'</span>').closest('.control-group').addClass('error');
				}
				
				if(json['error']['postcode']){
					$('input[name="postcode"]').after('<span class="help-inline error">'+json['error']['postcode']+'</span>').closest('.control-group').addClass('error');
				}					
			}
			
			if(json['shipping_method']){
				html ='<form class="modal fade" id="modal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">';
				html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
				html+='<div class="modal-body" id="modal-body">';
				html+='<p><?php echo $text_shipping_method; ?></p>';
				html+='<table class="table table-bordered table-striped">';
				
				for(i in json['shipping_method']){
					html+='<thead>';
					html+='<tr>';
					html+='<th colspan="2">'+json['shipping_method'][i]['title']+'</th>';
					html+='</tr>';
					html+='</thead>';
				
					if(!json['shipping_method'][i]['error']){
						for(j in json['shipping_method'][i]['quote']){
							html+='<tr>';
							html+='<td><label class="radio inline">';
							html+='<input type="radio" name="shipping_method" value="'+json['shipping_method'][i]['quote'][j]['code']+'" id="'+json['shipping_method'][i]['quote'][j]['code']+'"';
							if(json['shipping_method'][i]['quote'][j]['code']=='')html+=' checked=""';
							html+='>';
							html+=json['shipping_method'][i]['quote'][j]['title']+'</label></td>';
							html+='<td class="text-right text-error">'+json['shipping_method'][i]['quote'][j]['text']+'</td>';
							html+='</tr>';
						}		
					} else {
						html+='<tr><td colspan="2"><div class="text-error">'+json['shipping_method'][i]['error']+'</div></td></tr>';						
					}
				}
				
				html+='</table>';
				html+='<input type="hidden" name="next" value="shipping">';
				html+='</div>';
				html+='<div class="modal-footer"><button type="submit" id="button-shipping" class="btn btn-primary"';	
								html+=' disabled=""';	
								html+='><?php echo $button_shipping; ?></button>';	
				html+='<a href="#" class="btn btn-default" data-dismiss="modal">&times;</a>';
							html += '    </div';
			html += '  </div>';

				html+='</div></form>'
				
				$('body').append(html);

				$('#modal').modal('show');
				$('#modal-body').load(this.href);
		
				$('input[name="shipping_method"]').change(function(){
					$('#button-shipping').removeAttr('disabled');
				});
			}
		}
	});
});
</script><script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spinner').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script>
<?php } ?>

<?php echo $footer; ?>