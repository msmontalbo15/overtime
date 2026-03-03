<?php defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin extends CI_Controller
{
    private $_user_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('login_validate');
        $this->login_validate->is_not_login();
        $this->login_validate->is_superadmin();

        $this->_user_id = $this->session->userdata('overtime_user_id');

        $this->load->model('superadmin_model');
        $this->load->model('user_model');
        $this->load->library('upload_file');
    }

    /* ── Main page ──────────────────────────────────────────── */

    public function index()
    {
        $this->data['title']    = 'Super Admin';
        $this->data['settings'] = $this->superadmin_model->get_all_settings();
        $this->data['admins']   = $this->superadmin_model->get_admins();
        $this->data['all_users'] = $this->user_model->select_users();
        $this->load->view('superadmin_view', $this->data);
    }

    /* ── Templates ──────────────────────────────────────────── */

    public function save_templates()
    {
        $success = false;
        $message = 'Something went wrong';

        if (!$this->input->is_ajax_request()) exit('no valid request');

        if ($this->input->post()) {
            $keys = [
                'template_request_header',
                'template_request_through',
                'template_signatory_name',
                'template_signatory_title',
                'template_signatory_office',
                'template_summary_for_name',
                'template_summary_for_title',
                'app_title',
            ];

            $data = [];
            foreach ($keys as $key) {
                $data[$key] = $this->input->post($key, true);
            }

            $this->superadmin_model->save_settings_bulk($data);
            $success = true;
            $message = 'Templates saved successfully!';
        }

        echo json_encode(['success' => $success, 'msg' => $message]);
    }

    /* ── Logo upload ────────────────────────────────────────── */

    public function upload_logo()
    {
        $success = false;
        $message = 'Something went wrong';
        $path    = null;

        if (!$this->input->is_ajax_request()) exit('no valid request');

        if (!empty($_FILES['logo']['name'])) {
            $upload_path = FCPATH . 'assets/images/';

            $config = [
                'upload_path'   => $upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif|svg',
                'max_size'      => 2048,
                'file_name'     => 'logo_' . time(),
                'overwrite'     => false,
                'detect_mime'   => false,
            ];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $file_data = $this->upload->data();
                $new_path  = 'assets/images/' . $file_data['file_name'];

                // Delete old logo if it's not a default file
                $old_path = $this->superadmin_model->get_setting('logo_path');
                if ($old_path && !in_array(basename($old_path), ['dco.png', 'dcu.png', 'dco.ico'])) {
                    $old_full = FCPATH . $old_path;
                    if (file_exists($old_full)) {
                        @unlink($old_full);
                    }
                }

                $path = $new_path;
                $this->superadmin_model->save_setting('logo_path', $path);
                $success = true;
                $message = 'Logo updated successfully!';
            } else {
                $message = $this->upload->display_errors('', '');
            }
        } else {
            $message = 'No file selected.';
        }

        echo json_encode(['success' => $success, 'msg' => $message, 'path' => $path]);
    }

    public function upload_icon()
    {
        $success = false;
        $message = 'Something went wrong';
        $path    = null;

        if (!$this->input->is_ajax_request()) exit('no valid request');

        if (!empty($_FILES['icon']['name'])) {

            // Manually validate extension (bypass CI mime sniffing which fails for .ico on Windows)
            $ext = strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['ico', 'png', 'jpg', 'jpeg', 'gif', 'svg'];

            if (!in_array($ext, $allowed_exts)) {
                echo json_encode(['success' => false, 'msg' => 'Only ICO, PNG, JPG, GIF, SVG files are allowed.', 'path' => null]);
                return;
            }

            if ($_FILES['icon']['size'] > 1048576) { // 1MB
                echo json_encode(['success' => false, 'msg' => 'File too large. Maximum size is 1MB.', 'path' => null]);
                return;
            }

            $upload_path = FCPATH . 'assets/images/';
            $new_filename = 'icon_' . time() . '.' . $ext;

            // Use CI upload with wildcard to skip all mime checks
            $config = [
                'upload_path'   => $upload_path,
                'allowed_types' => '*',
                'max_size'      => 1024,
                'file_name'     => 'icon_' . time(),
                'overwrite'     => false,
            ];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('icon')) {
                $file_data = $this->upload->data();
                $new_path  = 'assets/images/' . $file_data['file_name'];

                // Delete old icon if not a default file
                $old_path = $this->superadmin_model->get_setting('icon_path');
                if ($old_path && !in_array(basename($old_path), ['dco.ico', 'dco.png'])) {
                    $old_full = FCPATH . $old_path;
                    if (file_exists($old_full)) {
                        @unlink($old_full);
                    }
                }

                $path = $new_path;
                $this->superadmin_model->save_setting('icon_path', $path);
                $success = true;
                $message = 'Icon updated successfully!';
            } else {
                $message = $this->upload->display_errors('', '');
            }
        } else {
            $message = 'No file selected.';
        }

        echo json_encode(['success' => $success, 'msg' => $message, 'path' => $path]);
    }

    /* ── Theme ──────────────────────────────────────────────── */

    public function save_theme()
    {
        $success = false;
        $message = 'Something went wrong';

        if (!$this->input->is_ajax_request()) exit('no valid request');

        if ($this->input->post()) {
            $theme_color  = $this->input->post('theme_color');
            $navbar_color = $this->input->post('navbar_color');

            $allowed_themes = [
                'sidebar-light-primary', 'sidebar-dark-primary',
                'sidebar-light-success', 'sidebar-dark-success',
                'sidebar-light-info',    'sidebar-dark-info',
                'sidebar-light-warning', 'sidebar-dark-warning',
                'sidebar-light-danger',  'sidebar-dark-danger',
                'sidebar-light-indigo',  'sidebar-dark-indigo',
                'sidebar-light-navy',    'sidebar-dark-navy',
                'sidebar-light-purple',  'sidebar-dark-purple',
                'sidebar-light-fuchsia', 'sidebar-dark-fuchsia',
                'sidebar-light-teal',    'sidebar-dark-teal',
                'sidebar-light-cyan',    'sidebar-dark-cyan',
            ];

            $allowed_navbars = [
                'navbar-dark navbar-primary',
                'navbar-dark navbar-secondary',
                'navbar-dark navbar-info',
                'navbar-dark navbar-success',
                'navbar-dark navbar-danger',
                'navbar-dark navbar-warning',
                'navbar-dark navbar-dark',
                'navbar-light navbar-light',
                'navbar-dark navbar-navy',
                'navbar-dark navbar-teal',
                'navbar-dark navbar-cyan',
                'navbar-dark navbar-indigo',
            ];

            if (in_array($theme_color, $allowed_themes) && in_array($navbar_color, $allowed_navbars)) {
                $this->superadmin_model->save_setting('theme_color', $theme_color);
                $this->superadmin_model->save_setting('navbar_color', $navbar_color);
                $success = true;
                $message = 'Theme updated successfully!';
            } else {
                $message = 'Invalid theme selection.';
            }
        }

        echo json_encode(['success' => $success, 'msg' => $message]);
    }

    /* ── Admin management ───────────────────────────────────── */

    public function set_admin()
    {
        $success = false;
        $message = 'Something went wrong';

        if (!$this->input->is_ajax_request()) exit('no valid request');

        if ($this->input->post()) {
            $user_id  = (int) $this->input->post('user_id');
            $new_type = (int) $this->input->post('new_type');

            // Prevent super admin from demoting themselves
            if ($user_id === (int) $this->_user_id && $new_type < 3) {
                $message = 'You cannot change your own super admin role.';
            } elseif (in_array($new_type, [1, 2, 3])) {
                $this->superadmin_model->set_user_type($user_id, $new_type);
                $success = true;
                $message = 'User role updated successfully!';
            } else {
                $message = 'Invalid role.';
            }
        }

        echo json_encode(['success' => $success, 'msg' => $message]);
    }
}