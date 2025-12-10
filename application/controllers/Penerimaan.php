<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_permission('penerimaan', 'view');
    }

    // ==================== PENERIMAAN DARI PENGGUNA (from_status = 1) ====================
    public function dari_pengguna()
    {
        $this->check_permission('pengguna_penerimaan', 'view');
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
        $this->check_permission('pengguna_penerimaan', 'edit');
        $this->data['title'] = 'Tambah Penerimaan dari Pengguna';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'pengguna';

        $data_login = data_login_user();

        // Get user role from session
        $user_role = $this->session->userdata('role');
        $warehouse_id_session = $this->session->userdata('warehouse_id');

        // Get all warehouses for superadmin, otherwise get user's warehouse
        if ($user_role == 'superadmin') {
            $warehouse_response = $this->Api_model->get_all_gudang($data_login);
        } else {
            $warehouse_response = $this->Api_model->get_gudang($data_login);
        }
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        // Get user's warehouse info for display
        $this->data['user_warehouse_id'] = $warehouse_id_session;
        $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');
        $this->data['user_role'] = $user_role;

        $customer_response = $this->Api_model->get_customer($data_login);
        $this->data['customers'] = $customer_response['success'] ? $customer_response['data'] : [];

        $products_response = $this->Api_model->get_barang($data_login);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        $this->data['from_status'] = '1';
        $this->data['form_type'] = 'pengguna';

        // Ambil data form dari session jika ada (setelah error)
        $this->data['old_form_data'] = $this->session->flashdata('form_data_1');

        $this->render_view('pages/penerimaan/form');
    }

    // ==================== PENERIMAAN DARI SUPPLIER (from_status = 2) ====================
    public function dari_supplier()
    {
        $this->check_permission('supplier_penerimaan', 'view');
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
        $this->check_permission('supplier_penerimaan', 'edit');
        $this->data['title'] = 'Tambah Penerimaan dari Supplier';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'supplier';

        $data_login = data_login_user();

        // Get user role from session
        $user_role = $this->session->userdata('role');
        $warehouse_id_session = $this->session->userdata('warehouse_id');

        // Get all warehouses for superadmin, otherwise get user's warehouse
        if ($user_role == 'superadmin') {
            $warehouse_response = $this->Api_model->get_all_gudang($data_login);
        } else {
            $warehouse_response = $this->Api_model->get_gudang($data_login);
        }
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        // Get user's warehouse info for display
        $this->data['user_warehouse_id'] = $warehouse_id_session;
        $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');
        $this->data['user_role'] = $user_role;

        $supplier_response = $this->Api_model->get_supplier($data_login);
        $this->data['suppliers'] = $supplier_response['success'] ? $supplier_response['data'] : [];

        $products_response = $this->Api_model->get_barang($data_login);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        $this->data['from_status'] = '2';
        $this->data['form_type'] = 'supplier';

        // Ambil data form dari session jika ada (setelah error)
        $this->data['old_form_data'] = $this->session->flashdata('form_data_2');

        $this->render_view('pages/penerimaan/form');
    }
    // ==================== PENERIMAAN ANTAR GUDANG (from_status = 3) ====================
    public function antar_gudang()
    {
        $this->check_permission('penerimaan_antar_gudang', 'view');
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
        $this->check_permission('penerimaan_antar_gudang', 'edit');
        $this->data['title'] = 'Tambah Penerimaan Antar Gudang';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'penerimaan_antar_gudang';

        $data_login = data_login_user();

        // Get user role from session
        $user_role = $this->session->userdata('role');
        $warehouse_id_session = $this->session->userdata('warehouse_id');

        // Get all warehouses for superadmin, otherwise get user's warehouse
        if ($user_role == 'superadmin') {
            $warehouse_response = $this->Api_model->get_all_gudang($data_login);
        } else {
            $warehouse_response = $this->Api_model->get_gudang($data_login);
        }
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        // Get user's warehouse info for display
        $this->data['user_warehouse_id'] = $warehouse_id_session;
        $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');
        $this->data['user_role'] = $user_role;

        $products_response = $this->Api_model->get_barang($data_login);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        $this->data['from_status'] = '3';
        $this->data['form_type'] = 'antar_gudang';

        // Ambil data form dari session jika ada (setelah error)
        $this->data['old_form_data'] = $this->session->flashdata('form_data_3');

        $this->render_view('pages/penerimaan/form');
    }

    // ==================== CREATE PENERIMAAN ====================
    public function create()
    {
        if ($_POST) {
            $data_login = data_login_user();

            // Get user role and warehouse from session
            $user_role = $this->session->userdata('role');
            $warehouse_id_session = $this->session->userdata('warehouse_id');

            // Determine to_warehouse_id based on user role
            $to_warehouse_id = $warehouse_id_session;
            if ($user_role == 'superadmin') {
                // Superadmin can select destination warehouse
                $to_warehouse_id = $this->input->post('to_warehouse_id');
            }

            $post_data = [
                'stockin_date' => $this->input->post('stockin_date'),
                'stockin_code' => $this->input->post('stockin_code'),
                'stockin_invoice' => $this->input->post('stockin_invoice'),
                'stockin_note' => $this->input->post('stockin_note'),
                'to_warehouse_id' => $to_warehouse_id,
                'from_status' => $this->input->post('from_status'),
                'login_id' => $data_login['login_id'],
                'login_name' => $data_login['login_name'],
                'items' => []
            ];

            // Tambahkan from_id berdasarkan tipe
            $from_status = $this->input->post('from_status');
            $from_id_field = '';

            if ($from_status == '1') {
                $post_data['from_id'] = $this->input->post('customer_id');
                $from_id_field = 'customer_id';
            } elseif ($from_status == '2') {
                $post_data['from_id'] = $this->input->post('supplier_id');
                $from_id_field = 'supplier_id';
            } elseif ($from_status == '3') {
                $post_data['from_id'] = $this->input->post('from_warehouse_id');
                $from_id_field = 'from_warehouse_id';
            }

            // Prepare items data
            $product_ids = $this->input->post('product_id');
            $qtys = $this->input->post('qty');
            $notes = $this->input->post('detail_note');

            $items_data = [];
            if (!empty($product_ids)) {
                foreach ($product_ids as $index => $product_id) {
                    if (!empty($product_id) && !empty($qtys[$index])) {
                        $items_data[] = [
                            'stock_id' => $product_id,
                            'qty' => (float) $qtys[$index],
                            'detail_note' => $notes[$index] ?? ''
                        ];

                        $post_data['items'][] = [
                            'stock_id' => $product_id,
                            'qty' => (float) $qtys[$index],
                            'detail_note' => $notes[$index] ?? ''
                        ];
                    }
                }
            }

            // Send to API
            $response = $this->Api_model->add_penerimaan($post_data);

            if ($response['success']) {
                $this->session->set_flashdata('success', 'Penerimaan barang berhasil ditambahkan');

                // Hapus form data dari session jika berhasil
                $this->session->unset_userdata('form_data_' . $from_status);

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

                // Simpan data form ke session untuk ditampilkan kembali
                $form_data = [
                    'stockin_date' => $this->input->post('stockin_date'),
                    'stockin_code' => $this->input->post('stockin_code'),
                    'stockin_invoice' => $this->input->post('stockin_invoice'),
                    'stockin_note' => $this->input->post('stockin_note'),
                    'from_status' => $from_status,
                    'from_id' => $this->input->post($from_id_field),
                    'items' => $items_data
                ];

                // Simpan ke warehouse_id untuk superadmin
                if ($user_role == 'superadmin' && $this->input->post('to_warehouse_id')) {
                    $form_data['to_warehouse_id'] = $this->input->post('to_warehouse_id');
                }

                $this->session->set_flashdata('form_data_' . $from_status, $form_data);

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

    // ==================== EDIT PENERIMAAN ====================
    public function edit($id)
    {
        $this->check_permission('penerimaan', 'edit');
        $this->data['title'] = 'Edit Penerimaan Barang';
        $this->data['active_menu'] = 'penerimaan';

        $data_login = data_login_user(['stockin_id' => $id]);
        $response = $this->Api_model->penerimaan_by_id($data_login);

        // Periksa struktur response API
        if (isset($response['header']) && $response['header'] !== false) {
            $header = $response['header'];
            $detail = $response['detail'] ?? [];

            // Normalize header keys to lowercase
            $normalized_header = [];
            foreach ($header as $key => $value) {
                $normalized_key = strtolower($key);
                $normalized_header[$normalized_key] = $value;
            }

            // Format data untuk view
            $penerimaan = [
                'header' => $normalized_header,
                'detail' => $detail
            ];

            $this->data['penerimaan'] = $penerimaan;
            $from_status = $normalized_header['from_status'];
            $this->data['from_status'] = $from_status;

            // Set active submenu dan title berdasarkan from_status
            if ($from_status == '1') {
                $this->data['active_submenu'] = 'pengguna';
                $this->data['title'] = 'Edit Penerimaan dari Pengguna';
            } elseif ($from_status == '2') {
                $this->data['active_submenu'] = 'supplier';
                $this->data['title'] = 'Edit Penerimaan dari Supplier';
            } else {
                $this->data['active_submenu'] = 'penerimaan_antar_gudang';
                $this->data['title'] = 'Edit Penerimaan Antar Gudang';
            }

            // Get user role from session
            $user_role = $this->session->userdata('role');
            $this->data['user_role'] = $user_role;
            $this->data['user_warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');

            // Get warehouses
            if ($user_role == 'superadmin') {
                $warehouse_response = $this->Api_model->get_all_gudang($data_login);
            } else {
                $warehouse_response = $this->Api_model->get_gudang($data_login);
            }
            $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

            // Get customers if from_status = 1
            if ($from_status == '1') {
                $customer_response = $this->Api_model->get_customer($data_login);
                $this->data['customers'] = $customer_response['success'] ? $customer_response['data'] : [];
            }

            // Get suppliers if from_status = 2
            if ($from_status == '2') {
                $supplier_response = $this->Api_model->get_supplier($data_login);
                $this->data['suppliers'] = $supplier_response['success'] ? $supplier_response['data'] : [];
            }

            // Get products
            $products_response = $this->Api_model->get_barang($data_login);
            $products = $products_response['success'] ? $products_response['data'] : [];

            // Add current_stock to detail items from products
            foreach ($this->data['penerimaan']['detail'] as &$item) {
                // Normalize detail item keys
                $normalized_detail = [];
                foreach ($item as $key => $value) {
                    $normalized_key = strtolower($key);
                    $normalized_detail[$normalized_key] = $value;
                }
                $item = $normalized_detail;

                // Add unit code from products
                foreach ($products as $product) {
                    if (isset($item['product_id']) && $item['product_id'] == $product['product_id']) {
                        $item['unit_code'] = $product['unit_code'] ?? '';
                        break;
                    }
                }
            }

            $this->data['products'] = $products;

            // Convert products data to JSON for JavaScript
            $this->data['products_json'] = json_encode($products);

            $this->render_view('pages/penerimaan/edit');
        } else {
            // Jika header false atau tidak ada data
            $this->session->set_flashdata('error', 'Data penerimaan tidak ditemukan');
            $this->redirect_back();
        }
    }

    // ==================== UPDATE PENERIMAAN ====================
    public function update($id)
    {
        if ($_POST) {
            $data_login = data_login_user();

            // Get user role and warehouse from session
            $user_role = $this->session->userdata('role');
            $warehouse_id_session = $this->session->userdata('warehouse_id');

            // Determine to_warehouse_id based on user role
            $to_warehouse_id = $warehouse_id_session;
            if ($user_role == 'superadmin') {
                // Superadmin can select destination warehouse
                $to_warehouse_id = $this->input->post('to_warehouse_id');
            }

            $post_data = [
                'stockin_id' => $id,
                'stockin_date' => $this->input->post('stockin_date'),
                'stockin_invoice' => $this->input->post('stockin_invoice') ?: '-',
                'stockin_note' => $this->input->post('stockin_note'),
                'to_warehouse_id' => $to_warehouse_id,
                'from_status' => $this->input->post('from_status'),
                'login_id' => $data_login['login_id'],
                'login_name' => $data_login['login_name'],
                'items' => []
            ];

            // Tambahkan from_id berdasarkan tipe
            $from_status = $this->input->post('from_status');
            if ($from_status == '1') {
                $post_data['from_id'] = $this->input->post('customer_id');
            } elseif ($from_status == '2') {
                $post_data['from_id'] = $this->input->post('supplier_id');
            } elseif ($from_status == '3') {
                $post_data['from_id'] = $this->input->post('from_warehouse_id');
            }

            // Prepare items data
            $stock_ids = $this->input->post('stock_id');
            $qtys = $this->input->post('qty');
            $notes = $this->input->post('detail_note');

            if (!empty($stock_ids)) {
                foreach ($stock_ids as $index => $stock_id) {
                    if (!empty($stock_id) && !empty($qtys[$index])) {
                        $post_data['items'][] = [
                            'stock_id' => $stock_id,
                            'qty' => (float) $qtys[$index],
                            'detail_note' => $notes[$index] ?? ''
                        ];
                    }
                }
            }

            // Send to API - perlu endpoint update_penerimaan
            $response = $this->Api_model->update_penerimaan($post_data);

            if ($response['success']) {
                $this->session->set_flashdata('success', 'Penerimaan barang berhasil diperbarui');

                // Redirect berdasarkan tipe penerimaan
                if ($from_status == '1') {
                    redirect('penerimaan/dari_pengguna');
                } elseif ($from_status == '2') {
                    redirect('penerimaan/dari_supplier');
                } else {
                    redirect('penerimaan/antar_gudang');
                }
            } else {
                $this->session->set_flashdata('error', $response['message'] ?? 'Gagal memperbarui penerimaan barang');
                redirect('penerimaan/edit/' . $id);
            }
        }
    }


    // ==================== DETAIL PENERIMAAN ====================
    public function detail($id)
    {
        $this->check_permission('penerimaan', 'view');
        $this->data['title'] = 'Detail Penerimaan Barang';
        $this->data['active_menu'] = 'penerimaan';

        $data_login = data_login_user(['stockin_id' => $id]);
        $response = $this->Api_model->penerimaan_by_id($data_login);

        // Periksa struktur response API
        if (isset($response['header']) && $response['header'] !== false) {
            $header = $response['header'];
            $detail = $response['detail'] ?? [];

            // Format data untuk view
            $penerimaan = [
                'header' => $header,
                'detail' => $detail
            ];

            $this->data['penerimaan'] = $penerimaan;

            // Set active submenu dan title berdasarkan from_status
            if ($header['from_Status'] == '1') {
                $this->data['active_submenu'] = 'pengguna';
                $this->data['title'] = 'Detail Penerimaan dari Pengguna';
            } elseif ($header['from_Status'] == '2') {
                $this->data['active_submenu'] = 'supplier';
                $this->data['title'] = 'Detail Penerimaan dari Supplier';
            } else {
                $this->data['active_submenu'] = 'penerimaan_antar_gudang';
                $this->data['title'] = 'Detail Penerimaan Antar Gudang';
            }
        } else {
            // Jika header false atau tidak ada data
            $this->session->set_flashdata('error', 'Data penerimaan tidak ditemukan');
            $this->redirect_back();
        }

        $this->render_view('pages/penerimaan/detail');
    }

    // Helper function untuk redirect back
    private function redirect_back()
    {
        $referer = $this->input->server('HTTP_REFERER');
        if (strpos($referer, 'pengguna') !== false) {
            redirect('penerimaan/dari_pengguna');
        } elseif (strpos($referer, 'supplier') !== false) {
            redirect('penerimaan/dari_supplier');
        } else {
            redirect('penerimaan/antar_gudang');
        }
    }

    // ==================== DELETE PENERIMAAN ====================
    public function delete($id)
    {
        $this->check_permission('penerimaan', 'delete');
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