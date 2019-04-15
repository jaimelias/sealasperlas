<?php

class sea_paguelofacil
{
	function __construct()
	{
		add_action('sea_checkout_buttons', array('sea_paguelofacil', 'buttons'), 100);
		add_action('sea_checkout_form', array('sea_paguelofacil', 'form'), 100);
		add_filter('sea_list_gateways', array('sea_paguelofacil', 'gateways'), 100);
		add_action('sea_checkout_icons', array('sea_paguelofacil', 'icons'), 100);
	}
	
	public static function buttons()
	{
		echo '<button type="button" class="pure-button pure-button-primary sea_bycard">'.esc_html('By Card', 'sealasperlas').'</button>';
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
		echo ' <i class="fab fa-cc-visa"></i> <i class="fab fa-cc-mastercard"></i>';
	}
	
}


?>