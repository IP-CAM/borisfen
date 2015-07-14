<?php  
class ControllerModuleManufacturer extends Controller {
	protected function index() {
		$this->language->load('module/manufacturer');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$this->data['manufacturer_id'] = $parts[0];
		} else {
			$this->data['manufacturer_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
							
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->data['manufactureres'] = array();
					
		$manufactureres = $this->model_catalog_manufacturer->getManufacturers(0);
		$man_block = 0;
		$m_counter = 0;
		
		foreach($manufactureres as $manufacturer) {
		    if($manufacturer['image']) {
		        $image = $this->model_tool_image->resize($manufacturer['image'], 276, 276);
		    } else {
		        $image = $this->model_tool_image->resize('no_image.jpg', 276, 276);
		    }
			$this->data['manufactureres'][$man_block][] = array(
				'manufacturer_id' => $manufacturer['manufacturer_id'],
				'name'            => $manufacturer['name'] ,
				'href'            => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']),
                'image'           => $image,
			);
			$m_counter++;
			if($m_counter > 9) {
			    $man_block++;
			    $m_counter = 0;
			}
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/manufacturer.tpl';
		} else {
			$this->template = 'default/template/module/manufacturer.tpl';
		}
		
		$this->render();
  	}
}
?>