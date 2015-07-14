<form action="<?php echo $action; ?>" method="post">
  <?php if (!$test) { ?>
  <input class="form-control"  type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>" />
  <input class="form-control"  type="hidden" name="success_url" value="<?php echo $success_url; ?>" />
  <input class="form-control"  type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>" />
  <input class="form-control"  type="hidden" name="declined_url" value="<?php echo $declined_url; ?>" />
  <?php } else { ?>
  <input class="form-control"  type="hidden" name="merchant_id" value="nochex_test" />
  <input class="form-control"  type="hidden" name="test_transaction" value="100" />
  <input class="form-control"  type="hidden" name="test_success_url" value="<?php echo $success_url; ?>" />
  <input class="form-control"  type="hidden" name="test_cancel_url" value="<?php echo $cancel_url; ?>" />
  <input class="form-control"  type="hidden" name="test_declined_url" value="<?php echo $declined_url; ?>" />
  <input class="form-control"  type="hidden" name="callback_url" value="<?php echo $callback_url; ?>" />
  <?php } ?>
  <input class="form-control"  type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input class="form-control"  type="hidden" name="description" value="<?php echo $description; ?>" />
  <input class="form-control"  type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
  <input class="form-control"  type="hidden" name="billing_fullname" value="<?php echo $billing_fullname; ?>" />
  <input class="form-control"  type="hidden" name="billing_address" value="<?php echo $billing_address; ?>" />
  <input class="form-control"  type="hidden" name="billing_postcode" value="<?php echo $billing_postcode; ?>" />
  <input class="form-control"  type="hidden" name="delivery_fullname" value="<?php echo $delivery_fullname; ?>" />
  <input class="form-control"  type="hidden" name="delivery_address" value="<?php echo $delivery_address; ?>" />
  <input class="form-control"  type="hidden" name="delivery_postcode" value="<?php echo $delivery_postcode; ?>" />
  <input class="form-control"  type="hidden" name="customer_phone_number" value="<?php echo $customer_phone_number; ?>" />
  <input class="form-control"  type="hidden" name="email_address" value="<?php echo $email_address; ?>" />
  <input class="form-control"  type="hidden" name="hide_billing_details" value="true" />
  <div class="buttons">
    <div class="pull-right">
      <input class="form-control"  type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>