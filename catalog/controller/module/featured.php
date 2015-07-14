<?php
class ControllerModuleFeatured extends Controller {
	protected function index($setting) {
		$this->language->load('module/featured'); 

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('catalog/product'); 
		
		$this->load->model('tool/image');
		
		$this->data['setting'] = $setting;

		$this->data['products'] = array();
		$cart_products = $this->cart->getProducts();
		$cart_ids = array();
		if(is_array($cart_products) && !empty($cart_products)) {
			foreach ($cart_products as $cart_product) {
				$cart_ids[] = $cart_product['product_id'];
			}
			array_unique($cart_ids);
		}
		$products = explode(',', $this->config->get('featured_product'));		

		if (empty($setting['limit'])) {
			$setting['limit'] = 5;
		}
		
		$products = array_slice($products, 0, (int)$setting['limit']);
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if ($product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = $this->model_tool_image->resize('no_image.jpg', $setting['image_width'], $setting['image_height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}
				
				if($product_info['quantity'] < 1) {
				    $out_of_stock = $product_info['stock_status'];
				} else {
				    $out_of_stock = false;
				}
				
				if($product_info['quantity'] <= $this->config->get('left_a_bit_quantity') && $product_info['quantity'] > 0) {
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
				if((strtotime(date('Y-m-d')) >= strtotime($product_info['promo_date_start'])) && (strtotime(date('Y-m-d')) <= strtotime($product_info['promo_date_end'])) || (($product_info['promo_date_start'] == '0000-00-00') && ($product_info['promo_date_end'] == '0000-00-00'))) {
				    $promo_on = TRUE;
				} else {
				    $promo_on = FALSE;
				}
				
				$promo_top_right = $this->model_catalog_product->getPromo($product_info['product_id'],$product_info['promo_top_right']);
				if (!empty($promo_top_right['promo_text']) && $promo_on) {
				    $promo_tag_top_right = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_top_right['image'] . '\') no-repeat;background-position:top right"></span>';
				} else {
				    $promo_tag_top_right = '';
				}
					
				$promo_top_left = $this->model_catalog_product->getPromo($product_info['product_id'],$product_info['promo_top_left']);
				if (!empty($promo_top_left['promo_text']) && $promo_on) {
				    $promo_tag_top_left = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_top_left['image'] . '\') no-repeat;background-position:top left"></span>';
				} else {
				    $promo_tag_top_left = '';
				}
					
				$promo_bottom_left = $this->model_catalog_product->getPromo($product_info['product_id'],$product_info['promo_bottom_left']);
				if (!empty($promo_bottom_left['promo_text']) && $promo_on) {
				    $promo_tag_bottom_left = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_bottom_left['image'] . '\') no-repeat;background-position:bottom left"></span>';
				} else {
				    $promo_tag_bottom_left = '';
				}
					
				$promo_bottom_right = $this->model_catalog_product->getPromo($product_info['product_id'],$product_info['promo_bottom_right']);
				if (!empty($promo_bottom_right['promo_text']) && $promo_on) {
				    $promo_tag_bottom_right = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_bottom_right['image'] . '\') no-repeat;background-position:bottom right"></span>';
				} else {
				    $promo_tag_bottom_right = '';
				}
				/*code end*/
					
				$this->data['products'][] = array(
					'product_id'    => $product_info['product_id'],
					'model'         => $product_info['model'],
					'thumb'   	    => $image,
					'name'    	    => $product_info['name'],
					'in_cart'		=> in_array($product_info['product_id'], $cart_ids),
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
	                'quantity'      => $product_info['quantity'],
					'special' 	    => $special,
					'special_end'		=> $product_info['special_end'],
					'rating'        => $rating,
					'description'   => strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')),
					'short_description'   => strip_tags(html_entity_decode($product_info['short_description'], ENT_QUOTES, 'UTF-8'), '<b><i><strong>'),
					'reviews'       => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	    => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/featured.tpl';
		} else {
			$this->template = 'default/template/module/featured.tpl';
		}

		$this->render();
	}
}
?>