<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_default
{
    protected $_ci;

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function create_spadm()
    {
        $role_id = 'RL2022-00000000';
        $user_id = 'US2022-00000000';
        $setting_id = 'ST2022-00000000';

        $where = ['role_id' => $role_id];
        $check_data = $this->_ci->db->get_where('main_role', $where)->num_rows();

        $menu_role = [$role_id];

        $output = false;
        if ($check_data == 0) {
            // create first role
            $insert_role = [
                'role_id' => $role_id,
                'role_name' => 'Superuser',
                'role_read' => 'Yes',
                'role_write' => 'Yes',
                'role_delete' => 'Yes',
                'role_export' => 'Yes',
                'access_status' => 'Activated',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $insert_first_role = $this->_ci->db->insert('main_role', $insert_role);
            $this->_ci->lib_log->create_first_log('Create Role', $insert_role, 'Create', $user_id);

            if ($insert_first_role) {
                $insert_role = [
                    'role_id' => $this->_ci->lib_code->create_id('RL', 'main_role', 'role_id'),
                    'role_name' => 'Superadmin',
                    'role_read' => 'Yes',
                    'role_write' => 'Yes',
                    'role_delete' => 'Yes',
                    'role_export' => 'Yes',
                    'access_status' => 'Activated',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->_ci->db->insert('main_role', $insert_role);
                $this->_ci->lib_log->create_first_log('Create Role', $insert_role, 'Create', $user_id);

                $insert_role = [
                    'role_id' => $this->_ci->lib_code->create_id('RL', 'main_role', 'role_id'),
                    'role_name' => 'CEO',
                    'role_read' => 'Yes',
                    'role_write' => 'Yes',
                    'role_delete' => 'Yes',
                    'role_export' => 'Yes',
                    'access_status' => 'Activated',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->_ci->db->insert('main_role', $insert_role);
                $this->_ci->lib_log->create_first_log('Create Role', $insert_role, 'Create', $user_id);

                $insert_role = [
                    'role_id' => $this->_ci->lib_code->create_id('RL', 'main_role', 'role_id'),
                    'role_name' => 'Head Of SME',
                    'role_read' => 'Yes',
                    'role_write' => 'Yes',
                    'role_delete' => 'Yes',
                    'role_export' => 'Yes',
                    'access_status' => 'Activated',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->_ci->db->insert('main_role', $insert_role);
                $this->_ci->lib_log->create_first_log('Create Role', $insert_role, 'Create', $user_id);

                $insert_role = [
                    'role_id' => $this->_ci->lib_code->create_id('RL', 'main_role', 'role_id'),
                    'role_name' => 'Manager SME',
                    'role_read' => 'Yes',
                    'role_write' => 'Yes',
                    'role_delete' => 'Yes',
                    'role_export' => 'Yes',
                    'access_status' => 'Activated',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->_ci->db->insert('main_role', $insert_role);
                $this->_ci->lib_log->create_first_log('Create Role', $insert_role, 'Create', $user_id);

                $insert_role = [
                    'role_id' => $this->_ci->lib_code->create_id('RL', 'main_role', 'role_id'),
                    'role_name' => 'Admin SME',
                    'role_read' => 'Yes',
                    'role_write' => 'Yes',
                    'role_delete' => 'Yes',
                    'role_export' => 'Yes',
                    'access_status' => 'Activated',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->_ci->db->insert('main_role', $insert_role);
                $this->_ci->lib_log->create_first_log('Create Role', $insert_role, 'Create', $user_id);
            }

            // create first user
            $insert_user = [
                'user_id' => $user_id,
                'role_id' => $role_id,
                'user_fullname' => 'Ahmad Aziz Dz',
                'user_name' => 'Superuser',
                'user_password' => sha1('Hammadad2994@'),
                'user_email' => 'ahmad.dzulkifli@sanders.co.id',
                'user_phone' => '628000000000',
                'user_photo' => 'No_Image_Available.jpg',
                'last_password_update' => date('Y-m-d H:i:s'),
                'access_status' => 'Activated',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->_ci->db->insert('main_user', $insert_user);
            $this->_ci->lib_log->create_first_log('Create User', $insert_user, 'Create', $user_id);

            $setting_data = [
                'dark_mode' => 'No',
                'pagination_limit' => '10',
            ];

            $insert_setting = [
                'setting_id' => $setting_id,
                'user_id' => $user_id,
                'setting_data' => json_encode($setting_data, true),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->_ci->db->insert('main_user_setting', $insert_setting);
            $this->_ci->lib_log->create_first_log('Create Setting', $insert_setting, 'Create', $user_id);

            // create base menu
            $insert_menu = [
                'menu_id' => 1,
                'menu_name' => 'Beranda',
                'menu_type' => 'Main Menu',
                'menu_order' => 1,
                'modules_name' => 'Dashboard',
                'controller_name' => 'Dashboard',
                'function_name' => 'index',
                'nav_icon' => 'fas fa-tachometer-alt',
                'role_id' => json_encode($menu_role, true),
                'access_status' => 'Activated',
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'is_base_menu' => 'Yes',
            ];
            $this->_ci->db->insert('main_menu', $insert_menu);
            $this->_ci->lib_log->create_first_log('Create Menu', $insert_menu, 'Create', $user_id);

            $insert_menu = [
                'menu_id' => 2,
                'menu_name' => 'MENU UTAMA',
                'menu_type' => 'Header',
                'menu_order' => 2,
                'modules_name' => '-',
                'controller_name' => '-',
                'function_name' => '-',
                'role_id' => json_encode($menu_role, true),
                'access_status' => 'Activated',
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'is_base_menu' => 'Yes',
            ];
            $this->_ci->db->insert('main_menu', $insert_menu);
            $this->_ci->lib_log->create_first_log('Create Menu', $insert_menu, 'Create', $user_id);

            $insert_menu = [
                'menu_id' => 3,
                'menu_name' => 'Pengaturan Sistem',
                'menu_type' => 'Main Menu',
                'menu_order' => 3,
                'modules_name' => '-',
                'controller_name' => '-',
                'function_name' => '-',
                'nav_icon' => 'fas fa-cogs',
                'role_id' => json_encode($menu_role, true),
                'access_status' => 'Activated',
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'is_base_menu' => 'Yes',
            ];
            $this->_ci->db->insert('main_menu', $insert_menu);
            $this->_ci->lib_log->create_first_log('Create Menu', $insert_menu, 'Create', $user_id);

            // create base submenu
            $insert_menu = [
                'menu_id' => 4,
                'menu_id_parent' => 3,
                'menu_name' => 'Menu - Menu',
                'menu_type' => 'Sub Menu',
                'menu_order' => 1,
                'modules_name' => 'Main',
                'controller_name' => 'Main_menu',
                'function_name' => 'index',
                'role_id' => json_encode($menu_role, true),
                'access_status' => 'Activated',
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'is_base_menu' => 'Yes',
            ];
            $this->_ci->db->insert('main_menu', $insert_menu);
            $this->_ci->lib_log->create_first_log('Create Menu', $insert_menu, 'Create', $user_id);

            $insert_menu = [
                'menu_id' => 5,
                'menu_id_parent' => 3,
                'menu_name' => 'Peran Pengguna',
                'menu_type' => 'Sub Menu',
                'menu_order' => 2,
                'modules_name' => 'Main',
                'controller_name' => 'Main_role',
                'function_name' => 'index',
                'role_id' => json_encode($menu_role, true),
                'access_status' => 'Activated',
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'is_base_menu' => 'Yes',
            ];
            $this->_ci->db->insert('main_menu', $insert_menu);
            $this->_ci->lib_log->create_first_log('Create Menu', $insert_menu, 'Create', $user_id);

            $insert_menu = [
                'menu_id' => 6,
                'menu_id_parent' => 3,
                'menu_name' => 'Pengguna',
                'menu_type' => 'Sub Menu',
                'menu_order' => 3,
                'modules_name' => 'Main',
                'controller_name' => 'Main_user',
                'function_name' => 'index',
                'role_id' => json_encode($menu_role, true),
                'access_status' => 'Activated',
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'is_base_menu' => 'Yes',
            ];
            $this->_ci->db->insert('main_menu', $insert_menu);
            $this->_ci->lib_log->create_first_log('Create Menu', $insert_menu, 'Create', $user_id);

            $insert_menu = [
                'menu_id' => 7,
                'menu_id_parent' => 3,
                'menu_name' => 'Log Sistem',
                'menu_type' => 'Sub Menu',
                'menu_order' => 4,
                'modules_name' => 'Main',
                'controller_name' => 'Main_log',
                'function_name' => 'index',
                'role_id' => json_encode($menu_role, true),
                'access_status' => 'Activated',
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'is_base_menu' => 'Yes',
            ];
            $this->_ci->db->insert('main_menu', $insert_menu);
            $this->_ci->lib_log->create_first_log('Create Menu', $insert_menu, 'Create', $user_id);

            $output = true;
        } else {
            $output = false;
        }

        return $output;
    }
}
