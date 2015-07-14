<?php 
class ControllerProductSpecial extends Controller { 	
	public function index() { 
		$this->language->load('product/special');

		$this->load->model('catalog/product');
		# start filter
		$this->load->model('catalog/filter');
		# end filter
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
			$limit = $this->config->get('config_catalog_limit');
		}
		
		if(isset($this->request->get['latest'])) {
			$latest = (bool)$this->request->get['latest'];
		} else {
			$latest = false;
		}
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addLink($this->url->link('product/special'), 'canonical');
		$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/special', $url),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_quantity'] = $this->language->get('text_quantity');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_points'] = $this->language->get('text_points');
		$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_grid'] = $this->language->get('text_grid');		
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');

		$this->data['button_cart'] = $this->language->get('button_cart');	
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
		$this->data['button_compare'] = $this->language->get('button_compare');

		$this->data['compare'] = $this->url->link('product/compare');

		$this->data['products'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'latest' => $latest,
		);

		$product_total = $this->model_catalog_product->getTotalProductSpecials($data);

		$results = $this->model_catalog_product->getProductSpecials($data);
		# start filter products attributes
		$filter_module = $this->config->get('filter_module');
		
		if (isset($filter_module[0])) {
		    $filter_settings = $filter_module[0];
		
		    $show_type = $filter_settings['pco_show_type'];
		    $show_limit = (int)$filter_settings['pco_show_limit'];
		
		    $products_id = array();
		    foreach ($results as $result) $products_id[] = (int)$result['product_id'];
		
		    $product_options = array();
		
		    if ($products_id) {
		        $filter_options = $this->model_catalog_filter->getOptionsByProductsId($products_id);
		
		        foreach ($filter_options as $product_id => $options) {
		            array_splice($options, $show_limit);
		
		            foreach($options as $option) {
		                if ($show_type == 'inline') {
		                    $product_options[$product_id][] = $option['name'] . ': <b>' . $option['values'] . '</b>';
		                } else {
		                    $product_options[$product_id][] = array(
		                                    'name'   => $option['name'],
		                                    'values' => $option['values']
		                    );
		                }
		            }
		        }
		    }
		}
//		$results = $this->model_catalog_product->getProducts($data);
		$cart_products = $this->cart->getProducts();
		$cart_ids = array();
		if(is_array($cart_products) && !empty($cart_products)) {
			foreach ($cart_products as $cart_product) {
				$cart_ids[] = $cart_product['product_id'];
			}
			array_unique($cart_ids);
		}
		# end filter products attributes

		foreach ($results as $result) {
            // Prevent processing old products
            if(empty($result)) {
                continue;
            }
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
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
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

			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
			} else {
				$tax = false;
			}				

			if ($this->config->get('config_review_status')) {
				$rating = (int)$result['rating'];
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
			
			# start filter products attributes
			if ($show_type == 'inline') {
			    if (isset($product_options[$result['product_id']])) {
			        $attributes = implode(' / ', $product_options[$result['product_id']]);
			    } else {
			        $attributes = '';
			    }
			} else {
			    if (isset($product_options[$result['product_id']])) {
			        $attributes = $product_options[$result['product_id']];
			    } else {
			        $attributes = array();
			    }
			}
            
        // Countdown
		$special_end = false;
		if($result['special_end'] && $result['special_end'] >= date('Y-m-d H:i:s') && $result['special_start'] <= date('Y-m-d H:i:s')) {
			$time = new DateTime($result['special_end']);
            //var_dump($result['special_end']);die();
			$finish = $time->getTimestamp() - time();
			$special_end = $finish;
		}
            
			# end filter products attributes
			$this->data['products'][] = array(
				'product_id'    => $result['product_id'],
				'thumb'         => $image,
				'name'          => $result['name'],
				'quantity'      => $result['quantity'],
				'model'         => $result['model'],
				'category'      => $result['category'],
				'out_of_stock'  => $out_of_stock,
				'left_of_stock' => $left_of_stock,
				'in_stock'      => $in_stock,
				'in_cart'		=> in_array($result['product_id'], $cart_ids),
				/*code start*/
				'promo_tag_top_right'		=> $promo_tag_top_right,
				'promo_tag_top_left'		=> $promo_tag_top_left,
				'promo_tag_bottom_left'		=> $promo_tag_bottom_left,
				'promo_tag_bottom_right'	=> $promo_tag_bottom_right,
				/*code end*/
				'description'   => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
			    'short_description' => strip_tags(html_entity_decode($result['short_description'], ENT_QUOTES, 'UTF-8'), '<b><i><strong>'),
				# start filter products attributes
				'attributes'    => $attributes,
				# end filter products attributes
				'price'         => $price,
				'special'       => $special,
				'special_end'   => $special_end,
				'tax'           => $tax,
				'rating'        => $result['rating'],
				'reviews'       => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'          => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
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
			'href'  => $this->url->link('product/special', 'sort=p.sort_order&order=ASC' . $url)
		);

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('product/special', 'sort=pd.name&order=ASC' . $url)
		); 

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('product/special', 'sort=pd.name&order=DESC' . $url)
		);  

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'ps.price-ASC',
			'href'  => $this->url->link('product/special', 'sort=ps.price&order=ASC' . $url)
		); 

		$this->data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'ps.price-DESC',
			'href'  => $this->url->link('product/special', 'sort=ps.price&order=DESC' . $url)
		); 

		if ($this->config->get('config_review_status')) {	
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('product/special', 'sort=rating&order=DESC' . $url)
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('product/special', 'sort=rating&order=ASC' . $url)
			);
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->data['limits'] = array();

		$limits = array_unique(array($this->config->get('config_catalog_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach($limits as $value){
			$this->data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('product/special', $url . '&limit=' . $value)
			);
		}

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
		$pagination->url = $this->url->link('product/special', $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/special.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/special.tpl';
		} else {
			$this->template = 'default/template/product/special.tpl';
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