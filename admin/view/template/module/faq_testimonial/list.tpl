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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
		<a onclick="location = '<?php echo $module; ?>';" class="btn btn-primary"><i class="fa fa-cog"></i> <?php echo $button_module; ?></a>
		<a onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_insert; ?></a>
		<a onclick="$('#form').submit();" class="btn btn-danger"><i class="fa fa-times"></i> <?php echo $button_delete; ?></a>
		</div>
      <h1 class="panel-title"><i class="fa fa-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
	
    <div class="panel-body">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table  id="module" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" align="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $column_title; ?></td>
              <td class="right"><?php echo $column_status; ?></td>
              <td class="right"><?php echo $column_sort_order; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($topics) { ?>
            <?php $class = 'odd'; ?>
            <?php foreach ($topics as $topic) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <tr class="<?php echo $class; ?>">
              <td style="align: center;"><?php if ($topic['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $topic['faq_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $topic['faq_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $topic['title']; ?></td>
              <td class="right"><?php echo $topic['status']; ?></td>
              <td class="right"><?php echo $topic['sort_order']; ?></td>
              <td class="right"><?php foreach ($topic['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr class="even">
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
