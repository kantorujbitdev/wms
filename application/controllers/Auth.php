<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $data['title'] = 'Login - ' . $this->config->item('app_name');
        $this->load->view('pages/login', $data);
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Username dan password harus diisi');
            redirect('auth');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Log login attempt
            log_message('debug', 'Login attempt for username: ' . $username);

            // Call API login
            $response = $this->Api_model->login($username, $password);

            // Log response
            log_message('debug', 'Login response: ' . json_encode($response));

            // Check if login successful
            if (isset($response['success']) && $response['success'] === true) {
                // Set session data
                $user_data = [
                    'user_id' => $response['data']['id'],
                    'username' => $response['data']['username'],
                    'name' => $response['data']['name'],
                    'role' => $response['data']['role'],
                    'api_token' => $response['data']['token'],
                    'logged_in' => TRUE
                ];

                $this->session->set_userdata($user_data);

                // Log successful login
                log_message('debug', 'Login successful for user: ' . $username);

                // Set flash message
                $this->session->set_flashdata('success', 'Login berhasil! Selamat datang ' . $response['data']['name']);

                redirect('dashboard');
            } else {
                // Log failed login
                log_message('error', 'Login failed for user: ' . $username . '. Response: ' . json_encode($response));

                // Set error message
                $error_message = isset($response['message']) ? $response['message'] : 'Username atau password salah';
                $this->session->set_flashdata('error', $error_message);

                // For debugging purposes, let's show the raw response
                if (ENVIRONMENT == 'development') {
                    $this->session->set_flashdata('debug_info', 'Debug Info: ' . json_encode($response));
                }

                redirect('auth');
            }
        }
    }

    public function logout()
    {
        // Get user data before destroying session
        $user_data = $this->session->userdata();

        // Log logout
        log_message('debug', 'Logout for user: ' . (isset($user_data['username']) ? $user_data['username'] : 'unknown'));

        // Destroy session
        $this->session->sess_destroy();

        // Set flash message
        $this->session->set_flashdata('success', 'Anda telah berhasil logout');

        redirect('auth');
    }

    /**
     * Fungsi untuk test koneksi API
     */
    public function test_api()
    {
        // Only allow in development environment
        if (ENVIRONMENT != 'development') {
            show_404();
        }

        $response = $this->Api_model->test_connection();

        echo '<pre>';
        echo 'API Test Connection Result:<br>';
        echo json_encode($response, JSON_PRETTY_PRINT);
        echo '</pre>';

        // Show logs
        echo '<h3>Logs:</h3>';
        echo '<pre>';
        if (file_exists(APPPATH . 'logs/log-' . date('Y-m-d') . '.php')) {
            $logs = file_get_contents(APPPATH . 'logs/log-' . date('Y-m-d') . '.php');
            // Remove PHP opening tag and log prefix
            $logs = str_replace('<?php defined("BASEPATH") OR exit("No direct script access allowed"); ?>', '', $logs);
            $logs = preg_replace('/^.*?DEBUG - /m', '', $logs);
            $logs = preg_replace('/^.*?ERROR - /m', '', $logs);
            echo htmlspecialchars($logs);
        } else {
            echo 'No logs found for today.';
        }
        echo '</pre>';
    }
}