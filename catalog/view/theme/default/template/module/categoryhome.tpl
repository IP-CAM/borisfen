<div class="row catImg">
    <?php foreach ($categoryhome as $categoryhome) { ?>
    <div class="col-xs-3">
        <a href="<?php echo $categoryhome['href']; ?>">
            <img alt="" src="<?php echo $categoryhome['thumb']; ?>" />
        </a>
    </div>
    <?php } ?>
</div>