<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * =====================================================
 *  Helper: api_helper
 *  Fungsionalitas sama seperti Api_model, tapi versi helper
 * =====================================================
 */

/**
 * Ambil URL endpoint lengkap berdasarkan nama endpoint
 */
if (!function_exists('getEndpoint')) {
    function getEndpoint($name)
    {
        $CI = &get_instance();

        $config = get_app_config();
        $baseUrl = rtrim($config['api_base_url'] ?? '', '/');

        $apiList = get_api_list();

        // 🔽 Normalisasi semua key menjadi lowercase
        $apiListLower = [];
        foreach ($apiList as $k => $v) {
            $apiListLower[strtolower($k)] = $v;
        }

        $key = strtolower(trim($name));

        if (!empty($apiListLower) && isset($apiListLower[$key]['endpoint'])) {
            $endpoint = $apiListLower[$key]['endpoint'];
            return $baseUrl . '/' . ltrim($endpoint, '/');
        }

        save_log("⚠️ Endpoint '{$name}' tidak ditemukan di daftar API.", 'warning');
        return null;
    }
}


/**
 * Fungsi utama untuk request API eksternal
 */
if (!function_exists('api_request')) {
    function api_request($method, $endpoint, $data = [], $params = [])
    {
        $CI = &get_instance();

        $url = getEndpoint($endpoint);
        if (empty($url)) {
            return response_error("Endpoint '{$endpoint}' tidak ditemukan.", 404);
        }

        // Query params
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        // Ambil timeout dari config
        $config = get_app_config();
        $timeout = (int) ($config['api_timeout'] ?? 30);

        // Header
        $token = $CI->session->userdata('api_token');
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        if ($token && strtolower($endpoint) !== 'login') {
            $headers[] = "Authorization: Bearer {$token}";
        }

        $method = strtoupper($method);
        $payload = (!empty($data) && in_array($method, ['POST', 'PUT', 'GET', 'DELETE'])) ? json_encode($data) : null;

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers
        ];

        if ($payload) {
            $options[CURLOPT_POSTFIELDS] = $payload;
        }

        // Eksekusi CURL
        $curl = curl_init();
        curl_setopt_array($curl, $options);

        save_log("🔗 Request {$method} → {$url}", 'info');
        if (!empty($data))
            save_log("Payload: " . json_encode($data), 'info');

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        $errno = curl_errno($curl);
        curl_close($curl);

        if ($error) {
            $msg = "cURL Error #{$errno}: {$error}";
            save_log($msg, 'error');
            return response_error($msg, 0);
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $msg = 'JSON Decode Error: ' . json_last_error_msg();
            save_log($msg, 'error');
            return response_error($msg, $http_code, $response);
        }

        $result['http_code'] = $http_code;
        log_http_response($http_code, $response, strtoupper($endpoint));

        // Token expired
        if ($http_code === 401 && strtolower($endpoint) !== 'login') {
            save_log('Token expired, destroying session', 'warning');
            $CI->session->sess_destroy();
            redirect('auth');
        }

        return $result;
    }
}

/**
 * Helper standar untuk response error
 */
if (!function_exists('response_error')) {
    function response_error($message, $httpCode = 0, $raw = '')
    {
        return [
            'success' => false,
            'message' => $message,
            'http_code' => $httpCode,
            'raw_response' => $raw
        ];
    }
}
