<?php

class sealasperlas_validators
{
	public static function booking_details()
	{
		$output = false;
		$invalids = 0;
		global $sea_booking_details;
		$params = array('s_origin', 's_destination', 's_free', 's_discount', 's_adults', 's_ferry', 's_departure_date');
		array_push($params, 's_departure_hour', 's_return_date', 's_return_hour', 'sea_pax', 'itineray', 'sea_total', 'sea_description');
		
		if(isset($sea_booking_details))
		{
			$output = true;
		}
		else
		{
			if(isset($_POST['sea_pax']))
			{
				if($_POST['sea_pax'] != '')
				{
					for($x = 0; $x < count($params); $x++)
					{
						if(!isset($_POST[$params[$x]]))
						{
							$invalids++;
							wp_die('Param not sent: '.esc_html($params[$x]), 'Error');
						}
						else
						{
							if($_POST[$params[$x]] == '')
							{
								if(!$params[$x] == 's_return_date' && !$_POST['s_ferry'] == 0)
								{
									$invalids++;
									wp_die('Param empty: '.esc_html($params[$x]), 'Error');
								}
							}
						}
					}

					if($invalids == 0)
					{
						$output = true;
						$GLOBALS['sea_booking_details'] = $output;
					}	
				}
			}
		}
		return $output;
	}	
	public static function contact_details()
	{
		$output = false;
		$invalids = 0;
		$params = array('fname', 'lastname', 'phone', 'email', 'country', 'address');
		global $contact_details;
		
		if(isset($contact_details))
		{
			$output = true;
		}
		else
		{
			for($x = 0; $x < count($params); $x++)
			{
				if(!isset($_POST[$params[$x]]))
				{
					$invalids++;
					wp_die('Param not sent: '.esc_html($params[$x]), 'Error');					
				}
				else
				{
					if($_POST[$params[$x]] == '')
					{
						$invalids++;
						wp_die('Param empty: '.esc_html($params[$x]), 'Error');						
					}
				}
			}
			
			if($invalids == 0)
			{
				$output = true;
				$GLOBALS['contact_details'] = $output;				
			}
		}
		return $output;
	}
	public static function validate_checkout()
	{
		$output = false;
		global $validate_checkout;
		
		if(isset($validate_checkout))
		{
			$output = true;
		}
		else
		{
			if(self::contact_details() && self::booking_details() && self::credit_card())
			{
				$output = true;
				$GLOBALS['validate_checkout'] = $output;
			}		
		}
		return $output;
	}
	public static function credit_card()
	{
		$output = false;
		$invalids = 0;
		$params = array('CCNum', 'ExpMonth', 'ExpYear', 'CVV2');
		global $credit_card;
		
		if(isset($credit_card))
		{
			return true;
		}
		else
		{
			for($x = 0; $x < count($params); $x++)
			{
				if(!isset($_POST[$params[$x]]))
				{
					$invalids++;
					wp_die('Param not sent: '.esc_html($params[$x]), 'Error');						
				}
				else
				{
					if($_POST[$params[$x]] == '')
					{
						$invalids++;
						wp_die('Param empty: '.esc_html($params[$x]), 'Error');							
					}
					else
					{
						if($params[$x] == 'CCNum')
						{
							if(strlen($_POST[$params[$x]]) != 16)
							{
								$invalids++;
								wp_die(__('Invalid Credit Card Number. We only accept Visa or Mastercard.', 'sealasperlas').': '.esc_html($params[$x]), 'Error');
							}
						}						
						else if($params[$x] == 'CVV2')
						{
							if(strlen($_POST[$params[$x]]) != 3)
							{
								$invalids++;
								wp_die(__('Invalid CVV. Check the numbers on the back numbers. We only accept Visa or Mastercard.', 'sealasperlas').': '.esc_html($params[$x]), 'Error');
							}
						}

						else if($params[$x] == 'ExpMonth' || $params[$x] == 'ExpYear')
						{
							if(strlen($_POST[$params[$x]]) != 2)
							{
								$invalids++;
								wp_die('Invalid Expiration: '.esc_html($params[$x]), 'Error');
							}
						}						
					}
				}
			}
			
			if($invalids == 0)
			{
				$output = true;
				$GLOBALS['credit_card'] = $output;				
			}	
		}
		return $output;
	}	
	public static function validate_recaptcha()
	{
		$output = false;
		global $valid_recaptcha;
		
		if(isset($valid_recaptcha))
		{
			$output = true;
		}
		else
		{
			if(get_option('captcha_secret_key') && isset($_POST['sea_recaptcha']))
			{
				if($_POST['sea_recaptcha'] != '')
				{
					$data = array();
					$data['secret'] = get_option('captcha_secret_key');
					$data['remoteip'] = $_SERVER['REMOTE_ADDR'];
					$data['response'] = sanitize_text_field($_POST['sea_recaptcha']);
					$url = 'https://www.google.com/recaptcha/api/siteverify';
					$verify = curl_init();
					curl_setopt($verify, CURLOPT_URL, $url);
					curl_setopt($verify, CURLOPT_POST, true);
					curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
					curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
					$verify_response = json_decode(curl_exec($verify), true);
					
					if($verify_response['success'] === true)
					{
						$output = true;
						$GLOBALS['valid_recaptcha'] = $output;
					}
					if(array_key_exists('error-codes', $verify_response))
					{
						wp_die(__('Invalid Recaptcha', 'sealasperlas').': '.json_encode($verify_response['error-codes']), 'Error');
					}				
				}
				else
				{
					wp_die(__('Invalid Recaptcha is NULL.', 'sealasperlas'), 'Error');
				}
			}
			else
			{
				wp_die(__('Recaptcha not sent.', 'sealasperlas'), 'Error');
			}
		}
		return $output;
	}	
}

?>