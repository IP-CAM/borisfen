<?php if(!empty($blog)) { ?>
	<div class="box blogevent">
		<div class = "heading"><span><?php echo $heading_title; ?></span></div>
		<div class="row widget-inline">
			<div class="widget">
				// Заголовок
				<?php print $blog['title']; ?>
				// Дата праздника
				<?php print $blog['date_event']; ?>
				// Описание
				<?php print $blog['description']; ?>
				// изображение
				<?php print $blog['image']; ?>
				// Ссылка
				<?php print $blog['href']; ?>

			</div>
		</div>
	</div>
<?php } ?>