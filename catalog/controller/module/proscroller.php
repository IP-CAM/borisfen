<?php
class ControllerModuleproscroller extends Controller {
	protected $path = array ();
	protected function index($setting) {
		static $module = 0;
		$this->language->load ( 'module/proscroller' );
		$this->load->model ( 'catalog/category' );
		
		$this->document->addScript ( 'catalog/view/javascript/jquery/jquery.jcarousel.min.js' );
// 		$this->document->addStyle ( 'catalog/view/theme/default/stylesheet/proscroller.css' );
// 		if (file_exists ( 'catalog/view/theme/' . $this->config->get ( 'config_template' ) . '/stylesheet/carousel.css' )) {
// 			$this->document->addStyle ( 'catalog/view/theme/' . $this->config->get ( 'config_template' ) . '/stylesheet/carousel.css' );
// 		} else {
// 			$this->document->addStyle ( 'catalog/view/theme/default/stylesheet/carousel.css' );
// 		}
		
		if ($setting ['title']) {
			$this->data ['heading_title'] = $setting ['title'] [$this->config->get ( 'config_language_id' )];
		} else {
			$category = $this->model_catalog_category->getCategory ( $setting ['category_id'] );
			if (isset ( $category ['name'] )) {
				$this->data ['heading_title'] = $category ['name'];
			} else {
				$this->data ['heading_title'] = $this->language->get ( 'heading_title' );
			}
		}
		
		if (($setting ['position'] == 'column_left') || ($setting ['position'] == 'column_right')) {
			$this->data ['position'] = 'column';
		}
		
		$this->data ['button_cart'] = $this->language->get ( 'button_cart' );
		$this->data ['visible'] = $setting ['visible'];
		$this->data ['scroll'] = $setting ['scroll'];
		$this->data ['sort'] = $setting ['sort'];
		$this->data ['type'] = "wrap: 'last'";
		if ($setting ['autoscroll'] > 0) {
			$this->data ['autoscroll'] = $setting ['autoscroll'];
		} else {
			$this->data ['autoscroll'] = '0';
		}
		if ($setting ['animationspeed'] > 0) {
			$this->data ['animationspeed'] = $setting ['animationspeed'];
		} else {
			$this->data ['animationspeed'] = '1000';
		}
		$this->data ['hoverpause'] = $setting ['hoverpause'];
		$this->data ['disableauto'] = $setting ['disableauto'];
		
		$this->data ['show_title'] = $setting ['show_title'];
		$this->data ['show_price'] = $setting ['show_price'];
		$this->data ['show_rate'] = $setting ['show_rate'];
		$this->data ['show_cart'] = $setting ['show_cart'];
		
		$this->load->model ( 'module/proscroller' );
		
		$this->load->model ( 'tool/image' );
		
		if (isset ( $this->request->get ['path'] )) {
			$this->path = explode ( '_', $this->request->get ['path'] );
			
			$this->category_id = end ( $this->path );
		}
		$url = '';
		
		$this->data ['products'] = array ();
		
		if ($setting ['category_id'] == 'featured') {
			$this->data ['products'] = $this->getfeaturedproducts ( $setting );
		} else {
			$this->data ['products'] = $this->getcategoryproducts ( $setting );
		}
		
		$this->data ['module'] = $module ++;
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/module/proscroller.tpl' )) {
			$this->template = $this->config->get ( 'config_template' ) . '/template/module/proscroller.tpl';
		} else {
			$this->template = 'default/template/module/proscroller.tpl';
		}
		
		$this->render ();
	}
	public function getcategoryproducts($setting) {
		$data = array (
				'filter_category_id' => $setting ['category_id'],
				'filter_sub_category' => true,
				'sort' => $setting ['sort'],
				'order' => 'DESC',
				'start' => '0',
				'limit' => $setting ['count'] 
		);
		$cart_products = $this->cart->getProducts();
		$cart_ids = array();
		if(is_array($cart_products) && !empty($cart_products)) {
			foreach ($cart_products as $cart_product) {
				$cart_ids[] = $cart_product['product_id'];
			}	
			array_unique($cart_ids);
		}
		$products = $this->model_module_proscroller->getProducts ( $data );
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
	    $this->data['button_compare']  = $this->language->get('button_compare');
		foreach ( $products as $product ) {
			if ($product ['image'] && file_exists(DIR_IMAGE . $product['image'])) {
				$image = $product ['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
				$price = $this->currency->format ( $this->tax->calculate ( $product ['price'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
			} else {
				$price = false;
			}
			
			if (( float ) $product ['special']) {
				$special = $this->currency->format ( $this->tax->calculate ( $product ['special'], $product ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
			} else {
				$special = false;
			}

			if($product['quantity'] < 1) {
			    $out_of_stock = $product['stock_status'];
			} else {
			    $out_of_stock = false;
			}
			
			if($product['quantity'] <= $this->config->get('left_a_bit_quantity') && $product['quantity'] > 0) {
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
			if((strtotime(date('Y-m-d')) >= strtotime($product['promo_date_start'])) && (strtotime(date('Y-m-d')) <= strtotime($product['promo_date_end'])) || (($product['promo_date_start'] == '0000-00-00') && ($product['promo_date_end'] == '0000-00-00'))) {
			    $promo_on = TRUE;
			} else {
			    $promo_on = FALSE;
			}
				
			$promo_top_right = $this->model_catalog_product->getPromo($product['product_id'],$product['promo_top_right']);
			if (!empty($promo_top_right['promo_text']) && $promo_on) {
			    $promo_tag_top_right = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_top_right['image'] . '\') no-repeat;background-position:top right"></span>';
			} else {
			    $promo_tag_top_right = '';
			}
			
			$promo_top_left = $this->model_catalog_product->getPromo($product['product_id'],$product['promo_top_left']);
			if (!empty($promo_top_left['promo_text']) && $promo_on) {
			    $promo_tag_top_left = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_top_left['image'] . '\') no-repeat;background-position:top left"></span>';
			} else {
			    $promo_tag_top_left = '';
			}
			
			$promo_bottom_left = $this->model_catalog_product->getPromo($product['product_id'],$product['promo_bottom_left']);
			if (!empty($promo_bottom_left['promo_text']) && $promo_on) {
			    $promo_tag_bottom_left = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_bottom_left['image'] . '\') no-repeat;background-position:bottom left"></span>';
			} else {
			    $promo_tag_bottom_left = '';
			}
			
			$promo_bottom_right = $this->model_catalog_product->getPromo($product['product_id'],$product['promo_bottom_right']);
			if (!empty($promo_bottom_right['promo_text']) && $promo_on) {
			    $promo_tag_bottom_right = '<span class="promotags" style="width:100%;height:100%;background: url(\'' . 'image/' . $promo_bottom_right['image'] . '\') no-repeat;background-position:bottom right"></span>';
			} else {
			    $promo_tag_bottom_right = '';
			}
			/*code end*/
			
			$options = $this->model_catalog_product->getProductOptions ( $product ['product_id'] );
			
			if ($this->config->get ( 'config_review_status' )) {
				$rating = ( int ) $product ['rating'];
			} else {
				$rating = false;
			}
			$this->data ['products'] [] = array (
					'id'            => $product['product_id'],
					'product_id'    => $product['product_id'],
					'name'          => $product['name'],
					'model'         => $product['model'],
					'qty'           => $product['quantity'],
					'quantity'      => $product['quantity'],
					'in_cart'		=> in_array($product['product_id'], $cart_ids),
                    'out_of_stock'  => $out_of_stock,
                    'left_of_stock' => $left_of_stock,
                    'in_stock'      => $in_stock,
	                /*code start*/
	                'promo_tag_top_right'		=> $promo_tag_top_right,
	                'promo_tag_top_left'		=> $promo_tag_top_left,
	                'promo_tag_bottom_left'		=> $promo_tag_bottom_left,
	                'promo_tag_bottom_right'	=> $promo_tag_bottom_right,
	                /*code end*/
                    'description'   => strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')),
                    'short_description' => strip_tags(html_entity_decode($product['short_description'], ENT_QUOTES, 'UTF-8'), '<b><i><strong>'),
					'rating'        => $rating,
					'reviews'       => sprintf($this->language->get('text_reviews'), (int)$product['reviews']),
					'thumb'         => $this->model_tool_image->resize($image, $setting['image_width'], $setting['image_height']),
					'price'         => $price,
					'special'       => $special,
					'special_end'	=> $product['special_end'],
					'href'          => $this->url->link('product/product', 'product_id=' . $product['product_id']) 
			);
		}
		return $this->data ['products'];
	}
	public function getfeaturedproducts($setting) {
	    $this->data['button_wishlist'] = $this->language->get('button_wishlist');
	    $this->data['button_compare']  = $this->language->get('button_compare');
		$products = explode ( ',', $setting ['featured'] );
		
		if (empty ( $setting ['count'] )) {
			$setting ['count'] = 5;
		}
		$cart_products = $this->cart->getProducts();
		$cart_ids = array();
		if(is_array($cart_products) && !empty($cart_products)) {
			foreach ($cart_products as $cart_product) {
				$cart_ids[] = $cart_product['product_id'];
			}
			array_unique($cart_ids);
		}
		$products = array_slice ( $products, 0, ( int ) $setting ['count'] );
		
		foreach ( $products as $product_id ) {
			$product_info = $this->model_catalog_product->getProduct ( $product_id );
			
			if ($product_info) {
				if ($product_info ['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
					$image = $this->model_tool_image->resize ( $product_info ['image'], $setting ['image_width'], $setting ['image_height'] );
				} else {
					$image = false;
				}
				
				if (($this->config->get ( 'config_customer_price' ) && $this->customer->isLogged ()) || ! $this->config->get ( 'config_customer_price' )) {
					$price = $this->currency->format ( $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$price = false;
				}
				
				if (( float ) $product_info ['special']) {
					$special = $this->currency->format ( $this->tax->calculate ( $product_info ['special'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) );
				} else {
					$special = false;
				}
				
				if ($this->config->get ( 'config_review_status' )) {
					$rating = $product_info ['rating'];
				} else {
					$rating = false;
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
				
				$this->data ['products'] [] = array (
						'id'            => $product_info['product_id'],
						'product_id'    => $product_info['product_id'],
						'quantity'      => $product_info['quantity'],
						'in_cart'		=> in_array($product_info['product_id'], $cart_ids),
						'thumb'         => $image,
						'name'          => $product_info['name'],
		                'out_of_stock'  => $out_of_stock,
		                'left_of_stock' => $left_of_stock,
		                'in_stock'      => $in_stock,
		                /*code start*/
		                'promo_tag_top_right'		=> $promo_tag_top_right,
		                'promo_tag_top_left'		=> $promo_tag_top_left,
		                'promo_tag_bottom_left'		=> $promo_tag_bottom_left,
		                'promo_tag_bottom_right'	=> $promo_tag_bottom_right,
		                /*code end*/
						'price'         => $price,
		                'description'   => strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')),
		                'short_description' => strip_tags(html_entity_decode($product_info['short_description'], ENT_QUOTES, 'UTF-8'), '<b><i><strong>'),
						'special'       => $special,
						'special_end'	=> $product_info['special_end'],
						'rating'        => $rating,
						'reviews'       => sprintf ( $this->language->get ( 'text_reviews' ), ( int ) $product_info ['reviews'] ),
						'href'          => $this->url->link ( 'product/product', 'product_id=' . $product_info ['product_id'] ) 
				);
			}
		}
		return $this->data ['products'];
	}
}
?>