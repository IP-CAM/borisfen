<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
	    $titles = $this->config->get('config_title');
	    
		$this->data['title'] = $titles[$this->config->get('config_language_id')];

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}
		
 		$this->load->library('lessc.inc');
 		$styleFolder = 'catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/';
		
 		$style_setting = array (
             'width'      => '1000px',
             'color'      => 'red',
             'bgcolor'    => '#e3eff2 url("../image/close.png") repeat'
 		);
		
 		$lessFile   = $styleFolder . 'stylesheet.less';
 		$cssFile    = $styleFolder . 'stylesheet.css';
 		if(file_exists($lessFile) && is_writable($styleFolder)){
 		    $lessNew		= new lessc($lessFile);
 		    $lessParse	= $lessNew->parse(null, $style_setting);
		
 		    $hashCss = file_exists($cssFile) ? sha1_file($cssFile) : '';
 		    $hashLess = sha1($lessParse);
		
 		    if ($hashCss != $hashLess) {
 		        file_put_contents($cssFile, $lessParse);
 		    }
		
 		    $this->document->addStyle($cssFile);
 		}
		
		
		$this->document->addScript(SCRIPT_FOLDER . 'arrow.js');
		$this->document->addScript(SCRIPT_FOLDER . 'rating-stars.js');
		$this->document->addScript(SCRIPT_FOLDER . 'bt_plugins/bootbox.js');
		$this->document->addScript(SCRIPT_FOLDER . 'jquery/jquery.validate.js');
		$this->document->addScript(SCRIPT_FOLDER . 'jquery/jquery.maskedinput-1.3.min.js');
		$this->document->addScript(SCRIPT_FOLDER . 'common.js');
		$this->document->addScript(SCRIPT_FOLDER . 'placeholder.js');
		$this->document->addScript(SCRIPT_FOLDER . 'jquery/jquery.lavalamp.min.js');
		$this->document->addScript(SCRIPT_FOLDER . 'jquery/jquery.easing.1.3.js');
		$this->document->addScript(SCRIPT_FOLDER . 'bt_plugins/ekko-lightbox.min.js');
		$this->document->addScript(SCRIPT_FOLDER . 'jquery/jquery.flexslider.js');
		$this->document->addScript(SCRIPT_FOLDER . 'bt_plugins/bootstrap-select.js');
		$this->document->addScript(SCRIPT_FOLDER . 'bt_plugins/bootstrap-checkbox.js');
		$this->document->addScript(SCRIPT_FOLDER . 'jquery/jquery.cluetip.js');
		$this->document->addScript(SCRIPT_FOLDER . 'jquery/jquery.numeric.js');
		$this->document->addScript(SCRIPT_FOLDER . 'callme.js');
		$this->document->addScript(SCRIPT_FOLDER . 'livesearch.js');
		$this->document->addScript(SCRIPT_FOLDER . 'linkInPopup.js');
		$this->document->addScript(SCRIPT_FOLDER . 'popup-cart.js');
		$this->document->addScript(SCRIPT_FOLDER . 'jquery/jquery.matchHeight-min.js');
        $this->document->addScript(SCRIPT_FOLDER . 'ajax2login.js');
        $this->document->addScript(SCRIPT_FOLDER . 'pnotify.custom.min.js');
		
		if($this->config->get('config_use_countdown')){
			$this->document->addScript(SCRIPT_FOLDER . 'countdown.js');
		}
		
		$this->document->addStyle(STYLE_FOLDER . 'bt_plugins/bootstrap-select.css');
		$this->document->addStyle(STYLE_FOLDER . 'bt_plugins/ekko-lightbox.min.css');
		$this->document->addStyle(STYLE_FOLDER . 'jquery.cluetip.css');
		
		$this->data['base'] = $server;
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['name'] = $this->config->get('config_name');

		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}		

		$this->language->load('common/header');
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login/ajax2login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_contact'] = $this->language->get('text_contact');

		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['shopping_cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

		if(isset($this->request->get['route']) && $this->request->get['route'] != 'common/home') {
		    $this->data['page_classes'] = str_replace('/', ' ', $this->request->get['route']);
		    if(isset($this->request->get['path'])) {
		    	$this->data['page_classes'] .= ' path-id-' . $this->request->get['path'];
		    }
		} else {
		    $this->data['page_classes'] = 'common home';
		}

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", trim($this->config->get('config_robots')));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		$this->load->model('setting/store');

		$this->data['stores'] = array();

		if ($this->config->get('config_shared') && $status) {
			$this->data['stores'][] = $server . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();

			$stores = $this->model_setting_store->getStores();

			foreach ($stores as $store) {
				$this->data['stores'][] = $store['url'] . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
			}
		}

		// Search		
		if (isset($this->request->get['search'])) {
			$this->data['search'] = $this->request->get['search'];
		} else {
			$this->data['search'] = '';
		}

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$product_total = $this->model_catalog_product->getTotalProducts($data);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);						
				}

				// Level 1
				$this->data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart',
            'common/mainmenu',
            'common/information_menu',
            'common/slideshow',
		);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}

		/* Pav blog */
		if(isset($this->data['categories'])){
		    $this->data['categories'][] = array(
                'name'     => $this->language->get("text_blogs"),
                'children' => array(),
                'column'   => 1,
                'href'     => $this->url->link('pavblog/blogs', '')
		    );
		}
		/* Pav blog */
		
		$this->render();
	} 	
}