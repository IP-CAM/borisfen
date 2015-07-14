<form action="https://www.secpay.com/java-bin/ValCard" method="post">
  <input class="form-control"  type="hidden" name="merchant" value="<?php echo $merchant; ?>" />
  <input class="form-control"  type="hidden" name="trans_id" value="<?php echo $trans_id; ?>" />
  <input class="form-control"  type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <?php if ($digest) { ?>
  <input class="form-control"  type="hidden" name="digest" value="<?php echo $digest; ?>" />
  <?php } ?>  
  <input class="form-control"  type="hidden" name="bill_name" value="<?php echo $bill_name; ?>" />
  <input class="form-control"  type="hidden" name="bill_addr_1" value="<?php echo $bill_addr_1; ?>" />
  <input class="form-control"  type="hidden" name="bill_addr_2" value="<?php echo $bill_addr_2; ?>" />
  <input class="form-control"  type="hidden" name="bill_city" value="<?php echo $bill_city; ?>" />
  <input class="form-control"  type="hidden" name="bill_state" value="<?php echo $bill_state; ?>" />
  <input class="form-control"  type="hidden" name="bill_post_code" value="<?php echo $bill_post_code; ?>" />
  <input class="form-control"  type="hidden" name="bill_country" value="<?php echo $bill_country; ?>" />
  <input class="form-control"  type="hidden" name="bill_tel" value="<?php echo $bill_tel; ?>" />
  <input class="form-control"  type="hidden" name="bill_email" value="<?php echo $bill_email; ?>" />
  <input class="form-control"  type="hidden" name="ship_name" value="<?php echo $ship_name; ?>" />
  <input class="form-control"  type="hidden" name="ship_addr_1" value="<?php echo $ship_addr_1; ?>" />
  <input class="form-control"  type="hidden" name="ship_addr_2" value="<?php echo $ship_addr_2; ?>" />
  <input class="form-control"  type="hidden" name="ship_city" value="<?php echo $ship_city; ?>" />
  <input class="form-control"  type="hidden" name="ship_state" value="<?php echo $ship_state; ?>" />
  <input class="form-control"  type="hidden" name="ship_post_code" value="<?php echo $ship_post_code; ?>" />
  <input class="form-control"  type="hidden" name="ship_country" value="<?php echo $ship_country; ?>" />
  <input class="form-control"  type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input class="form-control"  type="hidden" name="callback" value="<?php echo $callback; ?>" />
  <input class="form-control"  type="hidden" name="options" value="<?php echo $options; ?>" />
   <div class="buttons">
    <div class="pull-right">
      <input class="form-control"   type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
