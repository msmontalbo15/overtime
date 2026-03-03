<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

    public function check_username($username = null){
        $this->db->select('username');
        $this->db->from('user');
        $this->db->where('username', $username);
        return $this->db->get()->row();
    }

    public function user_login($username = null, $password = null){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

}