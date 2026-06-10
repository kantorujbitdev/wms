<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('has_permission')) {
    /**
     * Check if user has permission for specific menu and action
     * @param string $menu_key Menu key identifier
     * @param string $action Action type: view, edit, delete
     * @return bool
     */
    function has_permission($menu_key, $action = 'view')
    {
        $CI =& get_instance();

        // Jika belum login, return false
        if (!$CI->session->userdata('logged_in')) {
            return false;
        }

        // Superadmin selalu punya akses penuh (opsional)
        if ($CI->session->userdata('role_name') === 'superadmin') {
            return true;
        }

        $permissions = $CI->session->userdata('permissions');

        // Debug: Tampilkan permissions yang ada di session
        // log_message('debug', 'Checking permission for menu: ' . $menu_key . ', action: ' . $action);
        // log_message('debug', 'Available permissions: ' . json_encode($permissions));

        // Jika menu tidak ada di permissions, return false
        if (!isset($permissions[$menu_key])) {
            // Debug: log jika menu tidak ditemukan
            // log_message('debug', 'Menu ' . $menu_key . ' not found in permissions');
            return false;
        }

        $menu_perms = $permissions[$menu_key];

        // Logika: untuk edit/delete harus punya view dulu
        switch ($action) {
            case 'view':
                return $menu_perms['view'] == true || $menu_perms['view'] == 1;
            case 'edit':
                return ($menu_perms['view'] == true || $menu_perms['view'] == 1) &&
                    ($menu_perms['edit'] == true || $menu_perms['edit'] == 1);
            case 'delete':
                return ($menu_perms['view'] == true || $menu_perms['view'] == 1) &&
                    ($menu_perms['delete'] == true || $menu_perms['delete'] == 1);
            default:
                return false;
        }
    }
}

if (!function_exists('load_menu')) {
    /**
     * Check if user can access a menu (including parent check for submenus)
     */
    function load_menu()
    {
        $CI =& get_instance();
        $CI->load->model('role_permission_model');
        $permissions = $CI->role_permission_model->get_permissions_by_role_name($CI->session->userdata('role'));
        $CI->session->set_userdata('permissions', $permissions);
    }
}

if (!function_exists('can_access_menu')) {
    /**
     * Check if user can access a menu (including parent check for submenus)
     */
    function can_access_menu($menu_key)
    {
        $CI =& get_instance();

        // Superadmin selalu bisa akses
        if ($CI->session->userdata('role_name') === 'superadmin') {
            return true;
        }

        // Untuk menu utama, cek langsung
        if (has_permission($menu_key, 'view')) {
            return true;
        }

        // Untuk submenu, cek parent menu
        $menu_parents = array(
            'gudang_project' => 'gudang',
            'gudang_utama' => 'gudang',
            'tipe_produk' => 'barang',
            'tipe_satuan' => 'barang',
            'produk' => 'barang',
            'penerimaan_antar_gudang' => 'penerimaan',
            'supplier_penerimaan' => 'penerimaan',
            'pengguna_penerimaan' => 'penerimaan',
            'pengiriman_antar_gudang' => 'pengiriman',
            'penggunaan' => 'pengiriman',
            'web_pengaturan' => 'pengaturan',
            'api_pengaturan' => 'pengaturan'
        );

        // Jika ini submenu, cek parentnya
        if (isset($menu_parents[$menu_key])) {
            return has_permission($menu_parents[$menu_key], 'view');
        }

        return false;
    }
}

if (!function_exists('get_menu_icon')) {
    /**
     * Get icon for menu
     */
    function get_menu_icon($menu_key)
    {
        $icons = array(
            'dashboard' => 'fas fa-water',
            'gudang' => 'fas fa-warehouse',
            'gudang_project' => 'fas fa-box',
            'gudang_utama' => 'fas fa-layer-group',
            'barang' => 'fas fa-boxes',
            'tipe_produk' => 'fas fa-tags',
            'tipe_satuan' => 'fas fa-balance-scale',
            'produk' => 'fas fa-box',
            'gudang_stok' => 'fas fa-chart-bar',
            'penerimaan' => 'fas fa-inbox',
            'penerimaan_antar_gudang' => 'fas fa-right-left',
            'supplier_penerimaan' => 'fas fa-truck-ramp-box',
            'pengguna_penerimaan' => 'fas fa-users',
            'pengiriman' => 'fas fa-paper-plane',
            'pengiriman_antar_gudang' => 'fas fa-right-left',
            'penggunaan' => 'fas fa-users',
            'laporan' => 'fas fa-chart-bar',
            'laporan_stok' => 'fas fa-boxes',
            'laporan_stok_card' => 'fas fa-clipboard-list',
            'laporan_keluar' => 'fas fa-arrow-up-right-from-square',
            'laporan_masuk' => 'fas fa-inbox',
            'laporan_transaksi' => 'fas fa-receipt',
            'customer' => 'fas fa-users',
            'supplier' => 'fas fa-truck',
            'user' => 'fas fa-users-cog',
            'pengaturan' => 'fas fa-cog',
            'web_pengaturan' => 'fas fa-globe',
            'barang_proses' => 'fas fa-spinner',
            'api_pengaturan' => 'fas fa-plug',
            'logo_pengaturan' => 'fas fa-image'
        );

        return isset($icons[$menu_key]) ? $icons[$menu_key] : 'fas fa-circle';
    }
}

if (!function_exists('get_menu_permissions')) {
    /**
     * Get all permissions for a specific menu
     * @param string $menu_key Menu key identifier
     * @return array
     */
    function get_menu_permissions($menu_key)
    {
        $CI =& get_instance();
        $permissions = $CI->session->userdata('permissions');

        return isset($permissions[$menu_key]) ? $permissions[$menu_key] : array(
            'view' => false,
            'edit' => false,
            'delete' => false
        );
    }
}