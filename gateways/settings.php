<?php

class sealasperlas_settings
{
	function __construct()
	{
		self::load_gateways();
		self::define_gateways();
	}
	
	public static function load_gateways()
	{
		require_once plugin_dir_path(__FILE__).'paguelofacil/paguelofacil.php';
	}
	
	public static function define_gateways()
	{
		$paguelofacil = new sea_paguelofacil();
	}
	
	public static function buttons()
	{
		do_action('sea_checkout_buttons');
	}
	public static function form()
	{
		do_action('sea_checkout_form');
	}
	public static function list_gateways()
	{
		return apply_filters('sea_list_gateways', array());
	}
	public static function join_gateways()
	{
		$array = self::list_gateways();
		return join(' '.__('or', 'dynamicpackages').' ', array_filter(array_merge(array(join(', ', array_slice($array, 0, -1))), array_slice($array, -1)), 'strlen'));
	}
	public static function icons()
	{
		do_action('sea_checkout_icons');
	}	
}


?>