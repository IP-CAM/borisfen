<div class="blog-item">
	<?php if( $config->get('cat_show_title') ) { ?>
	<div class="blog-header">
		<div class="title"><a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a></div>
	</div>
    <?php } ?>
	<div class="blog-body">
		<?php if( $config->get('cat_show_description') ) {?>
		<div class="description">
            <?php if( $blog['thumb'] && $config->get('cat_show_image') )  { ?>
                <a href="<?php echo $blog['link'];?>" class="thumb"><img src="<?php echo $blog['thumb'];?>" title="<?php echo $blog['title'];?>" align="left"/></a>
            <?php } ?>
            <p><?php echo $blog['description'];?></p>
            <?php if( $config->get('cat_show_readmore') ) { ?>
            <a href="<?php echo $blog['link'];?>" class="readmore btn btn-primary"><?php echo $this->language->get('text_readmore');?></a>
            <?php if( $config->get('cat_show_created') ) { ?>
            <span class="created pull-right">
                <span class="day"><?php echo date("d",strtotime($blog['created']));?>.<?php echo date("m",strtotime($blog['created']));?>.<?php echo date("y",strtotime($blog['created']));?></span>
            </span>
            <?php } ?>
            <?php } ?>
        </div>
		<?php } ?>
	</div>	
</div>