<?php

class sealasperlas_public 
{
	function __construct()
	{
		add_shortcode('sealasperlas', array('sealasperlas_public', 'form'));
		add_action( 'wp_enqueue_scripts', array('sealasperlas_public', 'css'));
		add_action( 'wp_enqueue_scripts', array('sealasperlas_public', 'scripts'));
	}
	
	public static function form()
	{
		$output = null;
		ob_start();
		require_once plugin_dir_path( __FILE__ ).'quote.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public static function itinerary()
	{
		$output = null;
		ob_start();
		require_once plugin_dir_path( __FILE__ ).'itinerary.php';
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}	
	
	public static function css()
	{
		wp_enqueue_style('minimalLayout', plugin_dir_url( __FILE__ ) . 'css/minimal-layout.css', array(), '', 'all');
	}
	
	public static function scripts()
	{
		wp_add_inline_script('picker-legacy', self::js(), 'after');
		wp_add_inline_script('jquery', 'function sea_rates(){ return '.json_encode(sealasperlas::destinations()).';}', 'after');
		wp_add_inline_script('jquery', 'function sea_com(){ return '.esc_html(sealasperlas::commission()).';}', 'after');
	}	
	
	public static function option_destinations($option = false)
	{
		$destinations = sealasperlas::destinations();
		$output = null;
	
		
		for($x = 0; $x < count($destinations); $x++)
		{
			if($destinations[$x][0] == __('Panama City', 'sealasperlas') && $option === true)
			{
				$selected = 'selected';
			}
			else
			{
				$selected = null;
			}
	
			$output .= '<option value="'.esc_html($destinations[$x][1]).'" '.esc_html($selected).'>'.esc_html($destinations[$x][0]).'</option>';
		}
		echo $output;
	}
	
	public static function option_passengers()
	{	
		$output = null;
		for($x = 0; $x < 100; $x++)
		{
			$output .= '<option value="'.esc_html($x+1).'">'.esc_html($x+1).'</option>';
		}
		echo $output;
	}	

	
	public static function js()
	{
		$output = null;
		ob_start();
		require_once plugin_dir_path( __FILE__ ).'js/public.js';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}	
}

?>