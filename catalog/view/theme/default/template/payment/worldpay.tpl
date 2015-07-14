<form action="<?php echo $action; ?>" method="post">
  <input class="form-control"  type="hidden" name="instId" value="<?php echo $merchant; ?>" />
  <input class="form-control"  type="hidden" name="cartId" value="<?php echo $order_id; ?>" />
  <input class="form-control"  type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input class="form-control"  type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input class="form-control"  type="hidden" name="desc" value="<?php echo $description; ?>" />
  <input class="form-control"  type="hidden" name="name" value="<?php echo $name; ?>" />
  <input class="form-control"  type="hidden" name="address" value="<?php echo $address; ?>" />
  <input class="form-control"  type="hidden" name="postcode" value="<?php echo $postcode; ?>" />
  <input class="form-control"  type="hidden" name="country" value="<?php echo $country; ?>" />
  <input class="form-control"  type="hidden" name="tel" value="<?php echo $telephone; ?>" />
  <input class="form-control"  type="hidden" name="email" value="<?php echo $email; ?>" />
  <input class="form-control"  type="hidden" name="testMode" value="<?php echo $test; ?>" />
  <div class="buttons">
    <div class="pull-right">
      <input class="form-control"  type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
