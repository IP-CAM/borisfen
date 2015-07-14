<?php

class ImportModel {
	var $db;

	public function __construct(){
		$this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	}

	public function getManufacturers(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '0' ORDER BY name");
		return $query->rows;
	}

	public function addManufacturer($data) {
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "manufacturer
			SET
				name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$manufacturer_id = $this->db->getLastId();
		foreach ($data['manufacturer_description'] as $language_id => $value) {
			$this->db->query("
	    		INSERT INTO " . DB_PREFIX . "manufacturer_description
	    		SET
		    		manufacturer_id = '" . (int)$manufacturer_id . "',
	    			language_id = '" . (int)$language_id . "',
		    		custom_title = '" . (isset($value['custom_title'])?$this->db->escape($value['custom_title']):'') . "',
		    		meta_keyword = '" . (isset($value['meta_keyword'])?$this->db->escape($value['meta_keyword']):'') . "',
		    		meta_description = '" . (isset($value['meta_description'])?$this->db->escape($value['meta_description']):'') . "',
		    		description = '" . $this->db->escape($value['description']) . "'
    		");
		}
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}

		//if (isset($data['manufacturer_store'])) {
			//foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");
			//}
		//}

		if ($data['keyword']) {
			foreach ($data['keyword'] as $language_id => $keyword) {
				if ($keyword) {$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
			}
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'seopack'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		/*if (isset($data)) {$parameters = $data['parameters'];}

		if ((isset($parameters['autourls'])) && ($parameters['autourls']))
		{
			require_once(DIR_ROOT . 'admin/controller/catalog/seopack.php');
			$seo = new ControllerCatalogSeoPack($this->registry);

			$query = $this->db->query("SELECT l.language_id, m.name, m.manufacturer_id, l.code from ".DB_PREFIX."manufacturer m
						join ".DB_PREFIX."language l
						where m.manufacturer_id = '" . (int)$manufacturer_id . "';");


			foreach ($query->rows as $manufacturer_row){


				if( strlen($manufacturer_row['name']) > 1 ){

					$slug = $seo->generateSlug($manufacturer_row['name'].'-'.$manufacturer_row['code']);
					$exist_query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'manufacturer_id=" . $manufacturer_row['manufacturer_id'] . "' and language_id=".$manufacturer_row['language_id']);

					if(!$exist_query->num_rows){

						$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
						if($exist_keyword->num_rows){ $slug = $seo->generateSlug($manufacturer_row['name']).'-'.rand();}


						$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword,language_id) VALUES ('manufacturer_id=" . $manufacturer_row['manufacturer_id'] . "', '" . $slug . "', " . $manufacturer_row['language_id'] . ")";
						$this->db->query($add_query);

					}
				}
			}
		}*/
	}

	public function addProduct($data) {
		$sql = "
            INSERT INTO " . DB_PREFIX . "product
            SET model = '" . $this->db->escape($data['model']) . "',
            sku = '" . (isset($data['sku'])?$this->db->escape($data['sku']):'') . "',
            upc = '" . (isset($data['upc'])?$this->db->escape($data['upc']):'') . "',
            ean = '" . (isset($data['ean'])?$this->db->escape($data['ean']):'') . "',
            jan = '" . (isset($data['jan'])?$this->db->escape($data['jan']):'') . "',
            isbn = '" . (isset($data['isbn'])?$this->db->escape($data['isbn']):'') . "',
            mpn = '" . (isset($data['mpn'])?$this->db->escape($data['mpn']):'') . "',
            location = '" . (isset($data['location'])?$this->db->escape($data['location']):'') . "',
            quantity = '" . (int)$data['quantity'] . "',
            minimum = '" . (int)$data['minimum'] . "',
            subtract = '" . (isset($data['subtract'])?(int)$data['subtract']:0) . "',
            stock_status_id = '" . (int)$data['stock_status_id'] . "',
            date_available = '" . $this->db->escape($data['date_available']) . "',
            manufacturer_id = '" . (int)$data['manufacturer_id'] . "',
            shipping = '" . (isset($data['shipping'])?(int)$data['shipping']:1) . "',
            price = '" . (float)$data['price'] . "',
            whole_sale_price = '" . ((isset($data['whole_sale_price']))?($this->db->escape((float)$data['whole_sale_price'])):0) . "',
            whole_sale_price_quantity = '" . ((isset($value['whole_sale_price_quantity']))?($this->db->escape((float)$data['whole_sale_price_quantity'])):0) . "',
            points = '" . (isset($data['points'])?(int)$data['points']:0) . "',
            weight = '" . (float)$data['weight'] . "',
            weight_class_id = '" . (int)$data['weight_class_id'] . "',
            length = '" . (float)$data['length'] . "',
            width = '" . (float)$data['width'] . "',
            height = '" . (float)$data['height'] . "',
            length_class_id = '" . (int)$data['length_class_id'] . "',
            status = '" . (int)$data['status'] . "',
            tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "',
            sort_order = '" . (int)$data['sort_order'] . "',
            video = '" . (isset($data['video'])?$this->db->escape($data['video']):'') . "',
            date_added = NOW()
        ";
		$this->db->query($sql);

		$product_id = $this->db->getLastId();

		if (isset($data['def_img']) && $data['def_img'] != "") {
			$q="
    			UPDATE " . DB_PREFIX . "product
    			SET
    				image = '" . $this->db->escape($data['def_img']) . "'
    			WHERE
    				product_id = '" . (int)$product_id . "'
    		";
			$this->db->query($q);
		} else {
			$q="
    			UPDATE " . DB_PREFIX . "product
    			SET
    				image = ''
    			WHERE
    				product_id = '" . (int)$product_id . "'
    		";
			$this->db->query($q);
		}


		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("
				INSERT INTO
					" . DB_PREFIX . "product_description
				SET
					product_id = '" . (int)$product_id . "',
					language_id = '" . (int)$language_id . "',
					name = '" . $this->db->escape($value['name']) . "',
					description = '" . $this->db->escape($value['description']) . "',
					meta_keyword = '" . (isset($value['meta_keyword'])?$this->db->escape($value['meta_keyword']):'') . "',
					custom_title = '" . (isset($value['custom_title'])?$this->db->escape($value['custom_title']):'') . "',
					custom_h1 = '" . (isset($value['custom_h1'])?$this->db->escape($value['custom_h1']):'') . "',
					custom_alt = '" . (isset($value['custom_alt'])?$this->db->escape($value['custom_alt']):'') . "',
					meta_description = '" . (isset($value['meta_description'])?$this->db->escape($value['meta_description']):'') . "',
					short_description = '" . (isset($value['short_description'])?$this->db->escape($value['short_description']):'') . "',
					tag = '" . (isset($value['tag'])?$this->db->escape($value['tag']):'') . "'
			");
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("
						DELETE FROM
							" . DB_PREFIX . "product_attribute
						WHERE
							product_id = '" . (int)$product_id . "'
						AND
							attribute_id = '" . (int)$product_attribute['attribute_id'] . "'
					");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("
							INSERT INTO
								" . DB_PREFIX . "product_attribute
							SET
								product_id = '" . (int)$product_id . "',
								attribute_id = '" . (int)$product_attribute['attribute_id'] . "',
								language_id = '" . (int)$language_id . "',
								text = '" .  $this->db->escape($product_attribute_description['text']) . "'
						");
					}
				}
			}
		}

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
		}


		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("
					INSERT INTO
						" . DB_PREFIX . "product_discount
					SET
						product_id = '" . (int)$product_id . "',
						customer_group_id = '" . (int)$product_discount['customer_group_id'] . "',
						quantity = '" . (int)$product_discount['quantity'] . "',
						priority = '" . (int)$product_discount['priority'] . "',
						price = '" . (float)$product_discount['price'] . "',
						date_start = '" . $this->db->escape($product_discount['date_start']) . "',
						date_end = '" . $this->db->escape($product_discount['date_end']) . "'
				");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("
					INSERT INTO
						" . DB_PREFIX . "product_special
					SET
						product_id = '" . (int)$product_id . "',
						customer_group_id = '" . (int)$product_special['customer_group_id'] . "',
						priority = '" . (int)$product_special['priority'] . "',
						price = '" . (float)$product_special['price'] . "',
						date_start = '" . $this->db->escape($product_special['date_start']) . "',
						date_end = '" . $this->db->escape($product_special['date_end']) . "'
				");
			}
		}


		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'seopack'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		if (isset($data)) {$parameters = $data['parameters'];}
		else {
			$parameters['keywords'] = '%c%p';
			$parameters['tags'] = '%c%p';
			$parameters['metas'] = '%p - %f';
		}


		if (isset($parameters['ext'])) { $ext = $parameters['ext'];}
		else {$ext = '';}

		if ((isset($parameters['autokeywords'])) && ($parameters['autokeywords']))
		{
			$query = $this->db->query("
	    		select
		    		pd.name as pname,
		    		cd.name as cname,
		    		pd.language_id as language_id,
		    		pd.product_id as product_id,
		    		p.sku as sku,
		    		p.model as model,
		    		p.upc as upc,
		    		m.name as brand
		    	from
		    		" . DB_PREFIX . "product_description pd
				left join
		    		" . DB_PREFIX . "product_to_category pc
	    		on
		    		pd.product_id = pc.product_id
				inner join
		    		" . DB_PREFIX . "product p on pd.product_id = p.product_id
				left join
		    		" . DB_PREFIX . "category_description cd
	    		on
		    		cd.category_id = pc.category_id
	    			and
		    		cd.language_id = pd.language_id
				left join
		    		" . DB_PREFIX . "manufacturer m
	    		on
		    		m.manufacturer_id = p.manufacturer_id
				where
		    		p.product_id = '" . (int)$product_id . "';
    		");
			foreach ($query->rows as $product) {

				$bef = array("%", "_","\"","'","\\");
				$aft = array("", " ", " ", " ", "");

				$included = explode('%', str_replace(array(' ',','), '', $parameters['keywords']));

				$tags = array();

				if (in_array("p", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['pname']))))));}
				if (in_array("c", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['cname']))))));}
				if (in_array("s", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['sku']))))));}
				if (in_array("m", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['model']))))));}
				if (in_array("u", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['upc']))))));}
				if (in_array("b", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['brand']))))));}

				$keywords = '';

				foreach ($tags as $tag)
				{
					if (strlen($tag) > 2)
					{

						$keywords = $keywords.' '.strtolower($tag);

					}
				}


				$exists = $this->db->query("select count(*) as times from " . DB_PREFIX . "product_description where product_id = ".$product['product_id']." and language_id = ".$product['language_id']." and meta_keyword like '%".$keywords."%';");

				foreach ($exists->rows as $exist)
				{
					$count = $exist['times'];
				}
				$exists = $this->db->query("select length(meta_keyword) as leng from " . DB_PREFIX . "product_description where product_id = ".$product['product_id']." and language_id = ".$product['language_id'].";");

				foreach ($exists->rows as $exist)
				{
					$leng = $exist['leng'];
				}

				if (($count == 0) && ($leng < 255)) {$this->db->query("update " . DB_PREFIX . "product_description set meta_keyword = concat(meta_keyword, '". htmlspecialchars($keywords) ."') where product_id = ".$product['product_id']." and language_id = ".$product['language_id'].";");}


			}
		}
		if ((isset($parameters['autometa'])) && ($parameters['autometa']))
		{
			$query = $this->db->query("select pd.name as pname, p.price as price, cd.name as cname, pd.description as pdescription, pd.language_id as language_id, pd.product_id as product_id, p.model as model, p.sku as sku, p.upc as upc, m.name as brand from " . DB_PREFIX . "product_description pd
								left join " . DB_PREFIX . "product_to_category pc on pd.product_id = pc.product_id
								inner join " . DB_PREFIX . "product p on pd.product_id = p.product_id
								left join " . DB_PREFIX . "category_description cd on cd.category_id = pc.category_id and cd.language_id = pd.language_id
								left join " . DB_PREFIX . "manufacturer m on m.manufacturer_id = p.manufacturer_id
								where p.product_id = '" . (int)$product_id . "';");

			foreach ($query->rows as $product) {

				$bef = array("%", "_","\"","'","\\", "\r", "\n");
				$aft = array("", " ", " ", " ", "", "", "");

				$ncategory = trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['cname']))));
				$nproduct = trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['pname']))));
				$model = trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['model']))));
				$sku = trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['sku']))));
				$upc = trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['upc']))));
				$content = strip_tags(html_entity_decode($product['pdescription']));
				$pos = strpos($content, '.');
				if($pos === false) {}
				else { $content =  substr($content, 0, $pos+1);	}
				$sentence = trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft, $content))));
				$brand = trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['brand']))));
				$price = trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft, number_format($product['price'], 2)))));

				$bef = array("%c", "%p", "%m", "%s", "%u", "%f", "%b", "%$");
				$aft = array($ncategory, $nproduct, $model, $sku, $upc, $sentence, $brand, $price);

				$meta_description = str_replace($bef, $aft,  $parameters['metas']);

				$exists = $this->db->query("select count(*) as times from " . DB_PREFIX . "product_description where product_id = ".$product['product_id']." and language_id = ".$product['language_id']." and meta_description not like '%".htmlspecialchars($meta_description)."%';");

				foreach ($exists->rows as $exist)
				{
					$count = $exist['times'];
				}

				if ($count) {$this->db->query("update " . DB_PREFIX . "product_description set meta_description = concat(meta_description, '". htmlspecialchars($meta_description) ."') where product_id = ".$product['product_id']." and language_id = ".$product['language_id'].";");}

			}
		}
		if ((isset($parameters['autotags'])) && ($parameters['autotags']))
		{
			$query = $this->db->query("select pd.name as pname, pd.tag, cd.name as cname, pd.language_id as language_id, pd.product_id as product_id, p.sku as sku, p.model as model, p.upc as upc, m.name as brand from " . DB_PREFIX . "product_description pd
							inner join " . DB_PREFIX . "product_to_category pc on pd.product_id = pc.product_id
							inner join " . DB_PREFIX . "product p on pd.product_id = p.product_id
							inner join " . DB_PREFIX . "category_description cd on cd.category_id = pc.category_id and cd.language_id = pd.language_id
							left join " . DB_PREFIX . "manufacturer m on m.manufacturer_id = p.manufacturer_id
							where p.product_id = '" . (int)$product_id . "';");

			foreach ($query->rows as $product) {

				$newtags ='';

				$included = explode('%', str_replace(array(' ',','), '', $parameters['tags']));

				$tags = array();


				$bef = array("%", "_","\"","'","\\");
				$aft = array("", " ", " ", " ", "");

				if (in_array("p", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['pname']))))));}
				if (in_array("c", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['cname']))))));}
				if (in_array("s", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['sku']))))));}
				if (in_array("m", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['model']))))));}
				if (in_array("u", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['upc']))))));}
				if (in_array("b", $included)) {$tags = array_merge($tags, explode(' ',trim($this->db->escape(htmlspecialchars_decode(str_replace($bef, $aft,$product['brand']))))));}

				foreach ($tags as $tag)
				{
					if (strlen($tag) > 2)
					{
						if ((strpos($product['tag'], strtolower($tag)) === false) && (strpos($newtags, strtolower($tag)) === false))
						{
							$newtags .= ' '.strtolower($tag).',';
						}
					}
				}


				if ($product['tag']) {
					$newtags = trim($this->db->escape($product['tag']) . $newtags,' ,');
					$this->db->query("update " . DB_PREFIX . "product_description set tag = '$newtags' where product_id = '". $product['product_id'] ."' and language_id = '". $product['language_id'] ."';");
				}
				else {
					$newtags = trim($newtags,' ,');
					$this->db->query("update " . DB_PREFIX . "product_description set tag = '$newtags' where product_id = '". $product['product_id'] ."' and language_id = '". $product['language_id'] ."';");
				}

			}

		}
		if ((isset($parameters['autourls'])) && ($parameters['autourls']))
		{
			require_once(DIR_ROOT . 'admin/controller/catalog/seopack.php');
			$seo = new ControllerCatalogSeoPack($this->registry);

			$query = $this->db->query("SELECT pd.product_id, pd.name, pd.language_id ,l.code FROM ".DB_PREFIX."product p
								inner join ".DB_PREFIX."product_description pd ON p.product_id = pd.product_id
								inner join ".DB_PREFIX."language l on l.language_id = pd.language_id
								where p.product_id = '" . (int)$product_id . "';");


			foreach ($query->rows as $product_row ){


				if( strlen($product_row['name']) > 1 ){

					$slug = $seo->generateSlug($product_row['name']).$ext;
					$exist_query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'product_id=" . $product_row['product_id'] . "' and language_id=".$product_row['language_id']);

					if(!$exist_query->num_rows){

						$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
						if($exist_keyword->num_rows){
							$exist_keyword_lang = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "' AND " . DB_PREFIX . "url_alias.query <> 'product_id=" . $product_row['product_id'] . "'");
							if($exist_keyword_lang->num_rows){
								$slug = $seo->generateSlug($product_row['name']).'-'.rand().$ext;
							}
							else
							{
								$slug = $seo->generateSlug($product_row['name']).'-'.$product_row['code'].$ext;
							}
						}


						$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword, language_id) VALUES ('product_id=" . $product_row['product_id'] . "', '" . $slug . "', " . $product_row['language_id'] . ")";
						$this->db->query($add_query);

					}
				}
			}
		}
		return $product_id;
	}

	public function getProducts() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . 2 . "'");
		$res = array();
		foreach($query->rows as $row){
			if(isset($row['sku']))
				$res[$row['sku']] = $row;
		}
		return $res;
	}

	public function editProduct($product_id, $data){
		$this->db->query("UPDATE " . DB_PREFIX  . "product SET
			price = '" . $data['price'] . "',
			date_modified = NOW(),
			manufacturer_id = '" . $data['manufacturer_id'] . "',
			quantity = '" . $this->db->escape($data['quantity']) ."',
			whole_sale_price = '" . $this->db->escape($data['whole_sale_price']) ."',
			weight = '" . (float)$data['weight'] . "'
				WHERE product_id = '" . $product_id . "'");
		$this->db->query("UPDATE " . DB_PREFIX  . "product_description SET
			name = '" . $this->db->escape($data['name']) . "',
			description = '" . $this->db->escape($data['description']) . "',
			tag = '" . $this->db->escape($data['tag']) ."'
				WHERE product_id = '" . $product_id . "' AND language_id = 13");
		if(false){
			$this->db->query("DELETE FROM " . DB_PREFIX  . "product_to_category WHERE product_id = '" . $product_id . "'");
			if(isset($data['product_category']) && count($data['product_category']) > 0){
				foreach($data['product_category'] as $category_id){
					$this->db->query("INSERT INTO product_to_category (product_id, category_id, main_category) VALUES ('" . $product_id . "', '" . $category_id . "', '1')");
				}
			}
		}

		if(isset($data['price_special'])){
			if($data['price_special_exists']){
				$this->db->query("UPDATE " . DB_PREFIX  . "product_special SET price = '" . $data['price_special'] . "' WHERE product_id = '" . $product_id . "' AND priority = '999000'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX  . "product_special (product_id, customer_group_id, priority, date_start, date_end) VALUES ('{$product_id}', '1', '999000', '{$data['price_special_date_start']}', '{$data['price_special_date_end']}')");
			}
		}


		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("
						DELETE FROM
							" . DB_PREFIX . "product_attribute
						WHERE
							product_id = '" . (int)$product_id . "'
						AND
							attribute_id = '" . (int)$product_attribute['attribute_id'] . "'
					");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("
							INSERT INTO
								" . DB_PREFIX . "product_attribute
							SET
								product_id = '" . (int)$product_id . "',
								attribute_id = '" . (int)$product_attribute['attribute_id'] . "',
								language_id = '" . (int)$language_id . "',
								text = '" .  $this->db->escape($product_attribute_description['text']) . "'
						");
					}
				}
			}
		}
	}

	public function getProductsByManufacturerId($manufacturer_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE manufacturer_id = '{$manufacturer_id}'");
		return $query->rows;
	}

	public function setProductQuantity($model, $quantity){
		$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . $quantity . "' WHERE model = '" . $this->db->escape($model) . "'");
		return $this->db->countAffected();
	}

	public function removeProductOptions($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . $product_id . "'");
	}

	public function getOptionValuesByOptionId($option_id){
		$query = $this->db->query("SELECT LOWER(name) as name, option_value_id FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . $option_id . "'");
		$result = array();
		foreach($query->rows as $row){
			$result[] = array('name' => $row['name'], 'option_value_id' => $row['option_value_id']);
		}
		return $result;
	}

	public function addOption($option_id, $name){
		$this->db->query("INSERT INTO " . DB_PREFIX . "option_value (option_id, image) VALUES ('".$option_id."', 'no-image.jpg')");
		$option_value_id = $this->db->getLastId();
		$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description (option_value_id, language_id, option_id, name) VALUES ('".$option_value_id."', '2', '".$option_id."', '" . $this->db->escape($name) . "')");
		return $option_value_id;
	}

	public function addOptionToProduct($option_id, $option_value_id, $product_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_option (product_id, option_id, required) VALUES ('".$product_id."', '".$option_id."', '0')");
		$product_option_id = $this->db->getLastId();
		$sql = "INSERT INTO " . DB_PREFIX . "product_option_value (product_option_id, product_id, option_id, option_value_id, quantity, subtract, price, price_prefix, points, points_prefix, weight, weight_prefix, is_default) ";
		$sql .= "VALUES ('{$product_option_id}', '{$product_id}', '{$option_id}', '{$option_value_id}', '999', '0', '0', '+', '0', '+', '0', '+', 1)";
		$this->db->query($sql);
	}

	public function isOptionsInProduct($product_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . $product_id . "'");
		if($query->num_rows)
			return true;
		return false;
	}

	public function setDivision($product_id, $division){
		$sql = "INSERT INTO " . DB_PREFIX . "product_to_division SET product_id = '" . (int)$product_id . "', division = '" . $this->db->escape($division) . "'\n";

		//file_put_contents(__DIR__ . '/LOG_SQL.txt', print_r($sql,1), FILE_APPEND);
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_division SET
					product_id = '" . (int)$product_id . "',
					division = '" . $this->db->escape($division) . "'");
	}

	public function setSklad($product_id, $sklad){
		$sql = "INSERT INTO " . DB_PREFIX . "product_to_sklad SET product_id = '" . (int)$product_id . "', sklad = '" . $this->db->escape($sklad) . "'\n";
		//file_put_contents(__DIR__ . '/LOG_SQL.txt', print_r($sql,1), FILE_APPEND);

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_sklad SET
					product_id = '" . (int)$product_id . "',
					sklad = '" . $this->db->escape($sklad) . "'");
	}

	public function updateDivision($product_id, $division){
		$sql = "UPDATE " . DB_PREFIX . "product_to_division SET division = '" . $this->db->escape($division) . "' WHERE product_id = '" . (int)$product_id . "'\n";
		//file_put_contents(__DIR__ . '/LOG_SQL.txt', print_r($sql,1), FILE_APPEND);

		$this->db->query("UPDATE " . DB_PREFIX . "product_to_division SET division = '" . $this->db->escape($division) . "' WHERE product_id = '" . (int)$product_id . "'");
	}

	public function updateSklad($product_id, $sklad){
		$sql = "UPDATE " . DB_PREFIX . "product_to_sklad SET sklad = '" . $this->db->escape($sklad) . "' WHERE product_id = '" . (int)$product_id . "'\n";
		// file_put_contents(__DIR__ . '/LOG_SQL.txt', print_r($sql,1), FILE_APPEND);

		$this->db->query("UPDATE " . DB_PREFIX . "product_to_sklad SET
					sklad = '" . $this->db->escape($sklad) . "'
					WHERE product_id = '" . (int)$product_id . "'");
	}

}

$model_import = new ImportModel();