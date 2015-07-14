<?php 

class ControllerModuleQuickcheckout extends Controller { 

	private $settings = array();
	private $checkout = array();
	private $texts = array('title', 'tooltip', 'description', 'text');

	public function index(){
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){		
			$this->load_settings();
			if($this->settings['general']['enable']){
	
				//Load Scripts
				$this->document->addScript('catalog/view/javascript/quickcheckout/jquery.timer.js');
				$this->document->addScript('catalog/view/javascript/quickcheckout/tinysort/jquery.tinysort.min.js');
				$this->document->addScript('catalog/view/javascript/quickcheckout/uniform/jquery.uniform.js');
				$this->document->addScript('catalog/view/javascript/quickcheckout/tooltip/tooltip.js');
			
				//Set Breadcrumbs
				$this->data['breadcrumbs'] = array();
			
				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home'),
					'separator' => false
				); 
		
				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_cart'),
					'href'      => $this->url->link('checkout/cart'),
					'separator' => $this->language->get('text_separator')
				);
				
				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
					'separator' => $this->language->get('text_separator')
				);
				
				//Get customer option (Guest, Registration, Company etc)
				if(!$this->customer->isLogged()){
					$this->data['get_login_view'] = $this->get_login_view();
				}else{
					$this->data['get_login_view'] ='';
				}
				//Get customer info
				$this->data['get_payment_address_view'] = $this->get_payment_address_view();
				
				//Get Shipping address
				$this->data['get_shipping_address_view'] = $this->get_shipping_address_view();
				
				//Get shipping method
				$this->data['get_shipping_method_view'] = $this->get_shipping_method_view();
				
				//Get payment method
				$this->data['get_payment_method_view'] = $this->get_payment_method_view();
				
				//Get cart view
				$this->data['get_cart_view'] = $this->get_cart_view();

				//Get payment view
				$this->data['get_payment_view'] = $this->get_payment_view();
				
				//Get confirm view
				$this->data['get_confirm_view'] = $this->get_confirm_view();
				
				$this->data['settings'] = $this->settings;
				$this->data['checkout'] = $this->checkout;
	
				//Get template
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/quickcheckout.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/quickcheckout.tpl';
				} else {
					$this->template = 'default/template/module/quickcheckout.tpl';
				}
				
				//Render all
				return $this->render();
			} //if not enables
		
		}else{
			return false;
		}

	}

	private function load_settings(){
		//load models
		$this->load->model('setting/setting');
		$this->load->model('account/address');
		$this->load->model('setting/extension');
		$this->load->model('account/customer');
		$this->load->model('localisation/country');	
		$this->load->model('quickcheckout/order');
		$this->load->model('tool/image');
		$this->load->model('checkout/coupon');
		
		
		//Load languages
		$this->language->load('checkout/checkout');
		
		//Set language
		$this->data['heading_title'] = $this->language->get('heading_title');
        $this->language->load('checkout/cart');
		$this->language->load('module/quickcheckout');

		//load settings from database
		$result = $this->model_setting_setting->getSetting('quickcheckout', $this->config->get('config_store_id'));
		$this->settings = $result['quickcheckout'];

		//These are default settings (located in system/config/quickcheckout_settings.php)
		$settings = $this->config->get('quickcheckout_settings');
		if(empty($settings)){
			$this->config->load('quickcheckout_settings');
			$settings = $this->config->get('quickcheckout_settings');
		}
		
		//System settings
		$settings['general']['default_email'] = $this->config->get('config_email');
		$settings['step']['payment_address']['fields']['agree']['information_id'] = $this->config->get('config_account_id');
		$settings['step']['payment_address']['fields']['agree']['error'][0]['information_id'] = $this->config->get('config_account_id');
		$settings['step']['confirm']['fields']['agree']['information_id'] = $this->config->get('config_checkout_id');
		$settings['step']['confirm']['fields']['agree']['error'][0]['information_id'] = $this->config->get('config_checkout_id');
		
		if(!empty($this->settings)){
			$this->settings = $this->array_merge_recursive_distinct($settings, $this->settings);
		}else{
			$this->settings = $settings;
		}
			
		//Session 
		if(!empty($this->request->post['cart'])){
			foreach ($this->request->post['cart'] as $key => $value){
				$this->cart->update($key, $value);
			}
			unset($this->request->post['cart']);
		}

		if(isset($this->session->data)){
			$this->checkout = $this->array_merge_recursive_distinct($this->checkout, $this->session->data);
		}

		
		//Post
		if(!empty($this->request->post)){
			$this->checkout= $this->array_merge_recursive_distinct($this->checkout,  $this->request->post);
		}
		
		$this->checkout['min_order'] = ( $this->cart->getTotal() >= $this->settings['general']['min_order']['value'] );
		
		//Load address (will load only if logged in)
		$this->checkout['addresses'] = $this->model_account_address->getAddresses();
		
		//Check if guest checkout is allowed
		$this->checkout['guest_checkout'] = ($this->config->get('config_guest_checkout') && 
										!$this->config->get('config_customer_price') && 
										!$this->cart->hasDownload());
		if($this->customer->isLogged()){	
			$this->checkout['account'] = 'logged';
		} elseif ((!$this->customer->isLogged() && isset($this->checkout['account']) && $this->checkout['account'] == 'logged') || !isset($this->checkout['account'])) {
			$this->checkout['account'] = $this->config->get('config_guest_checkout') && 
									!$this->config->get('config_customer_price') && 
									$this->settings['general']['default_option'] == 'guest' &&
									!$this->cart->hasDownload() ? 'guest' : 'register';
		} 
		
		//Load payment address
		//Shipping required
		if($this->settings['option'][$this->checkout['account']]['shipping_address']['require'] == 1){
			$this->checkout['payment_address']['shipping'] = 0;
		}
		
		if($this->customer->isLogged()){
	
			$this->checkout['payment_address']['exists'] = (isset($this->checkout['payment_address']['exists'])) ? $this->checkout['payment_address']['exists'] : 1;
			
			if(!isset($this->checkout['payment_address']['islogged'])){
				$this->checkout['payment_address']['islogged'] = 1;
				$this->checkout['payment_address']['exists'] = 1;				
			}
			
			
			if(isset($this->session->data['payment_address']['created'])){
				$this->checkout['payment_address']['address_id'] = $this->session->data['payment_address']['created'];			
			}
				
			if($this->checkout['payment_address']['exists']){
				
				// if address exists
				$this->checkout['payment_address']['address_id'] = (isset($this->checkout['payment_address']['address_id'])) ? $this->checkout['payment_address']['address_id'] : key($this->checkout['addresses']);
				
				
				//compatibility with 1.5.2
				$address_found = false;
				
				foreach($this->checkout['addresses'] as $address){
					if ($this->checkout['payment_address']['address_id'] == $address['address_id']) {
						$address_found = true;
						break;
					}
				}
				
				if(!$address_found){
					foreach($this->checkout['addresses'] as $address){
						$this->checkout['payment_address']['address_id'] = $address['address_id'];
						break;
					}
				}
				
				$country_data =  $this->model_account_address->getAddress($this->checkout['payment_address']['address_id']);
				if (is_array($country_data)) $this->checkout['payment_address'] = array_merge($this->checkout['payment_address'], $country_data);
		
			}else{
				// if address new
				$country_data = $this->get_country_data($this->checkout['payment_address']['country_id'],
															$this->checkout['payment_address']['zone_id']);
												
				if (is_array($country_data)) $this->checkout['payment_address'] = array_merge($this->checkout['payment_address'], $country_data);
			}
				
		$this->checkout['payment_address']['shipping'] = 0;	
		
		}elseif(!empty($this->checkout['payment_address'])){
			
			$this->checkout['payment_address']['exists'] = 0;
			$this->checkout['payment_address']['shipping'] = (isset($this->checkout['payment_address']['shipping'])) ? $this->checkout['payment_address']['shipping'] : 0;
	
		}
				
				
			$payment_address = array(
				'country_id' => (isset($this->checkout['payment_address']['country_id']))? $this->checkout['payment_address']['country_id'] : $this->config->get('config_country_id'),
				'firstname' => (isset($this->checkout['payment_address']['firstname']))? $this->checkout['payment_address']['firstname'] : '',
				'lastname' => (isset($this->checkout['payment_address']['lastname']))? $this->checkout['payment_address']['lastname'] : '',
				'email' => (isset($this->checkout['payment_address']['email']))? $this->checkout['payment_address']['email'] : '',
				'telephone' => (isset($this->checkout['payment_address']['telephone']))? $this->checkout['payment_address']['telephone'] : '',
				'fax' => (isset($this->checkout['payment_address']['fax']))? $this->checkout['payment_address']['fax'] : '',
				'company' => (isset($this->checkout['payment_address']['company']))? $this->checkout['payment_address']['company'] : '',
				'customer_group_id' => (isset($this->checkout['payment_address']['customer_group_id']))? $this->checkout['payment_address']['customer_group_id'] : $this->config->get('config_customer_group_id'),
				'company_id' => (isset($this->checkout['payment_address']['company_id']))? $this->checkout['payment_address']['company_id'] : '',
				'tax_id' => (isset($this->checkout['payment_address']['tax_id']))? $this->checkout['payment_address']['tax_id'] : '',
				'address_1' => (isset($this->checkout['payment_address']['address_1']))? $this->checkout['payment_address']['address_1'] : '',
				'address_2' => (isset($this->checkout['payment_address']['address_2']))? $this->checkout['payment_address']['address_2'] : '',
				'postcode' => (isset($this->checkout['payment_address']['postcode']))? $this->checkout['payment_address']['postcode'] : '',
				'city' => (isset($this->checkout['payment_address']['city']))? $this->checkout['payment_address']['city'] : '',
				'zone_id' => (isset($this->checkout['payment_address']['zone_id']))? $this->checkout['payment_address']['zone_id'] : $this->config->get('config_zone_id'),
				'country' => (isset($this->checkout['payment_address']['country']))? $this->checkout['payment_address']['country'] : '',
				'iso_code_2' => (isset($this->checkout['payment_address']['iso_code_2']))? $this->checkout['payment_address']['iso_code_2'] : '',
				'iso_code_3' => (isset($this->checkout['payment_address']['iso_code_3']))? $this->checkout['payment_address']['iso_code_3'] : '',
				'address_format' => (isset($this->checkout['payment_address']['address_format']))? $this->checkout['payment_address']['address_format'] : '',
				'exists' => (isset($this->checkout['payment_address']['exists']))? $this->checkout['payment_address']['exists'] : 0,
				'zone' => (isset($this->checkout['payment_address']['zone']))? $this->checkout['payment_address']['zone'] : '',
				'zone_code' => (isset($this->checkout['payment_address']['zone_code']))? $this->checkout['payment_address']['zone_code'] : '',
				'address_id' => (isset($this->checkout['payment_address']['address_id']))? $this->checkout['payment_address']['address_id'] : 0,
				'shipping' => (isset($this->checkout['payment_address']['shipping']))? $this->checkout['payment_address']['shipping'] : 1,
				);
			$country_data = $this->get_country_data($payment_address['country_id'], $payment_address['zone_id']);
			if (is_array($country_data)) $payment_address = array_merge($payment_address, $country_data);
			
			if(isset($this->checkout['payment_address'])){ 
				if (is_array($payment_address)) $this->checkout['payment_address'] = array_merge($this->checkout['payment_address'], $payment_address); 
			}else{
				$this->checkout['payment_address'] = $payment_address;
			}
		
		$this->checkout['guest']['payment'] = $this->checkout['payment_address'];
		$this->tax->setPaymentAddress($this->checkout['payment_address']['country_id'], $this->checkout['payment_address']['zone_id']);
		
		//Load shipping address
	
		if($this->checkout['payment_address']['shipping'] || !$this->settings['option'][$this->checkout['account']]['shipping_address']['display'] ) {
			$this->checkout['shipping_address'] = $this->checkout['payment_address'];
		}else{

			if($this->customer->isLogged()){

				// if logged in check if address new or exists
				$this->checkout['shipping_address']['exists'] = (isset($this->checkout['shipping_address']['exists'])) ? $this->checkout['shipping_address']['exists'] : 1;
				if(!isset($this->checkout['shipping_address']['islogged'])){
					$this->checkout['shipping_address']['islogged'] = 1;
					$this->checkout['shipping_address']['exists'] = 1;				
				}
			
				if($this->checkout['shipping_address']['exists']){
					// if address exists
					if(!empty($this->checkout['shipping_address']['address_id'])){
						$country_data =  $this->model_account_address->getAddress($this->checkout['shipping_address']['address_id']);
					}else{
						$country_data =  $this->model_account_address->getAddress($this->checkout['payment_address']['address_id']);
						$this->checkout['shipping_address']['address_id'] = $this->checkout['payment_address']['address_id'];
					}
				
					if (is_array($country_data)) $this->checkout['shipping_address'] = array_merge($this->checkout['shipping_address'], $country_data);
			
				}else{
					// if address new
					$country_data = $this->get_country_data($this->checkout['shipping_address']['country_id'],
															$this->checkout['shipping_address']['zone_id']);
					
					
					if (is_array($country_data)) $this->checkout['shipping_address'] = array_merge($this->checkout['shipping_address'], $country_data);
				}

			}
			
			if(!empty($this->checkout['shipping_address'])){
				// shipping address is empty
				
			}
			
			$shipping_address = array(
				'country_id' => (isset($this->checkout['shipping_address']['country_id']))? $this->checkout['shipping_address']['country_id'] : $this->config->get('config_country_id'),
				'firstname' => (isset($this->checkout['shipping_address']['firstname']))? $this->checkout['shipping_address']['firstname'] : '',
				'lastname' => (isset($this->checkout['shipping_address']['lastname']))? $this->checkout['shipping_address']['lastname'] : '',
				'company' => (isset($this->checkout['shipping_address']['company']))? $this->checkout['shipping_address']['company'] : '',
				'company_id' => (isset($this->checkout['shipping_address']['company_id']))? $this->checkout['shipping_address']['company_id'] : '',
				'tax_id' => (isset($this->checkout['shipping_address']['tax_id']))? $this->checkout['shipping_address']['tax_id'] : '',
				'address_1' => (isset($this->checkout['shipping_address']['address_1']))? $this->checkout['shipping_address']['address_1'] : '',
				'address_2' => (isset($this->checkout['shipping_address']['address_2']))? $this->checkout['shipping_address']['address_2'] : '',
				'postcode' => (isset($this->checkout['shipping_address']['postcode']))? $this->checkout['shipping_address']['postcode'] : '',
				'city' => (isset($this->checkout['shipping_address']['city']))? $this->checkout['shipping_address']['city'] : '',
				'zone_id' => (isset($this->checkout['shipping_address']['zone_id']))? $this->checkout['shipping_address']['zone_id'] : $this->config->get('config_zone_id'),
				'country' => (isset($this->checkout['shipping_address']['country']))? $this->checkout['shipping_address']['country'] : '',
				'iso_code_2' => (isset($this->checkout['shipping_address']['iso_code_2']))? $this->checkout['shipping_address']['iso_code_2'] : '',
				'iso_code_3' => (isset($this->checkout['shipping_address']['iso_code_3']))? $this->checkout['shipping_address']['iso_code_3'] : '',
				'address_format' => (isset($this->checkout['shipping_address']['address_format']))? $this->checkout['shipping_address']['address_format'] : '',
				'zone' => (isset($this->checkout['shipping_address']['zone']))? $this->checkout['shipping_address']['zone'] : '',
				'zone_code' => (isset($this->checkout['shipping_address']['zone_code']))? $this->checkout['shipping_address']['zone_code'] : '',
				'exists' => (isset($this->checkout['shipping_address']['exists']))? $this->checkout['shipping_address']['exists'] : 0,
				'address_id' => (isset($this->checkout['shipping_address']['address_id']))? $this->checkout['shipping_address']['address_id'] : $this->checkout['payment_address']['address_id'],
				);
			$country_data = $this->get_country_data($shipping_address['country_id'], $shipping_address['zone_id']);
			if (is_array($country_data)) $shipping_address = array_merge($shipping_address, $country_data);
			
			if(isset($this->checkout['shipping_address'])){ 
				if (is_array($shipping_address)) $this->checkout['shipping_address'] = array_merge($this->checkout['shipping_address'], $shipping_address); 
			}else{
				$this->checkout['shipping_address'] = $shipping_address;
			}

		}
		$this->checkout['guest']['shipping'] = $this->checkout['shipping_address'];
		$this->tax->setShippingAddress($this->checkout['shipping_address']['country_id'], $this->checkout['shipping_address']['zone_id']);
		
		//Load shipping methods
		
			$this->checkout['shipping_methods'] = $this->get_shipping_methods($this->checkout['shipping_address']);
		
			if(!empty($this->checkout['shipping_methods'])){
				$first = current($this->checkout['shipping_methods']);
				$first = (is_array($first['quote'])) ? current($first['quote']) : $first['quote'];
			
				$shipping = explode('.', $this->settings['step']['shipping_method']['default_option']);
				$default_shipping_method = (isset($this->checkout['shipping_methods'][$shipping[0]]['quote'])) ? current($this->checkout['shipping_methods'][$shipping[0]]['quote']): $first;
			}else{
				$default_shipping_method = null;
			}
		
		//Load shipping method
		if(isset($this->request->post['shipping_method'])){
			$shipping = explode('.', $this->request->post['shipping_method']);
			$this->checkout['shipping_method'] = (isset($this->checkout['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) ? $this->checkout['shipping_methods'][$shipping[0]]['quote'][$shipping[1]] : $default_shipping_method;
		}
				
		if(!isset($this->checkout['shipping_method']['code']) || 
			!isset($this->checkout['shipping_method']['title']) ||
			!isset($this->checkout['shipping_method']['cost'])){
			$this->checkout['shipping_method'] = $default_shipping_method;	
		}
		
		//Load payment methods
		
		$this->checkout['payment_methods'] = $this->get_payment_methods($this->checkout['payment_address']);
	
		if(!empty($this->checkout['payment_methods'])){
			$first = current($this->checkout['payment_methods']);
			$default_payment_method = (isset($this->checkout['payment_methods'][$this->settings['step']['payment_method']['default_option']])) ? $this->checkout['payment_methods'][$this->settings['step']['payment_method']['default_option']] : $first;
		}else{
			$default_payment_method = array(
				'code' => 'cod',
				'title' => 'Доставка при оплате',
				'sort_order' => 5,
			);
		}
		
		//Load payment method
		if(isset($this->request->post['payment_method'])){
		$this->checkout['payment_method'] = (isset($this->checkout['payment_methods'][$this->request->post['payment_method']]))? $this->checkout['payment_methods'][$this->request->post['payment_method']] : $default_payment_method; 
		}
		
		if(!isset($this->checkout['payment_method']['code']) || 
			!isset($this->checkout['payment_method']['title']) ||
			!isset($this->checkout['payment_method']['sort_order'])){
			$this->checkout['payment_method'] = $default_payment_method; 	
		}

		//if order not created yet
		if(!isset($this->checkout['order_id'])){
			
			$this->get_total_data($total_data, $total, $taxes);
			$data = array();
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}
			if (isset($this->request->cookie['tracking'])) {
				$this->load->model('affiliate/affiliate');
				
				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
				$subtotal = $this->cart->getSubTotal();
				
				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
					$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
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
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			
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
						
			$data['total'] = $total;
			
			if(preg_match("/1.5.1/i", VERSION)){
			$this->checkout['order_id'] = $this->model_quickcheckout_order->addOrder151($data);
			}else{
			$this->checkout['order_id'] = $this->model_quickcheckout_order->addOrder($data);
			}	
		}
		
		//Set new session
		$this->checkout = array_merge($this->session->data, $this->checkout);	
		$this->session->data = $this->checkout;
	}

	/*
	*	Get login view
	*/
	private function get_login_view(){
		//Load languages

		$this->data['text_checkout_option'] =  $this->language->get('text_checkout_option');
		$this->data['text_new_customer'] = $this->language->get('text_new_customer');
		$this->data['text_new_guest'] = $this->language->get('text_new_guest');
		$this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_register'] = $this->language->get('text_register');
		$this->data['text_guest'] = $this->language->get('text_guest');
		$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$this->data['text_register_account'] = $this->language->get('text_register_account');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_login'] = $this->language->get('button_login');
		
		//Check if guest checkout is allowed
		$this->data['guest_checkout'] = $this->checkout['guest_checkout'];
		
		//Get Sellected account
		$this->data['account'] = $this->checkout['account'];
		
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		
		$this->data['settings'] = $this->settings;
		
		$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->checkout['account']]['login'], $this->settings['step']['login']);
		
		$lang = $this->language_merge($this->data['data']['option'], $this->texts);
		$this->data['data']['option'] = $this->array_merge_recursive_distinct($this->data['data']['option'], $lang);
		
			
		//Get template
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/login.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/quickcheckout/login.tpl';
		} else {
			$this->template = 'default/template/quickcheckout/login.tpl';
		}
		
		return $this->render();
	}

/*
*	Validate login
*/
	public function login_validate() {
		//$this->load_settings();
		$this->language->load('checkout/checkout');
		
		$json = array();
		if ($this->customer->isLogged()) {
		//	$json['reload'] = true;		
		}
		
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			//$json['reload'] = true;
		}	
		
		if (!$json) {
			if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				$json['error']['warning'] = $this->language->get('error_login');
			}
		
			$this->load->model('account/customer');
		
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
			
			if ($customer_info && !$customer_info['approved']) {
				$json['error']['warning'] = $this->language->get('error_approved');
			}		
		}
		
		if (!$json) {
			unset($this->session->data['guest']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['shipping_address']);
			
			// Default Addresses
			$this->load->model('account/address');
			
			$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());
									
			if ($address_info) {
				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_country_id'] = $address_info['country_id'];
					$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
					$this->session->data['shipping_postcode'] = $address_info['postcode'];	
				}
				
				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_country_id'] = $address_info['country_id'];
					$this->session->data['payment_zone_id'] = $address_info['zone_id'];
				}
			} else {
				unset($this->session->data['shipping_country_id']);	
				unset($this->session->data['shipping_zone_id']);	
				unset($this->session->data['shipping_postcode']);
				unset($this->session->data['payment_country_id']);	
				unset($this->session->data['payment_zone_id']);	
			}					
				
			$json['reload'] = true;
		}
					
		$this->response->setOutput(json_encode($json));		
	}
	/*
	*	Get customer info view
	*/
	private function get_payment_address_view(){

		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_address'] = $this->language->get('text_your_address');
		$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
						
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');		
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');

		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['customer_groups'] = array();
		
		//Get registrated address
		$this->data['addresses'] = $this->checkout['addresses'];
		
		
		//Payment address fields
		$payment_address = $this->array_merge_recursive_distinct($this->settings['step']['payment_address'], $this->settings['option'][$this->checkout['account']]['payment_address']);

		$sort_order = array(); 
		foreach ($payment_address['fields'] as $key => $value) {
			if(isset($value['sort_order'])){
      			$sort_order[$key] = $value['sort_order'];
			}else{
				unset($payment_address['fields'][$key]);
				}
    	}
		array_multisort($sort_order, SORT_ASC, $payment_address['fields']);

		$this->data['payment_address'] = $payment_address;
		
		if(isset($this->checkout['payment_address'])){
			foreach($this->checkout['payment_address'] as $key => $value){
				if(isset($this->data['payment_address']['fields'][$key])){
					$this->data['payment_address']['fields'][$key]['value'] = $value;
				}
			}
		}
		
		$lang = $this->language_merge($this->data['payment_address'], $this->texts);
		$this->data['payment_address'] = $this->array_merge_recursive_distinct($this->data['payment_address'], $lang);
	

		$this->data['payment_address']['fields']['newsletter']['title'] = sprintf($this->data['payment_address']['fields']['newsletter']['title'], $this->config->get('config_name'));
		$this->data['payment_address']['exists'] = $this->checkout['payment_address']['exists'];
		if($this->data['payment_address']['exists']){
			unset($this->session->data['payment_address']['created']);
			unset($this->checkout['payment_address']['created']);	
		}
		if(isset($this->checkout['payment_address']['registered'])) {
			$this->data['payment_address']['exists'] = 1;
			unset($this->session->data['payment_address']['registered']);
			unset($this->checkout['payment_address']['registered']);	
		}
		$this->data['payment_address']['address_id'] = $this->checkout['payment_address']['address_id'];
			
		//Customer group
		if (is_array($this->config->get('config_customer_group_display'))) {
			
			$this->load->model('account/customer_group');
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			foreach ($customer_groups  as $customer_group) {
				
				//customer_group_id
				$customer_group['value'] = $customer_group['customer_group_id'];
				//unset($customer_group['customer_group_id']);
				
				//name
				$customer_group['title'] = $customer_group['name'];
				//unset($customer_group['name']);

				if (in_array($customer_group['value'], $this->config->get('config_customer_group_display'))) {
					$this->data['payment_address']['fields']['customer_group_id']['options'][] = $customer_group;
				}
			}
		}
		
		if (isset($this->checkout['payment_address']['customer_group_id'])) {
			$this->data['payment_address']['fields']['customer_group_id']['value']= $this->checkout['payment_address']['customer_group_id'];
		} else {
			$this->data['payment_address']['fields']['customer_group_id']['value'] = $this->config->get('config_customer_group_id');
		}
		
		//Country
    	if (isset($this->checkout['payment_address']['country_id'])) {
			$this->data['payment_address']['fields']['country_id']['value'] = $this->checkout['payment_address']['country_id'];
		} else {
			$this->data['payment_address']['fields']['country_id']['value'] = $this->config->get('config_country_id');
		}

		$this->data['payment_address']['fields']['country_id']['options'] = $this->model_localisation_country->getCountries();
		
		$options = array();
		foreach ($this->data['payment_address']['fields']['country_id']['options'] as $option){
			$option['value'] = $option['country_id']; 
			//unset($option['country_id']);
			$options[] = $option;
			}
		$this->data['payment_address']['fields']['country_id']['options'] = $options;
		
		//Require shipping
		$this->data['shipping_required'] = $this->cart->hasShipping() && $this->settings['option'][$this->checkout['account']]['shipping_address']['display'];
		

		
		$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->checkout['account']]['payment_address'],$this->settings['step']['payment_address']);
		
		$lang = $this->language_merge($this->data['data'], $this->texts);
		$this->data['data'] = $this->array_merge_recursive_distinct($this->data['data'], $lang);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/payment_address.tpl')) {
        	$this->template = $this->config->get('config_template') . '/template/quickcheckout/payment_address.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/payment_address.tpl';
		}

		return $this->render();	
		
	}
	
	
	/*
	*	Get shipping address view
	*/
	private function get_shipping_address_view(){
		//Setting language
		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
	
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['addresses'] = $this->checkout['addresses'];
		
		//Shippping address fields
		$shipping_address = $this->array_merge_recursive_distinct($this->settings['step']['shipping_address'], $this->settings['option'][$this->checkout['account']]['shipping_address']);

		$sort_order = array(); 
		foreach ($shipping_address['fields'] as $key => $value) {
			if(isset($value['sort_order'])){
      			$sort_order[$key] = $value['sort_order'];
			}else{
				unset($shipping_address['fields'][$key]);
			}
    	}
		array_multisort($sort_order, SORT_ASC, $shipping_address['fields']);

		$this->data['shipping_address'] = $shipping_address;


		if(isset($this->checkout['shipping_address'])){
			foreach($this->checkout['shipping_address'] as $key => $value){
				if(isset($this->data['shipping_address']['fields'][$key])){
					$this->data['shipping_address']['fields'][$key]['value'] = $value;
				}
			}
		}
				
		$lang = $this->language_merge($this->data['shipping_address']['fields'], $this->texts);
		$this->data['shipping_address']['fields'] = $this->array_merge_recursive_distinct($this->data['shipping_address']['fields'], $lang);
		
		$this->data['shipping_address']['exists'] = $this->checkout['shipping_address']['exists'];
		$this->data['shipping_address']['address_id'] = $this->checkout['shipping_address']['address_id'];
		$this->data['payment_address']['shipping'] = $this->checkout['payment_address']['shipping'];
		if($this->data['shipping_address']['exists']){
			unset($this->session->data['shipping_address']['created']);
			unset($this->checkout['shipping_address']['created']);	
		}
		if(isset($this->session->data['shipping_address']['registered'])) {
			$this->data['shipping_address']['exists'] = 1;
			unset($this->session->data['shipping_address']['registered']);
			unset($this->checkout['shipping_address']['registered']);	
		}
	
		//Country
    	if (isset($this->checkout['shipping_address']['country_id'])) {
			$this->data['shipping_address']['fields']['country_id']['value'] = $this->checkout['shipping_address']['country_id'];
		} else {
			$this->data['shipping_address']['fields']['country_id']['value'] = $this->config->get('config_country_id');
		}

		$this->data['shipping_address']['fields']['country_id']['options'] = $this->model_localisation_country->getCountries();
		
		$options = array();
		foreach ($this->data['shipping_address']['fields']['country_id']['options'] as $option){
			$option['value'] = $option['country_id']; 
			$options[] = $option;
			}
		$this->data['shipping_address']['fields']['country_id']['options'] = $options;

		//Require shipping
		$this->data['shipping_required'] = $this->cart->hasShipping() && $this->settings['option'][$this->checkout['account']]['shipping_address']['display'];
		
		$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->checkout['account']]['shipping_address'],$this->settings['step']['shipping_address']);
		
		$lang = $this->language_merge($this->data['data'], $this->texts);
		$this->data['data'] = $this->array_merge_recursive_distinct($this->data['data'], $lang);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/shipping_address.tpl')) {
        	$this->template = $this->config->get('config_template') . '/template/quickcheckout/shipping_address.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/shipping_address.tpl';
		}
	
		return $this->render();
	}
		
	
	/*
	*	Get shipping method view
	*/
	private function get_shipping_method_view(){
		
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		
		if (empty($this->session->data['shipping_methods'])) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
		}	
					
		if (isset($this->checkout['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->checkout['shipping_methods']; 
		} else {
			$this->data['shipping_methods'] = array();
		}
		
		if (isset($this->checkout['shipping_method']['code'])) {
			$this->data['code'] = $this->checkout['shipping_method']['code'];
		} else {
			$this->data['code'] = '';
		}
	
		$this->data['settings'] = $this->settings;

		$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->checkout['account']]['shipping_method'],$this->settings['step']['shipping_method']);
		
		$lang = $this->language_merge($this->data['data'], $this->texts);
		$this->data['data'] = $this->array_merge_recursive_distinct($this->data['data'], $lang);

		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/shipping_method.tpl')) {
          $this->template = $this->config->get('config_template') . '/template/quickcheckout/shipping_method.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/shipping_method.tpl';
		}
		
		return $this->render();
	}
	
	
	/*
	*	Get payment method view
	*/
	private function get_payment_method_view(){
				
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
   
		if (empty($this->session->data['payment_methods'])) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
		}	

		if (isset($this->session->data['payment_methods'])) {
			$this->data['payment_methods'] = $this->session->data['payment_methods']; 
		} else {
			$this->data['payment_methods'] = array();
		}
	  
		if (isset($this->session->data['payment_method']['code'])) {
			$this->data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$this->data['code'] = '';
		}

		$this->data['settings'] = $this->settings;
		
		$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->checkout['account']]['payment_method'],$this->settings['step']['payment_method']);
		
		$lang = $this->language_merge($this->data['data'], $this->texts);
		$this->data['data'] = $this->array_merge_recursive_distinct($this->data['data'], $lang);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/payment_method.tpl')) {
        	$this->template = $this->config->get('config_template') . '/template/quickcheckout/payment_method.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/payment_method.tpl';
		}
		
		return $this->render();
	}
		
	/*
	*	Get cart view
	*/
	private function get_cart_view(){
		if($this->cart->getProducts() || !empty($this->session->data['vouchers'])){
			$this->get_total_data($total_data, $total, $taxes);
			$points = $this->customer->getRewardPoints();
	
			$points_total = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}	
			
			if(!$this->checkout['min_order']){
				$this->data['error']['error_min_order'] = sprintf($this->settings['general']['min_order']['text'][(int)$this->config->get('config_language_id')], $this->currency->format($this->settings['general']['min_order']['value']));
			}
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['text_comments'] = $this->language->get('text_comments');
			$this->data['text_use_coupon'] = $this->language->get('text_use_coupon');
			$this->data['text_use_voucher'] = $this->language->get('text_use_voucher');
			$this->data['text_use_reward'] = sprintf($this->language->get('text_use_reward'), $points);
			$this->data['coupon_status'] = ( $this->settings['option'][$this->checkout['account']]['cart']['option']['coupon']['display'] && $this->config->get('coupon_status'));
			$this->data['voucher_status'] = ( $this->settings['option'][$this->checkout['account']]['cart']['option']['voucher']['display'] && $this->config->get('voucher_status'));
			$this->data['reward_status'] = ( $this->settings['option'][$this->checkout['account']]['cart']['option']['reward']['display'] && $points && $points_total && $this->config->get('reward_status'));
	
			$this->data['products'] = array();
			
			
			/*1.5.1x*/
			if(preg_match("/1.5.1/i", VERSION)){
			$this->language->load('total/coupon');
			$this->data['text_use_coupon'] = $this->language->get('entry_coupon');
			$this->language->load('total/voucher');
			$this->data['text_use_voucher'] = $this->language->get('heading_title');
			$this->language->load('total/reward');
			$this->data['text_use_reward'] = sprintf($this->language->get('entry_reward'), $points );
			
			}
			
			
			foreach ($this->checkout['cart'] as $key => $value) {
				$this->cart->update($key, $value);
			}
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
										
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}  
				
	 			if ($product['image']) {
					$thumb = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$thumb = '';
				}

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->settings['general']['cart_image_size']['width'], $this->settings['general']['cart_image_size']['height']);
				} else {
					$image = '';
				}

				$this->data['products'][] = array(
					'product_id' => $product['key'],
					'weight' => $product['weight'],
					'num_in_pack' => $product['num_in_pack'],
					'thumb'    	 => $thumb,
					'image'    	 => $image,
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'stock'    	 => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'subtract'   => $product['subtract'],
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				); 
			} 
			
			// Gift Voucher
			$this->data['vouchers'] = array();
			
			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$this->data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'])
					);
				}
			}  

			if (!$this->cart->hasStock() && $this->config->get('config_stock_warning')) {
				if(!$this->config->get('config_stock_checkout')){
      				$this->data['error']['error_stock'] = $this->language->get('error_stock');	
				}
			} 
			
			$this->data['coupon_status'] = $this->config->get('coupon_status');
			
			if (isset($this->request->post['coupon'])) {
				$this->data['coupon'] = $this->request->post['coupon'];			
			} elseif (isset($this->session->data['coupon'])) {
				$this->data['coupon'] = $this->session->data['coupon'];
			} else {
				$this->data['coupon'] = '';
			}
			
			$this->data['voucher_status'] = $this->config->get('voucher_status');
			
			if (isset($this->request->post['voucher'])) {
				$this->data['voucher'] = $this->request->post['voucher'];				
			} elseif (isset($this->session->data['voucher'])) {
				$this->data['voucher'] = $this->session->data['voucher'];
			} else {
				$this->data['voucher'] = '';
			}
			
			$this->data['reward_status'] = ($points && $points_total && $this->config->get('reward_status'));
			
			if (isset($this->request->post['reward'])) {
				$this->data['reward'] = $this->request->post['reward'];				
			} elseif (isset($this->session->data['reward'])) {
				$this->data['reward'] = $this->session->data['reward'];
			} else {
				$this->data['reward'] = '';
			}
		
			$this->data['totals'] = $total_data;
	
			$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->checkout['account']]['cart'], $this->settings['step']['cart']);
			$this->data['settings'] = $this->settings;
			$lang = $this->language_merge($this->data['data']['option'], $this->texts);
			$this->data['data']['option'] = $this->array_merge_recursive_distinct($this->data['data']['option'], $lang);
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/quickcheckout/cart.tpl';
			}else{
				$this->template = 'default/template/quickcheckout/cart.tpl';
			}	
			return $this->render();	
		}else{
			return false;
		}
	}
	/*
	*	Get payment view
	*/

	private function get_payment_view(){
		if($this->cart->getProducts() || !empty($this->session->data['vouchers'])){
		
		$this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/payment.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/quickcheckout/payment.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/payment.tpl';
		}	
		return $this->render();	
	}else{
		return false;
		}
	}

	/*
	*	Get confirm view
	*/
	private function get_confirm_view(){
		if($this->cart->getProducts() || !empty($this->session->data['vouchers'])){
	
			$confirm = $this->array_merge_recursive_distinct($this->settings['option'][$this->checkout['account']]['confirm'],$this->settings['step']['confirm']);
	
			$sort_order = array(); 
			foreach ($confirm['fields'] as $key => $value) {
				if(isset($value['sort_order'])){
					$sort_order[$key] = $value['sort_order'];
				}else{
					unset($confirm['fields'][$key]);
				}
			}
			array_multisort($sort_order, SORT_ASC, $confirm['fields']);
	
			$this->data['confirm'] = $confirm;
	
			if(isset($this->checkout['confirm'])){
				foreach($this->checkout['confirm'] as $key => $value){
					if(isset($this->data['confirm']['fields'][$key])){
						$this->data['confirm']['fields'][$key]['value'] = $value;
					}
				}
			}
				
			$lang = $this->language_merge($this->data['confirm']['fields'], $this->texts);
			$this->data['confirm']['fields'] = $this->array_merge_recursive_distinct($this->data['confirm']['fields'], $lang);
					

		$this->update_order();
		
		$this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');	

		$this->data['data'] = $this->array_merge_recursive_distinct($this->settings['option'][$this->checkout['account']]['confirm'], $this->settings['step']['confirm']);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/confirm.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/quickcheckout/confirm.tpl';
		}else{
			$this->template = 'default/template/quickcheckout/confirm.tpl';
		}	
		return $this->render();	
	}else{
		return false;
		}
	}
	/*
	*	Confirm order
	*/
	public function confirm_order(){
		
		$this->load_settings();
		
		$this->get_total_data($total_data, $total, $taxes);
		$data = array();
			
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}

			if(!$this->checkout['payment_address']['email']){
				$this->checkout['payment_address']['email'] = $this->settings['general']['default_email'];
			}
	
			if($this->customer->isLogged() && $this->checkout['payment_address']['exists'] == 0 ){
				if(isset($this->session->data['payment_address']['created'])){
					$this->model_account_address->editAddress($this->checkout['payment_address']['created'], $this->checkout['payment_address']);
					//$this->checkout['payment_address']['address_id'] = $this->checkout['payment_address']['created'];
				}else{
					$this->checkout['payment_address']['address_id'] = $this->model_account_address->addAddress($this->checkout['payment_address']);
					$this->session->data['payment_address']['created'] = $this->checkout['payment_address']['address_id'];
				}
				
				$this->session->data['payment_address']['address_id'] = $this->checkout['payment_address']['address_id'];

			}
			
			if($this->customer->isLogged() && $this->checkout['shipping_address']['exists'] == 0 && $this->settings['option'][$this->checkout['account']]['shipping_address']['display']){
				if(isset($this->session->data['shipping_address']['created'])){
					$this->model_account_address->editAddress($this->checkout['shipping_address']['created'], $this->checkout['shipping_address']);
				}else{
					$this->checkout['shipping_address']['address_id'] = $this->model_account_address->addAddress($this->checkout['shipping_address']);
					$this->session->data['shipping_address']['created'] = $this->checkout['shipping_address']['address_id'];
				}
				$this->session->data['shipping_address']['address_id'] = $this->checkout['shipping_address']['address_id'];
			}

			if($this->checkout['account'] == 'register'){
				$this->create_customer($this->checkout['payment_address']);
				$this->customer->login($this->checkout['payment_address']['email'], $this->checkout['payment_address']['password']);
				$this->session->data['payment_address']['registered'] = 1;
				$this->session->data['shipping_address']['registered'] = 1;
				$this->session->data['shipping_address']['exists'] = 1;
				if(!$this->checkout['payment_address']['shipping'] && $this->settings['option'][$this->checkout['account']]['shipping_address']['display']){
					$this->checkout['shipping_address']['address_id'] = $this->model_account_address->addAddress($this->checkout['shipping_address']);	
					$this->session->data['shipping_address']['address_id'] = $this->checkout['shipping_address']['address_id'];
				}
			}	
			
			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();


			} elseif (isset($this->checkout['payment_address'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->checkout['payment_address']['customer_group_id'];
				$data['firstname'] = $this->checkout['payment_address']['firstname'];
				$data['lastname'] = $this->checkout['payment_address']['lastname'];
				$data['email'] = $this->checkout['payment_address']['email'];
				$data['telephone'] = $this->checkout['payment_address']['telephone'];
				$data['fax'] = $this->checkout['payment_address']['fax'];
			}
			
					
		

			$payment_address = $this->checkout['payment_address'];
		
			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];	
			$data['payment_company'] = $payment_address['company'];	
			$data['payment_company_id'] = $payment_address['company_id'];	
			$data['payment_tax_id'] = $payment_address['tax_id'];	
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
		
			if (isset($this->checkout['payment_method']['title'])) {
				$data['payment_method'] = $this->checkout['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
			
			if (isset($this->checkout['payment_method']['code'])) {
				$data['payment_code'] = $this->checkout['payment_method']['code'];
			} else {
				$data['payment_code'] = '';
			}
						
			if ($this->cart->hasShipping()) {
				$shipping_address = $this->checkout['shipping_address'];
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];	
				$data['shipping_company'] = $shipping_address['company'];	
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
				if (isset($this->checkout['shipping_method']['title'])) {
					$data['shipping_method'] = $this->checkout['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}
				
				if (isset($this->checkout['shipping_method']['code'])) {
					$data['shipping_code'] = $this->checkout['shipping_method']['code'];
				} else {
					$data['shipping_code'] = '';
				}				
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';	
				$data['shipping_company'] = '';	
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}
			
			$product_data = array();
		
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
	
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
	 
				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				); 
			}
			
			// Gift Voucher
			$voucher_data = array();
			
			if (!empty($this->checkout['vouchers'])) {
				foreach ($this->checkout['vouchers'] as $voucher) {
					$voucher_data[] = array(
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],						
						'amount'           => $voucher['amount']
					);
				}
			}  
						
			$data['products'] = $product_data;
			$data['vouchers'] = $voucher_data;
			$data['totals'] = $total_data;
			$data['comment'] = $this->checkout['confirm']['comment'];
			$data['total'] = $total;
			
			if (isset($this->request->cookie['tracking'])) {
				$this->load->model('affiliate/affiliate');
				
				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
				$subtotal = $this->cart->getSubTotal();
				
				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
					$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
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
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			
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
			if(preg_match("/1.5.2/i", VERSION)){
				$this->model_quickcheckout_order->updateOrder152($this->checkout['order_id'], $data);
			}elseif(preg_match("/1.5.1/i", VERSION)){
				$this->model_quickcheckout_order->updateOrder151($this->checkout['order_id'], $data);
			}else{
				$this->model_quickcheckout_order->updateOrder($this->checkout['order_id'], $data);
			}
		}
	
	/*
	*	Update order
	*/
	function update_order() {
			$this->load_settings();
		
		$this->get_total_data($total_data, $total, $taxes);
		$data = array();
			
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}
		
			
			
			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();

			} elseif (isset($this->checkout['payment_address'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->checkout['payment_address']['customer_group_id'];
				$data['firstname'] = $this->checkout['payment_address']['firstname'];
				$data['lastname'] = $this->checkout['payment_address']['lastname'];
				$data['email'] = $this->checkout['payment_address']['email'];
				$data['telephone'] = $this->checkout['payment_address']['telephone'];
				$data['fax'] = $this->checkout['payment_address']['fax'];
			}
			
			

			$payment_address = $this->checkout['payment_address'];
		
			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];	
			$data['payment_company'] = $payment_address['company'];	
			$data['payment_company_id'] = $payment_address['company_id'];	
			$data['payment_tax_id'] = $payment_address['tax_id'];	
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
		
			if (isset($this->checkout['payment_method']['title'])) {
				$data['payment_method'] = $this->checkout['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}
			
			if (isset($this->checkout['payment_method']['code'])) {
				$data['payment_code'] = $this->checkout['payment_method']['code'];
			} else {
				$data['payment_code'] = '';
			}
						
			if ($this->cart->hasShipping()) {
				$shipping_address = $this->checkout['shipping_address'];
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];	
				$data['shipping_company'] = $shipping_address['company'];	
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
				if (isset($this->checkout['shipping_method']['title'])) {
					$data['shipping_method'] = $this->checkout['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}
				
				if (isset($this->checkout['shipping_method']['code'])) {
					$data['shipping_code'] = $this->checkout['shipping_method']['code'];
				} else {
					$data['shipping_code'] = '';
				}				
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';	
				$data['shipping_company'] = '';	
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}
			
			$product_data = array();
		
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
	
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
	 
				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				); 
			}
			
			// Gift Voucher
			$voucher_data = array();
			
			if (!empty($this->checkout['vouchers'])) {
				foreach ($this->checkout['vouchers'] as $voucher) {
					$voucher_data[] = array(
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],						
						'amount'           => $voucher['amount']
					);
				}
			}  
						
			$data['products'] = $product_data;
			$data['vouchers'] = $voucher_data;
			$data['totals'] = $total_data;
			if(isset($this->checkout['confirm']['comment'])) {
				$data['comment'] = $this->checkout['confirm']['comment'];
			} else {
				$data['comment'] = '';
			}
			$data['total'] = $total;
			
			if(preg_match("/1.5.1/i", VERSION)){
				$data['reward'] = $this->cart->getTotalRewardPoints();
			}
			
			if (isset($this->request->cookie['tracking'])) {
				$this->load->model('affiliate/affiliate');
				
				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
				$subtotal = $this->cart->getSubTotal();
				
				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
					$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
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
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			
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
			if(preg_match("/1.5.2/i", VERSION)){
				$this->model_quickcheckout_order->updateOrder152($this->checkout['order_id'], $data);
			}elseif(preg_match("/1.5.1/i", VERSION)){
				$this->model_quickcheckout_order->updateOrder151($this->checkout['order_id'], $data);
			}else{
				$this->model_quickcheckout_order->updateOrder($this->checkout['order_id'], $data);
			}
	}
	/*
	*	Create customer
	*/
	function create_customer($data) {
			$this->model_account_customer->addCustomer($data);
			return true;
	}
	/*
	*	Get total data of cart
	*/
	private function get_total_data(&$total_data, &$total, &$taxes){
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
		$sort_order = array(); 
		
		$results = $this->model_setting_extension->getExtensions('total');

		
		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}
		
		array_multisort($sort_order, SORT_ASC, $results);
		
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('total/' . $result['code']);
				$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($total_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $total_data);
		
		return $total_data;
	}
	
	/*
	*	Get shipping methods
	*/
	private function get_shipping_methods($shipping_address){
		$quote_data = array();
		
		$this->load->model('setting/extension');
		
		$results = $this->model_setting_extension->getExtensions('shipping');
		
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('shipping/' . $result['code']);
				
				$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address); 
	
				if ($quote) {
					$quote_data[$result['code']] = array( 
						'title'      => $quote['title'],
						'quote'      => $quote['quote'], 
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
		}

		$sort_order = array();
	  
		foreach ($quote_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $quote_data);
		
		return $quote_data;
		
	}
	/*
	*	Get Payment Methods
	*/
	private function get_payment_methods($payment_address){

		$this->get_total_data($total_data, $total, $taxes);

		$method_data = array();
		
		$results = $this->model_setting_extension->getExtensions('payment');

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('payment/' . $result['code']);
				
				$method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total); 
				
				if ($method) {
					$method_data[$result['code']] = $method;
				}
			}
		}

		$sort_order = array();
		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $method_data);			
		return $method_data;		
	}			
		
	/*
	*	Get confirm view
	*/
	private function hooks(){
	
	}	
	
	public function country() {
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}

	
	private function get_country_data($country_id, $zone_id = 0){
		
		$address = array();
		
		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($country_id);
		
		if ($country_info) {
			$address['country'] = $country_info['name'];	
			$address['iso_code_2'] = $country_info['iso_code_2'];
			$address['iso_code_3'] = $country_info['iso_code_3'];
			$address['address_format'] = $country_info['address_format'];
		} else {
			$address['country'] = '';	
			$address['iso_code_2'] = '';
			$address['iso_code_3'] = '';
			$address['address_format'] = '';
		}
						
		$this->load->model('localisation/zone');
		$zone_info = $this->model_localisation_zone->getZone($zone_id);
		
		if ($zone_info) {
			$address['zone'] = $zone_info['name'];
			$address['zone_code'] = $zone_info['code'];
		} else {
			$address['zone'] = '';
			$address['zone_code'] = '';
		}
		return $address;
	}

	/*
	*	Get login view
	*/	
	public function refresh(){
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->index());
		}else{
			return false;	
		}
	}

	/*
	*	Get login view
	*/	
	public function refresh_payments(){
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			
			
			//Get shipping method
			$this->data['get_shipping_method_view'] = $this->get_shipping_method_view();
			
			//Get payment method
			$this->data['get_payment_method_view'] = $this->get_payment_method_view();
			
			//Get cart view
			$this->data['get_confirm_view'] = $this->get_confirm_view();
		}
		$this->response->setOutput($this->index());
	}
	/*
	*	Get views by ajax request
	*/	
	public function refresh_step1(){	
		$this->load_settings();
		if(($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) && !$this->customer->isLogged()){
			$this->response->setOutput($this->get_login_view());
		}else{
			$this->response->setOutput(false);
		}
	}
	public function refresh_step2(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_payment_address_view());
		}else{
			$this->response->setOutput(false);	
		}
	}
	public function refresh_step3(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_shipping_address_view());
		}else{
			$this->response->setOutput(false);
		}
	}
	public function refresh_step4(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_shipping_method_view());
		}else{
			$this->response->setOutput(false);
		}
	}
	public function refresh_step5(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_payment_method_view());
		}else{
			$this->response->setOutput(false);
		}
	}
	public function refresh_step6(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_cart_view());
		}else{
			$this->response->setOutput(false);
		}
	}

	public function refresh_step7(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_payment_view());
		}else{
			$this->response->setOutput(false);
		}
	}

	public function refresh_step8(){	
		$this->load_settings();
		if($this->cart->hasProducts() || !empty($this->session->data['vouchers'])){
			$this->response->setOutput($this->get_confirm_view());
		}else{
			$this->response->setOutput(false);
		}
	}

	/*
	*	function for validating the fields input data
	*/
	public function validate_field(){
		
		$result = true;
		$this->load_settings();
		
		if(isset($this->request->post['field'])){
			$field = explode("[", $this->request->post['field']);
			$field[1] =str_replace("]", "", $field[1]);
			$settings = $this->array_merge_recursive_distinct($this->settings['step'][$field[0]], $this->settings['option'][$this->checkout['account']][$field[0]]);
			
			if(isset($settings['fields'][$field[1]]['error'])){
				foreach ($settings['fields'][$field[1]]['error'] as $error){
					if($this->invalid($this->request->post['value'], $error)){
						if(is_array($error['text'])){
							$result = (isset($error['text'][(int)$this->config->get('config_language_id')])) ? $error['text'][(int)$this->config->get('config_language_id')] : $error['text'][1];
						}else{
							$result = $this->language->get($error['text']);	
						}
						if(isset($error['information_id']) && !empty($result)){
						$this->load->model('catalog/information');
							$information_info = $this->model_catalog_information->getInformation($error['information_id']);
							$result = sprintf($result, $information_info['title']);	
						}
						print_r($result);
						break;
					}
				}
			}
		}
	}
	/*
	*	function for validating all required fields 
	*/	
	public function validate_all_fields(){
		$this->load->model('catalog/information');
		$json = array();
		$this->load_settings();
		
		foreach($this->request->post as $step => $data){
			if(isset($this->request->post[$step])){
				$settings = $this->array_merge_recursive_distinct($this->settings['step'][$step], $this->settings['option'][$this->checkout['account']][$step]);
				foreach($this->request->post[$step] as $key => $value){
					if(isset($settings['fields'][$key]['error'])){
						foreach ($settings['fields'][$key]['error'] as $error){
							if($this->invalid($value, $error)){
								if(is_array($error['text'])){
									$json['error'][$step][$key] = (isset($error['text'][(int)$this->config->get('config_language_id')])) ? $error['text'][(int)$this->config->get('config_language_id')] : $error['text'][1];
								}else{
									$json['error'][$step][$key] = $this->language->get($error['text']);	
								}	
							}
							if(isset($error['information_id']) && !empty($json['error'][$step][$key])){
								$information_info = $this->model_catalog_information->getInformation($error['information_id']);
								$json['error'][$step][$key] = sprintf($json['error'][$step][$key], $information_info['title']);	
							}
						}
					}
				}
			}
		}
		//shipping
		if(empty($this->checkout['shipping_methods']) && $this->settings['step']['shipping_method']['display']){
			$json['error']['shipping_method']['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		}
		//payment
		if(empty($this->checkout['payment_methods']) && $this->settings['step']['payment_method']['display']){
			$json['error']['payment_method']['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		}
		
		//Confirm
		if(!$this->cart->hasStock() && $this->config->get('config_stock_warning')){
			if(!$this->config->get('config_stock_checkout')){
				$json['error']['confirm']['error_warning']['error_stock'] = $this->language->get('error_stock');
			}
		}
		if(!$this->checkout['min_order']){
			$json['error']['confirm']['error_warning']['error_min_order'] = sprintf($this->settings['general']['min_order']['text'][(int)$this->config->get('config_language_id')], $this->currency->format($this->settings['general']['min_order']['value']));
		}
		$this->response->setOutput(json_encode($json));
	}
	
	public function validate_coupon() {
		$this->language->load('checkout/cart');
		$json = array();
		$this->load->model('checkout/coupon');
		
		if(!empty($this->request->post['coupon'])){
			$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);			
			
			if (!$coupon_info) {			
				$json['error'] = $this->language->get('error_coupon');
			}
		}else{
			$json['error'] = $this->language->get('error_coupon');
		}
		
		if (!isset($json['error'])){
			$json['success'] = $this->language->get('text_coupon');
		}
		$this->response->setOutput(json_encode($json));
			
	}
	
	public function validate_voucher() {
		$this->language->load('checkout/cart');
		$json = array();
		$this->load->model('checkout/voucher');
		
		if(!empty($this->request->post['voucher'])){			
			$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);			
			if (!$voucher_info) {			
				$json['error']= $this->language->get('error_voucher');
			}
		}else{
			$json['error']= $this->language->get('error_voucher');
		}
		
		if (!isset($json['error'])){
			$json['success'] = $this->language->get('text_voucher');
		}
		$this->response->setOutput(json_encode($json));	
	}
	
	public function validate_reward() {
		$this->language->load('checkout/cart');
		$json = array();
		$points = $this->customer->getRewardPoints();
		
		$points_total = 0;

		if(!empty($this->request->post['reward'])){	

			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}	
					
			if ($this->request->post['reward'] > $points) {
				$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
			}
			
			if ($this->request->post['reward'] > $points_total) {
				$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
			}
		}else{
			$json['error']= $this->language->get('error_reward');
		}
		
		if (!isset($json['error'])){
			$json['success'] = $this->language->get('text_reward');
		}
		$this->response->setOutput(json_encode($json));	
	}
	

	/*
	*	helper function for validating the fields input data
	*/
	public function invalid($value, $data = array()){
		$result = false;
		
		
		if(isset($data['not_empty'])){
			$value = trim($value);
			$result = (empty($value)) ? true : false;
		}	
		if(isset($data['min_length']) && !$result){
			$value = trim($value);
			$result = (utf8_strlen($value) < $data['min_length'])  ? true : false;	
		}
		if(isset($data['max_length']) && !$result){
			$value = trim($value);
			$result = (utf8_strlen($value) > $data['max_length'])  ? true : false;	
		}
		if(isset($data['vat_address']) && !$result){
			$result = (vat_validation($this->checkout[$data['vat_address']]['iso_code_2'], $value) == 'invalid')  ? true : false;	
		}
		if(isset($data['compare_to']) && !$result){
			$field = explode("[", $data['compare_to']);
			$field[1] =str_replace("]", "", $field[1]);
			$data['compare_to'] = (isset($this->checkout[$field[0]][$field[1]])) ? $this->checkout[$field[0]][$field[1]]: '';
			$result = ($value != $data['compare_to'])  ? true : false;
		}
		if(isset($data['regex']) && !$result){
			$value = trim($value);
			$result = (!preg_match($data['regex'], $value))  ? true : false;	
		}
		if(isset($data['email_exists']) && !$result){
			$value = trim($value);
			$result = ($this->model_account_customer->getTotalCustomersByEmail($value)) ? true : false;
		}
		if(isset($data['checked']) && !$result){
			$result =(!$value);
		}

		return $result ;
	}
	public function language_merge($array, $texts){
		$this->load->model('catalog/information');
		$array_full = $array; 
		$result = array();
		foreach ($array as $key => $value){
			foreach ($texts as $text){
				if(isset($array_full[$text])){
					if(!is_array($array_full[$text])){
						$result[$text] = $this->language->get($array_full[$text]);	
					}else{
						if(isset($array_full[$text][(int)$this->config->get('config_language_id')])){
							$result[$text] = $array_full[$text][(int)$this->config->get('config_language_id')];
						}else{
							$result[$text] = current($array_full[$text]);
						}
					}
					if((strpos($result[$text], '%s') !== false) && isset($array_full['information_id'])){
						$information_info = $this->model_catalog_information->getInformation($array_full['information_id']);
						
						if(isset($information_info['title'])){
							$result[$text] = sprintf($result[$text], $this->url->link('information/information/info', 'information_id=' . $array_full['information_id'], 'SSL'), $information_info['title'], $information_info['title']);	
						}
					}
				}						
			}
			if(is_array($array_full[$key])){	
						$result[$key] = $this->language_merge($array_full[$key], $texts);	
			}
			
		}

		return $result;
		
	}
	public function array_merge_recursive_distinct( array &$array1, array &$array2 )
	{
	  $merged = $array1;	
	  foreach ( $array2 as $key => &$value )
		  {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
			  $merged [$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
			}
			else
			{
			  $merged [$key] = $value;
			}
		  }
		
	  return $merged;
	}
	
	public function debug(){
		$this->load_settings();
		$this->data['settings'] = $this->settings;
		$this->data['checkout'] = $this->checkout;
		
		//Get template
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/quickcheckout/debug.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/quickcheckout/debug.tpl';
		} else {
			$this->template = 'default/template/quickcheckout/debug.tpl';
		}
		
		//Render all
		$this->response->setOutput($this->render());
	}
}
?>
