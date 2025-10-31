<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{

    private $baseUrl;
    private $timeout;

    public function __construct()
    {
        parent::__construct();
        $this->baseUrl = $this->config->item('api_base_url');
        $this->timeout = $this->config->item('api_timeout');
    }

    public function request($method, $endpoint, $data = [])
    {
        $token = $this->session->userdata('api_token');

        // Initialize cURL
        $curl = curl_init($this->baseUrl . $endpoint);

        // Set cURL options
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Content-Type: application/json",
                "Accept: application/json"
            ],
            CURLOPT_TIMEOUT => $this->timeout
        ];

        // Add POST/PUT data if provided
        if (!empty($data) && in_array(strtoupper($method), ['POST', 'PUT'])) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);

        // Execute cURL request
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        // Close cURL
        curl_close($curl);

        // Handle cURL error
        if ($error) {
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $error,
                'http_code' => 0
            ];
        }

        // Decode JSON response
        $result = json_decode($response, true);

        // Handle JSON decode error
        if (json_last_error() !== JSON_ERROR_NONE) {
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
        if ($http_code == 401) {
            $this->session->sess_destroy();
            redirect('auth');
        }

        return $result;
    }

    public function upload($endpoint, $file_field, $data = [])
    {
        $token = $this->session->userdata('api_token');

        // Check if file was uploaded
        if (empty($_FILES[$file_field]['name'])) {
            return [
                'success' => false,
                'message' => 'No file uploaded'
            ];
        }

        // Prepare file for upload
        $file = new CURLFile($_FILES[$file_field]['tmp_name'], $_FILES[$file_field]['type'], $_FILES[$file_field]['name']);
        $post_data = ['file' => $file];

        // Add additional data
        if (!empty($data)) {
            $post_data = array_merge($post_data, $data);
        }

        // Initialize cURL
        $curl = curl_init($this->baseUrl . $endpoint);

        // Set cURL options
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_TIMEOUT => $this->timeout
        ];

        curl_setopt_array($curl, $options);

        // Execute cURL request
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        // Close cURL
        curl_close($curl);

        // Handle cURL error
        if ($error) {
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $error,
                'http_code' => 0
            ];
        }

        // Decode JSON response
        $result = json_decode($response, true);

        // Handle JSON decode error
        if (json_last_error() !== JSON_ERROR_NONE) {
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
        if ($http_code == 401) {
            $this->session->sess_destroy();
            redirect('auth');
        }

        return $result;
    }
}