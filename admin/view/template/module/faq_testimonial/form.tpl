<?php echo $header; ?>
<div id="content" class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="panel panel-default">
		<div class="panel-heading">
		  <div class="pull-right">
			<a onclick="$('#form').submit();" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_save; ?></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger"><i class="fa fa-times"></i> <?php echo $button_cancel; ?></a>
		  </div>
		  <h1 class="panel-title"><i class="fa fa-edit"></i> <?php echo $heading_title; ?></h1>
		</div>
	
    <div class="panel-body">


        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div class="tab-content">

                <ul class="nav nav-tabs" id="languages">
                    <?php $l=0; foreach ($languages as $language) { ?>
                        <li class="<?php if(!$l) { print 'active';} ?>">
                            <a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab">
                                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
                            </a>
                        </li>
                        <?php $l++; } ?>
                </ul>
                <div class="tab-content">
                    <?php $l=0; foreach ($languages as $language) { ?>
                        <div class="tab-pane <?php if(!$l) { print 'active';} ?>" id="language<?php echo $language['language_id']; ?>">
                            <table class="form" style="border-collapse: separate; border-spacing: 0 20px; width: 100%;">
                                <tr>
                                    <td>Имя (<?php echo $language['name']; ?>) </td>
                                    <td>
                                        <input name="faq_description[<?php echo $language['language_id']; ?>][author_name]" size="100" value="<?php echo isset($faq_description[$language['language_id']]) ? $faq_description[$language['language_id']]['author_name'] : ''; ?>" class="form-control" />
                                        <?php if (isset($error_author_name[$language['language_id']])) { ?>
                                            <span class="error"><?php echo $error_author_name[$language['language_id']]; ?></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="required">*</span> <?php echo $entry_title; ?> (<?php echo $language['name']; ?>) </td>
                                    <td>
                                        <textarea name="faq_description[<?php echo $language['language_id']; ?>][title]" id="faq_description<?php echo $language['language_id']; ?>"><?php echo isset($faq_description[$language['language_id']]) ? $faq_description[$language['language_id']]['title'] : ''; ?></textarea>
                                        <?php if (isset($error_title[$language['language_id']])) { ?>
                                            <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
                                            <script type="text/javascript">
                                                $('a[href="#language<?php echo $language['language_id']; ?>"]').css({'background-color' : '#faa'});;
                                            </script>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <input type="hidden" name="faq_description[<?php echo $language['language_id']; ?>][meta_description]" value=""/>

                                <tr>
                                    <td><?php echo $entry_description; ?> (<?php echo $language['name']; ?>) </td>
                                    <td><textarea name="faq_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($faq_description[$language['language_id']]) ? $faq_description[$language['language_id']]['description'] : ''; ?></textarea>
                                        <?php if (isset($error_description[$language['language_id']])) { ?>
                                            <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
                                            <script type="text/javascript">
                                                $('a[href="#language<?php echo $language['language_id']; ?>"]').css({'background-color' : '#faa'});;
                                            </script>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php $l++;} ?>

                </div>

            </div>
            <div id="tab_data">
                <table class="form" style="border-collapse: separate; border-spacing: 0 20px; width: 32%;">
                    <tr>
                        <td><?php echo $entry_keyword; ?></td>
                        <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
                    </tr>
                    <tr style="display: none">
                        <td><?php echo $entry_topic; ?></td>
                        <td><select name="topic_id" style="width: 20%;">
                                <option value="0"><?php echo $text_none; ?></option>
                                <?php foreach ($topics as $topic) { ?>
                                    <?php if ($topic['faq_id'] == $topic_id) { ?>
                                        <option value="<?php echo $topic['faq_id']; ?>" selected="selected"><?php echo $topic['title']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $topic['faq_id']; ?>"><?php echo $topic['title']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr style="display: none">
                        <td><?php echo $entry_store; ?></td>
                        <td><div class="scrollbox">
                                <?php $class = 'even'; ?>
                                <div class="<?php echo $class; ?>">
                                    <?php if (in_array(0, $faq_store)) { ?>
                                        <input type="checkbox" name="faq_store[]" value="0" checked="checked" />
                                        <?php echo $text_default; ?>
                                    <?php } else { ?>
                                        <input type="checkbox" name="faq_store[]" value="0" />
                                        <?php echo $text_default; ?>
                                    <?php } ?>
                                </div>
                                <?php foreach ($stores as $store) { ?>
                                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                    <div class="<?php echo $class; ?>">
                                        <?php if (in_array($store['store_id'], $faq_store)) { ?>
                                            <input type="checkbox" name="faq_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                            <?php echo $store['name']; ?>
                                        <?php } else { ?>
                                            <input type="checkbox" name="faq_store[]" value="<?php echo $store['store_id']; ?>" />
                                            <?php echo $store['name']; ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="status">
                                <?php if ($status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});

CKEDITOR.replace('faq_description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
//$('#tabs a').tabs();
//$('#languages a').tabs();
//--></script> 
<?php echo $footer; ?>
