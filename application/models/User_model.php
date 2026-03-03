<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_user($user_id = null)
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('id', $user_id);
		return $this->db->get()->row();
	}

	public function select_active_user()
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('status', 1);
		$this->db->order_by('last_name');
		return $this->db->get()->result();
	}

	public function select_users()
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->order_by('last_name');
		return $this->db->get()->result();
	}

	public function salary_grade($user_id = null)
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('salary_grade ', 'user.grade = salary_grade.grade', 'left'); //this is the left join in codeigniter
		$this->db->where('id', $user_id);
		return $this->db->get()->row();
	}

	/*
	public function filter_users($month = null)
	{
		//$month = '2023-05%';
		//echo "<script>console.log('Debug Objects: " . $month . "' );</script>";
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where("id IN (SELECT DISTINCT user_id FROM accomplishment WHERE overtime_date LIKE '" . $month . "')", NULL, FALSE);
		$this->db->order_by('last_name');
		return $this->db->get()->result();
	}
	*/

	public function select_appointment($appointment = null, $list_users = null)
	{
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('id', $list_users);
		$this->db->where('appointment', $appointment);
		//$this->db->order_by('last_name');
		return $this->db->get()->row();
	}

	public function update($id = null, $data = array())
	{
		$this->db->where('id', $id);
		$this->db->update('user', $data);
	}

	public function insert($data = array())
	{
		$this->db->insert('user', $data);
		return $this->db->insert_id();
	}
}
