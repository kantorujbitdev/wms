<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang extends CI_Controller
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
        $data['title'] = 'Daftar Gudang';
        $data['page'] = 'gudang';

        // Get warehouses data from API
        $data['warehouses'] = $this->api_model->request('GET', 'warehouses');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/gudang_list', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Gudang';
        $data['page'] = 'gudang';

        // Get warehouse detail from API
        $data['warehouse'] = $this->api_model->request('GET', 'warehouses/' . $id);

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/gudang_detail', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function stok($id)
    {
        $data['title'] = 'Stok Gudang';
        $data['page'] = 'gudang';

        // Get warehouse detail from API
        $data['warehouse'] = $this->api_model->request('GET', 'warehouses/' . $id);

        // Get stock items in warehouse from API
        $data['stock_items'] = $this->api_model->request('GET', 'warehouses/' . $id . '/stock');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/stok_gudang', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah Gudang';
        $data['page'] = 'gudang';

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Gudang', 'required');
            $this->form_validation->set_rules('code', 'Kode Gudang', 'required|is_unique[warehouses.code]');
            $this->form_validation->set_rules('address', 'Alamat', 'required');
            $this->form_validation->set_rules('capacity', 'Kapasitas', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/gudang_form', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $warehouse_data = [
                    'name' => $this->input->post('name'),
                    'code' => $this->input->post('code'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'province' => $this->input->post('province'),
                    'postal_code' => $this->input->post('postal_code'),
                    'capacity' => $this->input->post('capacity'),
                    'description' => $this->input->post('description')
                ];

                $response = $this->api_model->request('POST', 'warehouses', $warehouse_data);

                if (isset($response['success']) && $response['success'] === true) {
                    $this->session->set_flashdata('success', 'Gudang berhasil ditambahkan');
                    redirect('gudang');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambah gudang: ' . $response['message']);
                    redirect('gudang/add');
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/gudang_form', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Gudang';
        $data['page'] = 'gudang';

        // Get warehouse detail from API
        $data['warehouse'] = $this->api_model->request('GET', 'warehouses/' . $id);

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Gudang', 'required');
            $this->form_validation->set_rules('code', 'Kode Gudang', 'required');
            $this->form_validation->set_rules('address', 'Alamat', 'required');
            $this->form_validation->set_rules('capacity', 'Kapasitas', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/gudang_form', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $warehouse_data = [
                    'name' => $this->input->post('name'),
                    'code' => $this->input->post('code'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'province' => $this->input->post('province'),
                    'postal_code' => $this->input->post('postal_code'),
                    'capacity' => $this->input->post('capacity'),
                    'description' => $this->input->post('description')
                ];

                $response = $this->api_model->request('PUT', 'warehouses/' . $id, $warehouse_data);

                if (isset($response['success']) && $response['success'] === true) {
                    $this->session->set_flashdata('success', 'Gudang berhasil diupdate');
                    redirect('gudang');
                } else {
                    $this->session->set_flashdata('error', 'Gagal update gudang: ' . $response['message']);
                    redirect('gudang/edit/' . $id);
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/gudang_form', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function delete($id)
    {
        $response = $this->api_model->request('DELETE', 'warehouses/' . $id);

        if (isset($response['success']) && $response['success'] === true) {
            $this->session->set_flashdata('success', 'Gudang berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal hapus gudang: ' . $response['message']);
        }

        redirect('gudang');
    }
}