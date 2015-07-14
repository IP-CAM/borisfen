<?php 
class ControllerProductSearch extends Controller { 	
	public function index() {
		$this->language->load('product/search');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
		# start filter
		$this->load->model('catalog/filter');
		# end filter
		$this->load->model('tool/image'); 

		if (isset($this->request->get['search'])) {
			$search = $this->request->get['search'];
		} else {
			$search = '';
		} 

		if (isset($this->request->get['tag'])) {
			$tag = $this->request->get['tag'];
		} elseif (isset($this->request->get['search'])) {
			$tag = $this->request->get['search'];
		} else {
			$tag = '';
		} 

		if (isset($this->request->get['description'])) {
			$description = $this->request->get['description'];
		} else {
			$description = '';
		} 

		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		} 

		if (isset($this->request->get['sub_category'])) {
			$sub_category = $this->request->get['sub_category'];
		} else {
			$sub_category = '';
		} 

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

		if (isset($this->request->get['search'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}

		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
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
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/search', $url),
			'separator' => $this->language->get('text_separator')
		);

		if (isset($this->request->get['search'])) {
			$this->data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
		}

		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_critea'] = $this->language->get('text_critea');
		$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_keyword'] = $this->language->get('text_keyword');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_sub_category'] = $this->language->get('text_sub_category');
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

		$this->data['entry_search'] = $this->language->get('entry_search');
		$this->data['entry_description'] = $this->language->get('entry_description');

		$this->data['button_search'] = $this->language->get('button_search');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
		$this->data['button_compare'] = $this->language->get('button_compare');

		$this->data['compare'] = $this->url->link('product/compare');

		$this->load->model('catalog/category');

		// 3 Level Category Search
		$this->data['categories'] = array();

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}

				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],	
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);					
			}

			$this->data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}

		$this->data['products'] = array();

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$data = array(
				'filter_name'         => $search, 
				'filter_tag'          => $tag, 
				'filter_description'  => $description,
				'filter_category_id'  => $category_id, 
				'filter_sub_category' => $sub_category, 
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProducts($data);

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
			$results = $this->model_catalog_product->getProducts($data);
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
					'product_id'    => $result['product_id'],
					'thumb'         => $image,
					'name'          => $result['name'],
					'quantity'      => $result['quantity'],
					'model'         => $result['model'],
					'category'      => $result['category'],
					'manufacturer'    => $result['manufacturer'],
					'in_cart'		=> in_array($result['product_id'], $cart_ids),
					'out_of_stock'  => $out_of_stock,
					'left_of_stock' => $left_of_stock,
					'in_stock'      => $in_stock,
					'description'   => strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')),
				    'short_description' => strip_tags(html_entity_decode($result['short_description'], ENT_QUOTES, 'UTF-8'), '<b><i><strong>'),
					# start filter products attributes
					'attributes'    => $attributes,
					# end filter products attributes
    				/*code start*/
    				'promo_tag_top_right'		=> $promo_tag_top_right,
    				'promo_tag_top_left'		=> $promo_tag_top_left,
    				'promo_tag_bottom_left'		=> $promo_tag_bottom_left,
    				'promo_tag_bottom_right'	=> $promo_tag_bottom_right,
    				/*code end*/
					'price'         => $price,
					'special'       => $special,
					'special_end'	=> $special_end,
					'tax'           => $tax,
					'rating'        => $result['rating'],
					'reviews'       => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'          => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['sorts'] = array();

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.sort_order&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/search', 'sort=pd.name&order=ASC' . $url)
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/search', 'sort=pd.name&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.price&order=ASC' . $url)
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/search', 'sort=p.price&order=DESC' . $url)
			); 

			if ($this->config->get('config_review_status')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/search', 'sort=rating&order=DESC' . $url)
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/search', 'sort=rating&order=ASC' . $url)
				);
			}

			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

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
					'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('product/search', $url . '&page={page}');

			$this->data['pagination'] = $pagination->render();
		}	

		$this->data['search'] = $search;
		$this->data['description'] = $description;
		$this->data['category_id'] = $category_id;
		$this->data['sub_category'] = $sub_category;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/search.tpl';
		} else {
			$this->template = 'default/template/product/search.tpl';
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
	
	public function ajax()
	{
	    // Contains results
	    $data = array();
	    if( isset($this->request->get['keyword']) ) {
	        // Parse all keywords to lowercase
	        $keywords = $this->db->escape( $this->request->get['keyword'] );
	        // Perform search only if we have some keywords
	        if( strlen($keywords) >= 1 ) {
	            $parts = explode( ' ', $keywords );
	            $add = '';
	            // Generating search
	            foreach( $parts as $part ) {
	                $add .= ' AND (LOWER(pd.name) LIKE "%' . $this->db->escape($part) . '%"';
	                $add .= ' OR LOWER(p.model) LIKE "%' . $this->db->escape($part) . '%")';
	            }
	            $add = substr( $add, 4 );
	            $sql  = 'SELECT pd.product_id, pd.name, p.model, p.image, p.price, p.tax_class_id FROM ' . DB_PREFIX . 'product_description AS pd ';
	            $sql .= 'LEFT JOIN ' . DB_PREFIX . 'product AS p ON p.product_id = pd.product_id ';
	            $sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_to_store AS p2s ON p2s.product_id = pd.product_id ';
	            $sql .= 'WHERE ' . $add . ' AND p.status = 1 ';
	            $sql .= 'AND pd.language_id = ' . (int)$this->config->get('config_language_id');
	            $sql .= ' AND p2s.store_id =  ' . (int)$this->config->get('config_store_id');
	            $sql .= ' ORDER BY p.sort_order ASC, LOWER(pd.name) ASC, LOWER(p.model) ASC';
	            $sql .= ' LIMIT 15';
	            $res = $this->db->query( $sql );
	            if( $res ) {
	                $data = ( isset($res->rows) ) ? $res->rows : $res->row;
	
	                // For the seo url stuff
	                $basehref = 'product/product&keyword=' . $this->request->get['keyword'] . '&product_id=';
	            	$this->load->model('tool/image');
	                foreach( $data as $key => $values ) {
	                    $data[$key] = array(
                            //'name' => htmlspecialchars_decode($values['name'] . ' (' . $values['model'] . ')', ENT_QUOTES),
							'name' => htmlspecialchars_decode($values['name'], ENT_QUOTES),
                            'href' => $this->url->link($basehref . $values['product_id']),
                    		'img'  => $this->model_tool_image->resize((($values['image'] && file_exists(DIR_IMAGE . $values['image']))? $values['image'] : 'no_image.jpg'), 64, 64),
                    		'price' => $this->currency->format($this->tax->calculate($values['price'], $values['tax_class_id'], $this->config->get('config_tax')), '', '', true, false),
	                    );
	                }
	            }
	        }
	    }
	    echo json_encode( $data );
	}
}
?>