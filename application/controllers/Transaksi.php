<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Riwayat Transaksi';
        $this->data['active_menu'] = 'transaksi';
        $this->data['active_submenu'] = 'riwayat';

        // Get transactions from API
        $response = $this->Api_model->get_transaksi();
        $this->data['transactions'] = $this->handle_response($response);

        // Render view
        $this->render_view('pages/transaksi/index');
    }

    public function masuk()
    {
        // Set title
        $this->data['title'] = 'Barang Masuk';
        $this->data['active_menu'] = 'transaksi';
        $this->data['active_submenu'] = 'masuk';

        // Get items from API
        $items = $this->Api_model->get_barang(data_login_user());
        $this->data['items'] = $items['success'] ? $items['data'] : [];

        // Get warehouses from API
        $warehouses = $this->Api_model->get_gudang(data_login_user());
        $this->data['warehouses'] = $this->handle_response($warehouses);
        $warehouses['success'] ? $warehouses['data'] : [];

        // Render view
        $this->render_view('pages/transaksi/masuk');
    }

    public function keluar()
    {
        // Set title
        $this->data['title'] = 'Barang Keluar';
        $this->data['active_menu'] = 'transaksi';
        $this->data['active_submenu'] = 'keluar';

        // Get items from API
        $items = $this->Api_model->get_barang(data_login_user());
        $this->data['items'] = $this->handle_response($items);

        // Get warehouses from API
        $warehouses = $this->Api_model->get_gudang(data_login_user());
        $this->data['warehouses'] = $this->handle_response($warehouses);
        // Render view
        $this->render_view('pages/transaksi/keluar');
    }

    public function transfer()
    {
        // Set title
        $this->data['title'] = 'Transfer Stok';
        $this->data['active_menu'] = 'transaksi';
        $this->data['active_submenu'] = 'transfer';

        // Get items from API
        $items = $this->Api_model->get_barang(data_login_user());
        $this->data['items'] = $this->handle_response($items);

        // Get warehouses from API
        $warehouses = $this->Api_model->get_gudang(data_login_user());
        $this->data['warehouses'] = $this->handle_response($warehouses);

        // Render view
        $this->render_view('pages/transaksi/transfer');
    }

    public function save_masuk()
    {
        $data = [
            'item_id' => $this->input->post('item_id'),
            'warehouse_id' => $this->input->post('warehouse_id'),
            'quantity' => $this->input->post('quantity'),
            'date' => $this->input->post('date'),
            'notes' => $this->input->post('notes')
        ];

        $response = $this->Api_model->add_transaksi_masuk($data);
        $this->handle_response($response, 'Transaksi barang masuk berhasil disimpan!');
        redirect('transaksi');
    }

    public function save_keluar()
    {
        $data = [
            'item_id' => $this->input->post('item_id'),
            'warehouse_id' => $this->input->post('warehouse_id'),
            'quantity' => $this->input->post('quantity'),
            'date' => $this->input->post('date'),
            'notes' => $this->input->post('notes')
        ];

        $response = $this->Api_model->add_transaksi_keluar($data);
        $this->handle_response($response, 'Transaksi barang keluar berhasil disimpan!');

        redirect('transaksi');
    }

    public function save_transfer()
    {
        $data = [
            'item_id' => $this->input->post('item_id'),
            'from_warehouse_id' => $this->input->post('from_warehouse_id'),
            'to_warehouse_id' => $this->input->post('to_warehouse_id'),
            'quantity' => $this->input->post('quantity'),
            'date' => $this->input->post('date'),
            'notes' => $this->input->post('notes')
        ];

        $response = $this->Api_model->add_transfer_stok($data);
        $this->handle_response($response, 'Transfer stok berhasil disimpan!');
        redirect('transaksi');
    }

    public function delete($id)
    {
        $response = $this->Api_model->delete_transaksi($id);
        $this->handle_response($response, 'Transaksi berhasil dihapus!');
        redirect('transaksi');
    }
}