<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role_permission_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all permissions for a specific role (sesuai dengan struktur tabel baru)
     */
    public function get_permissions_by_role_name($role_name)
    {
        $this->db->select('menu_key, can_view, can_edit, can_delete');
        $this->db->from('role_permissions');
        $this->db->where('role_name', $role_name);
        $query = $this->db->get();

        $permissions = array();
        foreach ($query->result() as $row) {
            $permissions[$row->menu_key] = array(
                'view' => (bool) $row->can_view,
                'edit' => (bool) $row->can_edit,
                'delete' => (bool) $row->can_delete
            );
        }

        return $permissions;
    }

    /**
     * Get all menu keys available
     */
    public function get_all_menu_keys()
    {
        $this->db->select('DISTINCT(menu_key)');
        $this->db->from('role_permissions');
        $query = $this->db->get();

        $menus = array();
        foreach ($query->result() as $row) {
            $menus[] = $row->menu_key;
        }

        return $menus;
    }
}