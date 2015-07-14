<?php

class ModelCatalogFilter extends Model {

  public function getOptionsByCategoryId($category_id, $filter_id = 0) {
	$this->load->model('catalog/category');
	$category = $this->model_catalog_category->getCategory($category_id);
	if(isset($category['parent_id']) && $category['parent_id'] != 0) {
		$category_id = $this->getRootCategory($category_id);
	}
    $options_data = false;//$this->cache->get('option.' . $category_id . '.' . $this->config->get('config_language_id'));

    if (!$options_data && !is_array($options_data)) {
      $sql = "
          SELECT 
            co.option_id,
            co.type,
            3 as 'group_count',
            cod.name ,
            '' as 'postfix',
            '' as 'description'
        FROM
            " . DB_PREFIX . "option co
                LEFT JOIN
            " . DB_PREFIX . "option_description cod ON (co.option_id = cod.option_id)
        WHERE co.filter_option = 1 ";
      if(implode(', ', $this->getOptionCategoryIds($category_id))) {
        $sql .= "AND co.option_id IN (" . implode(', ', $this->getOptionCategoryIds($category_id)) . ")";
      } 
//       else {
//       	return false;
//       }
      $sql .= "AND cod.language_id = '" . (int)$this->config->get('config_language_id') . "'
        ORDER BY co.sort_order = 0, co.sort_order 
      ";
      $options_query = $this->db->query($sql);
      

      if ($options_query->num_rows) {
        $options_id = array();

        foreach ($options_query->rows as $option) $options_id[] = (int)$option['option_id'];
        $sql = "
            SELECT 
                cov.option_value_id as value_id , cov.option_id, covd.name, cov.color
            FROM
                " . DB_PREFIX . "option_value cov
                    LEFT JOIN
                " . DB_PREFIX . "option_value_description covd ON (cov.option_value_id = covd.option_value_id)
            WHERE
                cov.option_id IN (" . implode(',', $options_id) . ") AND covd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            ORDER BY cov.sort_order , covd.name                    
        ";
        $values_query = $this->db->query($sql);

        $values = array();

        foreach ($values_query->rows as $value) $values[$value['option_id']][] = $value;

        foreach ($options_query->rows as $option) {
          if (isset($values[$option['option_id']])) {
            $options_data[$option['option_id']] = $option;
            $options_data[$option['option_id']]['values'] = $values[$option['option_id']];
          }
        }
      }
      //$this->cache->set('option.' . $category_id . '.' . $this->config->get('config_language_id'), $options_data);
    }

    return $options_data;
  }
  
  private function getRootCategory($category_id) {
  	$sql = "Select category_id, parent_id From " . DB_PREFIX . "category Where category_id = " . (int)$category_id;
  	$category = $this->db->query($sql);
  	if($category->num_rows) {
  		if($category->row['parent_id'] != 0) {
  			return $this->getRootCategory($category->row['parent_id']);
  		} else {
  			return $category->row['category_id'];
  		}
  	} else {
  		return false;
  	}
  }
  
  private function getOptionCategoryIds($category_id) {
      $category_options = $this->config->get('filter_category_options');
      $options = array('');
      if(!empty($category_options)) {
          foreach ($category_options as $category => $values) {
              if($category == $category_id) {
                  $options = $values;
              }
          }
      }
      return $options;
  }

  public function getOptionsByProductsId($products_id = array()) {
    $options_data = array();

    if ($products_id) {
      $sql = "
        SELECT 
            p2v.product_id, co.option_id, cod.name, ' ' as postfix
        FROM
            " . DB_PREFIX . "option co
                LEFT JOIN
            " . DB_PREFIX . "option_description cod ON (cod.option_id = co.option_id)
                LEFT JOIN
            " . DB_PREFIX . "product_option_value p2v ON (co.option_id = p2v.option_id)
        WHERE
            cod.language_id = '2' AND p2v.product_id IN (" . implode(',', $products_id) . ")
        ORDER BY co.sort_order , cod.name
      ";
      $options_query = $this->db->query($sql);
      $sql = "  
        SELECT 
            p2v.product_id, cov.option_id, covd.name
        FROM
            " . DB_PREFIX . "option_value cov
                LEFT JOIN
            " . DB_PREFIX . "option_value_description covd ON (covd.option_value_id = cov.option_value_id)
                LEFT JOIN
            " . DB_PREFIX . "product_option_value p2v ON (cov.option_value_id = p2v.option_value_id)
        WHERE
            covd.language_id = '2' AND p2v.product_id IN (" . implode(',', $products_id) . ")
        ORDER BY cov.sort_order , covd.name                
      ";
      $values_query = $this->db->query($sql);

      if ($options_query->num_rows && $values_query->num_rows) {
        $values = array();
        foreach ($values_query->rows as $row) $values[$row['product_id']][$row['option_id']][] = $row['name'];

        foreach ($options_query->rows as $row) {
          if (isset($values[$row['product_id']][$row['option_id']])) {
            $options_data[$row['product_id']][$row['option_id']] = $row;
            $options_data[$row['product_id']][$row['option_id']]['values'] = implode($row['postfix'] . ' &bull; ', $values[$row['product_id']][$row['option_id']]) . $row['postfix'];
          }
        }
      }
    }
    return $options_data;
  }

  public function getManufacturersByCategoryId($category_id, $filter_id = 0) {
      $sql = "
          SELECT m.manufacturer_id AS value_id, m.name, 'm' AS option_id 
          FROM " . DB_PREFIX . "manufacturer m 
          LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
          LEFT JOIN " . DB_PREFIX . "product p ON (m.manufacturer_id = p.manufacturer_id) 
          LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) 
          WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
      ";
  		$childs = array();
        $children_categories = $this->getChildrenCategories($category_id);
        if(!empty($children_categories)) {
        	foreach ($children_categories as $children_category) {
        		$childs[] = $children_category;
        	}
        } 
		if(!empty($childs)) {
			$sql .= " AND p2c.category_id In (" . (int)$category_id . ", " . implode(', ', $childs) . ") "; // Original filter query
		} else {
			$sql .= " AND p2c.category_id = '" . (int)$category_id . "' "; // Original filter query
		}
      $sql .= " GROUP BY m.manufacturer_id ORDER BY name";
      
	  $query = $this->db->query($sql);

		return $query->rows;
	}

	public function getChildrenCategories($category_id, $data = array()) {
		$sql = "Select category_id From " . DB_PREFIX . "category Where parent_id = " . (int)$category_id;
		$categories = $this->db->query($sql);
	
		foreach ($categories->rows as $category) {
			$data[] = $category['category_id'];
			$data = array_merge($data, $this->getChildrenCategories($category['category_id'], $data));
		}
		$data = array_unique($data);
		
		return $data;
	}
	
  public function getStockStatuses() {
		$query = $this->db->query("SELECT stock_status_id AS value_id, name, 's' AS option_id FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");
		
		return $query->rows;
	}

  public function getProductPrices($data) {
    $product_data = array();

    $sql = "SELECT p.product_id, p.price, p.tax_class_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1' AND p2c.category_id = '" . (int)$data['filter_category_id'] . "' AND p.price > '0' AND p.date_available <= NOW()";

    if (!empty($data['filter_filter'])) {
      $filter_params = $this->getFilterQuery($data['filter_filter']);

      if ($filter_params) {
        $sql .= $filter_params;
      } else {
        return 0;
      }
    }

    $sql .= " ORDER BY p.price";

    $query = $this->db->query($sql);

    foreach ($query->rows as $row) {
      $product_data[$row['product_id']] = $row['price'];
    }

    $price_data = array();

    if ($product_data) {
      $price_data['min'] = floor(min($product_data) * $this->currency->getValue());
      $price_data['max'] = ceil(max($product_data) * $this->currency->getValue());
      $price_data['products'] = $product_data;
    }
    return $price_data;
  }

  public function getMinMaxCategoryPrice($data = array()) {
  	
    $sql = "
        SELECT MIN(p.price) AS min, MAX(p.price) AS max 
        FROM " . DB_PREFIX . "product p 
        LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) 
        LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
        WHERE 1
        AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
        AND p.status = '1' 
    ";
        $childs = array();
        $children_categories = $this->getChildrenCategories($data['filter_category_id']);
        if(!empty($children_categories)) {
        	foreach ($children_categories as $children_category) {
        		$childs[] = $children_category;
        	}
        } 
		if(!empty($childs)) {
			$sql .= " AND p2c.category_id In (" . (int)$data['filter_category_id'] . ", " . implode(', ', $childs) . ") "; // Original filter query
		} else {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "' "; // Original filter query
		}
     
    $sql .= "AND p.price > '0' 
        AND p.date_available <= NOW()
    "; 
    $query = $this->db->query($sql);

    $price_data = array();
	
    if ($query->num_rows) {
      $price_data['min'] = number_format($query->row['min'] * $this->currency->getValue(), 2, '.', '');
      $price_data['max'] = ceil($query->row['max'] * $this->currency->getValue());
    }
    return $price_data;
  }

  public function getFilterQuery($filter_params) {
    $sql = '';

    if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

    $module = $this->config->get('filter_module');

    $settings = $module[0];

    $values_id = array();
    $products_id = array();
    $options_count = 0;

    foreach (explode(';', $filter_params) as $option) {
      $values = explode(':', $option);

      if ($values[0] == 'p' && $settings['show_price']) { # if price filtering
        $between = explode('-', $values[1]);

        if (isset($between[0]) && isset($between[1])) {
          $price_from = floor((int)$between[0] / $this->currency->getValue());
          $price_to = ceil((int)$between[1] / $this->currency->getValue());

          $sql .= " AND (p.price BETWEEN '" . $price_from . "' AND '" . $price_to . "'";

          if ($settings['consider_discount']) {
            $sql .= " OR p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND price BETWEEN '" . $price_from . "' AND '" . $price_to . "')";
          }

          if ($settings['consider_special']) {
            $sql .= " OR p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND price BETWEEN '" . $price_from . "' AND '" . $price_to . "')";
          }

          $sql .= ")";
        }
      } else if ($values[0] == 'l') { # if label filtering
      	
        $data = array();
      	foreach (explode(',', $values[1]) as $value_id) {
			$data[] = '  p.promo_top_right = ' . (int)$value_id . ' ';
      	}
      	if(count($data) === 1) {
      		$sql .= ' And ' . $data[0];
      	} else {
      		$sql .= ' And (' . implode(' OR ', $data) . ' ) '; 
      	}
      } else if ($values[0] == 's' && $settings['stock_status']) { # if stock status filtering
      	
        $data = array();
      	foreach (explode(',', $values[1]) as $value_id) {
      		switch ($value_id) {
      			case '1':
      				$data[] = '  p.quantity > 0 ';
      				break;
      			case '2':
      				$data[] = '  p.quantity < 1 ';
      				break;
      			case '3':
      				$data[] = ' ( p.quantity < 1 And p.stock_status_id = 16 ) ';
      				break;
      			default:
      				break;
      		}
      	}
      	if(count($data) === 1) {
      		$sql .= ' And ' . $data[0];
      	} else {
      		$sql .= ' And (' . implode(' OR ', $data) . ' ) '; 
      	}
      	
      } else if ($values[0] == 'm' && $settings['manufacturer']) { # if manufacturer filtering
        $data = array();
        foreach (explode(',', $values[1]) as $value_id) $data[] = "p.manufacturer_id = '" . (int)$value_id . "'";

        $sql .= " AND (" . implode(' OR ', $data) . ")";
      } else if (isset($values[1])) { # if options
        foreach (explode(',', $values[1]) as $value_id) $values_id[] = (int)$value_id;

        $options_count++;
      }
    }

    if ($values_id) {
      $data = array();

      foreach ($values_id as $value_id) $data[] = "option_value_id = '" . (int)$value_id . "'";
      $vSql = "SELECT product_id, option_id FROM " . DB_PREFIX . "product_option_value WHERE " . implode(' OR ', $data);
      $query = $this->db->query($vSql);

      $data = array();

      foreach($query->rows as $row) $data[$row['option_id']][$row['product_id']] = (int)$row['product_id']; # Separate unique products_id by options (for multiply values in product)
      foreach($data as $product_data) $products_id = array_merge($product_data, $products_id);
    }

    if ($values_id && !$products_id) return 0;

    if ($products_id) {
      $data = array();

      if (count(array_unique($products_id)) < count($products_id)) {
        foreach (array_count_values($products_id) as $product_id => $count) {
          if ($count == $options_count) $data[] = (int)$product_id;
        }
      } elseif ($options_count == 1) $data = $products_id;

      if ($data) {
        $sql .= " AND p.product_id IN (" . implode(',', $data) . ")";
      } else {
        return 0;
      }
    }
    return $sql;
  }
  
  public function getPromoTags() {
  	$sql = "
		Select 
			promo_tags_id as value_id,
			promo_text as name,
			'l' as option_id
  		From
  			" . DB_PREFIX . "promo_tags
	";
  	
  	$promo = $this->db->query($sql);
  	
  	return $promo->rows;
  }
}

?>