<form action="<?php echo $action; ?>" method="post">
  <input class="form-control"  type="hidden" name="operation_xml" value="<?php echo $xml; ?>">
  <input class="form-control"  type="hidden" name="signature" value="<?php echo $signature; ?>">
  <div class="buttons">
    <div class="pull-right">
      <input class="form-control"  type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
