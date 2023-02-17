<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Auth
 *
 * @property Authenticate authenticate
 */
class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        //validate user is authenticated
        if (check()) {
            redirect(base_url('Dashboard'));
        }

        $data = array();

        if ($_POST) {
            $data = $this->authenticate->login($_POST);
        }


        $data['_url_plugin'] = site_url('vendor/almasaeed2010/adminlte/plugins/');
        $data['_url_js'] = site_url('vendor/almasaeed2010/adminlte/dist/js/');
        $data['_url_css'] = site_url('vendor/almasaeed2010/adminlte/dist/css/');

        $data['_signinURL'] = site_url('Auth');
        $data['_forgotURL'] = site_url('Auth/forgot_password');

        $cookie = get_cookie($this->config->item('cookie_nests'));
        $this->load->view("auth", $data);
    }
    public function signout()
    {
        $this->session->unset_userdata(array("userID", "username", "loginStatus"));
        delete_cookie($this->config->item('cookie_nests'));
        $this->session->set_flashdata('alert_error', 'Anda Telah Keluar Dari Sistem.');
        redirect(base_url());
        die();
    }

    public function forgot_password()
    {
        echo "forgot";
    }

    public function x_default()
    {
        $this->load->library('lib_default');

        $output = $this->lib_default->create_spadm();

        if ($output == true) {
            $this->session->set_flashdata('alert_success', 'Membuat Settingan Awal Berhasil.');
            redirect(base_url());
            die();
        } else {
            $this->session->set_flashdata('alert_info', 'Settingan Awal Sudah Tersedia.');
            redirect(base_url());
            die();
        }
    }

    public function x_migration()
    {
        $this->load->library('migration');

        if (!$this->migration->current()) {
            // show_error($this->migration->error_string());
            $this->session->set_flashdata('alert_error', $this->migration->error_string());
            redirect(base_url());
            die();
        } else {
            $this->session->set_flashdata('alert_success', 'Migrasi Berhasil.');
            redirect(base_url());
            die();
        }
    }
}
