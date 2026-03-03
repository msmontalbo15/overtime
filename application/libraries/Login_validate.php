<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_validate{

	var $CI;

	private $_permissions = array(),
			$_class_id = null,
			$_class = '',
			$_user_id = null;

	public function __construct(){

		$this->CI =& get_instance();
	}

	public function is_admin(){

		if($this->CI->session->userdata('overtime_type') < 2){
			redirect('login');
		}	
	}

	public function is_superadmin(){

		if($this->CI->session->userdata('overtime_type') != 3){
			redirect('accomplishment');
		}	
	}

	public function is_login(){

		if($this->CI->session->userdata('overtime_user_id')){
			redirect('accomplishment');
		}	
	}

	public function is_not_login(){

		if(!$this->CI->session->userdata('overtime_user_id')){
			redirect('login');
		}	
	}

}