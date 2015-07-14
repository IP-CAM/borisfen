<?php
class ControllerModuleSpecial extends Controller {
	protected function index($setting) {
		$this->language->load('module/special');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		
		$this->data['setting'] = $setting;

		$this->data['products'] = array();
		
		$data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);
		$cart_products = $this->cart->getProducts();
		$cart_ids = array();
		if(is_array($cart_products) && !empty($cart_products)) {
			foreach ($cart_products as $cart_product) {
				$cart_ids[] = $cart_product['product_id'];
			}
			array_unique($cart_ids);
		}
		$results = $this->model_catalog_product->getProductSpecials($data);

		foreach ($results as $result) {
			if(!$result) continue;
            // Product category
            $product_category = $this->model_catalog_product->getDeepestCategory($result['product_id']);
            if(!empty($product_category)) {
                $result['category'] = array(
                    'name' => $product_category['name'],
                    'url' => $this->url->link('product/category', 'path=' . $product_category['category_id'])
                );
            } else {
                $result['category'] = array();
            }

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', $setting['image_width'], $setting['image_height']);
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
					
			if ((float)$result['special']) { 
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
			
			if($result['quantity'] < 1) {
			    $out_of_stock = $result['stock_status'];
			} else {
			    $out_of_stock = false;
			}
			
			if($result['quantity'] <= $this->config->get('left_a_bit_quantity') && $result['quantity'] > 0) {
			    $left_of_stock = $this->model_catalog_product->getStockStatus(2);
			} else {
			    $left_of_stock = false;
			}
			
			if(!$left_of_stock && !$out_of_stock) {
			    $in_stock = $this->model_catalog_product->getStockStatus(1);
			} else {
			    $in_stock = false;
			}
			
			/*code start*/
			if((strtotime(date('Y-m-d')) >= strtotime($result['promo_date_start'])) && (strtotime(date('Y-m-d')) <= strtotime($result['promo_date_end'])) || (($result['promo_date_start'] == '0000-00-00') && ($result['promo_date_end'] == '0000-00-00'))) {
			    $promo_on = TRUE;
			} else {
			    $promo_on = FALSE;
			}
				
			$promo_top_right = $this->model_catalog_product->getPromo($result['product_id'],$result['promo_top_right']);
			if (!empty($promo_top_right['promo_text']) && $promo_on) {
			    $promo_tag_top_right = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_top_right['image'] . '\') no-repeat;background-position:top right"></span>';
			} else {
			    $promo_tag_top_right = '';
			}
			
			$promo_top_left = $this->model_catalog_product->getPromo($result['product_id'],$result['promo_top_left']);
			if (!empty($promo_top_left['promo_text']) && $promo_on) {
			    $promo_tag_top_left = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_top_left['image'] . '\') no-repeat;background-position:top left"></span>';
			} else {
			    $promo_tag_top_left = '';
			}
			
			$promo_bottom_left = $this->model_catalog_product->getPromo($result['product_id'],$result['promo_bottom_left']);
			if (!empty($promo_bottom_left['promo_text']) && $promo_on) {
			    $promo_tag_bottom_left = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_bottom_left['image'] . '\') no-repeat;background-position:bottom left"></span>';
			} else {
			    $promo_tag_bottom_left = '';
			}
			
			$promo_bottom_right = $this->model_catalog_product->getPromo($result['product_id'],$result['promo_bottom_right']);
			if (!empty($promo_bottom_right['promo_text']) && $promo_on) {
			    $promo_tag_bottom_right = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_bottom_right['image'] . '\') no-repeat;background-position:bottom right"></span>';
			} else {
			    $promo_tag_bottom_right = '';
			}
			/*code end*/
			
			$special_end = 0;
    				if($result['special_end'] && $result['special_end'] >= date('Y-m-d H:i:s') && $result['special_start'] <= date('Y-m-d H:i:s')) {
    					$time = new DateTime($result['special_end']);
    					$special_end = $time->getTimestamp() - time();
    				}
            
			$this->data['products'][] = array(
				'product_id'    => $result['product_id'],
				'thumb'   	    => $image,
				'name'    	    => $result['name'],
                'quantity'      => $result['quantity'],
                'model'         => $result['model'],
                'category'      => $result['category'],
				'in_cart'		=> in_array($result['product_id'], $cart_ids),
                'out_of_stock'  => $out_of_stock,
                'left_of_stock' => $left_of_stock,
                'in_stock'      => $in_stock,
                /*code start*/
                'promo_tag_top_right'		=> $promo_tag_top_right,
                'promo_tag_top_left'		=> $promo_tag_top_left,
                'promo_tag_bottom_left'		=> $promo_tag_bottom_left,
                'promo_tag_bottom_right'	=> $promo_tag_bottom_right,
                /*code end*/
				'price'   	    => $price,
				'special' 	    => $special,
				'special_end'	=> $special_end,
				'rating'        => $rating,
				'description'   => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
				'short_description' => strip_tags(html_entity_decode($result['short_description'], ENT_QUOTES, 'UTF-8'), '<b><i><strong>'),
				'reviews'       => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	    => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/special.tpl';
		} else {
			$this->template = 'default/template/module/special.tpl';
		}

		$this->render();
	}
}
?>