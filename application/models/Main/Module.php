<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Module extends CI_Model
{
    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find data.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->db->get_where("main_modules", array("id" => $id, "deleted_at" => null))->row(0);
    }

    /**
     * Read all data.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->db->get_where("main_modules", array("deleted_at" => null))->result();
    }

    /**
     * Insert Data.
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->db->insert('main_modules', $data);
    }

    /**
     * Edit data.
     *
     * @param $data
     * @return mixed
     */
    public function edit($data)
    {
        return $this->db->update('main_modules', $data, array('id' => $data['id']));
    }

    /**
     * Delete data.
     *
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        $data['deleted_at'] = date("Y-m-d H:i:s");

        return $this->find($id) ? $this->db->update('main_modules', $data, array('id' => $id)) : 0;
    }

    /**
     * @author Henry
     */
//     ================================== tambahan untuk install di setiap modul ==================================

    public function findWhere($whereArr)
    {
        return $this->db->get_where("main_modules", $whereArr)->row(0);
    }

    public function replace($whereArr, $data)
    {
        $module = $this->findWhere($whereArr);
        if (!$module) {
            $this->add($data);
            return $this->db->insert_id();
        }else{
            return $module->id;
        }
    }
}
