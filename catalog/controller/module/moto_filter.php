<?php  
class ControllerModulemotofilter extends Controller {
    
    private $filter_id  = false;
    private $mark_id    = false;
    private $model_id   = false;
    private $year_id    = false;
    private $depth      = false;
    private $root_id    = 71;
    
	protected function index($setting) {
	    $this->document->addScript(SCRIPT_FOLDER . 'moto_filter.js');
	    $this->language->load('module/moto_filter');
	    $this->load->model('catalog/category');
	    
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['marks']  = array();
		$this->data['models'] = array();
		$this->data['years']  = array();
		$selected = array(
            'mark'  => false,
            'model' => false,
            'year'  => false,
		);
		
		if(isset($this->request->get['filter_id'])) {
		    
		    $this->filter_id = (int)$this->request->get['filter_id'];
		    $this->depth = $this->getSelectedDepth();
		    
		    $selected['mark']  = $this->getSelectedFilter('marks');
		    $selected['model'] = $this->getSelectedFilter('models');
		    $selected['year']  = $this->getSelectedFilter('years');
		    
		}
				
		/* Start Marks */
		$categories = $this->model_catalog_category->getCategories($this->root_id);
		
		foreach ($categories as $category) {
		    $this->data['marks'][] = array(
		    	'name'        => $category['name'],
		    	'category_id' => $category['category_id'],
		    	'selected'    => ($selected['mark'] == $category['category_id']),
		    );
		}
		/* End Marks */
		/* Start Models */
		if($this->mark_id) {
    		$categories = $this->model_catalog_category->getCategories($this->mark_id);
    		foreach ($categories as $category) {
    		    $this->data['models'][] = array(
                    'name'        => $category['name'],
                    'category_id' => $category['category_id'],
                    'selected'    => ($selected['model'] == $category['category_id']),
    		    );
    		}
		}
		/* End Models */
		/* Start Years */
		if($this->model_id) {
		    $categories = $this->model_catalog_category->getCategories($this->model_id);
		    foreach ($categories as $category) {
		        $this->data['years'][] = array(
                    'name'        => $category['name'],
                    'category_id' => $category['category_id'],
                    'selected'    => ($selected['year'] == $category['category_id']),
		        );
		    }
		}
		/* End Years */
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/moto_filter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/moto_filter.tpl';
		} else {
			$this->template = 'default/template/module/moto_filter.tpl';
		}
		
		$this->render();
	}
	
	private function getSelectedFilter($fieldType = 'marks') {
	    $selected_id = false;
	    
	    if($this->filter_id) {
    	    switch ($fieldType) {
    	    	case 'marks':
    	    	    if($this->depth == 3) {
    	    	        $parentCategory = $this->model_catalog_category->getCategory($this->filter_id);
    	    	        $category = $this->model_catalog_category->getCategory($parentCategory['parent_id']);
    	    	        $selected_id = $this->mark_id = $category['parent_id'];
    	    	        
    	    	    } elseif($this->depth == 2) {
    	    	        $category = $this->model_catalog_category->getCategory($this->filter_id);
    	    	        $selected_id = $this->mark_id = $category['parent_id'];
    	    	    } elseif($this->depth == 1) {
    	    	        $selected_id = $this->mark_id = $this->filter_id;
    	    	    }
    	    	    break;
    	    	case 'models':
    	    	    if($this->depth == 2) {
    	    	        $selected_id = $this->model_id = $this->filter_id;
    	    	    } elseif($this->depth > 2) {
    	    	        $category = $this->model_catalog_category->getCategory($this->filter_id);
    	    	        $selected_id = $this->model_id = $category['parent_id'];
    	    	    }
    	    	    break;
    	    	case 'years':
    	    	    if($this->depth == 3) {
    	    	        $selected_id = $this->year_id = $this->filter_id;
    	    	    }
    	    	    break;
    	    	default:
    	    	    break;
    	    }
    	    
	    }
	    
	    return $selected_id;
	}
	
	private function getSelectedDepth($category_id = 0, $level = 1) {
	    if($this->filter_id) {
	        if(!$category_id) {
	           $category_id = $this->filter_id;
	        }
	        $category = $this->model_catalog_category->getCategory($category_id);
	        
	        if($category['parent_id'] && ($category['parent_id'] != $this->root_id)) {
	            return $this->getSelectedDepth($category['parent_id'], ++$level);
	        } elseif(!$category['parent_id']) {
	            return false;
	        } else {
	            return $level;
	        }
	    }
	}
	
	public function getChilds() {
	    if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') && (isset($this->request->post['child_id']))){
	        if((int)$this->request->post['child_id'] != 0) {
    	        $this->load->model('catalog/category');
    	        $categories = $this->model_catalog_category->getCategories((int)$this->request->post['child_id']);
    	        $this->data['categories'] = array();
    	        foreach ($categories as $category) {
    	            $this->data['categories'][] = array(
    	            	'name'        => $category['name'],
    	            	'category_id' => $category['category_id'],
    	            );
    	        }
    	        exit(json_encode($this->data['categories']));
	        } else {
	            exit(json_encode(array()));
	        }
	    } else {
	        die('error');
	    }
	}
	
	public function getLink() {
	    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            if(isset($this->request->post['path'])) {
                $url_data = parse_url(str_replace('&amp;', '&', $this->request->post['path']));
                $var_pares = explode('&', $url_data['path']);
                if(substr($url_data['path'], 0, 1) == '/') {
                   array_shift($var_pares);
                }
                
                $request = array();
                $p = 0;
                $additionals = '&' . implode($var_pares);
            } else {
                $additionals = '';
            }
            if(isset($this->request->post['category_id'])) {
                if(isset($this->request->post['filter_id'])) {
                    $response['uri'] = $this->url->link('product/category', 'path=' . (int)$this->request->post['category_id'] . '&filter_id=' . (int)$this->request->post['filter_id'] . $additionals);
                } else {
                    $response['uri'] = $this->url->link('product/category', 'path=' . (int)$this->request->post['category_id'] . $additionals);
                }
            } else {
                if(isset($this->request->post['filter_id'])) {
                    $response['uri'] = $this->url->link('product/category', 'filter_id=' . (int)$this->request->post['filter_id'] . $additionals);
                } else {
                    $response['uri'] = $this->url->link('product/category', $additionals);
                }
            }
	        exit(json_encode($response));
	    }
	}
}
?>