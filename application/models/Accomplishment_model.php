<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accomplishment_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function selectAccomplishmentList($user_id = null, $month = null, $wfh = 0)
	{
		$this->db->select('id, overtime_date, time_in, time_out, remarks, other_day, is_wfh');
		$this->db->from('accomplishment');
		$this->db->where('user_id', $user_id);
		$this->db->where('is_wfh', $wfh);
		$this->db->where('DATE_FORMAT(overtime_date,"%Y-%m")', $month);
		$this->db->order_by('overtime_date');
		return $this->db->get()->result();
	}

	public function selectAccomplishment($user_id = null)
	{
		$this->db->select('id, overtime_date, time_in, time_out, remarks, other_day, is_wfh');
		$this->db->from('accomplishment');
		$this->db->where('user_id', $user_id);
		$this->db->order_by('overtime_date');
		return $this->db->get()->result();
	}

	public function selectAccomplishmentfilter($user_id = null, $month = null)
	{
		//$month = "2023-09";
		$this->db->select('id, overtime_date, time_in, time_out, remarks, other_day, is_wfh');
		$this->db->from('accomplishment');
		$this->db->where('user_id', $user_id);
		$this->db->where('DATE_FORMAT(overtime_date,"%Y-%m")', $month);
		$this->db->order_by('overtime_date');
		return $this->db->get()->result();
	}

	public function getAccomplishment($id = null)
	{
		$this->db->select('id, overtime_date, time_in, time_out, remarks, other_day, is_wfh');
		$this->db->from('accomplishment');
		$this->db->where('id', $id);
		return $this->db->get()->row();
	}

	public function insert($data = array())
	{
		$this->db->insert('accomplishment', $data);
		return $this->db->insert_id();
	}

	public function update($id = null, $data = array())
	{
		$this->db->where('id', $id);
		$this->db->update('accomplishment', $data);
	}

	public function delete($id = null)
	{
		$this->db->delete('accomplishment', ['id' => $id]);
	}
}
