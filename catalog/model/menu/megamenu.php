<?php	
class ModelMenuMegamenu extends Model {
	
	
	/**
	 *
	 */
	private $_editString = '';

	/**
	 *
	 */
	private $children;
	
	/**
	 *
	 */
	private $shopUrl ;

	/**
	 *
	 */
	private $megaConfig = array();

	private $_editStringCol = '';

	private $_isLiveEdit = true;
	/**
	 *
	 */
	public function getChilds( $id=null, $store_id=0 ){
		$sql = ' SELECT m.*, md.title,md.description FROM ' . DB_PREFIX . 'megamenu m LEFT JOIN '
								.DB_PREFIX.'megamenu_description md ON m.megamenu_id=md.megamenu_id AND language_id='.(int)$this->config->get('config_language_id') ;
		$sql .= ' WHERE m.`published`=1 ';
                $sql .= ' AND store_id='.(int)$store_id;
		if( $id != null ) {						
			$sql .= ' AND parent_id='.(int)$id;						
		}
		$sql .= ' ORDER BY `position`  ';
		$query = $this->db->query( $sql );						
		return $query->rows;
	}
	
	/**
	 *
	 */
	public function hasChild( $id ){
		return isset($this->children[$id]);
	}	
	
	/**
	 *
	 */
	public function getNodes( $id ){
		return $this->children[$id];
	}
	
	/**
	 *
	 */
	public function getTree( $parent=1 , $edit=false, $params, $store_id = 0){
		$this->load->model( 'menu/widget' );
		$this->parserMegaConfig( $params ); 
		if( $edit ){ 
			$this->_editString  = ' data-id="%s" data-group="%s"  data-cols="%s" ';
		}
		$this->_editStringCol = ' data-colwidth="%s" data-class="%s" ' ;


		$childs = $this->getChilds( null, $store_id );
		foreach($childs as $child ){
			$child['megaconfig'] = $this->hasMegaMenuConfig( $child );
			if( isset($child['megaconfig']->group) ){
				$child['is_group'] = $child['megaconfig']->group;
			} 

			if( isset($child['megaconfig']->submenu) && $child['megaconfig']->submenu == 0){
				$child['menu_class'] = $child['menu_class'] .' disable-menu';
			}  

			$this->children[$child['parent_id']][] = $child;	
		}

		$parent = 1 ;
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->shopUrl = $this->config->get('config_ssl') ;
		} else {
			$this->shopUrl = $this->config->get('config_url') ;
		}
		$output = '';
		if( $this->hasChild($parent) ){
			$data = $this->getNodes( $parent );
			// render menu at level 0
			$output .= '<ul class="nav navbar-nav  megamenu" id="menu">';
			foreach( $data as $menu ){

				if( $this->hasChild($menu['megamenu_id']) || $menu['type_submenu'] == 'html'){
					$output .= '<li class=" ' . $this->getActiveClass($menu) . ' parent dropdown '.$menu['menu_class'].'" '.$this->renderAttrs($menu).'>
					<a class="dropdown-toggle"  href="'.$this->getLink( $menu ).'">'.'<span class="ico"><span></span></span>';
					//data-toggle="dropdown" в а для просто кликабельного 1 уровня
					if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
					if($menu['show_title']) {
						$output .= '<span class="menu-title">'.$menu['title']."</span>";
					}
					if( $menu['description'] ){
						$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
					}
					$output .= "<b class=\"caret\"></b></a>";
					if( $menu['image']){  $output .= '</span>'; }
					
					if($menu['megamenu_id'] > 1) {
						$output .= $this->genTree( $menu['megamenu_id'], 1, $menu, 0, ($menu['type'] === 'category_tree') );	
					}
					$output .= '</li>';
				} else if ( !$this->hasChild($menu['megamenu_id']) && $menu['megaconfig'] && $menu['megaconfig']->rows ){
					$output .= $this->genMegaMenuByConfig( $menu['megamenu_id'], 1, $menu );
				}elseif($menu['type'] == 'html'){
					$output .= '<li class=" ' . $this->getActiveClass($menu) . ' '.$menu['menu_class'].'" '.$this->renderAttrs($menu).'>';
					
					if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
					
					if($menu['show_title']) {
						$output .= '<span class="menu-title">'.$menu['title']."</span>";
					}
					
					if( $menu['description'] ){
						$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
					}
					if( $menu['image']){ $output .= '</span>';	}
					$output .= '</li>';
				} elseif($menu['type'] == 'category_tree') {
				    $output .= '<li class=" ' . $this->getActiveClass($menu) . ' parent dropdown '.$menu['menu_class'].'" '.$this->renderAttrs($menu).'>
				    <a class="dropdown-toggle"  href="'.$this->getLink( $menu ).'">'.'<span class="ico"><span></span></span>';
				    //data-toggle="dropdown" в а для просто кликабельного 1 уровня
				    if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
				    if($menu['show_title']) {
				        $output .= '<span class="menu-title">'.$menu['title']."</span>";
				    }
				    if( $menu['description'] ){
				        $output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
				    }
				    $output .= "<b class=\"caret\"></b></a>";
				    if( $menu['image']){  $output .= '</span>'; }
				
				    if($menu['megamenu_id'] > 1) {
				        $output .= $this->genTree( $menu['megamenu_id'], 1, $menu, 0, true );
				    }
				    $output .= '</li>';
				} elseif($menu['type'] == 'allbrands') {
					$this->load->model('catalog/manufacturer');
					$output .= '<li class=" ' . $this->getActiveClass($menu) . ' parent dropdown '.$menu['menu_class'].'" '.$this->renderAttrs($menu).'>
				    <a class="dropdown-toggle"  href="'.$this->getLink( $menu ).'">'.'<span class="ico"><span></span></span>';
					if($menu['show_title']) {
						$output .= '<span class="menu-title">'.$menu['title']."</span>";
					}
					$output .= "<b class=\"caret\"></b></a>";
					
					$elements = array();
					$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
					foreach ($manufacturers as $man) {
						$element = '<li class=" ' . $this->getActiveClass($menu) . $menu['menu_class'] . '" ' . $this->renderAttrs($menu).'>
							<a href="' . $this->url->link('product/manufacturer/info', 'manufacrturer_id=' . (int)$man['manufacturer_id']) . '"><span class="ico"><span></span></span>		
						';
						$element .= '<span class="menu-title">' . $man['name'] . "</span>";
						$element .= "</a>";
						$element .= '</li>';
						$elements[] = $element;
					}
					if($menu['colums'] > 1 && (count($elements) >= $menu['colums'])) {
						$elements = array_chunk($elements, $menu['colums']);
					} elseif($menu['colums'] > 1 && (count($elements) < $menu['colums'])) {
						$elements = array_chunk($elements, count($elements));
					} else {
						$elements = array($elements);
					}
					$output .= '<div class="dropdown-menu mega-cols cols' . count($elements) . '">
		    		  <div class="dropdown-menu-inner"><div class="row-fluid">';
					foreach ($elements as $r => $el_group) {
						$output .= '
		    			  <div class="mega-col span6 col-' . ($r+1) . '" data-type="menu">
		    		        <div class="mega-col-inner">
			    		            <ul>';
						foreach ($el_group as $element) {
							$output .= $element;
						}
						$output .= '</ul></div></div>';
					}
					$output .= '</div></div></div>';
					$output .= '</li>';
			    } else {
					$output .= '<li class=" ' . $this->getActiveClass($menu) . ' '.$menu['menu_class'].'" '.$this->renderAttrs($menu).'>
					<a href="'.$this->getLink( $menu ).'">'.'<span class="ico"><span></span></span>';
					
					if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
					
					if($menu['show_title']) {
						$output .= '<span class="menu-title">'.$menu['title']."</span>";
					}
					
					if( $menu['description'] ){
						$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
					}
					if( $menu['image']){ $output .= '</span>';	}
					$output .= '</a></li>';
				}
			}
			$output .= '</ul>';
			
		}

		 return $output;
	
	}
	
	/**
	 *
	 */
	public function genMegaMenuByConfig( $parentId, $level,$menu  ){
		
		$attrw = '';
		$px= '';
		$class = $level > 1 ? "dropdown-submenu":"dropdown";
		
		$output = '<li class=" ' . $this->getActiveClass($menu) . ' '.$menu['menu_class'].' parent '.$class.' " '.$this->renderAttrs($menu).'>
					<a href="'.$this->getLink( $menu ).'" class="dropdown-toggle" data-toggle="dropdown">';
					
					if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
					
					$output .= '<span class="menu-title">'.$menu['title']."</span>";
					if( $menu['description'] ){
						$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
					}
					if( $menu['image']){ $output .= '</span>';	}
					$output .= "<b class=\"caret\"></b></a>";

		if( isset($menu['megaconfig']->subwidth) &&  $menu['megaconfig']->subwidth ){
			$attrw .= ' style="width:'.$menu['megaconfig']->subwidth.'px"' ;
		}
		$class  = 'dropdown-menu';$class .= ' megacols-addition';
		$output .= '<div class="'.$class.'" '.$attrw.' ><div class="dropdown-menu-inner">';

		foreach( $menu['megaconfig']->rows  as $row ){
		
			$output .= '<div class="row-fluid">';
				foreach( $row->cols as $col ){
					$colclass = isset($col->colclass)?$col->colclass:'';
					 $output .= '<div class="mega-col span'.$col->colwidth.' '.$colclass.'" '.$this->getColumnDataConfig( $col ).'> <div class="mega-col-inner">';
					 	$output .= $this->renderWidgetsInCol( $col );
					 $output .= '</div></div>';
				}
			$output .= '</div>';
		}
		unset($colclass);

		$output .= '</div></div>';
		$output .= '</li>';
		return $output; 
	}

	public function renderWidgetsInCol( $col ){
		 if( is_object($col) && isset($col->widgets)  ){
		 	$widgets = $col->widgets; 
		 	$widgets = explode( '|wid-', '|'.$widgets );
			if( !empty($widgets) ){
				unset( $widgets[0] );
				$this->model_menu_widget->loadWidgets();
				$output = '';
				foreach( $widgets as $wid ){
					$output .= $this->model_menu_widget->renderContent( $wid );
				}

				return $output;
			}
		 }
	}

	public function getColumnDataConfig( $col ){
		$output = '';
	/*	if( is_object($col)  && $this->_isLiveEdit ){
			$vars = get_object_vars($col);
			foreach( $vars as $key => $var ){
				$output .= ' data-'.$key.'="'.$var . '" ' ;
			}
		} */
		return $output;
	}
	/**
	 *
	 */
	public function getColumnSpans( $col ){
		
	}
	
	/**
	 *
	 */
	public function parserMegaConfig( $params ){
		if( !empty($params) ) { 
			foreach( $params as $param ){
				if( $param  ){
					$this->megaConfig[$param->id] = $param;
				}
			}	
		}
	}
	
	public function hasMegaMenuConfig( $menu ){
		$id = $menu['megamenu_id'];
		return isset( $this->megaConfig[$id] )?$this->megaConfig[$id] :array(); 
	}
	/**
	 *
	 */
	public function genTree( $parentId, $level,$parent, $store_id = 0, $recursive = false){
	    if (!$recursive) {
    		$attrw = '';
    		$class = $parent['is_group']?"dropdown-mega":"dropdown-menu";
    		
    		if( isset($parent['megaconfig']->subwidth) &&  $parent['megaconfig']->subwidth ){
    			$attrw .= ' style="width:'.$parent['megaconfig']->subwidth.'px"' ;
    		}
    
    
    		if( $parent['type_submenu'] == 'html' ){
    			$output = '<div class="'.$class.'"><div class="menu-content">';
    			$output .= html_entity_decode($parent['submenu_content']);
    			$output .= '</div></div>';
    			return $output;
    		}elseif( $this->hasChild($parentId) ){
    			
    			$data = $this->getNodes( $parentId );			
    			$parent['colums'] = (int)$parent['colums'];
    			if( $parent['colums'] > 1  ){
    
    				if( !empty($parent['megaconfig']->rows) ) {
    					
    					$cols   = array_chunk( $data, ceil(count($data)/$parent['colums'])  );
    					
    					$oSpans = $this->getColWidth( $parent, (int)$parent['colums'] );
    					
    					$output = '<div class="'.$class.' level'.$level.'" '.$attrw.' ><div class="dropdown-menu-inner">';
    					foreach( $parent['megaconfig']->rows as $rows ){ 
    						foreach( $rows as $rowcols ){
    							$output .='<div class="row-fluid">';
    							
    							foreach( $rowcols as $key => $col ) {
    								$col->colwidth = isset($col->colwidth)?$col->colwidth:6;
    								if( isset($col->type) && $col->type == 'menu' && isset($cols[$key]) ){
    									$scol = '<div class="mega-col '.$oSpans[$key+1].' col-'.($key+1).'" data-type="menu" '.$this->getColumnDataConfig( $col ).'><div class="mega-col-inner">';
    									$scol .= '<ul>';
    									foreach( $cols[$key] as $menu ) {
    										 $scol .= $this->renderMenuContent( $menu, $level+1 );
    									}
    									$scol .='</ul></div></div>';
    								}else {
    									$scol = '<div class="mega-col span'.$col->colwidth.' col-'.($key+1).'"  '.$this->getColumnDataConfig( $col ).'><div class="mega-col-inner">';
    										$scol .= $this->renderWidgetsInCol( $col );
    									$scol .= '</div></div>';	
    								}
    								$output .= $scol;
    							}
    
    							$output .= '</div>';
    						}
    					}
    					$output .= '</div></div>';
    
    				}else {
    					$column_width= ($parent['colums']*250); //ширина колонки
    					$output = '<div class="'.$class.' mega-cols cols'.$parent['colums'].'" '.$attrw.' style="width:'.$column_width.''.'px'.' " ><div class="dropdown-menu-inner"><div class="row-fluid">';
    					$cols   = array_chunk( $data, ceil(count($data)/$parent['colums'])  );
    
    					$oSpans = $this->getColWidth( $parent, (int)$parent['colums'] );
    				
    					foreach( $cols as $i =>  $menus ){
    
    						$output .='<div class="mega-col '.$oSpans[$i+1].' col-'.($i+1).'" data-type="menu"><div class="mega-col-inner"><ul>';
    							foreach( $menus as $menu ) {
    								$output .= $this->renderMenuContent( $menu, $level+1 );
    							}
    						$output .='</ul></div></div>';
    					}
    
    					$output .= '</div></div></div>';
    				}	
    				return $output;
    			}else {
    
    				
    
    				$failse = false; 
    
    			///	echo '<pre>' .print_r( $parent, 1 );
    				if( !empty($parent['megaconfig']->rows) ) {
    					$output = '<div class="'.$class.' level'.$level.'" '.$attrw.' ><div class="dropdown-menu-inner">';
    					foreach( $parent['megaconfig']->rows as $rows ){ 
    						foreach( $rows as $rowcols ){
    							$output .='<div class="row-fluid">';
    							foreach( $rowcols as $col ) {
    								
    								if( isset($col->type) && $col->type == 'menu' ){
    									$colwidth = isset($col->colwidth)?$col->colwidth:'';
    									$scol = '<div class="mega-col span'.$colwidth.'" data-type="menu" '.$this->getColumnDataConfig( $col ).'><div class="mega-col-inner">';
    									$scol .= '<ul>';
    									foreach( $data as $menu ){
    										$scol .= $this->renderMenuContent( $menu , $level+1 );
    									}	
    									$scol .= '</ul>';
    									
    								}else {
    									$scol = '<div class="mega-col span'.$col->colwidth.'"  '.$this->getColumnDataConfig( $col ).'><div class="mega-col-inner">';
    									$scol .= $this->renderWidgetsInCol( $col );
    								}
    								$scol .= '</div></div>';
    								$output .= $scol;
    							}	
    							$output .= '</div>';
    						}
    
    					}$output .= '</div></div>';
    				} else {
    					$output = '<div class="'.$class.' level'.$level.'" '.$attrw.' ><div class="dropdown-menu-inner">';
    					$row = '<div class="row-fluid"><div class="span12 mega-col" data-colwidth="12" data-type="menu" ><div class="mega-col-inner"><ul>';
    					foreach( $data as $menu ){
    						$row .= $this->renderMenuContent( $menu , $level+1 );
    					}	
    					$row .= '</ul></div></div></div>';
    
    					$output .= $row;
    					
    				}
    				
    			}
    
    			return $output;
    
    		}
    		return ;
	    } else {
	    	$output = $this->genRecTreeMenu($parent);
	    	return $output;
	    }
	}
	
    private function genRecTreeMenu($parent) {
	    $output = '';
	    $sql = "
    			Select * From " . DB_PREFIX . "category c
    			Left Join " . DB_PREFIX . "category_description cd On (cd.category_id = c.category_id)
	    			Where
	    			c.parent_id = {$parent['item']}
	    			And
	    			cd.language_id = {$this->config->get('config_language_id')}
	    		AND c.status = '1'
	    			Group by
	    			c.category_id
	    			Order by
	    			c.sort_order ASC
	    ";
	    $categories = $this->db->query($sql);
	    if($categories->num_rows) {
	    $output .= '
		    		<div class="dropdown-menu mega-cols cols1">
		    		  <div class="dropdown-menu-inner">
		    		    <div class="row-fluid">
	
			    		    ';
			    		    $colN = 1;
			$col1 = '
		    			  <div class="mega-col span6 col-1" data-type="menu">
		    		        <div class="mega-col-inner">
			    		            <ul>';
	
			    		            foreach ($categories->rows as $category) {
			    		            $hasChild = $this->categoryHasCild($category['category_id']);
			    		            $col1 .= '<li class="';
				if($hasChild) {
					$col1 .= ' parent dropdown-submenu ';
				}
				$parents = $this->getCategoryParents($category['category_id']);
				$path = $this->url->link('product/category', 'path=' . (!empty($parents)?$parents . '_':'') . $category['category_id'] );
				$col1 .='' . $this->getActiveClass(array('item' => $category['category_id'], 'type' => 'category')) . '">
					        <a href="' . $path . '"';
				if($hasChild) {
				$col1 .= ' class="dropdown-toggle" ';
				}
				        $col1 .='>
								<span class="menu-title">' . $category['name'] . '</span>
									</a>
									        ';
				if($hasChild) {
				$col1 .= $this->genRecTreeMenu(array('item' => $category['category_id']));
				}
				$col1 .= '</li>';
	    }
	    $col1 .= '
	    </ul>
    		         </div>
    		       </div>';
			$output .= $col1;
				$output .= '
	
	    		     </div>
	    		   </div>
	    		 </div>
				';
			}
			return $output;
		}
	
		
		private function categoryHasCild($category_id) {
		    $sql = "
			Select count(*) as total From " . DB_PREFIX . "category Where parent_id = {$category_id};
					";
					$categories = $this->db->query($sql);
		
					if($categories->row['total'] > 0) {
					    return true;
					} else {
					    return false;
					}
		}
		
	/**
	 *
	 */
	public function renderAttrs( $menu ){  
	//	$t = sprintf( $this->_editString, $menu['megamenu_id'], $menu['is_group'], $menu['colums']  );
		//if( $this->_isLiveEdit  ){  
		//	if( isset($menu['megaconfig']->subwidth) &&  $menu['megaconfig']->subwidth ){
			//	$t .= ' data-subwidth="'.$menu['megaconfig']->subwidth.'" ';
		///	}
		//	$t .= ' data-submenu="'.(isset($menu['megaconfig']->submenu)?$menu['megaconfig']->submenu:$this->hasChild($menu['megamenu_id'])).'"'; 
		// }	
	//	return $t;
	}	

	/**
	 *
	 */
	public function renderMenuContent( $menu , $level ){

		$output = '';
		$class = $menu['is_group']?"mega-group":"";
		$menu['menu_class'] = ' '.$class;
		if( $menu['type'] == 'html' ){ 
			$output .= '<li class=" ' . $this->getActiveClass($menu) . ' '.$menu['menu_class'].'" '.$this->renderAttrs($menu).'>';	
			$output .= '<div class="menu-content">'.html_entity_decode($menu['content_text']).'</div>'; 
			$output .= '</li>';
			return $output;
		}
		if( $this->hasChild($menu['megamenu_id']) ){

			$output .= '<li class=" ' . $this->getActiveClass($menu) . ' parent dropdown-submenu'.$menu['menu_class'].'" '.$this->renderAttrs($menu). '>';
			if( $menu['show_title'] ){
				$output .= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.$this->getLink( $menu ).'">';
				$t = '%s';
				if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
				$output .= '<span class="menu-title">'.$menu['title']."</span>";
				if( $menu['description'] ){
					$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
				}
				$output .= "<b class=\"caret\"></b>";
				if( $menu['image']){ 
					$output .= '</span>';
				}
				$output .= '</a>';
			}	
			if($menu['megamenu_id'] > 1) {
				$output .= $this->genTree( $menu['megamenu_id'], $level, $menu );
			}
			$output .= '</li>';

		}else if (  $menu['megaconfig'] && $menu['megaconfig']->rows ){
			$output .= $this->genMegaMenuByConfig( $menu['megamenu_id'], $level, $menu );
		}else {
			if($menu['type'] == 'category_tree') {
		        $liClasses   = ' parent dropdown-submenu ';
		        $aAttributes = ' class="dropdown-toggle" ';
		    } else {
		        $liClasses   = '';
		        $aAttributes = '';
		    }
			$output .= '<li class=" ' . $this->getActiveClass($menu) . ' ' . $menu['menu_class'] . $liClasses . '" '.$this->renderAttrs($menu).'>';
			if( $menu['show_title'] ){ 
				$output .= '<a href="'.$this->getLink( $menu ).'" ' . $aAttributes . '>';
			
				if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
				$output .= '<span class="menu-title">'.$menu['title']."</span>";
				if( $menu['description'] ){
					$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
				}
				if( $menu['image']){ 
					$output .= '</span>';
				}

				$output .= '</a>';
				if($menu['type'] == 'category_tree') {
				    $output .= $this->genTree($menu['megamenu_id'], $level+1, $menu, 0, true);
				}
			}
			$output .= '</li>';
		}
		return $output;
	}
	
	


	public function getCategoryParents($category_id) {
		$query = $this->db->query("SELECT parent_id FROM " . DB_PREFIX . "category c WHERE c.category_id = '" . (int)$category_id . "' ORDER BY c.sort_order");
	
		if($query->num_rows) {
			if ($query->row['parent_id']) {
				$cat = $this->getCategoryParents($query->row['parent_id']);
				return  ($cat?$cat . '_':'') . $query->row['parent_id'];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	
	/**
	 *
	 */
	public function getLink( $menu ) {
		$id = (int)$menu['item'];
		switch( $menu['type'] ){
		    case 'category_tree' :
		    	$parents = $this->getCategoryParents($id);
		        return $this->url->link('product/category', 'path=' . (!empty($parents)?$parents . '_':'') . $id );
		        ;
			case 'category'     :
		    	$parents = $this->getCategoryParents($id);
		    	Debug::log($parents);
		        return $this->url->link('product/category', 'path=' . (!empty($parents)?$parents . '_':'') . $id );
				;
			case 'product'      :
				return  $this->url->link('product/product', 'product_id=' . $id);
				;
			case 'information'  :
		
				return   $this->url->link('information/information', 'information_id=' . $id);
				;
			case 'manufacturer' :;
				return  $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $id);
			case 'allbrands' :;
				return  $this->url->link('product/manufacturer');
			case 'pavblog' :;
				return  $this->url->link('pavblog/category', 'id=' . $id);
			default: 
				return $menu['url'];
		}
	}
	
	public function getActiveClass($menu) {
	    $id = (int)$menu['item'];
	    switch( $menu['type'] ){
	        case 'category_tree' :
	            if(
	            (
	                    isset($this->request->get['route']) &&
	                    $this->request->get['route'] == 'product/category'
	            ) && (
	                    isset($this->request->get['path'])
	            )
	            ) {
	                $path = explode('_', $this->request->get['path']);
	                $parent_categories = $this->getParentCategories(end($path));
	                if(end($path) == $id || in_array($id, $parent_categories)) {
	                    return 'active';
	                } else {
	                    return '';
	                }
	            } else {
	                return '';
	            }
	    	case 'category'     :
	    	    if(
	    	        (
	                  isset($this->request->get['route']) && 
	                  $this->request->get['route'] == 'product/category'
		            ) && (
	                  isset($this->request->get['path'])
		            )
		          ) {
	    	        $path = explode('_', $this->request->get['path']);
	    	        $parent_categories = $this->getParentCategories(end($path));
	    	        if(end($path) == $id || in_array($id, $parent_categories)) {
	    	            return 'active';
	    	        } else {
	                    return '';
	                }
	    	    } else {
	    	        return '';
	    	    }
	    	case 'product'      :
	            if(
	    	        (
	                  isset($this->request->get['route']) && 
	                  $this->request->get['route'] == 'product/product'
		            ) && (
	                  isset($this->request->get['product_id'])
		            )
		          ) {
	                if(((int)$this->request->get['product_id']) == $id) {
	                    return 'active';	                    
	                } else {
	                    return '';
	                }
	    	        
	    	    } else {
    	           return '';
	    	    }
	    	case 'allbrands'      :
	            if(
	                  isset($this->request->get['route']) && 
	                  (
	                  	  $this->request->get['route'] == 'product/manufacturer' ||
	                  	  $this->request->get['route'] == 'product/manufacturer/info'
					  )
		          ) {
	                return 'active';
	    	    } else {
    	           return '';
	    	    }
	    	case 'pavblog'      :
	            if(
	    	        (
	                  isset($this->request->get['route']) && 
	                  $this->request->get['route'] == 'pavblog/category'
		            ) && (
	                  isset($this->request->get['id'])
		            )
		          ) {
	                if(((int)$this->request->get['id']) == $id) {
	                    return 'active';	                    
	                } else {
	                    return '';
	                }
	    	        
	    	    } else {
    	           return '';
	    	    }
	    	case 'information'  :
	            if(
	    	        (
	                  isset($this->request->get['route']) && 
	                  $this->request->get['route'] == 'information/information'
		            ) && (
	                  isset($this->request->get['information_id'])
		            )
		          ) {
	                if(((int)$this->request->get['information_id']) == $id) {
	                    return 'active';
	                } else {
	                    return '';
	                }
	    	    } else {
                    return '';
	    	    }
	    	case 'manufacturer' :;
    	    	if(
        	    	(
        	    	        isset($this->request->get['route']) &&
        	    	        $this->request->get['route'] == 'product/manufacturer/info'
        	    	) && (
        	    	        isset($this->request->get['manufacturer_id'])
        	    	)
        	    	) {
    	    	    if(((int)$this->request->get['manufacturer_id']) == $id) {
    	    	        return 'active';
    	    	    } else {
    	    	        return '';
    	    	    }
    	    	} else {
    	    	    return '';
    	    	}
	    	case 'url' :;
    	    	if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] == $menu['url']) {
    	    	    return 'active';
    	    	} else {
    	    	    return '';
    	    	}
	    	default:
    	        return '';
	    }
	}
	
	public function getParentCategories($category_id, $data = array()) {
	    if($category_id) {
    	    $category = $this->model_catalog_category->getCategory($category_id);
    	    
    	    if($category['parent_id']) {
    	        $data[] = $category['parent_id'];
    	        return $this->getparentCategories($category['parent_id'], $data);
    	    } else {
    	        return $data;
    	    }
	    } else {
	        return $data;
	    }
	}
	
	/**
	 *
	 */
	public function getColWidth( $menu, $cols ){
		$output = array();
		
		$split = preg_split('#\s+#',$menu['submenu_colum_width'] );
		if( !empty($split) && !empty($menu['submenu_colum_width']) ){
			foreach( $split as $sp ) {
				$tmp = explode("=",$sp);
				if( count($tmp) > 1 ){
					$output[trim(preg_replace("#col#","",$tmp[0]))]=(int)$tmp[1];
				}
			}
		}
		$tmp = array_sum($output);
		$spans = array();
		$t = 0; 
		for( $i=1; $i<= $cols; $i++ ){
			if( array_key_exists($i,$output) ){
				$spans[$i] = 'span'.$output[$i];
			}else{		
				if( (12-$tmp)%($cols-count($output)) == 0 ){
					$spans[$i] = "span".((12-$tmp)/($cols-count($output)));
				}else {
					if( $t == 0 ) {
						$spans[$i] = "span".( ((11-$tmp)/($cols-count($output))) + 1 ) ;
					}else {
						$spans[$i] = "span".( ((11-$tmp)/($cols-count($output))) + 0 ) ;
					}
					$t++;
				}					
			}
		}
		return $spans;
	}
	
	/**
	 *
	 */
	public function getResponsiveTree(){
	
	}
	
}
?>