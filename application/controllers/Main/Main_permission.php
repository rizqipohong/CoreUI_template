<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class Main_permission
 *
 * @property Module module_model
 * @property Permission permission_model
 */
class Main_permission extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->config('pagination');
        $this->load->model('Main/Module', 'module_model');
        $this->load->model('Main/Permission', 'permission_model');

        // $this->authenticate->route_access();
    }

    public function index()
    {
        if (can('Main/Main_permission/index') == true) {
            // page title / menu name / validasi akses menu
            $data['_title'] = 'Permission';

            // URL
            $data['_pageURL'] = site_url('Main/Main_permission');
            $data['_filterURL'] = site_url('Main/Main_permission/filter_data');
            $data['_clearfilterURL'] = site_url('Main/Main_permission/clear_filter');

            $data['_createURL'] = site_url('Main/Main_permission/create_data');
            $data['_getupdateURL'] = site_url('Main/Main_permission/get_data');
            $data['_updateURL'] = site_url('Main/Main_permission/update_data');
            $data['_deleteURL'] = site_url('Main/Main_permission/delete_data');
            $data['_exportURL'] = site_url('Main/Main_permission/check_pin_export');

            // filter
            $data['_fil_permission_name'] = isset($_SESSION['_fil_permission_name']) ? $_SESSION['_fil_permission_name'] : null;
            $data['_fil_permission_access_status'] = isset($_SESSION['_fil_permission_access_status']) ? $_SESSION['_fil_permission_access_status'] : null;

            // permission data
            $limit = $this->lib_pagination->limit();
            $offset = isset($_REQUEST['per_page']) ? $_REQUEST['per_page'] : null;

            $data['permission_data'] = $this->permission_model->all();
            $data['module_data'] = $this->module_model->all();
            $data['permission_data_count'] = count($data['permission_data']);

            // pagination
            $data['pagination'] = $this->lib_pagination->pagination($data['_pageURL'], $limit, $data['permission_data_count']);
            $additionalCss = [];

            $additionalJs = [
                'assets/main-assets/js/permission.js' //assets di modules
            ];

            // view
            $this->lib_template->load('Main/permission-list', $data, $additionalCss, $additionalJs);
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak punya akses ke menu!');
            redirect('Dashboard');
        }
    }

    public function filter_data()
    {
        // set filter session
        $input = $this->input->post();
        $this->session->set_userdata('_fil_permission_name', $input['permission_name']);
        $this->session->set_userdata('_fil_permission_access_status', $input['access_status']);

        redirect(site_url('Main/Main_permission'));
    }

    public function clear_filter()
    {
        // clear filter session
        $uri = $this->uri->segment(4);

        if ($uri == 'permission_name') {
            $this->session->unset_userdata('_fil_permission_name');
        } else if ($uri == 'access_status') {
            $this->session->unset_userdata('_fil_permission_access_status');
        } else {
            $this->session->unset_userdata('_fil_permission_name');
            $this->session->unset_userdata('_fil_permission_access_status');
        }
        redirect(site_url('Main/Main_permission'));
    }

    public function create_data()
    {
        // validasi permission bisa menulis / mengubah
        if (can('Main/Main_permission/create_data') == true) {
            $this->form_validation->set_rules("name", "Nama Permission", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                $insert_data['id'] = $this->lib_code->create_id('PR', 'main_permissions', 'id');

                foreach ($input as $key => $val) {
                    $insert_data[$key] = $val;
                }

                // simpan data
                $this->permission_model->add($insert_data);

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
        $this->form_validation->set_rules("permission_id", "ID Permission", "trim|required");

        if ($this->form_validation->run() == true) {
            $permission_id = $this->input->post('permission_id');
            $data = $this->permission_model->find($permission_id);
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
        // validasi permission bisa menulis / mengubah
        if (can('Main/Main_permission/update_data') == true) {
            $input = $this->input->post();
            $this->form_validation->set_rules("permission_id", "ID Peran", "trim|required");
            $this->form_validation->set_rules("name", "Nama Permission", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $update_data['id'] = $input['permission_id'];
                $update_data['name'] = $input['name'];
                $update_data['display_name'] = $input['display_name'];
                $update_data['description'] = $input['description'];
                $update_data['status'] = $input['status'];
                $update_data['module_id'] = $input['module_id'];
                $this->permission_model->edit($update_data);

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
        // validasi permission bisa menghapus
        if (can('Main/Main_permission/delete_data') == true) {
            $this->form_validation->set_rules("permission_id", "ID Peran", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $this->permission_model->delete($input['permission_id']);

                //                // membuat log
                //                $this->lib_log->create_log('Delete Permission', null, 'Delete');

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

    public function check_pin_export()
    {
        // validasi role bisa Mengunduh
        if (can('Main/Main_permission/check_pin_export') == true) {
            $this->form_validation->set_rules("user_export_pin", "PIN", "trim|min_length[8]|max_length[8]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                if ($input['user_export_pin'] == '12345678') {
                    $output['success'] = true;
                    $output['message'] = 'Mohon tunggu, file sedang disiapkan.';
                    $output['exportURL'] = site_url('Main/Main_permission/export_data') . "?pin=" . $input['user_export_pin'];
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
        if (can('Main/Main_permission/export_data') == true) {
            $pin = isset($_REQUEST['pin']) ? $_REQUEST['pin'] : null;
            $role_name = isset($_SESSION['_fil_permission_name']) ? $_SESSION['_fil_permission_name'] : null;
            $access_status = isset($_SESSION['_fil_permission_access_status']) ? $_SESSION['_fil_permission_access_status'] : null;

            if ($pin) {
                $permission_data = $this->main_model->get_permission_data($permission_name, $access_status)->result();

                $spreadsheet = new Spreadsheet;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'permission_id')
                    ->setCellValue('C1', 'permission_name')
                    ->setCellValue('D1', 'landing_page')
                    ->setCellValue('E1', 'permission_read')
                    ->setCellValue('F1', 'permission_write')
                    ->setCellValue('G1', 'permission_delete')
                    ->setCellValue('H1', 'permission_export')
                    ->setCellValue('I1', 'landing_page')
                    ->setCellValue('J1', 'access_status')
                    ->setCellValue('K1', 'created_at');

                $rows = 2;
                $no = 1;

                foreach ($permission_data as $key => $val) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $rows, $no)
                        ->setCellValue('B' . $rows, $val->permission_id)
                        ->setCellValue('C' . $rows, $val->permission_name)
                        ->setCellValue('D' . $rows, $val->landing_page)
                        ->setCellValue('E' . $rows, $val->permission_read)
                        ->setCellValue('F' . $rows, $val->permission_write)
                        ->setCellValue('G' . $rows, $val->permission_delete)
                        ->setCellValue('H' . $rows, $val->permission_export)
                        ->setCellValue('I' . $rows, $val->landing_page)
                        ->setCellValue('J' . $rows, $val->access_status)
                        ->setCellValue('K' . $rows, date('d/m/Y H:i:s', strtotime($val->created_at)));

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
            redirect(site_url('Main/Main_permission'));
            die();
        }
    }
}
