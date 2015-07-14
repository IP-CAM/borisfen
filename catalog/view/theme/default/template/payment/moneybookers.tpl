<form action="<?php echo $action; ?>" method="post">
  <input class="form-control"  type="hidden" name="pay_to_email" value="<?php echo $pay_to_email; ?>" />
  <input class="form-control"  type="hidden" name="recipient_description" value="<?php echo $description; ?>" />
  <input class="form-control"  type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>" />
  <input class="form-control"  type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
  <input class="form-control"  type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>" />
  <input class="form-control"  type="hidden" name="status_url" value="<?php echo $status_url; ?>" />
  <input class="form-control"  type="hidden" name="language" value="<?php echo $language; ?>" />
  <input class="form-control"  type="hidden" name="logo_url" value="<?php echo $logo; ?>" />
  <input class="form-control"  type="hidden" name="pay_from_email" value="<?php echo $pay_from_email; ?>" />
  <input class="form-control"  type="hidden" name="firstname" value="<?php echo $firstname; ?>" />
  <input class="form-control"  type="hidden" name="lastname" value="<?php echo $lastname; ?>" />
  <input class="form-control"  type="hidden" name="address" value="<?php echo $address; ?>" />
  <input class="form-control"  type="hidden" name="address2" value="<?php echo $address2; ?>" />
  <input class="form-control"  type="hidden" name="phone_number" value="<?php echo $phone_number; ?>" />
  <input class="form-control"  type="hidden" name="postal_code" value="<?php echo $postal_code; ?>" />
  <input class="form-control"  type="hidden" name="city" value="<?php echo $city; ?>" />
  <input class="form-control"  type="hidden" name="state" value="<?php echo $state; ?>" />
  <input class="form-control"  type="hidden" name="country" value="<?php echo $country; ?>" />
  <input class="form-control"  type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input class="form-control"  type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input class="form-control"  type="hidden" name="detail1_text" value="<?php echo $detail1_text; ?>" />
  <input class="form-control"  type="hidden" name="merchant_fields" value="order_id" />
  <input class="form-control"  type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
  <input class="form-control"  type="hidden" name="platform" value="<?php echo $platform; ?>" />
  <div class="buttons">
    <div class="pull-right">
      <input class="form-control"  type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
