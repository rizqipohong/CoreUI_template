<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Migration_create_table_main_log extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'log_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 19,
                'Unsigned' => TRUE,
            ),
            'log_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
            ),
            'log_data' => array(
                'type' => 'LONGTEXT',
                'null' => TRUE,
            ),
            'log_status' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
            ),
            'created_by' => array(
                'type' => 'VARCHAR',
                'constraint' => 15,
                'Unsigned' => TRUE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('log_id', true);
        $this->dbforge->create_table('main_log');
    }

    public function down()
    {
        $this->dbforge->drop_table('main_log');
    }
}
