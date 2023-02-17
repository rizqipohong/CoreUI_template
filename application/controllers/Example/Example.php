<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Example extends CI_Controller
{
    public function index()
    {
        $data = array(
            '_1' => 'satu',
            '_2' => 'dua',
        );
        $this->lib_template->load('example/dashboard', $data);
    }

    public function blanks()
    {
        $data = array(
            '_1' => 'satu',
            '_2' => 'dua',
        );
        $this->lib_template->load('example/blank', $data);
    }

    public function tables()
    {
        $data = array(
            '_1' => '',
            '_2' => '',
        );
        $this->lib_template->load('example/tables', $data);
    }

    public function datatables()
    {
        $data = array(
            '_1' => '',
            '_2' => '',
        );
        $this->lib_template->load('example/datatables', $data);
    }
}
