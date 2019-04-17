<?php

class sealasperlas_validators
{
	public static function booking_details()
	{
		return true;		
	}	
	public static function contact_details()
	{
		$output = false;
		global $contact_details;
		
		if(isset($contact_details))
		{
			$output = true;
		}
		else
		{
			if(isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['phone']) && isset($_POST['email']))
			{
				$GLOBALS['contact_details'] = true;
				$output = true;
			}
			else
			{
				$output = false;
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
			if(self::contact_details() && isset($_POST['country']) && isset($_POST['address']) && self::booking_details() && self::credit_card())
			{
				$GLOBALS['validate_checkout'] = true;
				$output = true;
			}
			else
			{
				$output = false;
			}			
		}
		return $output;
	}
	public static function credit_card()
	{
		$output = false;
		global $credit_card;
		
		if(isset($credit_card))
		{
			return true;
		}
		else
		{
			if(isset($_POST['CCNum']) && isset($_POST['ExpMonth']) && isset($_POST['ExpYear']) && isset($_POST['CVV2']))
			{
				$GLOBALS['credit_card'] = true;
				$output = true;
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
			if(get_query_var('sealasperlas') && get_option('captcha_secret_key'))
			{
				$data = array();
				$data['secret'] = get_option('captcha_secret_key');
				$data['remoteip'] = $_SERVER['REMOTE_ADDR'];
				$data['response'] = sanitize_text_field(get_query_var('sealasperlas'));
				$url = 'https://www.google.com/recaptcha/api/siteverify';			
				$verify = curl_init();
				curl_setopt($verify, CURLOPT_URL, $url);
				curl_setopt($verify, CURLOPT_POST, true);
				curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
				curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
				$verify_response = json_decode(curl_exec($verify), true);

				if($verify_response['success'] == true)
				{
					$GLOBALS['valid_recaptcha'] = true;
					$output = true;
				}
				if(array_key_exists('error-codes', $verify_response))
				{
					write_log(json_encode($verify_response['error-codes']));
				}
			}
		}
		return $output;
	}	
}

?>