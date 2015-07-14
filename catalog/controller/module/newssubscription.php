<?php
class ControllerModuleNewssubscription extends Controller {
	
    public function subscribe() {
        if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') && isset($this->request->post['email'])) {
            if($this->validateEmail($this->request->post['email'])) {
                $this->load->model('catalog/information');
                exit(json_encode($this->model_catalog_information->subscribeToNews($this->request->post['email']))); 
            } else {
                exit(json_encode(false));
            }
        }
	}
	
	private function validateEmail($email) {
	    return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}
?>