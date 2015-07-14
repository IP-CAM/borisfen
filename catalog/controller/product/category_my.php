<?php 
class ControllerProductCategory extends Controller {  
	public function index() {
		$this->language->load('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
		# start filter
		$this->load->model('catalog/filter');

		# end filter
		$this->load->model('tool/image'); 
		
		$this->document->addScript(SCRIPT_FOLDER . 'pagi-more.js');
		
		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'rating';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
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
		
        # Filter start
        if (isset($this->request->get['filter_params'])) {
          $filter_params = $this->request->get['filter_params'];
        } else {
          $filter_params = null;
        }
        # Filter end

        /* Additional filter by category */
        if (isset($this->request->get['filter_id'])) {
            $filter_id = (int)$this->request->get['filter_id'];
        } else {
            $filter_id = null;
        }
        /* Additional filter by category */
        
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		if (isset($this->request->get['path'])) {
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

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path . $url),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);
		if ($category_info) {
		    
			($category_info['custom_title'] == '')?$this->document->setTitle($category_info['name']):$this->document->setTitle($category_info['custom_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/category', $this->request->get['path']), 'canonical');
			if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
			$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
			}

			if($category_info['custom_h1']) {
			    $this->data['heading_title'] = $category_info['custom_h1'];
			} else {
			    $this->data['heading_title'] = $category_info['name'];
			}
			

			$this->data['text_refine'] = $this->language->get('text_refine');
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
			$this->data['button_continue'] = $this->language->get('button_continue');

			// Set the last category breadcrumb		
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
				'text'      => $category_info['name'],
				'href'      => $this->url->link('product/category', 'path=' . $this->request->get['path']),
				'separator' => $this->language->get('text_separator')
			);

			if ($category_info['image'] && file_exists(DIR_IMAGE . $category_info['image'])) {
				$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$this->data['thumb'] = '';
			}

			$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			
			$this->data['short_description'] = html_entity_decode($category_info['short_description'], ENT_QUOTES, 'UTF-8');
			$this->data['additional_description'] = html_entity_decode($category_info['additional_description'], ENT_QUOTES, 'UTF-8');
			
			$this->data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}	

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			# Filter start
			if (isset($this->request->get['filter_params'])) {
			    $url .= '&filter_params=' . $this->request->get['filter_params'];
			}
			# Filter end
			# Filter start
			if (isset($this->request->get['filter_id'])) {
			    $url .= '&filter_id=' . $this->request->get['filter_id'];
			}
			# Filter end
			$this->data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);
				
				
                if($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                    $category_image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
                } else {
                    $category_image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
                }
                
				$product_total = $this->model_catalog_product->getTotalProducts($data);				

				$this->data['categories'][] = array(
					'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url),
	                'image' => $category_image,
	                'short_description' => htmlspecialchars_decode($result['short_description'])
				);
			}

			$this->data['products'] = array();

			if(!$category_info['is_information']) {
    			$data = array(
    				'filter_category_id' => $category_id,
    				# Filter start
    				'filter_params'      => $filter_params,
    				# Filter end
                    'filter_id'          => $filter_id,
    				'filter_filter'      => $filter, 
    				'sort'               => $sort,
    				'filter_sub_category' => true,
    				'order'              => $order,
    				'start'              => ($page - 1) * $limit,
    				'limit'              => $limit
    			);
    
    			$product_total = $this->model_catalog_product->getTotalProducts($data); 
    			
    			/* In cart */
    			$cart_products = $this->cart->getProducts();
    			$cart_ids = array();
    			if(is_array($cart_products) && !empty($cart_products)) {
    				foreach ($cart_products as $cart_product) {
    					$cart_ids[] = $cart_product['product_id'];
    				}
    				array_unique($cart_ids);
    			}
    			
    			/* In cart */
    			$results = $this->model_catalog_product->getProducts($data);
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
    			# end filter products attributes
    			foreach ($results as $result) {

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
    				# end filter products attributes

                    
		// Countdown
		$special_end = false;
		if($result['special_end'] && $result['special_end'] >= date('Y-m-d H:i:s') && $result['special_start'] <= date('Y-m-d H:i:s')) {
			$time = new DateTime($result['special_end']);
            //var_dump($result['special_end']);die();
			$finish = $time->getTimestamp() - time();
			$special_end = $finish;
		}

    				$this->data['products'][] = array(
    					'product_id'  => $result['product_id'],
    					'thumb'       => $image,
    					'name'        => $result['name'],
    					'quantity'    => $result['quantity'],
    					'model'       => $result['model'],
    					'category'    => $result['category'],
    					'manufacturer'    => $result['manufacturer'],
    					'in_cart'		=> in_array($result['product_id'], $cart_ids),
    					'out_of_stock'  => $out_of_stock,
    					'left_of_stock' => $left_of_stock,
    					'in_stock'      => $in_stock,
    					'description' => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
    					'short_description' => strip_tags(html_entity_decode($result['short_description'], ENT_QUOTES, 'UTF-8'), '<b><i><strong>'),
    					# start filter products attributes
    					'attributes'  => $attributes,
    					# end filter products attributes
    					'price'       => $price,
    					/*code start*/
    					'promo_tag_top_right'		=> $promo_tag_top_right,
    					'promo_tag_top_left'		=> $promo_tag_top_left,
    					'promo_tag_bottom_left'		=> $promo_tag_bottom_left,
    					'promo_tag_bottom_right'	=> $promo_tag_bottom_right,
    					/*code end*/
    					'special'     => $special,
						'special_end' => $special_end,
    					'tax'         => $tax,
    					'rating'      => $result['rating'],
    					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
    					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
    				);
    			}
    			
    			$url = '';
    
    			if (isset($this->request->get['filter'])) {
    				$url .= '&filter=' . $this->request->get['filter'];
    			}
    
    			if (isset($this->request->get['limit'])) {
    				$url .= '&limit=' . $this->request->get['limit'];
    			}
    			# start filter
    			if (isset($this->request->get['params'])) {
    			    $url .= '&params=' . $this->request->get['params'];
    			}
    			# end filter
    			$this->data['sorts'] = array();
    
    			$this->data['sorts'][] = array(
    				'text'  => $this->language->get('text_price_asc'),
    				'value' => 'p.price-ASC',
    				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
    			); 
    
    			$this->data['sorts'][] = array(
    				'text'  => $this->language->get('text_price_desc'),
    				'value' => 'p.price-DESC',
    				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
    			);

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_asc'),
                    'value' => 'pd.name-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_desc'),
                    'value' => 'pd.name-DESC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
                );

    			if ($this->config->get('config_review_status')) {
    				$this->data['sorts'][] = array(
    					'text'  => $this->language->get('text_rating_desc'),
    					'value' => 'rating-DESC',
    					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
    				); 
    
    				$this->data['sorts'][] = array(
    					'text'  => $this->language->get('text_rating_asc'),
    					'value' => 'rating-ASC',
    					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
    				);
    			}
    
    			$url = '';
    
    			if (isset($this->request->get['filter'])) {
    				$url .= '&filter=' . $this->request->get['filter'];
    			}
    
    			if (isset($this->request->get['sort'])) {
    				$url .= '&sort=' . $this->request->get['sort'];
    			}	
    			
    			if (isset($this->request->get['order'])) {
    				$url .= '&order=' . $this->request->get['order'];
    			}
    			# start filter
    			if (isset($this->request->get['params'])) {
    			    $url .= '&params=' . $this->request->get['params'];
    			}
    			# end filter
    			$this->data['limits'] = array();
    
    			$limits = array_unique(array($this->config->get('config_catalog_limit'), 25, 50, 75, 100));
    
    			sort($limits);
    
    			foreach($limits as $value){
    				$this->data['limits'][] = array(
    					'text'  => $value,
    					'value' => $value,
    					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
    				);
    			}
    
    			$url = '';
    
    			if (isset($this->request->get['filter'])) {
    				$url .= '&filter=' . $this->request->get['filter'];
    			}
    
    			if (isset($this->request->get['sort'])) {
    				$url .= '&sort=' . $this->request->get['sort'];
    			}	
    
    			if (isset($this->request->get['order'])) {
    				$url .= '&order=' . $this->request->get['order'];
    			}
    
    			if (isset($this->request->get['limit'])) {
    				$url .= '&limit=' . $this->request->get['limit'];
    			}
    			# start filter
    			if (isset($this->request->get['params'])) {
    			    $url .= '&params=' . $this->request->get['params'];
    			}
    			# end filter
    			$pagination = new Pagination();
    			$pagination->total = $product_total;
    			$pagination->page = $page;
    			$pagination->limit = $limit;
    			$pagination->text = $this->language->get('text_pagination');
    			if (isset($this->request->get['filter_id'])) {
    				$url .= '&filter_id=' . $this->request->get['filter_id'];
    			}
    			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
    
    			$this->data['pagination'] = $pagination->render();
    			$this->data['max_page']   = $pagination->getLast();
    			
    			$this->data['sort'] = $sort;
    			$this->data['order'] = $order;
    			$this->data['limit'] = $limit;
			}
			$this->data['continue'] = $this->url->link('common/home');
			
			/* layout patch - choose template by path */
			$this->load->model ( 'design/layout' );
			if (isset ( $this->request->get ['route'] )) {
				$route = ( string ) $this->request->get ['route'];
			} else {
				$route = 'common/home';
			}
			$layout_template = $this->model_design_layout->getLayoutTemplate($route);
			$isLayoutRoute = true;
			if(!$layout_template){
				$layout_template = 'category';
				$isLayoutRoute = false;
			}
			
			// get general layout template
			if(!$isLayoutRoute){
				$layout_id = $this->model_catalog_category->getCategoryLayoutId($category_id);
				if($layout_id){
					$tmp_layout_template = $this->model_design_layout->getGeneralLayoutTemplate($layout_id);
					if($tmp_layout_template)
						$layout_template = $tmp_layout_template;
				}
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/' . $layout_template . '.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/' . $layout_template . '.tpl';
			} else {
				$this->template = 'default/template/product/' . $layout_template . '.tpl';
			}
			
			$this->children = array(
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
            if(!$category_info['is_information']) {
                $this->children[] = 'common/column_left';
                $this->children[] = 'common/column_right';
            }
            $this->data['column_left'] = '';
            $this->data['column_right'] = '';
            
			$this->response->setOutput($this->render());										
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

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
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/category', $url),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($this->language->get('text_error'));

			$this->data['heading_title'] = $this->language->get('text_error');

			$this->data['text_error'] = $this->language->get('text_error');

			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
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
}
?>