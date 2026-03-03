<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Accomplishment extends CI_Controller
{
	private $_salary 	= null,
		$_render	= null,
		$_type      = null;


	public function __construct()
	{

		parent::__construct();


		//$this->load->library('timeago');
		$this->load->library('login_validate');
		$this->login_validate->is_not_login();

		$this->load->model('accomplishment_model');
		$this->load->model('user_model');

		$this->_user_id = $this->session->userdata('overtime_user_id');
	}


	public function data()
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		$results = $this->accomplishment_model->selectAccomplishment($this->_user_id);
		if ($results) {
			foreach ($results as $key => $value) {
				$id 		= $value->id;
				$date  		= date('F d, Y', strtotime($value->overtime_date));
				$time_in 	= date('h:iA', strtotime($value->time_in));
				$time_out   = date('h:iA', strtotime($value->time_out));

				$now  		= new DateTime($time_in);
				$time 		= new DateTime($time_out);
				$render		= $now->diff($time)->format('%h hr, %i mins');

				$this->_render = $render;

				$remarks 	= $value->remarks;
				$other_day  = ucwords($value->other_day);
				$isWfh      = $value->is_wfh;

				$time 		= $time_in . ' - ' . $time_out;
				$isWfh 	    = ($isWfh) ? "<br><span class='badge badge-warning'>WFH</span>" : '';

				$url = base_url("accomplishment/edit/$id");
				$button = "<button class='btn btn-info btn-xs' onclick=loadForm('$url')><i class='far fa-edit'></i></button> ";
				$button .= "<button type='button' class='btn btn-danger btn-xs' onclick=deleteItem('$id') title='Delete'><i class='far fa-trash-alt'></i></button>";

				$sortedid 	= date('Ymd', strtotime($value->overtime_date));

				$data[] = array(
					'id' 		=> $sortedid,
					'date' 		=> $date . $isWfh,
					'time' 		=> $time,
					'render' 	=> $render,
					'remarks' 	=> $remarks,
					'day' 		=> $other_day,
					'button' 	=> $button,
				);
			}
		} else {
			$data[] = array(
				'id' 		=> 1,
				'date' 		=> '--',
				'time' 		=> '--',
				'render' 	=> '--',
				'remarks' 	=> '--',
				'day' 		=> '--',
				'button' 	=> '--',
			);
		}
		$array = array(
			'data' => $data
		);

		echo json_encode($array);
	}

	public function selectrender()
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}

		if ($this->input->post()) {
			$month_name = $this->input->post('month'); // e.g. "February"
			$year       = date('Y');
			// Convert month name -> "YYYY-MM" for the DB query
			$month_date = date('Y-m', strtotime("$month_name 1, $year"));

			$results     = $this->user_model->salary_grade($this->_user_id);
			$salary      = $results->salary;
			$appointment = $results->appointment;

			$result = array_reverse(
				$this->accomplishment_model->selectAccomplishmentfilter($this->_user_id, $month_date)
			);

			$hours_render = 0;
			$pay          = 0;

			if ($result) {
				foreach ($result as $value) {
					$time_in  = date('h:iA', strtotime($value->time_in));
					$time_out = date('h:iA', strtotime($value->time_out));

					$now  = new DateTime($time_in);
					$time = new DateTime($time_out);
					$diff = $now->diff($time);
					$rendered_hours = $diff->h + ($diff->i / 60);
					$hours_render  += $rendered_hours;

					$other_day = ucwords($value->other_day);
					$weekdays  = 1.25;
					$weekends  = 1.50;

					if ($appointment == 1) {
						$incentive = 1;
					} else {
						if (in_array($other_day, ['Saturday', 'Sunday', 'Holiday'])) {
							$incentive = $weekends;
						} else {
							$incentive = $weekdays;
						}
					}

					$per_hour = $salary / 22 / 8;
					$pay += $per_hour * $incentive * $rendered_hours;
				}
			}

			$array = array(
				'success'      => true,
				'hours_render' => round($hours_render, 2),
				'pay'          => number_format($pay, 2, '.', ','),
				'month'        => $month_name,
			);

			echo json_encode($array);
		}
	}


	public function index()
	{
		$results  = $this->user_model->salary_grade($this->_user_id);
		if ($results === null) {
			redirect("accomplishment", 'refresh');
			die();
		} else {
			$salary 		= $results->salary;
			$purpose 		= $results->purpose;
			$appointment 	= $results->appointment;
			//$month 	= $this->input->post('month');
			$month = date('Y-m', mktime(0, 0, 0, date('m')));
			// $month = date('Y-m', mktime(0, 0, 0, date('m')-1));
			//echo "<script>console.log('Debug Objects: " . $month	  . "' );</script>";

			//$month = json_encode($_POST);
			$result = array_reverse($this->accomplishment_model->selectAccomplishmentfilter($this->_user_id, $month));
			if ($result) {
				foreach ($result as $key => $value) {
					$time_in 	= date('h:iA', strtotime($value->time_in));
					$time_out   = date('h:iA', strtotime($value->time_out));

					$now  			= new DateTime($time_in);
					$time 			= new DateTime($time_out);
					$hours_render	+= $now->diff($time)->format('%h hr, %i mins');
					$other_day  	= ucwords($value->other_dday);
					//$hours_render 	+= $render;

					$weekdays = 1.25;
					$weekends = 1.50;
					//APPOINTMENT 1 is contractual, 2 is casual
					if ($appointment == 1) {
						$incentive = 1;
					} else {
						if ($other_day == "Saturday" || $other_day == "Sunday" || $other_day == "Holiday") {
							$incentive = $weekends;
						} else {
							$incentive = $weekdays;
						}
					}
					//$this->_salary 			= $results->salary;
					$per_hour = $salary / 22 / 8;
					$pay = $per_hour * $incentive * $hours_render;
				}
			} else {
				$hours_render = 0;
			}
			$month = date('F', mktime(0, 0, 0, date('m')));

			$data = array(
				'hours_render' 	=>  $hours_render,
				'month' 	=>  $month,
				'pay' 		=>  $pay,
				'purpose' 		=>  $purpose,
			);

			$this->data['data'] 	= $data;
			$this->data['details'] 	= $results;
			$this->data['title'] = "Accomplishment";
			$this->load->view('accomplishment_view', $this->data);
		}
	}

	public function add()
	{
		$success = false;
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		if ($this->input->post()) {

			//validation
			$config = array(
				array(
					'field' => 'date',
					'label' => 'Date',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'time',
					'label' => 'Time',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'weekend',
					'label' => 'Weekend',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'remarks',
					'label' => 'Remarks',
					'rules' => 'trim|min_length[3]|max_length[5000]'
				),
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == TRUE) {

				$time_array =  explode('-', $this->input->post('time'));
				$time_in 	=  date('G:i', strtotime($time_array[0]));
				$time_out 	=  date('G:i', strtotime($time_array[1]));
				$remarks 	= $this->input->post('remarks');
				$wfh 		= ($this->input->post('wfh') == 'on') ? 1 : 0;

				$date 		= date('Y-m-d', strtotime($this->input->post('date')));

				$other_day 	= $this->input->post('weekend');

				$data = array(
					'user_id' 		=> $this->_user_id,
					'overtime_date' => $date,
					'time_in' 		=> $time_in,
					'time_out' 		=> $time_out,
					'remarks' 		=> $remarks,
					'other_day' 	=> $other_day,
					'is_wfh' 		=> $wfh,
				);

				$this->accomplishment_model->insert($data);

				$message 	=  "Successfully Added!";

				$success = true;
			} else {

				$error_msg = array_values($this->form_validation->error_array())[0];
				$message 	= $error_msg;
			}

			$array = array(
				'success' 	=> $success,
				'msg' 		=> $message
			);

			echo json_encode($array);
		}
	}

	public function edit($id = null)
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}

		$result  = $this->accomplishment_model->getAccomplishment($id);
		if ($result === null) {
			redirect("accomplishment", 'refresh');
			die();
		}

		$this->data['details'] 	= $result;
		$this->data['title']	= 'Edit Accomplishment';
		echo $this->load->view('accomplishment_edit', $this->data, true);
	}

	public function update_process()
	{

		$success = false;
		$message = 'Something went wrong';

		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		if ($this->input->post()) {

			//validation
			$config = array(
				array(
					'field' => 'date',
					'label' => 'Date',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'time',
					'label' => 'Time',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'weekend',
					'label' => 'Weekend',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'remarks',
					'label' => 'Remarks',
					'rules' => 'trim|min_length[3]|max_length[5000]'
				),
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == TRUE) {
				if ($id = $this->input->post('user_id')) {
					$time_array =  explode('-', $this->input->post('time'));
					$time_in 	=  date('G:i', strtotime($time_array[0]));
					$time_out 	=  date('G:i', strtotime($time_array[1]));
					$remarks 	= $this->input->post('remarks');
					$wfh 		= ($this->input->post('wfh') == 'on') ? 1 : 0;

					$date 		= date('Y-m-d', strtotime($this->input->post('date')));

					$other_day 	= $this->input->post('weekend');

					$data = array(
						'user_id' 		=> $this->_user_id,
						'overtime_date' => $date,
						'time_in' 		=> $time_in,
						'time_out' 		=> $time_out,
						'remarks' 		=> $remarks,
						'other_day' 	=> $other_day,
						'is_wfh' 		=> $wfh,
					);

					$this->accomplishment_model->update($id, $data);

					$message 	=  "Successfully Updated!";
					$success = true;
				}
			} else {

				$error_msg = array_values($this->form_validation->error_array())[0];
				$message 	= $error_msg;
			}

			$array = array(
				'success' 	=> $success,
				'msg' 		=> $message
			);

			echo json_encode($array);
		}
	}


	public function delete()
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		if ($this->input->get()) {

			$id 	= $this->input->get('id');
			$result = $this->accomplishment_model->getAccomplishment($id);

			if ($result) {

				$this->accomplishment_model->delete($id);
				$message 	=  "Successfully deleted";

				$array = array(
					'msg' 		=> $message
				);

				echo json_encode($array);
			}
		}
	}/*end delete*/
}