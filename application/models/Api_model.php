<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{
    private $baseUrl;
    private $timeout;
    private $endpoints;

    public function __construct()
    {
        parent::__construct();
        $this->baseUrl = $this->config->item('api_base_url');
        $this->timeout = $this->config->item('api_timeout');

        // Load endpoints from config
        $this->endpoints = [
            'login' => $this->config->item('endpoint_login'),
            'barang' => $this->config->item('endpoint_barang'),
            'gudang' => $this->config->item('endpoint_gudang'),
            'transaksi' => $this->config->item('endpoint_transaksi'),
            'laporan' => $this->config->item('endpoint_laporan'),
            'user' => $this->config->item('endpoint_user'),
            'pengaturan' => $this->config->item('endpoin_pengaturan')
        ];

        // Enable logging
        $this->load->library('session');
        log_message('debug', 'Api_model initialized with base URL: ' . $this->baseUrl);
    }

    /**
     * Fungsi untuk melakukan request ke API
     * 
     * @param string $method Metode HTTP (GET, POST, PUT, DELETE)
     * @param string $endpoint Endpoint API (login, barang, gudang, transaksi, laporan, user, pengaturan)
     * @param array $data Data yang akan dikirim
     * @param array $params Parameter tambahan untuk URL
     * @return array Response dari API
     */
    public function request($method, $endpoint, $data = [], $params = [])
    {
        $token = $this->session->userdata('api_token');

        // Log request
        log_message('debug', 'API Request: ' . $method . ' ' . $endpoint);
        log_message('debug', 'Request data: ' . json_encode($data));
        log_message('debug', 'Request params: ' . json_encode($params));

        // Cek endpoint
        if (!isset($this->endpoints[$endpoint])) {
            log_message('error', 'Endpoint not found: ' . $endpoint);
            return [
                'success' => false,
                'message' => 'Endpoint not found',
                'http_code' => 404
            ];
        }

        $url = $this->endpoints[$endpoint];
        log_message('debug', 'API URL: ' . $url);

        // Tambahkan parameter ke URL jika ada
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
            log_message('debug', 'API URL with params: ' . $url);
        }

        // Initialize cURL
        $curl = curl_init($url);

        // Set cURL options
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Accept: application/json"
            ],
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_VERBOSE => true, // Enable verbose logging
            CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification for development
            CURLOPT_SSL_VERIFYHOST => false
        ];

        // Add Authorization header if token exists (except for login)
        if ($token && $endpoint !== 'login') {
            $options[CURLOPT_HTTPHEADER][] = "Authorization: Bearer $token";
            log_message('debug', 'Using token: ' . substr($token, 0, 10) . '...');
        }

        // Add POST/PUT data if provided
        if (!empty($data) && in_array(strtoupper($method), ['POST', 'PUT'])) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
            log_message('debug', 'POST/PUT data: ' . json_encode($data));
        }

        curl_setopt_array($curl, $options);

        // Execute cURL request
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        $errno = curl_errno($curl);

        // Log cURL info
        log_message('debug', 'cURL info: ' . json_encode(curl_getinfo($curl)));

        // Close cURL
        curl_close($curl);

        // Handle cURL error
        if ($error) {
            log_message('error', 'cURL Error #' . $errno . ': ' . $error);
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $error,
                'http_code' => 0
            ];
        }

        // Log response
        log_message('debug', 'API Response HTTP Code: ' . $http_code);
        log_message('debug', 'API Response: ' . $response);

        // Decode JSON response
        $result = json_decode($response, true);

        // Handle JSON decode error
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'JSON Decode Error: ' . json_last_error_msg());
            log_message('error', 'Raw Response: ' . $response);
            return [
                'success' => false,
                'message' => 'JSON Decode Error: ' . json_last_error_msg(),
                'http_code' => $http_code,
                'raw_response' => $response
            ];
        }

        // Add HTTP code to result
        $result['http_code'] = $http_code;

        // Handle token expiration
        if ($http_code == 401 && $endpoint !== 'login') {
            log_message('debug', 'Token expired, destroying session');
            $this->session->sess_destroy();
            redirect('auth');
        }

        return $result;
    }

    /**
     * Fungsi untuk melakukan login
     * 
     * @param string $username Username
     * @param string $password Password
     * @return array Response dari API
     */
    public function login($username, $password)
    {
        $data = [
            'Username' => $username,
            'Password' => $password
        ];

        log_message('debug', 'Attempting login with username: ' . $username);
        return $this->request('POST', 'login', $data);
    }

    /**
     * Fungsi untuk melakukan test koneksi ke API
     * 
     * @return array Response dari API
     */
    public function test_connection()
    {
        log_message('debug', 'Testing API connection');
        return $this->request('GET', 'login');
    }

    /**
     * Fungsi untuk mendapatkan data barang
     * 
     * @param array $params Parameter tambahan
     * @return array Response dari API
     */
    public function get_barang($params = [])
    {
        return $this->request('GET', 'barang', [], $params);
    }

    /**
     * Fungsi untuk menambah barang
     * 
     * @param array $data Data barang
     * @return array Response dari API
     */
    public function add_barang($data)
    {
        return $this->request('POST', 'barang', $data);
    }

    /**
     * Fungsi untuk mengupdate barang
     * 
     * @param int $id ID barang
     * @param array $data Data barang
     * @return array Response dari API
     */
    public function update_barang($id, $data)
    {
        return $this->request('PUT', 'barang', $data, ['id' => $id]);
    }

    /**
     * Fungsi untuk menghapus barang
     * 
     * @param int $id ID barang
     * @return array Response dari API
     */
    public function delete_barang($id)
    {
        return $this->request('DELETE', 'barang', [], ['id' => $id]);
    }

    /**
     * Fungsi untuk mendapatkan data gudang
     * 
     * @param array $params Parameter tambahan
     * @return array Response dari API
     */
    public function get_gudang($params = [])
    {
        return $this->request('GET', 'gudang', [], $params);
    }

    /**
     * Fungsi untuk menambah gudang
     * 
     * @param array $data Data gudang
     * @return array Response dari API
     */
    public function add_gudang($data)
    {
        return $this->request('POST', 'gudang', $data);
    }

    /**
     * Fungsi untuk mengupdate gudang
     * 
     * @param int $id ID gudang
     * @param array $data Data gudang
     * @return array Response dari API
     */
    public function update_gudang($id, $data)
    {
        return $this->request('PUT', 'gudang', $data, ['id' => $id]);
    }

    /**
     * Fungsi untuk menghapus gudang
     * 
     * @param int $id ID gudang
     * @return array Response dari API
     */
    public function delete_gudang($id)
    {
        return $this->request('DELETE', 'gudang', [], ['id' => $id]);
    }

    /**
     * Fungsi untuk mendapatkan stok di gudang
     * 
     * @param int $id ID gudang
     * @return array Response dari API
     */
    public function get_stok_gudang($id)
    {
        return $this->request('GET', 'gudang', [], ['action' => 'stok', 'id' => $id]);
    }

    /**
     * Fungsi untuk menambah transaksi barang masuk
     * 
     * @param array $data Data transaksi
     * @return array Response dari API
     */
    public function add_transaksi_masuk($data)
    {
        return $this->request('POST', 'transaksi', $data, ['action' => 'masuk']);
    }

    /**
     * Fungsi untuk menambah transaksi barang keluar
     * 
     * @param array $data Data transaksi
     * @return array Response dari API
     */
    public function add_transaksi_keluar($data)
    {
        return $this->request('POST', 'transaksi', $data, ['action' => 'keluar']);
    }

    /**
     * Fungsi untuk menambah transfer stok
     * 
     * @param array $data Data transfer
     * @return array Response dari API
     */
    public function add_transfer_stok($data)
    {
        return $this->request('POST', 'transaksi', $data, ['action' => 'transfer']);
    }

    /**
     * Fungsi untuk mendapatkan data transaksi
     * 
     * @param array $params Parameter tambahan
     * @return array Response dari API
     */
    public function get_transaksi($params = [])
    {
        return $this->request('GET', 'transaksi', [], $params);
    }

    /**
     * Fungsi untuk menghapus transaksi
     * 
     * @param int $id ID transaksi
     * @return array Response dari API
     */
    public function delete_transaksi($id)
    {
        return $this->request('DELETE', 'transaksi', [], ['id' => $id]);
    }

    /**
     * Fungsi untuk mendapatkan laporan stok
     * 
     * @param array $params Parameter tambahan
     * @return array Response dari API
     */
    public function get_laporan_stok($params = [])
    {
        return $this->request('GET', 'laporan', [], array_merge(['action' => 'stok'], $params));
    }

    /**
     * Fungsi untuk mendapatkan laporan barang masuk
     * 
     * @param array $params Parameter tambahan
     * @return array Response dari API
     */
    public function get_laporan_masuk($params = [])
    {
        return $this->request('GET', 'laporan', [], array_merge(['action' => 'masuk'], $params));
    }

    /**
     * Fungsi untuk mendapatkan laporan barang keluar
     * 
     * @param array $params Parameter tambahan
     * @return array Response dari API
     */
    public function get_laporan_keluar($params = [])
    {
        return $this->request('GET', 'laporan', [], array_merge(['action' => 'keluar'], $params));
    }

    /**
     * Fungsi untuk mendapatkan data user
     * 
     * @param array $params Parameter tambahan
     * @return array Response dari API
     */
    public function get_user($params = [])
    {
        return $this->request('GET', 'user', [], $params);
    }

    /**
     * Fungsi untuk menambah user
     * 
     * @param array $data Data user
     * @return array Response dari API
     */
    public function add_user($data)
    {
        return $this->request('POST', 'user', $data);
    }

    /**
     * Fungsi untuk mengupdate user
     * 
     * @param int $id ID user
     * @param array $data Data user
     * @return array Response dari API
     */
    public function update_user($id, $data)
    {
        return $this->request('PUT', 'user', $data, ['id' => $id]);
    }

    /**
     * Fungsi untuk menghapus user
     * 
     * @param int $id ID user
     * @return array Response dari API
     */
    public function delete_user($id)
    {
        return $this->request('DELETE', 'user', [], ['id' => $id]);
    }

    /**
     * Fungsi untuk mendapatkan pengaturan
     * 
     * @return array Response dari API
     */
    public function get_pengaturan()
    {
        return $this->request('GET', 'pengaturan');
    }

    /**
     * Fungsi untuk mengupdate pengaturan
     * 
     * @param array $data Data pengaturan
     * @return array Response dari API
     */
    public function update_pengaturan($data)
    {
        return $this->request('PUT', 'pengaturan', $data);
    }

    /**
     * Fungsi untuk mendapatkan informasi cURL error
     * 
     * @param int $errno cURL error number
     * @return string Error message
     */
    private function get_curl_error_message($errno)
    {
        $errors = [
            CURLE_OK => 'No error',
            CURLE_UNSUPPORTED_PROTOCOL => 'Unsupported protocol',
            CURLE_FAILED_INIT => 'Failed init',
            CURLE_URL_MALFORMAT => 'URL format error',
            CURLE_COULDNT_RESOLVE_PROXY => 'Couldnt resolve proxy',
            CURLE_COULDNT_RESOLVE_HOST => 'Couldnt resolve host',
            CURLE_COULDNT_CONNECT => 'Couldnt connect',
            CURLE_OPERATION_TIMEDOUT => 'Operation timed out',
            CURLE_SSL_CONNECT_ERROR => 'SSL connect error',
            CURLE_TOO_MANY_REDIRECTS => 'Too many redirects',
        ];

        return isset($errors[$errno]) ? $errors[$errno] : 'Unknown error';
    }
}