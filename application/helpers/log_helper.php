<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Custom simple log untuk CodeIgniter 3
 * Menyimpan log dengan format rapi dalam satu level (general purpose)
 */
if (!function_exists('save_log')) {
    /**
     * Menyimpan log aplikasi ke file dengan format rapi dan ikon indikator
     * 
     * @param string $message Pesan log
     * @param string $type Jenis log (info, success, warning, error)
     */
    function save_log($message, $type = 'info')
    {
        $CI =& get_instance();

        // Siapkan direktori log
        $logDir = APPPATH . 'logs/data_log/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        // Pilih ikon berdasarkan jenis log
        $icons = [
            'info' => 'ℹ️',
            'success' => '✅',
            'warning' => '⚠️',
            'error' => '❌'
        ];

        $icon = isset($icons[$type]) ? $icons[$type] : '🔹';

        // Waktu log
        $timestamp = date('Y-m-d H:i:s');

        // Nama file log per tanggal
        $filename = $logDir . 'app-log-' . date('Y-m-d') . '.log';

        // Format log
        $logMessage = "[{$timestamp}] {$icon} [{$type}] {$message}" . PHP_EOL;

        // Simpan ke file
        file_put_contents($filename, $logMessage, FILE_APPEND);
    }
}

/**
 * Helper tambahan untuk logging hasil HTTP API
 */
if (!function_exists('log_http_response')) {
    /**
     * Log respons dari API dengan level otomatis berdasarkan kode HTTP
     *
     * @param int $http_code
     * @param string $response
     * @param string|null $prefix Pesan tambahan opsional (misal nama API)
     */
    function log_http_response($url, $method, $payload, $response, $http_code, $prefix = null)
    {
        $prefixText = !empty($prefix) ? "[{$prefix}] " : '';
        $shortResponse = substr($response, 0, 500); // batasi isi log agar tidak terlalu panjang

        // if (ENVIRONMENT == 'development') {
        //     $pesan = "[RESPONSE]: " . $response;
        // } else {
        if ($method == 'GET') {
            $pesan = "[RESPONSE]: " . $shortResponse;
        } else {
            $pesan = "[RESPONSE]: " . $response;
        }
        // }

        $icon = '❌';
        $level = 'error';

        if ($http_code >= 200 && $http_code < 300) {
            $icon = '✅';
            $level = 'success';
        } elseif ($http_code >= 300 && $http_code < 500) {
            $icon = '⚠️';
            $level = 'warning';
        }

        save_log(
            "{$icon} {$prefixText}[{$method}:{$http_code}] {$url} | {$payload}",
            $level
        );

        save_log(
            "{$icon} {$pesan}",
            $level
        );
    }
}
