<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permission
{

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('permission');
    }

    /**
     * Get all permissions from session
     */
    public function get_all_permissions()
    {
        return $this->CI->session->userdata('permissions');
    }

    /**
     * Check multiple permissions
     */
    public function has_permissions($permissions_array)
    {
        foreach ($permissions_array as $permission => $level) {
            if (!has_permission($permission, $level)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Refresh permissions from database (jika perlu update real-time)
     */
    public function refresh_permissions()
    {
        $this->CI->load->model('role_permission_model');
        $role_id = $this->CI->session->userdata('role_id');
        $permissions = $this->CI->role_permission_model->get_permissions_by_role($role_id);
        $this->CI->session->set_userdata('permissions', $permissions);
    }
}