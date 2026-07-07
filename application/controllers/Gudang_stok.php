<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang_stok extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_permission('gudang_stok', 'view');
    }

    // ============================================================
    // Controller: Gudang_stok (atau nama controller Anda)
    // Perbaikan method index() dan get_stock_by_warehouse()
    // ============================================================

    public function index()
    {
        $this->data['title'] = 'Stok Gudang';
        $this->data['active_menu'] = 'gudang_stok';

        $warehouse_id_session = $this->session->userdata('warehouse_id');
        $base_data = data_login_user();

        // -------------------------
        // Get product list (harus lebih dahulu agar bisa dipakai di bawah)
        // -------------------------
        $products = $this->Api_model->get_barang($base_data);
        $this->data['products_list'] = $this->handle_response($products);

        // -------------------------
        // Ambil filter dari GET
        // -------------------------
        $product_id = $this->input->get('product_id');
        if ($product_id === '' || $product_id === 'all') {
            $product_id = null;
        }

        // Validasi product_id dari URL
        if (!empty($product_id)) {
            $valid = false;
            foreach ($this->data['products_list'] as $p) {
                if ((string) $p['product_id'] === (string) $product_id) {
                    $valid = true;
                    break;
                }
            }
            if (!$valid) {
                $product_id = null;
            }
        }

        $this->data['filter_product_id'] = $product_id;

        // -------------------------
        // Get warehouses untuk dropdown (selalu ambil, agar tidak undefined)
        // -------------------------
        $warehouse_response = $this->Api_model->get_gudang($base_data);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        // -------------------------
        // Logika tampil data stok:
        //
        //   warehouse_id (session) = null + product_id = null  → tidak tampilkan data
        //   warehouse_id (session) = null + product_id = $id   → tampilkan (semua gudang, filter produk)
        //   warehouse_id (session) = $id  + product_id = null  → tampilkan (gudang session, semua produk)
        //   warehouse_id (session) = $id  + product_id = $id   → tampilkan (gudang + produk)
        // -------------------------
        $has_warehouse_session = !empty($warehouse_id_session);
        $has_product_filter = !empty($product_id);
        $should_load = $has_warehouse_session || $has_product_filter;

        $this->data['stoks'] = [];
        $this->data['is_filtered'] = $should_load;

        if ($should_load) {
            $params = ['status' => null];

            if ($has_warehouse_session) {
                $params['warehouse_id'] = $warehouse_id_session;
            }
            if ($has_product_filter) {
                $params['product_id'] = $product_id;
            }

            $stok_response = $this->Api_model->get_stock_all(data_login_user($params));
            $this->data['stoks'] = $this->handle_response($stok_response);
        }

        $this->render_view('pages/stok/index');
    }

    // ============================================================
    // AJAX endpoint: dipanggil saat filter warehouse/produk berubah
    // POST params: warehouse_id, product_id
    //
    // Logika return data:
    //   warehouse_id = '' + product_id = ''  → return kosong (tidak load)
    //   warehouse_id = '' + product_id = $id → load semua gudang, filter produk
    //   warehouse_id = $id + product_id = ''  → load gudang, semua produk
    //   warehouse_id = $id + product_id = $id → load gudang + produk
    // ============================================================

    public function get_stock_by_warehouse()
    {
        $warehouse_id = $this->input->post('warehouse_id');
        $product_id = $this->input->post('product_id');

        // Normalisasi: string kosong → null
        if ($warehouse_id === '' || $warehouse_id === 'all') {
            $warehouse_id = null;
        }
        if ($product_id === '' || $product_id === 'all') {
            $product_id = null;
        }

        // Kedua filter kosong → kembalikan data kosong (jangan load)
        if (empty($warehouse_id) && empty($product_id)) {
            echo json_encode(['success' => true, 'data' => [], 'empty' => true]);
            return;
        }

        // Bangun params request
        $params = ['status' => null];

        if (!empty($warehouse_id)) {
            $params['warehouse_id'] = $warehouse_id;
        }
        if (!empty($product_id)) {
            $params['product_id'] = $product_id;
        }

        $data = data_login_user($params);
        $response = $this->Api_model->get_stock_all($data);

        // Pastikan hanya JSON yang di-output
        $this->output->set_content_type('application/json');
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