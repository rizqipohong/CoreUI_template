<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lib_validation
{
    protected $_ci;

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function phone($phone_number)
    {
        $phone = str_replace(array('-', '_'), '', $phone_number);
        $phone_check = substr($phone, 0, 2);
        if ($phone_check == "08") {
            $number = "62" . substr($phone, 1);
        } else if ($phone_check == "62") {
            $number = $phone;
        } else {
            $number = "62" . substr($phone, 2);
        }

        return $number;
    }

    public function valid_data($data, $table, $field_name)
    {
        $check = $this->_ci->db->query("SELECT $field_name
        FROM " . $table . " WHERE " . $field_name . " = '" . $data . "'")->num_rows();

        if ($check >= 1) {
            $output = false;
        } else {
            $output = true;
        }

        return $output;
    }
}
