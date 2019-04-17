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
	
	public static function passengers()
	{
		$output = null;
		ob_start();
		require_once plugin_dir_path( __FILE__ ).'passengers.php';
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}	
	
	public static function css()
	{
		if(sea_has_shortcode())
		{
			wp_enqueue_style('minimalLayout', plugin_dir_url( __FILE__ ) . 'css/minimal-layout.css', array(), '', 'all');	
			wp_add_inline_style('minimalLayout', '.grecaptcha-badge{margin: 0 0 20px 0;}');
			self::datepickerCSS();
		}
	}
	
	public static function scripts()
	{
		if(sea_has_shortcode())
		{
			self::datepickerJS();
			wp_enqueue_script('landing-cookies', plugin_dir_url( __FILE__ ) . 'js/cookies.js', array( 'jquery'), '', true );
			wp_enqueue_script('sealasperlas', plugin_dir_url( __FILE__ ) . 'js/public.js', array('jquery', 'landing-cookies'), time(), true);
			wp_add_inline_script('sealasperlas', 'function sea_rates(){ return '.json_encode(sealasperlas::destinations()).';}', 'before');
			wp_add_inline_script('sealasperlas', 'function sea_url(){ return "'.esc_url(plugin_dir_url(dirname(__FILE__) )).'";}', 'before');
			wp_add_inline_script('sealasperlas', 'function sea_com(){ return '.esc_html(sealasperlas::commission()).';}', 'before');
			
			wp_dequeue_script('google-recaptcha');
			wp_enqueue_script('sea_recaptcha', 'https://www.google.com/recaptcha/api.js?onload=sea_recaptcha&render=explicit', array('sealasperlas'), 'async_defer', true);
			wp_add_inline_script('sealasperlas', 'function sea_recaptcha_key(){ return "'.esc_html(get_option('captcha_site_key')).'";}', 'before');
		}
	}
	public static function datepickerCSS()
	{
		wp_enqueue_style( 'picker-css', plugin_dir_url( __FILE__ ) . 'css/picker/default.css', array(), 'jetcharters', 'all');
		wp_add_inline_style('picker-css', self::get_inline_css('picker/default.date'));
		wp_add_inline_style('picker-css', self::get_inline_css('picker/default.time'));		
	}
	
	public static function datepickerJS()
	{
		//pikadate
		wp_enqueue_script( 'picker-js', plugin_dir_url( __FILE__ ) . 'js/picker/picker.js', array('jquery'), '3.5.6', true);
		wp_enqueue_script( 'picker-date-js', plugin_dir_url( __FILE__ ) . 'js/picker/picker.date.js', array('jquery', 'picker-js'), '3.5.6', true);
		wp_enqueue_script( 'picker-time-js', plugin_dir_url( __FILE__ ) . 'js/picker/picker.time.js',array('jquery', 'picker-js'), '3.5.6', true);	
		wp_enqueue_script( 'picker-legacy', plugin_dir_url( __FILE__ ) . 'js/picker/legacy.js', array('jquery', 'picker-js'), '3.5.6', true);
		$picker_translation = 'js/picker/translations/'.substr(get_locale(), 0, -3).'.js';
				
		if(file_exists(dirname( __FILE__ ).'/'.$picker_translation))
		{
			wp_enqueue_script( 'picker-time-translation', plugin_dir_url( __FILE__ ).$picker_translation, array('jquery', 'picker-js'), '3.5.6', true);
		}		
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

	public static function has_shortcode()
	{
		global $post;
		global $sea_has_shortcode;
		$output = false;
		
		if(is_object($post))
		{
			if($sea_has_shortcode)
			{
				$output = true;
			}
			else
			{
				if(has_shortcode($post->post_content, 'sealasperlas'))
				{
					$output = true;
					$GLOBALS['sea_has_shortcode'] = $output;
				}
			}			
		}

		return $output;
	}
	public static function get_inline_css($sheet)
	{
		ob_start();
		require_once(dirname( __FILE__ ) . '/css/'.$sheet.'.css');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;	
	}	
}

?>