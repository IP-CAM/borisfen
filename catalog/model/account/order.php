<?php
class ModelAccountOrder extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");


		//die("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';				
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';				
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],				
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],				
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],	
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],				
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],	
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip']
			);
		} else {
			return false;	
		}
	}

	public function getOrders($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}	

		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);	

		return $query->rows;
	}

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}	

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;
	}	

	public function getOrderDownloads($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' ORDER BY name");

		return $query->rows; 
	}	

	public function getTotalOrders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrderProductsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderVouchersByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
	
	public function getOrderStatus($status_id) {
		$query = $this->db->query("SELECT name AS status FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'			");
	
		return $query->row;
	}
	
	public function getOrderForCancel($order_id) {
	
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
	
		return $order_query->row;
	
	}
	
	public function getOrderProductsCancel($order_id, $cancel_product_id, $search ) {
		$sql = "SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'  AND product_id";
		if ($search) {
			$sql .= " = '" . (int)$cancel_product_id . "'";
			$query = $this->db->query($sql);
				
			return $query->row;
		} else {
			$sql .= " <> '" . (int)$cancel_product_id . "' AND quantity <> '0'";
			$query = $this->db->query($sql);
	
			return $query->rows;
		}
	
	}
	
	public function cancelProduct($data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET quantity = '', total = '', date_cancel = NOW() WHERE order_id = '" . (int)$data['order_id'] . "' AND product_id = '" . (int)$data['cancel_product_id'] . "' ");
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$data['order_id'] . "'");
		
		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_total
								 SET
									order_id = '" . (int)$data['order_id']. "',
									code = '" . $this->db->escape($total['code']) . "',
									title = '" . $this->db->escape($total['title']) . "',
									text = '" . $this->db->escape($total['text']) . "',
									`value` = '" . (float)$total['value'] . "',
									sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$data['total'] . "', order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$data['order_id'] . "' ");



		// TODO: Regeneric export file ↓

		$order_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id = '" . $data['order_id'] . "'")->row;
		$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$data['order_id'] . "'");

		// Generate file for order
		$exports = array();

		$exports['order_id'] = $data['order_id'];
		$exports['name'] = str_replace(';', '', $order_info['firstname']);
		$exports['date'] = (new DateTime())->format('Y-m-d H:i:s');
		$exports['products'] = array();

		foreach ($order_product_query->rows as $order_product) {
			$product = array();

			$product['quantity'] = $order_product['quantity'];
			$product['single_total'] = $order_product['total'] / $order_product['quantity'];

			// get real product
			$real_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . $order_product['product_id'] . "'");

			$product['real_price'] = $real_product_query->row['price'];
			$product['discount'] = round(($product['real_price'] - $product['single_total']) / $product['real_price'] * 100, 2);
			$product['sku'] = $real_product_query->row['sku'];
			$product['weight'] = $real_product_query->row['weight'];

			//get division and sklad
			$division_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_division WHERE product_id = '" . $order_product['product_id'] . "'");
			$sklad_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_sklad WHERE product_id = '" . $order_product['product_id'] . "'");

			$product['division'] = isset($division_query->row['division']) ? $division_query->row['division'] : '';
			$product['sklad'] = isset($sklad_query->row['sklad']) ? $sklad_query->row['sklad'] : '';

			$exports['products'][] = $product;
		}

		// Gen csv string
		$content = '<?xml version="1.0" encoding="windows-1251"?>' . "\r\n";
		$content .= "<order id=\"{$exports['order_id']}\">\r\n";
		$content .= "\t<date>{$exports['date']}</date>\r\n";
		$content .= "\t<name>{$exports['name']}</name>\r\n";
		$content .= "\t<products>\r\n";
		foreach($exports['products'] as $product){
			$content .= "\t\t<product>\r\n";
			$content .= "\t\t\t<tovarNo>{$product['sku']}</tovarNo>\r\n";
			$content .= "\t\t\t<quantity>{$product['quantity']}</quantity>\r\n";
			$content .= "\t\t\t<total>{$product['single_total']}</total>\r\n";
			$content .= "\t\t\t<discount>{$product['discount']}</discount>\r\n";
			$content .= "\t\t\t<division>{$product['division']}</division>\r\n";
			$content .= "\t\t\t<sklad>{$product['sklad']}</sklad>\r\n";
			$content .= "\t\t</product>\r\n";
		}
		$content .= "\t</products>\r\n";
		$content .= "</order>";

		$content = iconv("utf-8", "windows-1251", $content);

		//Figure out file name

		$files = scandir(DIR_ORDERS);
		$matches = array();
		foreach($files as $file){
			if(strpos($file, 'order_' . $exports['order_id'] . '_') !== false){
				if(preg_match('/order_' . $exports['order_id'] . '_([0-9]+)\.xml/', $file, $match)){
					$matches[] = $match[1];
				}
			}
		}
		if(count($matches)){
			rsort($matches);
			$index = $matches[0] + 1;
		} else {
			$index = 1;
		}

		// Create file
		file_put_contents(DIR_ORDERS . 'order_' . $data['order_id']  . '_' . $index . '.xml', $content);

	}
	
	public function addOrderHistory($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history
					SET
						order_id = '" . (int)$data['order_id'] . "',
						order_status_id = '" . (int)$data['order_status_id'] . "',
						notify = '1',
						comment = '" . $this->db->escape($data['comment']) . "',
						date_added = NOW()");
	
		$order_info = $this->getOrderForCancel($data['order_id']);
	
		// Отправка письма
		$this->language->load('mail/order');
			
		$subject = sprintf($this->language->get('text_new_subject_cancel'), $order_info['store_name'], $data['order_id']);
			
		$message  = $this->language->get('text_order') . ' ' . $data['order_id'] . "\n";
		$message .= $this->language->get('text_update_date_added') . ' ' . $order_info['date_added'] . "\n\n";
	
		$new_sql = "
            SELECT *
            FROM " . DB_PREFIX . "order_status
            WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'";
		$order_status_query = $this->db->query($new_sql);
			
		if ($order_status_query->num_rows) {
			$message .= $this->language->get('text_new_order_status') . $order_status_query->row['name'] ."\n\n";
		}
			
		if ($data['comment']) {
			$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
		}
			
		if ($order_info['customer_id']) {
			$message .= $this->language->get('text_update_link') . "\n";
			$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $data['order_id'], ENT_QUOTES, 'UTF-8') . "\n\n";
		}
	
		$message .= $this->language->get('text_update_footer');
			
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($order_info['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($order_info['store_name']);
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	
		//Отправка письма
	}
}
?>