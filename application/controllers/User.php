<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check if user is admin
        if ($this->user['role'] != 'admin' && $this->user['role'] != 'superadmin') {
            $this->session->set_flashdata('error', 'You do not have permission to access this page.');
            redirect('dashboard');
        }
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'User Management';
        $data = get_user_data_login();

        // Get users from API
        $response = $this->Api_model->get_user($data);
        $this->data['users'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/user/index');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah User';

        $gudang = $this->Api_model->get_gudang();
        $this->data['warehouses'] = $gudang['success'] ? $gudang['data'] : [];

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
        $data = get_user_data_login(['user_id' => $id]);

        // Get user data from API
        $user = $this->Api_model->get_user_by_id($data);
        $this->data['user_data'] = $user['success'] ? $user['data'][0] : [];

        // Get roles from API
        $roles = $this->Api_model->get_user(['action' => 'roles']);
        $this->data['roles'] = $roles['success'] ? $roles['data'] : ['Superadmin', 'Admin', 'Staff'];

        // Render view
        $this->render_view('pages/user/form');
    }

    public function save()
    {
        $id = $this->input->post('user_id');
        $warehouse_id = $this->input->post('warehouse_id');

        // Prepare data according to API format
        $data = get_user_data_login([
            'username' => $this->input->post('username'),
            'fullname' => $this->input->post('fullname'),
            'role' => $this->input->post('role'),
            'warehouse_id' => $warehouse_id
        ]);

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
        $data = get_user_data_login(['id' => $id]);
        $response = $this->Api_model->delete_user($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'User berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user: ' . $response['message']);
        }

        redirect('user');
    }
}