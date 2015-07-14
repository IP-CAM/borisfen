<?php

require_once('require.php');
require_once('model_import.php');

$c = new Color();
// -----------------------------------------

define('SKU', 0);
define('NAME', 1);
define('BRAND', 2);
define('PRICE', 3);
define('WHOLESALE_PRICE', 4);
define('DIVISION', 5);
define('SKLAD', 6);
define('LITERS', 7);
define('COUNT_PACK', 8);
define('WEIGHT', 9);
define('QUANTITY', 10);

$OPTIONS = array(
	40 => LITERS,
);


$log = new Log_1C(DIR_SYNC . 'logs/monitor.log');

$file = DIR_SYNC . 'files/import.csv';

if(!file_exists($file)){
	$log->add('Import file does not exists | ' . date('Y-m-d H-a-s'), true);
	exit;
}

$csv = file(DIR_SYNC . 'files/import.csv');
$price_array = array();

foreach($csv as $str){
	$row = explode(';', $str);
	foreach($row as &$field){
		// TODO: uncomment on production
		$field = iconv('windows-1251', 'UTF-8', $field);
	}
	if(count($row) > 5)
		$price_array[] = $row;
}

// ------------------------------------------


// Check manufacturers
$time = new DateTime;
$log = $time->format('H:i:s');
$log .= " Check manufacturers\n";
echo $c->text($log)->white()->bold()->highlight('green');

$brands_all = $model_import->getManufacturers();
$brands = array();
foreach($brands_all as $brand){
	$brands[$brand['manufacturer_id']] = trim($brand['name']);
}

foreach($price_array as $product){
	if(!in_array(trim($product[BRAND]), $brands) && strlen($product[BRAND]) > 1){
		$model_import->addManufacturer(array(
			'name' => $product[BRAND],
			'sort_order' => 0,
			'manufacturer_description' => array(),
			'keyword' => false
		));
		$brands[] = $product[BRAND];
	}
}
$brands_all = $model_import->getManufacturers();
foreach($brands_all as $brand){
	$brands[$brand['name']] = $brand['manufacturer_id'];
}



// Check products
$time = new DateTime;
$log = $time->format('H:i:s');
$log .= " Check products\n";
echo $c->text($log)->white()->bold()->highlight('green');


$products_all = $model_import->getProducts();
$products = array();
foreach($products_all as $product){
	$products[$product['sku']] = $product;
}
unset($products_all);



file_put_contents(__DIR__ . '/LOG.txt', print_r($price_array,1));



foreach($price_array as $price_product){
	$price_product[PRICE] = str_replace(',', '.', $price_product[PRICE]);
	$price_product[WHOLESALE_PRICE] = str_replace(',', '.', $price_product[WHOLESALE_PRICE]);
	$price_product[WEIGHT] = str_replace(',', '.', $price_product[WEIGHT]);


	if(!isset($products[$price_product[SKU]])){
		// add product
		$new_product = array();
		$new_product['product_description'] = array(
			2 => array(
				'name' => $price_product[NAME],
				'seo_h1' => '',
				'description_short' => '',
				'description' => '',
				'short_description' => '',
				'seo_title' =>'',
				'meta_keyword' =>'',
				'meta_description' =>'',
				'tag' => ''),
			13 => array(
				'name' => $price_product[NAME],
				'seo_h1' => '',
				'description_short' => '',
				'description' => '',
				'short_description' => '',
				'seo_title' =>'',
				'meta_keyword' =>'',
				'meta_description' =>'',
				'tag' => '')
		);
		$new_product['product_attribute'] = array(
			array(
				'attribute_id' => 23,
				'product_attribute_description' => array(
					2 => array('text' => $price_product[COUNT_PACK]),
					13 => array('text' => $price_product[COUNT_PACK])
				)
			),
		);

		$new_product['division'] = $price_product[DIVISION];
		$new_product['manufacturer_id'] = $price_product[SKLAD];

		$new_product['manufacturer_id']  = isset($brands[$price_product[BRAND]]) ? $brands[$price_product[BRAND]] : 0;
		$new_product['price'] 			 = $price_product[PRICE];
		$new_product['whole_sale_price'] = $price_product[WHOLESALE_PRICE];
		$new_product['sku']				 = $price_product[SKU];
		$new_product['quantity']		 = $price_product[QUANTITY];
		$new_product['weight']			 = $price_product[WEIGHT];
		$new_product['status']			 = 0;
		$new_product['currency'] 		 = '';
		$new_product['model'] 			 = '';
		//$new_product['product_category'] = array();
		$new_product['product_to_store'] = array(0);
		$new_product['option']			 = '';
		$new_product['related']			 = '';
		$new_product['location']		 = '';
		$new_product['image']			 = '';
		$new_product['date_available']	 = '';
		$new_product['length']			 = '';
		$new_product['width']			 = '';
		$new_product['height']			 = '';
		$new_product['label'] 			 = 'off';
		$new_product['purchase_price']	 = '';
		$new_product['product_store'] = array(0 => '0');
		$new_product['keyword']			 = array(2 => '');
		$new_product['tax_class_id']	 = 0;
		$new_product['minimum']		 	 = 1;
		$new_product['subtract']		 = 1;
		$new_product['stock_status_id']  = 8;
		$new_product['shipping']		 = 1;
		$new_product['length_class_id']	 = 1;
		$new_product['weight_class_id']	 = 1;
		$new_product['sort_order']		 = 1;
		$new_product['points']			 = 0;
		$new_product['upc']				 = '';
		$new_product['ean']				 = '';
		$new_product['jan']				 = '';
		$new_product['isbn']			 = '';
		$new_product['mpn']				 = '';
		$new_product['color_series_type'] = 'singleItem';
		$new_product['product_series_image'] = 'no_image.jpg';

		$product_id = $model_import->addProduct($new_product);

		$model_import->setDivision($product_id, $price_product[DIVISION]);
		$model_import->setSklad($product_id, $price_product[SKLAD]);
	} else {
		//update product
		$new_product = array();
		$p = $products[$price_product[SKU]];

		$new_product['name'] 			 = $price_product[NAME];
		$new_product['manufacturer_id']  = isset($brands[$price_product[BRAND]]) ? $brands[$price_product[BRAND]] : $p['manufacturer_id'];
		$new_product['price'] 			 = $price_product[PRICE];
		$new_product['whole_sale_price'] = $price_product[WHOLESALE_PRICE];
		$new_product['quantity']		 = $price_product[QUANTITY];
		$new_product['description']		 = '';
		$new_product['tag']				 = '';
		//$new_product['product_category'] = array();

		$new_product['weight']		 = $price_product[WEIGHT];
		$new_product['product_attribute'] = array(
			array(
				'attribute_id' => 23,
				'product_attribute_description' => array(
					2 => array('text' => $price_product[COUNT_PACK]),
					13 => array('text' => $price_product[COUNT_PACK])
				)
			),
		);

		$model_import->editProduct($p['product_id'], $new_product);

		$model_import->updateDivision($p['product_id'], $price_product[DIVISION]);
		$model_import->updateSklad($p['product_id'], $price_product[SKLAD]);
	}
}



// Update options!
$time = new DateTime;
$log = $time->format('H:i:s');
$log .= " Update options\n";
echo $c->text($log)->white()->bold()->highlight('green');

$products_all = $model_import->getProducts();
$products = array();
foreach($products_all as $product){
	$products[$product['sku']] = $product;
}
unset($products_all);

foreach($price_array as $price_product) {
	$product_id = $products[$price_product[SKU]]['product_id'];
	//$model_import->removeProductOptions($product_id);

	// IF NO OPTIONS IN Product
	if ($model_import->isOptionsInProduct($product_id) == false){
		foreach ($OPTIONS as $option_id => $option_key) {
			if (strlen($price_product[$option_key]) > 0) {
				$option_values = $model_import->getOptionValuesByOptionId($option_id);
				$option_value_id = false;
				foreach ($option_values as $opt) {
					if (utf8_strtolower($price_product[$option_key]) == $opt['name']) {
						$option_value_id = $opt['option_value_id'];
						break;
					}
				}
				if (!$option_value_id) {
					$option_value_id = $model_import->addOption($option_id, $price_product[$option_key]);
				}
				$model_import->addOptionToProduct($option_id, $option_value_id, $product_id);
			}
		}
	}
}