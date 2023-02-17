<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contoh_model extends CI_Model
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


    // CONTOH API
        public function api_get($id = '')
        {
          $url = $id != '' ? 'example/'.$id : 'example/';
          $array_data = $this->lib_api_sanders->params();
          return $data_api = $this->lib_api_sanders->connection('get', $url, $array_data);
        }

        public function api_post($data)
        {
          $url = 'example';
          $array_data = $this->lib_api_sanders->params($data);
          return $data_api = $this->lib_api_sanders->connection('post', $url, $array_data);
        }

        public function api_put($id, $data)
        {
          $url = 'example/'.$id;
          $array_data = $this->lib_api_sanders->params($data);
          return $data_api = $this->lib_api_sanders->connection('put', $url, $array_data);
        }

        public function api_delete($id)
        {
          $url = 'example/'.$id;
          $array_data = $this->lib_api_sanders->params();
          return $data_api = $this->lib_api_sanders->connection('delete', $url, $array_data);
        }
}
