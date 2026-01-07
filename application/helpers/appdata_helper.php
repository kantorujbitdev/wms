<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Helper untuk mengelola data aplikasi seperti pengaturan dan API
 */
if (!function_exists('data_login_user')) {
    function data_login_user($extra = [])
    {
        $CI = &get_instance();

        $data = [
            'login_id' => $CI->session->userdata('user_id'),
            'login_name' => $CI->session->userdata('username'),
        ];

        // Gabungkan tambahan parameter jika ada
        return array_merge($data, $extra);
    }
}
if (!function_exists('viewNumber')) {
    function viewNumber($nilai)
    {
        // 1. Beri format ribuan dengan titik (.) dan desimal dengan koma (,)
        // Kita set 2 desimal sebagai standar awal
        $formatted = number_format($nilai, 2, ',', '.');

        // 2. Hapus angka '0' di paling kanan desimal
        $formatted = rtrim($formatted, '0');

        // 3. Jika setelah dihapus ternyata karakter terakhirnya adalah koma (misal "1.000,"), hapus komanya
        return rtrim($formatted, ',');
    }
}

if (!function_exists('monthToRoman')) {
    function monthToRoman($month)
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $romawi[intval($month)];
    }
}

if (!function_exists('is_role')) {

    function is_role($roles = [])
    {
        $CI = &get_instance();
        $user_role = $CI->session->userdata('role');

        // Jika $roles adalah string → ubah jadi array
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        return in_array(strtolower($user_role), array_map('strtolower', $roles));
    }
}

if (!function_exists('load_appdata_to_session')) {
    /**
     * Muat data pengaturan dan API dari database ke session.
     * Dipanggil saat login agar siap dipakai di seluruh aplikasi.
     */
    function load_appdata_to_session()
    {
        $CI =& get_instance();
        $CI->load->database();
        $CI->load->library('session');

        // --- Load pengaturan ---
        $pengaturan = $CI->db->get('pengaturan')->result_array();
        save_log('Load pengaturan: ' . json_encode($pengaturan));
        $config = [];
        foreach ($pengaturan as $row) {
            $config[$row['nama_pengaturan']] = $row['value'];
        }
        $CI->session->set_userdata('app_config', $config);

        // --- Load API ---
        $api = $CI->db->where('status_aktif', 1)->get('api')->result_array();
        $apis = [];
        foreach ($api as $row) {
            $apis[$row['nama_api']] = [
                'endpoint' => $row['endpoint'],
                'status_aktif' => $row['status_aktif']
            ];
        }

        $CI->session->set_userdata('api_list', $apis);
        save_log('Load data enpoint API: ' . json_encode($apis));

    }
}

/**
 * Helper untuk cache data konfigurasi dan API ke dalam session.
 * Mengurangi query berulang ke database.
 */

if (!function_exists('get_app_config')) {
    /**
     * Ambil semua data dari tabel pengaturan
     * atau ambil berdasarkan nama_pengaturan
     */
    function get_app_config($key = null)
    {
        $CI =& get_instance();
        $CI->load->library('session');

        // Cek apakah data pengaturan sudah ada di session
        $config = $CI->session->userdata('app_config');

        if (!$config) {
            // Jika belum, ambil dari database
            $CI->load->database();
            $query = $CI->db->get('pengaturan')->result_array();

            $config = [];
            foreach ($query as $row) {
                $config[$row['nama_pengaturan']] = $row['value'];
            }

            // Simpan ke session
            $CI->session->set_userdata('app_config', $config);
        }

        // Kalau $key dikasih, ambil satu value aja
        return $key ? ($config[$key] ?? null) : $config;
    }
}

if (!function_exists('get_api_list')) {
    /**
     * Ambil semua endpoint API yang aktif
     */
    function get_api_list($name = null)
    {
        $CI =& get_instance();
        $CI->load->library('session');

        $apis = $CI->session->userdata('api_list');

        if (!$apis) {
            // Ambil dari database
            $CI->load->database();
            $query = $CI->db
                ->where('status_aktif', 1)
                ->get('api')
                ->result_array();

            $apis = [];
            foreach ($query as $row) {
                $apis[$row['nama_api']] = [
                    'endpoint' => $row['endpoint'],
                    'status_aktif' => $row['status_aktif']
                ];
            }

            // Simpan ke session
            $CI->session->set_userdata('api_list', $apis);
        }

        // Kalau mau ambil satu nama API saja
        return $name ? ($apis[$name]['endpoint'] ?? null) : $apis;
    }
}

if (!function_exists('clear_app_cache')) {
    /**
     * Hapus cache dari session (misal setelah update pengaturan/API)
     */
    function clear_app_cache()
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->unset_userdata('app_config');
        $CI->session->unset_userdata('api_list');
    }
}
