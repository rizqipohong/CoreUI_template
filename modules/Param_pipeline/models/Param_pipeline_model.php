<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Param_pipeline_model extends CI_Model
{
  /**
   * Permission constructor.
   */
  public function __construct()
  {
    parent::__construct();
  }


  public function module()
  {
    return $this->db->where('name', 'Param Pipeline')->get('main_modules')->row();
  }


  public function find($params)
  {
    if (!empty($params)) {
      $filters = array();

      foreach ($params as $key => $r) {
        $filters[] = 'filters[' . $key . ']=' . $r;
        $filters[$key] = $r;
      }
      $filterString = '?' . join('&', $filters);
      $url = 'param_pipeline' . $filterString;
    } else {
      $url = 'param_pipeline';
    }
    $array_data = $this->lib_api_sanders->params($params);
    $data_api = $this->lib_api_sanders->connection('get', $url, $array_data);
    if (isset($data_api->error)) {
      $this->session->set_flashdata('msgErrorGet', $data_api->error->message);
    }
    return $data_api;
  }


  public function all()
  {
    $params = $this->session->userdata('Param_pipeline') != '' ? $this->session->userdata('Param_pipeline') : [];
    $data = $this->find($params);
    return $data;
  }

  public function get_param_level_priorities($id = '')
  {
    $url = $id != '' ? "param_level_priorities" : "param_level_priorities/" . $id;
    $array_data = $this->lib_api_sanders->params();
    $data_api = $this->lib_api_sanders->connection('get', $url, $array_data);
    return $data_api->data;
  }
  public function get_param_level_risk($id = '')
  {
    $url = $id != '' ? "param_level_risk" : "param_level_risk/" . $id;
    $array_data = $this->lib_api_sanders->params();
    $data_api = $this->lib_api_sanders->connection('get', $url, $array_data);
    return $data_api->data;
  }
  public function get_tier($id = '')
  {
    $url = $id != '' ? "master_tier" : "master_tier/" . $id;
    $array_data = $this->lib_api_sanders->params();
    $data_api = $this->lib_api_sanders->connection('get', $url, $array_data);
    return $data_api->data;
  }


  public function all_data_count()
  {
    $params = $this->session->userdata('Param_pipeline') != '' ? $this->session->userdata('Param_pipeline') : [];
    $data = $this->find($params);

    $total = count($data->data);
    return $total;
  }


  public function get_data($page, $row_per_page)
  {

    $params = $this->session->userdata('Param_pipeline') != '' ? $this->session->userdata('Param_pipeline') : [];
    $params['page'] = $page;
    $params['row_per_page'] = $row_per_page;
    $data = $this->find($params);
    return $data;
  }

  public function add($data)
  {
    $url = 'param_pipeline';
    $array_data = $this->lib_api_sanders->params($data);
    $data_api = $this->lib_api_sanders->connection('post', $url, $array_data);
    // dd($data_api);

    if ($data_api->success) {
      $this->lib_log->create_log('Insert pipeline', $data, 'Insert');
    }
    return $data_api;
  }

  public function edit($data, $id)
  {
    $url = 'param_pipeline/' . $id;
    $array_data = $this->lib_api_sanders->params($data);
    // dd($array_data);
    $data_api = $this->lib_api_sanders->connection('put', $url, $array_data);
    // dd($data_api);
    if ($data_api->success) {
      $this->lib_log->create_log('Update param pipeline', $data, 'Update');
    }

    return $data_api;
  }

  public function get_id($params)
  {

    $url = 'param_pipeline/' . $params;
    $array_data = $this->lib_api_sanders->params($params);
    $data_api = $this->lib_api_sanders->connection('get', $url, $array_data);
    if (isset($data_api->error)) {
      $this->session->set_flashdata('msgErrorGet', $data_api->error->message);
    }
    return $data_api;
  }


  public function delete($id)
  {
    $url = 'param_pipeline/' . $id;
    $array_data = $this->lib_api_sanders->params();
    $data_api = $this->lib_api_sanders->connection('delete', $url, $array_data);
    if ($data_api->success) {
      $this->lib_log->create_log('Delete tipe pipeline', $id, 'Delete');
    }

    return $data_api;
  }
}
