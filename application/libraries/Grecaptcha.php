<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grecaptcha{

	var $CI;

	public function __construct(){
		$this->CI =& get_instance();
	}

	public function validate(){
		$endpoint   = 'https://www.google.com/recaptcha/api/siteverify';
		$secret_key = '6LdqJ60aAAAAAAVQ0zIWxs8TTUhnlb4Unur7A6-t';
		//$secret_key = '6LfMdJ8aAAAAAInstx7V3ZsokcFK-821CZzthyC_';
		$captcha    = $this->CI->input->post('g-recaptcha-response');

		$URL 		= $endpoint . '?secret='. $secret_key . '&response=' . $captcha;
		$response   = file_get_contents($URL);
		$json_array = json_decode($response, TRUE);


		if ($json_array['success'] == TRUE){
			return true;
		}else{	
			return false;
		}
	}
	
}