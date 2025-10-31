<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Check if user has permission to access settings
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['title'] = 'Pengaturan';
        $data['page'] = 'pengaturan';

        // Get current settings from API
        $data['settings'] = $this->api_model->request('GET', 'settings');

        if ($this->input->post()) {
            $this->form_validation->set_rules('api_base_url', 'API Base URL', 'required|valid_url');
            $this->form_validation->set_rules('app_name', 'Nama Aplikasi', 'required');
            $this->form_validation->set_rules('app_logo', 'Logo Aplikasi', 'required');
            $this->form_validation->set_rules('items_per_page', 'Item per Halaman', 'required|numeric|greater_than[0]');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/pengaturan', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $settings_data = [
                    'api_base_url' => $this->input->post('api_base_url'),
                    'app_name' => $this->input->post('app_name'),
                    'app_logo' => $this->input->post('app_logo'),
                    'items_per_page' => $this->input->post('items_per_page'),
                    'date_format' => $this->input->post('date_format'),
                    'time_format' => $this->input->post('time_format'),
                    'timezone' => $this->input->post('timezone'),
                    'email_notification' => $this->input->post('email_notification') ? 1 : 0,
                    'low_stock_threshold' => $this->input->post('low_stock_threshold')
                ];

                $response = $this->api_model->request('PUT', 'settings', $settings_data);

                if (isset($response['success']) && $response['success'] === true) {
                    // Update config file
                    $this->update_config_file($settings_data);

                    $this->session->set_flashdata('success', 'Pengaturan berhasil diupdate');
                    redirect('pengaturan');
                } else {
                    $this->session->set_flashdata('error', 'Gagal update pengaturan: ' . $response['message']);
                    redirect('pengaturan');
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/pengaturan', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    private function update_config_file($settings)
    {
        $config_file = APPPATH . 'config/config.php';
        $config_content = file_get_contents($config_file);

        // Update API base URL
        $config_content = preg_replace(
            '/\$config\[\'api_base_url\'\]\s*=\s*[\'"](.*?)[\'"];/',
            '$config[\'api_base_url\'] = \'' . $settings['api_base_url'] . '\';',
            $config_content
        );

        // Write back to config file
        file_put_contents($config_file, $config_content);
    }

    public function test_api()
    {
        $api_base_url = $this->input->post('api_base_url');
        $api_token = $this->input->post('api_token');

        // Test API connection
        $curl = curl_init($api_base_url . 'test');
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $api_token",
                "Content-Type: application/json"
            ],
            CURLOPT_TIMEOUT => 10
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $result = json_decode($response, true);

        if ($http_code == 200 && isset($result['success']) && $result['success'] === true) {
            echo json_encode([
                'success' => true,
                'message' => 'Koneksi API berhasil'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Koneksi API gagal: ' . ($result['message'] ?? 'Unknown error')
            ]);
        }
    }
}