<?php
class ControllerModuleFlexSlider extends Controller {
	protected function index($setting) {
		static $module = 1;
		
		$this->load->model ( 'design/banner' );
		$this->load->model ( 'tool/image' );
		
// 		$this->document->addScript ( 'catalog/view/javascript/jquery/flexslider/jquery.flexslider.js' );
// 		$this->document->addScript ( 'catalog/view/javascript/jquery/flexslider/jquery.mousewheel.js' );
// 		$this->document->addStyle ( 'catalog/view/javascript/jquery/flexslider/flexslider.css' );
		
		$this->data ['width'] = $setting ['width'];
		$this->data ['height'] = $setting ['height'];
		$this->data ['slideshow'] = $setting ['slideshow'];
		$this->data ['animation'] = $setting ['animation'];
		$this->data ['speed'] = $setting ['speed'];
		$this->data ['duration'] = $setting ['duration'];
		$this->data ['direction'] = $setting ['direction'];
		$this->data ['pause'] = $setting ['pause'];
		$this->data ['direction_nav'] = $setting ['direction_nav'];
		$this->data ['control_nav'] = $setting ['control_nav'];
		$this->data ['touch'] = $setting ['touch'];
		$this->data ['mousewheel'] = $setting ['mousewheel'];
		$this->data ['thumbnails'] = $setting ['thumbnails'];
		$this->data ['item_width'] = $setting ['item_width'];
		$this->data ['max_items'] = $setting ['max_items'];
		$this->data ['min_items'] = $setting ['min_items'];
		$this->data ['caption'] = $setting ['caption'];
		$this->data ['caption_button'] = $setting ['caption_button'];
		$this->data ['caption_position'] = $setting ['caption_position'];
		$this->data ['caption_button_text'] = $setting ['caption_button_text'] [$this->config->get ( 'config_language_id' )];
		
		$this->data ['banners'] = array ();
		
		if (isset ( $setting ['banner_id'] )) {
			$results = $this->model_design_banner->getBanner ( $setting ['banner_id'] );
			
			foreach ( $results as $result ) {
				if (file_exists ( DIR_IMAGE . $result ['image'] )) {
					$this->data ['banners'] [] = array (
							'title' => $result ['title'],
// 							'description' => $result ['description'],
							'description' => false,
							'link' => $result ['link'],
							'image' => $this->model_tool_image->resize ( $result ['image'], $setting ['width'], $setting ['height'] ),
							'thumb' => $this->model_tool_image->resize ( $result ['image'], $setting ['width'] / 4, $setting ['height'] / 4 ) 
					);
				}
			}
		}
		
		$this->data ['module'] = $module ++;
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/flexslider.tpl' )) {
			$this->template = $this->config->get ( 'config_template' ) . '/template/module/flexslider.tpl';
		} else {
			$this->template = 'default/template/module/flexslider.tpl';
		}
		
		$this->render ();
	}
}
?>