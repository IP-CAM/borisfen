<?php
class Cart {
	private $config;
	private $db;
	private $data = array();
	private $data_recurring = array();

	public function __construct($registry) {
		
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');
		
		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
			$this->session->data['cart'] = array();
		}
	}

        
        
        
        
        
        public function getFullPrice() {
            
            $p_total = 0;
            foreach ($this->session->data['cart'] as $key => $quantity) {
                        
		        $product = explode(':', $key);
		        $product_id = $product[0];
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p
									LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
										WHERE p.product_id = '" . (int)$product_id . "'
										AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
										AND p.date_available <= NOW()
										AND p.status = '1'");
				$num_in_pack = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . $product_id . "' AND attribute_id = '23'")->row['text'];

				if ($product_query->num_rows) {
					$option_price = 0;

					foreach ($options as $product_option_id => $option_value) {
						$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
					//$sql = "SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'";
						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'radiocolor' || $option_query->row['type'] == 'radiolabel' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}
								}
							} elseif (($option_query->row['type'] == 'checkbox' ||  $option_query->row['type'] == 'checkboxcolor' || $option_query->row['type'] == 'checkboxlabel') && is_array($option_value)) {
								foreach ($option_value as $product_option_value_id) {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}
									}
								}						
							} 
						}
					} 

					if ($this->customer->isLogged()) {
						$customer_group_id = $this->customer->getCustomerGroupId();
					} else {
						$customer_group_id = $this->config->get('config_customer_group_id');
					}

					/* Prices with whole_sale_price */
					$whole_sale_price_status = $this->config->get('config_whole_sale_price');
					$whole_sale_price_quantity = $this->config->get('config_whole_sale_price_quantity');
					if(isset($product_query->row['whole_sale_price_quantity']) && $product_query->row['whole_sale_price_quantity']) {
					    $whole_sale_price_quantity = $product_query->row['whole_sale_price_quantity'];
					}
					if($whole_sale_price_status) {
                                                if(
                                                    count($this->session->data['cart']) < $whole_sale_price_quantity && 
                                                    $group_quantity[$product_id] < $whole_sale_price_quantity && 
                                                    $quantity < $whole_sale_price_quantity
						) {
                                                    $price = $product_query->row['price'];
                                                } else {
                                                    if($product_query->row['whole_sale_price'] > 0) {
                                                        $price = $product_query->row['whole_sale_price'];
                                                    } else {
                                                        $price = $product_query->row['price'];
                                                    }
                                                }
					} else {
					    $price = $product_query->row['price'];
					}
					/* Prices with whole_sale_price */
					
					//MULTI DISCOUNT PACK - start
					$default_price = $price;
					$special_price = 0;
					//MULTI DISCOUNT PACK - stop
					
					
					// Product Discounts
					$discount_quantity = 0;

					foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
						$product_2 = explode(':', $key_2);

						if ($product_2[0] == $product_id) {
							$discount_quantity += $quantity_2;
						}
					}

					$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

					if ($product_discount_query->num_rows) {
						$price = $product_discount_query->row['price'];
					}

					// Product Specials
					$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

					if ($product_special_query->num_rows) {
						$price = $product_special_query->row['price'];
						//MULTI DISCOUNT PACK - start
						$special_price = $price;
						//MULTI DISCOUNT PACK - stop
					}			

					//MULTI DISCOUNT PACK - start
					$category_id = "";
					if(!isset($special_price)){$special_price = false;}
					
					$discount_price = $this->getMultiDiscountPrice($product_id,$default_price,$special_price,$product_query->row['manufacturer_id']);
					if($discount_price){
						$price = $discount_price['special'];
					}
					//MULTI DISCOUNT PACK - stop
					
					//MULTI DISCOUNT PACK - start
					$discount_price = $this->getMultiDiscountPrice($product_id,$option_price,false,$product_query->row['manufacturer_id'],true);
					if(isset($discount_price) AND isset($discount_price['option_price']) AND $option_price > 0){
						$option_price = $discount_price['option_price'];
					}
					//MULTI DISCOUNT PACK - stop


                                        $p_total = $p_total + ($price + $option_price) * $quantity;
				}                        
                         
            }
            return $p_total;
        }
        
        
        public function getFullQuant() { 
                    $q_now = 0;
		    foreach ($this->session->data['cart'] as $key => $quantity) {
                            $q_now = $q_now + $quantity;
		    }  
                    return $q_now;
        }
        
        
        
        
        
        
        
        
	public function getProducts() { //echo '<pre>'; print_r($this->session->data['cart']); echo '</pre>';
		if (!$this->data) {
		    $group_quantity = array();
		    foreach ($this->session->data['cart'] as $key => $quantity) {
		        $product = explode(':', $key);
		        $product_id = $product[0];
		        if(isset($group_quantity[$product_id])) {
		            $group_quantity[$product_id] += $quantity;
		        } else {
		            $group_quantity[$product_id] = $quantity;
		        }
		    }
			foreach ($this->session->data['cart'] as $key => $quantity) {
				$product = explode(':', $key);
				$product_id = $product[0];
				$stock = true;
				
				// Time_cancel (for cancel the product from order)
				if (!empty($this->session->data['time_cancel'][$key])) {
					$time_cancel = $this->session->data['time_cancel'][$key];
				} else {
					$time_cancel = '';
				}
				
				// Options
				if (!empty($product[1])) {
					$options = unserialize(base64_decode($product[1]));
				} elseif (isset($this->session->data['cancel_product_option']) && $this->session->data['cancel_product_option']) { // if are cancel product from confirm order
					$options = unserialize(base64_decode($this->session->data['cancel_product_option']));
				} else {
					$options = array();
				} 

				// Profile
                
				if (!empty($product[2])) {
					$profile_id = $product[2];
				} else {
					$profile_id = 0;
				}

				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p
									LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
										WHERE p.product_id = '" . (int)$product_id . "'
										AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
										AND p.date_available <= NOW()
										AND p.status = '1'");
				$num_in_pack = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . $product_id . "' AND attribute_id = '23'")->row['text'];

				if ($product_query->num_rows) {
					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;

					$option_data = array();

					foreach ($options as $product_option_id => $option_value) {
						$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
					//$sql = "SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'";
						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'radiocolor' || $option_query->row['type'] == 'radiolabel' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $option_value,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'option_value'            => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										//MULTI DISCOUNT PACK - start
									        'price'                   => $option_price,
									    //MULTI DISCOUNT PACK - stop
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],									
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);								
								}
							} elseif (($option_query->row['type'] == 'checkbox' ||  $option_query->row['type'] == 'checkboxcolor' || $option_query->row['type'] == 'checkboxlabel') && is_array($option_value)) {
								foreach ($option_value as $product_option_value_id) {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
										} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}

										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
										} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}

										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
											$stock = false;
										}

										$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id'               => $option_query->row['option_id'],
											'option_value_id'         => $option_value_query->row['option_value_id'],
											'name'                    => $option_query->row['name'],
											'option_value'            => $option_value_query->row['name'],
											'type'                    => $option_query->row['type'],
											'quantity'                => $option_value_query->row['quantity'],
											'subtract'                => $option_value_query->row['subtract'],
											'price'                   => $option_value_query->row['price'],
											'price_prefix'            => $option_value_query->row['price_prefix'],
											'points'                  => $option_value_query->row['points'],
											'points_prefix'           => $option_value_query->row['points_prefix'],
											'weight'                  => $option_value_query->row['weight'],
											'weight_prefix'           => $option_value_query->row['weight_prefix']
										);								
									}
								}						
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => '',
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => '',
									'name'                    => $option_query->row['name'],
									'option_value'            => $option_value,
									'type'                    => $option_query->row['type'],
									'quantity'                => '',
									'subtract'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'points'                  => '',
									'points_prefix'           => '',								
									'weight'                  => '',
									'weight_prefix'           => ''
								);						
							}
						}
					} 

					if ($this->customer->isLogged()) {
						$customer_group_id = $this->customer->getCustomerGroupId();
					} else {
						$customer_group_id = $this->config->get('config_customer_group_id');
					}
					

					$is_wholesale = false;
					if($customer_group_id == $this->config->get('wholesale_group_id') || (isset($this->session->data['cancel_product']) && $this->session->data['cancel_product'])){
						$is_wholesale = true;
					}

					/* Prices with whole_sale_price */
					$whole_sale_price_status = $this->config->get('config_whole_sale_price');
					$whole_sale_price_quantity = $this->config->get('config_whole_sale_price_price');
					if(isset($product_query->row['whole_sale_price_quantity']) && $product_query->row['whole_sale_price_quantity']) {
					    $whole_sale_price_quantity = $product_query->row['whole_sale_price_quantity'];
					}
					if($whole_sale_price_status) {
                                                if(
                                                    count($this->session->data['cart']) < $whole_sale_price_quantity && 
                                                    $group_quantity[$product_id] < $whole_sale_price_quantity && 
                                                    $quantity < $whole_sale_price_quantity
						) {
                                                    $price = $product_query->row['price'];
                                                } else {
                                                    if($product_query->row['whole_sale_price'] > 0) {
                                                        $price = $product_query->row['whole_sale_price'];
                                                    } else {
                                                        $price = $product_query->row['price'];
                                                    }
                                                }
					} else {
					    $price = $product_query->row['price'];
					}
					/* Prices with whole_sale_price */
                                      


            $price = ($is_wholesale && ($product_query->row['whole_sale_price'] > 0)) ? $product_query->row['whole_sale_price'] : $product_query->row['price'];


            if($this->hasProducts()){

                    if(($this->getFullPrice() >= $this->config->get('config_whole_sale_price_price')) && ($product_query->row['whole_sale_price'] > 0)){
                        $price = $product_query->row['whole_sale_price'];
                    }  
                    elseif(($this->getFullQuant() >= $this->config->get('config_whole_sale_price_quantity')) && ($product_query->row['whole_sale_price'] > 0)){
                        $price = $product_query->row['whole_sale_price'];
                    }
            }
					
					//MULTI DISCOUNT PACK - start
					$default_price = $price;
					$special_price = 0;
					//MULTI DISCOUNT PACK - stop
					
					
					// Product Discounts
					$discount_quantity = 0;

					foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
						$product_2 = explode(':', $key_2);

						if ($product_2[0] == $product_id) {
							$discount_quantity += $quantity_2;
						}
					}

					$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

					if ($product_discount_query->num_rows) {
						$price = $product_discount_query->row['price'];
					}

					// Product Specials
					$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

					if ($product_special_query->num_rows) {
						$price = $product_special_query->row['price'];
						//MULTI DISCOUNT PACK - start
						$special_price = $price;
						//MULTI DISCOUNT PACK - stop
					}			

					//MULTI DISCOUNT PACK - start
					$category_id = "";
					if(!isset($special_price)){$special_price = false;}
					
					$discount_price = $this->getMultiDiscountPrice($product_id,$default_price,$special_price,$product_query->row['manufacturer_id']);
					if($discount_price){
						$price = $discount_price['special'];
					}
					//MULTI DISCOUNT PACK - stop

					// Reward Points
					$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

					if ($product_reward_query->num_rows) {	
						$reward = $product_reward_query->row['points'];
					} else {
						$reward = 0;
					}

					// Downloads		
					$download_data = array();     		

					$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					foreach ($download_query->rows as $download) {
						$download_data[] = array(
							'download_id' => $download['download_id'],
							'name'        => $download['name'],
							'filename'    => $download['filename'],
							'mask'        => $download['mask'],
							'remaining'   => $download['remaining']
						);
					}

					// Stock
					if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
						$stock = false;
					}

					$recurring = false;
					$recurring_frequency = 0;
					$recurring_price = 0;
					$recurring_cycle = 0;
					$recurring_duration = 0;
					$recurring_trial_status = 0;
					$recurring_trial_price = 0;
					$recurring_trial_cycle = 0;
					$recurring_trial_duration = 0;
					$recurring_trial_frequency = 0;
					$profile_name = '';

					if ($profile_id) {
						$profile_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "profile` `p` JOIN `" . DB_PREFIX . "product_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`product_id` = " . (int)$product_query->row['product_id'] . " JOIN `" . DB_PREFIX . "profile_description` `pd` ON `pd`.`profile_id` = `p`.`profile_id` AND `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$customer_group_id)->row;

						if ($profile_info) {
							$profile_name = $profile_info['name'];

							$recurring = true;
							$recurring_frequency = $profile_info['frequency'];
							$recurring_price = $profile_info['price'];
							$recurring_cycle = $profile_info['cycle'];
							$recurring_duration = $profile_info['duration'];
							$recurring_trial_frequency = $profile_info['trial_frequency'];
							$recurring_trial_status = $profile_info['trial_status'];
							$recurring_trial_price = $profile_info['trial_price'];
							$recurring_trial_cycle = $profile_info['trial_cycle'];
							$recurring_trial_duration = $profile_info['trial_duration'];
						}
					}
					
					//MULTI DISCOUNT PACK - start
					$discount_price = $this->getMultiDiscountPrice($product_id,$option_price,false,$product_query->row['manufacturer_id'],true);
					if(isset($discount_price) AND isset($discount_price['option_price']) AND $option_price > 0){
						$option_price = $discount_price['option_price'];
					}
					//MULTI DISCOUNT PACK - stop

					$this->data[$key] = array(
						'key'                       => $key,
						'product_id'                => $product_query->row['product_id'],
						'name'                      => $product_query->row['name'],
						'model'                     => $product_query->row['model'],
						'shipping'                  => $product_query->row['shipping'],
						'image'                     => $product_query->row['image'],
						'option'                    => $option_data,
						'download'                  => $download_data,
						'quantity'                  => $quantity,
						'minimum'                   => $product_query->row['minimum'],
						'subtract'                  => $product_query->row['subtract'],
						'stock'                     => $stock,
						'price'                     => ($price + $option_price),
						'total'                     => ($price + $option_price) * $quantity,
						'reward'                    => $reward * $quantity,
						'points'                    => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $quantity : 0),
						'tax_class_id'              => $product_query->row['tax_class_id'],
						'weight'                    => ($product_query->row['weight'] + $option_weight) * $quantity,
						'weight_class_id'           => $product_query->row['weight_class_id'],
						'length'                    => $product_query->row['length'],
						'width'                     => $product_query->row['width'],
						'height'                    => $product_query->row['height'],
						'length_class_id'           => $product_query->row['length_class_id'],
						'profile_id'                => $profile_id,
						'profile_name'              => $profile_name,
						'recurring'                 => $recurring,
						'recurring_frequency'       => $recurring_frequency,
						'recurring_price'           => $recurring_price,
						'recurring_cycle'           => $recurring_cycle,
						'recurring_duration'        => $recurring_duration,
						'recurring_trial'           => $recurring_trial_status,
						'recurring_trial_frequency' => $recurring_trial_frequency,
						'recurring_trial_price'     => $recurring_trial_price,
						'recurring_trial_cycle'     => $recurring_trial_cycle,
						'recurring_trial_duration'  => $recurring_trial_duration,
						'time_cancel' 				=> $time_cancel,
						'num_in_pack'				=> $num_in_pack
					);
				} else {
					$this->remove($key);
				}
			}
		}

		return $this->data;
	}

	public function getRecurringProducts(){
		$recurring_products = array();

		foreach ($this->getProducts() as $key => $value) {
			if ($value['recurring']) {
				$recurring_products[$key] = $value;
			}
		}

		return $recurring_products;
	}

	public function add($product_id, $qty = 1, $option, $profile_id = '', $cancel_time = '') {
		$key = (int)$product_id . ':';

		if ($option) {
			$key .= base64_encode(serialize($option)) . ':';
		}  else {
			$key .= ':';
		}

		if ($profile_id) {
			$key .= (int)$profile_id;
		}

		if (((int)$qty && ((int)$qty > 0)) || $cancel_time) {
			if (!isset($this->session->data['cart'][$key])) {
				$this->session->data['cart'][$key] = (int)$qty;
				$this->session->data['time_cancel'][$key] = $cancel_time;
			} else {
				$this->session->data['cart'][$key] += (int)$qty;
				$this->session->data['time_cancel'][$key] = '';
			}
		}

		$this->data = array();
	}

	public function update($key, $qty) {
		if ((int)$qty && ((int)$qty > 0)) {
			$this->session->data['cart'][$key] = (int)$qty;
		} else {
			$this->remove($key);
		}

		$this->data = array();
	}

	public function remove($key) {
		if (isset($this->session->data['cart'][$key])) {
			unset($this->session->data['cart'][$key]);
		}

		$this->data = array();
	}

	public function clear() {
		$this->session->data['cart'] = array();
		$this->data = array();
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function countProducts() {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}		

		return $product_total;
	}

	public function hasProducts() {
		return count($this->session->data['cart']);
	}

	public function hasRecurringProducts(){
		return count($this->getRecurringProducts());
	}

	public function hasStock() {
		$stock = true;

		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
				$stock = false;
			}
		}

		return $stock;
	}

	public function hasShipping() {
		$shipping = false;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$shipping = true;

				break;
			}
		}

		return $shipping;
	}

	public function hasDownload() {
		$download = false;

		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				$download = true;

				break;
			}
		}

		return $download;
		
		
		//MULTI DISCOUNT PACK - start
		
		
		}
		
		
  public function getMultiDiscountPrice($product_id,$price,$special,$manufacturer_id,$option = false){
  //var_dump ($product_id,$special);die;
    $return            = false;
    $discount_available = true;
    
    $setting = $this->getMultiDiscountSetting('setting');
    
    if(isset($setting['status']) AND $setting['status'] == 1){

    $DISCOUNT = array();
    	
    //total
      //if($setting['discount_type'] == 'total'){
        $discount = $this->getMultiDiscountSetting('total');
        $discount_available = $this->isDiscountAvailable($discount['discount_start'],$discount['discount_stop']);
     	if($discount_available || (isset($discount['fulltime']) && $discount['fulltime'] == 1)){
        	$DISCOUNT['total'] = $discount;
     	}
     // }
        
    //manufacturer
      //if($setting['discount_type'] == 'manufacturer'){
        if(isset($manufacturer_id)){
          $discount = $this->getManufacturerDiscountSetting($manufacturer_id);
          if($discount){
            $discount_available = $this->isDiscountAvailable($discount['discount_start'],$discount['discount_stop']);
	        if($discount_available || (isset($discount['fulltime']) && $discount['fulltime'] == 1)){
	        	$DISCOUNT['manufacturer'] = $discount;
	     	}
          }
        }
     // }
      
    //category
      //if($setting['discount_type'] == 'category'){
          $discount = $this->getCategoryDiscountSetting($product_id);
          if($discount){
            $discount_available = $this->isDiscountAvailable($discount['discount_start'],$discount['discount_stop']);
	        if($discount_available || (isset($discount['fulltime']) && $discount['fulltime'] == 1)){
	        	$DISCOUNT['category'] = $discount;
	     	}
          }
      //}
      
    //customer_group
      //if($setting['discount_type'] == 'customer_group'){
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
      //}
    
      /*if(isset($discount['fulltime']) AND $discount['fulltime'] == 1){
        $discount_available = true;
      }*/
      
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
  
  
  public function isDiscountAvailable($date_start,$date_stop){
    $date_start = strtotime($date_start);
    $date_stop  = strtotime($date_stop);
    if(time() >= $date_start AND time() <= $date_stop){
      return true;
    }else{
      return false;
    }
  }  
  
  public function getManufacturerDiscountSetting($manufacturer_id){
  
		$manufacturer_setting = false;
		
		if(isset($manufacturer_id)){
      $query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `group` = 'discount' AND `key` = 'manufacturer'");
      if(!$query->num_rows){
      	return false;
      }
      $manufacturers = unserialize($query->row['value']);
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
  
  
  public function getCategoryDiscountSetting($product_id){
    $product_in_categories = array();
    $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE `product_id` = '".(int)$product_id."'");
    if($query->rows){
      foreach($query->rows as $product_category_id){
        $product_in_categories[] = $product_category_id['category_id'];
      }
    }
  
    $query      = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `group` = 'discount' AND `key` = 'category'");
    if($query->num_rows)
    	$categories = unserialize($query->row['value']);
    else {
    	return array();
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
  
  public function getProductPrice(){
  	Debug::log('WRONG IN DISKOUNT PACK!');
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `group` = 'discount' AND `key` = 'setting'");
	  $price = $query->row['price'];
  		if ((float)$product_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}
		return $price;
  }
  
  public function getMultiDiscountSetting($key){
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `group` = 'discount' AND `key` = '".$key."'");
		if($query->row){
		  return unserialize($query->row['value']);
		}else{
      return false;
    }
  }
  
  public function getCustomerGroupDiscountSetting($customer_group_id){
  	if(!$this->customer->isLogged()){
  		$customer_group_id = 1;
  	}
		$customer_group_setting = false;
		if(isset($customer_group_id)){
      $query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `group` = 'discount' AND `key` = 'customer_group'");
      if($query->num_rows < 1)
      	return false;
      $customer_groups = unserialize($query->row['value']);
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
	
	
	
	
				//MULTI DISCOUNT PACK - stop
		
	}	
}
?>