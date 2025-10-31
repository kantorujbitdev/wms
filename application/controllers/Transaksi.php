<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
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
        $data['title'] = 'Daftar Transaksi';
        $data['page'] = 'transaksi';

        // Get transactions data from API
        $data['transactions'] = $this->api_model->request('GET', 'transactions');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/transaksi_list', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Transaksi';
        $data['page'] = 'transaksi';

        // Get transaction detail from API
        $data['transaction'] = $this->api_model->request('GET', 'transactions/' . $id);

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/transaksi_detail', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function masuk()
    {
        $data['title'] = 'Transaksi Barang Masuk';
        $data['page'] = 'transaksi';

        // Get items from API
        $data['items'] = $this->api_model->request('GET', 'items');

        // Get warehouses from API
        $data['warehouses'] = $this->api_model->request('GET', 'warehouses');

        if ($this->input->post()) {
            $this->form_validation->set_rules('item_id', 'Barang', 'required');
            $this->form_validation->set_rules('warehouse_id', 'Gudang', 'required');
            $this->form_validation->set_rules('quantity', 'Jumlah', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('date', 'Tanggal', 'required');
            $this->form_validation->set_rules('supplier', 'Supplier', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/transaksi_masuk', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $transaction_data = [
                    'item_id' => $this->input->post('item_id'),
                    'warehouse_id' => $this->input->post('warehouse_id'),
                    'quantity' => $this->input->post('quantity'),
                    'date' => $this->input->post('date'),
                    'supplier' => $this->input->post('supplier'),
                    'reference_no' => $this->input->post('reference_no'),
                    'notes' => $this->input->post('notes')
                ];

                $response = $this->api_model->request('POST', 'transactions/in', $transaction_data);

                if (isset($response['success']) && $response['success'] === true) {
                    $this->session->set_flashdata('success', 'Transaksi barang masuk berhasil ditambahkan');
                    redirect('transaksi');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambah transaksi: ' . $response['message']);
                    redirect('transaksi/masuk');
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/transaksi_masuk', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function keluar()
    {
        $data['title'] = 'Transaksi Barang Keluar';
        $data['page'] = 'transaksi';

        // Get items from API
        $data['items'] = $this->api_model->request('GET', 'items');

        // Get warehouses from API
        $data['warehouses'] = $this->api_model->request('GET', 'warehouses');

        if ($this->input->post()) {
            $this->form_validation->set_rules('item_id', 'Barang', 'required');
            $this->form_validation->set_rules('warehouse_id', 'Gudang', 'required');
            $this->form_validation->set_rules('quantity', 'Jumlah', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('date', 'Tanggal', 'required');
            $this->form_validation->set_rules('customer', 'Customer', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/transaksi_keluar', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $transaction_data = [
                    'item_id' => $this->input->post('item_id'),
                    'warehouse_id' => $this->input->post('warehouse_id'),
                    'quantity' => $this->input->post('quantity'),
                    'date' => $this->input->post('date'),
                    'customer' => $this->input->post('customer'),
                    'reference_no' => $this->input->post('reference_no'),
                    'notes' => $this->input->post('notes')
                ];

                $response = $this->api_model->request('POST', 'transactions/out', $transaction_data);

                if (isset($response['success']) && $response['success'] === true) {
                    $this->session->set_flashdata('success', 'Transaksi barang keluar berhasil ditambahkan');
                    redirect('transaksi');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambah transaksi: ' . $response['message']);
                    redirect('transaksi/keluar');
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/transaksi_keluar', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function transfer()
    {
        $data['title'] = 'Transfer Stok Antar Gudang';
        $data['page'] = 'transaksi';

        // Get items from API
        $data['items'] = $this->api_model->request('GET', 'items');

        // Get warehouses from API
        $data['warehouses'] = $this->api_model->request('GET', 'warehouses');

        if ($this->input->post()) {
            $this->form_validation->set_rules('item_id', 'Barang', 'required');
            $this->form_validation->set_rules('from_warehouse_id', 'Gudang Asal', 'required');
            $this->form_validation->set_rules('to_warehouse_id', 'Gudang Tujuan', 'required|differs[from_warehouse_id]');
            $this->form_validation->set_rules('quantity', 'Jumlah', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('date', 'Tanggal', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/header', $data);
                $this->load->view('layouts/sidebar', $data);
                $this->load->view('pages/transfer_stok', $data);
                $this->load->view('layouts/footer', $data);
            } else {
                $transfer_data = [
                    'item_id' => $this->input->post('item_id'),
                    'from_warehouse_id' => $this->input->post('from_warehouse_id'),
                    'to_warehouse_id' => $this->input->post('to_warehouse_id'),
                    'quantity' => $this->input->post('quantity'),
                    'date' => $this->input->post('date'),
                    'notes' => $this->input->post('notes')
                ];

                $response = $this->api_model->request('POST', 'transactions/transfer', $transfer_data);

                if (isset($response['success']) && $response['success'] === true) {
                    $this->session->set_flashdata('success', 'Transfer stok berhasil ditambahkan');
                    redirect('transaksi');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambah transfer: ' . $response['message']);
                    redirect('transaksi/transfer');
                }
            }
        } else {
            $this->load->view('layouts/header', $data);
            $this->load->view('layouts/sidebar', $data);
            $this->load->view('pages/transfer_stok', $data);
            $this->load->view('layouts/footer', $data);
        }
    }

    public function delete($id)
    {
        $response = $this->api_model->request('DELETE', 'transactions/' . $id);

        if (isset($response['success']) && $response['success'] === true) {
            $this->session->set_flashdata('success', 'Transaksi berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal hapus transaksi: ' . $response['message']);
        }

        redirect('transaksi');
    }
}