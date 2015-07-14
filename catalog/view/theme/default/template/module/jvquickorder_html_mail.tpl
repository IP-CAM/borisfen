<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>
		<?php echo $title; ?>
	</title>
	
<style type="text/css">	
body {
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
}
body, a {
	font-size: 14px;
}

a img {
	border: none;
}


#logo {
	margin-bottom: 10px;
}

hr {
	margin-bottom: 10px;
}

.description {
	float: left;
	margin-bottom: 10px;
	/*border: 1px solid #ed5432;*/
}

.description .image {
	margin-right, margin-bottom: 10px;
	float: left;
	/*border: 1px solid #CCCCCC;*/
}

.description .shortdescription {
	float: left;
	/*border: 1px solid #127843;*/
}

#customer_text {
	margin-top: 20px;
	font-weight: bold;
}

#customer_text #customer_data {
	margin-top: 0px;
	margin-left: 20px;
	font-weight: bold;
}

#thanks {
	margin-top: 20px;
	font-weight: bold;
}
</style>

</head>

<body>	
		
	<div id="logo">
		<a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>">
			<img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" />
		</a>
		
		<h5><i><?php echo $store_name; ?></i></h5>
	</div>
	
	<hr>
	
	<h4><?php echo $body_message_text; ?></h4>

	<hr>

	<h3><?php echo $product_name; ?></h3>
	
	<div class="description">	
		<div class="image">
			<a href="<?php echo $product_link; ?>" title="<?php echo $product_name; ?>">
				<img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>" />
			</a>	
		</div>
		
		<div class="shortdescription">
			<?php echo $shortdescription; ?>
		</div>
	</div>
	
	<br /><hr />

	<div id="customer_text">
		<?php echo $body_message_admin_data_text_customer; ?>
		<div id="customer_data">
			<div><?php echo $customer_name; ?></div>
			<div><?php echo $customer_phone; ?></div>
			<div><?php echo $customer_email; ?></div>
			<div><?php echo $customer_comment; ?></div>
			<div><?php echo $customer_ip; ?></div>
		</div>
	</div>
	
	<div id="customer_text">
		<?php echo $body_message_admin_data_text_product; ?>
		<div id="customer_data">
			<div><?php echo $order_product_quantity; ?></div>
		</div>
	</div>
	
	<div id="customer_text">
		<?php echo $data_store_text; ?>
		<div id="customer_data">
			<div><?php echo $data_store_name; ?></div>
			<div><?php echo $data_store_phone; ?></div>
			<div><?php echo $data_store_email; ?></div>
		</div>
	</div>	
	
	<hr>
	
	<div id="thanks">
		<?php echo $text_thanks1; ?>
		<br /> <br />
		<?php echo $text_thanks2; ?>
	</div>	
	
</body>
</html>
