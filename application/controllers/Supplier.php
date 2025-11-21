<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Supplier';
        $this->data['active_menu'] = 'supplier';
        $this->data['active_submenu'] = 'supplier';

        // Get suppliers from API
        $response = $this->Api_model->get_supplier(data_login_user());
        $this->data['suppliers'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/supplier/index');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah Supplier';
        $this->data['active_menu'] = 'supplier';
        $this->data['active_submenu'] = 'supplier';

        // Render view
        $this->render_view('pages/supplier/form');
    }

    public function edit($id)
    {
        // Set title
        $this->data['title'] = 'Edit Supplier';
        $this->data['active_menu'] = 'supplier';
        $this->data['active_submenu'] = 'supplier';
        $data = data_login_user(['id' => $id]);

        // Get supplier data from API
        $supplier = $this->Api_model->get_supplier($data);
        $this->data['supplier'] = $supplier['success'] ? $supplier['data'][0] : [];

        // Render view
        $this->render_view('pages/supplier/form');
    }

    public function save()
    {
        $id = $this->input->post('id');

        if ($id) {
            // Prepare data according to API format
            $data = data_login_user([
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'person' => $this->input->post('contact'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            ]);
            // Update existing supplier
            $response = $this->Api_model->update_supplier($data);
            $message = 'Supplier berhasil diperbarui!';
        } else {
            $data = data_login_user([
                'name' => $this->input->post('name'),
                'person' => $this->input->post('contact'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            ]);// Add new supplier
            $response = $this->Api_model->add_supplier($data);
            $message = 'Supplier berhasil ditambahkan!';
        }

        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data supplier: ' . $response['message']);
        }

        redirect('supplier');
    }

    public function delete($id)
    {
        // Prepare data according to API format
        $data = data_login_user([
            'id' => $id
        ]);

        $response = $this->Api_model->delete_supplier($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Supplier berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus supplier: ' . $response['message']);
        }

        redirect('supplier');
    }
}