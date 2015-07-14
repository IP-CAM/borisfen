<form action="<?php echo $action; ?>" method="post">
  <input class="form-control"  type="hidden" name="sid" value="<?php echo $sid; ?>" />
  <input class="form-control"  type="hidden" name="total" value="<?php echo $total; ?>" />
  <input class="form-control"  type="hidden" name="cart_order_id" value="<?php echo $cart_order_id; ?>" />
  <input class="form-control"  type="hidden" name="card_holder_name" value="<?php echo $card_holder_name; ?>" />
  <input class="form-control"  type="hidden" name="street_address" value="<?php echo $street_address; ?>" />
  <input class="form-control"  type="hidden" name="city" value="<?php echo $city; ?>" />
  <input class="form-control"  type="hidden" name="state" value="<?php echo $state; ?>" />
  <input class="form-control"  type="hidden" name="zip" value="<?php echo $zip; ?>" />
  <input class="form-control"  type="hidden" name="country" value="<?php echo $country; ?>" />
  <input class="form-control"  type="hidden" name="email" value="<?php echo $email; ?>" />
  <input class="form-control"  type="hidden" name="phone" value="<?php echo $phone; ?>" />
  <input class="form-control"  type="hidden" name="ship_street_address" value="<?php echo $ship_street_address; ?>" />
  <input class="form-control"  type="hidden" name="ship_city" value="<?php echo $ship_city; ?>" />
  <input class="form-control"  type="hidden" name="ship_state" value="<?php echo $ship_state; ?>" />
  <input class="form-control"  type="hidden" name="ship_zip" value="<?php echo $ship_zip; ?>" />
  <input class="form-control"  type="hidden" name="ship_country" value="<?php echo $ship_country; ?>" />
  <?php $i = 0; ?>
  <?php foreach ($products as $product) { ?>
  <input class="form-control"  type="hidden" name="c_prod_<?php echo $i; ?>" value="<?php echo $product['product_id']; ?>,<?php echo $product['quantity']; ?>" />
  <input class="form-control"  type="hidden" name="c_name_<?php echo $i; ?>" value="<?php echo $product['name']; ?>" />
  <input class="form-control"  type="hidden" name="c_description_<?php echo $i; ?>" value="<?php echo $product['description']; ?>" />
  <input class="form-control"  type="hidden" name="c_price_<?php echo $i; ?>" value="<?php echo $product['price']; ?>" />
  <?php $i++; ?>
  <?php } ?>
  <input class="form-control"  type="hidden" name="id_type" value="1" />
  <?php if ($demo) { ?>
  <input class="form-control"  type="hidden" name="demo" value="<?php echo $demo; ?>" />
  <?php } ?>
  <input class="form-control"  type="hidden" name="lang" value="<?php echo $lang; ?>" />
  <input class="form-control"  type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
  <div class="buttons">
    <div class="pull-right">
      <input class="form-control"  type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
