<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Custom simple log untuk CodeIgniter 3
 * Menyimpan log dengan format rapi dalam satu level (general purpose)
 */

if (!function_exists('save_log')) {
    /**
     * @param string $message Pesan log
     * @param array|null $context Data tambahan opsional (misalnya array data)
     */
    function save_log($message, $context = null)
    {
        $CI =& get_instance();
        $log_path = APPPATH . 'logs/data_log/';

        // Buat folder log kalau belum ada
        if (!is_dir($log_path)) {
            mkdir($log_path, 0755, true);
        }

        // Nama file log per hari
        $filename = $log_path . 'log-' . date('Y-m-d') . '.log';

        // Format waktu
        $timestamp = date('Y-m-d H:i:s');

        // Format isi log
        $log_entry = "[$timestamp] $message";

        if (!empty($context)) {
            $log_entry .= ' | DATA: ' . json_encode($context, JSON_PRETTY_PRINT);
        }

        $log_entry .= PHP_EOL;

        // Simpan ke file log
        file_put_contents($filename, $log_entry, FILE_APPEND);
    }
}
