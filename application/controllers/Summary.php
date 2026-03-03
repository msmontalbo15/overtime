<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Summary extends CI_Controller
{


	public function __construct()
	{

		parent::__construct();

		$this->load->library('login_validate');
		$this->login_validate->is_not_login();
		$this->login_validate->is_admin();

		$this->load->model('summary_model');
		$this->load->model('user_model');
		$this->load->model('accomplishment_model');
		if ($this->session->userdata('overtime_type') < 2) {
			redirect('accomplishment');
		}
	}

	public function list()
	{
		$this->load->library('pagination');
		$limit  = 3; //no. of display
		$offset = 0;

		if ($this->input->get('page') != '') {
			$rowNo = $this->input->get('page');
			$offset = ($rowNo - 1) * $limit;
		}

		$SQL = "SELECT id, request_date, activities, list_users
				FROM overtime";
		$rows = $this->summary_model->queryData($SQL)->num_rows();

		$SQL  .= " ORDER BY request_date DESC, id DESC LIMIT $limit OFFSET $offset";
		$query = $this->summary_model->queryData($SQL);

		$config['base_url']   			= base_url('summary/list');
		$config['total_rows'] 			= $rows;
		$config['per_page']   			= $limit;
		$config['num_links'] 			= 4;
		$config['query_string_segment'] = 'page';
		$config['page_query_string'] 	= TRUE;
		$config['use_page_numbers']  	= TRUE;

		$this->pagination->initialize($config);

		$queries  = $query->result();

		$output = "";
		if ($queries) {
			foreach ($queries as $key => $value) {
				$id     = $value->id;
				$date   = date('F Y', strtotime($value->request_date));
				$users  = count(json_decode($value->list_users));

				$li = 'No Activities';
				if ($value->activities != '') {
					$li = "<ul class='text-muted'>";
					$activities = explode('|', $value->activities);
					foreach ($activities as $key => $value) {
						$li .= "<li>$value</li>";
					}
					$li .= "</ul>";
				}

				$url = base_url();

				$output .= "<div class='col-xl-4 col-lg-4  col-md-6 d-flex align-items-stretch flex-column' id='div-$id'>
				<div class='card bg-light d-flex flex-fill'>
					<div class='card-body'>
						<h2 class='lead'>$date<smal><button type='button' class='btn btn-link text-red btn-sm float-right p-0' onclick=deleteItem('$id') title='Delete'><i class='far fa-trash-alt'></i></button> <button onclick=loadForm('{$url}summary/edit///$id') class='btn btn-sm btn-link float-right p-0'>
									<i class='far fa-edit'></i>
								</button></smal>
						</h2>
						$li
					</div>
					<div class='card-footer'>
						<span class='float-left'>$users users </span>			
						<div class='text-right'>
						<form id='printSummary-$id' onsubmit=printSummary(event,$id)>
							<input type='hidden' name='id' value='$id'>
							<input type='hidden' name='page' value='$id'>
							<input type='date' class='float' name='date' value=''>
							<button type='submit' class='btn btn-sm btn-secondary'>
								Print
							</button>
							<br>
							<div class='text-right' style='padding-top: 10px;'->
								<label class='form-check-label' for='form'>Form: </label>
								<select id='printtype' name='printtype' style='padding: 3px;'>
									<option selected value='summary_print'>Authority To Render</option>
									<option value='purpose_print'>Purpose</option>
								</select>
							</div>
							<div class='text-right' style='padding-top: 10px;'->
								<label class='form-check-label' for='appointment'>Appointment: </label>
								<select id='appointment' name='appointment' style='padding: 3px;'>
									<option selected value='2'>Casual</option>
									<option value='1'>Contractual</option>
								</select>
							</div>
						</form>
					</div>
					</div>
				</div>
			</div>";
			}
		}
		$data = "<div class='row'>$output</div>";

		echo json_encode(array(
			'paging' => $this->pagination->create_links(),
			'data'   => $data
		));
	}


	public function index()
	{
		$this->data['title'] 	= 'Monthly Summary';
		$this->load->view('summary_view', $this->data);
	}

	public function add($month = null)
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		$curr_month = date('Y-m', mktime(0, 0, 0, date('m') - 1));
		//$month 	= date('Y-m', strtotime($this->input->get('month')));

		$month = $curr_month . '%';
		//echo "<script>console.log('Debug Controller: " . $month . "' );</script>";
		$result  = $this->summary_model->get_filter($month);
		if ($result === null) {
			//redirect("summary", 'refresh');
			die();
		}

		$this->data['details'] 	= $result;
		$this->data['users']	= $this->user_model->select_users();
		$this->data['title'] 	= 'Add Monthly Summary';
		echo $this->load->view('summary_add', $this->data, true);
	}

	public function add_process()
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}

		$success = false;
		$message = 'Something went wrong';

		if ($this->input->post()) {
			//validation
			$config = array(
				array(
					'field' => 'month',
					'label' => 'Month',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'tags',
					'label' => 'Activities',
					'rules' => 'trim'
				),
				array(
					'field' => 'users[]',
					'label' => 'Users',
					'rules' => 'trim|required'
				),
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == TRUE) {

				$month 	= date('Y-m-01', strtotime($this->input->post('month')));
				$tags 	= $this->input->post('tags');
				$users  = $this->input->post('users');


				$user_status = true;
				foreach ($users as $key => $value) {

					if (!$this->user_model->get_user($value)) {
						$user_status = false;
						break;
					}
				}

				if ($user_status) {
					$data = array(
						'request_date' 	=> $month,
						'activities' 	=> $tags,
						'list_users' 	=> json_encode($users),
					);

					$new_id = $this->summary_model->insert($data);

					$message = "Successfully Added!";
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

	public function edit($id = null)
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		$result  = $this->summary_model->get_summary($id);
		if ($result === null) {
			redirect("summary", 'refresh');
			die();
		}

		$this->data['details'] 	= $result;
		$this->data['users']	= $this->user_model->select_users();
		$this->data['title'] 	= 'Edit Monthly Summary';
		echo $this->load->view('summary_edit', $this->data, true);
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
					'field' => 'month',
					'label' => 'Month',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'tags',
					'label' => 'Activities',
					'rules' => 'trim'
				),
				array(
					'field' => 'users[]',
					'label' => 'Users',
					'rules' => 'trim|required'
				),
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == TRUE) {

				$month 	= date('Y-m-01', strtotime($this->input->post('month')));
				$tags 	= $this->input->post('tags');
				$users  = $this->input->post('users');

				$user_status = true;
				foreach ($users as $key => $value) {

					if (!$this->user_model->get_user($value)) {
						$user_status = false;
						break;
					}
				}

				if ($user_status) {
					$data = array(
						'request_date' 	=> $month,
						'activities' 	=> $tags,
						'list_users' 	=> json_encode($users),
					);

					$id = $this->input->post('summary_id');
					$new_id = $this->summary_model->update($id, $data);

					$message = "Successfully Updated!";
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

	public function print_process()
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		$success = false;
		$message = 'Something went wrong';
		$id 	= $this->input->get('id');
		$appointment 	= $this->input->get('appointment');
		$printtype 	= $this->input->get('printtype');
		$date 	= $this->input->get('date');

		if ($id && $date && $appointment) {
			$details  = $this->summary_model->get_summary($id);
			if ($details !== null) {

				$message = 'loaded success!';
				$success = true;
				$params  = "print?id=$id&date=$date&appointment=$appointment&printtype=$printtype";
			}
		}

		$array = array(
			'success' 	=> $success,
			'msg' 		=> $message
		);

		if ($success) {
			$array['params'] = $params;
		}

		echo json_encode($array);
	}

	public function print()
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		$id 	= $this->input->get('id');
		$appointment 	= $this->input->get('appointment');
		$printtype 	= $this->input->get('printtype');
		$date 	= $this->input->get('date');
		if ($id && $date) {
			$details  = $this->summary_model->get_summary($id);

			if ($details !== null) {

				$month  		= date('Y-m', strtotime($details->request_date));
				$user_info 		= array();
				$user_logs  	= array();
				$user_result 	= array();

				$list_users 	= json_decode($details->list_users);

				foreach ($list_users as $key => $value) {

					$user_detail = $this->user_model->select_appointment($appointment, $value);

					//var_dump($user_appointment);
					if ($user_detail !== null) {

						//$user_detail = $this->user_model->get_user($value);
						$fname 		= strtoupper($user_detail->first_name);
						$lname 		= strtoupper($user_detail->last_name);
						$purpose 		= strtoupper($user_detail->purpose);

						if ($user_detail->middle) {
							$mname 		= strtoupper($user_detail->middle) . '.';
						} else {
							$mname = '';
						}


						$logs = $this->accomplishment_model->selectAccomplishmentList($value, $month);

						if ($logs) {
							foreach ($logs as $key => $value) {

								$user_logs[] = array(
									'date' 		=> $value->overtime_date,
									'time_in'	=> $value->time_in,
									'time_out' 	=> $value->time_out,
									'remarks' 	=> $value->remarks,
									'otherday'  => $value->other_day
								);
							}
						}

						$user_result[] = array(
							'name' 		=> strtoupper($fname . " " . $mname . " " . $lname),
							'purpose' 		=> $purpose,
							'logs' 		=> $user_logs
						);

						$user_logs = array();
					}
				}
			}

			$this->data['date'] 	= $date;
			$this->data['appointment'] 	= $appointment;
			$this->data['details'] 	= $details;
			$this->data['results'] 	= $user_result;
			$this->data['users']	= $this->user_model->select_users();
			$this->data['title']	= 'Print Monthly Summary';
			$this->load->view($printtype, $this->data);
		}
	}


	public function delete()
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		if ($this->input->get()) {

			$id 	= $this->input->get('id');
			$result = $this->summary_model->get_summary($id);

			if ($result) {

				$this->summary_model->delete($id);
				$message 	=  "Successfully deleted";

				$array = array(
					'msg' 		=> $message
				);

				echo json_encode($array);
			}
		}
	}/*end delete*/
}