<?php
class ModelModuleOcmenu extends Model {
	
	public function getMenuByGroupIdAndParentId($group_id, $parent_id){
		$sql = "SELECT *, md.name as name, m.icon as icon FROM " . DB_PREFIX . "menu m 
					JOIN " . DB_PREFIX . "menu_description md ON md.menu_id = m.menu_id
					JOIN " . DB_PREFIX . "menu_type mt ON mt.type_id = m.type_id
						WHERE md.language_id = '" . $this->config->get('config_language_id') . "'
						AND m.status = 1
						AND m.parent_id = '" . $parent_id . "'
						AND m.group_id = '" . $group_id . "'
							ORDER BY m.sort_order";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getPropertiesByMenuId($menu_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "menu_property mp
				JOIN " . DB_PREFIX . "menu_type_property mtp ON mtp.type_property_id = mp.property_id
					WHERE mp.menu_id = '" . $menu_id . "'";
		$query = $this->db->query($sql);
		$result = array();
		foreach($query->rows as $row){
			$result[$row['name']] = $row;
		}
		
		return $result;
	}

	public function getColumnsByMenuId($menu_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "menu_column WHERE menu_id = '" . $menu_id . "'";
		$query = $this->db->query($sql);
		if($query->num_rows){
			return $query->rows;
		} else {
			return array(
						array(
							'column_id' => 0,
							'menu_id' => 0,
							'width' => 0,
							'style' => ''
				));
		}
	}
	
	public function hasChild($menu_id){
		$query = $this->db->query("SELECT menu_id FROM " . DB_PREFIX . "menu WHERE parent_id = '" . $menu_id . "'");
		return $query->num_rows;
	}
	
}
?>