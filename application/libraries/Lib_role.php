<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_role
{
    protected $_ci;

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function can($access = null)
    {
        $role_id = isset($_SESSION['sgn_role_id']) ? $_SESSION['sgn_role_id'] : null;

        $output = false;
        if (!empty($role_id) and !empty($access)) {
            if ($access == 'read') {
                $role_data = $this->_ci->db->get_where('main_role', ['role_id' => $role_id])->result();
                $role_data = $role_data[0]->role_read;
            } else if ($access == 'write') {
                $role_data = $this->_ci->db->get_where('main_role', ['role_id' => $role_id])->result();
                $role_data = $role_data[0]->role_write;
            } else if ($access == 'delete') {
                $role_data = $this->_ci->db->get_where('main_role', ['role_id' => $role_id])->result();
                $role_data = $role_data[0]->role_delete;
            } else if ($access == 'export') {
                $role_data = $this->_ci->db->get_where('main_role', ['role_id' => $role_id])->result();
                $role_data = $role_data[0]->role_export;
            } else {
                $role_data = '';
            }

            if (!empty($role_data)) {
                $output = $role_data == 'Yes' ? true : false;
            } else {
                $output = false;
            }
        } else {
            $output = false;
        }

        return $output;
    }

    public function valid_access()
    {
        $user_id = isset($_SESSION['sgn_user_id']) ? $_SESSION['sgn_user_id'] : null;
        $role_id = isset($_SESSION['sgn_role_id']) ? $_SESSION['sgn_role_id'] : null;
        $role_name = isset($_SESSION['sgn_role_name']) ? $_SESSION['sgn_role_name'] : null;
        $landing_page = isset($_SESSION['sgn_landing_page']) ? $_SESSION['sgn_landing_page'] : null;
        $user_name = isset($_SESSION['sgn_user_name']) ? $_SESSION['sgn_user_name'] : null;
        $user_fullname = isset($_SESSION['sgn_user_fullname']) ? $_SESSION['sgn_user_fullname'] : null;
        $user_email = isset($_SESSION['sgn_user_email']) ? $_SESSION['sgn_user_email'] : null;
        $user_password = isset($_SESSION['sgn_user_password']) ? $_SESSION['sgn_user_password'] : null;

        $output = false;
        if (!empty($user_id) && !empty($role_id) && !empty($role_name) && !empty($user_name) && !empty($user_fullname) && !empty($user_email) && !empty($user_password)) {

            $where_user = ['user_id' => $user_id, 'user_email' => $user_email, 'user_password' => $user_password, 'access_status' => 'Activated', 'is_delete' => 'No',];
            $valid_user = $this->_ci->db->get_where('main_user', $where_user)->num_rows();

            $where_role = ['role_id' => $role_id, 'access_status' => 'Activated', 'is_delete' => 'No',];
            $valid_role = $this->_ci->db->get_where('main_role', $where_role)->num_rows();

            if ($valid_user == 1 && $valid_role == 1) {
                $output = true;
            } else {
                $output = false;
            }
        } else {
            $output = false;
        }

        if ($output == true) {
            return $output;
        } else {
            $this->not_allowed();
        }
    }

    public function validate_signin($user_email, $user_password)
    {
        $where_user = ['user_email' => $user_email, 'user_password' => $user_password, 'access_status' => 'Activated', 'is_delete' => 'No',];
        $validate_user = $this->_ci->db->get_where('main_user', $where_user)->num_rows();

        $output = false;
        if ($validate_user == 1) {
            $user_data = $this->_ci->db->get_where('main_user', $where_user)->result();

            $where_role = ['role_id' => $user_data[0]->role_id, 'access_status' => 'Activated', 'is_delete' => 'No',];
            $validate_role = $this->_ci->db->get_where('main_role', $where_role)->num_rows();
            if ($validate_role == 1) {
                $output = true;
            } else {
                $output = false;
            }
        } else {
            $output = false;
        }

        return $output;
    }

    public function signout()
    {
        $this->_ci->lib_log->create_log('User Signout', $_SESSION, 'Signout');

        $unset_session = ['sgn_user_id', 'sgn_role_id', 'sgn_role_name', 'sgn_landing_page', 'sgn_user_name', 'sgn_user_fullname', 'sgn_user_email', 'sgn_user_password',];
        $this->_ci->session->unset_userdata($unset_session);
        delete_cookie($this->_ci->config->item('cookie_nests'));
        $this->_ci->session->set_flashdata('alert_error', 'Anda Telah Keluar Dari Sistem.');
        redirect(base_url());
        die();
    }

    public function not_allowed()
    {
        $url = base_url();
        $unset_session = ['sgn_user_id', 'sgn_role_id', 'sgn_role_name', 'sgn_landing_page', 'sgn_user_name', 'sgn_user_fullname', 'sgn_user_email', 'sgn_user_password',];
        $this->_ci->session->unset_userdata($unset_session);
        $this->_ci->session->set_flashdata('alert_error', 'Anda Tidak Memiliki Akses.');
        redirect($url);
        die();
    }
}
