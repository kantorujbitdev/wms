<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengiriman extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // ==================== PENGIRIMAN UNTUK PENGGUNAAN (ToStatus = 1) ====================
    public function penggunaan()
    {
        $this->data['title'] = 'Pengiriman untuk Penggunaan';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'penggunaan';

        $data_login = data_login_user(['To_Status' => '1']);
        $response = $this->Api_model->get_pengiriman($data_login);
        $this->data['pengiriman_list'] = $response['success'] ? $response['data'] : [];

        $this->render_view('pages/pengiriman/penggunaan');
    }

    public function add_penggunaan()
    {
        $this->data['title'] = 'Tambah Pengiriman untuk Penggunaan';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'penggunaan';

        $data_login = data_login_user();

        $warehouse_response = $this->Api_model->get_gudang($data_login);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        $customer_response = $this->Api_model->get_customer($data_login);
        $this->data['customers'] = $customer_response['success'] ? $customer_response['data'] : [];

        $products_response = $this->Api_model->get_barang($data_login);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        $this->render_view('pages/pengiriman/add_penggunaan');
    }

    public function create_penggunaan()
    {
        if ($_POST) {
            $data_login = data_login_user();

            $post_data = [
                'StockOutDate' => $this->input->post('StockOutDate'),
                'CustomerID' => $this->input->post('CustomerID'),
                'StockOutNote' => $this->input->post('StockOutNote'),
                'StockOutCode' => $this->input->post('StockOutCode'),
                'WarehouseID' => $this->input->post('WarehouseID'),
                'actionby' => $data_login['login_id'],
                'ToStatus' => '1',
                'Items' => []
            ];

            $product_ids = $this->input->post('Stock_Id');
            $qtys = $this->input->post('Qty');
            $notes = $this->input->post('DetailNote');

            if (!empty($product_ids)) {
                foreach ($product_ids as $index => $product_id) {
                    if (!empty($product_id) && !empty($qtys[$index])) {
                        $post_data['Items'][] = [
                            'Stock_Id' => $product_id,
                            'Qty' => (float) $qtys[$index],
                            'DetailNote' => $notes[$index] ?? ''
                        ];
                    }
                }
            }

            $response = $this->Api_model->add_pengiriman($post_data);

            if ($response['success']) {
                $this->session->set_flashdata('success', 'Pengiriman untuk penggunaan berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', $response['message'] ?? 'Gagal menambahkan pengiriman');
            }

            redirect('pengiriman/penggunaan');
        }
    }

    // ==================== PENGIRIMAN ANTAR GUDANG (ToStatus = 3) ====================
    public function antar_gudang()
    {
        $this->data['title'] = 'Pengiriman Antar Gudang';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'pengiriman_antar_gudang';

        $data_login = data_login_user(['To_Status' => '3']);
        $response = $this->Api_model->get_pengiriman($data_login);
        $this->data['pengiriman_list'] = $response['success'] ? $response['data'] : [];

        $this->render_view('pages/pengiriman/antar_gudang');
    }

    public function add_antar_gudang()
    {
        $this->data['title'] = 'Tambah Pengiriman Antar Gudang';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'pengiriman_antar_gudang';

        $data_login = data_login_user();

        $warehouse_response = $this->Api_model->get_gudang($data_login);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        $products_response = $this->Api_model->get_barang($data_login);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        $this->render_view('pages/pengiriman/add_antar_gudang');
    }

    public function create_antar_gudang()
    {
        if ($_POST) {
            $data_login = data_login_user();

            $post_data = [
                'StockOutDate' => $this->input->post('StockOutDate'),
                'StockOutCode' => $this->input->post('StockOutCode'),
                'WarehouseID' => $this->input->post('WarehouseID'),
                'To_WarehouseID' => $this->input->post('To_WarehouseID'),
                'StockOutNote' => $this->input->post('StockOutNote'),
                'actionby' => $data_login['login_id'],
                'ToStatus' => '3',
                'Items' => []
            ];

            $product_ids = $this->input->post('Stock_Id');
            $qtys = $this->input->post('Qty');
            $notes = $this->input->post('DetailNote');

            if (!empty($product_ids)) {
                foreach ($product_ids as $index => $product_id) {
                    if (!empty($product_id) && !empty($qtys[$index])) {
                        $post_data['Items'][] = [
                            'Stock_Id' => $product_id,
                            'Qty' => (float) $qtys[$index],
                            'DetailNote' => $notes[$index] ?? ''
                        ];
                    }
                }
            }

            $response = $this->Api_model->add_pengiriman($post_data);

            if ($response['success']) {
                $this->session->set_flashdata('success', 'Pengiriman antar gudang berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', $response['message'] ?? 'Gagal menambahkan pengiriman');
            }

            redirect('pengiriman/antar_gudang');
        }
    }

    // ==================== DETAIL & DELETE ====================
    public function detail($id)
    {
        $this->data['title'] = 'Detail Pengiriman';
        $this->data['active_menu'] = 'pengiriman';

        $data_login = data_login_user(['StockOut_Id' => $id]);
        $response = $this->Api_model->get_pengiriman_by_id($data_login);

        if ($response['success'] && !empty($response['data'])) {
            $pengiriman = $response['data'][0];
            $this->data['pengiriman'] = $pengiriman;

            if ($pengiriman['To_Status'] == '3') {
                $this->data['active_submenu'] = 'pengiriman_antar_gudang';
                $this->data['title'] = 'Detail Pengiriman Antar Gudang';
            } else {
                $this->data['active_submenu'] = 'penggunaan';
                $this->data['title'] = 'Detail Pengiriman untuk Penggunaan';
            }
        } else {
            $this->session->set_flashdata('error', 'Data pengiriman tidak ditemukan');
            redirect('pengiriman/penggunaan');
        }

        $this->render_view('pages/pengiriman/detail');
    }

    public function delete($id)
    {
        $data_login = data_login_user(['StockOut_Id' => $id]);
        $response = $this->Api_model->delete_pengiriman($data_login);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Pengiriman berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', $response['message'] ?? 'Gagal menghapus pengiriman');
        }

        redirect('pengiriman/penggunaan');
    }
}