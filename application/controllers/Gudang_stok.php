<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang_stok extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Stok Gudang';
        $this->data['active_menu'] = 'stok gudang';
        // $this->data['active_submenu'] = 'gudang_utama';
        $data = data_login_user();
        // Get warehouses from API
        $response = $this->Api_model->get_gudang($data);
        $this->data['warehouses'] = $response['success'] ? $response['data'] : [];
        $stok_response = $this->Api_model->get_stock_all($data);
        $this->data['stoks'] = $stok_response['success'] ? $stok_response['data'] : [];

        // Render view
        $this->render_view('pages/stok/index');
    }

    public function stock($id)
    {
        // Set title
        $this->data['title'] = 'Stok Gudang';
        $this->data['active_menu'] = 'stok gudang';
        // $this->data['active_submenu'] = 'gudang_utama';
        $data = data_login_user([
            'warehouse_id' => $id,
        ]);

        // Get warehouses from API
        $response = $this->Api_model->get_gudang($data);
        $this->data['warehouses'] = $response['success'] ? $response['data'] : [];
        $stok_response = $this->Api_model->get_stock_by_warehous($data);
        $this->data['stoks'] = $stok_response['success'] ? $stok_response['data'] : [];

        // Render view
        $this->render_view('pages/stok/index');
    }
}