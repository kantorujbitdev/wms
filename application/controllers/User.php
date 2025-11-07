<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check if user is admin
        if ($this->user['role'] != 'admin') {
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
        $this->data['roles'] = $roles['success'] ? $roles['data'] : ['admin', 'Supervisor', 'Staff'];

        // Render view
        $this->render_view('pages/user/form');
    }

    public function edit($id)
    {
        // Set title
        $this->data['title'] = 'Edit User';

        // Get user data from API
        $user = $this->Api_model->get_user_by_id($id);
        $this->data['user_data'] = $user['success'] ? $user['data'] : [];

        save_log("Editing user ID: " . $id . " | Data found: " . ($user['success'] ? 'yes' : 'no'), 'info');

        // Get roles from API
        $roles = $this->Api_model->get_user(['action' => 'roles']);
        $this->data['roles'] = $roles['success'] ? $roles['data'] : ['admin', 'Supervisor', 'Staff'];

        // Render view
        $this->render_view('pages/user/form');
    }

    public function save()
    {
        $id = $this->input->post('id');

        // Prepare data according to API format
        $data = [
            'username' => $this->input->post('username'),
            'fullname' => $this->input->post('fullname'),
            'role' => $this->input->post('role')
        ];

        // Add password if it's not empty
        if (!empty($this->input->post('password'))) {
            $data['password'] = $this->input->post('password');
        }

        if ($id) {
            // Update existing user - add ID to data for PUT request
            $data['id'] = $id;
            $response = $this->Api_model->update_user($data);
            $message = 'User berhasil diperbarui!';
        } else {
            // Add new user
            $response = $this->Api_model->add_user($data);
            $message = 'User berhasil ditambahkan!';
        }

        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data user: ' . $response['message']);
        }

        redirect('user');
    }

    public function delete($id)
    {
        // Prepare data according to API format
        $data = ['id' => $id];
        $response = $this->Api_model->delete_user($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'User berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user: ' . $response['message']);
        }

        redirect('user');
    }
}