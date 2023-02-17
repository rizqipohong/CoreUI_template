<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_pagination
{
    protected $_ci;
    public $total = 0;
    public $perPage = 10;
    public $url = '';
    public $uriSegment = 0;

    function __construct()
    {
        $this->_ci = &get_instance();
        $this->_ci->load->library('pagination');
    }

    public function pagination($base_url, $limit, $data_count)
    {
        $config['base_url'] = $base_url;
        $config['total_rows'] = $data_count;
        $config['per_page'] = $limit;
        foreach ($this->_ci->config->item('_pageConfig') as $key => $val) {
            $config[$key] = $val;
        }
        $this->_ci->pagination->initialize($config);
        return $data['pagination'] = $this->_ci->pagination->create_links();
    }

    public function limit()
    {
        // main setting
        $user_id = isset($_SESSION['sgn_user_id']) ? $_SESSION['sgn_user_id'] : null;

        if (!empty($user_id)) {
            $where = ['user_id' => $user_id];
            $main_user_setting = $this->_ci->db->get_where('main_user_setting', $where)->result();
            $main_user_setting = json_decode($main_user_setting[0]->setting_data);
            $limit = $main_user_setting->pagination_limit;
        } else {
            $limit = 10;
        }

        return $limit;
    }

    public function getPagination()
    {
        $config['base_url'] = $this->url;
        $config['uri_segment'] = $this->uriSegment;
        $config['total_rows'] = $this->total;
        $config['per_page'] = $this->perPage;
        $config['num_links'] = 3;
        foreach ($this->_ci->config->item('_pageConfig') as $key => $val) {
            if ($key == 'page_query_string' || $key == 'num_links') {
                continue;
            }
            $config[$key] = $val;
        }
        $this->_ci->pagination->initialize($config);
        return $this->_ci->pagination->create_links();
    }
}
