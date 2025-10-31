<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Check if user has permission to access user management
        $role = $this->session->userdata('role');
        if ($role != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['title'] = 'Daftar User';
        $data['page'] = 'user';

        // Get users data from API
        $data['users'] = $this->api_model->request('GET', 'users');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/user_list', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail User';
        $data['page'] = 'user';

        // Get user detail from API
        $data['user'] = $this->api_model->request('GET', 'users/' . $id);

        // Get user activity logs
        $data['activity_logs'] = $this->api_model->request('GET', 'users/' . $id . '/logs');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/user_detail', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah User';
        $data['page'] = 'user';

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Lengkap', 'required');
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');
            $this->form_validation->set_rules('role', 'Role', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/user_form', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $user_data = [
                    'name' => $this->input->post('name'),
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password'),
                    'role' => $this->input->post('role'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address')
                ];

                $response = $this->api_model->request('POST', 'users', $user_data);

                if (isset($response['success']) && $response['success'] === true) {
                    $this->session->set_flashdata('success', 'User berhasil ditambahkan');
                    redirect('user');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambah user: ' . $response['message']);
                    redirect('user/add');
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/user_form', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit User';
        $data['page'] = 'user';

        // Get user detail from API
        $data['user'] = $this->api_model->request('GET', 'users/' . $id);

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Lengkap', 'required');
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('role', 'Role', 'required');

            // Only validate password if it's provided
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
                $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'matches[password]');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/user_form', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $user_data = [
                    'name' => $this->input->post('name'),
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'role' => $this->input->post('role'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address')
                ];

                // Add password if provided
                if ($this->input->post('password')) {
                    $user_data['password'] = $this->input->post('password');
                }

                $response = $this->api_model->request('PUT', 'users/' . $id, $user_data);

                if (isset($response['success']) && $response['success'] === true) {
                    $this->session->set_flashdata('success', 'User berhasil diupdate');
                    redirect('user');
                } else {
                    $this->session->set_flashdata('error', 'Gagal update user: ' . $response['message']);
                    redirect('user/edit/' . $id);
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/user_form', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function delete($id)
    {
        // Prevent self-deletion
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun sendiri');
            redirect('user');
        }

        $response = $this->api_model->request('DELETE', 'users/' . $id);

        if (isset($response['success']) && $response['success'] === true) {
            $this->session->set_flashdata('success', 'User berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal hapus user: ' . $response['message']);
        }

        redirect('user');
    }
}