<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_api_model extends CI_Model
{
    private $table = 'api';

    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_api' => $id])->row_array();
    }
    public function get_by_name($name)
    {
        $query = $this->db->select('endpoint')
            ->from($this->table)
            ->where('nama_api', $name)
            ->get();

        if ($query->num_rows() > 0) {
            return $query->row()->endpoint; // kembalikan langsung string endpoint
        }

        return null; // kalau tidak ditemukan
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_api', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id_api', $id);
        return $this->db->delete($this->table);
    }
}
