<?php if(!empty($min_price) && !empty($max_price)) { ?>
<div class="box filter">
  <div class="heading"><span><?php echo $heading_title; ?></span></div>
  <div class="box-content" id="product-filter">
    <form action="">
      <?php if ($selecteds) { ?>
      <?php # Selected options ?>
      <div id="selecteds">
        <?php foreach ($selecteds as $option) { ?>
        <div class="filter-option">
          <span><?php echo $option['name']; ?>:</span>
          <?php foreach ($option['values'] as $value) { ?>
          <a rel="nofollow" href="<?php echo $value['href']; ?>" class="cancel-small"><i class="fa fa-times"></i><?php echo $value['name']; ?></a>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <?php if (isset($max_price) && $max_price) { ?>
      <?php # Price filtering ?>
      <div class="filter-option" id="filter-price">
        <div class="option-name">
          <?php echo $text_price; ?>

          <a rel="nofollow" href="#" class="button" id="filter-button" style="display: block !important;"><span><?php echo $button_select; ?></span></a>

        </div>
        <div id="price-handlers" class="option-values">
           <div class="priceValue row">
                  <div class="col-xs-5"><input type="text" id="p-from" class="form-control number" value="<?php echo ($min_price_get ? $min_price_get : $min_price); ?>"></div>
                  <div class="col-xs-2 text-left"><span>-</span></div>
                  <div class="col-xs-5"><input type="text" id="p-to" class="form-control number" value="<?php echo ($max_price_get ? $max_price_get : $max_price); ?>"></div>
                  <!--<div class="col-xs-1 text-right"><span><?php echo $this->currency->getSymbolRight(); ?><?php echo $this->currency->getSymbolLeft(); ?></span></div>-->
           </div>
           <div id="scale"></div>
          <?php if ($diagram) { ?>
          <div id="diagram">
            <div id="field">
              <?php foreach ($diagram as $key => $item) { ?>
              <div style="height:<?php echo $item['height']; ?>;width:<?php echo $item['width']; ?>;left:<?php echo $item['width']*$key; ?>px;"></div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
        </div>
        <?php if (($filter_position == 'column_left' || $filter_position == 'column_right') && !$show_button) { ?>
        <div id="price-button"><a rel="nofollow" href="#" class="button" id="filter-button"><span><?php echo $button_select; ?></span></a></div>
        <?php } ?>
      </div>
      <?php } ?>

      <?php if ($hide_options) { ?>
      <div class="filter-buttons"><a href="#" id="show-hide-options" class="button"><span><?php echo $button_show; ?></span></a></div>
      <?php $id = 'other-options-hide'; ?>
      <?php } else { ?>
      <?php $id = 'other-options'; ?>
      <?php } ?>

      <div id="<?php echo $id; ?>">
        <div id="easyPreloaderFilter"><div class="circle"></div><div class="circle1"></div></div><div id="easyPreloaderFilterModal" style="display: none;" class="modal-backdrop fade in"></div>
        <?php foreach ($options as $option) {?>
        <div class="filter-option" option-id="<?php echo $option['option_id'];?>">
          <div class="option-name">
            <?php echo $option['name']; ?>

            <?php if ($option['description']) { ?>
            <a href="#" onclick="return false;"></a>
            <span class="description"><?php echo $option['description']; ?></span>
            <?php } ?>
          </div>
          <?php if ($option['values']) { ?>
          <div class="option-values <?php if($option['type'] == 'radiocolor') { ?>colorWrapper<?php } ?> <?php print $option['type']; ?> ">
            <?php if ($option['type'] == 'select' || $option['type'] == 'image') { ?>
            <?php # Select type start ?>
            <label>
              <select data-size="5" name="option[<?php echo $option['option_id']; ?>]"<?php echo ($option['selected'] ? ' class="selected"' : ''); ?>>
                <?php foreach ($option['values'] as $value) { ?>
                <?php if ($value['selected']) { ?>
                <option value="<?php echo $value['params']; ?>" id="v-<?php echo $option['option_id'] . $value['value_id']; ?>" selected="selected"><?php echo $value['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $value['params']; ?>" id="v-<?php echo $option['option_id'] . $value['value_id']; ?>" disabled="disabled"><?php echo $value['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </label>
            <?php # Select type end ?>
            <?php } elseif ($option['type'] == 'radio' || $option['type'] == 'radiocolor' || $option['type'] == 'radiolabel'|| $option['type'] == 'custom_text') { ?>
            <?php # Radio type start ?>
            <?php foreach ($option['values'] as $value) { ?>
            <?php if($option['type'] == 'radiocolor' && isset($value['color']) && $value['color']) { ?>
            <?php if ($value['selected']) { ?>
            <label style="background-color: <?php echo $value['color']; ?>" id="v-<?php echo $option['option_id'] . $value['value_id']; ?>" class="selected isColorized"><input type="radio" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" checked="checked" /><?php if ($show_counter) { ?><small></small><?php } ?></label>
            <?php } else { ?>
            <label style="background-color: <?php echo $value['color']; ?>" id="v-<?php echo $option['option_id'] . $value['value_id']; ?>" class="disabled isColorized"><input type="radio" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" disabled="disabled" /><?php if ($show_counter) { ?><small></small><?php } ?></label>
            <?php } ?>
            <?php } else {?>
            <?php if ($value['selected']) { ?>
            <label id="v-<?php echo $option['option_id'] . $value['value_id']; ?>" class="selected notColorized"><input type="radio" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" checked="checked" /><?php echo $value['name']; ?><?php if ($show_counter) { ?><small></small><?php } ?></label>
            <?php } else { ?>
            <label id="v-<?php echo $option['option_id'] . $value['value_id']; ?>" class="disabled notColorized"><input type="radio" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" disabled="disabled" /><?php echo $value['name']; ?><?php if ($show_counter) { ?><small></small><?php } ?></label>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php # Radio type end ?>
            <?php } elseif ($option['type'] == 'checkbox' || $option['type'] == 'group' || $option['type'] == 'checkboxcolor' || $option['type'] == 'checkboxlabel') { # if is checkbox ?>
            <?php # Checkbox type start ?>
            <?php foreach ($option['values'] as $value) { ?>
            <?php if ($value['selected']) { ?>
            <label id="v-<?php echo $option['option_id'] . $value['value_id']; ?>" class="selected"><input type="checkbox" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" checked="checked" /><?php echo $value['name']; ?><?php if ($show_counter) { ?><small></small><?php } ?></label>
            <?php } else { ?>
            <label id="v-<?php echo $option['option_id'] . $value['value_id']; ?>" class="disabled"><input type="checkbox" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" disabled="disabled" /><?php echo $value['name']; ?><?php if ($show_counter) { ?><small></small><?php } ?></label>
            <?php } ?>
            <?php } ?>
            <?php # Checkbox type end ?>
            <?php } # End type switcher ?>
          </div>
          <?php } # End "if values" ?>
        </div>
      <?php } # End "foreach $options" ?>
      </div>

      <?php if ($show_button) { ?>
      <div class="filter-buttons" id="button-bottom"><a href="#" class="button" id="filter-button"><span><?php echo $button_select; ?></span></a></div>
      <?php } ?>

      <?php if (count($selecteds) > 0 || (isset($selecteds[0]) && count($selecteds[0]['values']) > 0)) { ?>
      <div class="resetAll"><a rel="nofollow" href="<?php echo $link; ?>" class="cancel-large"><i class="fa fa-times"></i><?php echo $text_cancel_all; ?></a></div>
      <?php } ?>
    </form>
  </div>

  <script type="text/javascript"><!--
var filter = {
  tag: {
    vals  : '#v-',   // ID value tag
    count : 'small'  // Product counter tag
  },
  slide: {
    width        : 206, // Scale width (px)
    widthRem     : 14,  // Slider handler width (px)
    leftLimit    : <?php echo $min_price; ?>, // Start limit
  	leftValue    : <?php echo ($min_price_get ? $min_price_get : $min_price); ?>, // Left value
  	rightLimit   : <?php echo $max_price; ?>, // End limit
  	rightValue   : <?php echo ($max_price_get ? $max_price_get : $max_price); ?>  // Right value
  },
  url: {
    link   : '<?php echo $link; ?>'
  },
  php: {
    showButton   : <?php echo $show_button; ?>,
    scrollButton : <?php echo $scroll_button; ?>,
    showPrice    : <?php echo $show_price; ?>,
    showCounter  : <?php echo $show_counter; ?>,
    total        : <?php echo $total; ?>,
    path         : '<?php echo $path; ?>',
    params       : '<?php echo $params; ?>',
    price        : '<?php echo $price; ?>'
  },
  text: {
    show   : '<?php echo $button_show; ?>',
    hide   : '<?php echo $button_hide; ?>',
    select : '<?php echo $button_select; ?>',
    load   : '<?php echo $text_load; ?>'
  }
}
/*checkbox*/

    $('input[type="checkbox"]').checkbox();
//--></script>

<?php if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) { ?>
<?php if ($show_price) { ?>
<script type="text/javascript" src="catalog/view/javascript/filter/trackbar.js"></script>
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/filter/filter.js"></script>
<?php } ?>
</div>

<?php } ?>