<?php defined('BASEPATH') or exit('No direct script access allowed');

class Summary_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	public function queryData($query)
	{
		return $this->db->query($query);
	}
	public function get_summary($id = null)
	{
		$this->db->select('*');
		$this->db->from('overtime');
		$this->db->where('id', $id);
		return $this->db->get()->row();
	}

	public function get_filter($month = null)
	{
		$this->db->select('id AS list_users');
		$this->db->from('user');
		$this->db->where("id IN (SELECT DISTINCT user_id FROM accomplishment WHERE overtime_date LIKE '" . $month . "')", NULL, FALSE);
		$this->db->order_by('last_name');
		return $this->db->get()->result();
	}

	public function select_user_logs($user_id = null, $month = null)
	{
		$this->db->select('user_id, overtime_date, time_in, time_out, other_day');
		$this->db->from('tbl_overtime');
		$this->db->where('user_id', $user_id);
		$this->db->where('request_id', $month);
		$this->db->order_by('overtime_date');
		return $this->db->get()->result();
	}

	public function select_summary()
	{
		$this->db->select('id, request_date, activities, list_users');
		$this->db->from('overtime');
		return $this->db->get()->result();
	}

	public function update($id = null, $data = array())
	{
		$this->db->where('id', $id);
		$this->db->update('overtime', $data);
	}

	public function insert($data = array())
	{
		$this->db->insert('overtime', $data);
		return $this->db->insert_id();
	}
	public function delete($id = null)
	{
		$this->db->delete('overtime', ['id' => $id]);
	}
}
