<div class="row">
    <?php foreach ($blogs as $key => $blog) { ?>
        <a class="cont-link-block ru" href="<?php echo $blog['link']; ?>"><?php echo $blog['title']; ?></a>
    <?php } ?>
</div>