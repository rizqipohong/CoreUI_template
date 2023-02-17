<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * Product
 *
 * @property Module Product_model
 */
class Install extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->config('pagination');
        $this->load->model('Product_model');
    }

    public function index()
    {
        $cek_menu = $this->db->get_where('main_menu', ['menu_name' => 'Product'])->row();
        $data_menu = [
            'menu_name' => 'Product',
            'menu_type' => 'Main Menu',
            'nav_icon' => 'fas fa-globe',
            'access_status' => 'Activated',
            'url' => 'Product/index',
            'role_id' => json_encode(["RL2022-00000000"], true),
            'created_at' => date('Y-m-d H:i:s')];
        if (empty($cek_menu)) {
            $this->db->insert('main_menu', $data_menu);
        }
        $cek_modul = $this->db->get_where('main_modules', ['name' => 'Product'])->row();
        $data_modul = [
            'name' => 'Product',
            'description' => 'Belajar CI 3',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (empty($cek_modul)) {
            $this->db->insert('main_modules', $data_modul);
            $id_modul = $this->db->insert_id();
        } else {
            $id_modul = $cek_modul->id;
        }
        $role_id = 1;
        $permission = [
            [
                'name' => 'Product/index',
                'display_name' => 'Product Index',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Product/create_data',
                'display_name' => 'Product Insert',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Product/update_data',
                'display_name' => 'Product Update',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Product/delete_data',
                'display_name' => 'Product Delete',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Product/export_data',
                'display_name' => 'Product Export',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];
        foreach ($permission as $val) {
            $data = $val;
            unset($val['display_name']);

            $check_permission = $this->db->get_where('main_permissions', $val)->row();
            if (empty($check_permission)) {
                $this->db->insert('main_permissions', $data);
                $permission_id = $this->db->insert_id();
            } else {
                $permission_id = $check_permission->id;
            }

            $check_permission_role = $this->db->get_where("main_permission_roles", ["role_id" => $role_id, "permission_id" => $permission_id])->row();
            if (empty($check_permission_role)) {
                $this->db->insert("main_permission_roles", ["role_id" => $role_id, "permission_id" => $permission_id]);
            }
        }

        return true;


    }
}
