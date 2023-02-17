<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Init_token extends CI_Controller
{

    public function index()
    {
        $this->load->database('default', TRUE);
        if (!$this->db->table_exists('tb_token_key')) {
            $tableQuery = "CREATE TABLE `tb_token_key` (
              `token` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
            $proses = $this->db->query($tableQuery);
            if (!$proses) {
              echo 'Gagal menambahkan tabel tb_token_key';
            }
            echo 'Berhasil menambahkan tabel tb_token_key';
        }else{
          echo 'Tabel tb_token_key sudah ada.';
        }
    }
}
