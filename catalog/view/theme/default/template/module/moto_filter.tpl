<div class="filterHolder row">
    <form class="moto_filter">
        <div class="title"><?php echo $heading_title; ?></div>
        <select id="remote-mark" name="mark" data-size="5" class="mark">
            <option value="0">Выберите марку</option>
            <?php foreach ($marks as $mark) { ?>
            <option value="<?php print $mark['category_id']; ?>" <?php if($mark['selected']) { ?>selected="selected"<?php } ?>><?php print $mark['name']; ?></option>
            <?php } ?>
        </select>
        <select id="remote-model" name="model" data-size="5" class="model">
            <option value="0">Выберите модель</option>
            <?php foreach ($models as $model) { ?>
            <option value="<?php print $model['category_id']; ?>" <?php if($model['selected']) { ?>selected="selected"<?php } ?>><?php print $model['name']; ?></option>
            <?php } ?>
        </select>
        <select id="remote-year" name="year" data-size="5" class="year">  
            <option value="0">Выберите год</option>
            <?php foreach ($years as $year) { ?>
            <option value="<?php print $year['category_id']; ?>" <?php if($year['selected']) { ?>selected="selected"<?php } ?>><?php print $year['name']; ?></option>
            <?php } ?>
        </select>
        <button type="button" class="btn filterSend">Подтвердить выбор</button>
    </form>
</div>