<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head> 
	<meta charset="UTF-8" />
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=1024">
	<title><?php print $this->document->getTitle(); ?></title>
	<base href="<?php echo $base; ?>" />
	<?php if ($description) { ?>
	<meta name="description" content="<?php echo $description; ?>" />
	<?php } ?>
	<?php if ($keywords) { ?>
	<meta name="keywords" content= "<?php echo $keywords; ?>" />
	<?php } ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php if ($icon) { ?>
	<link href="<?php echo $icon; ?>" rel="icon" />
	<?php } ?>

	<?php foreach ($links as $link) { ?>
	<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
	<?php } ?>
	<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/custom-theme/jquery-ui-1.10.0.custom.css" />
	<link href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
	<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/jquery.ui.datepicker.css" media="screen" />
	<?php foreach ($styles as $style) { ?>
	<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
	<?php } ?>
	
	<script>
		var LANGS = {};
		<?php $arr = $this->language->getGroupData(); foreach($arr as $group => $langs){ ?>LANGS['<?php echo $group?>']={};<?php foreach($langs as $name => $value){?>LANGS['<?php echo $group?>']['<?php echo $name ;?>']='<?php echo $value ;?>';<?php } ?><?php } ?>
	</script>
	
	<script src="catalog/view/javascript/jquery/jquery-2.0.3.min.js" type="text/javascript"></script>
	<script src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/bootstrap/js/bootstrap.js" type="text/javascript"></script>
	<script src="catalog/view/javascript/jquery/ui/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="catalog/view/javascript/jquery/jquery.jcarousel.min.js" type="text/javascript"></script>
	
	<?php foreach ($scripts as $script) { ?>
	<script src="<?php echo $script; ?>" type="text/javascript"></script>
	<?php } ?>

	<!-- Important Owl stylesheet -->
	<link rel="stylesheet" href="/catalog/view/javascript/owl-carousel/owl.carousel.css">
	<!-- Default Theme -->
	<link rel="stylesheet" href="/catalog/view/javascript/owl-carousel/owl.theme.css">
	<!-- Include js plugin -->
	<script src="/catalog/view/javascript/owl-carousel/owl.carousel.js"></script>

		<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<?php echo $google_analytics; ?>

		<script type="text/javascript">
			$(document).ready(function() {
				$('a.title').cluetip({
					splitTitle : '|'
				});
				$('ol.rounded a:eq(0)').cluetip({
					splitTitle : '|',
					dropShadow : false,
					cluetipClass : 'rounded',
					showtitle : false
				});
				$('ol.rounded a:eq(1)').cluetip({
					cluetipClass : 'rounded',
					dropShadow : false,
					showtitle : false,
					positionBy : 'mouse'
				});
				$('ol.rounded a:eq(2)').cluetip({
					cluetipClass : 'rounded',
					dropShadow : false,
					showtitle : false,
					positionBy : 'bottomTop',
					topOffset : 70
				});
				$('ol.rounded a:eq(3)').cluetip({
					cluetipClass : 'rounded',
					dropShadow : false,
					sticky : true,
					ajaxCache : false,
					arrows : true
				});
				$('ol.rounded a:eq(4)').cluetip({
					cluetipClass : 'rounded',
					dropShadow : false
				});
			});
		</script>
	</head>
	<body class="<?php if(isset($page_classes)) { print $page_classes; } ?>">
		<div id="wrapper">
				<header>
                    <div id="top-links">
                        <div class="container">
                            <?php print $information_menu; ?>
                            <div class="authorization">
                                <?php if (!$logged) { ?><?php echo $text_welcome; ?>
                                <?php } else { ?><?php echo $text_logged; ?><?php } ?>
                            </div>
                        </div>
                    </div>
					<div class="container header">
						<div class="headerFW">
							<div class="header-content row">
                                <div class="col-xs-3 h_logo">
                                    <?php if ($logo) { ?>
                                    <a href="/" class="logo"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
                                    <?php } else { ?>
                                    <h1><a href="<?php echo $home; ?>" class="logo"><?php echo $name; ?></a></h1>
                                    <?php } ?>
                                </div>
                                <div class="col-xs-6 h_cont">
                                    <div class="phoneW">
                                        <a href="skype:borisfen-market" class="skype">borisfen-market</a>
                                        <span class="city"><b>Харьков:</b> +38 (057) 733-34-33</span>
                                        <!--<span class="vider"><b>Viber:</b> +38 (093) 704-35-36 </span>-->
                                    </div> 
                                    <div class="phoneW">
                                        <span class="mts"><b>МТС:</b><?php echo $this->config->get('config_telephone'); ?></span>
                                        <?php if(trim($this->config->get('config_telephone_2'))): ?>
                                        <span class="kiev"><b>Киевстар:</b><?php echo $this->config->get('config_telephone_2'); ?></span>
                                        <?php endif; ?>
                                        <?php if(trim($this->config->get('config_telephone_3'))): ?>
                                        <span class="life"><b>Лайф:</b><?php echo $this->config->get('config_telephone_3'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div id="search" class="input-group">
                                        <input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" class="form-control input-md"  autocomplete="false" />
                                        <span class="input-group-btn"><button type="button" class="btn btn-default btn-md"><i class="fa fa-search"></i></button></span>
                                    </div>
                                    <div id="callme-button" class="pull-right">Обратный звонок</div>
                                </div>
                                <div class="col-xs-3 h_cart">
                                    <a href="/index.php?route=pavblog/category&id=25" class="btn btn-default">Электронный сомелье</a>
                                    <div class="cartW">
                                        <?php echo $cart; ?>
                                    </div>
								</div>
							</div>
                        </div>
                        <div id="notification"></div>
                    </div>
                    <div class="nav-navbar">
                        <div class="container">
                            <nav class="main-navbar navbar navbar-default">
                                <?php echo $mainmenu; ?>
                            </nav>
                        </div>
                    </div>
				</header>
			<div class="main">
                <?php if ( !empty($slideshow) ) { ?>
				<div class="container slideHolder <?php if (isset($this->request->request['_route_']) || (isset($this->request->request['route']) && $this->request->request['route'] == 'common/home')) {  echo 'home';} ?>">
					<?php print $slideshow; ?>
				</div>
                <?php } ?>