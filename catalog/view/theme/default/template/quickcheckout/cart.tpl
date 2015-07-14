<style>
  .qc.popup {
    width: <?php echo $settings['general']['cart_image_size']['width']; ?>px;
    height: <?php echo $settings['general']['cart_image_size']['height']; ?>px;
  }
</style>
<div class="opt-box" style="display: none"><b>*</b> Оптовая цена начисляется автоматически при покупке от n' единиц товара</div>
<div id="cart_wrap">
    <div class="checkout-product <?php echo (!$data['display']) ? 'hide' : ''; ?>" >
        <?php if(isset($error)){ foreach ($error as $error_message){ ?>
        <div class="error"><?php echo $error_message; ?></div>
        <?php } } ?>
        <table class="cart table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="image <?php echo (!$data['columns']['image'])?  'hide' :""; ?>"><?php echo $column_name; ?></th>
                    <th class="name <?php echo (!$data['columns']['name'])?  'hide' :""; ?>"></th>
                    <th class="quantity <?php echo (!$data['columns']['quantity'])?  'hide' :""; ?>"><?php echo $column_quantity; ?></th>
                    <th class="price  <?php echo (!$data['columns']['price'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'hide' :""; ?> "><?php echo $column_price; ?></th>
                    <th class="total <?php  echo (!$data['columns']['total'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'hide' :""; ?>"><?php echo $column_total; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $pn = 0; foreach ($products as $product) { ?>
                <tr <?php echo(!$product['stock']) ? 'class="stock"' : '' ;?>>
                    <td class="image <?php echo (!$data['columns']['image'])?  'hide' : '' ?> ">
                        <a rel="popup" data-help='<img src="<?php echo $product['image']; ?>" />'  href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" /></a>
                        <i rel="tooltip" data-help="'.$field['tooltip'] .'"></i>
                    </td>
                    <td class="name <?php echo (!$data['columns']['name'])?  'hide' : '' ?> ">
                        <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?> <?php echo (!$product['stock'])? '<span class="out-of-stock">***</span>' : '' ?></a>
                        <?php foreach ($product['option'] as $option) { ?>
                        <div> &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small></div>
                        <?php } ?>
                        <span class="model"><?php echo $product['model']; ?></span>
                        <span class="model">Вес: <?php echo $product['weight']; ?> кг.</span>
						<?if($product['num_in_pack']){?>
							<span class="model">Кол-во в упаковке: <?php echo $product['num_in_pack']; ?> шт.</span>
						<? } ?>
                    </td>
                    <td class="quantity <?php echo (!$data['columns']['quantity'])?  'hide' : '' ?> ">
                        <div class="quantity">
                            <i class="icon-small-minus minus decrease fa fa-minus" data-product="<?php echo $product['product_id']; ?>"></i>
                            <input type="text" value="<?php echo $product['quantity']; ?>" class="product-qantity form-control" name="cart[<?php echo $product['product_id']; ?>]"  data-refresh="6"  />
                            <i class="icon-small-plus plus increase fa fa-plus" data-product="<?php echo $product['product_id']; ?>"></i>
                        </div>
                    </td>
                    <td class="price <?php echo (!$data['columns']['price'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'hide' : ''; ?> "><?php echo $product['price']; ?></td>
                    <td class="total <?php echo (!$data['columns']['total'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'hide' : ''; ?> ">
                        <?php echo $product['total']; ?>
                        <span class="removeItem" style="cursor: pointer;"><i class="fa fa-times"></i></span>
                    </td>
                </tr>
                <?php $pn++; } ?>
                <?php foreach ($vouchers as $vouchers) { ?>
                <tr>
                  <td class="name <?php echo (!$data['columns']['image'])?  'hide' : '' ?> "></td>
                  <td class="name <?php echo (!$data['columns']['name'])?  'hide' : '' ?> "><?php echo $vouchers['description']; ?></td>
                  <td class="model <?php echo (!$data['columns']['model'])?  'hide' : '' ?> "></td>
                  <td class="quantity <?php echo (!$data['columns']['quantity'])?  'hide' : '' ?> ">1</td>
                  <td class="price <?php echo (!$data['columns']['price'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'hide' : ''; ?> "><?php echo $vouchers['amount']; ?></td>
                  <td class="total <?php echo (!$data['columns']['total'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'hide' : '' ?> "><?php echo $vouchers['amount']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <table class="table summary">
            <tbody class=" <?php if($this->config->get('config_customer_price') && !$this->customer->isLogged()){ echo 'hide';}?>">
                <tr class="coupon <?php if(!$coupon_status || !$data['option']['coupon']['display']){ echo 'hide';} ?>">
                    <td class="text" ><b><?php echo $text_use_coupon; ?>:</b></td>
                    <td class="total">
                        <input type="text" class="form-control" value="<?php echo (isset($coupon))?  $coupon : ''; ?>" name="coupon" id="coupon"  />
                        <i class="icon-confirm" id="confirm_coupon"></i>
                    </td>
                </tr>
                <tr class="voucher <?php if(!$voucher_status || !$data['option']['voucher']['display']){ echo 'hide';} ?>">
                    <td  class="text"><b><?php echo $text_use_voucher; ?>:</b></td>
                    <td class="total">
                        <input type="text" class="form-control" value="<?php echo (isset($voucher))?  $voucher : ''; ?>" name="voucher" id="voucher"  />
                        <i class="icon-confirm" id="confirm_voucher"></i>
                    </td>
                </tr>
                <tr class="reward <?php if(!$reward_status || !$data['option']['reward']['display']){ echo 'hide';} ?>">
                    <td  class="text"><b><?php echo $text_use_reward; ?>:</b></td>
                    <td class="total">
                        <input type="text" class="form-control" value="<?php echo (isset($reward))?  $reward : ''; ?>" name="reward" id="reward"  />
                        <i class="icon-confirm" id="confirm_reward"></i>
                    </td>
                </tr>
                <?php $i=0; foreach ($totals as $total) { ?>
                <tr>
                    <td class="text go-shoper"><?php if ($i== 0) { ?> <a href="/">Заказать что-нибудь еще</a> <?php } ?></td>
                    <td class="total"><b><?php echo $total['title']; ?>:</b> <?php echo $total['text']; ?></td>
                </tr>
                <?php $i++; } ?>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>
<div id="cart-incognito">
    <div class="title">Оформление заказа</div>
    <div class="guest-rega">
        <div class="incognito-guest"></div>
        <div class="incognito-rega"></div>
    </div>
    <div class="row">
        <div id="cart-one" class="col-xs-4">
            <!--<div class="incognito-name"></div>
            <div class="incognito-phone"></div>
            <div class="incognito-mail"></div>
            <div class="incognito-pass"></div>
            <div class="incognito-rpass"></div>-->
        </div>
        <div id="cart-two" class="col-xs-4">
            <div class="incognito-country"></div>
            <div class="incognito-zone"></div>
            <div class="incognito-address"></div>
            <div class="incognito-comment"></div>
        </div>
        <div id="cart-three" class="col-xs-4">
            <div class="incognito-shipping_method"></div>
            <div class="incognito-payment_method"></div>
            <div class="incognito-sum"></div>
            <div class="incognito-required"></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    /*$( "#payment_address_firstname" ).appendTo( $( ".incognito-name" ) );
    $( "#payment_address_telephone" ).appendTo( $( ".incognito-phone" ) );
    $( "#payment_address_email" ).appendTo( $( ".incognito-mail" ) );
    $( "#payment_address_password" ).clone().appendTo( $( ".incognito-pass" ) );
    $( "#payment_address_confirm" ).clone().appendTo( $( ".incognito-rpass" ) );*/
    
    $( "#option_guest_popup" ).appendTo( $( ".incognito-guest" ) );
    $( "#option_register_popup" ).appendTo( $( ".incognito-rega" ) );
    
    $( "#payment_address_wrap" ).appendTo( $( "#cart-one" ) );
   
    //$( "#payment_address_city" ).clone().appendTo( $( ".incognito-city" ) );
    
    //$( "#country_id_input" ).appendTo( $( ".incognito-country" ) );
    //$( "#zone_id_input" ).clone().appendTo( $( ".incognito-zone" ) );
    
    $( "#payment_address_address_1" ).clone().appendTo( $( ".incognito-country" ) );
    $( "#confirm_comment" ).clone().appendTo( $( ".incognito-comment" ) );
    $(document).on('click','.error',function() {
        console.log('asdwetr');
        $(this).addClass('hide');
    });
    
    $( ".shipping-method-select" ).appendTo( $( ".incognito-shipping_method" ) );
    $( ".payment-method-select" ).appendTo( $( ".incognito-payment_method" ) );
    $( "#cart_wrap .table.summary tr > .go-shoper+td.total" ).clone().appendTo( $( ".incognito-sum" ) );
    $( "#confirm_order" ).clone().appendTo( $( ".incognito-required" ) );
    if ( $('input#guest').prop( "checked" ) ) {
        console.log('123');
        $('.incognito-pass').addClass('hide');
        $('.incognito-rpass').addClass('hide');
    }
    $('.aqc-column-2').addClass('hide');
});
</script>
<script><!--
$(function(){
    if($.isFunction($.fn.uniform)){
        $(" .styled, input:radio.styled").uniform().removeClass('styled');
    }
    if($.isFunction($.fn.colorbox)){
        $('.colorbox').colorbox({
            width: 640,
            height: 480
        });
    }
    if($.isFunction($.fn.fancybox)){
        $('.fancybox').fancybox({
            width: 640,
            height: 480
        });
    }
});
//--></script>