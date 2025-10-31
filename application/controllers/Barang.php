<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Daftar Barang';
        $data['page'] = 'barang';

        // Get items data from API
        $response = $this->api_model->get_barang();

        if ($response['success']) {
            $data['items'] = $response;
        } else {
            $data['items'] = ['data' => []];
            $this->session->set_flashdata('error', $response['message']);
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/barang_list', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah Barang';
        $data['page'] = 'barang';

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Barang', 'required');
            $this->form_validation->set_rules('code', 'Kode Barang', 'required');
            $this->form_validation->set_rules('category_id', 'Kategori', 'required');
            $this->form_validation->set_rules('unit', 'Satuan', 'required');
            $this->form_validation->set_rules('price', 'Harga', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/barang_form', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $item_data = [
                    'name' => $this->input->post('name'),
                    'code' => $this->input->post('code'),
                    'category_id' => $this->input->post('category_id'),
                    'description' => $this->input->post('description'),
                    'unit' => $this->input->post('unit'),
                    'price' => $this->input->post('price'),
                    'min_stock' => $this->input->post('min_stock')
                ];

                $response = $this->api_model->add_barang($item_data);

                if ($response['success']) {
                    $this->session->set_flashdata('success', 'Barang berhasil ditambahkan');
                    redirect('barang');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambah barang: ' . $response['message']);
                    redirect('barang/add');
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/barang_form', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Barang';
        $data['page'] = 'barang';

        // Get item detail from API
        $response = $this->api_model->get_barang(['id' => $id]);

        if ($response['success']) {
            $data['item'] = $response['data'][0];
        } else {
            $this->session->set_flashdata('error', $response['message']);
            redirect('barang');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Barang', 'required');
            $this->form_validation->set_rules('code', 'Kode Barang', 'required');
            $this->form_validation->set_rules('category_id', 'Kategori', 'required');
            $this->form_validation->set_rules('unit', 'Satuan', 'required');
            $this->form_validation->set_rules('price', 'Harga', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/barang_form', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $item_data = [
                    'id' => $id,
                    'name' => $this->input->post('name'),
                    'code' => $this->input->post('code'),
                    'category_id' => $this->input->post('category_id'),
                    'description' => $this->input->post('description'),
                    'unit' => $this->input->post('unit'),
                    'price' => $this->input->post('price'),
                    'min_stock' => $this->input->post('min_stock')
                ];

                $response = $this->api_model->update_barang($id, $item_data);

                if ($response['success']) {
                    $this->session->set_flashdata('success', 'Barang berhasil diupdate');
                    redirect('barang');
                } else {
                    $this->session->set_flashdata('error', 'Gagal update barang: ' . $response['message']);
                    redirect('barang/edit/' . $id);
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/barang_form', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function delete($id)
    {
        $response = $this->api_model->delete_barang($id);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Barang berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal hapus barang: ' . $response['message']);
        }

        redirect('barang');
    }
}