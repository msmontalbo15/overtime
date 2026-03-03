<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller{


	public function __construct(){

		parent::__construct();
/*
		$this->load->library('login_validate');
		$this->login_validate->is_not_login();

		$this->load->model('accomplishment_model');
		$this->load->model('user_model');

		$this->_user_id = $this->session->userdata('overtime_user_id');*/
		
	}


	public function index(){
		$this->data['title'] = 'Monthly report';
	}


	public function add(){

	}

	public function edit(){

	}


}