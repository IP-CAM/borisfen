<?php 
	define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . '/');
	
	$ch = curl_init();
	
	$order_data_cancel = array(
					'order_id' 					=> $_POST['order_id'],
					'order_status_id' 			=> $_POST['order_status_id'],
					'cancel_product_id' 		=> $_POST['product_id']
					);
	
	curl_setopt($ch, CURLOPT_URL, HTTP_SERVER . "index.php?route=account/order/cancelProduct");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
		http_build_query($order_data_cancel));
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$server_output = curl_exec ($ch);
	
	curl_close ($ch);
	
	exit($server_output);
				
?>
