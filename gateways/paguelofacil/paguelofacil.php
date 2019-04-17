<?php

class sea_paguelofacil
{
	function __construct()
	{
		//self::load_dependencies();
		add_action('sea_checkout_buttons', array('sea_paguelofacil', 'buttons'), 1);
		add_action('sea_checkout_form', array('sea_paguelofacil', 'form'), 1);
		add_filter('sea_list_gateways', array('sea_paguelofacil', 'gateways'), 1);
		add_action('sea_checkout_icons', array('sea_paguelofacil', 'icons'), 1);
		add_action( 'wp_enqueue_scripts', array('sea_paguelofacil', 'scripts'));
		
		//checkout
		add_filter('the_content', array('sea_paguelofacil', 'content'), 100);
		add_filter('template_include', array('sea_paguelofacil', 'template'), 10);
		add_filter( 'pre_get_document_title',  array('sea_paguelofacil', 'wp_title'));
		add_filter( 'wp_title', array('sea_paguelofacil', 'wp_title'));
		add_filter( 'the_title', array('sea_paguelofacil', 'the_title'));
		add_action('pre_get_posts', array('sea_paguelofacil', 'wp_main_query'), 100);
		add_filter( 'query_vars', array('sea_paguelofacil', 'query_vars'));
		add_action('init', array('sea_paguelofacil', 'rewrite'));
		add_action('init', array('sea_paguelofacil', 'rewrite_tag'), 10, 0);			
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
			$title = 'Sea';		
		}
		
		return $title;
	}	
	
	public static function content($content)
	{
		if(get_query_var('sealasperlas') && in_the_loop())
		{
			$content = '<h1>Hello</h1>';		
		}
		
		return $content;
	}
	public static function wp_title($title)
	{
		if(get_query_var('sealasperlas'))
		{
			$title = 'Sea';
		}
		
		return $title;
	}
	
	public static function load_dependencies()
	{
		require_once plugin_dir_path( __FILE__ ).'validators.php';
		$validators = new sealasperlas_validators();
	}	
	
	public static function buttons()
	{
		echo '<button type="button" class="pure-button pure-button-primary sea_bycard">'.esc_html('Pay by card', 'sealasperlas').'</button>';
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
		echo ' <i class="fab fa-cc-visa"></i> <i class="fab fa-cc-mastercard"></i>';
	}
	
	public static function template($template)
	{
		if(get_query_var('sealasperlas'))
		{
			return locate_template( array( 'page.php' ) );	
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