<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Migration_create_table_main_user extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 15,
                'Unsigned' => TRUE,
            ),
            'role_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 15,
                'Unsigned' => TRUE,
            ),
            'user_fullname' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'user_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
            ),
            'user_password' => array(
                'type' => 'TEXT',
            ),
            'user_email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'user_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 15,
            ),
            'user_photo' => array(
                'type' => 'text',
                'null' => TRUE,
            ),
            'last_signin' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'last_signout' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'last_password_update' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'otp_status' => array(
                'type' => 'ENUM',
                'constraint' => array('Activated', 'Deactivated'),
                'default' => "Deactivated",
            ),
            'otp_code' => array(
                'type' => 'INT',
                'constraint' => 6,
                'null' => TRUE,
            ),
            'otp_timer' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'otp_fail' => array(
                'type' => 'INT',
                'constraint' => 1,
                'default' => 0,
            ),
            'cookie_user' => array(
                'type' => 'text',
                'null' => TRUE,
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
        $this->dbforge->add_key('user_id', true);
        $this->dbforge->create_table('main_user');
    }

    public function down()
    {
        $this->dbforge->drop_table('main_user');
    }
}
