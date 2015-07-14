<?php echo $header; ?>
<script>
    $('body').addClass('not_found');
</script>
<div class="container">
    <div class="p404">
        <img src="/catalog/view/theme/default/image/404.png" class="img404" />
        <div class="inf-text">Такой страницы нет!</div>
        <span class="desc-text">Чтобы вернуться на главную<br> страницу нажмите <a href="/">сюда</a></span>
        <img src="/catalog/view/theme/default/image/logo.png" class="flogo" />
    </div>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
    <?php } else { ?>
    <?php $cols = 12; ?>
    <?php } ?>  
    <div class="col-xs-<?php echo $cols; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <p><?php echo $text_error; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>