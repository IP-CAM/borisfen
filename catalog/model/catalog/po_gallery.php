<?php
class ModelCatalogPOGallery extends Model {
	public function updateViewed($album_id) {
		$this->db->query("UPDATE po_gallery_album SET viewed = (viewed + 1) WHERE album_id = '" . (int)$album_id . "'");
	}
	
	public function getAlbum($album_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, (SELECT AVG(rating) AS total FROM po_gallery_album_review r1 WHERE r1.album_id = p.album_id AND r1.status = '1' GROUP BY r1.album_id) AS rating, (SELECT COUNT(*) AS total FROM po_gallery_album_review r2 WHERE r2.album_id = p.album_id AND r2.status = '1' GROUP BY r2.album_id) AS reviews, p.sort_order FROM po_gallery_album p LEFT JOIN po_gallery_album_description pd ON (p.album_id = pd.album_id) LEFT JOIN po_gallery_album_to_store p2s ON (p.album_id = p2s.album_id) WHERE p.album_id = '" . (int)$album_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		
		if ($query->num_rows) {
			$query->row['rating'] = (int)$query->row['rating'];
			
			return $query->row;
		} else {
			return false;
		}
	}

	public function getAlbums($data = array()) {
		
			$sql = "SELECT p.album_id , (SELECT AVG(rating) AS total FROM po_gallery_album_review r1 WHERE r1.album_id = p.album_id AND r1.status = '1' GROUP BY r1.album_id) AS rating FROM po_gallery_album p LEFT JOIN po_gallery_album_description pd ON (p.album_id = pd.album_id) LEFT JOIN po_gallery_album_to_store p2s ON (p.album_id = p2s.album_id)"; 
		
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
			
			if (!empty($data['filter_name'])) {
				$sql .= " AND (";
											
				if (!empty($data['filter_name'])) {
					$implode = array();
					
					$words = explode(' ', $data['filter_name']);
					
					foreach ($words as $word) {
						if (!empty($data['filter_description'])) {
							$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						} else {
							$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						}				
					}
					
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR ";
				}
				
				$sql .= ")";
			}
			
			$sql .= " GROUP BY p.album_id";
			
			$sort_data = array(
				'pd.name',
				'p.viewed',
				'rating',
				'p.sort_order',
				'p.date_added'
			);	
			
// 			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
// 				if ($data['sort'] == 'pd.name') {
// 					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
// 				} else {
// 					$sql .= " ORDER BY " . $data['sort'];
// 				}
// 			} else {
// 				$sql .= " ORDER BY p.sort_order";	
// 			}
			
// 			if (isset($data['order']) && ($data['order'] == 'DESC')) {
// 				$sql .= " DESC, LCASE(pd.name) DESC";
// 			} else {
// 				$sql .= " ASC, LCASE(pd.name) ASC";
// 			}
		    $sql .= ' Order By p.date_modified DESC';
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$album_data = array();
					
			$query = $this->db->query($sql);
		
			foreach ($query->rows as $result) {
				$album_data[$result['album_id']] = $this->getAlbum($result['album_id']);
			}

		
		return $album_data;
	}
	
	public function getTotalAlbums($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.album_id) AS total FROM po_gallery_album p LEFT JOIN po_gallery_album_description pd ON (p.album_id = pd.album_id) LEFT JOIN po_gallery_album_to_store p2s ON (p.album_id = p2s.album_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND (";
								
			if (!empty($data['filter_name'])) {
				$implode = array();
				
				$words = explode(' ', $data['filter_name']);
				
				foreach ($words as $word) {
					if (!empty($data['filter_description'])) {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					} else {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}				
				}
				
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}
			
			if (!empty($data['filter_name'])) {
				$sql .= " OR ";
			}
		
			$sql .= ")";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getAlbumbyAlbumID($album_id) {
		$query = $this->db->query("SELECT DISTINCT * ,(SELECT AVG(rating) AS total FROM po_gallery_album_review r1 WHERE r1.album_id = c.album_id AND r1.status = '1' GROUP BY r1.album_id) AS rating, (SELECT COUNT(*) AS total FROM po_gallery_album_review r2 WHERE r2.album_id = c.album_id AND r2.status = '1' GROUP BY r2.album_id) AS reviews FROM po_gallery_album c LEFT JOIN po_gallery_album_description cd ON (c.album_id = cd.album_id) LEFT JOIN po_gallery_album_to_store c2s ON (c.album_id = c2s.album_id) WHERE c.album_id = '" . (int)$album_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getAlbumImages($album_id) {
		$query = $this->db->query("SELECT * FROM po_gallery_photo p LEFT JOIN po_gallery_photo_to_album p2a ON (p.photo_id = p2a.photo_id) WHERE p2a.album_id = '" . (int)$album_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
	
	public function getTotalReviewsByAlbumId($album_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM po_gallery_album_review r LEFT JOIN po_gallery_album p ON (r.album_id = p.album_id) LEFT JOIN po_gallery_album_description pd ON (p.album_id = pd.album_id) WHERE p.album_id = '" . (int)$album_id . "' AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}
	
	public function getReviewsByAlbumId($album_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.album_id, pd.name, p.image, r.date_added FROM po_gallery_album_review r LEFT JOIN po_gallery_album p ON (r.album_id = p.album_id) LEFT JOIN po_gallery_album_description pd ON (p.album_id = pd.album_id) WHERE p.album_id = '" . (int)$album_id . "' AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
	
	
	

	public function getCategoriesByParentId($category_id) {
		$category_data = array();
		
		$category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "'");
		
		foreach ($category_query->rows as $category) {
			$category_data[] = $category['category_id'];
			
			$children = $this->getCategoriesByParentId($category['category_id']);
			
			if ($children) {
				$category_data = array_merge($children, $category_data);
			}			
		}
		
		return $category_data;
	}
		
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_category');
		}
	}
					
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
	
	public function addReview($album_id, $data) {
		$this->db->query("INSERT INTO po_gallery_album_review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', album_id = '" . (int)$album_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}
}
?>