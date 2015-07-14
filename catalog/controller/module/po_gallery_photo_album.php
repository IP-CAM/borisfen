<?php
class ControllerModulePoGalleryPhotoAlbum extends Controller {
	protected function index($setting) {
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/po_gallery.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/po_gallery.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/po_gallery.css');
		}
		
		$this->document->addStyle('http://fonts.googleapis.com/css?family=Lobster');
		
		$this->language->load('module/po_gallery_photo_album');
		
      	$this->data['heading_title'] =  $setting['title'];
		
		$this->data['font_size'] = $this->config->get('po_gallery_album_title_font');
		
		$this->data['apr'] = $setting['apr'];
		$this->data['type'] = $this->config->get('po_gallery_type');
		
		if($this->config->get('po_gallery_album_width') == 120) {
			if($this->data['type'] == 'square') {
					$width = 120; 
					$height = 120;
			}
			if($this->data['type'] == 'rectangular') {
					$width = 120; 
					$height = 90;
			}
		} else if ($this->config->get('po_gallery_album_width') == 160) {
			if($this->data['type'] == 'square') {
				$width = 160; 
				$height = 160;
			}
			if($this->data['type'] == 'rectangular') {
				$width = 160; 
				$height = 120;
			}
		} else {
			if($this->data['type'] == 'square') {
				$width = 200; 
				$height = 200;
			}
			if($this->data['type'] == 'rectangular') {
				$width = 200; 
				$height = 150;
			}
		}
		
		$this->data['bgwidth'] = $width;
		$this->data['alheight'] = $height + 80;
				
		if($setting['sort'] == 'latest') {
			$sort = 'p.date_added';
			$order = 'DESC';
		}
		if($setting['sort'] == 'viewed') {
			$sort = 'p.viewed';
			$order = 'DESC';
		}
		if($setting['sort'] == 'name') {
			$sort = 'pd.name';
			$order = 'ASC';
		}
		if($setting['sort'] == 'rating') {
			$sort = 'rating';
			$order = 'DESC';
		}
				
		$this->data['button_cart'] = $this->language->get('button_cart');
				
		$this->load->model('catalog/po_gallery');
		
		$this->load->model('tool/image');
		
		$this->data['products'] = array();
		
		$data = array(
			'sort'  => $sort, //'pd.name',
			'order' => $order, //'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_po_gallery->getAlbums($data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $width , $height );
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', $width , $height );
			}
						
			if ($this->config->get('po_gallery_show_rating')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
									
			$this->data['products'][] = array(
				'album_id' => $result['album_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('gallery/album', 'album_id=' . $result['album_id']),
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/po_gallery_photo_album.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/po_gallery_photo_album.tpl';
		} else {
			$this->template = 'default/template/module/po_gallery_photo_album.tpl';
		}

		$this->render();
	}
}
?>