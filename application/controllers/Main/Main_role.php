<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class Main_role
 *
 * @property Role role_model
 * @property Permission permission_model
 */
class Main_role extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        //        $this->authenticate->route_access();

        $this->load->config('pagination');
        $this->load->model('Main/Role', 'role_model');
        $this->load->model('Main/Permission', 'permission_model');
    }

    public function index()
    {
        if (can('Main/Main_role/index') == true) {
            // page title / menu name / validasi akses menu
            $data['_title'] = 'Main Role';

            // URL
            $data['_pageURL'] = site_url('Main/Main_role');
            $data['_filterURL'] = site_url('Main/Main_role/filter_data');
            $data['_clearfilterURL'] = site_url('Main/Main_role/clear_filter');

            $data['_createURL'] = site_url('Main/Main_role/create_data');
            $data['_getupdateURL'] = site_url('Main/Main_role/get_data');
            $data['_updateURL'] = site_url('Main/Main_role/update_data');
            $data['_deleteURL'] = site_url('Main/Main_role/delete_data');
            $data['_exportURL'] = site_url('Main/Main_role/check_pin_export');

            // filter
            $data['_fil_role_name'] = isset($_SESSION['_fil_role_name']) ? $_SESSION['_fil_role_name'] : null;
            $data['_fil_role_access_status'] = isset($_SESSION['_fil_role_access_status']) ? $_SESSION['_fil_role_access_status'] : null;

            // role data
            $limit = $this->lib_pagination->limit();
            $offset = isset($_REQUEST['per_page']) ? $_REQUEST['per_page'] : null;

            //        $role_data = $this->role_model->all();
            $role_data = $this->role_model->get_dashboard_data($limit, $offset, $data['_fil_role_name'], $data['_fil_role_access_status']);
            $data['role_data'] = $role_data->result();
            $data['role_data_count'] = $role_data->num_rows();
            $additionalCss = [];
            $additionalJs = [
                'assets/main-assets/js/role.js' //assets di modules
            ];
            // pagination
            $data['pagination'] = $this->lib_pagination->pagination($data['_pageURL'], $limit, $data['role_data_count']);

            // view
            $this->lib_template->load('Main/role-list', $data, $additionalCss, $additionalJs);
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak punya akses ke menu!');
            redirect('Dashboard');
        }
    }

    public function filter_data()
    {
        // set filter session
        $input = $this->input->post();
        $this->session->set_userdata('_fil_role_name', $input['role_name']);
        if ($input['access_status'] !== '') {
            $this->session->set_userdata('_fil_role_access_status', $input['access_status']);
        }

        redirect(site_url('Main/Main_role'));
    }

    public function clear_filter()
    {
        // clear filter session
        $uri = $this->uri->segment(4);

        if ($uri == 'role_name') {
            $this->session->unset_userdata('_fil_role_name');
        } else if ($uri == 'access_status') {
            $this->session->unset_userdata('_fil_role_access_status');
        } else {
            $this->session->unset_userdata('_fil_role_name');
            $this->session->unset_userdata('_fil_role_access_status');
        }
        redirect(site_url('Main/Main_role'));
    }

    public function create_data()
    {
        // validasi role bisa menulis / mengubah
        if (can('Main/Main_role/create_data') == true) {
            $this->form_validation->set_rules("name", "Nama Peran", "trim|alpha_numeric_spaces|min_length[3]|max_length[30]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                foreach ($input as $key => $val) {
                    $insert_data[$key] = $val;
                }

                // simpan data
                $this->role_model->add($insert_data);

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
        $this->form_validation->set_rules("role_id", "ID Peran", "trim|required");

        if ($this->form_validation->run() == true) {
            $role_id = $this->input->post('role_id');
            $data = $this->role_model->find($role_id);
            $output['data'] = $data;
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
        if (can('Main/Main_role/update_data') == true) {
            $input = $this->input->post();
            $this->form_validation->set_rules("role_id", "ID Peran", "trim|required");
            $this->form_validation->set_rules("name", "Nama Peran", "trim|required");
            $this->form_validation->set_rules("display_name", "Nama yang ditampilkan", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $update_data['id'] = $input['role_id'];
                $update_data['name'] = $input['name'];
                $update_data['display_name'] = $input['display_name'];
                $update_data['description'] = $input['description'];
                $update_data['status'] = $input['status'];
                $this->role_model->edit($update_data);

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
        if (can('Main/Main_role/delete_data') == true) {
            $this->form_validation->set_rules("role_id", "ID Peran", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                $this->role_model->delete($input['role_id']);

                // membuat log 
                $this->lib_log->create_log('Delete Role', null, 'Delete');

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

    public function permission($id)
    {
        if (can('Main/Main_role/permission') == true) {
            $data['permission_data'] = $this->role_model->all_permission_by_role_id($id);
            $data['_getupdateURL'] = site_url('Main/Main_role/edit_permission/' . $id);

            $data['_title'] = 'Role Permission';
            $additionalCss = [
                'vendor/almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css'
            ];
            $additionalJs = [
                'assets/main-assets/js/role_permission.js' //assets di modules
            ];
            $this->lib_template->load('Main/permission-role', $data, $additionalCss, $additionalJs);
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak punya akses ke menu!');
            redirect('Dashboard');
        }
    }

    public function edit_permission($id)
    {
        if (can('Main/Main_role/edit_permission') == true) {
            $permissionArr = $this->input->post('permission_id');
            if (is_null($permissionArr)) {
                $output['success'] = false;
                $output['message'] = 'Anda harus memilih permission role terlebih dahulu!';
            } else {
                $this->role_model->editPermissions($id, $permissionArr);

                $output['success'] = true;
                $output['message'] = 'Data berhasil diubah.';
            }
            echo json_encode($output);
        } else {
            $output['success'] = false;
            $output['message'] = "Anda Tidak Memiliki Akses Edit Data.";
        }
    }


    public function check_pin_export()
    {
        // validasi role bisa Mengunduh
        if (can('Main/Main_role/check_pin_export') == true) {
            $this->form_validation->set_rules("user_export_pin", "PIN", "trim|min_length[8]|max_length[8]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                if ($input['user_export_pin'] == '12345678') {
                    $output['success'] = true;
                    $output['message'] = 'Mohon tunggu, file sedang disiapkan.';
                    $output['exportURL'] = site_url('Main/Main_role/export_data') . "?pin=" . $input['user_export_pin'];
                } else {
                    $output['success'] = false;
                    $output['message'] = 'PIN yang Anda masukan salah!';
                }
            } else {
                $output['success'] = false;
                $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
            }
        } else {
            $output['success'] = false;
            $output['message'] = "Anda Tidak Memiliki Akses Mengunduh Data.";
        }
        echo json_encode($output);
    }

    public function export_data()
    {
        // validasi role bisa Mengunduh
        if (can('Main/Main_role/export_data') == true) {
            $pin = isset($_REQUEST['pin']) ? $_REQUEST['pin'] : null;
            $role_name = isset($_SESSION['_fil_role_name']) ? $_SESSION['_fil_role_name'] : null;
            $access_status = isset($_SESSION['_fil_role_access_status']) ? $_SESSION['_fil_role_access_status'] : null;

            if ($pin) {
                $role_data = $this->role_model->get_dashboard_data(null, null, $role_name, $access_status)->result();

                $spreadsheet = new Spreadsheet;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'id')
                    ->setCellValue('C1', 'Role Name')
                    ->setCellValue('D1', 'Display Name')
                    ->setCellValue('E1', 'Description')
                    ->setCellValue('F1', 'Status');

                $rows = 2;
                $no = 1;


                foreach ($role_data as $key => $val) {
                    $statusDesc = ($val->status ? 'Activated' : 'Not Activated');
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $rows, $no)
                        ->setCellValue('B' . $rows, $val->id)
                        ->setCellValue('C' . $rows, $val->name)
                        ->setCellValue('D' . $rows, $val->display_name)
                        ->setCellValue('E' . $rows, $val->description)
                        ->setCellValue('F' . $rows, $statusDesc);
                    $rows++;
                    $no++;
                }

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Peran-Pengguna-' . date('d-m-Y') . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                echo '<script>toastr.error("Anda belum memasukan PIN!")</script>';
            }
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak Memiliki Akses Mengunduh Data.');
            redirect(site_url('Main/Main_role'));
            die();
        }
    }
}
