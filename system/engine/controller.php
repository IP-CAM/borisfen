<?php
/**
 * @property string $id
 * @property string $template
 * @property array $children
 * @property array $data
 * @property string $output
 * @property Loader $load
 * @property Cache $cache
 * @property Url $url
 * @property Config $config
 * @property Db $db
 * @property Sms $sms
 * @property Document $document
 * @property Encryption $encryption
 * @property Image $image
 * @property Language $language
 * @property Log $log
 * @property Mail $mail
 * @property Pagination $pagination
 * @property Request $request
 * @property Response $response
 * @property Session $session
 * @property Openbay $openbay
 * @property Ebay $ebay
 * @property Amazon $amazon
 * @property Amazonus $amazonus
 * @property Customer $customer
 * @property Affiliate $affiliate
 * @property Currency $currency
 * @property Tax $tax
 * @property Weight $weight
 * @property Length $length
 * @property Cart $cart
 * @property Debug $debug
 * @property ModelAccountAddress $model_account_address
 * @property ModelAccountCustomer $model_account_customer
 * @property ModelAccountCustomerGroup $model_account_customer_group
 * @property ModelAccountDownload $model_account_download
 * @property ModelAccountOrder $model_account_order
 * @property ModelAccountRecurring $model_account_recurring
 * @property ModelAccountReturn $model_account_return
 * @property ModelAccountReward $model_account_reward
 * @property ModelAccountTransaction $model_account_transaction
 * @property ModelAffiliateAffiliate $model_affiliate_affiliate
 * @property ModelAffiliateTransaction $model_affiliate_transaction
 * @property ModelAmazonListing $model_amazon_listing
 * @property ModelAmazonOrder $model_amazon_order
 * @property ModelAmazonProduct $model_amazon_product
 * @property ModelAmazonusListing $model_amazonus_listing
 * @property ModelAmazonusOrder $model_amazonus_order
 * @property ModelAmazonusProduct $model_amazonus_product
 * @property ModelCatalogCategory $model_catalog_category
 * @property ModelCatalogFilter $model_catalog_filter
 * @property ModelCatalogInformation $model_catalog_information
 * @property ModelCatalogManufacturer $model_catalog_manufacturer
 * @property ModelCatalogPoGallery $model_catalog_po_gallery
 * @property ModelCatalogProduct $model_catalog_product
 * @property ModelCatalogReview $model_catalog_review
 * @property ModelCatalogTestimonial $model_catalog_testimonial
 * @property ModelCheckoutCoupon $model_checkout_coupon
 * @property ModelCheckoutFraud $model_checkout_fraud
 * @property ModelCheckoutOrder $model_checkout_order
 * @property ModelCheckoutRecurring $model_checkout_recurring
 * @property ModelCheckoutVoucher $model_checkout_voucher
 * @property ModelCheckoutVoucherTheme $model_checkout_voucher_theme
 * @property ModelDesignBanner $model_design_banner
 * @property ModelDesignLayout $model_design_layout
 * @property ModelEbayOpenbay $model_ebay_openbay
 * @property ModelEbayOrder $model_ebay_order
 * @property ModelEbayProduct $model_ebay_product
 * @property ModelExportYandexMarket $model_export_yandex_market
 * @property ModelFidoPoll $model_fido_poll
 * @property ModelLocalisationCountry $model_localisation_country
 * @property ModelLocalisationCurrency $model_localisation_currency
 * @property ModelLocalisationLanguage $model_localisation_language
 * @property ModelLocalisationReturnReason $model_localisation_return_reason
 * @property ModelLocalisationZone $model_localisation_zone
 * @property ModelMenuMegamenu $model_menu_megamenu
 * @property ModelMenuWidget $model_menu_widget
 * @property ModelModuleOcMenu $model_module_oc_menu
 * @property ModelModuleProscroller $model_module_proscroller
 * @property ModelPavblogBlog $model_pavblog_blog
 * @property ModelPavblogCategory $model_pavblog_category
 * @property ModelPavblogComment $model_pavblog_comment
 * @property ModelPaymentAmazonCheckout $model_payment_amazon_checkout
 * @property ModelPaymentAuthorizenetAim $model_payment_authorizenet_aim
 * @property ModelPaymentAuthorizenetSim $model_payment_authorizenet_sim
 * @property ModelPaymentBankTransfer $model_payment_bank_transfer
 * @property ModelPaymentCheque $model_payment_cheque
 * @property ModelPaymentCod $model_payment_cod
 * @property ModelPaymentFreeCheckout $model_payment_free_checkout
 * @property ModelPaymentGoogleCheckout $model_payment_google_checkout
 * @property ModelPaymentKlarnaAccount $model_payment_klarna_account
 * @property ModelPaymentKlarnaInvoice $model_payment_klarna_invoice
 * @property ModelPaymentLiqpay $model_payment_liqpay
 * @property ModelPaymentMoneybookers $model_payment_moneybookers
 * @property ModelPaymentNochex $model_payment_nochex
 * @property ModelPaymentPaymate $model_payment_paymate
 * @property ModelPaymentPaypoint $model_payment_paypoint
 * @property ModelPaymentPayza $model_payment_payza
 * @property ModelPaymentPerpetualPayments $model_payment_perpetual_payments
 * @property ModelPaymentPpExpress $model_payment_pp_express
 * @property ModelPaymentPpPayflowIframe $model_payment_pp_payflow_iframe
 * @property ModelPaymentPpPro $model_payment_pp_pro
 * @property ModelPaymentPpProIframe $model_payment_pp_pro_iframe
 * @property ModelPaymentPpProPf $model_payment_pp_pro_pf
 * @property ModelPaymentPpProUk $model_payment_pp_pro_uk
 * @property ModelPaymentPpStandard $model_payment_pp_standard
 * @property ModelPaymentSagepay $model_payment_sagepay
 * @property ModelPaymentSagepayDirect $model_payment_sagepay_direct
 * @property ModelPaymentSagepayUs $model_payment_sagepay_us
 * @property ModelPaymentTwocheckout $model_payment_twocheckout
 * @property ModelPaymentWebPaymentSoftware $model_payment_web_payment_software
 * @property ModelPaymentWorldpay $model_payment_worldpay
 * @property ModelQuickcheckoutOrder $model_quickcheckout_order
 * @property ModelSettingExtension $model_setting_extension
 * @property ModelSettingSetting $model_setting_setting
 * @property ModelSettingStore $model_setting_store
 * @property ModelShippingAuspost $model_shipping_auspost
 * @property ModelShippingCitylink $model_shipping_citylink
 * @property ModelShippingFedex $model_shipping_fedex
 * @property ModelShippingFlat $model_shipping_flat
 * @property ModelShippingFree $model_shipping_free
 * @property ModelShippingItem $model_shipping_item
 * @property ModelShippingParcelforce48 $model_shipping_parcelforce_48
 * @property ModelShippingPickup $model_shipping_pickup
 * @property ModelShippingRoyalMail $model_shipping_royal_mail
 * @property ModelShippingUps $model_shipping_ups
 * @property ModelShippingUsps $model_shipping_usps
 * @property ModelShippingWeight $model_shipping_weight
 * @property ModelToolImage $model_tool_image
 * @property ModelToolOnline $model_tool_online
 * @property ModelTotalCoupon $model_total_coupon
 * @property ModelTotalCredit $model_total_credit
 * @property ModelTotalHandling $model_total_handling
 * @property ModelTotalKlarnaFee $model_total_klarna_fee
 * @property ModelTotalLowOrderFee $model_total_low_order_fee
 * @property ModelTotalReward $model_total_reward
 * @property ModelTotalShipping $model_total_shipping
 * @property ModelTotalSubTotal $model_total_sub_total
 * @property ModelTotalTax $model_total_tax
 * @property ModelTotalTotal $model_total_total
 * @property ModelTotalVoucher $model_total_voucher
 * @property ModelCatalogAttribute $model_catalog_attribute
 * @property ModelCatalogAttributeGroup $model_catalog_attribute_group
 * @property ModelCatalogDiscount $model_catalog_discount
 * @property ModelCatalogDownload $model_catalog_download
 * @property ModelCatalogOption $model_catalog_option
 * @property ModelCatalogPoGalleryAlbum $model_catalog_po_gallery_album
 * @property ModelCatalogPoGalleryAlbumReview $model_catalog_po_gallery_album_review
 * @property ModelCatalogPoGalleryPhoto $model_catalog_po_gallery_photo
 * @property ModelCatalogProfile $model_catalog_profile
 * @property ModelCatalogPromotags $model_catalog_promotags
 * @property ModelCatalogSeoeditor $model_catalog_seoeditor
 * @property ModelCatalogSeoreport $model_catalog_seoreport
 * @property ModelCatalogSeries $model_catalog_series
 * @property ModelDesignCustomField $model_design_custom_field
 * @property ModelLocalisationGeoZone $model_localisation_geo_zone
 * @property ModelLocalisationLengthClass $model_localisation_length_class
 * @property ModelLocalisationOrderStatus $model_localisation_order_status
 * @property ModelLocalisationReturnAction $model_localisation_return_action
 * @property ModelLocalisationReturnStatus $model_localisation_return_status
 * @property ModelLocalisationStockStatus $model_localisation_stock_status
 * @property ModelLocalisationTaxClass $model_localisation_tax_class
 * @property ModelLocalisationTaxRate $model_localisation_tax_rate
 * @property ModelLocalisationWeightClass $model_localisation_weight_class
 * @property ModelMenuTree $model_menu_tree
 * @property ModelOpenbayAmazon $model_openbay_amazon
 * @property ModelOpenbayAmazonListing $model_openbay_amazon_listing
 * @property ModelOpenbayAmazonPatch $model_openbay_amazon_patch
 * @property ModelOpenbayAmazonus $model_openbay_amazonus
 * @property ModelOpenbayAmazonusListing $model_openbay_amazonus_listing
 * @property ModelOpenbayAmazonusPatch $model_openbay_amazonus_patch
 * @property ModelOpenbayEbay $model_openbay_ebay
 * @property ModelOpenbayEbayPatch $model_openbay_ebay_patch
 * @property ModelOpenbayEbayProduct $model_openbay_ebay_product
 * @property ModelOpenbayEbayProfile $model_openbay_ebay_profile
 * @property ModelOpenbayEbayTemplate $model_openbay_ebay_template
 * @property ModelOpenbayOpenbay $model_openbay_openbay
 * @property ModelOpenbayOrder $model_openbay_order
 * @property ModelOpenbayVersion $model_openbay_version
 * @property ModelPavblogInstall $model_pavblog_install
 * @property ModelPavblogMenu $model_pavblog_menu
 * @property ModelPavblogSeo $model_pavblog_seo
 * @property ModelReportAffiliate $model_report_affiliate
 * @property ModelReportCoupon $model_report_coupon
 * @property ModelReportCustomer $model_report_customer
 * @property ModelReportOnline $model_report_online
 * @property ModelReportProduct $model_report_product
 * @property ModelReportReturn $model_report_return
 * @property ModelReportSale $model_report_sale
 * @property ModelSaleAffiliate $model_sale_affiliate
 * @property ModelSaleCoupon $model_sale_coupon
 * @property ModelSaleCustomer $model_sale_customer
 * @property ModelSaleCustomerBanIp $model_sale_customer_ban_ip
 * @property ModelSaleCustomerGroup $model_sale_customer_group
 * @property ModelSaleFraud $model_sale_fraud
 * @property ModelSaleOrder $model_sale_order
 * @property ModelSaleRecurring $model_sale_recurring
 * @property ModelSaleReturn $model_sale_return
 * @property ModelSaleVoucher $model_sale_voucher
 * @property ModelSaleVoucherTheme $model_sale_voucher_theme
 * @property ModelToolBackup $model_tool_backup
 * @property ModelUserUser $model_user_user
 * @property ModelUserUserGroup $model_user_user_group
 **/
abstract class Controller {
	protected $registry;	
	protected $id;
	protected $layout;
	protected $template;
	protected $children = array();
	protected $data = array();
	protected $output;

	public function __construct($registry) { 
		$this->registry = $registry;
	    
	    require_once(DIR_SYSTEM . 'library/PhpConsole/__autoload.php');
	    
	    if(!PhpConsole\Handler::getInstance()->isStarted()) {
            $phpConsoleConnector = PhpConsole\Connector::getInstance();
            $phpConsoleConnector->setSourcesBasePath(DIR_CATALOG);
            $phpConsoleConnector->setPassword('connectMe');
            $phpConsoleConnector->setAllowedIpMasks(array('127.0.0.1', '109.86.239.146', '109.86.224.216', '37.57.71.199'));
            $phpConsoleHandler = PhpConsole\Handler::getInstance();
	        $phpConsoleHandler->start();
	        PhpConsole\Helper::register();
	        $evalProvider = $phpConsoleConnector->getEvalDispatcher()->getEvalProvider();
	        $evalProvider->addSharedVar('registry', $this->registry);
	        $evalProvider->setOpenBaseDirs(array(__DIR__));
	        $phpConsoleConnector->startEvalRequestsListener();
	    }
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

	protected function forward($route, $args = array()) {
		return new Action($route, $args);
	}

	protected function redirect($url, $status = 302) {
		header('Status: ' . $status);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
		exit();				
	}

	protected function getChild($child, $args = array()) {
		$action = new Action($child, $args);

		if (file_exists($action->getFile())) {
			require_once($action->getFile());

			$class = $action->getClass();

			$controller = new $class($this->registry);

			$controller->{$action->getMethod()}($action->getArgs());

			return $controller->output;
		} else {
			trigger_error('Error: Could not load controller ' . $child . '!');
			exit();					
		}		
	}

	protected function hasAction($child, $args = array()) {
		$action = new Action($child, $args);

		if (file_exists($action->getFile())) {
			require_once($action->getFile());

			$class = $action->getClass();

			$controller = new $class($this->registry);

			if(method_exists($controller, $action->getMethod())){
				return true;
			}else{
				return false;
			}
		} else {
			return false;				
		}		
	}

	protected function render() {
	    if(!isset($this->request->post['inPopup']) || !$this->request->post['inPopup']) {
	        foreach ($this->children as $child) {
    			$this->data[basename($child)] = $this->getChild($child);
    		}
	    } else {
	        if(isset($this->request->post['childs']) && !empty($this->request->post['childs'])) {
	            foreach ($this->children as $child) {
	                if(!in_array($child, $this->request->post['childs'])) {
	                   $this->data[basename($child)] = '';
	                } else {
	                    $this->data[basename($child)] = $this->getChild($child);
	                }
	            }
	        } else {
	            foreach ($this->children as $child) {
	                $this->data[basename($child)] = '';
	            }
	        }
	        $this->data['breadcrumbs'] = array();
	    }

		if (file_exists(DIR_TEMPLATE . $this->template)) {
			extract($this->data);

			ob_start();

			require(DIR_TEMPLATE . $this->template);

			$this->output = ob_get_contents();

			ob_end_clean();

			return $this->output;
		} else {
			trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
			exit();				
		}
	}
	
	public function applyLangVars(){
		foreach($this->registry->get('language')->getData() as $name => $value){
			$this->data[$name] = $value;
		}
	}
	
	public function applyPostVars($vars){
		foreach($vars as $var){
			if (isset($this->request->post[$var])) {
				$this->data[$var] = $this->request->post[$var];
			} else {
				$this->data[$var] = '';
			}
		}
	}
	
	public function applyErrors($errors, $vars){
		foreach($vars as $var){
			if (isset($errors[$var])) {
				$this->data['error_' . $var] = $errors[$var];
			} else {
				$this->data['error_' . $var] = '';
			}
		}
	}
}
?>
