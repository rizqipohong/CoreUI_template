<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class Main_module
 *
 * @property Module module_model
 */
class Main_module extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->config('pagination');
        $this->load->model('Main/Module', 'module_model');
    }

    public function index()
    {
        if (can('Main/Main_module/index') == true) {
            // page title / menu name / validasi akses menu
            $data['_title'] = 'Modul';

            // URL
            $data['_pageURL'] = site_url('Main/Main_module');
            $data['_filterURL'] = site_url('Main/Main_module/filter_data');
            $data['_clearfilterURL'] = site_url('Main/Main_module/clear_filter');

            $data['_createURL'] = site_url('Main/Main_module/create_data');
            $data['_getupdateURL'] = site_url('Main/Main_module/get_data');
            $data['_updateURL'] = site_url('Main/Main_module/update_data');
            $data['_deleteURL'] = site_url('Main/Main_module/delete_data');
            $data['_exportURL'] = site_url('Main/Main_module/check_pin_export');

            // filter
            $data['_fil_module_name'] = isset($_SESSION['_fil_module_name']) ? $_SESSION['_fil_module_name'] : null;
            $data['_fil_module_access_status'] = isset($_SESSION['_fil_module_access_status']) ? $_SESSION['_fil_module_access_status'] : null;

            // module data
            $limit = $this->lib_pagination->limit();
            $offset = isset($_REQUEST['per_page']) ? $_REQUEST['per_page'] : null;

            $data['module_data'] = $this->module_model->all();
            $data['module_data_count'] = count($data['module_data']);

            // pagination
            $data['pagination'] = $this->lib_pagination->pagination($data['_pageURL'], $limit, $data['module_data_count']);
            $additionalCss = [];

            $additionalJs = [
                'assets/main-assets/js/module.js' //assets di modules
            ];

            // view
            $this->lib_template->load('Main/module-list', $data, $additionalCss, $additionalJs);
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak punya akses ke menu!');
            redirect('Dashboard');
        }
    }

    public function filter_data()
    {
        // set filter session
        $input = $this->input->post();
        $this->session->set_userdata('_fil_module_name', $input['module_name']);
        $this->session->set_userdata('_fil_module_access_status', $input['access_status']);

        redirect(site_url('Main/Main_module'));
    }

    public function clear_filter()
    {
        // clear filter session
        $uri = $this->uri->segment(4);

        if ($uri == 'module_name') {
            $this->session->unset_userdata('_fil_module_name');
        } else if ($uri == 'access_status') {
            $this->session->unset_userdata('_fil_module_access_status');
        } else {
            $this->session->unset_userdata('_fil_module_name');
            $this->session->unset_userdata('_fil_module_access_status');
        }
        redirect(site_url('Main/Main_module'));
    }

    public function create_data()
    {
        // validasi module bisa menulis / mengubah
        if (can('Main/Main_module/create_data') == true) {
            $this->form_validation->set_rules("name", "Nama Modul", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                $insert_data['id'] = $this->lib_code->create_id('MD', 'main_modules', 'id');

                foreach ($input as $key => $val) {
                    $insert_data[$key] = $val;
                }

                // simpan data
                $this->module_model->add($insert_data);

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
        $this->form_validation->set_rules("module_id", "ID Modul", "trim|required");

        if ($this->form_validation->run() == true) {
            $module_id = $this->input->post('module_id');
            $data = $this->module_model->find($module_id);
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
        // validasi module bisa menulis / mengubah
        if (can('Main/Main_module/update_data') == true) {
            $input = $this->input->post();
            $this->form_validation->set_rules("module_id", "ID Peran", "trim|required");
            $this->form_validation->set_rules("name", "Nama Modul", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $update_data['id'] = $input['module_id'];
                $update_data['name'] = $input['name'];
                $update_data['description'] = $input['description'];
                $update_data['status'] = $input['status'];
                $this->module_model->edit($update_data);

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
        // validasi module bisa menghapus
        if (can('Main/Main_module/delete_data') == true) {
            $this->form_validation->set_rules("module_id", "ID Peran", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $this->module_model->delete($input['module_id']);

                //                // membuat log
                //                $this->lib_log->create_log('Delete Modul', null, 'Delete');

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
        if (can('Main/Main_module/check_pin_export') == true) {
            $this->form_validation->set_rules("user_export_pin", "PIN", "trim|min_length[8]|max_length[8]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                if ($input['user_export_pin'] == '12345678') {
                    $output['success'] = true;
                    $output['message'] = 'Mohon tunggu, file sedang disiapkan.';
                    $output['exportURL'] = site_url('Main/Main_module/export_data') . "?pin=" . $input['user_export_pin'];
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
        if (can('Main/Main_module/export_data') == true) {
            $pin = isset($_REQUEST['pin']) ? $_REQUEST['pin'] : null;
            $role_name = isset($_SESSION['_fil_module_name']) ? $_SESSION['_fil_module_name'] : null;
            $access_status = isset($_SESSION['_fil_module_access_status']) ? $_SESSION['_fil_module_access_status'] : null;

            if ($pin) {
                $module_data = $this->main_model->get_module_data($module_name, $access_status)->result();

                $spreadsheet = new Spreadsheet;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'module_id')
                    ->setCellValue('C1', 'module_name')
                    ->setCellValue('D1', 'landing_page')
                    ->setCellValue('E1', 'module_read')
                    ->setCellValue('F1', 'module_write')
                    ->setCellValue('G1', 'module_delete')
                    ->setCellValue('H1', 'module_export')
                    ->setCellValue('I1', 'landing_page')
                    ->setCellValue('J1', 'access_status')
                    ->setCellValue('K1', 'created_at');

                $rows = 2;
                $no = 1;

                foreach ($module_data as $key => $val) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $rows, $no)
                        ->setCellValue('B' . $rows, $val->module_id)
                        ->setCellValue('C' . $rows, $val->module_name)
                        ->setCellValue('D' . $rows, $val->landing_page)
                        ->setCellValue('E' . $rows, $val->module_read)
                        ->setCellValue('F' . $rows, $val->module_write)
                        ->setCellValue('G' . $rows, $val->module_delete)
                        ->setCellValue('H' . $rows, $val->module_export)
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
            redirect(site_url('Main/Main_module'));
            die();
        }
    }
}
