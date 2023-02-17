<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class APP_Model_V1 extends CI_Model {

    function __construct()
	{
		parent::__construct();
		ini_set('max_execution_time', '-1');
		ini_set('memory_limit', '-1');
		$this->db = $this->load->database('v1', true);
	}
}

?>