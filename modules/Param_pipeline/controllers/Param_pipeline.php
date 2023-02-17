<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Param_pipeline
 *
 * @property Module Param_pipeline
 */
class Param_pipeline extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->lib_api_sanders->valid_token();

        $this->load->config('pagination');
        $this->load->model('Param_pipeline_model', 'model');
    }

    public function index()
    {

        if (can('Param_pipeline/index') == true) {
            // page title / menu name / validasi akses menu
            $data['_title'] = 'Param Pipeline';

            // URL
            $data['_pageURL'] = site_url('Param_pipeline');
            $data['_filterURL'] = site_url('Param_pipeline/filter_data');
            $data['_clearfilterURL'] = site_url('Param_pipeline/clear_filter');

            $row = 0;
            $row_per_page = $this->lib_pagination->limit();
            $data['total'] = $this->model->all_data_count();
            $data['table'] = $this->model->get_data($row, $row_per_page);
            $data['module'] = $this->model->module();

            $data['param_level_priorities'] = $this->model->get_param_level_priorities();
            $data['param_level_risk'] = $this->model->get_param_level_risk();
            $data['param_tier'] = $this->model->get_tier();


            $_url_plugin = 'vendor/almasaeed2010/adminlte/plugins/';

            $additionalCss = [
                'vendor/almasaeed2010/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
                $_url_plugin . 'tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css', //datepicker
                'modules/Param_pipeline/assets/css/custom.css' //assets di modules
            ];

            $additionalJs = [
                'vendor/almasaeed2010/adminlte/plugins/sweetalert2/sweetalert2.min.js',
                $_url_plugin . 'moment/moment.min.js', //moment js untuk datepicker
                $_url_plugin . 'tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
                'modules/Param_pipeline/assets/js/custom.js' //assets di modules
            ];

            // view
            $this->lib_template->load('list', $data, $additionalCss, $additionalJs);
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak punya akses ke menu!');
            redirect('Dashboard');
        }
    }


    public function pagination()
    {
        $row = $_POST['row'];
        $row_per_page = $this->lib_pagination->limit();
        $data = $this->model->get_data($row, $row_per_page);
        $html = '';
        $no = $row;
        foreach ($data->data as $val) {
            $no++;
            $html .= '
            <tr class="init-table" id="Param_pipeline_' . $val->id_pipeline . '">
            <td class="text-center">' . $no . '</td>
            <td class="text-left">' . $val->pipeline_origin . '</td>
            <td class="text-center">' . $val->level_priorities . '</td>
            <td class="text-center">' . $val->level_risk . '</td>
            <td class="text-center">' . $val->tier_name . '</td>
            <td class="text-center">';
            if (can('Param_pipeline/update_data')) {
                $html .= '<button type="button" class="btn btn-xs btn-action-icon btn-warning" onclick="_modal_update(\'' . $val->id_pipeline . '\')">
                        <i class="fas fa-pencil-alt"></i>
                    </button> ';
            } else {
                $html .= '<button type="button" class="btn btn-xs btn-action-icon btn-warning" disabled>
                        <i class="fas fa-pencil-alt"></i>
                    </button> ';
            }
            if (can('Param_pipeline/delete_data')) {

                $html .= '<button type="button" class="btn btn-xs btn-action-icon btn-danger" onclick="_delete(\'' . $val->id_pipeline . '\')">
                <i class="fas fa-minus"></i>
                </button>';
            } else {

                $html .= '<button type="button" class="btn btn-xs btn-action-icon btn-danger" disabled>
                <i class="fas fa-minus"></i>
                </button>';
            }
            $html .= '
            </td>
        </tr>
            ';
        }
        echo $html;
    }

    public function filter_data()
    {
        // set filter session
        $input = $this->input->post();
        $result = array();
        if ($input['pipeline_origin'] != '') {
            $result['pipeline_origin'] = $input['pipeline_origin'];
        }
        if ($input['tier'] != '') {
            $result['tier'] = $input['tier'];
        }
        if ($input['level_of_priorities'] != '') {
            $result['level_of_priorities'] = $input['level_of_priorities'];
        }
        if ($input['level_of_risk'] != '') {
            $result['level_of_risk'] = $input['level_of_risk'];
        }
        $filters["Param_pipeline"] = $result;

        $this->session->set_userdata($filters);
        redirect(site_url('Param_pipeline'));
    }

    public function clear_filter()
    {
        if ($this->input->post('session') != '') {
            // clear specific filter session
            $filter = $this->input->post('session');
            $sess_filter = $this->session->userdata('Param_pipeline');
            unset($sess_filter[$filter]);
            $this->session->set_userdata('Param_pipeline', $sess_filter);
            $return['success'] = true;
            echo json_encode($return);
        } else {
            // clear filter session
            $this->session->unset_userdata('Param_pipeline');
            redirect(site_url('Param_pipeline'));
        }
    }

    public function create_data()
    {
        // validasi module bisa menulis / mengubah
        if (can('Param_pipeline/create_data') == true) {
            $this->form_validation->set_data($this->input->post());
            $this->form_validation->set_rules('in_pipeline_origin', 'Pipeline origin', 'required');
            $this->form_validation->set_rules('in_level_of_priorities', 'Level of Priorities', 'trim|required');
            $this->form_validation->set_rules('in_level_of_risk', 'Level of Risk', 'trim|required');
            $this->form_validation->set_rules('in_tier', 'Tier', 'trim|required');


            $this->form_validation->set_message('required', '%s tidak boleh kosong.');

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $insert_data['pipeline_origin']     = $input['in_pipeline_origin'];
                $insert_data['level_of_priorities'] = $input['in_level_of_priorities'];
                $insert_data['level_of_risk']       = $input['in_level_of_risk'];
                $insert_data['tier']                = $input['in_tier'];
                $insert_data['created_at']          = date("Y-m-d H:i:s");


                $data = [
                    'form_params' => $insert_data
                ];

                $proses = $this->model->add($data);

                if ($proses->success) {
                    $output['success'] = true;
                    $output['message'] = 'Data berhasil ditambahkan.';
                } else {
                    $output['success'] = false;
                    $message = '';
                    if (is_array($proses->error->message)) {
                        foreach ($proses->error->message as $key => $value) {
                            $message .= $value . ' <br>';
                        }
                    } else {
                        $message = $proses->error->message;
                    }
                    $output['message'] = $message;
                }
            } else {
                $output['success'] = false;
                // $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
                $output['message'] = validation_errors();
            }
        } else {
            $output['success'] = false;
            $output['message'] = "Anda Tidak Memiliki Akses Membuat Data.";
        }
        echo json_encode($output);
    }

    public function get_data()
    {
        $this->form_validation->set_rules("id_pipeline", "ID Pipeline", "trim|required");

        if ($this->form_validation->run() == true) {
            $params =  $this->input->post('id_pipeline');
            // dd($id);
            $output = $this->model->get_id($params);
        } else {
            $output['success'] = false;
            $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
        }
        echo json_encode($output);
    }

    public function update_data()
    {
        // validasi module bisa menulis / mengubah
        if (can('Param_pipeline/update_data') == true) {
            $this->form_validation->set_data($this->input->post());

            $this->form_validation->set_rules('up_pipeline_origin', 'Param Pipeline', 'trim|required');
            $this->form_validation->set_rules('up_level_of_priorities', 'Level of Priorities', 'trim|required');
            $this->form_validation->set_rules('up_level_of_risk', 'Level of Risk', 'trim|required');
            $this->form_validation->set_rules('up_tier', 'Tier', 'trim|required');

            $this->form_validation->set_message('required', '%s tidak boleh kosong.');

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();


                $update_data['pipeline_origin']     = $input['up_pipeline_origin'];
                $update_data['level_of_priorities'] = $input['up_level_of_priorities'];
                $update_data['level_of_risk']       = $input['up_level_of_risk'];
                $update_data['tier']                = $input['up_tier'];
                $update_data['updated_at']          = date("Y-m-d H:i:s");


                $data = [
                    'form_params' => $update_data
                ];

                $proses = $this->model->edit($data, $input['up_id']);
                if ($proses->success) {
                    $output['success'] = true;
                    $output['message'] = 'Data berhasil diubah.';
                } else {
                    $output['success'] = false;
                    $message = '';
                    if (is_array($proses->error->message)) {
                        foreach ($proses->error->message as $key => $value) {
                            $message .= $value . ' <br>';
                        }
                    } else {
                        $message = $proses->error->message;
                    }
                    $output['message'] = $message;
                }
            } else {
                $output['success'] = false;
                $output['message'] = validation_errors();
                // $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
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
        if (can('Param_pipeline/delete_data') == true) {
            $this->form_validation->set_rules("id", "id param", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                $proses = $this->model->delete($input['id']);

                if ($proses->success) {
                    $output['success'] = true;
                    $output['message'] = 'Data berhasil dihapus.';
                } else {
                    $output['success'] = false;
                    $message = '';
                    if (is_array($proses->error->message)) {
                        foreach ($proses->error->message as $key => $value) {
                            $message .= $value . ' <br>';
                        }
                    } else {
                        $message = $proses->error->message;
                    }
                    $output['message'] = $message;
                }
            } else {
                $output['success'] = false;
                $output['message'] = validation_errors();

                // $output['message'] = str_replace("\n", "", strip_tags(validation_errors()));
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
        if (can('Param_pipeline/export_data') == true) {
            $this->form_validation->set_rules("user_export_pin", "PIN", "trim|min_length[8]|max_length[8]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                if ($this->authenticate->check_pin(md5($input['user_export_pin']))) {
                    $output['success'] = true;
                    $output['message'] = 'Mohon tunggu, file sedang disiapkan.';
                    $output['exportURL'] = site_url('Param_pipeline/export_data') . "?pin=" . md5($input['user_export_pin']);
                } else {
                    $output['success'] = false;
                    $output['message'] = 'PIN yang Anda masukan salah!';
                }
            } else {
                $output['success'] = false;
                $output['message'] = validation_errors();
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
        if (can('Param_pipeline/export_data') == true) {
            $this->lib_log->create_log('Export Param_pipeline', null, 'Export');

            $pin = isset($_REQUEST['pin']) ? $_REQUEST['pin'] : null;

            if ($this->authenticate->check_pin($pin)) {

                $module_data = $this->model->all();

                $spreadsheet = new Spreadsheet;

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Asal Pipeline')
                    ->setCellValue('C1', 'Level of Priorities')
                    ->setCellValue('D1', 'Level of Risk')
                    ->setCellValue('E1', 'Tier');

                $spreadsheet
                    ->getActiveSheet(0)
                    ->getStyle("A1:E1")
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('001f3f');
                $spreadsheet
                    ->getActiveSheet(0)
                    ->getStyle("A1:E1")
                    ->getFont()
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
                $spreadsheet->getActiveSheet(0)->getColumnDimension('A')->setWidth(5)->setAutoSize(true);
                $spreadsheet->getActiveSheet(0)->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet(0)->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet(0)->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet(0)->getColumnDimension('E')->setAutoSize(true);

                $spreadsheet->getActiveSheet(0)->getStyle('A1:E1')->getFont()->setBold(true);
                $spreadsheet->getActiveSheet(0)->getStyle('A1:E1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $spreadsheet->getActiveSheet(0)->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $rows = 2;
                $first_rows_data = 1;
                $no = 1;
                foreach ($module_data->data as $val) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $rows, $no)
                        ->setCellValue('B' . $rows, $val->pipeline_origin)
                        ->setCellValue('C' . $rows, $val->level_priorities)
                        ->setCellValue('D' . $rows, $val->level_risk)
                        ->setCellValue('E' . $rows, $val->tier_name);

                    $no++;
                    $rows++;
                    $first_rows_data++;

                    $spreadsheet->getActiveSheet()->getStyle('A1:E' . $first_rows_data)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM)
                        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('acacac'));
                }

                $writer = new Xlsx($spreadsheet);


                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Param_pipeline-' . date('d-m-Y H:i:s') . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
                exit;
            } else {
                echo '<script>toastr.error("Anda belum memasukan PIN!")</script>';
            }
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak Memiliki Akses Mengunduh Data.');
            redirect(site_url('Param_pipeline'));
            die();
        }
    }

    public function guide_dummy()
    {
        echo '<object data="' . base_url() . 'modules/Param_pipeline/assets/guide.pdf" type="application/pdf" width="100%" height="600px">
                        <embed src="' . base_url() . 'modules/Param_pipeline/assets/guide.pdf" type="application/pdf">
                    </object>';
    }

    function increase($letter)
    {
        $letter++;
        return $letter;
    }
}
