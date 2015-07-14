<form action="<?php echo $action; ?>" method="get">
  <input class="form-control"  type="hidden" name="mid" value="<?php echo $mid; ?>" />
  <input class="form-control"  type="hidden" name="amt" value="<?php echo $amt; ?>" />
  <input class="form-control"  type="hidden" name="amt_editable" value="N" />
  <input class="form-control"  type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input class="form-control"  type="hidden" name="ref" value="<?php echo $ref; ?>" />
  <input class="form-control"  type="hidden" name="pmt_sender_email" value="<?php echo $pmt_sender_email; ?>" />
  <input class="form-control"  type="hidden" name="pmt_contact_firstname" value="<?php echo $pmt_contact_firstname; ?>" />
  <input class="form-control"  type="hidden" name="pmt_contact_surname" value="<?php echo $pmt_contact_surname; ?>" />
  <input class="form-control"  type="hidden" name="pmt_contact_phone" value="<?php echo $pmt_contact_phone; ?>" />
  <input class="form-control"  type="hidden" name="pmt_country" value="<?php echo $pmt_country; ?>" />
  <input class="form-control"  type="hidden" name="regindi_address1" value="<?php echo $regindi_address1; ?>" />
  <input class="form-control"  type="hidden" name="regindi_address2" value="<?php echo $regindi_address2; ?>" />
  <input class="form-control"  type="hidden" name="regindi_sub" value="<?php echo $regindi_sub; ?>" />
  <input class="form-control"  type="hidden" name="regindi_state" value="<?php echo $regindi_state; ?>" />
  <input class="form-control"  type="hidden" name="regindi_pcode" value="<?php echo $regindi_pcode; ?>" />
  <input class="form-control"  type="hidden" name="return" value="<?php echo $return; ?>" />
  <input class="form-control"  type="hidden" name="popup" value="false" />
   <div class="buttons">
    <div class="pull-right">
      <input class="form-control"  type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
