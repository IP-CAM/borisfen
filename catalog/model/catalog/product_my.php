<?php
class ModelCatalogProduct extends Model {
	
	private $discount_settings = array();
	
	public function __construct($registry){
		parent::__construct($registry);
		$query = $this->db->query("SELECT value, `key` FROM " . DB_PREFIX . "setting WHERE `group` = 'discount' AND (`key` = 'manufacturer' OR `key` = 'setting' OR `key` = 'category' OR `key` = 'customer_group' OR `key` = 'total')");
		if($query->num_rows){
			foreach($query->rows as $row){
				$this->discount_settings[$row['key']] = unserialize($row['value']);
			}
		}
	}
	
	
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getProduct($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$is_wholesale = false;
		if($customer_group_id == $this->config->get('wholesale_group_id')){
			$is_wholesale = true;
		}

        $sql = "SELECT DISTINCT *, 
        		pd.name AS name, 
        		p.image, m.name AS manufacturer, 
        		
        		(SELECT price FROM " . DB_PREFIX . "product_discount pd2 
        				WHERE pd2.product_id = p.product_id 
	        			AND pd2.customer_group_id = '" . (int)$customer_group_id . "' 
	        			AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) 
	        			AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) 
	        				ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) 
	        	AS discount, 
	        						
        		(SELECT price FROM " . DB_PREFIX . "product_special ps 
        				WHERE ps.product_id = p.product_id 
        				AND ps.customer_group_id = '" . (int)$customer_group_id . "' 
        				AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) 
        				AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) 
        					ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) 
        		AS special, 
        						
        		(SELECT date_start FROM " . DB_PREFIX . "product_special ps 
					WHERE ps.product_id = p.product_id
        			AND ps.customer_group_id = '" . (int)$customer_group_id . "' 
					AND (
	        				(ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) 
	        				AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW()
						)
					) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) 
        		AS special_start, 
        					
        		(SELECT date_end FROM " . DB_PREFIX . "product_special ps 
					WHERE ps.product_id = p.product_id 
					AND ps.customer_group_id = '" . (int)$customer_group_id . "' 
					AND (
							(ps.date_start = '0000-00-00 00:00:00' OR ps.date_start < NOW()) 
							AND (ps.date_end = '0000-00-00 00:00:00' OR ps.date_end > NOW())
					) 
					ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) 
				AS special_end,
        							
        		(SELECT points FROM " . DB_PREFIX . "product_reward pr 
        				WHERE pr.product_id = p.product_id 
        				AND customer_group_id = '" . (int)$customer_group_id . "') 
        		AS reward, 
        							
        		(SELECT ss.name FROM " . DB_PREFIX . "stock_status ss 
        				WHERE ss.stock_status_id = p.stock_status_id 
        				AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') 
        		AS stock_status, 
        						
        		(SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd 
        				WHERE p.weight_class_id = wcd.weight_class_id 
        				AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') 
        		AS weight_class, 
        						
        		(SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd 
        				WHERE p.length_class_id = lcd.length_class_id 
        				AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') 
        		AS length_class, 
        						
        		(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, 
        		(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, 
        		p.sort_order 
        				FROM " . DB_PREFIX . "product p 
        						LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
        						LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
        						LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
        								WHERE p.product_id = '" . (int)$product_id . "'
        									AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        									AND p.status = '1' 
        									AND p.date_available <= NOW() 
        									AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
        
        $query = $this->db->query($sql);
        
        if(!isset($query->row['product_id'])){
        	return false;
        }

		$query->row['price'] = ($is_wholesale && ($query->row['whole_sale_price'] > 0)) ? $query->row['whole_sale_price'] : $query->row['price'];

		/* MULTI DISCOUNT */
		$_price = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
		$discount = $this->getMultiDiscountPrice($query->row['product_id'], $_price,$query->row['special'], $query->row['manufacturer_id']);
		if($discount){
			$price = $discount['price'];
			$special = $discount['special'];
		} else {
			$price = $query->row['price'];
			$special = $query->row['special'];
		}
		/* MULTI DISCOUNT */

		// Countdown
		$special_end = 0;
		if($this->config->get('config_use_countdown')){
			if($query->row['special_end'] && $query->row['special_end'] >= date('Y-m-d H:i:s') && $query->row['special_start'] <= date('Y-m-d H:i:s')) {
				$time = new DateTime($query->row['special_end']);
				$special_end = $time->getTimestamp() - time();
			}
		}
		
		if ($query->num_rows) {
			return array(
				'product_id'         => $query->row['product_id'],
				'name'               => $query->row['name'],
				'description'        => $query->row['description'],
				'short_description'  => $query->row['short_description'],
				'meta_description'   => $query->row['meta_description'],
				'meta_keyword'       => $query->row['meta_keyword'], 
				'video'				 => html_entity_decode($query->row['video']),
                'custom_alt'         => $query->row['custom_alt'],
                'custom_h1'          => $query->row['custom_h1'], 
                'custom_title'       => $query->row['custom_title'],
				'tag'                => $query->row['tag'],
				'model'              => $query->row['model'],
				'sku'                => $query->row['sku'],
				'upc'                => $query->row['upc'],
				'ean'                => $query->row['ean'],
				'jan'                => $query->row['jan'],
				'isbn'               => $query->row['isbn'],
				'mpn'                => $query->row['mpn'],
				'location'           => $query->row['location'],
				'quantity'           => $query->row['quantity'],
				'stock_status'       => $query->row['stock_status'],
				'image'              => $query->row['image'],
				'manufacturer_id'    => $query->row['manufacturer_id'],
				'manufacturer'       => $query->row['manufacturer'],
				'price'				 => $price,
				'special'            => $special,
				'special_end'		 => $special_end,
				'reward'             => $query->row['reward'],
				'points'             => $query->row['points'],
				'tax_class_id'       => $query->row['tax_class_id'],
				'date_available'     => $query->row['date_available'],
				'weight'             => $query->row['weight'],
				'weight_class_id'    => $query->row['weight_class_id'],
				'length'             => $query->row['length'],
				'width'              => $query->row['width'],
				'height'             => $query->row['height'],
				'length_class_id'    => $query->row['length_class_id'],
				'subtract'           => $query->row['subtract'],
				'rating'             => round($query->row['rating']),
				'reviews'            => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'            => $query->row['minimum'],
				'sort_order'         => $query->row['sort_order'],
				'status'             => $query->row['status'],
				'date_added'         => $query->row['date_added'],
				'date_modified'      => $query->row['date_modified'],
				'promo_date_start'   => $query->row['promo_date_start'],
				'promo_date_end'     => $query->row['promo_date_end'],
				'promo_top_left'     => $query->row['promo_top_left'],
				'promo_top_right'    => $query->row['promo_top_right'],
				'promo_bottom_right' => $query->row['promo_bottom_right'],
				'promo_bottom_left'  => $query->row['promo_bottom_left'],
				'viewed'             => $query->row['viewed'],
				'video'              => $query->row['video'],
				'special_end'		 => $query->row['special_end'],
				'special_start'		 => $query->row['special_start'],
			);
		} else {
			return false;
		}
	}

	public function getProductCategories($product_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$result = array();
		foreach($query->rows as $row){
			$result[] = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id WHERE c.category_id = '" . $row['category_id'] . "' AND cd.language_id = '" . $this->config->get('config_language_id') . "'")->row;
		}
		return $result;
	}

	public function getProducts($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$sql = "SELECT p.product_id, p2ser.series_id AS sid, p2ser.is_main AS ism, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special"; 

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN oc_product_to_series p2ser ON (p.product_id = p2ser.product_id) ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
		    // Original filter
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	
		    // Original filter
		    /* Multi category filter */
// 		    $implode_data = array();
		    	
// 		    $implode_data[] = (int)$data['filter_category_id'];
		    
// 		    if (isset($data['filter_id']) && $data['filter_id']) {
// 		        $implode_data[] = $data['filter_id'];
// 		    }
		    	
// 		    foreach ($implode_data as $impi) {
// 		        $sql .= " AND
// 		        (SELECT count(" . DB_PREFIX . "product_to_category.product_id) as pcount FROM " . DB_PREFIX . "product_to_category WHERE category_id = $impi AND product_id = p.product_id) > 0";
// 		    }
// 		    /* Multi category filter */
// 			if (!empty($data['filter_filter'])) {
// 				$implode = array();

// 				$filters = explode(',', $data['filter_filter']);

// 				foreach ($filters as $filter_id) {
// 					$implode[] = (int)$filter_id;
// 				}

// 				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
// 			}
		}	

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}	

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

        //$sql .= " And (p2ser.is_main = 1 or p2ser.is_main is null) ";

	    # Filter start
        if (!empty($data['filter_params'])) {
            if ($this->registry->has('filter_params') && $this->registry->get('filter_params')) {
                $sql .= $this->registry->get('filter_params');
                $this->registry->set('filter_params', false);
            } else {
                return array();
            }
  		}
  		# Filter end
  		
		//$sql .= " GROUP BY CASE WHEN p2ser.product_id > 0 THEN series_id ELSE p.product_id END";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		} 

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}
		
		$sql .= ", p.product_id";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

        //die($sql);

		$query = $this->db->query($sql);

                /* ������� ��� ��������� ������ ������� � ������� */
                $massp = $query->rows;
                $delsid = array();
                foreach ($massp as $valuet) {
                    if($valuet['ism'] == 1 ){
                        $delsid[] = $valuet['sid'];
                    }

                }
                foreach ($delsid as $key => $value) {
                    $iii = 0;
                    $delmass = array();
                    foreach ($massp as $tmp) {
                        if($tmp['sid'] == $value && $tmp['ism'] == 0){
                            $delmass[] = $iii;
                        }
                        $iii++;
                    }
                    foreach ($delmass as $key => $value) {
                        unset($massp[$value]);
                    }
                }
                /*����� �������*/


		foreach ($massp as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

public function getProductSpecials($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		$sql = "";
		
		if(isset($data['latest']) && $data['latest']) {
			
			$promo['tr'] = $this->config->get('config_last_promo_tr');
			$promo['tl'] = $this->config->get('config_last_promo_tl');
			$promo['br'] = $this->config->get('config_last_promo_br');
			$promo['bl'] = $this->config->get('config_last_promo_bl');
			$promo = array_filter($promo);
			
			$sql .= "
				SELECT DISTINCT
				    p.product_id,
				    (SELECT 
				            AVG(rating)
				        FROM
				            oc_review r1
				        WHERE
				            r1.product_id = p.product_id
				                AND r1.status = '1'
				        GROUP BY r1.product_id) AS rating
				FROM
				    oc_product p
			";
		} else {
			$sql .= "
				SELECT 
					DISTINCT ps.product_id, 
					(
						SELECT AVG(rating) 
						FROM 
							" . DB_PREFIX . "review r1 
						WHERE 
							r1.product_id = ps.product_id 
						AND 
							r1.status = '1' 
						GROUP BY 
							r1.product_id
					) AS rating 
				FROM 
					" . DB_PREFIX . "product_special ps 
				LEFT JOIN 
					" . DB_PREFIX . "product p 
					ON (ps.product_id = p.product_id) ";
		}
		$sql .= " LEFT JOIN 
				" . DB_PREFIX . "product_description pd 
				ON (p.product_id = pd.product_id) 
			LEFT JOIN 
				" . DB_PREFIX . "product_to_store p2s 
				ON (p.product_id = p2s.product_id) 
			WHERE 
				p.status = '1' 
			AND 
				p.date_available <= NOW() 
			AND 
				p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
		"; 
		if(isset($data['latest']) && $data['latest']) {
			if(count($promo)) {
				if(count($promo) > 1) {
					$sql .= " And ( ";
				}
				$or = ' ';
				if(isset($promo['tr'])) {
					if(count($promo) > 1) {
						$sql .= " $or p.promo_top_right = " . (int) $promo['tr'];
					} else {
						$sql .= " And p.promo_top_right = " . (int) $promo['tr'];
					}
					$or = ' OR ';
				}
				if(isset($promo['tl'])) {
					if(count($promo) > 1) {
						$sql .= " $or p.promo_top_left = " . (int) $promo['tl'];
					} else {
						$sql .= " And p.promo_top_left = " . (int) $promo['tl'];
					}
					$or = ' OR ';
				}
				if(isset($promo['br'])) {
					if(count($promo) > 1) {
						$sql .= " $or p.promo_bottom_right = " . (int) $promo['br'];
					} else {
						$sql .= " And p.promo_bottom_right = " . (int) $promo['br'];
					}
					$or = ' OR ';
				}
				if(isset($promo['bl'])) {
					if(count($promo) > 1) {
						$sql .= " $or p.promo_bottom_left = " . (int) $promo['bl'];
					} else {
						$sql .= " And p.promo_bottom_left = " . (int) $promo['bl'];
					}
					$or = ' OR ';
				}
				if(count($promo) > 1) {
					$sql .= " ) ";
				}
				$sql .= "
					And (
						p.promo_date_end >= NOW()
						OR p.promo_date_start <= NOW()
					)
				";
			}
		} else {
			$sql .= "
			AND
			    ps.price > 0
			AND
				ps.customer_group_id = '" . (int)$customer_group_id . "'
			AND
				(
					(
						ps.date_start = '0000-00-00'
						OR ps.date_start < NOW()
					)
					AND
					(
						ps.date_end = '0000-00-00'
						OR ps.date_end > NOW()
					)
				)
			GROUP BY
				ps.product_id
			";
		}

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ") ";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC ";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC ";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$product_data = array();
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $customer_group_id . '.' . (int)$limit);

		if (!$product_data) { 
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = array();

		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit);

		if (!$product_data) { 
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) { 		
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']		 	
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);			
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
		
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radiocolor' || $product_option['type'] == 'radiolabel' || $product_option['type'] == 'checkboxcolor' || $product_option['type'] == 'checkboxlabel' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' || $product_option['type'] == 'custom_text') {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'color'                   => $product_option_value['color'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix'],
						'product_option_image'    => $product_option_value['product_option_image'],
						'is_default'    		  => $product_option_value['is_default']
					);
				}

				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
	                'product_page'      => isset($product_option['product_page']) ? ((int)$product_option['product_page']) : 1,
					'type'              => $product_option['type'],
					'option_value'      => $product_option_value_data,
					'required'          => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
	                'product_page'      => isset($product_option['product_page']) ? ((int)$product_option['product_page']) : 1,
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
		}

		return $product_option_data;
	}

	
	public function getDependentOptions($product_id) {
	    $this->checkDependentOptionDb();
	
	    $query = $this->db->query("SELECT parent_product_option_id AS parent, child_product_option_id AS child FROM `" . DB_PREFIX . "dependent_option` WHERE product_id = '" . (int)$product_id . "'");
	
	    return $query->rows;
	}
	
	public function getDependentOptionValues($product_id, $product_option_value_id) {
	    $this->checkDependentOptionValueDb();
	
	    $parent = array();
	
	    $query = $this->db->query("SELECT parent_product_option_value_id FROM `" . DB_PREFIX . "dependent_option_value` WHERE product_id = '" . (int)$product_id . "' AND child_product_option_value_id = '" . (int)$product_option_value_id . "'");
	
	    foreach ($query->rows as $value) {
	        $parent[] = $value['parent_product_option_value_id'];
	    }
	
	    $parent = implode(' ', $parent);
	
	    return $parent;
	}
	
	private function checkDependentOptionDb() {
	    $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "dependent_option'");
	
	    if (!$query->rows) {
	        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "dependent_option` (
								`product_id` int(11) NOT NULL,
								`parent_option_id` int(11) NOT NULL,
								`child_option_id` int(11) NOT NULL,
								`parent_product_option_id` int(11) NOT NULL,
								`child_product_option_id` int(11) NOT NULL,
								KEY `product_id` (`product_id`),
								KEY `child_product_option_id` (`child_product_option_id`))
								ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	    }
	}
	
	private function checkDependentOptionValueDb() {
	    $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "dependent_option_value'");
	
	    if (!$query->rows) {
	        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "dependent_option_value` (
								`product_id` int(11) NOT NULL,
								`parent_option_id` int(11) NOT NULL,
								`child_option_id` int(11) NOT NULL,
								`parent_option_value_id` int(11) NOT NULL,
								`child_option_value_id` int(11) NOT NULL,
								`parent_product_option_value_id` int(11) NOT NULL,
								`child_product_option_value_id` int(11) NOT NULL,
								KEY `product_id` (`product_id`),
								KEY `parent_option_id` (`parent_option_id`))
								ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	    }
	}
	
	
	public function getProductDiscounts($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;		
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) { 
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}	

	public function getTotalProducts($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$sql = "SELECT count(total_products) as total From (SELECT COUNT(DISTINCT p.product_id) AS total_products";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

        $sql .= " LEFT JOIN oc_product_to_series p2ser ON (p.product_id = p2ser.product_id) ";

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
		    // Original Filter
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	
            // Original Filter
		    /* Multi category filter */
// 		    $implode_data = array();
		     
// 		    $implode_data[] = (int)$data['filter_category_id'];
		    
// 		    if (isset($data['filter_id']) && $data['filter_id']) {
// 		        $implode_data[] = $data['filter_id'];
// 		    }
		     
// 		    foreach ($implode_data as $impi) {
// 		        $sql .= " AND
// 		        (SELECT count(" . DB_PREFIX . "product_to_category.product_id) as pcount FROM " . DB_PREFIX . "product_to_category WHERE category_id = $impi AND product_id = p.product_id) > 0";
// 		    }
// 		    /* Multi category filter */
// 			if (!empty($data['filter_filter'])) {
// 				$implode = array();

// 				$filters = explode(',', $data['filter_filter']);

// 				foreach ($filters as $filter_id) {
// 					$implode[] = (int)$filter_id;
// 				}

// 				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
// 			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}	

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}		

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";				
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		# Filter start
		if (!empty($data['filter_params'])) {
		    $this->load->model('catalog/filter');
		
		    $filter_params = $this->model_catalog_filter->getFilterQuery($data['filter_params']);
		
		    if ($filter_params) {
		        $sql .= $filter_params;
		
		        $this->registry->set('filter_params', $filter_params);
		    } else {
		        return 0;
		    }
		}
		# Filter end

        $sql .= " GROUP BY CASE WHEN p2ser.product_id > 0 THEN series_id ELSE p.product_id END";
        $sql .= " ) total_table ";

		$query = $this->db->query($sql);
		# Start filter
		if (!empty($data['filter_filter'])) {
		    $this->load->model('catalog/filter');
		
		    $filter = $this->model_catalog_filter->getFilterQuery($data['filter_filter']);
		
		    if ($filter) {
		        $sql .= $filter;
		
		        $this->session->data['sql'] = $filter;
		    } else {
		        return 0;
		    }
		}

        //die($sql);
		# End filter
		return $query->row['total'];
	}

	public function getProfiles($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "product_profile` `pp` JOIN `" . DB_PREFIX . "profile_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`profile_id` = `pp`.`profile_id` JOIN `" . DB_PREFIX . "profile` `p` ON `p`.`profile_id` = `pd`.`profile_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$customer_group_id . " ORDER BY `sort_order` ASC")->rows;

	}

	public function getProfile($product_id, $profile_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		

		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "profile` `p` JOIN `" . DB_PREFIX . "product_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$customer_group_id)->row;
	}

	public function getTotalProductSpecials($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}		
		if(isset($data['latest']) && $data['latest']) {
			$promo['tr'] = $this->config->get('config_last_promo_tr');
			$promo['tl'] = $this->config->get('config_last_promo_tl');
			$promo['br'] = $this->config->get('config_last_promo_br');
			$promo['bl'] = $this->config->get('config_last_promo_bl');
			$promo = array_filter($promo);
			
			$sql = "
				SELECT
					COUNT(DISTINCT p.product_id) AS total 
				FROM
					" . DB_PREFIX . "product as p
				LEFT JOIN
					" . DB_PREFIX . "product_to_store p2s
					ON (p.product_id = p2s.product_id)
				WHERE
					p.status = '1'
				AND
					p.date_available <= NOW()
				AND
					p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
				
			";
			if(count($promo)) {
				if(count($promo) > 1) {
					$sql .= " And ( ";
				}
				$or = ' ';
				if(isset($promo['tr'])) {
					if(count($promo) > 1) {
						$sql .= " $or p.promo_top_right = " . (int) $promo['tr'];
					} else {
						$sql .= " And p.promo_top_right = " . (int) $promo['tr'];
					}
					$or = ' OR ';
				}
				if(isset($promo['tl'])) {
					if(count($promo) > 1) {
						$sql .= " $or p.promo_top_left = " . (int) $promo['tl'];
					} else {
						$sql .= " And p.promo_top_left = " . (int) $promo['tl'];
					}
					$or = ' OR ';
				}
				if(isset($promo['br'])) {
					if(count($promo) > 1) {
						$sql .= " $or p.promo_bottom_right = " . (int) $promo['br'];
					} else {
						$sql .= " And p.promo_bottom_right = " . (int) $promo['br'];
					}
					$or = ' OR ';
				}
				if(isset($promo['bl'])) {
					if(count($promo) > 1) {
						$sql .= " $or p.promo_bottom_left = " . (int) $promo['bl'];
					} else {
						$sql .= " And p.promo_bottom_left = " . (int) $promo['bl'];
					}
					$or = ' OR ';
				}
				if(count($promo) > 1) {
					$sql .= " ) ";
				}
				$sql .= "
					And (
						p.promo_date_end >= NOW()
						OR p.promo_date_start <= NOW()
					)
				";
			}
		} else {
			$sql = "
				SELECT
					COUNT(DISTINCT ps.product_id) AS total
				FROM
					" . DB_PREFIX . "product_special ps
				LEFT JOIN
					" . DB_PREFIX . "product p
					ON (ps.product_id = p.product_id)
				LEFT JOIN
					" . DB_PREFIX . "product_to_store p2s
					ON (p.product_id = p2s.product_id)
				WHERE
					p.status = '1'
				AND
					p.date_available <= NOW()
				AND
					p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
				AND
					ps.customer_group_id = '" . (int)$customer_group_id . "'
				AND
					(
						(
							ps.date_start = '0000-00-00'
							OR ps.date_start < NOW()
						)
						AND
						(
							ps.date_end = '0000-00-00'
							OR ps.date_end > NOW()
						)
					)
			";
		}
		
		$query = $this->db->query($sql);

		if (isset($query->row['total'])) {
		    # Start filter
		    if (!empty($data['filter_filter'])) {
		        $this->load->model('catalog/filter');
		    
		        $filter = $this->model_catalog_filter->getFilterQuery($data['filter_filter']);
		    
		        if ($filter) {
		            $sql .= $filter;
		    
		            $this->session->data['sql'] = $filter;
		        } else {
		            return 0;
		        }
		    }
		    # End filter
			return $query->row['total'];
		} else {
			return 0;	
		}
	}
	
	public function getFullPath($product_id) {
	
	    $query = $this->db->query("SELECT COUNT(product_id) AS total, min(category_id) as catid FROM " . DB_PREFIX . "product_to_category  WHERE product_id = '" . (int)$product_id . "' group by product_id");
	   
	    if ($query->num_rows && $query->row['total'] >= 1) {
	        $path = array();
	        $path[0] = $query->row['catid'];
	
	        $query = $this->db->query("SELECT parent_id AS pid FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$path[0] . "'");
	
	        $parent_id = $query->row['pid'];
	
	        $i = 1;
	        while($parent_id > 0) {
	            $path[$i] = $parent_id;
	
	            $query = $this->db->query("SELECT parent_id AS pid FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$parent_id . "'");
	            $parent_id = $query->row['pid'];
	            $i++;
	        }
	
	        $path = array_reverse($path);
	        	
	        $fullpath = '';
	
	        foreach($path as $val){
	            $fullpath .= '_'.$val;
	        }
	
	        return ltrim($fullpath, '_');
	    }	else {
	        return false;
	    }
	}
	
	public function getStockStatus($status_id) {
	    $sql = "Select name From " . DB_PREFIX . "stock_status Where stock_status_id = $status_id And language_id = " . (int)$this->config->get('config_language_id');
	    $result = $this->db->query($sql);
	    if($result->num_rows) {
	        return $result->row['name'];
	    } else {
	        return false;
	    }
	}
	
	/*code start*/
	public function getPromo($product_id,$promo_tags_id) {
	    $query = $this->db->query("SELECT pt.promo_text, pti.image 
	    							FROM 
	    								" . DB_PREFIX . "promo_tags pt
	    								JOIN " . DB_PREFIX . "promo_tags_images pti ON pti.promo_tags_id = pt.promo_tags_id
	    							WHERE pt.promo_tags_id = '" . (int)$promo_tags_id . "' 
	    							AND   pti.language_id = '" . $this->config->get('config_language_id') . "'");
	    
	    
	   /*  die("SELECT pt.promo_text, pt.image, pt.pimage 
	    							FROM 
	    								" . DB_PREFIX . "promo_tags pt, 
	    								" . DB_PREFIX . "product p 
	    							WHERE pt.promo_tags_id = '" . (int)$promo_tags_id . "' 
	    								AND p.product_id = '" . (int)$product_id . "'"); */
	    return $query->row;
	}
	/*code end*/
	
	public function getProductsInSameSeries($product_id) {
		$sql = "
			Select 
				*
			From 
				" . DB_PREFIX . "product_to_series p2se
			Left Join 
				" . DB_PREFIX . "series s
			On 
				(p2se.series_id = s.series_id)
			Where 
				product_id = " . (int)$product_id . "
		";
		$series = $this->db->query($sql);
		$result = array();
		if($series->num_rows) {
			foreach ($series->rows as $single) {
				$products = array();
				$sql = "
					Select 
						product_id
					From 
						" . DB_PREFIX . "product_to_series
					Where 
						series_id = " . (int)$single['series_id'] . "";
					//And
					//	product_id <> " . (int)$product_id . "
				//";
				$ids = $this->db->query($sql);

				
				if($ids->num_rows) {
					foreach ($ids->rows as $id) {
						$products[] = $this->getproduct($id['product_id']);
					}
					$result[] = array(
						'name' => $single['name'],
						'products' => $products,
					);
				}
			}
			
			return $result;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////// DISCOUNT FUNCTIONS //////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function getMultiDiscountPrice($product_id,$price,$special,$manufacturer_id,$option = false){
		$return            = false;
		$discount_available = true;
	
		$setting = $this->getMultiDiscountSetting('setting');
	
		if(isset($setting['status']) AND $setting['status'] == 1){
	
			$DISCOUNT = array();
			 
			//total
			$discount = $this->getMultiDiscountSetting('total');
			$discount_available = $this->isDiscountAvailable($discount['discount_start'],$discount['discount_stop']);
			if($discount_available || (isset($discount['fulltime']) && $discount['fulltime'] == 1)){
				$DISCOUNT['total'] = $discount;
			}
	
	
			//manufacturer
			if(isset($manufacturer_id)){
				$discount = $this->getManufacturerDiscountSetting($manufacturer_id);
				if($discount){
					$discount_available = $this->isDiscountAvailable($discount['discount_start'],$discount['discount_stop']);
					if($discount_available || (isset($discount['fulltime']) && $discount['fulltime'] == 1)){
						$DISCOUNT['manufacturer'] = $discount;
					}
				}
			}
	
			//category
			$discount = $this->getCategoryDiscountSetting($product_id);
			if($discount){
				$discount_available = $this->isDiscountAvailable($discount['discount_start'],$discount['discount_stop']);
				if($discount_available || (isset($discount['fulltime']) && $discount['fulltime'] == 1)){
					$DISCOUNT['category'] = $discount;
				}
			}
	
			//customer_group
			if($this->customer->isLogged()){
				$customer_group_id = $this->customer->getCustomerGroupId();
			}else{
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			if(isset($customer_group_id)){
				$discount = $this->getCustomerGroupDiscountSetting($customer_group_id);
				if($discount){
					$discount_available = $this->isDiscountAvailable($discount['discount_start'],$discount['discount_stop']);
					if($discount_available || (isset($discount['fulltime']) && $discount['fulltime'] == 1)){
						$DISCOUNT['customer_group'] = $discount;
					}
				}
			}
	
			$discount_available = false;
			$discount = false;
	
			foreach($DISCOUNT as $type => $d){
				if(!isset($discount['discount_value'])){
					$discount = $d;
					$discount_available = true;
				} else {
					if($discount['discount_value'] < $d['discount_value']){
						$discount = $d;
						$discount_available = true;
					}
				}
			}
	
			if($discount_available && $discount){
				if($special){
	
					if($setting['special']){
						if($discount['discount_type'] == "percentage"){
							$return['price'] = $price;
							$return['special'] = $special-($special*($discount['discount_value']/100));
						}
						if($discount['discount_type'] == "fixed"){
							$return['price'] = $price;
							$return['special'] = $special-$discount['discount_value'];
						}
					}else{
						$return['price'] = $price;
						$return['special'] = $special;
					}
				}else{
					if($discount['discount_type'] == "percentage"){
						$return['price'] = $price;
						$return['special'] = $price-($price*($discount['discount_value']/100));
					}
	
					if($discount['discount_type'] == "fixed"){
						$return['price'] = $price;
						$return['special'] = $price-$discount['discount_value'];
					}
				}
			}
	
	
			if($option AND isset($setting['options']) AND $setting['options'] == 1){
				$return['option_price'] = $return['special'];
			}elseif($option AND (!isset($setting['options']) || $setting['options'] == 0)){
				$return['option_price'] = $return['price'];
			}
			 
			return $return;
	
		}else{
			return false;
		}
	}
	
	
	
	private function isDiscountAvailable($date_start,$date_stop){
		$date_start = strtotime($date_start);
		$date_stop  = strtotime($date_stop);
		if(time() >= $date_start AND time() <= $date_stop){
			return true;
		}else{
			return false;
		}
	}
	
	private function getMultiDiscountSetting($key){
		if(isset($this->discount_settings[$key])){
			return $this->discount_settings[$key];
		} else {
			return false;
		}
	}
	
	private function getManufacturerDiscountSetting($manufacturer_id){
	
		$manufacturer_setting = false;
	
		if(isset($manufacturer_id)){
			
			if(isset($this->discount_settings['manufacturer'])){
				$manufacturers = $this->discount_settings['manufacturer'];
			} else {
				return false;
			}
			
			foreach($manufacturers as $manufacturer){
				if($manufacturer['manufacturer_id'] == $manufacturer_id){
					if(isset($manufacturer['fulltime']) AND $manufacturer['fulltime'] == 1){
						$manufacturer_setting['fulltime']     = true;
						$manufacturer_setting['discount_start'] = false;
						$manufacturer_setting['discount_stop']  = false;
					}else{
						$manufacturer_setting['fulltime']     = false;
						$manufacturer_setting['discount_start'] = $manufacturer['discount_start'];
						$manufacturer_setting['discount_stop']  = $manufacturer['discount_stop'];
					}
					$manufacturer_setting['discount_type'] = $manufacturer['discount_type'];
					$manufacturer_setting['discount_value'] = $manufacturer['discount_value'];
				}
	
			}
		}
		return $manufacturer_setting;
	}
	
	
	
	
	private function getCategoryDiscountSetting($product_id){
		
		if(isset($this->discount_settings['category']) && count($this->discount_settings['category'])){
			$categories = $this->discount_settings['category'];
		} else {
			return array();
		}
	
	
		$product_in_categories = array();
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE `product_id` = '".(int)$product_id."'");
		if($query->rows){
			foreach($query->rows as $product_category_id){
				$product_in_categories[] = $product_category_id['category_id'];
			}
		}
	
		$discount_categories = array();
		foreach($categories as $discount_category){
			$discount_categories[] = $discount_category['category_id'];
	
	
			if(isset($discount_category['subcategories'])){
				$query = $this->db->query("SELECT parent_id,category_id FROM " . DB_PREFIX . "category WHERE `parent_id` = '".(int)$discount_category['category_id']."'");
				if($query->row){
					$actual_category = $query->row;
					$parent_id = $actual_category['category_id'];
					$discount_categories[] = $actual_category['category_id'];
					$subcategories[$discount_category['category_id']][] = $actual_category['category_id'];
	
	
					$continue_to_subcategory = true;
					while($continue_to_subcategory){
						$query           = $this->db->query("SELECT parent_id,category_id FROM " . DB_PREFIX . "category WHERE `parent_id` = '".(int)$parent_id."'");
						if($query->row){
							$parent_category = $query->row;
							$parent_id       = $parent_category['category_id'];
							$discount_categories[] = $parent_category['category_id'];
							$subcategories[$discount_category['category_id']][] = $parent_category['category_id'];
						}else{
							$continue_to_subcategory = false;
						}
					}
				}
			}
		}
	
		//check subcategories
		$discount_category_id = false;
		$have_category_discount = false;
		if(isset($subcategories)){
			foreach($subcategories as $key => $subcategory){
				foreach($product_in_categories as $product_category_id){
					if (in_array($product_category_id, $subcategory) || $key == $product_category_id) {
						$discount_category_id = $key;
						$have_category_discount = true;
					}
				}
			}
		}else{ //doesn't have subcategory
			if(isset($discount_categories)){
				foreach($product_in_categories as $product_category_id){
					if (in_array($product_category_id, $discount_categories)) {
						$discount_category_id = $product_category_id;
						$have_category_discount = true;
					}
				}
			}
		}
	
		$category_setting = false;
		if($have_category_discount AND isset($discount_category_id)){
			$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `group` = 'discount' AND `key` = 'category'");
			$categories = unserialize($query->row['value']);
			foreach($categories as $category){
				if($category['category_id'] == $discount_category_id){
					if(isset($category['fulltime']) AND $category['fulltime'] == 1){
						$category_setting['fulltime']       = true;
						$category_setting['discount_start'] = false;
						$category_setting['discount_stop']  = false;
					}else{
						$category_setting['fulltime']       = false;
						$category_setting['discount_start'] = $category['discount_start'];
						$category_setting['discount_stop']  = $category['discount_stop'];
					}
					$category_setting['discount_type']    = $category['discount_type'];
					$category_setting['discount_value']   = $category['discount_value'];
				}
			}
		}
	
		return $category_setting;
	
	}
	
	private function getCustomerGroupDiscountSetting($customer_group_id){
		
		$customer_group_setting = false;
		if(isset($customer_group_id)){
			
			if(isset($this->discount_settings['customer_group'])){
				$customer_groups = $this->discount_settings['customer_group'];
			} else {
				return false;
			}
			
			foreach($customer_groups as $customer_group){
				if($customer_group['customer_group_id'] == $customer_group_id){
					if(isset($customer_group['fulltime']) AND $customer_group['fulltime'] == 1){
						$customer_group_setting['fulltime']     = true;
						$customer_group_setting['discount_start'] = false;
						$customer_group_setting['discount_stop']  = false;
					}else{
						$customer_group_setting['fulltime']     = false;
						$customer_group_setting['discount_start'] = $customer_group['discount_start'];
						$customer_group_setting['discount_stop']  = $customer_group['discount_stop'];
					}
					$customer_group_setting['discount_type'] = $customer_group['discount_type'];
					$customer_group_setting['discount_value'] = $customer_group['discount_value'];
				}
	
			}
		}
		return $customer_group_setting;
	}

    public function getDeepestCategory($product_id) {
        $sql = "
            Select
                *
            From
                oc_category_path cp
            Left join
                oc_product_to_category p2c
            On
                p2c.category_id = cp.category_id
            Left Join
                oc_category_description cd
            On
                cd.category_id = cp.category_id
            Where
                p2c.product_id = " . (int)$product_id . "
            And
                cd.language_id = " . $this->config->get('config_language_id') . "
            Order By cp.level DESC
            Limit 1
        ";
        $categories = $this->db->query($sql);

        return $categories->row;
    }

    public function getProductsByTags($tags) {
        $sql = "
            Select
                p.product_id
            From
                " . DB_PREFIX . "product p
            Left Join
                " . DB_PREFIX . "product_description pd
            On
                pd.product_id = p.product_id
                LEFT JOIN " . DB_PREFIX . "product_to_series p2ser ON (p.product_id = p2ser.product_id)
            Where
                pd.language_id = " . (int)$this->config->get('config_language_id') . "
            And
        ";
        $sqls = array();
        foreach ($tags as $tag) {
            $sqls[] = " pd.tag like ('%" . $this->db->escape(trim($tag)) . "%') ";
        }
        $sql .= '(' . implode(' OR ', $sqls) . ')';

		$sql .= " GROUP BY CASE WHEN p2ser.product_id > 0 THEN p2ser.series_id ELSE p.product_id END";

        $product_ids = $this->db->query($sql);

        $products = array();
        if($product_ids->num_rows) {
            foreach ($product_ids->rows as $product) {
                $products[] = $this->getProduct($product['product_id']);
            }
        }

        return $products;
    }
}
