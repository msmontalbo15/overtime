<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Alert{

	var $CI;

	public function __construct(){
		$this->CI =& get_instance();
	}


	public function generate($data = array()){
		$code 	 = $data['code'];
		if($code){
			$alert_data = array(
				'type'		=> $data['type'],
				'message' 	=> $data['message']
			);
			$this->CI->session->set_flashdata($code, $alert_data);
		}
	}

	public function get_alert($code = null){
		if($this->CI->session->flashdata($code)){
			$detail =  $this->CI->session->flashdata($code);
			$type	= $detail['type'];
			$msg 	= $detail['message'];

			$output = "<div class='alert alert-{$type} alert-dismissible'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<h5>Alert!</h5>
						{$msg}
					</div>";
			return $output;
		}
		return '';		
	}

	public function get_sweet_alert($code = null){
		if($this->CI->session->flashdata($code)){
			$detail =  $this->CI->session->flashdata($code);
			$type	= $detail['type'];
			$msg 	= $detail['message'];

			$output = "Toast.fire({
				          icon: '$type',
				          title: '$msg'
				      });";
			return $output;
		}
		//return '';		

	}



}