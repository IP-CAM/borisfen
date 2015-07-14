<?php if($success){?>
	<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><?php echo $text_success;?></div>
<?php } ?>

<div class="faq-form">
<div class="row">
    <form action="/faq" method="POST">
        <div class="form-group col-xs-8 required">
            <div class="col-xs-6">
                <input type="text" name="author_name" value="<?php echo $author_name;?>" class="form-control default" placeholder="Псевдоним *" />
                <?php if($error_author_name){?>
                    <div class="text-danger"><?php echo $error_author_name;?></div>
                <?php } ?>
            </div> 
            <div class="col-xs-6">
                <input type="text" name="author_mail" value="" class="form-control default" placeholder="E-mail" />
                <?php // if($error_author_name){?>
                    <?php // echo $error_author_name;?>
                <?php // } ?>
            </div> 
        </div>
        <div class="form-group col-xs-8 required">
            <div class="col-xs-12">
                <textarea name="title" class="form-control default" placeholder="Вопрос *"><?php echo $title;?></textarea>
                <?php if($error_title){?>
                    <div class="text-danger"><?php echo $error_title;?></div>
                <?php }?>
            </div> 
        </div>
        <div class="form-group col-xs-8 required">
            <div class="col-xs-3">
                <input type="text" name="captcha" value="<?php echo $captcha; ?>" id="input-captcha" class="form-control default" placeholder="<?php echo $entry_captcha; ?>"/>
            </div> 
            <div class="col-xs-3 captchan">
                <i class="fa fa-refresh" id="ref"></i>&nbsp;&nbsp;
                <img id="captcha" src="index.php?route=information/contact/captcha" alt=""/>
            </div>
            <div class="col-xs-4 pull-right text-right">
                <input type="submit" value="<?php echo $entry_submit;?>" class="btn btn-primary"/>
            </div>
            <?php if ($error_captcha) { ?>
                <div class="text-danger"><?php echo $error_captcha; ?></div>
            <?php } ?>
            <script>
                $(document).ready(function () {
                    $('#ref').click(function () {
                        $('#captcha').attr('src', 'index.php?route=product/product/captcha&rand=' + Math.round((Math.random() * 10000 )));
                    });
                });
            </script>
        </div>
    </form>
</div>
</div>

<div class="box faq">
    <div class="box-content">
        <?php foreach ($faqs as $faq) { ?>
        <div class="faqlist">
            <div class="box-faqlist" <?php if( !empty($faq['description']) ) { ?>style="width: 90%;"<?php } ?>>
                <div class="faqlist-name">
                    <i><?php echo $faq['date_added']; ?></i><b><?php echo $faq['author_name']; ?></b>
                </div>
                <div class="faqlist-title">
                    <?php echo $faq['title']; ?>
                </div>
            </div>
            <?php if( !empty($faq['description']) ) { ?>
			<div class="box-faqrequest">
                <div class="moderator_name"><b><?php echo $faq['moderator_name']; ?></b><br><i><?php echo $faq['date_added']; ?></i></div>
                <div class="moderator_description"><?php echo $faq['description']; ?></div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>

<!--
<?php if ($faqs) { ?>
	<?php foreach ($faqs as $faq) { ?>
		<ul>
			<?php foreach ($faq['children'] as $child) { ?>
				<li>
				<?php if ($child['faq_id'] == $child_id) { ?>
					<a href="<?php echo $child['href']; ?>" class="active"> - <?php echo $child['title']; ?></a>
				<?php } else { ?>
					<a href="<?php echo $child['href']; ?>"> - <?php echo $child['title']; ?></a>
				<?php } ?>
				</li>
			<?php } ?>
		</ul>
	<?php } ?>
<?php } ?>
-->

<script type="text/javascript" src="catalog/view/javascript/jquery.accordion.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.accordion').accordion({defaultOpen: 'some_id'}); //some_id section1 in demo
    });
</script>