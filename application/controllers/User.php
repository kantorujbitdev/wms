<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check if user is admin
        if ($this->user['role'] != 'Admin') {
            $this->session->set_flashdata('error', 'You do not have permission to access this page.');
            redirect('dashboard');
        }
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'User Management';

        // Get users from API
        $response = $this->Api_model->get_user();
        $this->data['users'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/user/index');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah User';

        // Get roles from API
        $roles = $this->Api_model->get_user(['action' => 'roles']);
        $this->data['roles'] = $roles['success'] ? $roles['data'] : ['Admin', 'Supervisor', 'Staff'];

        // Render view
        $this->render_view('pages/user/form');
    }

    public function edit($id)
    {
        // Set title
        $this->data['title'] = 'Edit User';

        // Get user data from API
        $user = $this->Api_model->get_user(['id' => $id]);
        $this->data['user_data'] = $user['success'] ? $user['data'] : [];

        // Get roles from API
        $roles = $this->Api_model->get_user(['action' => 'roles']);
        $this->data['roles'] = $roles['success'] ? $roles['data'] : ['Admin', 'Supervisor', 'Staff'];

        // Render view
        $this->render_view('pages/user/form');
    }

    public function save()
    {
        $id = $this->input->post('id');
        $data = [
            'username' => $this->input->post('username'),
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
            'status' => $this->input->post('status')
        ];

        // Add password if it's not empty
        if (!empty($this->input->post('password'))) {
            $data['password'] = $this->input->post('password');
        }

        if ($id) {
            // Update existing user
            $response = $this->Api_model->update_user($id, $data);
            $message = 'User berhasil diperbarui!';
        } else {
            // Add new user
            $response = $this->Api_model->add_user($data);
            $message = 'User berhasil ditambahkan!';
        }

        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data user!');
        }

        redirect('user');
    }

    public function delete($id)
    {
        $response = $this->Api_model->delete_user($id);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'User berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user!');
        }

        redirect('user');
    }
}