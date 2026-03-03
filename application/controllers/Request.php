<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller{


	public function __construct(){

		parent::__construct();

		$this->load->library('login_validate');
		$this->login_validate->is_not_login();

		$this->load->model('accomplishment_model');
		$this->load->model('user_model');

		$this->_user_id = $this->session->userdata('overtime_user_id');
		
	}


	public function index(){

		$this->data['users'] 	= $this->user_model->select_active_user();
		$this->data['title'] 	= "Individual Request";
		$this->load->view('request_view', $this->data);
	}


	public function print_process(){
		if(!$this->input->is_ajax_request()){ exit('no valid request'); }
		$success = false;
		$message = 'Something went wrong';

		$month 	= $this->input->get('month');
		$date 	= $this->input->get('date');

		if($date && $month){
			$is_Wfh = $this->input->get('wfh');

			if($is_Wfh && $is_Wfh == 'on'){
				$isWfh = '&wfh=1';
			}else{
				$isWfh = '&wfh=0';
			}

			$params  = "print?month=$month&date=$date";

			if($this->session->userdata('overtime_type') == 2){
				$this->_user_id =$this->input->get('employee');
				$params  = "print?employee=$this->_user_id&month=$month&date=$date";
			}		

			$params = $params . $isWfh;

			$details  = $this->user_model->get_user( $this->_user_id );

			if($details !== null){

				$message = 'loaded success!';
				$success = true;
				

	   		}

		}else{
			$message = 'Month and Year is required';
		}

		$array = array(
			'success' 	=> $success,
			'msg' 		=> $message
		);

		if($success){
			$array['params'] = $params;
		}

		echo json_encode($array);
	}

	public function print(){
		$month 	= $this->input->get('month');
		$date 	= $this->input->get('date');

		if($month && $date){

			if($this->session->userdata('overtime_type') == 2){
				$this->_user_id =$this->input->get('employee');
			}	

			$wfh 	= $this->input->get('wfh');

			if($wfh == 1){
				$result = $this->accomplishment_model->selectAccomplishmentList($this->_user_id, date('Y-m', strtotime($month)), 1);
				$isWfh  = true;
			}else{
				$result = $this->accomplishment_model->selectAccomplishmentList($this->_user_id, date('Y-m', strtotime($month)));
				$isWfh  = false;
			}


			$info = array(
				'month' => $month,
				'date' 	=> $date
			);


			$this->data['info'] 	= $info;
			$this->data['lists'] 	= $result;
			$this->data['wfh'] 		= $isWfh;
			$this->data['details'] 	= $this->user_model->get_user($this->_user_id);
			$this->data['title'] 	= 'Individual Request';
			$this->load->view('request_print', $this->data);

		}
	}

}