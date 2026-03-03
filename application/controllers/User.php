<?php defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		$this->load->library('login_validate');
		$this->login_validate->is_not_login();
		$this->login_validate->is_admin();

		$this->load->model('user_model');
		$this->load->library('generate');
	}

	public function data()
	{

		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		$results = $this->user_model->select_users();
		if ($results) {
			foreach ($results as $key => $value) {
				$id 		= $value->id;
				$name 		= strtolower($value->last_name . ', ' . $value->first_name);
				$title 		= strtolower($value->possition);
				$color  = ($value->status) ? 'success' : 'danger';
				$active = ($value->status) ? 'Active' : 'Idle';

				$status = "<span class='badge badge-pill badge-$color'>$active</span>";

				$url = base_url("user/edit/$id");
				$button = "<button class='btn btn-info btn-xs' onclick=loadForm('$url')><i class='far fa-edit'></i></button> ";

				$data[] = array(
					'id' 		=> $id,
					'name' 		=> ucwords($name),
					'title' 	=> strtoupper($title),
					'status' 	=> $status,
					'button' 	=> $button,
				);
			}
		} else {
			$data[] = array(
				'id' 		=> 1,
				'name' 		=> '--',
				'title' 	=> '--',
				'status' 	=> '--',
				'button' 	=> '--',
			);
		}

		$array = array(
			'data' => $data
		);

		echo json_encode($array);
	}

	public function index()
	{

		$this->data['title'] 		= "User";
		$this->load->view('user_view', $this->data);
	}

	public function add()
	{
		if (!$this->input->is_ajax_request()) {
			exit('no valid request');
		}
		$this->data['title'] 	= 'User';
		echo $this->load->view('user_add', $this->data, true);
	}

	public function add_process()
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
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|min_length[4]|max_length[15]|is_unique[user.username]',
					'errors' => array(
						'is_unique' => '{field} is already exists'
					)
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[7]|max_length[20]'
				),
				array(
					'field' => 'fname',
					'label' => 'First Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]'
				),
				array(
					'field' => 'lname',
					'label' => 'Last Name',
					'rules' => 'trim|required|min_length[2]|max_length[30]'
				),
				array(
					'field' => 'mname',
					'label' => 'Middle Name',
					'rules' => 'trim|min_length[1]|max_length[2]'
				),
				array(
					'field' => 'position',
					'label' => 'Position',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'grade',
					'label' => 'Grade',
					'rules' => 'trim|min_length[1]|max_length[2]|required'
				),
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == TRUE) {

				$hash = $this->generate->hash_data($this->input->post('password'));

				$data = array(
					'username' 		=> strtolower($this->input->post('username')),
					'password' 		=> $hash,
					'first_name' 	=> strtoupper($this->input->post('fname')),
					'last_name' 	=> strtoupper($this->input->post('lname')),
					'middle' 		=> strtoupper($this->input->post('mname')),
					'possition' 	=> strtoupper($this->input->post('position')),
					'grade' 	=> strtoupper($this->input->post('grade')),
				);

				$this->user_model->insert($data);

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

		$result  = $this->user_model->get_user($id);
		if ($result === null) {
			redirect("user", 'refresh');
			die();
		}

		$this->data['title']	= 'Edit User';
		$this->data['details'] 	= $result;
		echo $this->load->view('user_edit', $this->data, true);
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
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|min_length[4]|max_length[20]'
				),
				array(
					'field' => 'fname',
					'label' => 'First Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]'
				),
				array(
					'field' => 'lname',
					'label' => 'Last Name',
					'rules' => 'trim|required|min_length[2]|max_length[30]'
				),
				array(
					'field' => 'mname',
					'label' => 'Middle Name',
					'rules' => 'trim|min_length[1]|max_length[2]'
				),
				array(
					'field' => 'position',
					'label' => 'Position',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'grade',
					'label' => 'Grade',
					'rules' => 'trim|required'
				),
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == TRUE) {

				if ($id = $this->input->post('user_id')) {

					$status 	= ($this->input->post('status')) ? 1 : 0;

					$data = array(
						'first_name' 	=> strtoupper($this->input->post('fname')),
						'last_name' 	=> strtoupper($this->input->post('lname')),
						'middle' 		=> strtoupper($this->input->post('mname')),
						'status' 		=> $status,
						'possition' 	=> strtoupper($this->input->post('position')),
						'grade' 	=> strtoupper($this->input->post('grade')),
					);

					if ($this->input->post('password')) {
						$hash = $this->generate->hash_data($this->input->post('password'));
						$data['password'] = $hash;
					}

					$this->user_model->update($id, $data);

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

	public function export()
	{

		$headers = [
			'NAME',
			'SIGNATURE'
		];

		$dataObj =  $this->user_model->select_users();

		// Excel file name for download 
		$fileName = 'dcu_users_' . date('Y-m-d') . ".xls";

		// Display column names as first row 
		$excelData = implode("\t", array_values($headers)) . "\n";

		if ($dataObj) {
			foreach ($dataObj as $key => $value) {

				$name = strtoupper($value->last_name . ', ' . $value->first_name);

				$lineData = [
					$name,
					''
				];

				//array_walk($lineData, $this->filterData()); 
				$excelData .= implode("\t", array_values($lineData)) . "\n";
			}
		} else {
			$excelData .= 'No records found...' . "\n";
		}
		// Headers for download 
		//header('Content-Encoding: UTF-8');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$fileName\"");
		//echo "\xEF\xBB\xBF"; // UTF-8 BOM
		echo $excelData;
	}
}
