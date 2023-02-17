<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Migration_create_table_main_menu extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'menu_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'menu_id_parent' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'menu_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'menu_type' => array(
                'type' => 'ENUM',
                'constraint' => array('Header', 'Main Menu', 'Sub Menu'),
                'default' => "Main Menu",
            ),
            'menu_order' => array(
                'type' => 'INT',
                'constraint' => 3,
                'null' => TRUE,
            ),
            'modules_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => "-",
            ),
            'controller_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => "-",
            ),
            'function_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 150,
                'default' => "-",
            ),
            'nav_icon' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => "far fa-circle",
            ),
            'role_id' => array(
                'type' => 'LONGTEXT',
                'null' => TRUE,
            ),
            'access_status' => array(
                'type' => 'ENUM',
                'constraint' => array('Activated', 'Deactivated'),
                'default' => "Deactivated",
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
            'is_base_menu' => array(
                'type' => 'ENUM',
                'constraint' => array('Yes', 'No'),
                'default' => "No",
            ),
        ));
        $this->dbforge->add_key('menu_id', true);
        $this->dbforge->create_table('main_menu');
    }

    public function down()
    {
        $this->dbforge->drop_table('main_menu');
    }
}
