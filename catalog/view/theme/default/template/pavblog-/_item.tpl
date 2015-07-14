<div class="blog-item row">
	<?php if( $config->get('cat_show_title') ) { ?>
	<div class="blog-header col-xs-12">
		<div class="heading title">	<a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a></div>
		<?php } ?>
	</div>
	<div class="btn-group col-xs-12">
		<?php if( $config->get('cat_show_created') ) { ?>
		<span class="created btn btn-default">
			<span class="day"><?php echo date("d",strtotime($blog['created']));?></span>
			<span class="month"><?php echo date("M",strtotime($blog['created']));?></span>
			<span class="month"><?php echo date("Y",strtotime($blog['created']));?></span>
		</span>
		<?php } ?>
		<?php if( $config->get('cat_show_author') ) { ?>
		<span class="author btn btn-default"><span><?php echo $this->language->get("text_write_by");?></span> <?php echo $blog['author'];?></span>
		<?php } ?>
		<?php if( $config->get('cat_show_category') ) { ?>
		<span class="publishin btn btn-default">
			<span><?php echo $this->language->get("text_published_in");?></span>
			<a href="<?php echo $blog['category_link'];?>" title="<?php echo $blog['category_title'];?>"><?php echo $blog['category_title'];?></a>
		</span>
		<?php } ?>
		
		<?php if( $config->get('cat_show_hits') ) { ?>
		<span class="hits btn btn-default"><span><?php echo $this->language->get("text_hits");?></span> <?php echo $blog['hits'];?></span>
		<?php } ?>
		<?php if( $config->get('cat_show_comment_counter') ) { ?>
		<span class="comment_count btn btn-default"><span><?php echo $this->language->get("text_comment_count");?></span> <?php echo $blog['comment_count'];?></span>
		<?php } ?>
	</div>
	<div class="blog-body clearfix col-xs-12">
		<?php if( $blog['thumb'] && $config->get('cat_show_image') )  { ?>
		<a href="<?php echo $blog['link'];?>"><img src="<?php echo $blog['thumb'];?>" title="<?php echo $blog['title'];?>" align="left"/></a>
		<?php } ?>
		

		<?php if( $config->get('cat_show_description') ) {?>
		<div class="description">
			<?php echo $blog['description'];?>
		</div>
		<?php } ?>
		<?php if( $config->get('cat_show_readmore') ) { ?>
		<a href="<?php echo $blog['link'];?>" class="readmore btn btn-primary"><?php echo $this->language->get('text_readmore');?></a>
		<?php } ?>
	</div>	
</div>