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

        // Get warehouses from API
        $response = $this->Api_model->get_gudang();
        $this->data['warehouses'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/gudang/index');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah Gudang';

        // Render view
        $this->render_view('pages/gudang/form');
    }

    public function edit($id)
    {
        // Set title
        $this->data['title'] = 'Edit Gudang';

        // Get warehouse data from API
        $warehouse = $this->Api_model->get_gudang(['id' => $id]);
        $this->data['warehouse'] = $warehouse['success'] ? $warehouse['data'] : [];

        // Render view
        $this->render_view('pages/gudang/form');
    }

    public function save()
    {
        $id = $this->input->post('id');
        $data = [
            'name' => $this->input->post('name'),
            'code' => $this->input->post('code'),
            'address' => $this->input->post('address'),
            'capacity' => $this->input->post('capacity'),
            'manager' => $this->input->post('manager'),
            'phone' => $this->input->post('phone')
        ];

        if ($id) {
            // Update existing warehouse
            $response = $this->Api_model->update_gudang($id, $data);
            $message = 'Gudang berhasil diperbarui!';
        } else {
            // Add new warehouse
            $response = $this->Api_model->add_gudang($data);
            $message = 'Gudang berhasil ditambahkan!';
        }

        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data gudang!');
        }

        redirect('gudang');
    }

    public function delete($id)
    {
        $response = $this->Api_model->delete_gudang($id);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Gudang berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus gudang!');
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