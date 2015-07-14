<?php echo $header; ?>
<div class="container">
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
    <div id="content" class="col-xs-<?php echo $cols; ?>"><?php echo $content_top; ?>
    <?php if (isset($reactions)) { ?>
      <?php $labels = array(); $values = array(); ?>
      <h1><?php echo $poll_data['question']; ?></h1>
        <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th width="10%"><?php echo $text_percent; ?></td>
            <th><?php echo $text_answer; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 0; $i < 8; $i++) { ?>
            <?php if (!empty($poll_data['answer_' . ($i + 1)])) { ?>
              <?php array_push($labels, $poll_data['answer_' . ($i + 1)]); ?>
              <?php array_push($values, $percent[$i]); ?>
              <tr>
                <td width="10%"><strong><?php echo $percent[$i]; ?>%</strong></td>
                <td><?php echo $poll_data['answer_' . ($i + 1)]; ?></td>
              </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2"><?php echo $text_total_votes . $total_votes; ?></td>
          </tr>
        </tfoot>
      </table>
      <div style="text-align: center; margin-top: 10px; margin-bottom: 10px;">
        <?php $labels = implode('|', $labels); $values = implode(',', $values); ?>
        <img class="chart" src="http://chart.apis.google.com/chart?cht=p3&chco=303F4A,E4EEF7,849721&chd=t:<?php echo $values; ?>&chs=770x200&chl=<?php echo $labels; ?>" alt="chart" />
      </div>
    <?php } else { ?>
      <div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><?php echo $text_no_votes; ?></div>
    <?php } ?>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.details').fancybox({
		'titlePosition' : 'inside',
		'speedIn'       : 600,
		'speedOut'      : 500,
		'transitionIn'  : 'elastic',
		'transitionOut' : 'elastic',
		'easingIn'      : 'easeOutBack',
		'easingOut'     : 'easeInBack'
	});
});
//--></script>
<?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?> 