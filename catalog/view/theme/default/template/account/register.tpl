<?php echo $header; ?>
<div class="container">
<div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
    <?php } else { ?>
    <?php $cols = 12; ?>
    <?php } ?>
    <div id="content" class="col-xs-<?php echo $cols; ?>"><?php echo $content_top; ?>
<?php if ($error_warning) { ?>
<br>
<div class="alert alert-danger"><?php echo $error_warning; ?>
	<button type="button" class="close" data-dismiss="alert">
		&times;
	</button>
</div>
<?php } ?>
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
        <h1><?php echo $heading_title; ?></h1>
        <p><?php echo $text_account_already; ?></p>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="content">

               <legend><?php echo $text_your_details; ?></legend>
               <fieldset>
                <div class="form-group col-xs-10">
                    <label class="col-xs-4 control-label" for="firstname">
                        <span class="text-danger">*</span>
                        <?php echo $entry_firstname; ?>
                    </label>
                    <div class="col-xs-8">
                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" autofocus class="form-control default" />
                        <?php if ($error_firstname) { ?>
                        <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_firstname; ?></div>
                        <?php } ?>
                    </div> <!-- controls -->
                </div> <!-- form-group -->
					<!--
                    <div class="form-group col-xs-8">

                        <label class="col-xs-3 control-label" for="lastname">
                            <span class="text-error">*</span> <?php echo $entry_lastname; ?>
                        </label>

                        <div class="col-xs-9  control-label">
                            <input class="form-control" type="text" name="lastname" value="<?php echo $lastname; ?>" />
                            <?php if ($error_lastname) { ?>
                            <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_lastname; ?></div>
                            <?php } ?>
                        </div> 

                    </div> -->
                    
                    <div class="form-group col-xs-10">
                        <label class="col-xs-4 control-label" for="email">
                            <span class="text-error">*</span> <?php echo $entry_email; ?>
                        </label>
                        <div class="col-xs-8">
                            <input class="form-control default" type="email" name="email" value="<?php echo $email; ?>" />
                            <?php if ($error_email) { ?>
                            <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_email; ?></div>
                            <?php } ?>
                        </div> <!-- col-xs-10  control-label -->
                    </div> <!-- form-group -->

                    <div class="form-group col-xs-10">
                        <label class="col-xs-4 control-label" for="telephone">
                            <span class="text-error">*</span> <?php echo $entry_telephone; ?>
                        </label>
                        <div class="col-xs-8">
                            <input class="form-control default" type="tel" name="telephone" value="<?php echo $telephone; ?>" />
                            <?php if ($error_telephone) { ?>
                            <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_telephone; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->
                    </div> <!-- form-group -->
                    <div class="form-group col-xs-10">
                        <label class="col-xs-4 control-label" for="address_1">
                            <span class="text-error">*</span> <?php echo $entry_address_1; ?>
                        </label>
                        <div class="col-xs-8">
                            <input class="form-control default" type="text" name="address_1" value="<?php echo $address_1; ?>" />
                            <?php if ($error_address_1) { ?>
                            <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_address_1; ?></div>
                            <?php } ?>
                        </div> <!-- col-xs-10  control-label -->
                    </div> <!-- form-group -->
                    <div class="form-group col-xs-10">
                        <label class="col-xs-4 control-label" for="city">
                            <span class="text-error">*</span> <?php echo $entry_city; ?>
                        </label>
                        <div class="col-xs-8">
                            <input class="form-control default" type="text" name="city" value="<?php echo $city; ?>" />
                            <?php if ($error_city) { ?>
                            <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_city; ?></div>
                            <?php } ?>
                        </div> <!-- col-xs-10  control-label -->
                    </div> <!-- form-group -->
                    <div class="form-group" style="display: none;">
                        <label class="col-xs-3 control-label" for="fax">
                            <?php echo $entry_fax; ?>
                        </label>
                        <div class="col-xs-9  control-label">
                            <input class="form-control default" type="text" name="fax" value="<?php echo $fax; ?>" />
                        </div> <!-- col-xs-10  control-label -->
                    </div> <!-- form-group -->

                </fieldset>

            </div> <!-- content -->

            <div class="content" style="display: none;">
                <legend><?php echo $text_your_address; ?></legend>
                <fieldset>
                    <div class="form-group col-xs-10">
                        <label class="col-xs-4 control-label" for="company">
                            <?php echo $entry_company; ?>
                        </label>
                        <div class="col-xs-10">
                            <input class="form-control default" type="text" name="company" value="<?php echo $company; ?>" />
                        </div> <!-- col-xs-10  control-label -->
                    </div> <!-- form-group -->
                    <div class="form-group required" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;" style="display: none;">
                        <label class="col-xs-3 control-label"><?php echo $entry_customer_group; ?></label>
                        <div class="col-xs-10">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                          <div class="radio">
                            <label>
                              <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                              <?php echo $customer_group['name']; ?></label>
                          </div>
                          <?php } else { ?>
                          <div class="radio">
                            <label>
                              <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" />
                              <?php echo $customer_group['name']; ?></label>
                          </div>
                          <?php } ?>
                          <?php } ?>
                      </div>
                  </div>
                  <div class="form-group" id="company-id-display" style="display: none;">

                    <label class="col-xs-3 control-label" for="company_id">
                        <span id="company-id-required" class="text-error">*</span> <?php echo $entry_company_id; ?>
                    </label>
                    <div class="col-xs-9  control-label">
                        <input class="form-control default" type="text" name="company_id" value="<?php echo $company_id; ?>" />
                        <?php if ($error_company_id) { ?>
                        <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_company_id; ?></div>
                        <?php } ?></td>
                    </div> <!-- col-xs-10  control-label -->

                </div> <!-- form-group -->

                <div class="form-group" id="tax-id-display" style="display: none;">

                    <label class="col-xs-3 control-label" for="tax-id">
                        <span id="tax-id-required" class="text-error">*</span> <?php echo $entry_tax_id; ?>
                    </label>
                    <div class="col-xs-9  control-label">
                        <input class="form-control default" type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
                        <?php if ($error_tax_id) { ?>
                        <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_tax_id; ?></div>
                        <?php } ?>
                    </div> <!-- col-xs-10  control-label -->

                </div> <!-- form-group -->

                

                <div class="form-group" style="display: none;">

                    <label class="col-xs-3 control-label" for="address_2">
                        <?php echo $entry_address_2; ?>
                    </label>
                    <div class="col-xs-9  control-label">
                        <input class="form-control default" type="text" name="address_2" value="<?php echo $address_2; ?>" />
                    </div> <!-- col-xs-10  control-label -->

                </div> <!-- form-group -->

                

                <div class="form-group" style="display: none;">

                    <label class="col-xs-3 control-label" for="postcode">
                        <span id="postcode-required" class="text-error">*</span> <?php echo $entry_postcode; ?>
                    </label>
                    <div class="col-xs-9  control-label">
                        <input class="form-control default" type="text" name="postcode" value="<?php echo $postcode; ?>" />
                        <?php if ($error_postcode) { ?>
                        <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_postcode; ?></div>
                        <?php } ?>
                    </div> <!-- col-xs-10  control-label -->

                </div> <!-- form-group -->

                <div class="form-group" style="display: none;">

                    <label class="col-xs-3 control-label" for="country_id">
                        <span class="text-error">*</span> <?php echo $entry_country; ?>
                    </label>
                    <div class="col-xs-9  control-label">
                        <select class="form-control" name="country_id">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($countries as $country) { ?>
                            <?php if ($country['country_id'] == $country_id) { ?>
                            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>

                        <?php if ($error_country) { ?>
                        <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_country; ?></div>
                        <?php } ?>
                    </div> <!-- col-xs-10  control-label -->

                </div> <!-- form-group -->

                <div class="form-group" style="display: none;">

                    <label class="col-xs-3 control-label" for="zone_id">
                        <span class="text-error">*</span> <?php echo $entry_zone; ?>
                    </label>
                    <div class="col-xs-9  control-label">
                        <select class="form-control" name="zone_id">
                        </select>
                        <?php if ($error_zone) { ?>
                        <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_zone; ?></div>
                        <?php } ?>
                    </div> <!-- col-xs-10  control-label -->

                </div> <!-- form-group -->

            </fieldset>

        </div> <!-- content -->


        <div class="content">
         <legend><?php echo $text_your_password; ?></legend>
         <fieldset>
            <div class="form-group col-xs-10">
                <label class="col-xs-4 control-label" for="password">
                    <span class="text-error">*</span> <?php echo $entry_password; ?>
                </label>
                <div class="col-xs-8">
                    <input class="form-control" type="password" name="password" value="" />
                    <?php if ($error_password) { ?>
                    <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_password; ?></div>
                    <?php } ?>
                </div> <!-- col-xs-10  control-label -->
            </div> <!-- form-group -->

            <div class="form-group col-xs-10">
                <label class="col-xs-4 control-label" for="confirm">
                    <span class="text-error">*</span> <?php echo $entry_confirm; ?>
                </label>
                <div class="col-xs-8">
                    <input class="form-control" type="password" name="confirm" value="" />
                    <?php if ($error_confirm) { ?>
                    <div class="alert alert-error alert-form" style="color: #F00;"><?php echo $error_confirm; ?></div>
                    <?php } ?>
                </div> <!-- col-xs-10  control-label -->
            </div> <!-- form-group -->
        </fieldset>
    </div> <!-- content -->

    <div class="content">
        <legend><?php echo $text_newsletter; ?></legend>
        <fieldset>
            <div class="form-group col-xs-10">
                <label class="col-xs-4 control-label" for="newsletter"><?php echo $entry_newsletter; ?></label>
                <div class="col-xs-8">
                    <?php if ($newsletter) { ?>
                    <label class="radio-inline">
                        <input type="radio" name="newsletter" value="1" checked="checked" />
                        <?php echo $text_yes; ?></label>
                        <label class="radio-inline">
                            <input type="radio" name="newsletter" value="0" />
                            <?php echo $text_no; ?></label>
                            <?php } else { ?>
                            <label class="radio-inline">
                                <input type="radio" name="newsletter" value="1" />
                                <?php echo $text_yes; ?></label>
                                <label class="radio-inline">
                                    <input type="radio" name="newsletter" value="0" checked="checked" />
                                    <?php echo $text_no; ?></label>
                                    <?php } ?>
                </div> 
            </div>
        </fieldset>
    </div> <!-- content -->

                <?php if ($text_agree) { ?>
                    <div class="buttons col-xs-12">
                        <?php echo $text_agree; ?>
                        <?php if ($agree) { ?>
                        <input type="checkbox" name="agree" value="1" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="agree" value="1" />
                        <?php } ?>
                        &nbsp;
                        <div class="pull-right">
                            <input type="submit" value="<?php echo $heading_title; ?>" class="btn btn-primary" />
                        </div>
                    </div>
                <?php } else { ?>
                <div class="buttons col-xs-12">
                  <div class="pull-right">
                    <input type="submit" value="<?php echo $heading_title; ?>" class="btn btn-primary" />
                </div>
            </div>
            <?php } ?>

        </form>
        <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
    </div>
    <script type="text/javascript"><!--
        $('input[name=\'customer_group_id\']').on('change', function() {
           var customer_group = [];
           
           <?php foreach ($customer_groups as $customer_group) { ?>
               customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
               customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
               customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
               customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
               customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';


               <?php } ?>	

               if (customer_group[this.value]) {
                  if (customer_group[this.value]['company_id_display'] == '1') {
                     $('#company-id-display').show();
                 } else {
                     $('#company-id-display').hide();
                 }
                 
                 if (customer_group[this.value]['company_id_required'] == '1') {
                     $('#company-id-required').show();
                 } else {
                     $('#company-id-required').hide();
                 }
                 
                 if (customer_group[this.value]['tax_id_display'] == '1') {
                     $('#tax-id-display').show();
                 } else {
                     $('#tax-id-display').hide();
                 }
                 
                 if (customer_group[this.value]['tax_id_required'] == '1') {
                     $('#tax-id-required').show();
                 } else {
                     $('#tax-id-required').hide();
                 }	
                 
             }
         });

$('input[name=\'customer_group_id\']:checked').trigger('change');//--></script> 
<script type="text/javascript"><!--
    $('select[name=\'country_id\']').on('change', function() {
       $.ajax({
          url: 'index.php?route=account/register/country&country_id=' + this.value,
          dataType: 'json',
          beforeSend: function() {
             $('select[name=\'country_id\']').after(' <i class="fa fa-spinner fa fa-spin"></i>');
         },		
         success: function(json) {
             if (json['postcode_required'] == '1') {
                $('#input-postcode').parent().parent().addClass('required');
            } else {
                $('#input-postcode').parent().parent().removeClass('required');
            }
            
            html = '<option value=""><?php echo $text_select; ?></option>';
            
            if (json['zone']) {
                for (i = 0; i < json['zone'].length; i++) {
                 html += '<option value="' + json['zone'][i]['zone_id'] + '"';
                 
                 if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                     html += ' selected="selected"';
                 }
                 
                 html += '>' + json['zone'][i]['name'] + '</option>';
             }
         } else {
            html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
        }
        
        $('select[name=zone_id]').html(html);
        $('.fa.fa-spinner').remove();
        $('select[name=zone_id]').selectpicker('refresh');
    },
    error: function(xhr, ajaxOptions, thrownError) {
      $('.fa.fa-spinner').remove();
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  }
});
});
$(document).ready(function(){
    $('select[name=\'country_id\']').trigger('change');
});
//--></script> 
<?php echo $footer; ?>