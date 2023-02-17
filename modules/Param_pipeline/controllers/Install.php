<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Matrix\Operators\Addition;
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
        $this->load->model('Param_pipeline_model');

        //        $this->authenticate->route_access();

    }

    public function index()
    {

        //untuk melakukan pengecekkan Header Menu
        $find_header = $this->db->get_where('main_menu', ['menu_name' => 'SME'])->row();
        if (!empty($find_header)) {
            $id_header_sme = $find_header->menu_id;
        } else {
            $header_sme = [
                'menu_name' => 'SME',
                'menu_type' => 'Header',
                'url' => 'Menu/SME',
                'nav_icon' => 'fas fa-building',
                'access_status' => 'Activated',
                'role_id' => json_encode(['RL2022-00000000'], true),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('main_menu', $header_sme);
            $id_header_sme = $this->db->insert_id();
        }


        //untuk melakukan pengecekkan sub menu header
        $find_menu = $this->db->get_where('main_menu', ['menu_name' => 'Master Data & Param.', 'menu_type' => 'Sub Menu'])->row();
        if (!empty($find_menu)) {
            $id_menu_sme = $find_menu->menu_id;
        } else {
            $menu_sme = [
                'menu_name' => 'Master Data & Param.',
                'menu_type' => 'Sub Menu',
                'menu_id_parent' => $id_header_sme,
                'url' => 'Menu/SME_master_data',
                'nav_icon' => 'fa fa-box',
                'access_status' => 'Activated',
                'role_id' => json_encode(['RL2022-00000000'], true),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('main_menu', $menu_sme);
            $id_menu_sme = $this->db->insert_id();
        }


        //create modul
        $cek_modul = $this->db->get_where('main_modules', ['name' => 'Param Pipeline'])->row();
        $data_modul = [
            'name' => 'Param Pipeline',
            'description' => 'Param Pipeline',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (empty($cek_modul)) {
            $this->db->insert('main_modules', $data_modul);
            $id_modul = $this->db->insert_id();
        } else {
            $id_modul = $cek_modul->id;
        }

        //Create Menu 
        $cek_menu = $this->db->get_where('main_menu', ['menu_name' => 'Param Pipeline'])->row();
        $data_menu = [
            'menu_name' => 'Param Pipeline',
            'menu_id_parent' => $id_menu_sme,
            'menu_type' => 'Sub Menu',
            'nav_icon' => 'far fa-circle',
            'role_id' => json_encode(["RL2022-00000000"], true),
            'access_status' => 'Activated',
            'url' => 'Param_pipeline/index',
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (empty($cek_menu)) {
            $this->db->insert('main_menu', $data_menu);
        } else {
            $this->db->where(['menu_name' => $data_menu['menu_name']])
                ->update("main_menu", ['menu_id_parent' => $data_menu['menu_id_parent'], 'menu_type' => $data_menu['menu_type']]);
        }



        //create permission
        $role_id = 1; //default id Admin
        $check_permission_SME = $this->db->get_where('main_permissions', ['name' => 'Menu/SME'])->row();
        if (empty($check_permission_SME)) {
            $arrSME = [
                'name' => 'Menu/SME',
                'display_name' => 'Menu SME Hunter',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'module_id' => 5
            ];
            $this->db->insert('main_permissions', $arrSME);
            $this->db->insert('main_permission_roles', ['role_id' => $role_id, 'permission_id' => $this->db->insert_id()]);
        } else {
            $check_permission_roles_SME = $this->db->get_where('main_permission_roles', ['role_id' => $role_id, 'permission_id' => $check_permission_SME->id])->row();
            if (empty($check_permission_roles_SME)) {
                $this->db->insert('main_permission_roles', ['role_id' => $role_id, 'permission_id' => $check_permission_SME->id]);
            }
        }

        $check_permission_SME_master_data = $this->db->get_where('main_permissions', ['name' => 'Menu/SME_master_data'])->row();
        if (empty($check_permission_SME_master_data)) {
            $arrSME = [
                'name' => 'Menu/SME_master_data',
                'display_name' => 'Menu SME Master Data',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'module_id' => 5
            ];
            $this->db->insert('main_permissions', $arrSME);
            $this->db->insert('main_permission_roles', ['role_id' => 1, 'permission_id' => $this->db->insert_id()]);
        } else {
            $check_permission_roles_SME = $this->db->get_where('main_permission_roles', ['role_id' => $role_id, 'permission_id' => $check_permission_SME_master_data->id])->row();
            if (empty($check_permission_roles_SME)) {
                $this->db->insert('main_permission_roles', ['role_id' => $role_id, 'permission_id' => $check_permission_SME_master_data->id]);
            }
        }


        $permission = [
            [
                'name' => 'Param_pipeline/index',
                'display_name' => 'Param pipeline Index',
            ],
            [
                'name' => 'Param_pipeline/create_data',
                'display_name' => 'Param pipeline Insert',
            ],
            [
                'name' => 'Param_pipeline/update_data',
                'display_name' => 'Param pipeline Update',
            ],
            [
                'name' => 'Param_pipeline/delete_data',
                'display_name' => 'Param pipeline Delete',
            ],
            [
                'name' => 'Param_pipeline/export_data',
                'display_name' => 'Param pipeline Export',
            ],
        ];
        $additionalData = [
            'status' => 1,
            'module_id' => $id_modul,
            'created_at' => date('Y-m-d H:i:s')
        ];

        foreach ($permission as $val) {
            $data = array_merge($val, $additionalData);
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
        // menu

        $output['message'] = 'Data berhasil diubah.';
        $output['success'] = true;

        echo json_encode($output);
    }
}
