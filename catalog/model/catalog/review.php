<?php
class ModelCatalogReview extends Model {
    
    public function getLastReview(){
        $query = $this->db->query("SELECT review_id FROM ".DB_PREFIX."review WHERE customer_id = '".$this->customer->getId()."' ORDER BY `review_id` DESC LIMIT 1");
        return $query->row['review_id'];
    }
    
    public function addReview($product_id, $data) {
        if (!$this->config->get('config_review_statusp'))  {
            $review_statusp = 0;
        } else {
            $review_statusp = 1;
        }
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET addimage = '" . $this->db->escape($data['addimage']) . "', good = '" . $this->db->escape($data['good']) . "', bads = '" . $this->db->escape($data['bads']) . "', status = '" . $review_statusp . "', author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}

	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}		
		
		$query = $this->db->query("SELECT r.review_id, r.answer, r.html_status, r.purchased, r.addimage, r.good, r.bads, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' And (author <> '' AND author is not null) ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
			
		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}
	
	public function addVote($rating, $product_id, $uid) {
		$sql = "
			Insert Into " . DB_PREFIX . "review
			Set
				product_id = '" . (int)$product_id . "',
				customer_id = '" . (int)$this->customer->getId() . "',
				author = '',
				text = '" . $this->db->escape($uid) . "',
				addimage = '',
				html_status = '0',
				purchased = '',
				answer = '',
				bads = '',
				good = '',
				rating = '" . (int)$rating . "',
				status = 1,
				date_added = NOW(),
				date_modified = NOW()
		";
		$this->db->query($sql);
		
		return true;
	}
	
	public function updateVote($rating, $product_id, $uid) {
		$sql = "
			Update " . DB_PREFIX . "review
			Set
				customer_id = '" . (int)$this->customer->getId() . "',
				rating = '" . (int)$rating . "',
				status = 1,
				date_modified = NOW()
			Where
				text = '" . $this->db->escape($uid) . "'
			And
				product_id = '" . (int)$product_id . "'
		";
		$this->db->query($sql);
		
		return true;
	}
}
?>