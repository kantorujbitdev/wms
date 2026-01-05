<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang_stok extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_permission('gudang_stok', 'view');
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Stok Gudang';
        $this->data['active_menu'] = 'gudang_stok';

        $warehouse_id = $this->session->userdata('warehouse_id');
        $data = data_login_user();

        // Get warehouses from API
        $response = $this->Api_model->get_gudang($data);
        $this->handle_response($response);
        // Get initial stock data
        if ($warehouse_id == 0 || $warehouse_id == null) {
            $stok_response = $this->Api_model->get_stock_all($data);
            $this->data['warehouses'] = $response['success'] ? $response['data'] : [];
        } else {
            $data_with_warehouse = data_login_user(['warehouse_id' => $warehouse_id]);
            $stok_response = $this->Api_model->get_stock_all($data_with_warehouse);
        }

        $this->data['stoks'] = $this->handle_response($stok_response);

        // Render view
        $this->render_view('pages/stok/index');
    }

    public function get_stock_by_warehouse()
    {
        $warehouse_id = $this->input->post('warehouse_id');

        if (empty($warehouse_id)) {
            // Jika semua gudang dipilih
            $data = data_login_user();
            $response = $this->Api_model->get_stock_all($data);
        } else {
            // Jika gudang spesifik dipilih
            $data = data_login_user(['warehouse_id' => $warehouse_id]);
            $response = $this->Api_model->get_stock_by_warehous($data);
        }
        $this->handle_response($response);
        echo json_encode($response);
    }

    public function add()
    {
        $this->check_permission('gudang_stok', 'edit');
        $this->data['title'] = 'Tambah Stok';

        // Ambil data warehouse & produk dari API
        $data = data_login_user();

        $warehouse = $this->Api_model->get_gudang($data);
        $product = $this->Api_model->get_barang($data);

        $this->data['warehouses'] = $this->handle_response($warehouse);
        $this->data['products'] = $this->handle_response($product);

        $this->render_view('pages/stok/form');
    }

    public function store()
    {
        $warehouse_id = $this->input->post('warehouse_id', true);
        $product_id = $this->input->post('product_id', true);
        $current_stock = $this->input->post('current_stock', true);

        $payload = data_login_user([
            "warehouse_id" => $warehouse_id,
            "product_id" => $product_id,
            "current_stock" => $current_stock
        ]);

        $response = $this->Api_model->add_stok($payload);

        $this->handle_response($response, 'Stok berhasil ditambahkan!');
        redirect('gudang_stok');
    }


}