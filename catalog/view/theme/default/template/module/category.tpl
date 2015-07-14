<?php
if(!function_exists('printCategories')){
	function printCategories($categories, $list_categories, $lvl){
		if($lvl != 1) echo "<ul>\n";
		foreach($list_categories as $category_id){
			$this_category = $categories[$category_id];
			
			// add classes
			$classes = '';
			$classes .= $this_category['active'] ? 'active ' : '';  
			$classes .= count($this_category['children']) > 0 ? 'parent' : '';
			
			echo "<li" . ($classes != '' ? ' class="' . $classes . '"' : '') . ">\n";
			echo '<a href="' . $this_category['href'] . '">' . $this_category['name'] . "</a>\n";
			if(count($this_category['children']) > 0){
				$lvl++;
				printCategories($categories, $this_category['children'], $lvl);
			}
		}
		if($lvl != 1) echo "</ul>\n";
	}
}
?>
<div class="box categoryMenu">
<ul class="nav ">
    <?php printCategories($categories, $main_categories, 1)?>
</ul>
</div>