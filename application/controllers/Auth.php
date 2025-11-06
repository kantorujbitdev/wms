<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->model('Pengaturan_model', 'pengaturan');
        $this->load->model('Data_api_model', 'data_api');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');

        include(APPPATH . 'views/layouts/wording.php'); // hasilnya $wording array
        $this->data['wording'] = $wording;
    }

    public function index()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->data['title'] = 'Login';

        $this->load->view('pages/login', $this->data);
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Username dan password harus diisi');
            redirect('auth');
        } else {
            load_appdata_to_session();

            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Log login attempt
            log_message('debug', 'Login attempt for username: ' . $username);

            $api_base_url = $this->pengaturan->get_by_name('api_base_url');
            log_message('debug', 'Login api_base_url : ' . $api_base_url);

            // Call API login
            $response = $this->Api_model->login($username, $password);

            // Log response
            log_message('debug', 'Login response: ' . json_encode($response));

            // Check if login successful
            if (isset($response['success']) && $response['success'] === true) {
                // Set session data
                $user_data = [
                    'user_id' => $response['data']['UserId'],
                    'username' => $response['data']['Username'],
                    'name' => $response['data']['name'],
                    'role' => $response['data']['Role'],
                    'api_token' => $response['data']['token'],
                    'logged_in' => TRUE
                ];

                $this->session->set_userdata($user_data);

                // Log successful login
                log_message('debug', 'Login successful for user: ' . $username);

                // Set flash message
                $this->session->set_flashdata('success', 'Login berhasil! Selamat datang ' . $username);

                redirect('dashboard');
            } else {
                // Log failed login
                log_message('error', 'Login failed for user: ' . $username . '. Response: ' . json_encode($response));

                // Set error message
                $error_message = isset($response['message']) ? $response['message'] : 'Username atau password salah';
                $this->session->set_flashdata('error', $error_message);

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
        clear_app_cache();

        redirect('auth');
    }
}