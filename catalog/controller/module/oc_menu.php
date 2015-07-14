<?php  
class ControllerModuleOcmenu extends Controller {
	var $group_id;
	var $is_active_category;
	var $curent_category;
	var $methodPrefix = 'getContentFor_';
	
	protected function index($setting) {
		$this->language->load('module/oc_menu');
		$this->load->model('module/oc_menu');
		
		$this->group_id = $setting['group_id'];
		
		$menu_array = $this->recursiveFill(0);

		$this->data = array();
		$this->data['menu_array'] = $menu_array;
		$this->data['position'] = $setting['position'];
		$this->data['note'] = $setting['note'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/oc_menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/oc_menu.tpl';
		} else {
			$this->template = 'default/template/module/oc_menu/oc_menu.tpl';
		}
		$this->render();
	}
	
	private function recursiveFill($parent_id){
		$tmp_menu = array();
		$menu = array();
		$curent_list = $this->model_module_oc_menu->getMenuByGroupIdAndParentId($this->group_id, $parent_id);
		
		if(!count($curent_list))
			return array();
		
		foreach($curent_list as $key => $menu_item){
			$content_method = $this->methodPrefix . $menu_item['type_key'];
			$hasChild = $this->model_module_oc_menu->hasChild($menu_item['menu_id']);
			$properties = $this->model_module_oc_menu->getPropertiesByMenuId($menu_item['menu_id']);
			$firstClass = ($key == 0) ? 'first' : '';
			$hasChildClass = ($hasChild) ? 'parent' : '';
			
			$tmp_menu['columns_count'] = 	$menu_item['columns'] == 0 ? 1 : $menu_item['columns'];
			$tmp_menu['name'] =				$menu_item['name'];
			$tmp_menu['icon'] =				$menu_item['icon'];
			$tmp_menu['has_child'] =		$hasChild;
			$tmp_menu['columns'] = 			$this->model_module_oc_menu->getColumnsByMenuId($menu_item['menu_id']);
			$tmp_menu['classes'] = 			trim(implode(' ', array($menu_item['class'], $hasChildClass)));
			$tmp_menu['children'] = 		$this->recursiveFill($menu_item['menu_id']);
			$tmp_menu['children_count'] = 	count($tmp_menu['children']);
			$tmp_menu['active']	=			false;
			$tmp_menu['content'] = 			$this->$content_method($tmp_menu, $properties);
			
			$menu[] = $tmp_menu;
		}
		return $menu;
	}
	
	//||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	//|||||||||||||||||||||||||||'GET CONTENT' FUNCTIONS||||||||||||||||||||||||||||||||||||
	//||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	private function getContentFor_category(&$menu, $properties){//var_dump($menu);die;
		$this->load->model('catalog/category');
		if(isset($this->request->get['route']) && $this->request->get['route'] == 'product/category' && $this->request->get['path'] == $properties['category_id']['value']){
			$menu['active'] = true;
		}
		$this->data['url'] = $this->url->link('product/category', 'path=' . $properties['category_id']['value'], 'SSL');
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_simple.tpl';
		return $this->render();
	}
	//-------------------------------------------------------------------------------------
	private function getContentFor_products_list(&$menu, $properties){
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$product_ids = explode(',', $properties['products_list']['value']);
		$products = array();
		foreach($product_ids as $product_id){
			$p = $this->model_catalog_product->getProduct($product_id);
			if($p){
				$p['href'] = $this->url->link('product/product', 'product_id=' . $p['product_id']);
				if ($p['image'] && file_exists(DIR_IMAGE . $p['image'])) {
					$p['image'] = $this->model_tool_image->scale($p['image'], $this->config->get('config_image_category_width'), 'x');
				} else {
					$p['image'] = $this->model_tool_image->scale('no_image.jpg', $this->config->get('config_image_category_width'), 'x');
				}
				$products[] = $p;
			}
		}
		$this->data['products'] = $products;
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_products_list.tpl';
		return $this->render();
	}
	//-------------------------------------------------------------------------------------
	private function getContentFor_html(&$menu, $properties){
		$this->data['data'] = html_entity_decode($properties['html']['value']);			
		$this->template = 'default/template/module/oc_menu/type_html.tpl';
		return $this->render();
	}
	//-------------------------------------------------------------------------------------
	private function getContentFor_information(&$menu, $properties){
		if(isset($this->request->get['route']) && $this->request->get['route'] == 'information/information' && $this->request->get['information_id'] == $properties['information_id']['value']){
			$menu['active'] = true;
		}
		$this->data['url'] = $this->url->link('information/information', 'information_id=' . $properties['information_id']['value'], 'SSL');
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_simple.tpl';
		return $this->render();
	}
	//-------------------------------------------------------------------------------------
	private function getContentFor_gallery_link(&$menu, $properties){
		if(isset($this->request->get['route']) && $this->request->get['route'] == 'gallery/gallery'){
			$menu['active'] = true;
		}
		$this->data['url'] = $this->url->link('gallery/gallery', '', 'SSL');
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_simple.tpl';
		return $this->render();
	}
	//-------------------------------------------------------------------------------------
	private function getContentFor_blog_category(&$menu, $properties){
		if(isset($this->request->get['route']) && $this->request->get['route'] == 'pavblog/category' && $this->request->get['id'] == $properties['blog_category_id']['value']){
			$menu['active'] = true;
		}
		$this->data['url'] = $this->url->link('pavblog/category', 'id=' . $properties['blog_category_id']['value'], 'SSL');
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_simple.tpl';
		return $this->render();
	}
	//-------------------------------------------------------------------------------------
	/*
	private function getContentFor_category_list(&$menu, $properties){
		$this->load->model('catalog/category');
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		$this->data['active_categories'] = array();
		foreach($parts as $part){
			$this->data['active_categories'][] = $part;
		}
		
		$activeClass = '';
		if(in_array($properties['root_category_id']['value'], $this->data['active_categories'])){
			$activeClass = 'active';
		}
		
		$deep_level = $properties['deep_level']['value'] > 0 ? $properties['deep_level']['value'] : 100;
		
		$this->is_active_category = false;
		$categories = $this->_getCategories($properties['root_category_id']['value'], $deep_level, 0, '');
		
		if($this->is_active_category || $activeClass){
			$menu['classes'] = $menu['classes'] . ' active';
		}
		
		$menu['href'] = $this->url->link('product/category', 'path=' . $properties['root_category_id']['value']);
		
		$this->data['categories'] = $categories;
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_category_list.tpl';
		return $this->render();
	}*/

	// NEW VERSION (from profi_flowers)
	private function getContentFor_category_list(&$menu, $properties){
		$this->load->model('catalog/category');
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		$this->data['active_categories'] = array();
		foreach($parts as $part){
			$this->data['active_categories'][] = $part;
		}

		$activeClass = '';
		if(in_array($properties['root_category_id']['value'], $this->data['active_categories'])){
			$activeClass = 'active';
		}

		$deep_level = $properties['deep_level']['value'] > 0 ? $properties['deep_level']['value'] : 100;

		$this->is_active_category = false;
		$categories = $this->_getCategories($properties['root_category_id']['value'], $deep_level, 0, '');

		if($this->is_active_category || $activeClass){
			$menu['classes'] = $menu['classes'] . ' active';
		}

		$menu['href'] = $this->url->link('product/category', 'path=' . $properties['root_category_id']['value']);

		$this->data['categories'] = $categories;
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_category_list.tpl';
		return $this->render();
	}
	
	private function _getCategories($parent_id, $depth, $lvl, $path){
		$categories = array();
		$tmp = array();
		$curent_categories = $this->model_catalog_category->getCategories($parent_id);
	
		if(!count($curent_categories) || $lvl > $depth)
			return array();
	
		foreach($curent_categories as $index => $cat){
			$this_path = $path . ($path ? '_' . $cat['category_id'] : $cat['category_id']);
			$firstClass = $index ? '' : 'first';
			$activeClass = '';
				
			if(in_array($cat['category_id'], $this->data['active_categories'])){
				$activeClass = 'active';
				$this->is_active_category = true;
			}
				
			$tmp['category_id'] = 	$cat['category_id'];
			$tmp['name'] = 			$cat['name'];
			$tmp['lvl'] = 			$lvl;
			$tmp['path'] = 			$this_path;
			$tmp['href'] = 			$this->url->link('product/category', 'path=' . $this_path);
			$tmp['children'] = 		$this->_getCategories($cat['category_id'], $depth, $lvl+1, $this_path);
			$parentClass =			count($tmp['children']) ? 'parent' : '';
			$tmp['classes'] = 		trim(implode(' ', array($firstClass, $parentClass, $activeClass)));
				
			$categories[] = $tmp;
		}
		return $categories;
	}
	//-------------------------------------------------------------------------------------
	private function getContentFor_manufacturer_link(&$menu, $properties){
		if(isset($this->request->get['route']) && $this->request->get['route'] == 'product/manufacturer'){
			$menu['active'] = true;
		}
		$this->data['url'] = $this->url->link('product/manufacturer', '', 'SSL');
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_simple.tpl';
		return $this->render();
	}
	//-------------------------------------------------------------------------------------
	private function getContentFor_gallery(&$menu, $properties){
		if(isset($this->request->get['route']) && $this->request->get['route'] == 'gallery/album' && $this->request->get['album_id'] == $properties['gallery_id']['value']){
			$menu['active'] = true;
		}
		$this->data['url'] = $this->url->link('gallery/album', 'album_id=' . $properties['gallery_id']['value'], 'SSL');
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_simple.tpl';
		return $this->render();
	}
	//-------------------------------------------------------------------------------------
	private function getContentFor_custom_link(&$menu, $properties){
		if ($properties['uri']['value'] == '/'
				&& (!isset($this->request->get['route']) || $this->request->get['route'] == 'common/home')
		) {
			$menu['active'] = true;
		}
		
		if (!empty($this->request->get['route'])) {
			if ($this->request->get['route'] == 'account/wishlist' && $properties['uri']['value'] == '/wishlist') {
				$menu['active'] = true;
			}
		
			if ($this->request->get['route'] == 'information/contact' && $properties['uri']['value'] == '/contact') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'account/account' && $properties['uri']['value'] == '/account') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'information/sitemap' && $properties['uri']['value'] == '/sitemap') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'product/manufacturer' && $properties['uri']['value'] == '/manufacturer') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'affiliate/account' && $properties['uri']['value'] == '/affiliates') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'product/special' && $properties['uri']['value'] == '/special') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'account/login' && $properties['uri']['value'] == '/login') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'account/logout' && $properties['uri']['value'] == '/logout') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'account/register' && $properties['uri']['value'] == '/register') {
				$menu['active'] = true;
			}
			if ($this->request->get['route'] == 'product/testimonial' && $properties['uri']['value'] == '/reviews') {
				$menu['active'] = true;
			}
		
		}
				
		$this->data['url'] = $properties['uri']['value'];
		$this->data['menu'] = $menu;
		$this->template = 'default/template/module/oc_menu/type_simple.tpl';
		return $this->render();	
	}
	//||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	//||||||||||||||||||||||||||||||||End of block||||||||||||||||||||||||||||||||||||||||||
	//||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
	
	



}











?>

