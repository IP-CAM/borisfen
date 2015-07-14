<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row">
        <?php echo $column_left; ?>
        
        <?php if ($column_left && $column_right) { ?>
        <?php $cols = 6; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $cols = 9; ?>
        <?php } else { ?>
        <?php $cols = 12; ?>
        <?php } ?>
        <div class="col-xs-<?php echo $cols; ?> ">
            <div id="content">
                <?php echo $content_top; ?>
                <div class="row">
                    <div class="heading container">
                        <h1><?php echo $heading_title; ?></h1> 
                    </div>
                </div>
                <div class="row">
                    <div class="pav-filter-blogs container">
                        
                        <div class="pav-blogs">
                            <?php $cols = $config->get('cat_columns_leading_blogs');if( count($leading_blogs) ) { ?>
                            <div class="leading-blogs clearfix">
                                <?php foreach( $leading_blogs as $key => $blog ) { $key = $key + 1;?>
                                <div class="pavcol<?php echo $cols;?>">
                                    <?php require( '_item.tpl' ); ?>
                                </div>
                                <?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
                                <div class="clearfix"></div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            
                            <?php $cols = $config->get('cat_columns_secondary_blogs'); $cols = !empty($cols)?$cols:1; if ( count($secondary_blogs) ) { ?>
                            <div class="secondary clearfix">
                                
                                <?php foreach( $secondary_blogs as $key => $blog ) {  $key = $key+1; ?>
                                <div class="pavcol<?php echo $cols;?>">
                                    <?php require( '_item.tpl' ); ?>
                                </div>
                                <?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
                                <div class="clearfix"></div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <div class="pav-pagination pagination"><?php echo $pagination;?></div>
                        </div>
                    </div>
                </div>  
                <?php echo $content_bottom; ?>
            </div>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>
<?php echo $footer; ?> 