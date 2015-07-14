<div class="box interview">
  <div id="poll">
    <?php if ($poll_data) { ?>
      <div class="question"><?php echo $poll_data['question']; ?></div>
      <?php if (isset($answered) || isset($disabled)) { ?>
        <?php if (isset($reactions)) { ?>
          <table>
            <?php for ($i = 0; $i < 8; $i++) { ?>
              <?php if (!empty($poll_data['answer_' . ($i + 1)])) { ?>
                <tr>
                  <td><strong><?php echo $percent[$i]; ?>%</strong></td>
                  <td>
                    <div class="progress progress-striped">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $percent[$i]; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent[$i]; ?>%">
                        <span class="sr-only"></span>
                      </div>
                    </div>
                      
                  </td>
                </tr>
                <tr>
                  <td class="bottom" colspan="2"><?php echo $poll_data['answer_' . ($i + 1)]; ?></td>
                </tr>
              <?php } ?>
            <?php } ?>
          </table>
            <div class="form-group vote">
                <span><?php echo $text_total_votes . $total_votes; ?></span>
            </div>
        <?php } else { ?>
          <div style="text-align: center;"><?php echo $text_no_votes; ?></div>
        <?php } ?>
      <?php } else { ?>
        <form method="post" action="<?php echo $action; ?>" id="vote">
          <fieldset>
            <div class="form-group">
              <ul class="list-unstyled option">
                <?php for ($i = 0; $i < 8; $i++) { ?>
                  <?php if (!empty($poll_data['answer_' . ($i + 1)])) { ?>
                  <li>
                    <label class="radio"  for="answer<?php echo $i + 1; ?>">
                      <input type="radio" name="poll_answer" value="<?php echo $i + 1; ?>" id="answer<?php echo $i + 1; ?>">
                      <span><?php echo $poll_data['answer_' . ($i + 1)]; ?></span>
                    </label>
                  </li>
                  <?php } ?>
                <?php } ?>
                <input type="hidden" name="poll_id" value="<?php echo $poll_id; ?>" />
              </ul>
            </div>
          </fieldset>
          <div class="form-group vote">
            <input type="submit" class="btn btn-primary" value="<?php echo $text_vote; ?>">
          </div>
        </form>
      <?php } ?>
    <?php } else { ?>
      <div style="text-align: center;"><?php echo $text_no_poll; ?></div>
    <?php } ?>
  </div>
</div>
