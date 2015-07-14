<?php if(!function_exists('printOcCategories')){ function printOcCategories($category, $menu, $index, $is_native_title){;?>
<ul>
	<?if( $is_native_title && strlen(trim($menu['name'])) > 0):?>
	  <li class="title <?=$menu['classes'];?>"><?=$menu['name'];?></li>
	<? endif;?> 
	<li class="<?=$category['classes']?> item<?=$index?>"><a href="<?php echo $category['href']?>"><span><?php echo $category['name']?></span></a>
		<?if(count($category['children'])){?>
			<div class="dropdown-holder" data-column="1">
				<table>
	                <tr>
	                	<td>
			                	<?php $index=1; if(count($category['children'])) foreach($category['children'] as $key => $child){ ?>
		                    			<?php printOcCategories($child, $menu, $index++, false); ?>
			                    <? } ?>
	                    </td>
	                </tr>
	           </table>
	       </div>
	   <? } ?>
	</li>
</ul>
<? }} ?>
<?php 
	$is_native_title = true;
	foreach($categories as $index => $category){
		printOcCategories($category, $menu, $index+1, $is_native_title);
		$is_native_title = false;	
	}
?>
