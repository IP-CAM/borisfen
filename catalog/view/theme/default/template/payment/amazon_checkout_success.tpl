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
    <h2><?php echo $heading_title; ?></h2>
    <p><?php echo $text_payment_success ?></p>
    <div id="AmazonOrderDetail"></div>
     <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
    new CBA.Widgets.OrderDetailsWidget ({
        merchantId: "<?php echo $merchant_id ?>",
        orderID: "<?php echo $amazon_order_id ?>"
    }).render ("AmazonOrderDetail");
//--></script>
<?php echo $footer; ?>