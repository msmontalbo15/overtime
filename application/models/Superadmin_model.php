<?php defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /* ── Settings ───────────────────────────────────────────── */

    public function get_setting($key)
    {
        $row = $this->db->get_where('system_settings', ['setting_key' => $key])->row();
        return $row ? $row->setting_value : null;
    }

    public function get_all_settings()
    {
        $results = $this->db->get('system_settings')->result();
        $map = [];
        foreach ($results as $r) {
            $map[$r->setting_key] = $r->setting_value;
        }
        return $map;
    }

    public function save_setting($key, $value)
    {
        $exists = $this->db->get_where('system_settings', ['setting_key' => $key])->row();
        if ($exists) {
            $this->db->where('setting_key', $key);
            $this->db->update('system_settings', ['setting_value' => $value]);
        } else {
            $this->db->insert('system_settings', ['setting_key' => $key, 'setting_value' => $value]);
        }
    }

    public function save_settings_bulk(array $data)
    {
        foreach ($data as $key => $value) {
            $this->save_setting($key, $value);
        }
    }

    /* ── Admins / Super admins ──────────────────────────────── */

    public function get_admins()
    {
        // type 2 = admin, type 3 = superadmin
        $this->db->select('id, first_name, last_name, username, user_type, status');
        $this->db->from('user');
        $this->db->where_in('user_type', [2, 3]);
        $this->db->order_by('last_name');
        return $this->db->get()->result();
    }

    public function set_user_type($user_id, $type)
    {
        $this->db->where('id', $user_id);
        $this->db->update('user', ['user_type' => (int) $type]);
    }
}