<?php 
class ControllerGalleryGallery extends Controller {  
	public function index() { 
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/po_gallery.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/po_gallery.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/po_gallery.css');
		}
		
		$this->document->addStyle('http://fonts.googleapis.com/css?family=Lobster');
	
		$this->language->load('gallery/gallery');
		
		$this->load->model('catalog/po_gallery');
				
		$this->load->model('tool/image'); 
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}	
							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			if($this->config->get('po_gallery_limit')) {
				$limit = $this->config->get('po_gallery_limit');
			} else {
				$limit = 4;
			}
		}
					
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_gallery'),
			'href'      => $this->url->link('gallery/gallery'),
       		'separator' => $this->language->get('text_separator')
   		);	
		
		$this->document->setTitle($this->language->get('text_heading'));
			
		$this->data['heading_title'] = $this->language->get('text_heading');
			
		$this->data['text_empty'] = $this->language->get('text_empty');	
		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_grid'] = $this->language->get('text_grid');
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');	
		
		$this->data['font_size'] = $this->config->get('po_gallery_album_title_font');
		$this->data['apr'] = $this->config->get('po_gallery_album_per_row');
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
		
		$this->data['albums'] = array();

		$data = array(
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);
					
		$product_total = $this->model_catalog_po_gallery->getTotalAlbums($data); 
			
		$results = $this->model_catalog_po_gallery->getAlbums($data);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $width , $height);
				} else {
					$image = $this->model_tool_image->resize('no_image.jpg', $width , $height);
				}
				
				if ($this->config->get('po_gallery_show_rating')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
								
				$this->data['albums'][] = array(
					'album_id'  => $result['album_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
					'rating'      => $rating,
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('gallery/album','&album_id=' . $result['album_id'])
				);
			}
			
			$url = '';
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
							
			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('gallery/gallery', '&sort=p.sort_order&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('gallery/gallery', '&sort=pd.name&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('gallery/gallery', '&sort=pd.name&order=DESC' . $url)
			);
						
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_newest'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('gallery/gallery', '&sort=p.date_added&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_date_oldest'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('gallery/gallery', '&sort=p.date_added&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('gallery/gallery', '&sort=rating&order=DESC' . $url)
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('gallery/gallery', '&sort=rating&order=ASC' . $url)
			);
			
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['limits'] = array();
			
			$this->data['limits'][] = array(
				'text'  => 4,
				'value' => 4,
				'href'  => $this->url->link('gallery/gallery', $url . '&limit=' . 4 )
			);
			
			$this->data['limits'][] = array(
				'text'  => 8,
				'value' => 8,
				'href'  => $this->url->link('gallery/gallery', $url . '&limit=' . 8 )
			);
						
			$this->data['limits'][] = array(
				'text'  => 12,
				'value' => 12,
				'href'  => $this->url->link('gallery/gallery', $url . '&limit=12')
			);
			
			$this->data['limits'][] = array(
				'text'  => 16,
				'value' => 16,
				'href'  => $this->url->link('gallery/gallery', $url . '&limit=16')
			);

			$this->data['limits'][] = array(
				'text'  => 20,
				'value' => 20,
				'href'  => $this->url->link('gallery/gallery', $url . '&limit=20')
			);
			
			$this->data['limits'][] = array(
				'text'  => 24,
				'value' => 24,
				'href'  => $this->url->link('gallery/gallery', $url . '&limit=24')
			);
						
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
					
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('gallery/gallery', $url . '&page={page}');
		
			$this->data['pagination'] = $pagination->render();
		
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
		
			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/gallery.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/gallery/gallery.tpl';
			} else {
				$this->template = 'default/template/gallery/gallery.tpl';
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