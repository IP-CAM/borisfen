<?php 
class R_stars {
    public static function show($field_name = 'rating', $selected = 0, $static = false, $product_id = 0) {
        if($selected) {
            $r1o = '';
        } else {
            $r1o = '-o';
        }
        if($selected > 1) {
            $r2o = '';
        } else {
            $r2o = '-o';
        }
        if($selected > 2) {
            $r3o = '';
        } else {
            $r3o = '-o';
        }
        if($selected > 3) {
            $r4o = '';
        } else {
            $r4o = '-o';
        }
        if($selected > 4) {
            $r5o = '';
        } else {
            $r5o = '-o';
        }
        
        if($static) {
        	$static = 'notSelectable';
        } else {
        	$static = 'dynamic'; 
        }
        
        print '
<div class="rating dynamic ' . $static . ' " style="display: block !important;">
	<input type="hidden" name="rating_product_id" value="' . $product_id . '" />
    <div class="rating-radio-div ' . $static . ' ">
        <label class="fa fa-stack ' . $static . ' ">
        <i class="fa fa-star' . $r1o . ' fa-stack-1x"></i>
        <i class="fa fa-star-o fa-stack-1x"></i>
        <input class="radio-star" type="radio" name="' . $field_name . '" value="1" />
        </label>
        <label class="fa fa-stack ' . $static . ' ">
            <i class="fa fa-star' . $r2o . ' fa-stack-1x"></i>
            <i class="fa fa-star-o fa-stack-1x"></i>
            <input class="radio-star" type="radio" name="' . $field_name . '" value="2" />
        </label>
        <label class="fa fa-stack ' . $static . ' ">
            <i class="fa fa-star' . $r3o . ' fa-stack-1x"></i>
            <i class="fa fa-star-o fa-stack-1x"></i>
            <input class="radio-star" type="radio" name="' . $field_name . '" value="3" />
        </label>
        <label class="fa fa-stack ' . $static . ' ">
            <i class="fa fa-star' . $r4o . ' fa-stack-1x"></i>
            <i class="fa fa-star-o fa-stack-1x"></i>
            <input class="radio-star" type="radio" name="' . $field_name . '" value="4" />
        </label>
        <label class="fa fa-stack ' . $static . ' ">
            <i class="fa fa-star' . $r5o . ' fa-stack-1x"></i>
            <i class="fa fa-star-o fa-stack-1x"></i>
            <input class="radio-star" type="radio" name="' . $field_name . '" value="5" />
        </label>
        <input type="hidden" class="curentRating" value="' . $selected . '">
    </div>
</div>
        ';
    }
}
