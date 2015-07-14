<?php print $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php print $breadcrumb['href']; ?>"><?php print $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row">
        <?php print $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $cols = 6; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $cols = 9; ?>
        <?php } else { ?>
        <?php $cols = 12; ?>
        <?php } ?>
        <div id="content" class="testimonial col-xs-<?php print $cols; ?>">
            <?php print $content_top; ?>
            <span xmlns:v="http://rdf.data-vocabulary.org/#"> <?php foreach ($breadcrumbs as $breadcrumb) { ?> <span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="<?php print $breadcrumb['href']; ?>" alt="<?php print $breadcrumb['text']; ?>"></a></span> <?php } ?> </span>
            <div class="heading">
                <h1><?php print $heading_title; ?></h1>
            </div>

            <?php if (true/*$testimonials*/) { ?>

            <?php foreach ($testimonials as $testimonial) { ?>
            <div class="row reviewT">
                <div class="col-xs-12"><b><?php echo $testimonial['title']; ?></b></div>
                <div class="col-xs-12"><?php echo $testimonial['description']; ?></div>
                <div class="col-xs-12 author">
                  <?php if ($testimonial['rating']) { ?> 
                  <!--<img src="catalog/view/theme/default/image/testimonials/stars-<?php echo $testimonial['rating'] . '.png'; ?>" >-->
                  <?php R_stars::show('rating', $testimonial['rating'], true); ?> 
                  <?php } ?>
                  <i><?php echo $testimonial['name'].' '.$testimonial['city'].' '.$testimonial['date_added']; ?></i></div>
              </div>
              <?php } ?>

              <?php if ( isset($pagination)) { ?>
              <div class="pagination">
                <?php echo $pagination;?>
            </div>
            <div class="row">
                <a class="btn-primary" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><?php echo $write;?></a>
            </div>
            <?php }?>

            <?php if (isset($showall_url)) { ?>
            <div class="row">
                <a class="btn-primary" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><?php echo $write;?></a>
                <a class="btn-primary" href="<?php echo $showall_url;?>" title="<?php echo $showall;?>"><?php echo $showall;?></a>
            </div>
            <?php }?>
            <?php } ?>
        </div>
        <?php print $column_right; ?>
    </div>
</div>
<?php print $footer; ?>