<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {

		if (isset($this->session->data['order_id'])) {
			$this->data['order_id'] = $this->session->data['order_id'];
		    $this->load->model('checkout/order');
		    $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		    if($this->config->get('config_sms_notifications')) {
			    // check is telephone entered
	            if($order['telephone']) {
	                // send sms to client
	                $this->sms->init();
	                $this->sms->send($order['telephone'], sprintf($this->config->get('config_sms_notifications_text_customer'), $order['order_id']));
	                // check is admin telephone entered
			        if($this->config->get('config_telephone') && $this->config->get('config_sms_notifications_admin')) {
	    		        // sned sms to administrator
			            $this->sms->send($this->config->get('config_telephone'), sprintf($this->config->get('config_sms_notifications_text_admin'), $order['order_id']));
	    		    }
			    }
		    }
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->language->load('checkout/success');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/cart'),
			'text'      => $this->language->get('text_basket'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
			'text'      => $this->language->get('text_checkout'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/success'),
			'text'      => $this->language->get('text_success'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['heading_title'] = $this->language->get('heading_title');
		if(!isset( $this->data['order_id']) ){
			$this->redirect($this->url->link('common/home'));
		}
// 		if ($this->customer->isLogged()) {
			$this->data['text_message'] = sprintf(
				$this->language->get('text_customer'),
					 $this->data['order_id'],
					 $this->url->link('account/account', '', 'SSL'),
					 $this->url->link('account/order', '', 'SSL'),
					 $this->url->link('information/contact'),
					 $this->url->link('account/download', '', 'SSL')
			);
// 		} else {
// 			$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->data['order_id'], $this->url->link('information/contact'));
// 		}

		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
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