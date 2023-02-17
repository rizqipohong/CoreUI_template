<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Migration_create_table_main_user_setting extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'setting_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 15,
                'Unsigned' => TRUE,
            ),
            'user_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 15,
                'Unsigned' => TRUE,
            ),
            'setting_data' => array(
                'type' => 'LONGTEXT',
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('setting_id', true);
        $this->dbforge->create_table('main_user_setting');
    }

    public function down()
    {
        $this->dbforge->drop_table('main_user_setting');
    }
}
