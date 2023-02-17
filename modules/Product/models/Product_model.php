<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
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
    return $this->db->where('name', 'Product')->get('main_modules')->row();
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
      $url = 'product' . $filterString;
    } else {
      $url = 'product';
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
    $params = $this->session->userdata('Product') != '' ? $this->session->userdata('Product') : [];
    $data = $this->find($params);
    return $data;
  }

  public function all_data_count()
  {
    $params = $this->session->userdata('Product') != '' ? $this->session->userdata('Product') : [];
    $data = $this->find($params);

    $total = count($data->data);
    return $total;
  }


  public function get_data($page, $row_per_page)
  {
    $params = $this->session->userdata('Product') != '' ? $this->session->userdata('Product') : [];
    $params['page'] = $page;
    $params['row_per_page'] = $row_per_page;
    $data = $this->find($params);
    return $data;
  }

  public function add($data)
  {
    $url = 'product';
    $array_data = $this->lib_api_sanders->params($data);
    $data_api = $this->lib_api_sanders->connection('post', $url, $array_data);
    if ($data_api->success) {
      $this->lib_log->create_log('Insert Product', $data, 'Insert');
    }
    return $data_api;
  }

  public function edit($data, $id)
  {
    $url = 'product/' . $id;
    $array_data = $this->lib_api_sanders->params($data);
    $data_api = $this->lib_api_sanders->connection('put', $url, $array_data);
    if ($data_api->success) {
      $this->lib_log->create_log('Update product', $data, 'Update');
    }
    return $data_api;
  }

  public function get_id($params)
  {
    $url = 'product/' . $params;
    $array_data = $this->lib_api_sanders->params($params);
    $data_api = $this->lib_api_sanders->connection('get', $url, $array_data);
    if (isset($data_api->error)) {
      $this->session->set_flashdata('msgErrorGet', $data_api->error->message);
    }
    return $data_api;
  }


  public function delete($id)
  {
    $url = 'product/' . $id;
    $array_data = $this->lib_api_sanders->params();
    $data_api = $this->lib_api_sanders->connection('delete', $url, $array_data);
    if ($data_api->success) {
      $this->lib_log->create_log('Delete product', $id, 'Delete');
    }
    return $data_api;
  }
}
