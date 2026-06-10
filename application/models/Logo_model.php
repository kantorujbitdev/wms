<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logo_model extends CI_Model
{
    private $table = 'logo_management';

    public function get_all()
    {
        return $this->db
            ->get($this->table)
            ->result_array();
    }

    public function get_active()
    {
        return $this->db
            ->where('status_aktif', 1)
            ->get($this->table)
            ->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id_logo', $id)
            ->get($this->table)
            ->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id_logo', $id)
            ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id_logo', $id)
            ->delete($this->table);
    }

}