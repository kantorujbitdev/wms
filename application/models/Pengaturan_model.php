<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan_model extends CI_Model
{
    private $table = 'pengaturan';

    public function get_all()
    {
        return $this->db->order_by('nama_pengaturan', 'ASC')->get($this->table)->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_pengaturan' => $id])->row_array();
    }
    public function get_by_name($name)
    {
        $query = $this->db->select('value')
            ->from($this->table)
            ->where('nama_pengaturan', $name)
            ->get();

        if ($query->num_rows() > 0) {
            return $query->row()->value;
        }

        return null; // kalau tidak ditemukan
    }

    public function update($id, $data)
    {
        $this->db->where('id_pengaturan', $id);
        return $this->db->update($this->table, $data);
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id_pengaturan', $id);
        return $this->db->delete($this->table);
    }
}
