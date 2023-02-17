<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_log
{
    protected $_ci;

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function create_log($log_name, $log_data, $log_status)
    {
        $log_id = 'LG' . date('Ymd') . '-';

        $created_by = isset($_SESSION['username']) ? $_SESSION['username'] : null;

        $cekID = $this->_ci->db->query("SELECT max(log_id) as maxID 
        FROM main_log WHERE log_id LIKE '" . $log_id . "%'")->result();

        $noUrut = (int) substr(@$cekID[0]->maxID, 12, 8);
        $noUrut++;
        $newID = $log_id . sprintf("%08s", $noUrut);

        $insert_log = [
            'log_id' => $newID,
            'log_name' => $log_name,
            'log_data' => json_encode($log_data, true),
            'log_status' => $log_status,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->_ci->db->insert('main_log', $insert_log);
    }

    public function create_first_log($log_name, $log_data, $log_status, $created_by)
    {
        $log_id = 'LG' . date('Ymd') . '-';

        $cekID = $this->_ci->db->query("SELECT max(log_id) as maxID 
        FROM main_log WHERE log_id LIKE '" . $log_id . "%'")->result();

        $noUrut = (int) substr(@$cekID[0]->maxID, 12, 8);
        $noUrut++;
        $newID = $log_id . sprintf("%08s", $noUrut);

        $insert_log = [
            'log_id' => $newID,
            'log_name' => $log_name,
            'log_data' => json_encode($log_data, true),
            'log_status' => $log_status,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->_ci->db->insert('main_log', $insert_log);
    }
}
