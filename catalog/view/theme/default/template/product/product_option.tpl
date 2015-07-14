<div class="options">
<?php if ($options) { ?>
    <!--  <div class="title"><?php print $text_option; ?></div> -->
    <?php foreach ($options as $option) { ?>

        <?php
            foreach($option['option_value'] as $opt) {
                if ($opt['is_default']) {
                    echo "<input type='hidden' name='option[" . $option['product_option_id'] . "]' value='" . $opt['product_option_value_id'] . "'>";
                }
            }
        ?>

        <?php if ($option['type'] == 'select') { ?>
            <div class="form-group select">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>"
                    product-option-id="<?php print $option['product_option_id']; ?>" option-type="select">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?></li>
                    <li>
                        <select class="form-control option_value"
                                name="option[<?php print $option['product_option_id']; ?>]">
                            <option value="" <?php print " popup=\"$thumb\" thumb=\"$thumb\"" ?>
                                    id="option-value-0"> <?php print $text_select; ?></option>
                            <?php foreach ($option['option_value'] as $option_value) { ?>
                            <?php if ($option_value['price'] && $this->config->get('config_display_product_price')) { ?>
                            <option <?php print ($option_value['disabled'] ? ' disabled="disabled" ' : '') ?>
                                option-price-prefix="<?php print $option_value['price_prefix']; ?>"
                                option-price-value="<?php print (float)$option_value['price']; ?>"
                                value="<?php print $option_value['product_option_value_id']; ?>"
                                <?php if ($option_value['parent']) { ?>class="<?php print $option_value['parent']; ?> <?php print ($option_value['disabled'] ? ' disabled ' : '') ?>"<?php } ?><?php if ($option_value['product_option_image_thumb']) print " popup=\"{$option_value['product_option_image_popup']}\" thumb=\"{$option_value['product_option_image_thumb']}\"" ?>
                                id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?>><?php print $option_value['name']; ?>
                                <?php } else { ?>
                            <option <?php print ($option_value['disabled'] ? ' disabled="disabled" ' : '') ?>
                                value="<?php print $option_value['product_option_value_id']; ?>"
                                <?php if ($option_value['parent']) { ?>class="<?php print $option_value['parent']; ?> <?php print ($option_value['disabled'] ? ' disabled ' : '') ?>"<?php } ?><?php if ($option_value['product_option_image_thumb']) print " popup=\"{$option_value['product_option_image_popup']}\" thumb=\"{$option_value['product_option_image_thumb']}\"" ?>
                                id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?>><?php print $option_value['name']; ?>
                                <?php } ?>
                                <?php } ?>
                        </select>
                    </li>
                </ul>
            </div>
        <?php } ?>

        <?php if ($option['type'] == 'custom_text') { ?>
            <div class="form-group text">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>"
                    product-option-id="<?php print $option['product_option_id']; ?>">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <?php foreach ($option['option_value'] as $option_value) { ?>
                        <?php print $option_value['name']; ?><br>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if ($option['type'] == 'radiocolor') { ?><!--цвет radio-->
            <div class="form-group color">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>"
                    product-option-id="<?php print $option['product_option_id']; ?>" option-type="radio">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?></li>
                    <?php foreach ($option['option_value'] as $option_value) { ?>
                        <li>
                            <label class="radio <?php print ($option_value['disabled'] ? ' disabled ' : '') ?>"
                                   title="<?php print $option_value['name']; ?>" data-toggle="tooltip"
                                   for="option-value-<?php print $option_value['product_option_value_id']; ?>">
                                <?php if (!$option_value['color'] && isset($option_value['bg_image']) && $option_value['bg_image']) { ?>
                                    <span
                                        style="background-color: #fff; background-image: url('<?php print $option_value['bg_image']; ?>');"></span>
                                <?php } else { ?>
                                    <span style="background-color: <?php print $option_value['color']; ?>"></span>
                                <?php } ?>
                                <?php if ($option_value['price'] && $this->config->get('config_display_product_price')) { ?>
                                    <input option-price-prefix="<?php print $option_value['price_prefix']; ?>"
                                           option-price-value="<?php print (float)$option_value['price']; ?>"
                                           class="additional-option option_value" type="radio"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } else { ?>
                                    <input class="additional-option option_value" type="radio"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } ?>
                                <input type="hidden"
                                       class="product_option_image_container"<?php if ($option_value['product_option_image_thumb']) print " popup=\"{$option_value['product_option_image_popup']}\" thumb=\"{$option_value['product_option_image_thumb']}\"" ?> />
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'radiolabel') { ?><!--Альтернативный radio-->
            <div class="form-group button">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>"
                    product-option-id="<?php print $option['product_option_id']; ?>" option-type="radio">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?></li>
                    <?php foreach ($option['option_value'] as $option_value) { ?>
                        <li>
                            <label class="radio  <?php print ($option_value['disabled'] ? ' disabled ' : '') ?>"
                                   for="option-value-<?php print $option_value['product_option_value_id']; ?>"><span><?php print $option_value['name']; ?></span>
                                <?php if ($option_value['price'] && $this->config->get('config_display_product_price')) { ?>
                                    <input option-price-prefix="<?php print $option_value['price_prefix']; ?>"
                                           option-price-value="<?php print (float)$option_value['price']; ?>"
                                           class="additional-option option_value" type="radio"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } else { ?>
                                    <input class="additional-option option_value" type="radio"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } ?>
                                <input type="hidden"
                                       class="product_option_image_container"<?php if ($option_value['product_option_image_thumb']) print " popup=\"{$option_value['product_option_image_popup']}\" thumb=\"{$option_value['product_option_image_thumb']}\"" ?> />
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'checkboxlabel') { ?><!--Альтернативный checkbox-->
            <div class="form-group button">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>"
                    product-option-id="<?php print $option['product_option_id']; ?>" option-type="checkbox">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <?php foreach ($option['option_value'] as $option_value) { ?>
                        <li>
                            <label class="radio  <?php print ($option_value['disabled'] ? ' disabled ' : '') ?>"
                                   for="option-value-<?php print $option_value['product_option_value_id']; ?>"><span><?php print $option_value['name']; ?></span>
                                <?php if ($option_value['price'] && $this->config->get('config_display_product_price')) { ?>
                                    <input option-price-prefix="<?php print $option_value['price_prefix']; ?>"
                                           option-price-value="<?php print (float)$option_value['price']; ?>"
                                           class="additional-option option_value" type="radio"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } else { ?>
                                    <input class="additional-option option_value" type="radio"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?>/>
                                <?php } ?>
                                <input type="hidden"
                                       class="product_option_image_container"<?php if ($option_value['product_option_image_thumb']) print " popup=\"{$option_value['product_option_image_popup']}\" thumb=\"{$option_value['product_option_image_thumb']}\"" ?> />
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'checkboxcolor') { ?><!--Цвет checkbox-->
            <div class="form-group color">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>"
                    product-option-id="<?php print $option['product_option_id']; ?>" option-type="checkbox">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?>:
                    </li>
                    <?php foreach ($option['option_value'] as $option_value) { ?>
                        <li>
                            <label class="radio  <?php print ($option_value['disabled'] ? ' disabled ' : '') ?>"
                                   title="<?php print $option_value['name']; ?>" data-toggle="tooltip"
                                   for="option-value-<?php print $option_value['product_option_value_id']; ?>">
                                <?php if (!$option_value['color'] && isset($option_value['bg_image']) && $option_value['bg_image']) { ?>
                                    <span
                                        style="background-color: #fff; background-image: url('<?php print $option_value['bg_image']; ?>');"></span>
                                <?php } else { ?>
                                    <span style="background-color: <?php print $option_value['color']; ?>"></span>
                                <?php } ?>
                                <?php if ($option_value['price'] && $this->config->get('config_display_product_price')) { ?>
                                    <input option-price-prefix="<?php print $option_value['price_prefix']; ?>"
                                           option-price-value="<?php print (float)$option_value['price']; ?>"
                                           class="additional-option option_value" type="radio"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } else { ?>
                                    <input class="additional-option option_value" type="radio"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } ?>
                                <input type="hidden"
                                       class="product_option_image_container"<?php if ($option_value['product_option_image_thumb']) print " popup=\"{$option_value['product_option_image_popup']}\" thumb=\"{$option_value['product_option_image_thumb']}\"" ?> />
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>
            <div class="form-group radioD">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>"
                    product-option-id="<?php print $option['product_option_id']; ?>" option-type="radio">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?></li>
                    <?php foreach ($option['option_value'] as $option_value) { ?>
                        <li>
                            <label class="radio  <?php print ($option_value['disabled'] ? ' disabled ' : '') ?>"
                                   for="option-value-<?php print $option_value['product_option_value_id']; ?>"><span><?php print $option_value['name']; ?></span>
                                <?php if ($option_value['price'] && $this->config->get('config_display_product_price')) { ?>
                                    <input option-price-prefix="<?php print $option_value['price_prefix']; ?>"
                                           option-price-value="<?php print (float)$option_value['price']; ?>"
                                           type="radio" class="option_value"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } else { ?>
                                    <input type="radio" class="option_value"
                                           name="option[<?php print $option['product_option_id']; ?>]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } ?>
                                <input type="hidden"
                                       class="product_option_image_container"<?php if ($option_value['product_option_image_thumb']) print " popup=\"{$option_value['product_option_image_popup']}\" thumb=\"{$option_value['product_option_image_thumb']}\"" ?> />
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
            <div class="form-group checkboxD">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>"
                    product-option-id="<?php print $option['product_option_id']; ?>" option-type="checkbox">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <?php foreach ($option['option_value'] as $option_value) { ?>
                        <li>
                            <label class="checkbox  <?php print ($option_value['disabled'] ? ' disabled ' : '') ?>"
                                   for="option-value-<?php print $option_value['product_option_value_id']; ?>"><span><?php print $option_value['name']; ?></span>

                                <?php if ($option_value['price'] && $this->config->get('config_display_product_price')) { ?>
                                    <input option-price-prefix="<?php print $option_value['price_prefix']; ?>"
                                           option-price-value="<?php print (float)$option_value['price']; ?>"
                                           type="checkbox" class="option_value"
                                           name="option[<?php print $option['product_option_id']; ?>][]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } else { ?>
                                    <input type="checkbox" class="option_value"
                                           name="option[<?php print $option['product_option_id']; ?>][]"
                                           value="<?php print $option_value['product_option_value_id']; ?>"
                                           id="option-value-<?php print $option_value['product_option_value_id']; ?>"
                                           <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                <?php } ?>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'image') { ?>
            <div class="image form-group hidden<?php print ($option['required'] ? ' required' : ''); ?>">
                <label class="control-label"><?php print $option['name']; ?></label>

                <div id="input-option<?php print $option['product_option_id']; ?>" class="row option">
                    <ul class="radio list-unstyled thumbnails option"
                        product-option-id="<?php print $option['product_option_id']; ?>" option-type="radio">
                        <?php $i = 0;
                        foreach ($option['option_value'] as $option_value) {
                            ?>
                            <li class="image-additional gallery col-xs-2">
                                <label for="<?php echo $i; ?>"
                                       class="<?php print ($option_value['disabled'] ? ' disabled ' : '') ?> F<?php print $option_value['product_option_value_id']; ?>">
                                    <?php if ($option_value['price'] && $this->config->get('config_display_product_price')) { ?>
                                        <input option-price-prefix="<?php print $option_value['price_prefix']; ?>"
                                               option-price-value="<?php print (float)$option_value['price']; ?>"
                                               type="radio" class="option_value not_custom"
                                               name="option[<?php print $option['product_option_id']; ?>]"
                                               value="<?php print $option_value['product_option_value_id']; ?>"
                                               id="<?php echo $i; ?>"
                                               <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                    <?php } else { ?>
                                        <input type="radio" class="option_value not_custom"
                                               name="option[<?php print $option['product_option_id']; ?>]"
                                               value="<?php print $option_value['product_option_value_id']; ?>"
                                               id="<?php echo $i; ?>"
                                               <?php if ($option_value['parent']) { ?>parent-value-id="<?php print $option_value['parent']; ?>"<?php } ?> />
                                    <?php } ?>
                                    <a class="thumbnail gallery">
                                        <img popup="<?php print $option_value['image_popup']; ?>"
                                             thumb="<?php print $option_value['image_thumb']; ?>"
                                             src="<?php print $option_value['image']; ?>"
                                             alt="<?php print $option_value['name'] . (($option_value['price'] && $this->config->get('config_display_product_price')) ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>"/>
                                        <span><?php print $option_value['name']; ?></span>
                                    </a>
                                </label>
                            </li>
                            <?php $i++; ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'text') { ?>
            <div class="form-group text">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <input class="form-control " type="text"
                           name="option[<?php print $option['product_option_id']; ?>]"
                           value="<?php print $option['option_value']; ?>"/>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
            <div class="form-group textarea">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <textarea class="form-control " name="option[<?php print $option['product_option_id']; ?>]"
                              cols="40" rows="5"><?php print $option['option_value']; ?></textarea>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'file') { ?>
            <div class="file form-group">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <input type="button" class="form-control btn btn-default btn-block"
                           value="<?php print $button_upload; ?>"
                           id="button-option-<?php print $option['product_option_id']; ?>">

                    <input type="hidden" name="option[<?php print $option['product_option_id']; ?>]" value=""/>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'date') { ?>
            <div class="date form-group">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <input class="form-control  date" type="text"
                           name="option[<?php print $option['product_option_id']; ?>]"
                           value="<?php print $option['option_value']; ?>"/>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'datetime') { ?>
            <div class="datetime form-group">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <input class="form-control  datetime" type="text"
                           name="option[<?php print $option['product_option_id']; ?>]"
                           value="<?php print $option['option_value']; ?>"/>
                </ul>
            </div>
        <?php } ?>
        <?php if ($option['type'] == 'time') { ?>
            <div class=" time form-group">
                <ul class="list-unstyled option" id="option-<?php print $option['product_option_id']; ?>">
                    <li>
                        <?php if ($option['required']) { ?>
                            <div class="text-danger">*</div>
                        <?php } ?>
                        <?php print $option['name']; ?> </li>
                    <input class="form-control  time" type="text"
                           name="option[<?php print $option['product_option_id']; ?>]"
                           value="<?php print $option['option_value']; ?>"/>
                </ul>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>
</div>

<?php if ($chained_options) { ?>
    <script type="text/javascript"><!--
        var chained_options = <?php print json_encode($chained_options);?>;
        $(document).on('change', 'select', function () {
            $('select').selectpicker('update');
        });
        //--></script>
<?php } ?>
<script>
    $(document).ready(function () {
        $(document).on('click', '.additional-option', function () {
            if ($(this).attr('type') == 'radio') {
                var radioName = $(this).attr('name');
                $('input[name=\'' + radioName + '\']').each(function () {
                    $(this).parent('label').children('span').removeClass('active');
                });
                $(this).parent('label').children('span').addClass('active');
            } else if ($(this).attr('type') == 'checkbox') {
                $(this).parent('label').children('span').toggleClass('active');
            }

        });
    });

    $(document).ready(function () {
        $(document).on('click', '.image.form-group li', function () {
            $('.image.form-group li').each(function () {
                $(this).removeClass('active');
            });
            var thumb = $(this).children('label').children('.thumbnail').children('img').attr('thumb');
            //var popup = $(this).children('label').children('.thumbnail').children('img').attr('popup');
            //$('.bigImgUl > .gallery > a').attr('href', popup);
           // $('.bigImgUl > .gallery > a > img').attr('src', thumb);
            $(this).addClass('active');
        });

        $("label[for^='option-value']").on('click', function () {
            var image = $(this).children('.product_option_image_container');
            if (typeof $(image).attr('thumb') != 'undefined') {
                $('.bigImgUl > .gallery > a').attr('href', $(image).attr('popup'));
                $('.bigImgUl > .gallery > a > img').attr('src', $(image).attr('thumb'));
            }
        });

        $('select.form-control.option_value').on('change', function () {
            var optionSelected = $("option:selected", this);
            var thumb = $(optionSelected).attr('thumb');
            var popup = $(optionSelected).attr('popup');
            if (typeof thumb != 'undefined') {
                $('.bigImgUl > .gallery > a').attr('href', popup);
                $('.bigImgUl > .gallery > a > img').attr('src', thumb);
            }
        });

    });
</script>