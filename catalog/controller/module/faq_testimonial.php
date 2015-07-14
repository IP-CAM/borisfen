<?php
class ControllerModuleFaqTestimonial extends Controller {

	private $error = array();

	protected function index() {
		$this->language->load('module/faq_testimonial');
		$this->load->model('fido/faq_testimonial');

    	$this->applyLangVars();

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
			$last_faq_id = $this->model_fido_faq_testimonial->addFaq($this->request->post);
			$this->session->data['success'] = true;

            $entry_email = !empty($this->request->post['author_mail']) ? $this->request->post['author_mail'] : 'Не указан';
            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo(MAIL_FAQ);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_email'));
            $mail->setSubject($this->language->get('email_subject'));

            $mail->setText(
                strip_tags(
                    html_entity_decode(
                        $this->language->get('entry_name') . ": " . $this->request->post['author_name'] . "\n" .
                        $this->language->get('entry_email') . ": " . $entry_email . "\n\n" .
                        $this->language->get('entry_text_faq') . ": " . $this->request->post['title'] . "\n\n" .
                        $this->language->get('text_approve') . ": " . HTTP_SERVER . 'admin/index.php?route=module/faq_testimonial/update&faq_id=' . $last_faq_id . "\n",
                        ENT_QUOTES,
                        'UTF-8'
                    )
                )
            );
            $mail->send();

			$this->redirect('/vopros-otvet');
		}



		$this->data['error_author_name'] = '';
		if (isset($this->error['author_name']))
			$this->data['error_author_name'] = $this->error['author_name'];

		$this->data['error_title'] = '';
		if (isset($this->error['title']))
			$this->data['error_title'] = $this->error['title'];

		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}


		$this->data['success'] = false;
		if (isset($this->session->data['success'])){
			$this->data['success'] = true;
			unset($this->session->data['success']);
		}

		if (isset($this->request->post['author_name']))
			$this->data['author_name'] = $this->request->post['author_name'];
		else
			$this->data['author_name'] = $this->customer->getFirstName();

		$this->data['title'] = '';
		if (isset($this->request->post['title']))
			$this->data['title'] = $this->request->post['title'];

		if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}

		if (isset($this->request->get['topic'])) {
			$parts = explode('_', (string)$this->request->get['topic']);
		} else {
			$parts = array();
		}



		if (isset($parts[0])) {
			$this->data['topic_id'] = $parts[0];
		} else {
			$this->data['topic_id'] = 0;
		}

		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}

		$this->data['faqs'] = array();

		$faqs = $this->model_fido_faq_testimonial->getTopics(0);

		foreach ($faqs as $faq) {
			$children_data = array();

			$children = $this->model_fido_faq_testimonial->getTopics($faq['faq_id']);

			foreach ($children as $child) {
				$data = array(
					'filter_faq_id'  => $child['faq_id'],
					'filter_sub_faq' => true
				);

				$children_data[] = array(
					'faq_id'      => $child['faq_id'],
					'title'       => $child['title'],
					'href'        => $this->url->link('information/faq', 'topic=' . $faq['faq_id'] . '_' . $child['faq_id'])
				);
			}

			$this->data['faqs'][] = array(
				'faq_id'		=> $faq['faq_id'],
				'description'	=> html_entity_decode($faq['description']),
				'title'			=> $faq['title'],
                'date_added'	=> date_format(new DateTime($faq['date_added']), 'd.m.Y'),
				'author_name'	=> $faq['author_name'],
				'moderator_name'=> $faq['moderator_name'],
				'children'		=> $children_data,
				'href'			=> $this->url->link('information/faq_testimonial', 'topic=' . $faq['faq_id'])
			);
		}


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/faq_testimonial.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/faq_testimonial.tpl';
		} else {
			$this->template = 'default/template/module/faq_testimonial.tpl';
		}

		$this->render();
  	}

	private function validate(){
		if ((strlen(trim(utf8_decode($this->request->post['author_name']))) < 2) || (strlen(trim(utf8_decode($this->request->post['author_name']))) > 20)) {
			$this->error['author_name'] = $this->language->get('error_author_name');
		}
		if ((strlen(utf8_decode($this->request->post['title'])) < 5) || (strlen(utf8_decode($this->request->post['title'])) > 500)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			$this->error['captcha'] = $this->language->get('error_captcha');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}




}