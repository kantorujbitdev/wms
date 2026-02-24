<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengiriman extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // ==================== PENGIRIMAN KE PENGGUNA (to_status = 1) ====================
    public function penggunaan()
    {
        $this->data['title'] = 'Pengiriman ke Pengguna';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'penggunaan';
        $warehouse_id = $this->session->userdata('warehouse_id');

        $start_date = date('Y-m-01'); // Tanggal 1 bulan ini
        $end_date = date('Y-m-d');   // Tanggal hari ini

        if ($warehouse_id == 0 || $warehouse_id == null) {
            $data_login = data_login_user([
                'date_start' => $start_date,
                'date_end' => $end_date,
                'to_status' => '1'
            ]);
        } else {
            $data_login = data_login_user([
                'date_start' => $start_date,
                'date_end' => $end_date,
                'to_status' => '1',
                'warehouse_id' => $warehouse_id
            ]);
        }
        $response = $this->Api_model->get_pengiriman($data_login);

        $this->data['pengiriman_list'] = $this->handle_response($response);

        $this->render_view('pages/pengiriman/ke_pengguna');
    }
    public function add_pengguna()
    {
        $this->data['title'] = 'Tambah Pengiriman ke Pengguna';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'penggunaan';

        $data_login = data_login_user();

        // Get user role from session
        $user_role = $this->session->userdata('role');
        $warehouse_id_session = $this->session->userdata('warehouse_id');

        // Get warehouses for superadmin or specific warehouse for others
        if ($user_role == 'superadmin') {
            $warehouse_response = $this->Api_model->get_all_gudang($data_login);
        } else {
            $warehouse_response = $this->Api_model->get_gudang($data_login);
        }
        $this->handle_response($warehouse_response);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        // Get user's warehouse info for display
        $this->data['user_warehouse_id'] = $warehouse_id_session;
        $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');
        $this->data['user_role'] = $user_role;

        // Get customers
        $customer_response = $this->Api_model->get_customer($data_login);
        $this->data['customers'] = $this->handle_response($customer_response);

        // Get products from stock based on warehouse
        if ($user_role == 'superadmin') {
            // For superadmin, initially no products
            $this->data['stocks'] = [];
            $this->data['products'] = [];
        } else {
            // Get stock from current warehouse
            $stock_response = $this->Api_model->get_stock_by_warehous(array_merge($data_login, ['warehouse_id' => $warehouse_id_session]));
            $this->handle_response($stock_response);

            if ($stock_response['success']) {
                $this->data['stocks'] = $stock_response['data'];
                $this->data['products'] = $stock_response['data'];
            } else {
                $this->data['stocks'] = [];
                $this->data['products'] = [];
            }
        }

        // Convert products data to JSON for JavaScript
        $this->data['products_json'] = json_encode($this->data['products']);

        $this->data['to_status'] = '1';
        $this->data['form_type'] = 'pengguna';

        // Get old form data from session if exists (after error)
        $this->data['old_form_data'] = $this->session->flashdata('form_data_1_out');

        $this->render_view('pages/pengiriman/form');
    }

    // ==================== PENGIRIMAN ANTAR GUDANG (to_status = 3) ====================
    public function antar_gudang()
    {
        $this->data['title'] = 'Pengiriman Antar Gudang';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'pengiriman_antar_gudang';

        $start_date = date('Y-m-01'); // Tanggal 1 bulan ini
        $end_date = date('Y-m-d');   // Tanggal hari ini

        $data_login = data_login_user(['to_status' => '3']);
        $warehouse_id_session = $this->session->userdata('warehouse_id');
        $data_request_penerimaan = data_login_user(
            [
                'date_start' => $start_date,
                'date_end' => $end_date,
                'to_status' => 3,
                'transfer_status' => 1,
                'warehouse_id' => $warehouse_id_session ? $warehouse_id_session : null
            ]
        );

        $response = $this->Api_model->get_pengiriman($data_request_penerimaan);
        $this->data['pengiriman_list'] = $this->handle_response($response);

        $this->render_view('pages/pengiriman/antar_gudang');
    }

    public function add_antar_gudang1()
    {
        $this->data['title'] = 'Tambah Pengiriman Antar Gudang';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'pengiriman_antar_gudang';

        $data_login = data_login_user();

        // Get user role from session
        $user_role = $this->session->userdata('role');
        $warehouse_id_session = $this->session->userdata('warehouse_id');

        // Get warehouses for superadmin or specific warehouse for others
        if ($user_role == 'superadmin') {
            $warehouse_response = $this->Api_model->get_all_gudang($data_login);
        } else {
            $warehouse_response = $this->Api_model->get_gudang($data_login);
        }
        $this->handle_response($warehouse_response);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        // Get user's warehouse info for display
        $this->data['user_warehouse_id'] = $warehouse_id_session;
        $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');
        $this->data['user_role'] = $user_role;

        // Get products from stock based on warehouse
        if ($user_role == 'superadmin') {
            // For superadmin, we need to load products after warehouse selection
            $this->data['stocks'] = [];
            $this->data['products'] = [];
        } else {
            // Get stock from current warehouse
            $stock_response = $this->Api_model->get_stock_by_warehous(array_merge($data_login, ['warehouse_id' => $warehouse_id_session]));
            $this->data['stocks'] = $stock_response['success'] ? $stock_response['data'] : [];
            $this->data['products'] = $this->data['stocks'];
        }

        $this->data['to_status'] = '3';
        $this->data['form_type'] = 'antar_gudang';

        // Get old form data from session if exists (after error)
        $this->data['old_form_data'] = $this->session->flashdata('form_data_3_out');

        $this->render_view('pages/pengiriman/form');
    }
    public function add_antar_gudang()
    {
        $this->data['title'] = 'Tambah Pengiriman Antar Gudang';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'pengiriman_antar_gudang';

        $data_login = data_login_user();

        // Get user role from session
        $user_role = $this->session->userdata('role');
        $warehouse_id_session = $this->session->userdata('warehouse_id');

        // Get warehouses for superadmin or specific warehouse for others
        if ($user_role == 'superadmin') {
            $warehouse_response = $this->Api_model->get_all_gudang($data_login);
        } else {
            $warehouse_response = $this->Api_model->get_gudang($data_login);
        }
        $this->handle_response($warehouse_response);
        $this->data['warehouses'] = $warehouse_response['success'] ? $warehouse_response['data'] : [];

        // Get user's warehouse info for display
        $this->data['user_warehouse_id'] = $warehouse_id_session;
        $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');
        $this->data['user_role'] = $user_role;

        // Get products from stock based on warehouse
        if ($user_role == 'superadmin') {
            // For superadmin, initially no products
            $this->data['stocks'] = [];
            $this->data['products'] = [];
        } else {
            // Get stock from current warehouse
            $stock_response = $this->Api_model->get_stock_by_warehous(array_merge($data_login, ['warehouse_id' => $warehouse_id_session]));
            $this->handle_response($stock_response);

            if ($stock_response['success']) {
                $this->data['stocks'] = $stock_response['data'];
                $this->data['products'] = $stock_response['data'];
            } else {
                $this->data['stocks'] = [];
                $this->data['products'] = [];
            }
        }

        // Convert products data to JSON for JavaScript
        $this->data['products_json'] = json_encode($this->data['products']);

        $this->data['to_status'] = '3';
        $this->data['form_type'] = 'antar_gudang';

        // Get old form data from session if exists (after error)
        $this->data['old_form_data'] = $this->session->flashdata('form_data_3_out');

        $this->render_view('pages/pengiriman/form');
    }
    // ==================== AJAX: LOAD PRODUCTS BY WAREHOUSE ====================
    public function load_products_by_warehouse()
    {
        $data_login = data_login_user();
        $warehouse_id = $this->input->post('warehouse_id');

        if ($warehouse_id) {
            $stock_response = $this->Api_model->get_stock_by_warehous(array_merge($data_login, ['warehouse_id' => $warehouse_id]));
            $this->handle_response($stock_response);
            if ($stock_response['success']) {
                echo json_encode([
                    'success' => true,
                    'data' => $stock_response['data']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $stock_response['message'] ?? 'Gagal memuat data stok'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Warehouse ID tidak valid'
            ]);
        }
    }

    // ==================== CREATE PENGIRIMAN ====================
    public function create()
    {
        if ($_POST) {
            $data_login = data_login_user();

            // Get user role and warehouse from session
            $user_role = $this->session->userdata('role');
            $warehouse_id_session = $this->session->userdata('warehouse_id');

            // Determine from_warehouse_id based on user role
            $from_warehouse_id = $warehouse_id_session;
            if ($user_role == 'superadmin') {
                // Superadmin can select source warehouse
                $from_warehouse_id = $this->input->post('from_warehouse_id');
            }

            $post_data = data_login_user([
                'stockout_date' => $this->input->post('stockout_date'),
                'stockout_code' => $this->input->post('stockout_code'),
                // 'stockout_invoice' => $this->input->post('stockout_invoice') ?: '-',
                'stockout_note' => $this->input->post('stockout_note'),
                'from_warehouse_id' => $from_warehouse_id,
                'to_status' => $this->input->post('to_status'),
                'items' => []
            ]);

            // Tambahkan to_id berdasarkan tipe
            $to_status = $this->input->post('to_status');
            $to_id_field = '';

            if ($to_status == '1') {
                $post_data['to_id'] = $this->input->post('customer_id');
                $to_id_field = 'customer_id';
            } elseif ($to_status == '3') {
                $post_data['to_id'] = $this->input->post('to_warehouse_id');
                $to_id_field = 'to_warehouse_id';
            }

            // Prepare items data
            $stock_ids = $this->input->post('stock_id');
            $qtys = $this->input->post('qty');
            $notes = $this->input->post('detail_note');

            $items_data = [];
            if (!empty($stock_ids)) {
                foreach ($stock_ids as $index => $stock_id) {
                    if (!empty($stock_id) && !empty($qtys[$index])) {
                        $items_data[] = [
                            'stock_id' => $stock_id,
                            'qty' => (float) $qtys[$index],
                            'detail_note' => $notes[$index] ?? ''
                        ];

                        $post_data['items'][] = [
                            'stock_id' => $stock_id,
                            'qty' => (float) $qtys[$index],
                            'detail_note' => $notes[$index] ?? ''
                        ];
                    }
                }
            }

            // Send to API
            $response = $this->Api_model->add_pengiriman($post_data);
            $this->handle_response($response);
            if ($response['success']) {
                $this->session->set_flashdata('success', 'Pengiriman barang berhasil ditambahkan');

                // Hapus form data dari session jika berhasil
                $this->session->unset_userdata('form_data_' . $to_status . '_out');

                // Redirect berdasarkan tipe pengiriman
                if ($to_status == '1') {
                    redirect('pengiriman/penggunaan');
                } else {
                    redirect('pengiriman/antar_gudang');
                }
            } else {
                $this->session->set_flashdata('error', $response['message'] ?? 'Gagal menambahkan pengiriman barang');

                // Simpan data form ke session untuk ditampilkan kembali
                $form_data = [
                    'stockout_date' => $this->input->post('stockout_date'),
                    'stockout_code' => $this->input->post('stockout_code'),
                    // 'stockout_invoice' => $this->input->post('stockout_invoice'),
                    'stockout_note' => $this->input->post('stockout_note'),
                    'to_status' => $to_status,
                    'to_id' => $this->input->post($to_id_field),
                    'items' => $items_data
                ];

                // Simpan from_warehouse_id untuk superadmin
                if ($user_role == 'superadmin' && $this->input->post('from_warehouse_id')) {
                    $form_data['from_warehouse_id'] = $this->input->post('from_warehouse_id');
                }

                $this->session->set_flashdata('form_data_' . $to_status . '_out', $form_data);

                // Redirect back based on to_status
                if ($to_status == '1') {
                    redirect('pengiriman/add_pengguna');
                } else {
                    redirect('pengiriman/add_antar_gudang');
                }
            }
        }
    }

    // ==================== EDIT PENGIRIMAN ====================
    public function edit($id)
    {
        $this->data['title'] = 'Edit Pengiriman Barang';
        $this->data['active_menu'] = 'pengiriman';

        $data_login = data_login_user(['stockout_id' => $id]);
        $response = $this->Api_model->pengiriman_by_id($data_login);

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
            $pengiriman = [
                'header' => $normalized_header,
                'detail' => $detail
            ];

            $this->data['pengiriman'] = $pengiriman;
            $to_status = $normalized_header['to_status'];
            $this->data['to_status'] = $to_status;

            // Set active submenu dan title berdasarkan to_status
            if ($to_status == '1') {
                $this->data['active_submenu'] = 'penggunaan';
                $this->data['title'] = 'Edit Pengiriman ke Pengguna';
            } else {
                $this->data['active_submenu'] = 'pengiriman_antar_gudang';
                $this->data['title'] = 'Edit Pengiriman Antar Gudang';
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
            $this->data['warehouses'] = $this->handle_response($warehouse_response);

            // Get customers if to_status = 1
            if ($to_status == '1') {
                $customer_response = $this->Api_model->get_customer($data_login);
                $this->data['customers'] = $customer_response['success'] ? $customer_response['data'] : [];
            }

            // Get products from stock based on warehouse
            $warehouse_id = $normalized_header['warehouse_id'];
            $stock_response = $this->Api_model->get_stock_by_warehous(array_merge($data_login, ['warehouse_id' => $warehouse_id]));
            $stocks = $this->handle_response($stock_response);
            // $stock_response['success'] ? $stock_response['data'] : [];

            // Process detail items
            foreach ($this->data['pengiriman']['detail'] as &$item) {
                // Normalize detail item keys
                $normalized_detail = [];
                foreach ($item as $key => $value) {
                    $normalized_detail[strtolower($key)] = $value;
                }
                $item = $normalized_detail;

                // Find matching stock and add product info
                foreach ($stocks as $stock) {
                    if (
                        isset($stock['stock_id']) && isset($item['stock_id']) &&
                        $stock['stock_id'] == $item['stock_id']
                    ) {
                        // Current stock available (current stock + edited qty)
                        $current_stock = floatval($stock['current_stock']);
                        $edited_qty = floatval($item['qty']);
                        $available_qty = $current_stock + $edited_qty; // Add back the qty being edited

                        $item['available_qty'] = $available_qty;
                        $item['product_code'] = $stock['product_code'] ?? '';
                        $item['product_name'] = $stock['product_name'] ?? '';
                        $item['unit_code'] = $stock['unit_code'] ?? '';
                        $item['unit_name'] = $stock['unit_name'] ?? '';
                        break;
                    }
                }

                // If not found in stocks
                if (!isset($item['available_qty'])) {
                    $item['available_qty'] = $item['qty']; // At least the qty being edited
                    $item['unit_code'] = '';
                    $item['unit_name'] = '';
                }

                // Make sure available_qty is not negative
                if ($item['available_qty'] < 0) {
                    $item['available_qty'] = 0;
                }
            }

            $this->data['products'] = $stocks;

            // Convert products data to JSON for JavaScript
            $this->data['products_json'] = json_encode($stocks);

            $this->render_view('pages/pengiriman/edit');
        } else {
            // Jika header false atau tidak ada data
            $this->session->set_flashdata('error', 'Data pengiriman tidak ditemukan');
            $this->redirect_back();
        }
    }

    // ==================== UPDATE PENGIRIMAN ====================
    public function update($id)
    {
        if ($_POST) {
            $data_login = data_login_user();

            // Get user role and warehouse from session
            $user_role = $this->session->userdata('role');
            $warehouse_id_session = $this->session->userdata('warehouse_id');

            // Determine from_warehouse_id based on user role
            $from_warehouse_id = $warehouse_id_session;
            if ($user_role == 'superadmin') {
                // Superadmin can select source warehouse
                $from_warehouse_id = $this->input->post('from_warehouse_id');
            }

            $post_data = data_login_user([
                'stockout_id' => $id,
                'stockout_date' => $this->input->post('stockout_date'),
                // 'stockout_invoice' => $this->input->post('stockout_invoice') ?: '-',
                'stockout_note' => $this->input->post('stockout_note'),
                'from_warehouse_id' => $from_warehouse_id,
                'to_status' => $this->input->post('to_status'),
                'items' => []
            ]);

            // Tambahkan to_id berdasarkan tipe
            $to_status = $this->input->post('to_status');
            if ($to_status == '1') {
                $post_data['to_id'] = $this->input->post('customer_id');
            } elseif ($to_status == '3') {
                $post_data['to_id'] = $this->input->post('to_warehouse_id');
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

            // Send to API
            $response = $this->Api_model->update_pengiriman($post_data);
            $this->handle_response($response);

            if ($response['success']) {
                $this->handle_response($response, 'Pengiriman barang berhasil diperbarui!');

                // Redirect berdasarkan tipe pengiriman
                if ($to_status == '1') {
                    redirect('pengiriman/penggunaan');
                } else {
                    redirect('pengiriman/antar_gudang');
                }
            } else {
                $this->handle_response($response);
                redirect('pengiriman/edit/' . $id);
            }
        }
    }

    // ==================== DETAIL PENGIRIMAN ====================
    public function detail($id)
    {
        $this->data['title'] = 'Detail Pengiriman Barang';
        $this->data['active_menu'] = 'pengiriman';

        $data_login = data_login_user(['stockout_id' => $id]);
        $response = $this->Api_model->pengiriman_by_id($data_login);

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

            // Normalize detail keys
            $normalized_detail = [];
            foreach ($detail as $item) {
                $normalized_item = [];
                foreach ($item as $key => $value) {
                    $normalized_item[strtolower($key)] = $value;
                }
                $normalized_detail[] = $normalized_item;
            }

            // Format data untuk view
            $pengiriman = [
                'header' => $normalized_header,
                'detail' => $normalized_detail
            ];

            $this->data['pengiriman'] = $pengiriman;

            // Set active submenu dan title berdasarkan to_status
            if ($normalized_header['to_status'] == '1') {
                $this->data['active_submenu'] = 'penggunaan';
                $this->data['title'] = 'Detail Pengiriman ke Pengguna';
            } else {
                $this->data['active_submenu'] = 'pengiriman_antar_gudang';
                $this->data['title'] = 'Detail Pengiriman Antar Gudang';
            }
        } else {
            // Jika header false atau tidak ada data
            $this->handle_response($response);
            $this->redirect_back();
        }

        $this->render_view('pages/pengiriman/detail');
    }

    // ==================== CETAK PENGIRIMAN (SURAT JALAN) ====================
    public function cetak($id)
    {
        $this->data['title'] = 'Surat Jalan';
        $this->data['active_menu'] = 'pengiriman';

        // Ambil data
        $data_login = data_login_user(['stockout_id' => $id]);
        $response = $this->Api_model->pengiriman_by_id($data_login);

        if (isset($response['header']) && $response['header'] !== false) {
            $header = $response['header'];
            $detail = $response['detail'] ?? [];

            // Normalize header keys
            $normalized_header = [];
            foreach ($header as $key => $value) {
                $normalized_header[strtolower($key)] = $value;
            }

            // Normalize detail keys
            $normalized_detail = [];
            foreach ($detail as $item) {
                $normalized_item = [];
                foreach ($item as $key => $value) {
                    $normalized_item[strtolower($key)] = $value;
                }
                $normalized_detail[] = $normalized_item;
            }

            $this->data['pengiriman'] = [
                'header' => $normalized_header,
                'detail' => $normalized_detail
            ];

            // Hitung halaman
            $items_per_page = 12;
            $total_items = count($normalized_detail) + 10; // +10 baris kosong
            $this->data['total_pages'] = ceil($total_items / $items_per_page);

            $this->data['jenis_surat'] = 'SURAT JALAN';

            // Tentukan jenis surat jalan
            if ($normalized_header['to_status'] == '1') {
                $this->data['tipe_pengiriman'] = 'Pengguna';
            } else {
                $this->data['tipe_pengiriman'] = 'Gudang';
            }

            // Load view cetak surat jalan
            $this->load->view('pages/pengiriman/cetak_surat_jalan', $this->data);

        } else {
            $this->handle_response($response);
            redirect('pengiriman');
        }
    }

    // ==================== CETAK LANSGUNG (AUTO PRINT) ====================
    public function cetak_langsung($id)
    {
        // Ambil data
        $data_login = data_login_user(['stockout_id' => $id]);
        $response = $this->Api_model->pengiriman_by_id($data_login);

        if (isset($response['header']) && $response['header'] !== false) {
            $header = $response['header'];
            $detail = $response['detail'] ?? [];

            // Normalize header keys
            $normalized_header = [];
            foreach ($header as $key => $value) {
                $normalized_header[strtolower($key)] = $value;
            }

            // Normalize detail keys
            $normalized_detail = [];
            foreach ($detail as $item) {
                $normalized_item = [];
                foreach ($item as $key => $value) {
                    $normalized_item[strtolower($key)] = $value;
                }
                $normalized_detail[] = $normalized_item;
            }

            $this->data['pengiriman'] = [
                'header' => $normalized_header,
                'detail' => $normalized_detail
            ];

            $this->data['auto_print'] = true;

            // Tentukan jenis surat jalan
            if ($normalized_header['to_status'] == '1') {
                $this->data['jenis_surat'] = 'SURAT JALAN - PENGIRIMAN KE PENGGUNA';
                $this->data['tipe_pengiriman'] = 'Pengguna';
            } else {
                $this->data['jenis_surat'] = 'SURAT JALAN - PENGIRIMAN ANTAR GUDANG';
                $this->data['tipe_pengiriman'] = 'Gudang';
            }

            // Load view cetak surat jalan
            $this->load->view('pages/pengiriman/cetak_surat_jalan', $this->data);

        } else {
            $this->handle_response($response);
            redirect('pengiriman');
        }
    }

    // ==================== DELETE PENGIRIMAN ====================
    public function delete($id)
    {
        $data_login = data_login_user(['stockout_id' => $id]);
        $response = $this->Api_model->delete_pengiriman($data_login);

        $this->handle_response($response, 'Pengiriman berhasil dihapus!');

        $this->redirect_back();
    }

    // Helper function untuk redirect back
    private function redirect_back()
    {
        $referer = $this->input->server('HTTP_REFERER');
        if (strpos($referer, 'penggunaan') !== false || strpos($referer, 'pengguna') !== false) {
            redirect('pengiriman/penggunaan');
        } else if (strpos($referer, 'antar_gudang') !== false) {
            redirect('pengiriman/antar_gudang');
        } else {
            redirect('pengiriman/penggunaan');
        }
    }
}