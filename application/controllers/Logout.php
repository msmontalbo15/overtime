<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller{

	public function __construct(){

		parent::__construct();

		//$this->load->library('log_file');
	
	}

	public function index(){
		//$this->log_file->create('LOGOUT', 'logout page', $this->session->userdata('user_id'));
		$this->session->unset_userdata('overtime_user_id');
		$this->session->unset_userdata('overtime_name');
		$this->session->unset_userdata('overtime_type');
		redirect('login');
		
	}

}