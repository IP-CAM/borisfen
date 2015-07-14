<?php if(!function_exists('printMenu')){ function printMenu($menu, $index){?>
<li class="<?=$menu['classes']?> item<?=$index?><?=$index==1?' first':''?><?=$menu['active']?' active':'';?>">

	
	<?=$menu['content'];?>
	
	<?if($menu['has_child']){?>
	<div class="dropdown-holder<?=$menu['columns_count']>1 ? ' row-holder':''?>" data-column="<?=$menu['columns_count']?>">
		<table>
			<tr>
				<?php foreach($menu['columns'] as $key => $column){
					$style = ($column['width'] > 0) ? $column['style'] . '; min-width:' . $column['width'] . 'px;' : $column['style'];
					?>  
					<td style="<?=$style?>">
						<ul>
							<?php
										//--------------------------DANGEROUS CODE----------------------------//
							$index = 1;
							$i = $key * ceil($menu['children_count'] / $menu['columns_count']);
							$num_items_in_column = (ceil($menu['children_count'] / $menu['columns_count']) + $i);
							for($i; $i < $num_items_in_column; $i++)
								if(isset($menu['children'][$i]))
									printMenu($menu['children'][$i], $index++);
										//--------------------------DANGEROUS CODE----------------------------//
								?>
							</ul>
						</td>
						<? } ?>
					</tr>
				</table>
			</div>
			<? } ?>
		</li>
		<? }} ?>
		
		
		<ul class="nav">
			<?php 
			foreach($menu_array as $index => $menu){
				printMenu($menu, $index+1);
			}
			?>
		</ul>

		<script>
			$(".nav *").hover(
				function() {
					$( this ).addClass("hover");
				}, function() {
					$( this ).removeClass("hover");
				});

		</script>

