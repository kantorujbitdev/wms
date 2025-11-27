<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // ==================== PENERIMAAN DARI PENGGUNA (from_status = 1) ====================
    public function dari_pengguna()
    {
        $this->data['title'] = 'Penerimaan dari Pengguna';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'pengguna';

        $data_login = data_login_user(['from_status' => '1']);
        $response = $this->Api_model->get_penerimaan($data_login);
        $this->data['penerimaan_list'] = $response['success'] ? $response['data'] : [];

        $this->render_view('pages/penerimaan/dari_pengguna');
    }

    public function add_pengguna()
    {
        $this->data['title'] = 'Tambah Penerimaan dari Pengguna';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'pengguna';

        $data_login = data_login_user();

        $warehouse_response = $this->Api_model->get_gudang($data_login);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        $customer_response = $this->Api_model->get_customer($data_login);
        $this->data['customers'] = $customer_response['success'] ? $customer_response['data'] : [];

        $products_response = $this->Api_model->get_barang($data_login);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        $this->data['from_status'] = '1';
        $this->data['form_type'] = 'pengguna';

        $this->render_view('pages/penerimaan/form');
    }

    // ==================== PENERIMAAN DARI SUPPLIER (from_status = 2) ====================
    public function dari_supplier()
    {
        $this->data['title'] = 'Penerimaan dari Supplier';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'supplier';

        $data_login = data_login_user(['from_status' => '2']);
        $response = $this->Api_model->get_penerimaan($data_login);
        $this->data['penerimaan_list'] = $response['success'] ? $response['data'] : [];

        $this->render_view('pages/penerimaan/dari_supplier');
    }

    public function add_supplier()
    {
        $this->data['title'] = 'Tambah Penerimaan dari Supplier';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'supplier';

        $data_login = data_login_user();

        $warehouse_response = $this->Api_model->get_gudang($data_login);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        $supplier_response = $this->Api_model->get_supplier($data_login);
        $this->data['suppliers'] = $supplier_response['success'] ? $supplier_response['data'] : [];

        $products_response = $this->Api_model->get_barang($data_login);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        $this->data['from_status'] = '2';
        $this->data['form_type'] = 'supplier';

        $this->render_view('pages/penerimaan/form');
    }

    // ==================== PENERIMAAN ANTAR GUDANG (from_status = 3) ====================
    public function antar_gudang()
    {
        $this->data['title'] = 'Penerimaan Antar Gudang';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'penerimaan_antar_gudang';

        $data_login = data_login_user(['from_status' => '3']);
        $response = $this->Api_model->get_penerimaan($data_login);
        $this->data['penerimaan_list'] = $response['success'] ? $response['data'] : [];

        $this->render_view('pages/penerimaan/antar_gudang');
    }

    public function add_antar_gudang()
    {
        $this->data['title'] = 'Tambah Penerimaan Antar Gudang';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'penerimaan_antar_gudang';

        $data_login = data_login_user();

        $warehouse_response = $this->Api_model->get_gudang($data_login);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        $products_response = $this->Api_model->get_barang($data_login);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        $this->data['from_status'] = '3';
        $this->data['form_type'] = 'antar_gudang';

        $this->render_view('pages/penerimaan/form');
    }

    // ==================== CREATE PENERIMAAN ====================
    public function create()
    {
        if ($_POST) {
            $data_login = data_login_user();

            $post_data = [
                'stockin_date' => $this->input->post('stockin_date'),
                'stockin_code' => $this->input->post('stockin_code'),
                'stockin_invoice' => $this->input->post('stockin_invoice'),
                'warehouse_id' => $this->input->post('warehouse_id'),
                'from_status' => $this->input->post('from_status'),
                'login_id' => $data_login['login_id'],
                'login_name' => $data_login['login_name'],
                'items' => []
            ];

            // Tambahkan field berdasarkan tipe
            $from_status = $this->input->post('from_status');
            if ($from_status == '1') {
                $post_data['customer_id'] = $this->input->post('customer_id');
            } elseif ($from_status == '2') {
                $post_data['supplier_id'] = $this->input->post('supplier_id');
            } elseif ($from_status == '3') {
                $post_data['from_warehouse_id'] = $this->input->post('from_warehouse_id');
            }

            // Prepare items data
            $product_ids = $this->input->post('product_id');
            $qtys = $this->input->post('qty');
            $notes = $this->input->post('detail_note');

            if (!empty($product_ids)) {
                foreach ($product_ids as $index => $product_id) {
                    if (!empty($product_id) && !empty($qtys[$index])) {
                        $post_data['items'][] = [
                            'stock_id' => $product_id,
                            'qty' => (float) $qtys[$index],
                            'detail_note' => $notes[$index] ?? '',
                            'warehouse_id' => $post_data['warehouse_id'],
                            'login_id' => $data_login['login_id']
                        ];
                    }
                }
            }

            // Send to API
            $response = $this->Api_model->add_penerimaan($post_data);

            if ($response['success']) {
                $this->session->set_flashdata('success', 'Penerimaan barang berhasil ditambahkan');

                // Redirect berdasarkan tipe penerimaan
                if ($from_status == '1') {
                    redirect('penerimaan/dari_pengguna');
                } elseif ($from_status == '2') {
                    redirect('penerimaan/dari_supplier');
                } else {
                    redirect('penerimaan/antar_gudang');
                }
            } else {
                $this->session->set_flashdata('error', $response['message'] ?? 'Gagal menambahkan penerimaan barang');

                // Redirect back based on from_status
                if ($from_status == '1') {
                    redirect('penerimaan/add_pengguna');
                } elseif ($from_status == '2') {
                    redirect('penerimaan/add_supplier');
                } else {
                    redirect('penerimaan/add_antar_gudang');
                }
            }
        }
    }

    // ==================== DETAIL PENERIMAAN ====================
    public function detail($id)
    {
        $this->data['title'] = 'Detail Penerimaan Barang';
        $this->data['active_menu'] = 'penerimaan';

        $data_login = data_login_user(['stockin_id' => $id]);
        $response = $this->Api_model->get_penerimaan_by_id($data_login);

        if ($response['success'] && !empty($response['data'])) {
            $penerimaan = $response['data'][0];
            $this->data['penerimaan'] = $penerimaan;

            // Set active submenu berdasarkan from_status
            if ($penerimaan['from_status'] == '1') {
                $this->data['active_submenu'] = 'pengguna';
                $this->data['title'] = 'Detail Penerimaan dari Pengguna';
            } elseif ($penerimaan['from_status'] == '2') {
                $this->data['active_submenu'] = 'supplier';
                $this->data['title'] = 'Detail Penerimaan dari Supplier';
            } else {
                $this->data['active_submenu'] = 'penerimaan_antar_gudang';
                $this->data['title'] = 'Detail Penerimaan Antar Gudang';
            }
        } else {
            $this->session->set_flashdata('error', 'Data penerimaan tidak ditemukan');
            redirect('penerimaan/dari_supplier');
        }

        $this->render_view('pages/penerimaan/detail');
    }

    // ==================== DELETE PENERIMAAN ====================
    public function delete($id)
    {
        $data_login = data_login_user(['stockin_id' => $id]);
        $response = $this->Api_model->delete_penerimaan($data_login);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Penerimaan berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', $response['message'] ?? 'Gagal menghapus penerimaan');
        }

        redirect('penerimaan/dari_supplier');
    }
}