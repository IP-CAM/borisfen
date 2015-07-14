<?php
class ControllerModuleBlogevent extends Controller {
	protected function index($setting) {
		$this->language->load('module/blogevent');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->load->model('tool/image');
		$this->load->model('pavblog/blog');
		$blog = $this->model_pavblog_blog->getNearesBlog();
		if(!empty($blog)) {
			$event = array();
			$this->load->model('tool/image');
			$event['title'] = $blog['title'];
			$event['date_event'] = $blog['date_event'];
			$event['description'] = $blog['description'];
			if(!empty($blog['image']) && file_exists(DIR_IMAGE . $blog['image'])) {
				$event['image'] = $this->model_tool_image->resize($blog['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$event['image'] = $this->model_tool_image->resize('no_image.jpg', $setting['image_width'], $setting['image_height']);
			}
			$event['href'] = $this->url->link('pavblog/blog', 'id=' . $blog['blog_id']);
			$this->data['blog'] = $event;
		} else {
			$this->data['blog'] = array();
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blogevent.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/blogevent.tpl';
		} else {
			$this->template = 'default/template/module/blogevent.tpl';
		}

		$this->render();
	}
}
?>