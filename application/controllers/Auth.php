<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // If user is already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
    }

    public function index()
    {
        $this->load->view('pages/login');
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('pages/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $response = $this->api_model->request('POST', 'login', [
                'username' => $username,
                'password' => $password
            ]);

            if (isset($response['success']) && $response['success'] === true) {
                $user_data = [
                    'user_id' => $response['data']['id'],
                    'username' => $response['data']['username'],
                    'name' => $response['data']['name'],
                    'role' => $response['data']['role'],
                    'api_token' => $response['data']['token'],
                    'logged_in' => TRUE
                ];

                $this->session->set_userdata($user_data);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect('auth');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}