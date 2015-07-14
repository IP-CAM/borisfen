<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-klarna-fee" class="btn btn-primary "><i class="fa fa-check"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger "><i class="fa fa-times"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="fa fa-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-klarna-fee" class="form-horizontal">
        <ul class="nav nav-tabs" id="country">
          <?php foreach ($countries as $country) { ?>
          <li><a href="#tab-<?php echo $country['code']; ?>" data-toggle="tab"><?php echo $country['name']; ?></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <?php foreach ($countries as $country) { ?>
          <div class="tab-pane" id="tab-<?php echo $country['code']; ?>">
            <div class="form-group">
              <label class="col-xs-2 control-label" for="input-total<?php echo $country['code']; ?>"><?php echo $entry_total ?></label>
              <div class="col-xs-10">
                <input type="text" name="klarna_fee[<?php echo $country['code']; ?>][total]" value="<?php echo isset($klarna_fee[$country['code']]) ? $klarna_fee[$country['code']]['total'] : ''; ?>" id="input-total<?php echo $country['code']; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-2 control-label" for="input-fee<?php echo $country['code']; ?>"><?php echo $entry_fee ?></label>
              <div class="col-xs-10">
                <input type="text" name="klarna_fee[<?php echo $country['code']; ?>][fee]" value="<?php echo isset($klarna_fee[$country['code']]) ? $klarna_fee[$country['code']]['fee'] : ''; ?>" id="input-fee<?php echo $country['code']; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-2 control-label" for="input-tax-class<?php echo $country['code']; ?>"><?php echo $entry_tax_class; ?></label>
              <div class="col-xs-10">
                <select name="klarna_fee[<?php echo $country['code']; ?>][tax_class_id]" id="input-tax-class<?php echo $country['code']; ?>" class="form-control">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if (isset($klarna_fee[$country['code']]) && $klarna_fee[$country['code']]['tax_class_id'] == $tax_class['tax_class_id']) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-2 control-label" for="input-status<?php echo $country['code']; ?>"><?php echo $entry_status; ?></label>
              <div class="col-xs-10">
                <div class="btn-group" data-toggle="buttons">
  			<label class="btn btn-primary<?php if (isset($klarna_fee[$country['code']]) && $klarna_fee[$country['code']]['status'] == 1) { ?> active<?php } ?>">
    				<i class="fa fa-check"></i> <input type="radio" name="klarna_fee[<?php echo $country['code']; ?>][status]" id="input-status<?php echo $country['code']; ?>" <?php if (isset($klarna_fee[$country['code']]) && $klarna_fee[$country['code']]['status'] == 1) { ?>checked<?php } ?>> <?php echo $text_enabled; ?>
  			</label>
  			<label class="btn btn-primary<?php if (isset($klarna_fee[$country['code']]) && $klarna_fee[$country['code']]['status'] == 0) { ?> active<?php } ?>">
    				<i class="fa fa-times"></i> <input type="radio" name="klarna_fee[<?php echo $country['code']; ?>][status]" id="input-status<?php echo $country['code']; ?>" <?php if (isset($klarna_fee[$country['code']]) && $klarna_fee[$country['code']]['status'] == 0) { ?>checked<?php } ?>> <?php echo $text_disabled; ?>
  			</label>
		</div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-2 control-label" for="input-sort-order<?php echo $country['code']; ?>"><?php echo $entry_sort_order; ?></label>
              <div class="col-xs-10">
                <input type="text" name="klarna_fee[<?php echo $country['code']; ?>][sort_order]" value="<?php echo isset($klarna_fee[$country['code']]) ? $klarna_fee[$country['code']]['sort_order'] : ''; ?>" id="input-sort-order<?php echo $country['code']; ?>" class="form-control" />
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#country a:first').tab('show');
//--></script> 
<?php echo $footer; ?> 