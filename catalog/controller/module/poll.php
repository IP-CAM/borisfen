<?php
class ControllerModulePoll extends Controller {
	protected function index() {
		$this->load->language('module/poll');
		$this->load->model('fido/poll');

		$this->data['template'] = $this->config->get('config_template');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_vote'] = $this->language->get('text_vote');
		$this->data['text_total_votes'] = $this->language->get('text_total_votes');
		$this->data['text_no_votes'] = $this->language->get('text_no_votes');
		$this->data['text_no_poll'] = $this->language->get('text_no_poll');

		$active_poll = $this->model_fido_poll->getActivePoll();

		if ($active_poll) {
			$poll_id = $active_poll['poll_id'];

			if ($active_poll['status'] == 0) {
				$this->data['disabled'] = TRUE;
			}
		} else {
			$poll_id = 0;
		}

		if(isset($this->request->cookie['poll_answered'])) {
			if($this->request->cookie['poll_answered'] == $poll_id) {
				$this->data['answered'] = TRUE;
			}
		}

		$this->data['poll_id'] = $poll_id;
		$this->data['poll_data'] = $this->model_fido_poll->getPollData($poll_id);

		$this->data['text_poll_results'] = $this->language->get('text_poll_results');

		$this->data['poll_results'] = $this->url->link('information/poll');

		$this->data['action'] = $this->url->link('information/poll');

		$reactions = $this->model_fido_poll->getPollResults($poll_id);

		$total_votes = $this->model_fido_poll->getTotalResults($poll_id);

		if ($reactions) {
			$this->data['reactions'] = TRUE;

			$percent = array(0, 0, 0, 0, 0, 0, 0, 0);

			$totals  = array(0, 0, 0, 0, 0, 0, 0, 0);

			foreach($reactions as $reaction) {
				$totals[$reaction['answer'] - 1]++;
			}

			for($i = 0; $i < 8; $i++) {
				$percent[$i] = round(100 * ($totals[$i]/$total_votes));
			}

			$this->data['percent'] = $percent;
			$this->data['total_votes'] = $total_votes;
			$this->data['text_percent'] = $this->language->get('text_percent');
			$this->data['text_answer'] = $this->language->get('text_answer');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/poll.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/poll.tpl';
		} else {
			$this->template = 'default/template/module/poll.tpl';
		}

		$this->render();
	}
}
?>
