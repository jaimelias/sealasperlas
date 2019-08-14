<?php

class sea_paguelofacil
{
	function __construct()
	{
		self::load_dependencies();
		add_action('sea_checkout_buttons', array('sea_paguelofacil', 'buttons'), 1);
		add_action('sea_checkout_form', array('sea_paguelofacil', 'form'), 1);
		add_filter('sea_list_gateways', array('sea_paguelofacil', 'gateways'), 1);
		add_action('sea_checkout_icons', array('sea_paguelofacil', 'icons'), 1);
		add_action( 'wp_enqueue_scripts', array('sea_paguelofacil', 'scripts'));
		
		//checkout
		add_filter('the_content', array('sea_paguelofacil', 'content'), 100);
		add_filter('template_include', array('sea_paguelofacil', 'template'), 10);
		add_filter( 'pre_get_document_title',  array('sea_paguelofacil', 'wp_title'), 100);
		add_filter( 'wp_title', array('sea_paguelofacil', 'wp_title'), 100);
		add_filter( 'the_title', array('sea_paguelofacil', 'the_title'), 100);
		add_action('pre_get_posts', array('sea_paguelofacil', 'wp_main_query'), 100);
		add_filter( 'query_vars', array('sea_paguelofacil', 'query_vars'));
		add_action('init', array('sea_paguelofacil', 'rewrite'));
		add_action('init', array('sea_paguelofacil', 'rewrite_tag'), 10, 0);
	}
	
	public static function buttons()
	{
		echo '<button type="button" class="pure-button pure-button-primary sea_bycard">'.esc_html(__('Pay by card', 'sealasperlas')).'</button>';
	}	

	public static function process_payment()
	{
		$headers = array();
		array_push($headers, 'Content-Type: application/x-www-form-urlencoded');
		array_push($headers, 'Accept: */*');
		$gateway_url = 'https://secure.paguelofacil.com/rest/ccprocessing';
		$data = http_build_query(self::payment_obj());
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $gateway_url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		$result = json_decode($result, true);
		
		if(is_array($result))
		{
			if(array_key_exists('Status', $result))
			{
				if($result['Status'] == 'Approved')
				{
					self::webhook_obj();
					return '<div class="minimal_success padding-10 bottom-20"><h2><span class="large"><i class="fas fa-thumbs-up"></i></span> '.esc_html(__('Hello', 'dynamicpackages').' '.sanitize_text_field($_POST['fname']).' '.__('Payment approved. Thank you for order! You will receive and email confirmation shortly at', 'dynamicpackages').' '.sanitize_text_field($_POST['email'])).'</h2></div>';
				}
				else if($result['Status'] == 'Declined')
				{
					return '<div class="minimal_alert padding-10"><h2><span class="large"><i class="fa fa-phone"></i></span> '.esc_html(__('Payment Declined. Please contact your bank to authorize the transaction.', 'sealasperlas')).'</h2></div>';
				}
				else
				{
					return '<div class="minimal_alert padding-10"><h2><span class="large"><i class="fa fa-exclamation-triangle"></i></span> '.esc_html(__('Undefined Checkout Error', 'sealasperlas')).'</h2></div>';
					write_log($result);
				}
			}
			else
			{
				return '<div class="minimal_alert padding-10"><h2><span class="large"><i class="fa fa-exclamation-triangle"></i></span> '.esc_html(__('Undefined Checkout Error. Status not defined.', 'sealasperlas')).'</h2></div>';
				write_log($result);
			}
		}
		else
		{
			return '<div class="minimal_alert padding-10"><h2><span class="large"><i class="fa fa-exclamation-triangle"></i></span> '.esc_html(__('Undefined Checkout Error. OBJ not an Array.', 'sealasperlas')).'</h2></div>';
			write_log($result);
		}
	}
	
	public static function webhook_obj()
	{
		$url = 'https://hooks.zapier.com/hooks/catch/1345330/jmff7a/';
		$webhook = $_POST;
		unset($webhook['CCNum']);
		unset($webhook['ExpMonth']);
		unset($webhook['ExpYear']);
		unset($webhook['CVV2']);
		$webhook = json_encode($webhook);
		
		if(!filter_var($url, FILTER_VALIDATE_URL) === false)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $webhook);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($webhook)));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
	public static function payment_obj()
	{
		//payment vars
		$CCLW = 'E879CB831FB96062E5B0173B29B1BAFE9EC93056C9336CDD134741D8AFFC08D3';
		$gateway = array();
		$gateway['SecretHash'] = hash('sha512', sanitize_text_field($_POST['CCNum']).sanitize_text_field($_POST['CVV2']).sanitize_text_field($_POST['email']));
		$gateway['CMTN'] = sanitize_text_field($_POST['sea_total']);
		$gateway['CDSC'] = sanitize_text_field($_POST['sea_description']);
		$gateway['CCNum'] = sanitize_text_field($_POST['CCNum']);
		$gateway['ExpMonth'] = sprintf('%02d', sanitize_text_field($_POST['ExpMonth']));
		$gateway['ExpYear'] = sanitize_text_field($_POST['ExpYear']);
		$gateway['CVV2'] = sanitize_text_field($_POST['CVV2']);
		$gateway['Name'] = sanitize_text_field($_POST['fname']);
		$gateway['LastName'] = sanitize_text_field($_POST['lastname']);
		$gateway['Email'] = sanitize_text_field($_POST['email']);
		$gateway['Tel'] = sanitize_text_field($_POST['phone']);
		$gateway['Address'] = sanitize_text_field($_POST['country'].', '.$_POST['city'].' '.$_POST['address']);
		$gateway['CCLW'] = esc_html($CCLW);
		$gateway['TxType'] = 'SALE';
		return $gateway;
	}

	public static function query_vars( $vars ) {
		array_push($vars, 'sealasperlas');
		return $vars;
	}
	
	public static function wp_main_query($query)
	{
		if(get_query_var('sealasperlas') && $query->is_main_query())
		{
			$query->set('post_type', 'page');
			$query->set('posts_per_page', 1);
		}
	}
	
	public static function the_title($title)
	{
		if(get_query_var('sealasperlas') && in_the_loop())
		{
			$title = __('Checkout Page', 'sealasperlas');		
		}
		
		return $title;
	}	
	
	public static function content($content)
	{
		if(get_query_var('sealasperlas') && in_the_loop())
		{
			$content = self::process_payment();
		}
		
		return $content;
	}
	public static function wp_title($title)
	{
		if(get_query_var('sealasperlas'))
		{
			$title = __('Checkout Page', 'sealasperlas');	
		}
		
		return $title;
	}
	
	public static function load_dependencies()
	{
		require_once plugin_dir_path( __FILE__ ).'validators.php';
		$validators = new sealasperlas_validators();
	}
	
	public static function scripts()
	{
		if(sea_has_shortcode())
		{
			wp_enqueue_script('sea_paguelo', plugin_dir_url(dirname(__FILE__) ) . 'paguelofacil/paguelofacil.js', array('jquery'), time(), true);
		}
	}
	
	public static function form()
	{
		ob_start();
		require_once plugin_dir_path(__FILE__).'form.php';
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}
	
	public static function gateways($var)
	{
		$var[] = 'Visa';
		$var[] = 'Mastercard';
		return $var;
	}
	
	public static function icons()
	{
		echo '<img width="200" height="40" alt="visa mastercard logo svg" src="'.plugin_dir_url( __FILE__ ).'img/payment.svg" />';
	}
	
	public static function template($template)
	{
		if(get_query_var('sealasperlas'))
		{
			if(sealasperlas_validators::validate_checkout())
			{
				if(sealasperlas_validators::validate_recaptcha())
				{
					return locate_template(array( 'page.php'));	
				}
			}
		}
		return $template;
	}
	public static function rewrite()
	{
		add_rewrite_rule('^sealasperlas/([^/]*)/?', 'index.php?sealasperlas=$matches[1]','top');

		global $polylang;		
		if(isset($polylang))
		{
			$languages = PLL()->model->get_languages_list();
			$language_list = array();
			
			for($x = 0; $x < count($languages); $x++)
			{
				foreach($languages[$x] as $key => $value)
				{
					if($key == 'slug')
					{
						array_push($language_list, $value);
					}
				}	
			}
			$language_list = implode('|', $language_list);
			
			add_rewrite_rule('('.$language_list.')/sealasperlas/([^/]*)/?', 'index.php?sealasperlas=$matches[2]','top');
		}		
	}
	public static function rewrite_tag()
	{
		add_rewrite_tag('%sealasperlas%', '([^&]+)');
	}	
	
}
?>