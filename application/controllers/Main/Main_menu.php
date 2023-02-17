<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_menu extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //        $this->authenticate->route_access();

        $this->load->config('pagination');
    }

    public function index()
    {
        if (can('Main/Main_menu/index') == true) {
            $data['_title'] = 'Menu';

            // URL
            $data['_pageURL'] = site_url('Main/Main_menu');
            $data['_createURL'] = site_url('Main/Main_menu/create_data');
            $data['_getupdateURL'] = site_url('Main/Main_menu/get_data');
            $data['_updateURL'] = site_url('Main/Main_menu/update_data');
            $data['_deleteURL'] = site_url('Main/Main_menu/delete_data');
            $data['_switchURL'] = site_url('Main/Main_menu/switch_status');

            // menu data
            $data['menu_data'] = $this->lib_template->list_menu();

            $additionalCss = [
                'assets/main-assets/css/menu.css' //assets di modules
            ];
            $additionalJs = [
                'vendor/almasaeed2010/adminlte/plugins/jquery-ui/jquery-ui.min.js',
                'vendor/almasaeed2010/adminlte/plugins/jquery-ui/jquery.mjs.nestedSortable.js',
                'assets/main-assets/js/menu.js' //assets di modules
            ];

            // view
            $this->lib_template->load('Main/menu-list', $data, $additionalCss, $additionalJs);
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak punya akses ke menu!');
            redirect('Dashboard');
        }
    }

    public function create_data()
    {
        // validasi role bisa menulis / mengubah
        if (can('Main/Main_menu/create_data')) {
            $this->form_validation->set_rules("menu_name", "Nama Menu", "trim|min_length[3]|max_length[25]|required");
            $this->form_validation->set_rules("menu_type", "Type Menu", "trim|in_list[Header,Main Menu,Sub Menu]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $insert_data['menu_name'] = $input['menu_name'];
                $insert_data['menu_type'] = $input['menu_type'];
                $insert_data['url'] = $input['url'];
                // $insert_data['permission_label'] = $input['permission_label'];
                // $insert_data['modules_name'] = !empty($input['modules_name']) ? $input['modules_name'] : '-';
                // $insert_data['controller_name'] = !empty($input['controller_name']) ? $input['controller_name'] : '-';
                // $insert_data['function_name'] = !empty($input['function_name']) ? $input['function_name'] : '-';
                $insert_data['nav_icon'] = !empty($input['nav_icon']) ? $input['nav_icon'] : 'far fa-circle';

                if (isset($input['role_id'])) {
                    $role_id = [];
                    array_push($role_id, 'RL2022-00000000');
                    foreach ($input['role_id'] as $key => $val) {
                        array_push($role_id, $val);
                    }
                } else {
                    $role_id = ['RL2022-00000000'];
                }

                $insert_data['role_id'] = json_encode($role_id, true);

                $insert_data['created_by'] = $_SESSION['username'];
                $insert_data['access_status'] = $input['access_status'];
                $insert_data['created_at'] = date('Y-m-d H:i:s');
                // simpan data
                $this->db->insert('main_menu', $insert_data);

                // membuat log
                $this->lib_log->create_log('Create Menu', $insert_data, 'Create', 'System');

                $output['success'] = true;
                $output['message'] = 'Data berhasil disimpan.';
            } else {
                $output['success'] = false;
                $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
            }
        } else {
            $output['success'] = false;
            $output['message'] = "Anda Tidak Memiliki Akses Membuat Data.";
        }

        echo json_encode($output);
    }

    public function get_data()
    {
        // ambil data untuk form update
        $this->form_validation->set_rules("menu_id", "ID Menu", "trim|required");

        if ($this->form_validation->run() == true) {
            $menu_id = $this->input->post('menu_id');

            $get_data = ['menu_id' => $menu_id];
            $data = $this->db->get_where('main_menu', $get_data)->result();
            $output['menu_id'] = $data[0]->menu_id;
            $output['menu_name'] = $data[0]->menu_name;
            $output['menu_type'] = $data[0]->menu_type;
            // $output['modules_name'] = $data[0]->modules_name;
            // $output['controller_name'] = $data[0]->controller_name;
            // $output['function_name'] = $data[0]->function_name;
            // $output['permission_label'] = $data[0]->permission_label;
            $output['nav_icon'] = $data[0]->nav_icon;
            $output['access_status'] = $data[0]->access_status;
            $output['url'] = $data[0]->url;

            $output['success'] = true;
        } else {
            $output['success'] = false;
            $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
        }

        echo json_encode($output);
    }

    public function update_data()
    {
        // validasi role bisa menulis / mengubah
        if (can('Main/Main_menu/update_data') == true) {
            $this->form_validation->set_rules("menu_id", "ID Menu", "trim|required");
            $this->form_validation->set_rules("menu_name", "Nama Menu", "trim|min_length[3]|max_length[25]|required");
            $this->form_validation->set_rules("menu_type", "Type Menu", "trim|in_list[Header,Main Menu,Sub Menu]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $update_data['menu_id'] = $input['menu_id'];
                $update_data['menu_name'] = $input['menu_name'];
                $update_data['menu_type'] = $input['menu_type'];
                // $update_data['modules_name'] = !empty($input['modules_name']) ? $input['modules_name'] : '-';
                // $update_data['controller_name'] = !empty($input['controller_name']) ? $input['controller_name'] : '-';
                // $update_data['function_name'] = !empty($input['function_name']) ? $input['function_name'] : '-';
                // $update_data['permission_label'] = $input['permission_label'];
                $update_data['nav_icon'] = !empty($input['nav_icon']) ? $input['nav_icon'] : 'far fa-circle';
                $update_data['url'] = $input['url'];

                $update_data['created_by'] = $_SESSION['username'];
                $update_data['access_status'] = $input['access_status'];
                $update_data['updated_at'] = date('Y-m-d H:i:s');

                $where = ['menu_id' => $input['menu_id']];

                // update data
                $this->db->update('main_menu', $update_data, $where);

                // membuat log
                //                $this->lib_log->create_log('Update Menu', $update_data, 'Update');

                $output['success'] = true;
                $output['message'] = 'Data berhasil diubah.';
            } else {
                $output['success'] = false;
                $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
            }
        } else {
            $output['success'] = false;
            $output['message'] = "Anda Tidak Memiliki Akses Mengedit Data.";
        }

        echo json_encode($output);
    }

    public function delete_data()
    {
        // validasi role bisa menghapus
        if (can('Main/Main_menu/delete_data') == true) {
            $this->form_validation->set_rules("menu_id", "ID Menu", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $delete_data['menu_id'] = $input['menu_id'];
                $delete_data['created_by'] = $_SESSION['username'];
                $delete_data['deleted_at'] = date('Y-m-d H:i:s');
                $delete_data['is_delete'] = 'Yes';

                $where = ['menu_id' => $input['menu_id']];

                // update data / mengubah status data menjadi terhapus di aplikasi tetapi tidak di database
                $this->db->update('main_menu', $delete_data, $where);

                // membuat log
                $this->lib_log->create_log('Delete Menu', $delete_data, 'Delete');

                $output['success'] = true;
                $output['message'] = 'Data berhasil dihapus.';
            } else {
                $output['success'] = false;
                $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
            }
        } else {
            $output['success'] = false;
            $output['message'] = "Anda Tidak Memiliki Akses Menghapus Data.";
        }
        echo json_encode($output);
    }

    public function change_order()
    {
        // validasi role bisa menulis / mengubah
        if (can('Main/Main_menu/change_order') == true) {
            $menuitem = $this->input->post('menuitem');

            $menu_order = 0;
            $submenu_order = 0;
            foreach ($menuitem as $key => $val) {
                $menu = $this->db->get_where("main_menu", array("menu_id" => $key))->result();
                $menu_type = $menu[0]->menu_type;

                $data = [];
                $data[$key] = $val;
                if ($val == 'null') {
                    $menu_order++;
                    $order = $menu_order;
                    if ($menu_type == 'Header') {
                        $type = 'Header';
                    } else {
                        $type = 'Main Menu';
                    }
                } else {
                    $submenu_order++;
                    $order = $submenu_order;
                    if ($menu_type == 'Header') {
                        $type = 'Header';
                    } else {
                        $type = 'Sub Menu';
                    }
                }

                $sql = "Update main_menu SET menu_id_parent=" . $val . ", menu_order=" . $order . ", menu_type='" . $type . "' WHERE menu_id=" . $key;
                $this->db->query($sql);

                // membuat log
                $this->lib_log->create_log('Update Menu', 'Mengubah Susunan Menu', 'Update');
            }
            $output['success'] = true;
            $output['message'] = "Susunan Menu Berhasil Diubah.";
        } else {
            $output['success'] = false;
            $output['message'] = "Anda Tidak Memiliki Akses Mengedit Data.";
        }
        echo json_encode($output);
    }

    public function switch_status()
    {
        // validasi role bisa menulis / mengubah
        if (can('Main/Main_menu/switch_status') == true) {
            $this->form_validation->set_rules("menu_id", "ID", "trim|required");

            if ($this->form_validation->run() == true) {
                $menu_id = $this->input->post('menu_id');

                $get_data = ['menu_id' => $menu_id];
                $data = $this->db->get_where('main_menu', $get_data)->result();

                if ($data[0]->access_status == "Activated") {
                    $access_status = "Deactivated";
                    $message = "Status di nonaktifkan";
                } else {
                    $access_status = "Activated";
                    $message = "Status di aktifkan";
                }

                $update_data['access_status'] = $access_status;
                $update_data['updated_at'] = date('Y-m-d H:i:s');

                $where = ['menu_id' => $menu_id];

                // update data
                $this->db->update('main_menu', $update_data, $where);

                // membuat log
                $this->lib_log->create_log('Update Menu', $update_data, 'Update');

                $output['success'] = true;
                $output['message'] = $message;
            } else {
                $output['success'] = false;
                $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
            }
        } else {
            $output['success'] = false;
            $output['message'] = "Anda Tidak Memiliki Akses Mengedit Data.";
        }

        echo json_encode($output);
    }
}
