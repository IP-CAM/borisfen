<?php print $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li>
            <a href="<?php print $breadcrumb['href']; ?>"><?php print $breadcrumb['text']; ?></a>
        </li>
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

            <p>
                <?php echo $text_conditions ?>
            </p>
            <p>&nbsp;</p>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="testimonial" class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-2 control-label"><?php echo $entry_title ?></label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" name="title" value="<?php echo $title; ?>" class="form-control">
                            <?php if ($error_title) { ?>
                            <span class="error"><?php echo $error_title; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-xs-2 control-label"><?php echo $entry_enquiry ?></label>
                        <div class="col-xs-6">
                            <textarea name="description" class="form-control" rows="5"><?php echo $description; ?></textarea>                            
                            <?php if ($error_enquiry) { ?>
                            <span class="error"><?php echo $error_enquiry; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-2 control-label"><?php echo $entry_name ?></label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" />
                            <?php if ($error_name) { ?>
                            <span class="error"><?php echo $error_name; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-2 control-label"><?php echo $entry_city ?></label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" name="city" value="<?php echo $city; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-2 control-label"><?php echo $entry_email ?></label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" />
                            <?php if ($error_email) { ?>
                            <span class="error"><?php echo $error_email; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                       <label class="col-xs-2 control-label"><?php echo $entry_rating; ?></label>
                       <div class="col-xs-6 ">
                        <?php R_stars::show(); ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-xs-2 control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
                    <div class="col-xs-2">
                      <input type="text" name="captcha" value="<?php echo $captcha; ?>" id="input-captcha" class="form-control" />
                  </div>
                  <div class="col-xs-3 captcha">
                      <i class="fa fa-refresh" id="ref"></i> 
                      <img id="captcha" src="index.php?route=information/contact/captcha" alt="" />
                  </div>
                  <?php if ($error_captcha) { ?>
                  <div class="text-danger"><?php echo $error_captcha; ?></div>
                  <?php } ?>
                  <script>
                      $(document).ready(function(){
                          $('#ref').click(function() {$('#captcha').attr('src', 'index.php?route=product/product/captcha&rand='+ Math.round((Math.random() * 10000 )));});
                      });
                  </script>
              </div>
              <div class="buttons underT text-center">
                 <a  onclick="$('#testimonial').submit();" class="btn-primary"><span><?php echo $button_send; ?></span></a>
                 <a class="btn-primary" href="<?php echo $showall_url;?>"><span><?php echo $show_all; ?></span></a>
             </div>

             <fieldset>
             </form>
         </div>
         <?php print $column_right; ?>
     </div>
 </div>
 <?php print $footer; ?>