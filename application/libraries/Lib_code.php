<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_code
{
    protected $_ci;

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function create_id($id, $table, $field_name)
    {
        $main_id = $id . date('Y') . '-';

        $cekID = $this->_ci->db->query("SELECT max(" . $field_name . ") as maxID 
        FROM " . $table . " WHERE " . $field_name . " LIKE '" . $main_id . "%'")->result();

        $noUrut = (int) substr(@$cekID[0]->maxID, 8, 8);
        $noUrut++;
        $newID = $main_id . sprintf("%08s", $noUrut);

        return $newID;
    }
}
