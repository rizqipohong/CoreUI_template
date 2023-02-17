<?php

use LDAP\Result;

defined('BASEPATH') or exit('No direct script access allowed');

class Lib_opLogActivity
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->default_db = $this->set_db();
    }

    public function add_log_activity_insert($log_name, $log_type, $log_field_id, $log_table_id, $log_table_name, $new_data, $query_action, $task_id, $is_update = false)
    {
        $current_user_id = isset($_SESSION['sgn_user_id']) ? $_SESSION['sgn_user_id'] : null;
        $log = array(
            'log_name' => $log_name,
            'log_type' => $log_type,
            'log_field_id' => $log_field_id,
            'log_table_id' => $log_table_id,
            'log_table_name' => $log_table_name,
            'log_database_name' => $this->CI->db->database,
            'log_details_before' => null,
            'log_details_after' => is_null($new_data) ? null : json_encode($new_data),
            'log_query_action' =>  $query_action,
            'log_task_id' =>  $task_id,
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $current_user_id,
        );

        $result = $this->default_db->insert('operational_log_activity', $log);
        if ($is_update && $log_table_id == null) {
            $new_log_id = $this->default_db->insert_id();
            $new_id = $this->default_db->select($log_field_id)->order_by($log_field_id, 'DESC')->get($log_table_name, 1)->result();
            $array = (array)$new_id[0];
            $log_update = array(
                'log_table_id' => $array[$log_field_id]
            );
            $this->default_db->where('log_id', $new_log_id)->update('operational_log_activity', $log_update);
        }
        return $result;
    }

    public function add_log_activity_before_update($log_name, $log_type, $log_field_id, $log_table_id, $log_table_name, $query_action, $task_id)
    {
        $log_details_before = $this->CI->db->where($log_field_id, $log_table_id)->get($log_table_name)->row();
        $current_user_id = isset($_SESSION['sgn_user_id']) ? $_SESSION['sgn_user_id'] : null;
        $log = array(
            'log_name' => $log_name,
            'log_type' => $log_type,
            'log_field_id' => $log_field_id,
            'log_table_id' => $log_table_id,
            'log_table_name' => $log_table_name,
            'log_database_name' => $this->CI->db->database,
            'log_details_before' => json_encode($log_details_before),
            'log_details_after' => null,
            'log_query_action' =>  $query_action,
            'log_task_id' =>  $task_id,
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $current_user_id,
        );

        $this->default_db->insert('operational_log_activity', $log);
        return $this->default_db->insert_id();
    }

    public function add_log_activity_after_update($log_id, $log_details_after, $row_id = false)
    {
        $log_details = $this->default_db->where('log_id', $log_id)->get('operational_log_activity')->row();
        $data_before = json_decode($log_details->log_details_before);
        if($row_id !== false) {
            $old_value_data = '';
            foreach ($row_id->update as $key => $value) {
                $old_value_data .= $old_value_data !== '' ? "$key: ".$data_before->$key : "\n$key: ".$data_before->$key;
            }
        }
        $log_update = array(
            'log_details_before' => $row_id !== false ? "{\n...\n$old_value_data\n...\n}" : json_encode($log_details),
            'log_details_after' => $log_details_after
        );
        return $this->default_db->where('log_id', $log_id)->update('operational_log_activity', $log_update);
    }

    private function set_db()
    {
        if (
            !file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/database.php')
            && !file_exists($file_path = APPPATH . 'config/database.php')
        ) {
            show_error('The configuration file database.php does not exist.');
        }

        include($file_path);

        if (class_exists('CI_Controller', FALSE)) {
            foreach (get_instance()->load->get_package_paths() as $path) {
                if ($path !== APPPATH) {
                    if (file_exists($file_path = $path . 'config/' . ENVIRONMENT . '/database.php')) {
                        include($file_path);
                    } elseif (file_exists($file_path = $path . 'config/database.php')) {
                        include($file_path);
                    }
                }
            }
        }

        $final_db = [];
        foreach ($db as $key => $value) {
            $final_db[] = $key;
        }

        return $this->CI->load->database($final_db[0], true);
    }
}
