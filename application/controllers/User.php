<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->check_permission('user', 'view');

    }

    public function index()
    {
        load_appdata_to_session();
        load_menu();
        // Set title
        $this->data['title'] = 'User Management';
        $data = data_login_user();
        $user_role = $this->session->userdata('role');
        if ($user_role != 'superadmin') {
            $warehouse_id = $this->session->userdata('warehouse_id');
            $data = data_login_user(['warehouse_id' => $warehouse_id]);
        }

        // Get users from API
        $response = $this->Api_model->get_user($data);
        $this->data['users'] = $this->handle_response($response);

        // Render view
        $this->render_view('pages/user/index');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah User';

        $gudang = $this->Api_model->get_gudang(data_login_user());
        $this->data['warehouses'] = $this->handle_response($gudang);

        // Get roles from API
        $roles = $this->Api_model->get_user(['action' => 'roles']);
        $this->data['roles'] = $roles['success'] ? $roles['data'] : ['superadmin', 'admin', 'Staff'];

        // Render view
        $this->render_view('pages/user/form');
    }

    public function edit($id)
    {
        // Set title
        $this->data['title'] = 'Edit User';
        $data = data_login_user(['user_id' => $id]);

        // Get user data from API
        $user = $this->Api_model->get_user_by_id($data);
        $this->handle_response($user);
        $this->data['user_data'] = $user['success'] ? $user['data'][0] : [];

        // Get roles from API
        $roles = $this->Api_model->get_user(['action' => 'roles']);
        $this->handle_response($roles);
        $this->data['roles'] = $roles['success'] ? $roles['data'] : ['Superadmin', 'Admin', 'Staff'];

        // ✅ Get Warehouse
        $warehouse = $this->Api_model->get_gudang(data_login_user());
        $this->data['warehouses'] = $this->handle_response($warehouse);

        // Render view
        $this->render_view('pages/user/form');
    }

    public function save()
    {
        $id = $this->input->post('id');
        $warehouse_id = $this->input->post('warehouse_id');

        // Add password if it's not empty
        if (!empty($this->input->post('password'))) {
            $data['password'] = $this->input->post('password');
        }
        if ($id) {
            // Prepare data according to API format
            $data = data_login_user([
                'user_id' => $id,
                'user_name' => $this->input->post('username'),
                'full_name' => $this->input->post('fullname'),
                'password' => $this->input->post('password'),
                'user_role' => strtolower($this->input->post('role')),
                'warehouse_id' => $warehouse_id
            ]);
            $response = $this->Api_model->update_user($data);
            $message = 'User berhasil diperbarui!';
        } else {
            if (!empty($this->input->post('password'))) {
                $data = data_login_user([
                    'user_name' => $this->input->post('username'),
                    'full_name' => $this->input->post('fullname'),
                    'password' => $this->input->post('password'),
                    'user_role' => strtolower($this->input->post('role')),
                    'warehouse_id' => $warehouse_id
                ]);
            } else {
                $data = data_login_user([
                    'user_name' => $this->input->post('username'),
                    'full_name' => $this->input->post('fullname'),
                    'user_role' => strtolower($this->input->post('role')),
                    'warehouse_id' => $warehouse_id
                ]);
            }
            // Add new user
            $response = $this->Api_model->add_user($data);
        }
        $this->handle_response($response, 'User berhasil ditambahkan!');
        redirect('user');
    }

    public function delete($id)
    {
        // Prepare data according to API format
        $data = data_login_user(['user_id' => $id]);
        $response = $this->Api_model->delete_user($data);
        $this->handle_response($response, 'User berhasil dihapus!');

        redirect('user');
    }
}