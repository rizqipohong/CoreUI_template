<?php

/**
 * Author: Firoz Ahmad Likhon <likh.deshi@gmail.com>
 * Website: https://github.com/firoz-ahmad-likhon
 *
 * Copyright (c) 2018 Firoz Ahmad Likhon
 * Released under the MIT license
 *       ___            ___  ___    __    ___      ___  ___________  ___      ___
 *      /  /           /  / /  /  _/ /   /  /     /  / / _______  / /   \    /  /
 *     /  /           /  / /  /_ / /    /  /_____/  / / /      / / /     \  /  /
 *    /  /           /  / /   __|      /   _____   / / /      / / /  / \  \/  /
 *   /  /_ _ _ _ _  /  / /  /   \ \   /  /     /  / / /______/ / /  /   \    /
 *  /____________/ /__/ /__/     \_\ /__/     /__/ /__________/ /__/     /__/
 * Likhon the hackman, who claims himself as a hacker but really he isn't.
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Permission extends CI_Model
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
        return $this->db->get_where("main_permissions", array("id" => $id, "deleted_at" => null))->row(0);
    }

    /**
     * Read all data.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->db->get_where("main_permissions", array("deleted_at" => null))->result();
    }

    /**
     * Insert Data.
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->db->insert('main_permissions', $data);
    }

    /**
     * Edit data.
     *
     * @param $data
     * @return mixed
     */
    public function edit($data)
    {
        return $this->db->update('main_permissions', $data, array('id' => $data['id']));
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

        return $this->find($id) ? $this->db->update('main_permissions', $data, array('id' => $id)) : 0;
    }


    /**
     * @author Henry
     */
//     ================================== tambahan untuk install di setiap modul ==================================

    public function findWhere($whereArr)
    {
        return $this->db->get_where("main_permissions", $whereArr)->row(0);
    }

    public function replace($whereArr, $data)
    {
        $permission = $this->findWhere($whereArr);
        if (!$permission) {
            $this->add($data);
            return $this->db->insert_id();
        }else{
            return $permission->id;
        }
    }
}
