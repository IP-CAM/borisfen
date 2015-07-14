<script type="text/javascript">
	var 
		main_stylesheet, 
		stylesheet_ie6,
		stylesheet_ie7;
		
	$(document).ready(function(){	
		$('#myModal').on('hide', function () {
			//$('#myModal').addClass('fade');	
		})
		
		//Это событие срабатывает после скрытия всплывающего элемента и окончания анимации.
		$('#myModal').on('hidden', function () {
			<?php if ( $del_system_css_on_show =='1' ) { ?>
				main_stylesheet.appendTo('head');
				stylesheet_ie6.appendTo('head');
				stylesheet_ie7.appendTo('head');
			<?php } ?>
			
			$("link[href$='bootstrap-min.css']").remove();
			$('#myModal').remove();
		});
		
		$('#myModal').on('show', function () {
			<?php if ( $del_system_css_on_show =='1' ) { ?>
				main_stylesheet = $("link[href$='stylesheet.css']").detach();
				stylesheet_ie6 = $("link[href$='ie6.css']").detach();
				stylesheet_ie7 = $("link[href$='ie7.css']").detach();
			<?php } ?>	
		});
	}); 
</script>
 
	  <div class="row" id="pop-up-cart">
	      <div class="container-holder">
		  <?php if ($product['thumb']) { ?>	
			    <div class="col-xs-3">
				<?php if ($show_product_images == '1') { ?>
					
					<?php if ($type_product_images == 'type_product_images_carousel') { ?>
						<div id="myCarousel" class="carousel slide">
							<!-- Картинки в карусельке -->
							<div class="carousel-inner">
								<div class="active item">
									<img alt="<?php echo $product['name']; ?>" src="<?php echo $product['thumb']; ?>">
								</div>			
								<?php if ($images) { ?>
									<?php foreach ($images as $result) { ?>
										<div class="item">
											<img alt="<?php echo $product['name']; ?>" src="<?php echo $result['thumb']; ?>">
										</div>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					
					
					<?php if ($type_product_images == 'type_product_images_oneimage') { ?>
						<div class="thumbnail">
							<img alt="<?php echo $product['name']; ?>" src="<?php echo $product['thumb']; ?>">
						</div>
					<?php } ?>

				<?php } ?>
			  </div>
		  <?php } ?>
		  <div class="col-xs-9">
			<?php if ($show_product_name_price == '1') { ?>
				<div class="caption" style="padding: 0 15px">
					<a href="<?php echo $product['href']; ?>" class="product-name" ><?php echo $product['name']; ?></a>
					<span class="price"><?php echo $product['price']; ?></span>
				</div>
			<?php } ?>
		  </div>
          <div class="col-xs-12 shortdescription">
          <?php if ($show_product_desc == '1') { ?>
				<div class="p" rel="popover" >
					<?php echo $product['shortdescription']; ?> 
				</div>		
			<?php } ?>
		  </div>
          
		  </div>	  
	  </div>
	  <div class="row" id="pop-up-cart-tw">
  	  <div class="container-holder">
      <div class="col-xs-12">
		<form class="form-horizontal" id="jv_quickorder" method='post'>
			<fieldset class="row">
				<legend class="col-xs-12"><?php echo $legend_text; ?></legend>
					
					<?php if ( $field_user_name_show =='1' ) { ?>
						<div class="form-group  required">
							<label class="col-xs-3 control-label" for="user_name" ><?php echo $label_name_text; ?></label>
							<div class="col-xs-8">
                                <input type="text" value="<?php echo $FullName; ?>" class="form-control" id="user_name" rel="popover" name="user_name" placeholder="<?php echo $hint_name_descr_text; ?>" data-content="<?php echo $hint_name_descr_text; ?>" data-original-title="<?php echo $hint_name_heading_text; ?>" />
							</div>
						</div>
					<?php } ?>
					
					<?php if ( $field_user_phone_show =='1' ) { ?>
						<div class="form-group  required">
							<label class="col-xs-3 control-label" for="user_phone" ><?php echo $label_phone_text; ?></label>
							<div class="col-xs-8">
								<input type="text" class="form-control" id="user_phone" rel="popover" name="user_phone" placholder="<?php echo $placeholder_phone_text; ?>"  data-content="<?php echo $hint_phone_descr_text; ?>" data-original-title="<?php echo $hint_phone_heading_text; ?>" />
							</div>
						</div>
					<?php } ?>
					
					<?php if ( $field_email_show =='1' ) { ?>
						<div class="form-group">
							<label class="col-xs-3 control-label" for="user_email"><?php echo $label_email_text; ?></label>
							<div class="col-xs-8">
					        <input type="text" value="<?php echo $Email; ?>" class="form-control" id="user_email" rel="popover" name="user_email" placeholder="<?php echo $placeholder_email_text; ?>" data-content="<?php echo $hint_email_descr_text; ?>" data-original-title="<?php echo $hint_email_heading_text; ?>" />
							</div>
						</div>
					<?php } ?>
					
					<?php if ( $field_comment_show =='1' ) { ?>
						<div class="form-group">
							<label class="col-xs-3 control-label" for="user_comment" ><?php echo $label_comment_text; ?></label>
							<div class="col-xs-8">
							<textarea rows="1" class="form-control" id="user_comment" rel="popover" name="user_comment" placeholder="<?php echo $placeholder_comment_text; ?>"  data-content="<?php echo $hint_comment_descr_text; ?>" data-original-title="<?php echo $hint_comment_heading_text; ?>"></textarea>
							</div>
						</div>
					<?php } ?>
					
					<?php if ( $field_product_quantity_show =='1' ) { ?>
						<div class="form-group ">
							<label class="col-xs-3 control-label" for="order_product_quantity"><?php echo $label_product_quantity_text; ?></label>
							<div class="col-xs-8">
		                    <input type="text" value="<?php echo $quantity; ?>" class="form-control" id="order_product_quantity" rel="popover" name="order_product_quantity" placeholder="<?php echo $placeholder_product_quantity_text; ?>" data-content="<?php echo $hint_product_quantity_descr_text; ?>" data-original-title="<?php echo $hint_product_quantity_heading_text; ?>" />
							</div>
						</div>
					<?php } ?>
					
					<input type="hidden" name="version" value="<?php echo $version; ?>" />				
			</fieldset>			
		</form>
		</div>
        <div class="clearfix"></div>
		</div>
        
          <?php $myrandom = rand(); ?>
          <button id="button_order<?php echo $myrandom; ?>"  class="button_order addToCart btn <?php echo $type_colour_button_quickorder; ?>" rel="tooltip"><?php echo $button_order_text; ?></button>
        		
	</div>


<script type="text/javascript">
	function is_undefined(val){
	  if(typeof(val)  === 'undefined') {
		return ''
		}
	  else
	    return val;
	}

	var myvalidator;
	
	<?php if ( $show_popover =='1' ) { ?>
	$(document).ready(function(){
		$('input').hover(function(){
			$(this).popover('show');
		}, function() {
		    $(this).popover('hide');
		});
		
		$('.p').hover(function(){
			$(this).popover({
				placement: 'left',
				animation: true
			});
			$(this).popover('show')
		}, function() {
		    $(this).popover('hide');
		});
	});
	<?php } ?>

	<?php if ( ($show_product_images == '1') && ($type_product_images == 'type_product_images_carousel') ) { ?>
		$(document).ready(function(){	
			$('.carousel').carousel({
				interval: 2000,
				pause: "hover"
			})
		});
	<?php } ?>
	
	
	$(document).ready(function(){	
		$("#jv_quickorder #user_phone").mask('<?php echo $field_user_phone_maskedinput; ?>');	
	});
	
	$(document).ready(function(){	
		myvalidator = $('#jv_quickorder').validate({		
			focusInvalid: true,
			errorClass: "help-inline",
// 			errorElement: "span",
			errorClass: "error",
			validClass: "success", 
			highlight:function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('success');
				$(element).parents('.control-group').addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('error');
				$(element).parents('.control-group').addClass('success');
			}
		});
		
		<?php if ( ($field_user_name_show =='1') && ($field_user_name_required == '1') ) { ?>
			$("#jv_quickorder #user_name").rules("add", {
				required: true,
				
				messages: {
					required: "<?php echo $error_name_descr_text; ?>"
				}
			});
		<?php } ?>

		<?php if ( $field_user_phone_show =='1' ) { ?>
			$("#jv_quickorder #user_phone").rules("add", {
				rangelength: [5, 25],
				//digits: true,
				
				messages: {
					rangelength:"<?php echo $error_rangelengthphone_descr_text; ?>",
					digits: "<?php echo $error_digitsphone_descr_text; ?>"
				}
			});
		<?php } ?>
		
		<?php if ( ($field_user_phone_show =='1') && ($field_user_phone_required == '1') ) { ?>
			$("#jv_quickorder #user_phone").rules("add", {
				required: true,
				rangelength: [5, 25],
				//digits: true,
			
				messages: {
					required:"<?php echo $error_phone_descr_text; ?>",
					rangelength:"<?php echo $error_rangelengthphone_descr_text; ?>",
					digits: "<?php echo $error_digitsphone_descr_text; ?>"
				}
			});
		<?php } ?>

		
		<?php if ( ($field_email_show =='1') && ($field_email_required == '1') ) { ?>
			$("#jv_quickorder #user_email").rules("add", {
				required: true,
				email: true,
				
				messages: {
					required: "<?php echo $error_email_descr_text; ?>",
					email:"<?php echo $error_validemail_descr_text; ?>"
				}
			});
		<?php } ?>
		
		<?php if ( ($field_comment_show =='1') && ($field_comment_required == '1') ) { ?>
			$("#jv_quickorder #user_comment").rules("add", {
				required: true,
				rangelength: [5, 400],
			
				messages: {
					required:"<?php echo $error_comment_descr_text; ?>",
					rangelength:"<?php echo $error_rangelengthcomment_descr_text; ?>"
				}
			});
		<?php } ?>
		
		<?php if ( $field_product_quantity_show =='1' ) { ?>
			$("#jv_quickorder #order_product_quantity").rules("add", {
				min: <?php echo $product['minimum']; ?>,
				<?php if ($consider_in_stock) { ?>
					<?php if (!$config_stock_checkout) { ?>
						max: <?php echo $product['quantity']; ?>,
					<?php } ?>
				<?php } ?>	
				digits: true,
				
				messages: {
					min:"<?php echo $error_min_prod_quantity_descr_text; ?>",
					<?php if ($consider_in_stock) { ?>
						<?php if (!$config_stock_checkout) { ?>
							max:"<?php echo $error_max_prod_quantity_descr_text; ?>",
						<?php } ?>
					<?php } ?>	
					digits: "<?php echo $error_digits_prod_quantity_descr_text; ?>"
				}
			});
		<?php } ?>
		
		<?php if ( ($field_product_quantity_show =='1') && ($field_product_quantity_required == '1') ) { ?>
			$("#jv_quickorder #order_product_quantity").rules("add", {
				required: true,
				min: <?php echo $product['minimum']; ?>,
				<?php if ($consider_in_stock) { ?>
					<?php if (!$config_stock_checkout) { ?>
						max: <?php echo $product['quantity']; ?>,
					<?php } ?>
				<?php } ?>	
				digits: true,
			
				messages: {
					required:"<?php echo $error_product_quantity_descr_text; ?>",
					min:"<?php echo $error_min_prod_quantity_descr_text; ?>",
					<?php if ($consider_in_stock) { ?>
						<?php if (!$config_stock_checkout) { ?>
							max:"<?php echo $error_max_prod_quantity_descr_text; ?>",
						<?php } ?>
					<?php } ?>	
					digits: "<?php echo $error_digits_prod_quantity_descr_text; ?>"
				}
			});
		<?php } ?>
	});
	
	function successmessage_in_full_body(heading_text, body_text){
		
		alert(body_text);
		
		setTimeout(
			function(){
				$('div.modal-body > button').trigger('click');
			}, 
			5000
		);
		
	    return false;
	}
	
	function errormessage_in_full_body(heading_text, body_text){
		$('.modal-body').empty();
		$('.modal-footer').remove();
		$('.alert').remove();

		$('.alert').alert();
		$('.modal-body').prepend('<div class="alert  alert-block fade in">' + 
									'<a class="close" data-dismiss="alert" href="#">×</a>' +
									'<div class="alert-heading h3">' + heading_text + '</div><br /><br />' + 
									'<strong>' + body_text + '</strong><div>');	
		setTimeout(
			function(){
			    console.log('errormessage_in_full_body');
				$('#myModal').modal('hide')
			}, 
			5000
		);
	
	    return false;
	}
	
	function errormessage_in_body(heading_text, body_text){
		$('.alert').remove();

		$('.alert').alert();
		$('.modal-body').prepend('<div class="alert alert-error alert-block fade in">' + 
									'<a class="close" data-dismiss="alert" href="#">×</a>' +
									'<div class="alert-heading h3">' + heading_text + '<br /><br />' + 
									'<strong>' + body_text + '</strong><div>');	
		setTimeout(
			function(){
				console.log('errormessage_in_body');
				$(".alert").alert('close');
				$('#button_order').removeAttr('disabled')
			}, 
			5000
		);
	    return false;
	}
	
	$(document).on('click', '#button_order' + <?php echo $myrandom; ?>, function() {
		if ( myvalidator.form() ) {
			//Sending E-Mail
			var issuccess = true;
			
			<?php if ( $send_email_status == '1' ) { ?>	
				$.ajax({
					<?php if ( $type_email == 'type_email_text' ) { ?>
						url: 'index.php?route=module/jvquickorder/SendTextMail',
					<?php } else { ?>
						url: 'index.php?route=module/jvquickorder/SendHTMLMail',
					<?php } ?>
					type: 'post',
					timeout : 6000,
					async: false,
					data: 'product_id=' + <?php echo $product['product_id']; ?> + '&customer_name=' + is_undefined($('#jv_quickorder #user_name').val()) + '&customer_phone=' + is_undefined($('#jv_quickorder #user_phone').val()) + '&customer_email=' + is_undefined($('#jv_quickorder #user_email').val()) + '&customer_comment=' + is_undefined($('#jv_quickorder #user_comment').val()) + '&order_product_quantity=' +is_undefined($('#jv_quickorder #order_product_quantity').val()),
					dataType: 'json',
					beforeSend: function() {
						$('#button_order').attr('disabled', 'disabled');
					},
					success: function(json) {
						issuccess = true;
					},
					error: function(data) {
						issuccess = false;
						errormessage_in_body('<?php echo $error_message_heading_text; ?>', '<?php echo $error_message_body_text; ?>');					
					}
				});	
			<?php } ?>
			//Sending E-Mail
			
			//Checkout order
			var order_offon = <?php echo $order_offon ?>;
			
			if ( ( order_offon == '1'  ) && issuccess ) {
				$.ajax({
					url: 'index.php?route=module/jvquickorder/addorder',
					type: 'post',
					timeout : 6000,
					async: false,
					data: 'product_id=' + <?php echo $product['product_id']; ?> + '&customer_name=' + is_undefined($('#jv_quickorder #user_name').val()) + '&customer_phone=' + is_undefined($('#jv_quickorder #user_phone').val()) + '&customer_email=' + is_undefined($('#jv_quickorder #user_email').val()) + '&customer_comment=' + is_undefined($('#jv_quickorder #user_comment').val()) + '&order_product_quantity=' +is_undefined($('#jv_quickorder #order_product_quantity').val()),
					dataType: 'json',
					beforeSend: function() {
						$('#button_order').attr('disabled', 'disabled');
					},
					success: function(json) {
						issuccess = true;
						console.log(json);
						console.log('in success');
					},
					
					error: function(data) {
						issuccess = false;
						console.log(data);
						console.log('in success');
						errormessage_in_body('<?php echo $error_message_heading_text; ?>', '<?php echo $error_message_ordererror_body_text; ?>');					
					}
				});	
			}
			//Checkout order
			if ( issuccess ) {
				successmessage_in_full_body('<?php echo $success_message_heading_text; ?>', '<?php echo $success_message_body_text; ?>');
			}
		};
	});

	/*
	$(document).ready(function(){
		<?php if (!$show_in_category) { ?>
			errormessage_in_full_body('<?php echo $error_message_heading_text; ?>', '<?php echo $error_message_not_work_in_categories_body_text; ?>');	
		<?php } ?>
	});	
	*/
	
	$(document).ready(function(){
		<?php if ($consider_in_stock) { ?>
			<?php if (!$config_stock_checkout) { ?>
				<?php if (!$instock) { ?>
					errormessage_in_full_body('<?php echo $error_message_heading_text; ?>', '<?php echo $error_message_nostock_body_text; ?>');	
				<?php } ?>
			<?php } ?>
		<?php } ?>
	});		
</script>
<script type="text/javascript"><!--
/*
if (!$("script").is("script[src$='jquery-ui-timepicker-addon.js']")) {
		$("head").append('<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>');
	}	

$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
*/
//--></script> 