<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Contoh
 *
 * @property Contoh_model contoh_model
 */
class Contoh extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->config('pagination');
        $this->load->model('contoh_model','contoh_model');
    }

    public function index()
    {
        if (can('Contoh/index') == true) {
            // page title / menu name / validasi akses menu
            $data['_title'] = 'Contoh';

            // URL
            $data['_pageURL'] = site_url('Contoh');
            $data['_filterURL'] = site_url('Contoh/filter_data');
            $data['_clearfilterURL'] = site_url('Contoh/clear_filter');

            $row = 0;
            $row_per_page = $this->lib_pagination->limit();
            $data['total'] = count($this->contoh_model->api_get()->data);
            $data['table'] = $this->contoh_model->api_get();
            $data['module'] = $this->contoh_model->module();

            $additionalCss = [
                'vendor/almasaeed2010/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'
            ];

            $additionalJs = [
                'vendor/almasaeed2010/adminlte/plugins/sweetalert2/sweetalert2.min.js',
                'modules/Contoh/assets/js/contoh.js' //assets di modules
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
        $data = $this->contoh_model->get_data($row, $row_per_page);
        $html = '';
        foreach ($data as $val) {
            $html .= '
            <tr class="contoh" id="contoh_' . $val->id . '">
            <td class="text-center">' . $val->id . '</td>
            <td class="text-left">' . $val->nama . '</td>
            <td class="text-left">' . $val->alamat . '</td>
            <td class="text-center">';
            if (can('Contoh/update_data')) {
                $html .= '<button type="button" class="btn btn-xs btn-action-icon btn-warning" onclick="_modal_update(\'' . $val->id . '\')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>';
            } else {
                $html .= '<button type="button" class="btn btn-xs btn-action-icon btn-warning" disabled>
                        <i class="fas fa-pencil-alt"></i>
                    </button>';
            }
            if (can('Contoh/delete_data')) {
                $html .= '<button type="button" class="btn btn-xs btn-action-icon btn-danger" onclick="_delete(\'' . $val->id . '\')">
                        <i class="fas fa-trash-alt"></i>
                    </button>';
            } else {
                $html .= '<button type="button" class="btn btn-xs btn-action-icon btn-danger" disabled>
                        <i class="fas fa-trash-alt"></i>
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
        $filter['contoh'] = $this->input->post();
        $this->session->set_userdata($filter);
        redirect(site_url('Contoh'));
    }

    public function clear_filter()
    {
        // clear filter session
        $this->session->unset_userdata('contoh');
        // exit;
        redirect(site_url('Contoh'));
    }

    public function create_data()
    {
        // validasi module bisa menulis / mengubah
        if (can('Contoh/create_data') == true) {
            $this->form_validation->set_rules("nama", "Nama", "trim|required");
            $this->form_validation->set_rules("alamat", "Alamat", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                $insert_data['id'] = $this->lib_code->create_id('CT', 'tb_contoh', 'id');

                foreach ($input as $key => $val) {
                    $insert_data[$key] = $val;
                }

                // simpan data
                $this->contoh_model->add($insert_data);

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
        $this->form_validation->set_rules("id", "ID", "trim|required");

        if ($this->form_validation->run() == true) {
            $id = $this->input->post('id');
            $data = $this->contoh_model->find($id);
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
        if (can('Contoh/update_data') == true) {
            $input = $this->input->post();
            $this->form_validation->set_rules("nama", "Nama", "trim|required");
            $this->form_validation->set_rules("alamat", "Alamat", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $update_data['id'] = $input['id'];
                $update_data['nama'] = $input['nama'];
                $update_data['alamat'] = $input['alamat'];
                $this->contoh_model->edit($update_data);

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
        if (can('Contoh/delete_data') == true) {
            $this->form_validation->set_rules("id", "ID Peran", "trim|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                $this->contoh_model->delete($input['id']);

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
        if (can('Contoh/export_data') == true) {
            $this->form_validation->set_rules("user_export_pin", "PIN", "trim|min_length[8]|max_length[8]|required");

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();

                if ($this->authenticate->check_pin(md5($input['user_export_pin']))) {
                    $output['success'] = true;
                    $output['message'] = 'Mohon tunggu, file sedang disiapkan.';
                    $output['exportURL'] = site_url('Contoh/export_data') . "?pin=" . md5($input['user_export_pin']);
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
        if (can('Contoh/export_data') == true) {
            $this->lib_log->create_log('Export Contoh', null, 'Export');

            $pin = isset($_REQUEST['pin']) ? $_REQUEST['pin'] : null;

            if ($this->authenticate->check_pin($pin)) {
                $module_data = $this->contoh_model->all();

                $spreadsheet = new Spreadsheet;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Nama')
                    ->setCellValue('C1', 'Alamat')
                    ->setCellValue('D1', 'created_at');

                $rows = 2;
                $no = 1;

                foreach ($module_data as $key => $val) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $rows, $no)
                        ->setCellValue('B' . $rows, $val->nama)
                        ->setCellValue('C' . $rows, $val->alamat)
                        ->setCellValue('D' . $rows, date('d/m/Y H:i:s', strtotime($val->created_at)));

                    $rows++;
                    $no++;
                }

                $writer = new Xlsx($spreadsheet);

                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Contoh-' . date('d-m-Y') . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                echo '<script>toastr.error("Anda belum memasukan PIN!")</script>';
            }
        } else {
            $this->session->set_flashdata('alert_error', 'Anda Tidak Memiliki Akses Mengunduh Data.');
            redirect(site_url('Contoh'));
            die();
        }
    }

    public function template($view)
    {
        // page title / menu name / validasi akses menu
        $data['_title'] = 'Contoh - ' . $view;

        // URL
        // $data['_pageURL'] = site_url('Contoh');
        // $data['_filterURL'] = site_url('Contoh/filter_data');
        // $data['_clearfilterURL'] = site_url('Contoh/clear_filter');

        // $row = 0;
        // $row_per_page = $this->lib_pagination->limit();
        // $data['total'] = $this->contoh_model->all_data_count();
        // $data['table'] = $this->contoh_model->get_data($row, $row_per_page);
        // $data['module'] = $this->contoh_model->module();

        $additionalCss = [
            'vendor/almasaeed2010/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'
        ];

        $additionalJs = [
            'vendor/almasaeed2010/adminlte/plugins/sweetalert2/sweetalert2.min.js',
            'modules/Contoh/assets/js/contoh.js' //assets di modules
        ];

        // view
        $this->lib_template->load('template/' . $view, $data, $additionalCss, $additionalJs);
    }

    public function guide_dummy()
    {
        echo '<object data="' . base_url() . 'modules/Contoh/assets/guide.pdf" type="application/pdf" width="100%" height="600px">
                        <embed src="' . base_url() . 'modules/Contoh/assets/guide.pdf" type="application/pdf">
                    </object>';
    }

    public function api_get()
    {
      // ID di isi jika ingin get where
      $id = '';
      // $id = 2;
      dd($this->contoh_model->api_get($id));
    }

    public function api_post()
    {
      $data = [
        'form_params' => [
          'name' => 'Test Nama',
          'display_name' => 'Test display name',
          'description' => 'Test Deskripsi'
        ]
      ];
      dd($this->contoh_model->api_post($data));
    }

    public function api_put()
    {
      $id = 2;
      $data = [
        'form_params' => [
          'name' => 'Test Nama PUT',
          'display_name' => 'Test display name PUT',
          'description' => 'Test Deskripsi PUT'
        ]
      ];
      dd($this->contoh_model->api_put($id, $data));
    }

    public function api_delete()
    {
      $id = 2;
      dd($this->contoh_model->api_delete($id));
    }

}
