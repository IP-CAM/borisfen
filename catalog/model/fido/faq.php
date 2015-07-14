<?php
class ModelFidoFaq extends Model {

	public function addFaq($data){
		$this->db->query("INSERT INTO ". DB_PREFIX . "faq SET
			topic_id = '0',
			status = '0',
			sort_order = '0',
			date_added = NOW()");
		$faq_id = $this->db->getLastId();

        $query = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

        foreach ($query->rows as $result) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET
                    faq_id = '" . (int)$faq_id . "',
                    language_id = '" . (int)$result['language_id'] . "',
                    title = '" . $this->db->escape($data['title']) . "',
                    author_name = '" . $this->db->escape($data['author_name']) . "'");
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "faq_to_store SET
			faq_id = '" . (int)$faq_id . "',
			store_id = '0'");
		$this->cache->delete('faq');

        return $faq_id;
	}

	public function getTopic($faq_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "faq f
			LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.faq_id = fd.faq_id)
			LEFT JOIN " . DB_PREFIX . "faq_to_store f2s ON (f.faq_id = f2s.faq_id)
			WHERE
				f.faq_id = '" . (int)$faq_id . "' AND
				fd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
				f2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND
				f.status = '1'");
		return $query->row;
	}

	public function getTopics($topic_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq f
			LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.faq_id = fd.faq_id)
			LEFT JOIN " . DB_PREFIX . "faq_to_store f2s ON (f.faq_id = f2s.faq_id)
			WHERE
				f.topic_id = '" . (int)$topic_id . "'AND
				fd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND
				f2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND
				f.status = '1'
					ORDER BY f.sort_order");
		return $query->rows;
	}

	public function getTotalFaqsByTopicId($topic_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faq f
			LEFT JOIN " . DB_PREFIX . "faq_to_store f2s ON (f.faq_id = f2s.faq_id)
			WHERE
				f.topic_id = '" . (int)$topic_id . "' AND
				f2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND
				f.status = '1'");
		return $query->row['total'];
	}
}