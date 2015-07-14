<form action="<?php echo $action; ?>" method="post">
  <input class="form-control"  type="hidden" name="ap_merchant" value="<?php echo $ap_merchant; ?>" />
  <input class="form-control"  type="hidden" name="ap_amount" value="<?php echo $ap_amount; ?>" />
  <input class="form-control"  type="hidden" name="ap_currency" value="<?php echo $ap_currency; ?>" />
  <input class="form-control"  type="hidden" name="ap_purchasetype" value="<?php echo $ap_purchasetype; ?>" />
  <input class="form-control"  type="hidden" name="ap_itemname" value="<?php echo $ap_itemname; ?>" />
  <input class="form-control"  type="hidden" name="ap_itemcode" value="<?php echo $ap_itemcode; ?>" />
  <input class="form-control"  type="hidden" name="ap_returnurl" value="<?php echo $ap_returnurl; ?>" />
  <input class="form-control"  type="hidden" name="ap_cancelurl" value="<?php echo $ap_cancelurl; ?>" />
    <div class="buttons">
    <div class="pull-right">
      <input class="form-control"     type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
