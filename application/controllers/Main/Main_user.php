<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Main_user
 *
 * @property User user_model
 * @property Role role_model
 */
class Main_user extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //        $this->authenticate->route_access();

        $this->load->config('pagination');

        $this->load->model('Main/User', 'user_model');
        $this->load->model('Main/Role', 'role_model');
    }

    public function index()
    {
        if (can('Main/Main_user/index') == true) {
            // page title / menu name / validasi akses menu
            $data['_title'] = 'Main User';

            // URL
            $data['_pageURL'] = site_url('Main/Main_user');
            $data['_filterURL'] = site_url('Main/Main_user/filter_data');
            $data['_clearfilterURL'] = site_url('Main/Main_user/clear_filter');

            $data['_createURL'] = site_url('Main/Main_user/create_data');
            $data['_getupdateURL'] = site_url('Main/Main_user/get_data');
            $data['_updateURL'] = site_url('Main/Main_user/update_data');
            $data['_deleteURL'] = site_url('Main/Main_user/delete_data');
            $data['_exportURL'] = site_url('Main/Main_user/check_pin_export');

            // filter
            $data['_fil_user_fullname'] = isset($_SESSION['_fil_user_fullname']) ? $_SESSION['_fil_user_fullname'] : null;
            $data['_fil_user_access_status'] = isset($_SESSION['_fil_user_access_status']) ? $_SESSION['_fil_user_access_status'] : null;

            // user data
            $limit = $this->lib_pagination->limit();
            $offset = isset($_REQUEST['per_page']) ? $_REQUEST['per_page'] : null;

            $user_data = $this->user_model->get_dashboard_data($limit, $offset, $data['_fil_user_fullname'], $data['_fil_user_access_status']);
            foreach ($user_data->result() as $user) {
                $user->roleArr = $this->user_model->userWiseRoleDetails($user->id);
            }
            $data['user_data'] = $user_data->result();
            $data['user_data_count'] = $user_data->num_rows();
            // pagination
            $data['pagination'] = $this->lib_pagination->pagination($data['_pageURL'], $limit, $data['user_data_count']);
            $data['select_role'] = $this->role_model->all();

            $additionalCss = [];

            $additionalJs = [
                'assets/main-assets/js/user.js' //assets di modules
            ];
            // view
            $this->lib_template->load('Main/user-list', $data, $additionalCss, $additionalJs);
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak punya akses ke menu!');
            redirect('Dashboard');
        }
    }

    public function get_data()
    {
        // ambil data untuk form update
        $this->form_validation->set_rules("user_id", "ID Pengguna", "trim|required");

        if ($this->form_validation->run() == true) {
            $user_id = $this->input->post('user_id');

            $data = $this->user_model->find($user_id);
            $data->role_id = $this->user_model->userWiseRoleDetails($user_id);
            $output['data'] = $data;
            $output['success'] = true;
        } else {
            $output['success'] = false;
            $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
        }
        echo json_encode($output);
    }

    public function create_data()
    {
        // validasi role bisa menulis / mengubah
        if (can('Main/Main_user/create_data') == true) {
            $this->form_validation->set_rules("user_fullname", "Nama Pengguna", "trim|alpha_numeric_spaces|min_length[3]|max_length[100]|required");
            $this->form_validation->set_rules("user_name", "Username", "trim|min_length[8]|max_length[20]|required");
            $this->form_validation->set_rules("role_id", "Peran pengguna", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $insert_data['id'] = $this->lib_code->create_id('US', 'main_users', 'id');
                $insert_data['name'] = $input['user_fullname'];
                $insert_data['username'] = $input['user_name'];
                $insert_data['created_at'] = date('Y-m-d H:i:s');
                $insert_data['status'] = $input['access_status'];
                $insert_data['password'] = sha1($input['user_password']);
                $this->user_model->add($insert_data);
                $this->user_model->addRoles($insert_data['id'], explode(',', $input['role_id']));

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

    public function update_data()
    {
        // validasi role bisa edit
        if (can('Main/Main_user/update_data') == true) {
            $this->form_validation->set_rules("up_user_fullname", "Nama Pengguna", "trim|alpha_numeric_spaces|min_length[3]|max_length[100]|required");
            $this->form_validation->set_rules("up_user_name", "Username", "trim|min_length[8]|max_length[20]|required");
            $this->form_validation->set_rules("up_role_id", "Peran pengguna", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $update_data['id'] = $input['up_user_id'];
                $update_data['name'] = $input['up_user_fullname'];
                $update_data['username'] = $input['up_user_name'];
                $update_data['status'] = $input['up_access_status'];

                if (!empty($input['up_user_password'])) {
                    $update_data['password'] = sha1($input['up_user_password']);
                }


                $this->user_model->edit($update_data);
                $this->user_model->editRoles($update_data['id'], explode(',', $input['up_role_id']));
                $output['success'] = true;
                $output['message'] = 'Data berhasil disimpan.';
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
        if (can('Main/Main_user/delete_data') == true) {
            $this->form_validation->set_rules("user_id", "ID Pengguna", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $this->user_model->delete($input['user_id']);

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

    public function filter_data()
    {
        // set filter session
        $input = $this->input->post();
        $this->session->set_userdata('_fil_user_fullname', $input['user_fullname']);
        if ($input['access_status'] !== '') {
            $this->session->set_userdata('_fil_user_access_status', $input['access_status']);
        } else {
            $this->session->unset_userdata('_fil_user_access_status');
        }

        redirect(site_url('Main/Main_user'));
    }

    public function clear_filter()
    {
        // clear filter session
        $uri = $this->uri->segment(4);

        if ($uri == 'user_fullname') {
            $this->session->unset_userdata('_fil_user_fullname');
        } else if ($uri == 'access_status') {
            $this->session->unset_userdata('_fil_user_access_status');
        } else {
            $this->session->unset_userdata('_fil_user_fullname');
            $this->session->unset_userdata('_fil_user_access_status');
        }
        redirect(site_url('Main/Main_user'));
    }

    //MENU YANG LAMA
    public function index_raw()
    {
        // page title / menu name / validasi akses menu
        $data['_title'] = $this->lib_template->valid_menu();

        // URL
        $data['_pageURL'] = site_url('Main/Main_user');
        $data['_filterURL'] = site_url('Main/Main_user/filter_data');
        $data['_clearfilterURL'] = site_url('Main/Main_user/clear_filter');

        $data['_createURL'] = site_url('Main/Main_user/create_data');
        $data['_getupdateURL'] = site_url('Main/Main_user/get_data');
        $data['_updateURL'] = site_url('Main/Main_user/update_data');
        $data['_deleteURL'] = site_url('Main/Main_user/delete_data');
        $data['_exportURL'] = site_url('Main/Main_user/check_pin_export');

        // filter
        $data['_fil_user_fullname'] = isset($_SESSION['_fil_user_fullname']) ? $_SESSION['_fil_user_fullname'] : null;
        $data['_fil_user_access_status'] = isset($_SESSION['_fil_user_access_status']) ? $_SESSION['_fil_user_access_status'] : null;

        // user data
        $limit = $this->lib_pagination->limit();
        $offset = isset($_REQUEST['per_page']) ? $_REQUEST['per_page'] : null;

        $data['user_data'] = $this->main_model->get_user_data($data['_fil_user_fullname'], $data['_fil_user_access_status'], $limit, $offset)->result();
        $data['user_data_count'] = $this->main_model->get_user_data($data['_fil_user_fullname'], $data['_fil_user_access_status'])->num_rows();

        // pagination
        $data['pagination'] = $this->lib_pagination->pagination($data['_pageURL'], $limit, $data['user_data_count']);

        // select role
        $data['select_role'] = $this->main_model->get_role_select()->result();

        // view
        $this->lib_template->load('Main/user-list', $data);
    }

    public function create_data_raw()
    {
        $base64toImg = new Lib_base64toImg();
        // validasi role bisa menulis / mengubah
        if ($this->lib_role->can('write') == true) {
            $this->form_validation->set_rules("user_fullname", "Nama Pengguna", "trim|alpha_numeric_spaces|min_length[3]|max_length[100]|required");
            $this->form_validation->set_rules("user_name", "Username", "trim|min_length[8]|max_length[20]|required");
            $this->form_validation->set_rules("user_email", "E-mail", "trim|min_length[3]|max_length[100]|required");
            $this->form_validation->set_rules("user_phone", "No Handphone", "trim|min_length[10]|max_length[15]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $insert_data['user_id'] = $this->lib_code->create_id('US', 'main_user', 'user_id');
                $insert_data['user_fullname'] = $input['user_fullname'];
                $insert_data['user_name'] = $input['user_name'];
                $insert_data['user_email'] = $input['user_email'];
                $insert_data['user_phone'] = $input['user_phone'];
                $insert_data['created_at'] = date('Y-m-d H:i:s');
                $insert_data['access_status'] = $input['access_status'];

                $insert_data['role_id'] = $input['role_id'];
                $insert_data['user_password'] = sha1($input['user_password']);
                $insert_data['last_password_update'] = date('Y-m-d H:i:s');

                $check_email = $this->lib_validation->valid_data($input['user_email'], 'main_user', 'user_email');
                $check_phone = $this->lib_validation->valid_data($insert_data['user_phone'], 'main_user', 'user_phone');
                if ($check_email == true) {
                    if ($check_phone == true) {
                        if (!empty($input['user_photo'])) {
                            // if (!empty($_FILES['user_photo']['name'])) {
                            // $config['upload_path'] = './uploads/profile_picture';
                            // $config['file_name'] = $insert_data['user_id'];
                            // $config['max_size'] = 5000;
                            // $config['overwrite'] = true;
                            // $config['allowed_types'] = 'jpg|png';

                            // $this->load->library('upload', $config);
                            // $this->upload->initialize($config);
                            // if ($this->upload->do_upload('user_photo')) {
                            //     $upload_file = $this->upload->data();

                            //     $insert_data['user_photo'] = $upload_file['file_name'];

                            //     // simpan data dengan foto
                            //     $this->db->insert('main_user', $insert_data);

                            //     // membuat log
                            //     $this->lib_log->create_log('Create User', $insert_data, 'Create');

                            $isUploaded = $base64toImg->file_uploads('./uploads/profile_picture/', $input['user_photo'], $insert_data['user_id'], 4096000);
                            if ($isUploaded['status']) {
                                $insert_data['user_photo'] = $isUploaded['file_name'];
                                // simpan data dengan foto
                                $this->db->insert('main_user', $insert_data);

                                // membuat log
                                $this->lib_log->create_log('Create User', $insert_data, 'Create');

                                $output['success'] = true;
                                $output['message'] = 'Data berhasil disimpan.';
                            } else {
                                $output['success'] = false;
                                $output['message'] = $this->upload->display_errors('', '');
                            }
                        } else {
                            $insert_data['user_photo'] = 'No_Image_Available.jpg';

                            // simpan data tanpa foto
                            $this->db->insert('main_user', $insert_data);

                            // membuat log
                            $this->lib_log->create_log('Create User', $insert_data, 'Create');

                            $output['success'] = true;
                            $output['message'] = 'Data berhasil disimpan.';
                        }

                        $setting_data = [
                            'dark_mode' => 'No',
                            'pagination_limit' => '10',
                        ];

                        $insert_setting = [
                            'setting_id' => $this->lib_code->create_id('ST', 'main_user_setting', 'setting_id'),
                            'user_id' => $insert_data['user_id'],
                            'setting_data' => json_encode($setting_data, true),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];

                        $this->db->insert('main_user_setting', $insert_setting);
                        $this->lib_log->create_log('Create Setting', $insert_setting, 'Create');
                    } else {
                        $output['success'] = false;
                        $output['message'] = "No Seluler Sudah Terpakai.";
                    }
                } else {
                    $output['success'] = false;
                    $output['message'] = "E-mail Sudah Terpakai.";
                }
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

    public function update_data_raw()
    {
        $base64toImg = new Lib_base64toImg();
        // validasi role bisa edit
        if ($this->lib_role->can('write') == true) {
            $this->form_validation->set_rules("up_user_fullname", "Nama Pengguna", "trim|alpha_numeric_spaces|min_length[3]|max_length[100]|required");
            $this->form_validation->set_rules("up_user_name", "Username", "trim|min_length[8]|max_length[20]|required");
            $this->form_validation->set_rules("up_user_email", "E-mail", "trim|min_length[3]|max_length[100]|required");
            $this->form_validation->set_rules("up_user_phone", "No Handphone", "trim|min_length[10]|max_length[15]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $update_data['user_id'] = $input['up_user_id'];
                $update_data['user_fullname'] = $input['up_user_fullname'];
                $update_data['user_name'] = $input['up_user_name'];
                $update_data['user_email'] = $input['up_user_email'];
                $update_data['user_phone'] = $input['up_user_phone'];
                $update_data['updated_at'] = date('Y-m-d H:i:s');
                $update_data['access_status'] = $input['up_access_status'];

                $update_data['role_id'] = $input['up_role_id'];

                if (!empty($input['up_user_password'])) {
                    $update_data['user_password'] = sha1($input['up_user_password']);
                    $update_data['last_password_update'] = date('Y-m-d H:i:s');
                }
                if (!empty($input['up_user_photo'])) {
                    $isUploaded = $base64toImg->file_uploads('./uploads/profile_picture/', $input['up_user_photo'], $update_data['user_id'], 4096000);
                    if (!$isUploaded['status']) {
                        $output['success'] = false;
                        $output['message'] = $this->upload->display_errors('', '');
                    }
                    $update_data['user_photo'] = $isUploaded['file_name'];
                }

                $where = ['user_id' => $input['up_user_id']];

                $this->db->update('main_user', $update_data, $where);

                $this->lib_log->create_log('Update User', $update_data, 'Update');

                $output['success'] = true;
                $output['message'] = 'Data berhasil disimpan.';
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

    public function get_data_raw()
    {
        // ambil data untuk form update
        $this->form_validation->set_rules("user_id", "ID Pengguna", "trim|required");

        if ($this->form_validation->run() == true) {
            $user_id = $this->input->post('user_id');

            $get_data = ['user_id' => $user_id];
            $data = $this->db->get_where('main_user', $get_data)->result();

            foreach ($data[0] as $key => $val) {
                $output[$key] = $val == 'Yes' ? true : ($val == 'No' ? false : $val);
            }

            $output['success'] = true;
        } else {
            $output['success'] = false;
            $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
        }
        echo json_encode($output);
    }

    public function delete_data_raw()
    {
        // validasi role bisa menghapus
        if ($this->lib_role->can('delete') == true) {
            $this->form_validation->set_rules("user_id", "ID Pengguna", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $delete_data['user_id'] = $input['user_id'];
                $delete_data['deleted_at'] = date('Y-m-d H:i:s');
                $delete_data['is_delete'] = 'Yes';

                $where = ['user_id' => $input['user_id']];

                // update data / mengubah status data menjadi terhapus di aplikasi tetapi tidak di database
                $this->db->update('main_user', $delete_data, $where);

                // membuat log
                $this->lib_log->create_log('Delete User', $delete_data, 'Delete');

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

    public function darkmode()
    {
        $user_id = isset($_SESSION['sgn_user_id']) ? $_SESSION['sgn_user_id'] : null;
        if (!empty($user_id)) {
            $where = ['user_id' => $user_id];
            $user_data = $this->db->get_where('main_user_setting', $where)->result();

            $data_setting = json_decode($user_data[0]->setting_data);

            $setting = [];
            foreach ($data_setting as $key => $val) {
                $setting[$key] = $val;
            }
            if ($data_setting->dark_mode == 'Yes') {
                $change = ['dark_mode' => 'No'];
            } else {
                $change = ['dark_mode' => 'Yes'];
            }
            $output = array_merge($setting, $change);

            $update_data = [
                "setting_data" => json_encode($output, true),
                "updated_at" => date('Y-m-d h:i:s'),
            ];

            $where = ['user_id' => $user_id, 'setting_id' => $user_data[0]->setting_id,];

            // update data
            $this->db->update('main_user_setting', $update_data, $where);

            // membuat log
            $this->lib_log->create_log('Update Setting', $update_data, 'Update');

            $this->session->set_flashdata('alert_success', 'Berhasil Merubah Tampilan.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak Memiliki Akses Halaman.');
            $landing_page = $this->lib_template->role_landing_page();
            redirect(site_url($landing_page));
            die();
        }
    }
}
