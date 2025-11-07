<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{
    private $baseUrl;
    private $timeout;
    private $endpoints = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

        $config = get_app_config();
        $this->baseUrl = $config['api_base_url'] ?? '';
        $this->timeout = (int) ($config['api_timeout'] ?? 30);

        $apiList = get_api_list();
        if (!empty($apiList)) {
            foreach ($apiList as $key => $row) {
                $this->endpoints[strtolower($key)] = $row['endpoint'];
            }
        }
        save_log("Api_model initialized | Base URL: {$this->baseUrl} | Endpoints loaded: " . count($this->endpoints), 'info');
    }

    /**
     * Mendapatkan URL lengkap dari endpoint.
     */
    private function getEndpoint($name)
    {
        $key = strtolower(trim($name));
        if (isset($this->endpoints[$key])) {
            return rtrim($this->baseUrl, '/') . '/' . ltrim($this->endpoints[$key], '/');
        }

        save_log("⚠️ Endpoint '{$name}' tidak ditemukan dalam daftar API.", 'warning');
        return null;
    }

    /**
     * Request umum ke API eksternal
     */
    public function request($method, $endpoint, $data = [], $params = [])
    {
        $url = $this->getEndpoint($endpoint);
        if (empty($url)) {
            return $this->responseError("Endpoint '{$endpoint}' tidak ditemukan.", 404);
        }

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $token = $this->session->userdata('api_token');
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
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers
        ];

        if ($payload) {
            $options[CURLOPT_POSTFIELDS] = $payload;
        }

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
            return $this->responseError($msg, 0);
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $msg = 'JSON Decode Error: ' . json_last_error_msg();
            save_log($msg, 'error');
            return $this->responseError($msg, $http_code, $response);
        }

        $result['http_code'] = $http_code;
        log_http_response($http_code, $response, strtoupper($endpoint));

        // Handle token expired
        if ($http_code === 401 && strtolower($endpoint) !== 'login') {
            save_log('Token expired, destroying session', 'warning');
            $this->session->sess_destroy();
            redirect('auth');
        }

        return $result;
    }

    /**
     * Fungsi pembantu untuk membuat response error terstandarisasi
     */
    private function responseError($message, $httpCode = 0, $raw = '')
    {
        return [
            'success' => false,
            'message' => $message,
            'http_code' => $httpCode,
            'raw_response' => $raw
        ];
    }

    // ---------------------------------------------------------------------
    // 🔹 Bagian berikut hanya pembungkus endpoint (lebih ringkas & DRY)
    // ---------------------------------------------------------------------

    public function login($username, $password)
    {
        return $this->request('POST', 'login', [
            'Username' => $username,
            'Password' => $password
        ]);
    }

    // ---- Barang ----
    public function get_barang($params = [])
    {
        return $this->request('GET', 'barang', [], $params);
    }
    public function add_barang($data)
    {
        return $this->request('POST', 'barang', $data);
    }
    public function update_barang($id, $data)
    {
        return $this->request('PUT', 'barang', $data, ['id' => $id]);
    }
    public function delete_barang($id)
    {
        return $this->request('DELETE', 'barang', [], ['id' => $id]);
    }

    // ---- Gudang ----
    public function get_gudang($params = [])
    {
        return $this->request('GET', 'gudang', [], $params);
    }
    public function add_gudang($data)
    {
        return $this->request('POST', 'gudang', $data);
    }
    public function update_gudang($id, $data)
    {
        return $this->request('PUT', 'gudang', $data, ['id' => $id]);
    }
    public function delete_gudang($data)
    {
        return $this->request('DELETE', 'gudang', $data);
    }
    public function get_stok_gudang($id)
    {
        return $this->request('GET', 'gudang', [], ['action' => 'stok', 'id' => $id]);
    }

    // ---- Transaksi ----
    public function add_transaksi_masuk($data)
    {
        return $this->request('POST', 'transaksi', $data, ['action' => 'masuk']);
    }
    public function add_transaksi_keluar($data)
    {
        return $this->request('POST', 'transaksi', $data, ['action' => 'keluar']);
    }
    public function add_transfer_stok($data)
    {
        return $this->request('POST', 'transaksi', $data, ['action' => 'transfer']);
    }
    public function get_transaksi($params = [])
    {
        return $this->request('GET', 'transaksi', [], $params);
    }
    public function delete_transaksi($id)
    {
        return $this->request('DELETE', 'transaksi', [], ['id' => $id]);
    }

    // ---- Laporan ----
    public function get_laporan_stok($params = [])
    {
        return $this->request('GET', 'laporan', [], array_merge(['action' => 'stok'], $params));
    }
    public function get_laporan_masuk($params = [])
    {
        return $this->request('GET', 'laporan', [], array_merge(['action' => 'masuk'], $params));
    }
    public function get_laporan_keluar($params = [])
    {
        return $this->request('GET', 'laporan', [], array_merge(['action' => 'keluar'], $params));
    }

    // ---- User ----
    public function get_user($params = [])
    {
        return $this->request('GET', 'user', [], $params);
    }
    public function get_user_by_id($id)
    {
        return $this->request('GET', 'user', [
            'id' => $id
        ]);
    }
    public function add_user($data)
    {
        return $this->request('POST', 'user', $data);
    }
    public function update_user($data)
    {
        return $this->request('PUT', 'user', $data);
    }
    public function delete_user($data)
    {
        return $this->request('DELETE', 'user', $data);
    }

    // ---- Pengaturan ----
    public function get_pengaturan()
    {
        return $this->request('GET', 'pengaturan');
    }
    public function update_pengaturan($data)
    {
        return $this->request('PUT', 'pengaturan', $data);
    }
}
