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
    <div id="content" class="col-xs-<?php echo $cols; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="custom-box">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_order_detail; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php if ($invoice_no) { ?>
              <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
              <?php } ?>
              <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
              <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
            <td class="text-left">
              <?php if($this->config->get('use_payment_methods')) { ?>
              <?php if ($payment_method) { ?>
              <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
              <?php } ?>
              <?php } ?>
               <?php if($this->config->get('use_shipping_methods')) { ?>
              <?php if ($shipping_method) { ?>
              <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
              <?php } ?>
              <?php } ?>
              </td>
          </tr>
        </tbody>
      </table>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <?php if($this->config->get('use_payment_methods')) { ?>
            <td class="text-left"><?php echo $text_payment_address; ?></td>
            <?php } ?>
            <?php if($this->config->get('use_shipping_methods')) { ?>
            <?php if ($shipping_address) { ?>
            <td class="text-left"><?php echo $text_shipping_address; ?></td>
            <?php } ?>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php if($this->config->get('use_payment_methods')) { ?>
            <td class="text-left"><?php echo $payment_address; ?></td>
            <?php } ?>
            <?php if($this->config->get('use_shipping_methods')) { ?>
            <?php if ($shipping_address) { ?>
            <td class="text-left"><?php echo $shipping_address; ?></td>
            <?php } ?>
            <?php }?>
          </tr>
        </tbody>
      </table>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_name; ?></td>
              <td class="text-left"><?php echo $column_model; ?></td>
              <td class="text-right"><?php echo $column_quantity; ?></td>
              <td class="text-right"><?php echo $column_price; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <?php if ($products) { ?>
              <td style="width: 20px;"></td>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php $product_row = 0; $count_product = count($products); foreach ($products as $product) { ?>
            <tr class="product-row">
              <td class="text-left"><?php echo $product['name']; ?>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?></td>
              <td class="text-left"><?php echo $product['model']; ?></td>
              <td class="text-right"><?php echo $product['quantity']; ?></td>
              <td class="text-right"><?php echo $product['price']; ?></td>
              <td class="text-right"><?php echo $product['total']; ?></td>
              
              <td class="text-right">
              	<?php if($status['status_id'] == $this->config->get('config_complete_status_id')) { ?>
                  
                <?php } elseif ($count_product > 1 && ($status['status_id'] == 1 || $status['status_id'] == 2)) { ?>
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" <?php echo $product['product_id']; ?>" class=" btn btn-default cancel-button">
                  <i class="fa fa-times"></i></button>
                <?php } ?>
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
              </td>
            </tr>
            <?php $product_row++; } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td class="text-left"><?php echo $voucher['description']; ?></td>
              <td class="text-left"></td>
              <td class="text-right">1</td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              <?php if ($products) { ?>
              <td></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <?php $i=0; foreach ($totals as $total) { ?>
            <tr class="total-row">
              <td colspan="3"></td>
              <td class="text-right total_title<?php echo $i; ?>"><b><?php echo $total['title']; ?>:</b></td>
              <td class="text-right total_text<?php echo $i; ?>"><?php echo $total['text']; ?></td>
              <?php if ($products) { ?>
              <td></td>
              <?php } ?>
            </tr>
            <?php $i++; } ?>
          </tfoot>
        </table>
      </div>
      <?php if ($comment) { ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $comment; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
      <?php if ($histories) { ?>
      <h3><?php echo $text_history; ?></h3>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $column_date_added; ?></td>
            <td class="text-left"><?php echo $column_status; ?></td>
            <td class="text-left"><?php echo $column_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($histories as $history) { ?>
          <tr>
            <td class="text-left"><?php echo $history['date_added']; ?></td>
            <td class="text-left"><?php echo $history['status']; ?></td>
            <td class="text-left"><?php echo $history['comment']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } ?>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.cancel-button').on('click', function(e){
			$(this).parents('.product-row').first().fadeOut(200);
			var data = {
				order_status_id: '<?php echo $status['status_id']; ?>',
				order_id: '<?php echo $order_id; ?>',
			    product_id: $(this).parents('.product-row').find('input[name=product_id]').first().val(),
			    };
			$.ajax({
				url: '/cancelproduct.php',
				type: 'POST',
				data: data,
				dataType: 'json',
				success: function(resp) {
					console.log(resp);
					/* for (var i = 0; i < resp.totals.length; i++) {
						$('.total_title' + i).html(resp.totals[i]['title']);
						$('.total_text' + i).html(resp.totals[i]['text']);
						}
					if (resp.quantity == 1){
						$('.cancel-button').fadeOut(100); // из-за будстреповского тайтла,  пришлось заморочиться с fadeOut и setTimeout
						setTimeout(function(){
							$('.cancel-button').remove();
						}, 200);
					} */
                 window.location.reload();
				}
			});
		});
	});
</script>
<?php echo $footer; ?>