<?php  
class ControllerCommonHome extends Controller {
	public function index() {
		
	    //if (isset($this->session->data['proute']) && (($this->session->data['proute'] == 'product/product') || ($this->session->data['proute'] == 'product/category') || ($this->session->data['proute'] == 'product/manufacturer/product') || ($this->session->data['proute'] == 'information/information') || ($this->session->data['proute'] == 'product/manufacturer/info'))) {unset($this->request->post['redirect']);$this->session->data['proute'] = '';}
	    $this->session->data['proute'] = 'common/home';
	    $titles = $this->config->get('config_title');
	    $this->document->setTitle($titles[$this->config->get('config_language_id')]);
		$this->document->addLink($this->config->get('config_url'), 'canonical');
		$this->document->setKeywords($this->config->get('config_meta_keywords'));
		$meta_descriptions = $this->config->get('config_meta_description');
		$this->document->setDescription($meta_descriptions[$this->config->get('config_language_id')]);

		$this->data['heading_title'] = $titles[$this->config->get('config_language_id')];
		
		/* layout patch - choose template by path */
		$this->load->model ( 'design/layout' );
		if (isset ( $this->request->get ['route'] )) {
			$route = ( string ) $this->request->get ['route'];
		} else {
			$route = 'common/home';
		}
		$layout_template = $this->model_design_layout->getLayoutTemplate($route);
		if(!$layout_template){
			$layout_template = 'home';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/' . $layout_template . '.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/' . $layout_template . '.tpl';
		} else {
			$this->template = 'default/template/common/' . $layout_template . '.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->response->setOutput($this->render());
	}
}
?>