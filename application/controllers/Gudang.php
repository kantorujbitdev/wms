<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Gudang';
        $this->data['active_menu'] = 'gudang';
        $this->data['active_submenu'] = 'gudang_utama';
        $data = data_login_user(['warehouse_type' => 0]);
        // Get warehouses from API
        $response = $this->Api_model->get_gudang($data);
        $this->data['warehouses'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/gudang/index');
    }

    public function gudang_project()
    {
        // Set title
        $this->data['title'] = 'Gudang Project';
        $this->data['active_menu'] = 'gudang';
        $this->data['active_submenu'] = 'gudang_project';

        $data = data_login_user(['warehouse_type' => 1]);
        // Get warehouses from API
        $response = $this->Api_model->get_gudang_id_project($data);
        $this->data['warehouses'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/gudang_project/index');
    }

    public function add_gudang_project()
    {
        // Set title
        $this->data['title'] = 'Tambah Gudang Project';
        $this->data['active_menu'] = 'gudang';
        $this->data['active_submenu'] = 'gudang_project';

        // Render view
        $this->render_view('pages/gudang_project/form');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah Gudang';
        $this->data['active_menu'] = 'gudang';
        $this->data['active_submenu'] = 'gudang_utama';

        // Render view
        $this->render_view('pages/gudang/form');
    }

    public function edit($id)
    {
        // Set title
        $this->data['title'] = 'Edit Gudang';
        $this->data['active_menu'] = 'gudang';
        $this->data['active_submenu'] = 'gudang_utama';

        $data = data_login_user(['warehouse_id' => $id]);
        // Get warehouse data from API
        $warehouse = $this->Api_model->get_gudang_id($data);
        // $this->data['warehouse'] = $warehouse['success'] ? $warehouse['data'] : [];

        if ($warehouse['success'] && !empty($warehouse['data'])) {
            // Ambil baris pertama karena API return array
            $this->data['warehouse'] = $warehouse['data'][0];
        }

        // Render view
        $this->render_view('pages/gudang/form');
    }

    public function edit_gudang_project($id)
    {
        // Set title
        $this->data['title'] = 'Edit Gudang Project';
        $this->data['active_menu'] = 'gudang';
        $this->data['active_submenu'] = 'gudang_project';

        $data = data_login_user(['warehouse_id' => $id]);

        // Get warehouse data from API
        $warehouse = $this->Api_model->get_gudang_id($data);
        // $this->data['warehouse'] = $warehouse['success'] ? $warehouse['data'] : [];
        if ($warehouse['success'] && !empty($warehouse['data'])) {
            // Ambil baris pertama karena API return array
            $this->data['warehouse'] = $warehouse['data'][0];
        }
        // Render view
        $this->render_view('pages/gudang_project/form');
    }
    public function save_warehouse_project()
    {
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // gudang utama warehouse_type = 0
        // gudang project warehouse_type = 1

        $warehouse_id = $this->input->post('id');
        save_log('save_warehouse_project warehouse_id: ' . $warehouse_id);
        if ($warehouse_id) {
            // Update existing warehouse - add ID to data for PUT request
            // Prepare data according to API format
            $data = data_login_user([
                'warehouse_id' => $this->input->post('id'),
                'warehouse_code' => $this->input->post('warehouse_code'),
                'warehouse_name' => $this->input->post('warehouse_name'),
                'warehouse_address' => $this->input->post('warehouse_address'),
                'contact_person' => $this->input->post('contact_person'),
                'phone' => $this->input->post('phone'),
                'warehouse_status' => 0,
                'warehouse_type' => 1,
                'user_id' => $user_id
            ]);
            $response = $this->Api_model->update_gudang($data);
            $message = 'Gudang berhasil diperbarui!';
        } else {
            // Prepare data according to API format
            $data = data_login_user([
                'warehouse_code' => $this->input->post('warehouse_code'),
                'warehouse_name' => $this->input->post('warehouse_name'),
                'warehouse_address' => $this->input->post('warehouse_address'),
                'contact_person' => $this->input->post('contact_person'),
                'phone' => $this->input->post('phone'),
                'warehouse_status' => 0,
                'warehouse_type' => 1,
                'user_id' => $user_id
            ]);
            // Add new warehouse
            $response = $this->Api_model->add_gudang($data);
            $message = 'Gudang berhasil ditambahkan!';
        }


        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data gudang: ' . $response['message']);
        }

        redirect('gudang/gudang_project');
    }
    public function save_warehouse_utama()
    {
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');
        $warehouse_id = $this->input->post('id');
        save_log('save_warehouse_utama warehouse_id: ' . $warehouse_id);

        // gudang utama warehouse_type = 0
        // gudang project warehouse_type = 1

        if ($warehouse_id) {
            // Update existing warehouse - add ID to data for PUT request
            $data = data_login_user([
                'warehouse_id' => $this->input->post('id'),
                'warehouse_code' => $this->input->post('warehouse_code'),
                'warehouse_name' => $this->input->post('warehouse_name'),
                'warehouse_address' => $this->input->post('warehouse_address'),
                'contact_person' => $this->input->post('contact_person'),
                'phone' => $this->input->post('phone'),
                'warehouse_status' => 0,
                'warehouse_type' => 0,
                'user_id' => $user_id
            ]);
            $response = $this->Api_model->update_gudang($data);
            $message = 'Gudang berhasil diperbarui!';
        } else {
            // Add new warehouse
            $data = data_login_user([
                'warehouse_code' => $this->input->post('warehouse_code'),
                'warehouse_name' => $this->input->post('warehouse_name'),
                'warehouse_address' => $this->input->post('warehouse_address'),
                'contact_person' => $this->input->post('contact_person'),
                'phone' => $this->input->post('phone'),
                'warehouse_status' => 0,
                'warehouse_type' => 0,
                'user_id' => $user_id
            ]);
            $response = $this->Api_model->add_gudang($data);
            $message = 'Gudang berhasil ditambahkan!';
        }


        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data gudang: ' . $response['message']);
        }

        redirect('gudang');
    }

    public function delete($id)
    {
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = data_login_user([
            'warehouse_id' => $id,
            'user_id' => $user_id
        ]);

        $response = $this->Api_model->delete_gudang($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Gudang berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus gudang: ' . $response['message']);
        }

        redirect('gudang');
    }

    public function stock($id)
    {
        // Set title
        $this->data['title'] = 'Stok Gudang';

        // Get warehouse data from API
        $warehouse = $this->Api_model->get_gudang(['id' => $id]);
        $this->data['warehouse'] = $warehouse['success'] ? $warehouse['data'] : [];

        // Get stock data from API
        $stock = $this->Api_model->get_stok_gudang($id);
        $this->data['stock_items'] = $stock['success'] ? $stock['data'] : [];

        // Render view
        $this->render_view('pages/gudang/stock');
    }
}