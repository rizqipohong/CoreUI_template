<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public $db;

	function __construct()
	{
		parent::__construct();
		ini_set('max_execution_time', '-1');
		ini_set('memory_limit', '-1');
		$this->db = $this->load->database('default', true);
	}

	function data($usedb = 'default', $select = '*', $tabel = '', $and_where = array(), $or_where = array(), $and_where_in = array(), $or_where_in = array(), $having = array(), $or_having = array(), $limit = 0, $offset = 0, $order = '', $like = '', $field_like = '')
	{
		$usedb = $this->load->database($usedb, true);

		if ($select != null && $select != '' && $select != '*') {
			$usedb->select($select);
		}

		$usedb->from($tabel);

		if (is_array($and_where)) {
			$usedb->where($and_where);
		}

		if (is_array($or_where)) {
			$usedb->or_where($or_where);
		}

		if (is_array($and_where_in)) {
			if (count($and_where_in) == 2) {
				if (isset($and_where_in[0]) and isset($and_where_in[1])) {
					if (is_string($and_where_in[0]) and is_array($and_where_in[1])) {
						$usedb->where_in($and_where_in[0], $and_where_in[1]);
					}
				}
			}
		}

		if (is_array($or_where_in)) {
			if (count($or_where_in) == 2) {
				if (isset($or_where_in[0]) and isset($or_where_in[1])) {
					if (is_string($or_where_in[0]) and is_array($or_where_in[1])) {
						$usedb->or_where_in($or_where_in[0], $or_where_in[1]);
					}
				}
			}
		}

		if (is_array($having)) {
			$usedb->having($having);
		}

		if (is_array($or_having)) {
			$usedb->or_having($or_having);
		}

		if ($like) {
			if (is_array($field_like)) {
				$usedb->group_start();
				$i = 0;
				foreach ($field_like as $key => $value) {
					if (is_numeric($like)) {
						if ($i == 0) {
							$usedb->like("CAST(" . $value . " as TEXT)", $like);
						} else {
							$usedb->or_like("CAST(" . $value . " as TEXT)", $like);
						}
					} else {
						if ($i == 0) {
							$usedb->like("LOWER(CAST(" . $value . " as TEXT))", strtolower($like));
						} else {
							$usedb->or_like("LOWER(CAST(" . $value . " as TEXT))", strtolower($like));
						}
					}
					$i++;
				}
				$usedb->group_end();
			} else {
				if (is_numeric($like)) {
					$usedb->like("CAST(" . $field_like . " as TEXT)", $like);
				} else {
					$usedb->like("LOWER(CAST(" . $field_like . ") as TEXT)", strtolower($like));
				}
			}
		}

		if ($order) {
			$usedb->order_by($order);
		}

		if ($limit > 0) {
			$usedb->limit($limit, $offset);
		}

		return $usedb;
	}

	function save($data = array(), $table = '', $usedb = 'default')
	{
		$usedb 				= $this->load->database($usedb, true);
		return $usedb->insert($table, $data);
	}

	function save_batch($data, $table = '', $usedb = 'default')
	{
		$usedb 				= $this->load->database($usedb, true);
		return $usedb->insert_batch($table, $data);
	}

	function update($set = array(), $where = array(), $tabel = '', $usedb = 'default')
	{
		$usedb 				= $this->load->database($usedb, true);
		$update 			= $usedb->set($set);
		$update 			= $usedb->where($where);
		$update 			= $usedb->update($tabel);
		return $update;
	}

	function soft_delete($set = array(), $where = array(), $tabel = '', $usedb = 'default')
	{
		$usedb 				= $this->load->database($usedb, true);
		$update 			= $usedb->set($set);
		$update 			= $usedb->where($where);
		$update 			= $usedb->update($tabel);
		return $update;
	}

	function delete($where = array(), $tabel = '', $usedb = 'default')
	{
		$usedb 				= $this->load->database($usedb, true);
		$hasil 				= FALSE;
		if (!empty($where) and $tabel != NULL) {
			$hasil 			= $usedb->where($where);
			$hasil 			= $usedb->delete($tabel);
		}
		return $hasil;
	}

	function reset($tabel = '', $usedb = 'default')
	{
		$usedb 				= $this->load->database($usedb, true);
		return $usedb->truncate($tabel);
	}

	public function check_field($table = '', $usedb = 'default')
	{
		$usedb 				= $this->load->database($usedb, true);
		$all_field = $usedb->query('DESCRIBE '.$table)->result_array();
		$data = [];
		if (!empty($all_field)) {
			foreach ($all_field as $key => $value) {
				array_push($data, $value['Field']);
			}
		}
		return $data;
	}

}
