<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Migration_create_table_main_role extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'role_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 15,
                'Unsigned' => TRUE,
            ),
            'role_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'role_read' => array(
                'type' => 'ENUM',
                'constraint' => array('Yes', 'No'),
                'default' => "No",
            ),
            'role_write' => array(
                'type' => 'ENUM',
                'constraint' => array('Yes', 'No'),
                'default' => "No",
            ),
            'role_delete' => array(
                'type' => 'ENUM',
                'constraint' => array('Yes', 'No'),
                'default' => "No",
            ),
            'role_export' => array(
                'type' => 'ENUM',
                'constraint' => array('Yes', 'No'),
                'default' => "No",
            ),
            'landing_page' => array(
                'type' => 'VARCHAR',
                'constraint' => 15,
                'default' => 1,
            ),
            'access_status' => array(
                'type' => 'ENUM',
                'constraint' => array('Activated', 'Deactivated'),
                'default' => "Deactivated",
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'deleted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'is_delete' => array(
                'type' => 'ENUM',
                'constraint' => array('Yes', 'No'),
                'default' => "No",
            ),
        ));
        $this->dbforge->add_key('role_id', true);
        $this->dbforge->create_table('main_role');
    }

    public function down()
    {
        $this->dbforge->drop_table('main_role');
    }
}
