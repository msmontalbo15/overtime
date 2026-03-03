<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Generate{

	var $CI;

	public function __construct(){
		$this->CI =& get_instance();
	}

	public function hash_data($data = null){
	    $key 	= $this->CI->config->item('encryption_key');
	    $salt1 	= hash('sha512', $key . $data);
	    $salt2 	= hash('sha512', $data . $key);
	    return hash('sha512', $salt1 . $data . $salt2);
	}

	public function randomData($length = 4) {
	    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*()_+';
	    $data = array(); //remember to declare $data as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

		    for ($i = 0; $i < $length; $i++) {
		        $n = rand(0, $alphaLength);
		        $data[] = $alphabet[$n];
		    }
	    	return implode($data); //turn the array into a string
	}	


	public function randomSlag() {
	    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
	    $data = array(); //remember to declare $data as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

		    for ($i = 0; $i < 5; $i++) {
		        $n = rand(0, $alphaLength);
		        $data[] = $alphabet[$n];
		    }
	    	return implode($data); //turn the array into a string
	}	

}