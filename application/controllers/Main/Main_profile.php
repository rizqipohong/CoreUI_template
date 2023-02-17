<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_profile extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        // validasi akses login
        // $this->lib_role->valid_access();
    }

    public function index()
    {
        if (can('Main/Main_profile/index') == true) {

            $data['_user'] = $this->db->select('main_users.*')
                ->from('main_users')
                ->where('id', $this->session->userdata('userID'))
                ->get()->row();

            // foreach ($user[0] as $key => $val) {
            //     $data['_data'][$key] = $val == 'Yes' ? true : ($val == 'No' ? false : $val);
            // }

            // page title / menu name / validasi akses menu
            $data['_title'] = 'Akun';
            $data['_updateURL'] =  site_url('Main/Main_profile/update_data');

            // $this->lib_template->load('Main/user-profile', $data);

            $additionalCss = [
                'assets/cropperjs/dist/cropper.css'
            ];

            $additionalJs = [
                'assets/cropperjs/dist/cropper.js', //assets di modules
                'assets/main-assets/js/profile.js' //assets di modules
            ];

            // view
            $this->lib_template->load('Main/user-profile', $data, $additionalCss, $additionalJs);
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak punya akses ke menu!');
            redirect('Dashboard');
        }
    }

    public function update_data()
    {
        if (can('Main/Main_profile/update_data')) {
            $base64toImg   = new Lib_base64toImg();
            $this->form_validation->set_rules("up_name", "Nama Pengguna", "trim|alpha_numeric_spaces|min_length[3]|max_length[100]|required");
            $this->form_validation->set_rules("up_username", "Username", "trim|min_length[8]|max_length[20]|required");
            $this->form_validation->set_rules("up_email", "E-mail", "trim|min_length[5]|max_length[100]|required");
            $this->form_validation->set_rules("up_phone", "No Handphone", "trim|min_length[10]|max_length[15]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                // $update_data['id'] = $input['up_id'];
                $update_data['name'] = $input['up_name'];
                $update_data['username'] = $input['up_username'];
                $update_data['email'] = $input['up_email'];
                $update_data['phone'] = $input['up_phone'];
                $update_data['updated_at'] = date('Y-m-d H:i:s');


                if (!empty($input['uppassword'])) {
                    $update_data['password'] = sha1($input['uppassword']);
                    $update_data['last_password_update'] = date('Y-m-d H:i:s');
                }
                if (!empty($input['up_user_photo'])) {
                    $isUploaded = $base64toImg->file_uploads('./uploads/profile_picture/', $input['up_user_photo'], md5($update_data['username']), 4096000);
                    if (!$isUploaded['status']) {
                        $output['success'] = false;
                        $output['message'] = $this->upload->display_errors('', '');
                    }
                    $update_data['photo'] = $isUploaded['file_name'];

                    $this->session->set_userdata('photo', $isUploaded['file_name']);
                }

                $where = ['id' => $input['up_id']];

                $this->db->update('main_users', $update_data, $where);

                $this->lib_log->create_log('Update User', $update_data, 'Update');

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
}
