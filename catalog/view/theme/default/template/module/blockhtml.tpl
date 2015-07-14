<?php if($show_title) { ?>
	<div class="box">
		<div class="heading"><span><?php echo $title; ?></span></div>
	<div class="box-content">
		<div class="box-html">
			<?php echo $html; ?>
		</div>
	</div>
	</div>
<?php } else { ?>
	<div class="box-html">
		<?php echo $html; ?>
	
	</div>
<?php } ?>
