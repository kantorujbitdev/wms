<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_permission('barang', 'view');
    }

    // ==================== TIPE PRODUK ====================

    public function tipe_produk()
    {
        $this->check_permission('tipe_produk', 'view');
        // Set title
        $this->data['title'] = 'Tipe Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_produk';

        $data = data_login_user();
        // Get product types from API - sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->get_product_type($data);
        $this->data['product_types'] = $this->handle_response($response);

        // Render view
        $this->render_view('pages/barang/tipe_produk/index');
    }

    public function add_tipe_produk()
    {
        $this->check_permission('tipe_produk', 'edit');
        // Set title
        $this->data['title'] = 'Tambah Tipe Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_produk';

        // Render view
        $this->render_view('pages/barang/tipe_produk/form');
    }

    public function edit_tipe_produk($id)
    {
        $this->check_permission('tipe_produk', 'edit');
        // Set title
        $this->data['title'] = 'Edit Tipe Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_produk';
        $data = data_login_user(['id' => $id]);

        // Get product type data from API - sesuaikan dengan nama fungsi di model
        $product_type = $this->Api_model->get_product_type_by_id($data);
        $this->handle_response($product_type);
        $this->data['product_type'] = $product_type['success'] ? $product_type['data'][0] : [];

        // Render view
        $this->render_view('pages/barang/tipe_produk/form');
    }

    public function save_tipe_produk()
    {
        $id = $this->input->post('id');

        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');
        if ($id) {
            // Update existing product type - sesuaikan dengan nama fungsi di model
            $data = data_login_user([
                'id' => $id,
                'code' => $this->input->post('name'),
                'name' => $this->input->post('description')
            ]);
            $response = $this->Api_model->update_product_type($data);
            $message = 'Tipe produk berhasil diperbarui!';
        } else {
            $data = data_login_user([
                'code' => $this->input->post('name'),
                'name' => $this->input->post('description')
            ]);
            // Add new product type - sesuaikan dengan nama fungsi di model
            $response = $this->Api_model->add_product_type($data);
            $message = 'Tipe produk berhasil ditambahkan!';
        }
        $this->handle_response($response, $message);
        redirect('barang/tipe_produk');
    }

    public function delete_tipe_produk($id)
    {
        $this->check_permission('tipe_produk', 'delete');
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = data_login_user([
            'id' => $id
        ]);

        // Sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->delete_product_type($data);
        $this->handle_response($response, 'Tipe produk berhasil dihapus!');
        redirect('barang/tipe_produk');
    }

    // ==================== TIPE SATUAN ====================

    public function tipe_satuan()
    {
        $this->check_permission('tipe_satuan', 'view');
        // Set title
        $this->data['title'] = 'Tipe Satuan';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_satuan';
        $data = data_login_user();

        // Get unit types from API - sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->get_unit_type($data);
        $this->data['unit_types'] = $this->handle_response($response);

        // Render view
        $this->render_view('pages/barang/tipe_satuan/index');
    }

    public function add_tipe_satuan()
    {
        $this->check_permission('tipe_satuan', 'edit');
        // Set title
        $this->data['title'] = 'Tambah Tipe Satuan';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_satuan';

        // Render view
        $this->render_view('pages/barang/tipe_satuan/form');
    }

    public function edit_tipe_satuan($id)
    {
        $this->check_permission('tipe_satuan', 'edit');
        // Set title
        $this->data['title'] = 'Edit Tipe Satuan';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_satuan';

        $data = data_login_user(['id' => $id]);

        // Get unit type data from API - sesuaikan dengan nama fungsi di model
        $unit_type = $this->Api_model->get_unit_type_by_id($data);
        $this->handle_response($unit_type);
        $this->data['unit_type'] = $unit_type['success'] ? $unit_type['data'][0] : [];

        // Render view
        $this->render_view('pages/barang/tipe_satuan/form');
    }

    public function save_tipe_satuan()
    {
        $id = $this->input->post('id');

        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');
        if ($id) {
            // Update existing product type - sesuaikan dengan nama fungsi di model
            $data = data_login_user([
                'id' => $id,
                'code' => $this->input->post('name'),
                'name' => $this->input->post('description')
            ]);
            $response = $this->Api_model->update_unit_type($data);
            $message = 'Tipe produk berhasil diperbarui!';
        } else {
            $data = data_login_user([
                'code' => $this->input->post('name'),
                'name' => $this->input->post('description')
            ]);  // Add new product type - sesuaikan dengan nama fungsi di model
            $response = $this->Api_model->add_unit_type($data);
            $message = 'Tipe produk berhasil ditambahkan!';
        }

        $this->handle_response($response, $message);
        redirect('barang/tipe_satuan');
    }

    public function delete_tipe_satuan($id)
    {
        $this->check_permission('tipe_satuan', 'delete');
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = data_login_user([
            'id' => $id
        ]);

        // Sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->delete_unit_type($data);
        $this->handle_response($response, 'Tipe satuan berhasil dihapus!');
        redirect('barang/tipe_satuan');
    }

    // ==================== PRODUK ====================

    public function index()
    {
        $this->check_permission('produk', 'view');
        // Set title
        $this->data['title'] = 'Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'produk';
        $data = data_login_user();

        // Get products from API - sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->get_barang($data);
        $this->data['products'] = $this->handle_response($response);

        // Get product types for filter - sesuaikan dengan nama fungsi di model
        $product_types = $this->Api_model->get_product_type($data);
        $this->data['product_types'] = $this->handle_response($product_types);

        // Get unit types for filter - sesuaikan dengan nama fungsi di model
        $unit_types = $this->Api_model->get_unit_type($data);
        $this->data['unit_types'] = $this->handle_response($unit_types);
        // Render view
        $this->render_view('pages/barang/produk/index');
    }

    public function add_produk()
    {
        $this->check_permission('produk', 'edit');
        // Set title
        $this->data['title'] = 'Tambah Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'produk';

        // Get product types - sesuaikan dengan nama fungsi di model
        $product_types = $this->Api_model->get_product_type(data_login_user());
        $this->data['product_types'] = $this->handle_response($product_types);

        // Get unit types - sesuaikan dengan nama fungsi di model
        $unit_types = $this->Api_model->get_unit_type(data_login_user());
        $this->data['unit_types'] = $this->handle_response($unit_types);
        // Render view
        $this->render_view('pages/barang/produk/form');
    }

    public function edit_produk($id)
    {
        $this->check_permission('produk', 'edit');
        // Set title
        $this->data['title'] = 'Edit Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'produk';

        // Get product data from API - sesuaikan dengan nama fungsi di model
        $product = $this->Api_model->get_barang(data_login_user(['product_id' => $id]));
        $this->handle_response($product);
        $this->data['product'] = $product['success'] ? $product['data'][0] : [];

        // Get product types - sesuaikan dengan nama fungsi di model
        $product_types = $this->Api_model->get_product_type(data_login_user());
        $this->data['product_types'] = $this->handle_response($product_types);

        // Get unit types - sesuaikan dengan nama fungsi di model
        $unit_types = $this->Api_model->get_unit_type(data_login_user());
        $this->data['unit_types'] = $this->handle_response($unit_types);

        // Render view
        $this->render_view('pages/barang/produk/form');
    }

    public function save_produk()
    {
        $id = $this->input->post('product_id');

        if ($id) {
            // Update existing product - sesuaikan dengan nama fungsi di model
            // Prepare data according to API format
            $data = data_login_user([
                'product_id' => $id,
                'bos_code' => $this->input->post('bos_code'),
                'product_name' => $this->input->post('product_name'),
                'type_id' => $this->input->post('product_type_id'),
                'unit_id' => $this->input->post('unit_type_id'),
            ]);
            $response = $this->Api_model->update_barang($data);
            $message = 'Produk berhasil diperbarui!';
        } else {
            // Add new product - sesuaikan dengan nama fungsi di model
            // Prepare data according to API format
            $data = data_login_user([
                'bos_code' => $this->input->post('bos_code'),
                'product_name' => $this->input->post('product_name'),
                'type_id' => $this->input->post('product_type_id'),
                'unit_id' => $this->input->post('unit_type_id'),
            ]);
            $response = $this->Api_model->add_barang($data);
            $message = 'Produk berhasil ditambahkan!';
        }
        $this->handle_response($response, $message);
        redirect('barang');
    }

    public function delete_produk($id)
    {
        $this->check_permission('produk', 'delete');
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = data_login_user([
            'product_id' => $id
        ]);

        // Sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->delete_barang($data);
        $this->handle_response($response, 'Produk berhasil dihapus!');
        redirect('barang');
    }
}