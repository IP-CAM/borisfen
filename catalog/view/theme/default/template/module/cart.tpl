<div id="cart" class="btn-group btn-block">
  <button type="button" data-toggle="dropdown" class="btn btn-inverse btn-block btn-lg dropdown-toggle">
      <span id="cart-total"><?php echo $text_items; ?></span>
  </button>
  <ul class="dropdown-menu pull-right">
    <?php if ($products || $vouchers) { ?>
    <li>
      <table class="table">
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="text-center thumb"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
            <?php } ?></td>
          <td class="text-left name">
             <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
             <div class="total"><?php echo $product['total']; ?></div>
                <?php if ($product['option']) { ?>
                    <br />
                    <?php foreach ($product['option'] as $option) { ?>
                    <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                    <?php } ?>
                    <?php if ($product['recurring']): ?>
                    <small><?php echo $text_payment_profile ?>: <?php echo $product['profile']; ?></small><br />
                <?php endif; ?>
                <small>Вес: <?php print $product['weight']; ?> кг.</small><br />
            <?php } ?>
            </td>
          <td class="text-left quantity"><?php echo $product['quantity']; ?>&nbsp;шт</td>
                    <td class="text-right cancel"><button type="button" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $product['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>' + ' #cart > *'); $('[onclick=\'addToCart(\'' + <?php print $product['product_id']; ?> + '\');\']').removeClass('in-cart');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs removeProductB" product-id="<?php print $product['product_id']; ?>"><i class="fa fa-times"></i></button></td>
        </tr>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="text-center"></td>
          <td class="text-left description"><?php echo $voucher['description']; ?></td>
          <td class="text-right">x&nbsp;1</td>
          <td class="text-right amount"><?php echo $voucher['amount']; ?></td>
          <td class="text-right text-danger"><button type="button" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $voucher['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $voucher['key']; ?>' + ' #cart > *');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <?php } ?>
        <?php } ?>
      </table>
    </li>
    <li>
      <div>
        <table class="table table-bordered">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
            <td class="text-right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
        <p class="text-right goToCart"><a href="<?php echo $cart; ?>"><strong><i class="fa fa-shopping-cart"></i> <?php echo $text_cart; ?></strong></a></p>
      </div>
    </li>
    <?php } else { ?>
    <li><p class="text-center"><?php echo $text_empty; ?></p></li>
    <?php } ?>
  </ul>
</div>
