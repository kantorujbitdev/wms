<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Barang';

        // Get items from API
        $response = $this->Api_model->get_barang();
        $this->data['items'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/barang/index');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah Barang';

        // Get categories from API
        $categories = $this->Api_model->get_barang(['action' => 'categories']);
        $this->data['categories'] = $categories['success'] ? $categories['data'] : [];

        // Get units from API
        $units = $this->Api_model->get_barang(['action' => 'units']);
        $this->data['units'] = $units['success'] ? $units['data'] : [];

        // Render view
        $this->render_view('pages/barang/form');
    }

    public function edit($id)
    {
        // Set title
        $this->data['title'] = 'Edit Barang';

        // Get item data from API
        $item = $this->Api_model->get_barang(['id' => $id]);
        $this->data['item'] = $item['success'] ? $item['data'] : [];

        // Get categories from API
        $categories = $this->Api_model->get_barang(['action' => 'categories']);
        $this->data['categories'] = $categories['success'] ? $categories['data'] : [];

        // Get units from API
        $units = $this->Api_model->get_barang(['action' => 'units']);
        $this->data['units'] = $units['success'] ? $units['data'] : [];

        // Render view
        $this->render_view('pages/barang/form');
    }

    public function save()
    {
        $id = $this->input->post('id');
        $data = [
            'name' => $this->input->post('name'),
            'code' => $this->input->post('code'),
            'description' => $this->input->post('description'),
            'category' => $this->input->post('category'),
            'unit' => $this->input->post('unit'),
            'price' => $this->input->post('price'),
            'min_stock' => $this->input->post('min_stock')
        ];

        if ($id) {
            // Update existing item
            $response = $this->Api_model->update_barang($id, $data);
            $message = 'Barang berhasil diperbarui!';
        } else {
            // Add new item
            $response = $this->Api_model->add_barang($data);
            $message = 'Barang berhasil ditambahkan!';
        }

        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data barang!');
        }

        redirect('barang');
    }

    public function delete($id)
    {
        $response = $this->Api_model->delete_barang($id);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Barang berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus barang!');
        }

        redirect('barang');
    }
}