<?php if ((!$this->config->get('disable_product_series')) && isset($series) && !empty($series)) { ?>
    <?php foreach ($series as $sery) { ?>
        <div class="series-box options ">
            <div class="Tlabel">Объем</div>
            <?php $i = 0; ?>
            <div class="image form-group">
                <ul class="radio list-unstyled thumbnails option">
                    <?php foreach ($sery['products'] as $product) { ?>
                        <?php // print_r($product['litters']);
                                if(count($product['litters'])):?>
                            <?php if ($product['is_current_product'] == true) { ?>
                            <li class="image-additional gallery col-xs-2 active not-img">
                                <label class="<?php print 'F'.str_replace(',','',$product['litters']['name']); ?>">
                                    <span class="thumbnail gallery">
                                        <img src="<?php print $product['litters']['product_option_image']; ?>" alt="<?php print $product['name']; ?>">
                                        <span><?php print $product['litters']['name']; ?></span>
                                    </span>
                                </label>

                                <input type="hidden" value="<?php print $product['litters']['option_value_id']; ?>" name="option[<?php echo OPTION_LITTERS; ?>]" />
                            </li>
                            <?php } else { ?>
                            <li class="image-additional gallery col-xs-2 not-img">
                                <label class="<?php print 'F'.str_replace(',','',$product['litters']['name']); ?>">
                                    <a href="<?php print $product['href']; ?>" class="thumbnail gallery">
                                        <img src="<?php print $product['litters']['product_option_image']; ?>" alt="<?php print $product['name']; ?>">
                                        <span><?php print $product['litters']['name']; ?></span>
                                    </a>
                                </label>
                            </li>
                            <?php } ?>
                            <?php $i++; ?>
                        <?endif;?>
                    <?php } ?>
                </ul>
            </div>
        </div>
    <?php } ?>
<?php } ?>