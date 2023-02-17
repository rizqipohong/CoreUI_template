<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Main_log extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->config('pagination');
        $this->load->model('main/main_model');

        // validasi akses login
        $this->lib_role->valid_access();

        // validasi role bisa melihat
        if ($this->lib_role->can('read') == false) {
            $this->session->set_flashdata('alert_error', 'Anda Tidak Memiliki Akses Halaman.');
            $landing_page = $this->lib_template->role_landing_page();
            redirect(site_url($landing_page));
            die();
        }
    }

    public function index()
    {
        // page title / menu name / validasi akses menu
        $data['_title'] = $this->lib_template->valid_menu();

        // URL
        $data['_pageURL'] = site_url('Main/Main_log');
        $data['_filterURL'] = site_url('Main/Main_log/filter_data');
        $data['_clearfilterURL'] = site_url('Main/Main_log/clear_filter');

        $data['_exportURL'] =  site_url('Main/Main_log/check_pin_export');

        // filter
        $data['_fil_log_name'] = isset($_SESSION['_fil_log_name']) ? $_SESSION['_fil_log_name'] : null;
        $data['_fil_log_status'] = isset($_SESSION['_fil_log_status']) ? $_SESSION['_fil_log_status'] : null;

        // log data
        $limit  = $this->lib_pagination->limit();
        $offset = isset($_REQUEST['per_page']) ? $_REQUEST['per_page'] : null;

        $data['log_data'] = $this->main_model->get_log_data($data['_fil_log_name'], $data['_fil_log_status'], $limit, $offset)->result();
        $data['log_data_count'] = $this->main_model->get_log_data($data['_fil_log_name'], $data['_fil_log_status'])->num_rows();

        // select menu
        $data['select_menu'] = $this->main_model->get_log_data(null, null, null, null, 'log_status')->result();

        // pagination
        $data['pagination'] = $this->lib_pagination->pagination($data['_pageURL'], $limit, $data['log_data_count']);

        $this->lib_template->load('Main/log-list', $data);
    }

    public function filter_data()
    {
        // set filter session
        $input = $this->input->post();
        $this->session->set_userdata('_fil_log_name', $input['log_name']);
        $this->session->set_userdata('_fil_log_status', $input['log_status']);

        redirect(site_url('Main/Main_log'));
    }

    public function clear_filter()
    {
        // clear filter session
        $uri = $this->uri->segment(4);

        if ($uri == 'log_name') {
            $this->session->unset_userdata('_fil_log_name');
        } else if ($uri == 'log_status') {
            $this->session->unset_userdata('_fil_log_status');
        } else {
            $this->session->unset_userdata('_fil_log_name');
            $this->session->unset_userdata('_fil_log_status');
        }
        redirect(site_url('Main/Main_log'));
    }

    public function check_pin_export()
    {
        // validasi role bisa Mengunduh
        if ($this->lib_role->can('export') == true) {
            $this->form_validation->set_rules("user_export_pin", "PIN", "trim|min_length[8]|max_length[8]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                if ($input['user_export_pin'] == '12345678') {
                    $output['success'] = true;
                    $output['message'] = 'Mohon tunggu, file sedang disiapkan.';
                    $output['exportURL'] = site_url('Main/Main_log/export_data') . "?pin=" . $input['user_export_pin'];
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
        if ($this->lib_role->can('export') == true) {

            $pin = isset($_REQUEST['pin']) ? $_REQUEST['pin'] : null;
            $log_name = isset($_SESSION['_fil_log_name']) ? $_SESSION['_fil_log_name'] : null;
            $log_status = isset($_SESSION['_fil_log_status']) ? $_SESSION['_fil_log_status'] : null;

            if ($pin) {
                $export_data = $this->main_model->get_log_data($log_name, $log_status)->result();

                $spreadsheet = new Spreadsheet;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'log_id')
                    ->setCellValue('C1', 'created_at')
                    ->setCellValue('D1', 'log_name')
                    ->setCellValue('E1', 'log_data')
                    ->setCellValue('F1', 'log_status')
                    ->setCellValue('G1', 'created_by');

                $rows = 2;
                $no = 1;

                foreach ($export_data as $key => $val) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $rows, $no)
                        ->setCellValue('B' . $rows, $val->log_id)
                        ->setCellValue('C' . $rows, date('d/m/Y H:i:s', strtotime($val->created_at)))
                        ->setCellValue('D' . $rows, $val->log_name)
                        ->setCellValue('E' . $rows, $val->log_data)
                        ->setCellValue('F' . $rows, $val->log_status)
                        ->setCellValue('G' . $rows, $val->created_by);

                    $rows++;
                    $no++;
                }

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Log-aplikasi-' . date('d-m-Y') . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                echo '<script>toastr.error("Anda belum memasukan PIN!")</script>';
            }
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak Memiliki Akses Mengunduh Data.');
            redirect(site_url('Main/Main_log'));
            die();
        }
    }
}
