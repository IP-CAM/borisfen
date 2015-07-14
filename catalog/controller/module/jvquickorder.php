<?php
class ControllerModuleJVquickorder extends Controller {	
	
	private $version = 'ver 2.75';
	
	private function versioncore() {
	
		$myversioncore = 'unknown_version_core';
	
		if( (VERSION == '1.0.1') || (VERSION == '1.5.1') || (VERSION == '1.5.1.3') ) {
			$myversioncore = 'old_version_core';
		}
		
		if( (VERSION == '1.5.2') || (VERSION == '1.5.2.1') || (VERSION == '1.5.3') || (VERSION == '1.5.3.1') || (VERSION == '1.5.4') || (VERSION == '1.5.4.1') ) {
			$myversioncore = 'new_version_core';
		}
		
		return $myversioncore;
	}
	
	private function inputinfoorder($data) {
		$error_field = $this->language->get('error_field');
		
		if (!empty($data['customer_name'])) {
			$customer_name = $data['customer_name'];
			} else {
				$customer_name = $error_field;
		}
		
		if (!empty($data['customer_phone']) ) {
			$customer_phone = $data['customer_phone'];
			} else {
				$customer_phone = $error_field;
		}	
		
		if (!empty($data['customer_email'])) {
			$customer_email = $data['customer_email'];
			} else {
				$customer_email = $error_field;
		}
		
		if (!empty($data['customer_comment'])) {
			$customer_comment = $data['customer_comment'];
			} else {
				$customer_comment = $error_field;
		}
		
		if (!empty($data['order_product_quantity'])) {
			$order_product_quantity = $data['order_product_quantity'];
			} else {
				
				$this->load->model('catalog/product');
				$product_info = $this->model_catalog_product->getProduct($data['product_id']);
				if ($product_info) {
					if ($product_info['minimum'] < 1) {
					$minimum = 1;
					} else {
						$minimum = $product_info['minimum'];
					}
				}
				$order_product_quantity = $minimum;
		}
		
		$customer_ip = $this->request->server['REMOTE_ADDR'];
		
		$inputinfo_array = array(
			'customer_name' 	=> 	$customer_name,
			'customer_phone'	=>	$customer_phone,
			'customer_email'	=>	$customer_email,
			'customer_comment'	=>	$customer_comment,
			'order_product_quantity'  =>  $order_product_quantity,
			'customer_ip'		=>	$customer_ip
		);
		
		return $inputinfo_array;
	}
		
	protected function index($setting) {
		/*
		$this->render();
		*/
	}

	public function update() {
		$this->data['version'] = $this->version;
		
		$this->language->load('module/jvquickorder');
		$json = array();
		
		if (isset($this->request->post['product_id'])) {
			$this->load->model('catalog/product');
			if(isset($this->request->post['quantity'])) {
				$this->data['quantity'] = (int)$this->request->post['quantity'];
			} else {
				$this->data['quantity'] = 1;
			}
			// Категории
			$categories_of_product = $this->model_catalog_product->getCategories($this->request->post['product_id']);
			if ($categories_of_product) {
			
				$this->data['show_in_category'] = false;
				$config_var_categories = $this->config->get('config_var_category');
				
				foreach ($categories_of_product as $category_of_product) {  
					if ( isset($config_var_categories[$category_of_product['category_id']]) && ($category_of_product['category_id'] == $config_var_categories[$category_of_product['category_id']]) ) {
						$this->data['show_in_category'] = true;
					}	
				}	
			} else {
				$this->data['show_in_category'] = false;
			}
			// Категории
			
			$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);
	
			if ($product_info) {	
			
				//Учитывать кол-во товара и настройку "Заказ при нехватке на складе"
				if ($this->config->get('consider_in_stock') == '1') {
					$this->data['consider_in_stock'] = true;
				}	else {
					$this->data['consider_in_stock'] = false;
				}	

				// Заказ при нехватке на складе
// 				if ($this->config->get('config_stock_checkout') == '1') {
					$this->data['config_stock_checkout'] = true;
// 				}	else {
// 					$this->data['config_stock_checkout'] = false;
// 				}

				if ($product_info['quantity'] > 0) {
					$this->data['instock'] = true;
				}	else {
					$this->data['instock'] = false;
				}		
				
				// Заголовок перед формой 
				if ($this->config->get('title_before_form')) {
					$title_before_form = $this->config->get('title_before_form');
					$this->data['title_before_form'] = $title_before_form[$this->config->get('config_language_id')];
					} else {
						$this->data['title_before_form'] = '';
				}	

				// текст перед формой
				if ($this->config->get('text_before_form')) {
					$text_before_form = $this->config->get('text_before_form');
					$this->data['text_before_form'] = $text_before_form[$this->config->get('config_language_id')];
					} else {
						$this->data['text_before_form'] = '';
				}		
				
				$this->load->model('tool/image');
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], 120, 120);
				} else {
					$image = '';
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
				
				if (!$special) {
					$lastprice = $price;
				} else {
					$lastprice = $special; 
				}
				
				if ($product_info['minimum'] < 1) {
					$minimum = 1;
				} else {
					$minimum = $product_info['minimum'];
				}
				
				$description = strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8'));
				$description = str_ireplace ( '"', '&quot;', $description);
				$description = utf8_substr($description, 0, 1300) . '..';
				
				$shortdescription = utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..';			
				
				$this->data['product'] = array(
					'product_id' => $product_info['product_id'],
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'minimum'    => $minimum,
					'quantity'   => $product_info['quantity'],
					'price'      => $lastprice,
					'special'    => $special,
					'shortdescription' => $shortdescription,
					'description' => $description
				);
				
				// Карусель - картинки
				$this->data['images'] = array();
				$results = $this->model_catalog_product->getProductImages($this->request->post['product_id']);
				foreach ($results as $result) {
					$this->data['images'][] = array(
						'thumb' => $this->model_tool_image->resize($result['image'], 120, 120)
					);
				}
				// Карусель - картинки
			}
			
			// E-Mail, ФИО		
			if ($this->customer->isLogged()) {
				$this->data['Email'] = $this->customer->getEmail();
				$this->data['FirstName'] = $this->customer->getFirstName();
				$this->data['LastName'] = $this->customer->getLastName();
			} else {
				$this->data['Email'] = '';
				$this->data['FirstName'] = '';
				$this->data['LastName'] = '';
			}
			
			$this->data['FullName'] = $this->data['FirstName'] . $this->data['LastName'];		
			if ( (!empty($this->data['FirstName'])) && (!empty($this->data['LastName'])) ) {
				$this->data['FullName'] = $this->data['FirstName'] . ' ' . $this->data['LastName'];
			}
			// E-Mail, ФИО			
			
			$lang_vars = array(
				'heading_title', 'hint_description_heading_text', 
				'legend_text', 
				
				'label_name_text', 'hint_name_heading_text', 'hint_name_descr_text', 'error_name_descr_text',

				'label_phone_text', 'placeholder_phone_text', 'hint_phone_heading_text', 
				'hint_phone_descr_text', 'error_phone_descr_text',
				'error_rangelengthphone_descr_text', 'error_digitsphone_descr_text', 
				
				'label_email_text', 'placeholder_email_text', 'hint_email_heading_text', 
				'hint_email_descr_text', 'error_email_descr_text', 'error_validemail_descr_text',  
				
				'label_comment_text', 'placeholder_comment_text', 'hint_comment_descr_text', 'hint_comment_heading_text',
				'hint_comment_descr_text', 'error_comment_descr_text', 'error_rangelengthcomment_descr_text', 
				
				'label_product_quantity_text', 'placeholder_product_quantity_text', 'hint_product_quantity_heading_text', 
				'hint_product_quantity_descr_text', 'error_product_quantity_descr_text', 'error_digits_prod_quantity_descr_text',
				
				'button_order_text', 'button_close_text', 'button_send_text', 
				
				'success_message_heading_text', 'success_message_body_text', 
				
				'error_message_heading_text', 'error_message_body_text', 
				'error_message_disable_email_sending', 
				'error_message_ordererror_body_text',
				'error_message_unknownerror_body_text', 
				
				'error_message_nostock_body_text', 'error_message_not_work_in_categories_body_text'
				
			);
			
			foreach ($lang_vars as $lang_var) {
				$this->data[$lang_var] = $this->language->get($lang_var);
			}
			$this->data['error_min_prod_quantity_descr_text'] = sprintf($this->language->get('error_min_prod_quantity_descr_text'), $this->data['product']['minimum']);
			$this->data['error_max_prod_quantity_descr_text'] = sprintf($this->language->get('error_max_prod_quantity_descr_text'), $this->data['product']['quantity']);

			// Настройки
			$config_vars = array(
				'show_product_name_price', 'show_product_desc', 'show_product_images', 'type_product_images',
				'show_popover',
				'type_colour_button_quickorder',
				
				'field_user_name_show', 'field_user_name_required',
				'field_user_phone_show', 'field_user_phone_required', 
				'field_email_show', 'field_email_required', 
				'field_comment_show', 'field_comment_required',
				
				'field_product_quantity_show', 'field_product_quantity_required',
				
				'send_email_status', 'type_email',
				
				'order_offon',
				
				'del_system_css_on_show'
			);				
			
			foreach ($config_vars as $config_var) {
				$this->data[$config_var] = $this->config->get($config_var);
			}
			
			$myfield_user_phone_maskedinput = $this->config->get('field_user_phone_maskedinput');
			if ( !empty($myfield_user_phone_maskedinput) ) {
				$this->data['field_user_phone_maskedinput'] = $this->config->get('field_user_phone_maskedinput');
			} else {
				$this->data['field_user_phone_maskedinput'] = '+9 (999) 999-9999';
			}
			// Настройки
			
			$this->load->model('catalog/product');
			$this->data['options'] = array();
			
			// Options
			$product_id = $this->request->post['product_id'];
			foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) { 
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
					$option_value_data = array();
					
					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							} else {
								$price = false;
							}
							
							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}
					
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);					
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);						
				}
			}
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['button_upload'] = $this->language->get('button_upload');		
			// Options
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/jvquickorder.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/jvquickorder.tpl';
			} else {
				$this->template = 'default/template/module/jvquickorder.tpl';
			}
		
		}
		
		$json['output'] = $this->render();			
		$this->response->setOutput(json_encode($json));	
	}	
	
	public function addorder() {
	
		$data = array();
		
		$this->language->load('module/jvquickorder');	
		
		$myinput_info_order = $this->inputinfoorder($this->request->post);
		
		$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');
		
		if ($data['store_id']) {
			$data['store_url'] = $this->config->get('config_url');		
		} else {
			$data['store_url'] = HTTP_SERVER;	
		}
		
		$customer_name = 			$myinput_info_order['customer_name'];
		$customer_phone = 			$myinput_info_order['customer_phone'];
		$customer_email = 			$myinput_info_order['customer_email'];
		$customer_comment = 		$myinput_info_order['customer_comment'];
		$customer_ip = 				$myinput_info_order['customer_ip'];
		$order_product_quantity = 	$myinput_info_order['order_product_quantity'];
		
		
		if ($this->customer->isLogged()) {
			$data['customer_id'] = $this->customer->getId();
			$data['customer_group_id'] = $this->customer->getCustomerGroupId();
			$data['fax'] = $this->customer->getFax();
			
		} else {
			$data['customer_id'] = 0;
			$data['customer_group_id'] = 0;		
			$data['fax'] = '';
		}
		
		$data['firstname'] = '';
		$data['lastname'] = $this->config->get('order_name_in_admin') . $customer_name;
		$data['email'] = $customer_email;
		$data['telephone'] = $customer_phone;
		
		$data['payment_firstname'] = '';
		$data['payment_lastname'] = '';
		$data['payment_company'] = '';
		$data['payment_company_id'] = '';
		$data['payment_tax_id'] = '';
		$data['payment_address_1'] = '';
		$data['payment_address_2'] = '';
		$data['payment_city'] = '';
		$data['payment_postcode'] = '';
		$data['payment_zone'] = '';
		$data['payment_zone_id'] = '';
		$data['payment_country'] = '';
		$data['payment_country_id'] = '';
		$data['payment_address_format'] = '';
			
		$data['payment_method'] = '';
		$data['payment_code'] = '';
		
		$data['shipping_firstname'] =  '';
		$data['shipping_lastname'] =  '';
		$data['shipping_company'] =  '';
		$data['shipping_address_1'] =  '';
		$data['shipping_address_2'] =  '';
		$data['shipping_city'] =  '';
		$data['shipping_postcode'] =  '';
		$data['shipping_zone'] =  '';
		$data['shipping_zone_id'] =  '';
		$data['shipping_country'] =  '';
		$data['shipping_country_id'] =  '';
		$data['shipping_address_format'] =  '';

		$data['shipping_code'] = '';
		$data['shipping_method'] = '';
		
		$product_data = array();
		if (isset($this->request->post['product_id'])) {
		
			$this->load->model('catalog/product');	
			$product_id = $this->request->post['product_id'];
			$product = $this->model_catalog_product->getProduct($product_id);
			
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'), $format = false);
			} else {
				$price = false;
			}
			
			if ((float)$product['special']) {
				$special = $this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax'));
			} else {
				$special = false;
			}
			
			if (!$special) {
				$lastprice = $price;
			} else {
				$lastprice = $special; 
			}
		}
		
		$option_data = array();
		
		/*
		foreach ($product['option'] as $option) {
			if ($option['type'] != 'file') {
				$value = $option['option_value'];	
			} else {
				$value = $this->encryption->decrypt($option['option_value']);
			}	
			
			$option_data[] = array(
				'product_option_id'       => $option['product_option_id'],
				'product_option_value_id' => $option['product_option_value_id'],
				'option_id'               => $option['option_id'],
				'option_value_id'         => $option['option_value_id'],								   
				'name'                    => $option['name'],
				'value'                   => $value,
				'type'                    => $option['type']
			);					
		}
		*/

		$download_data = array();
		
		if ($this->versioncore() == 'old_version_core') {
			$data['reward'] = $product['reward'];
		}	
		
		$product_data[] = array(
			'product_id' => $product['product_id'],
			'name'       => $product['name'],
			'model'      => $product['model'],
			'option'     => $option_data,
			'download'   => $download_data,   //
			'quantity'   => $order_product_quantity,  
			//'subtract'   => $product['subtract'], //
			'price'      => $lastprice,  //
			'total'      => $lastprice * $order_product_quantity,  
			'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
			'reward'     => $product['reward']
		); 
		
		$data['products'] = $product_data; //
		
		$voucher_data = array();
		$data['vouchers'] = $voucher_data;
		
		$total_data = array();			
		$data['totals'] = $total_data; //
		
		$data['comment'] = $customer_comment;
		
		$total = $lastprice * $order_product_quantity;
		$data['total'] = $total;  //
		
		if (isset($this->request->cookie['tracking'])) {
			$this->load->model('affiliate/affiliate');
			
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
			
			if ($affiliate_info) {
				$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
				$data['commission'] = ($total / 100) * $affiliate_info['commission']; 
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
		} else {
			$data['affiliate_id'] = 0;
			$data['commission'] = 0;
		}
		
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency_code'] = $this->currency->getCode();
		$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $customer_ip;
		
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
		} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
		} else {
			$data['forwarded_ip'] = '';
		}
		
		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
		} else {
			$data['user_agent'] = '';
		}
		
		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
		} else {
			$data['accept_language'] = '';
		}
		
		$this->load->model('checkout/order');
		
// 		if ($this->versioncore() == 'old_version_core') {
// 			$this->session->data['order_id'] = $this->model_checkout_order->create($data);
// 		}

// 		if ($this->versioncore() == 'new_version_core') {
			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($data);
// 		}
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('order_order_status_id'), '', false); 
		exit(json_encode(true));
	}
	
	public function SendTextMail() {
        $json = array();

		if (isset($this->request->post['product_id'])) {
		
			$this->language->load('module/jvquickorder');
			$this->load->model('catalog/product');
			
			$product_id = $this->request->post['product_id'];
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			$myinput_info_order = $this->inputinfoorder($this->request->post);
			
			//Mail
			
			//body
			$body_message_admin = $this->language->get('body_message_admin_data_text_product') . "\n";
			$body_message_admin .= $this->language->get('body_message_admin_name_product') . $product_info['name'] . "\n";
			$body_message_admin .= $this->language->get('body_message_admin_href_product') . $this->url->link('product/product', 'product_id=' . $product_info['product_id']) . "\n";
			$body_message_admin .= $this->language->get('body_message_admin_product_quantity') . $myinput_info_order['order_product_quantity'] . "\n";
			
			$body_message_admin .= "\n\n";
			
			$body_message_admin .= $this->language->get('body_message_admin_data_text_customer') . "\n";
			$body_message_admin .= $this->language->get('body_message_admin_customer_name') . $myinput_info_order['customer_name'] . "\n";
			$body_message_admin .= $this->language->get('body_message_admin_customer_phone') . $myinput_info_order['customer_phone'] . "\n";
			$body_message_admin .= $this->language->get('body_message_admin_customer_email') . $myinput_info_order['customer_email'] . "\n";	
			$body_message_admin .= $this->language->get('body_message_admin_customer_ip') . $myinput_info_order['customer_ip'] . "\n";
			$body_message_admin .= $this->language->get('body_message_admin_customer_comment') . $myinput_info_order['customer_comment'] . "\n";

			$body_message_admin .= "\n";
			//body
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			
			$subject = sprintf($this->language->get('heading_title_mail_admin'), $myinput_info_order['customer_name'], $product_info['name']);
			$mail->setSubject(html_entity_decode($subject), ENT_QUOTES, 'UTF-8');
			
			$content = html_entity_decode(sprintf($body_message_admin), ENT_QUOTES, 'UTF-8');
			$mail->setText(strip_tags($content));
				
			// Письмо админу	
			if ( $this->config->get('offon_email_admin') ) {
				$mail->setTo($this->config->get('config_email'));
				$mail->send();
			}
			
			
			// Письмо дополнительным адресам
			$emails = explode(',', $this->config->get('email_additional'));
			
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}	

			// Письмо клиенту				
			if ( $this->config->get('offon_email_customer') && ($this->request->post['customer_email']) ) {

				$body_message_customer = sprintf($this->language->get('body_message_customer_data_text'), $this->config->get('config_name')) . "\n";
				$body_message_customer .= "\n";
				
				$body_message_customer .= $this->language->get('body_message_customer_data_text_product') . "\n";
				$body_message_customer .= $this->language->get('body_message_customer_name_product') . $product_info['name'] . "\n";
				$body_message_customer .= $this->language->get('body_message_customer_href_product') . $this->url->link('product/product', 'product_id=' . $product_info['product_id']) . "\n";
				$body_message_customer .= $this->language->get('body_message_customer_product_quantity') . $myinput_info_order['order_product_quantity'] . "\n";
				$body_message_customer .= "\n";
				
				$body_message_customer .= $this->language->get('text_thanks1') . "\n" . "\n";
				$body_message_customer .= sprintf($this->language->get('text_thanks2'), $this->config->get('config_name')) . "\n";
				
				$body_message_customer .= "\n";	
				
				
				$mail->setTo($this->request->post['customer_email']);  
				
				$subject = sprintf($this->language->get('heading_title_mail_customer'), $product_info['name'], $this->config->get('config_name'));
				$mail->setSubject(html_entity_decode($subject), ENT_QUOTES, 'UTF-8');
				
				$content = html_entity_decode(sprintf($body_message_customer), ENT_QUOTES, 'UTF-8');
				$mail->setText(strip_tags($content));
				
				$mail->send();
			}
		}
		
		// Output
        $this->response->setOutput(json_encode($json));
    }
	
	public function SendHTMLMail() {
        $json = array();

		if (isset($this->request->post['product_id'])) {
		
			$this->language->load('module/jvquickorder');
			$this->load->model('catalog/product');
			
			$product_id = $this->request->post['product_id'];
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$myinput_info_order = $this->inputinfoorder($this->request->post);
			
			//Mail
			
			//template
			$template = new Template();
			
			// Заголовок письма
			$template->data['title'] = sprintf($this->language->get('heading_title_mail_admin'), $myinput_info_order['customer_name'], $product_info['name']);
			$subject = sprintf($this->language->get('heading_title_mail_admin'), $myinput_info_order['customer_name'], $product_info['name']);
			
			$template->data['logo'] = 'cid:' . md5(basename($this->config->get('config_logo')));
			
			$template->data['store_name'] = $this->config->get('config_name');
			$template->data['store_url'] = $this->url->link('common/home', '', 'SSL');
			
			$template->data['body_message_text'] = $this->language->get('body_message_text');
			
			$template->data['data_product_tittle'] = $this->language->get('body_message_admin_data_text_product');
			$template->data['product_name'] = $product_info['name'];
			$template->data['product_link'] = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);

			$this->load->model('tool/image');
			if ($product_info['image']) {
				$image = $this->model_tool_image->resize($product_info['image'], 200,200);
			} else {
				$image = '';
			}
			
			if ($image) {
				$template->data['product_image'] = 'cid:' . md5(basename($image));
			}	else {
				$template->data['product_image'] = '';
			}			
			
			$template->data['shortdescription'] = utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 1600);
			
			$template->data['body_message_admin_data_text_customer'] = $this->language->get('body_message_admin_data_text_customer');
			$template->data['customer_name'] = $this->language->get('body_message_admin_customer_name') . $myinput_info_order['customer_name'];
			$template->data['customer_phone'] = $this->language->get('body_message_admin_customer_phone') . $myinput_info_order['customer_phone'];
			$template->data['customer_email'] = $this->language->get('body_message_admin_customer_email') . $myinput_info_order['customer_email'];	
			$template->data['customer_comment'] = $this->language->get('body_message_admin_customer_comment') . $myinput_info_order['customer_comment'];
			$template->data['customer_ip'] = $this->language->get('body_message_admin_customer_ip') . $myinput_info_order['customer_ip'];
			
			$template->data['body_message_admin_data_text_product'] = $this->language->get('body_message_admin_data_text_product');
			$template->data['order_product_quantity'] = $this->language->get('body_message_admin_product_quantity') . $myinput_info_order['order_product_quantity'];
			
			$template->data['data_store_text'] = $this->language->get('data_store_text');
			$template->data['data_store_name'] = sprintf($this->language->get('data_store_name'), $this->url->link('common/home', '', 'SSL'), $this->config->get('config_name'));
			$template->data['data_store_phone'] = $this->language->get('data_store_phone') . $this->config->get('config_telephone');
			$template->data['data_store_email'] = sprintf($this->language->get('data_store_email'), $this->config->get('config_email'), $this->config->get('config_email') );
			
			$template->data['text_thanks1'] = $this->language->get('text_thanks1');
			$template->data['text_thanks2'] = sprintf($this->language->get('text_thanks2'), $this->config->get('config_name') );
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/jvquickorder_html_mail.tpl')) {
					$html = $template->fetch($this->config->get('config_template') . '/template/module/jvquickorder_html_mail.tpl');
				} else {
					$html = $template->fetch('default/template/module/jvquickorder_html_mail.tpl');
				}
			
			$mail = new Mail(); 
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');			
			
			$mail->setSubject($subject);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			
			$mail->setHtml($html);

			$mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'), md5(basename(DIR_IMAGE . $this->config->get('config_logo'))));
			
			$startimagefilename = strpos ( $image, 'data'); 		// позиция начала имени кэшированного файла с учётом подкаталога
			$imagefilename = substr ($image, $startimagefilename);  // имя кэшированного файла с учётом подкаталога
			$mail->addAttachment(DIR_IMAGE . 'cache/' . $imagefilename, md5(basename($image)));
			
			// Письмо админу	
			if ( $this->config->get('offon_email_admin') ) {
				$mail->setTo($this->config->get('config_email'));
				$mail->send();
			}
			
			// Письмо дополнительным адресам
			$emails = explode(',', $this->config->get('email_additional'));
			
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
					
			// Письмо клиенту				
			if ( $this->config->get('offon_email_customer') && ($this->request->post['customer_email']) ) {				
				$template->data['body_message_text'] = sprintf($this->language->get('body_message_customer_data_text'), $this->config->get('config_name'));
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/jvquickorder_html_mail.tpl')) {
					$html = $template->fetch($this->config->get('config_template') . '/template/module/jvquickorder_html_mail.tpl');
				} else {
					$html = $template->fetch('default/template/module/jvquickorder_html_mail.tpl');
				}

				$mail->setHtml($html);
				
				$subject = sprintf($this->language->get('heading_title_mail_customer'), $product_info['name'], $this->config->get('config_name'));
				$mail->setSubject($subject);
				
				$mail->setTo($this->request->post['customer_email']);  
				$mail->send();
			}
		}
		
		// Output
        $this->response->setOutput(json_encode($json));
    }
	
	public function GetCurrentTemplate() {
	$json = array();
	$json['currenttemplate'] = $this->config->get('config_template');	
	$this->response->setOutput(json_encode($json));	
	}
	
}
?>