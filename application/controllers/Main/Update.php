<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Main_user
 *
 * @property User user_model
 * @property Role role_model
 */
class Update extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public function index()
    {
        $versi = $this->input->get('ver');
        if ($versi == '2.0.2') {
            $this->dbforge->drop_table('main_role', TRUE);
            $this->dbforge->drop_table('main_user', TRUE);

            $this->dbforge->rename_table('modules', 'main_modules');
            $this->dbforge->rename_table('permission_role', 'main_permission_role');
            $this->dbforge->rename_table('permissions', 'main_permissions');
            $this->dbforge->rename_table('roles', 'main_roles');
            $this->dbforge->rename_table('role_users', 'main_role_users');
        }
    }
}
