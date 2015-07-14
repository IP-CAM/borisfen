<div id="carousel1" class="flexslider carousel">
    <ul class="slides">
        <?php foreach ($manufactureres as $manufacturer_block) { ?>
        <?php foreach($manufacturer_block as $manufacturer) { ?>
        <li>
            <a href="<?php print $manufacturer['href']; ?>">
                <img src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo $manufacturer['name']; ?>" />
            </a>
        </li>
        <?php } ?>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript"><!--
    $(window).load(function() {
        $('#carousel1').flexslider({
            animation: 'slide',
            itemWidth: 276,
            itemMargin: 8,
            minItems: 0,
            maxItems: 0,
            move: 0
        });
    });
--></script>