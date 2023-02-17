<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Main_model extends CI_Model
{
    public function get_list_main_menu_all()
    {
        return $this->db->query("SELECT * FROM main_menu a WHERE a.menu_id_parent IS NULL AND a.is_delete = 'No' ORDER BY a.menu_order ASC");
    }

    public function get_list_sub_menu_all($menu_id_parent)
    {
        return $this->db->query("SELECT * FROM main_menu a where a.menu_id_parent ='" . $menu_id_parent . "' AND a.is_delete = 'No' ORDER BY a.menu_order ASC");
    }

    public function get_sidebar_main_menu_active()
    {
        return $this->db->query("SELECT * FROM main_menu a WHERE a.menu_id_parent IS NULL AND a.access_status = 'Activated' AND a.is_delete = 'No' ORDER BY a.menu_order ASC");
    }

    public function get_sidebar_sub_menu_active($menu_id_parent)
    {
        return $this->db->query("SELECT * FROM main_menu a where a.menu_id_parent ='" . $menu_id_parent . "' AND a.access_status = 'Activated' AND a.is_delete = 'No' ORDER BY a.menu_order ASC");
    }

    public function get_main_menu()
    {
        return $this->db->query("SELECT * FROM main_menu a WHERE a.menu_type != 'Header' AND a.access_status = 'Activated' AND a.modules_name != '-' AND a.is_delete = 'No' ORDER BY a.menu_type, a.menu_order ASC");
    }

    public function get_role_data($role_name = null, $access_status = null, $limit = null, $offset = null)
    {
        $this->db->select("a.*, b.menu_name")
            ->from("main_role a");
        $this->db->join("main_menu b", "b.menu_id = a.landing_page", "left");
        $this->db->where("a.is_delete = 'No'");
        $this->db->where("a.role_id != 'RL2022-00000000'");

        if (!empty($role_name)) {
            $this->db->like('a.role_name', $role_name, 'both');
        }
        if (!empty($access_status)) {
            $this->db->where("a.access_status", $access_status);
        }
        if (!empty($limit)) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by("a.role_id", "ASC");

        return $this->db->get();
    }

    public function get_role_data_menu()
    {
        $this->db->select("a.*, b.menu_name")
            ->from("main_role a");
        $this->db->join("main_menu b", "b.menu_id = a.landing_page", "left");
        $this->db->where("a.is_delete = 'No'");
        $this->db->where("a.access_status", "Activated");
        $this->db->order_by("a.role_id", "ASC");

        return $this->db->get();
    }

    public function get_menu_access($_modules = null, $_controller = null, $_function = null)
    {
        $this->db->select("a.*")
            ->from("main_menu a");
        $this->db->where("a.is_delete = 'No'");
        $this->db->where("a.access_status = 'Activated'");

        if (!empty($_modules)) {
            $this->db->where("a.modules_name", $_modules);
        }
        if (!empty($_controller)) {
            $this->db->where("a.controller_name", $_controller);
        }
        if (!empty($_function)) {
            $this->db->where("a.function_name", $_function);
        }
        $this->db->order_by("a.menu_id", "ASC");

        return $this->db->get();
    }

    public function get_menu_sidebar_access($menu_id = null)
    {
        $this->db->select("a.*")
            ->from("main_menu a");
        $this->db->where("a.is_delete = 'No'");
        $this->db->where("a.access_status = 'Activated'");

        if (!empty($menu_id)) {
            $this->db->where("a.menu_id", $menu_id);
        }
        $this->db->order_by("a.menu_id", "ASC");

        return $this->db->get();
    }

    public function get_user_data($user_fullname = null, $access_status = null, $limit = null, $offset = null)
    {
        $this->db->select("a.*, b.role_name")
            ->from("main_user a");
        $this->db->join("main_role b", "b.role_id = a.role_id", "left");
        $this->db->where("a.is_delete = 'No'");
        $this->db->where("a.user_id != 'US2022-00000000'");

        if (!empty($user_fullname)) {
            $this->db->like('a.user_fullname', $user_fullname, 'both');
        }
        if (!empty($access_status)) {
            $this->db->where("a.access_status", $access_status);
        }
        if (!empty($limit)) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by("a.user_id", "ASC");

        return $this->db->get();
    }

    public function get_role_select()
    {
        $this->db->select("a.*")
            ->from("main_role a");
        $this->db->where("a.is_delete = 'No'");
        $this->db->where("a.access_status = 'Activated'");

        $this->db->order_by("a.role_id", "ASC");

        return $this->db->get();
    }

    public function get_log_data($log_name = null, $log_status = null, $limit = null, $offset = null, $group = null)
    {
        $this->db->select("a.*")
            ->from("main_log a");

        if (!empty($log_name)) {
            $this->db->like('a.log_name', $log_name, 'both');
        }
        if (!empty($log_status)) {
            $this->db->where("a.log_status", $log_status);
        }
        if (!empty($limit)) {
            $this->db->limit($limit, $offset);
        }
        if (!empty($group)) {
            $this->db->group_by("a." . $group);
        }
        $this->db->order_by("a.created_at", "DESC");

        return $this->db->get();
    }
}
