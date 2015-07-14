<?php  
class ControllerModuleCategory extends Controller {
	protected function index($setting) {
		$this->language->load('module/category');

		$this->data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$this->data['active_categories'] = array();
		foreach($parts as $part){
			$this->data['active_categories'][] = $part;
		}
		
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->data['categories'] = array();

		$all_categories = $this->model_catalog_category->getAllCategories();
		$categories = array();
		foreach($all_categories as $category){
			$categories[$category['category_id']] = $category;
		}

		$main_categories = $this->model_catalog_category->getCategories(0);
		$this->data['main_categories'] = array();
		foreach ($main_categories as $category) {
			$this->data['main_categories'][] = $category['category_id'];
		}
		$this->recursiveFillCategories($categories, $this->data['main_categories'], 1, '');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category.tpl';
		} else {
			$this->template = 'default/template/module/category.tpl';
		}
		
		$this->render();
	}
	
	private function recursiveFillCategories($all_categories, $categories, $lvl, $path){
		foreach ($categories as $category_id) {
			
			$category = $all_categories[$category_id];
			
			$children_categories = array();
			foreach($all_categories as $cat){
				if($cat['parent_id'] == $category_id){
					$children_categories[] = $cat['category_id'];
				}
			}
			
			$this_path = $path . ($path ? '_' . $category_id : $category_id); 
			 
			$this->data['categories'][$category_id] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'],
				'children'    => $children_categories,
				'href'        => $this->url->link('product/category', 'path=' . $this_path),
				'active'	  => in_array($category_id, $this->data['active_categories']),
				'level'		  => $lvl
			);
			
			if(count($children_categories) > 0){
				$lvl++;
				$this->recursiveFillCategories($all_categories, $children_categories, $lvl, $this_path);
			}
			
		} 
	}
}















?>