<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/jaimelias/sealasperlas
 * @since             1.0.0
 * @package           sealasperlas
 *
 * @wordpress-plugin
 * Plugin Name:       Sea Las Perlas
 * Plugin URI:        https://github.com/jaimelias/sealasperlas
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jaimelias
 * Author URI:        https://jaimelias.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sealasperlas
 * Domain Path:       /languages
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}

class sealasperlas {
	
	private $domain;
	
	
	function __construct()
	{
		add_action( 'plugins_loaded', array('sealasperlas', 'load_plugin_textdomain'));

		
		require_once plugin_dir_path(__FILE__).'gateways/settings.php';
		
		$settings = new sealasperlas_settings();
		
		if(is_admin())
		{
			require_once plugin_dir_path( __FILE__ ).'admin/admin.php';
			$admin = new sealasperlas_admin();
		}
		else
		{
			require_once plugin_dir_path( __FILE__ ).'public/public.php';
			$public = new sealasperlas_public();
		}
	}
	
	public static function deposit()
	{
		return 10;
	}
	
	public static function commission()
	{
		return 10;
	}	
	
	public static function destinations()
	{
		$output = array();
		array_push($output, array(__('Panama City', 'sealasperlas'), 11));
		array_push($output, array(__('Contadora Island', 'sealasperlas'), 9, array(49, 39)));
		array_push($output, array(__('Viveros Island', 'sealasperlas'), 10, array(49, 39)));
		array_push($output, array(__('San Miguel Island', 'sealasperlas'), 12, array(49, 39)));
		array_push($output, array(__('Saboga Island', 'sealasperlas'), 13, array(49, 39)));
		array_push($output, array(__('Bolanos Island Day Pass', 'sealasperlas'), 15, array(67.5, 55)));
		return $output;
	}
	
	public static function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'sealasperlas',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);		
	}	
}

function sea_has_shortcode()
{
	return sealasperlas_public::has_shortcode();
}

function run_sealasperlas()
{
	$plugin = new sealasperlas();
}

run_sealasperlas();

?>