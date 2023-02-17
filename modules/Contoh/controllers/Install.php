<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * CContoh
 *
 * @property Module contoh_model
 */
class Install extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->config('pagination');
        $this->load->model('contoh_model');

        //        $this->authenticate->route_access();

    }

    public function index()
    {
        $cek_menu = $this->db->get_where('main_menu', ['menu_name' => 'Existing Borrower'])->row();
        $data_menu = [
            'menu_name' => 'Contoh',
            'menu_type' => 'Main Menu',
            'nav_icon' => 'fas fa-globe',
            'access_status' => 'Activated',
            'url' => 'Contoh/index',
            'role_id' => json_encode(["RL2022-00000000"], true),
            'access_status' => 'Activated',
            'created_at' => date('Y-m-d H:i:s')];
        if (empty($cek_menu)) {
            $this->db->insert('main_menu', $data_menu);
        }
        
        //create modul
        $cek_modul = $this->db->get_where('main_modules', ['name' => 'Contoh'])->row();
        $data_modul = [
            'name' => 'Contoh',
            'description' => 'Template Contoh untuk pembuatan sebuah modul',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (empty($cek_modul)) {
            $this->db->insert('main_modules', $data_modul);
            $id_modul = $this->db->insert_id();
        } else {
            $id_modul = $cek_modul->id;
        }        
        $role_id = 1; //default id Admin

        $permission = [
            [
                'name' => 'Contoh/index',
                'display_name' => 'Contoh Index',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Contoh/create_data',
                'display_name' => 'Contoh Insert',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Contoh/update_data',
                'display_name' => 'Contoh Update',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Contoh/delete_data',
                'display_name' => 'Contoh Delete',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Contoh/export_data',
                'display_name' => 'Contoh Export',
                'status' => 1,
                'module_id' => $id_modul,
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];
        foreach ($permission as $val) {
            $data = $val;
            // $data = array_merge($val, $additionalData);
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
