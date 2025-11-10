<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // ==================== TIPE PRODUK ====================

    public function tipe_produk()
    {
        // Set title
        $this->data['title'] = 'Tipe Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_produk';

        // Get product types from API - sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->get_product_type();
        $this->data['product_types'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/barang/tipe_produk/index');
    }

    public function add_tipe_produk()
    {
        // Set title
        $this->data['title'] = 'Tambah Tipe Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_produk';

        // Render view
        $this->render_view('pages/barang/tipe_produk/form');
    }

    public function edit_tipe_produk($id)
    {
        // Set title
        $this->data['title'] = 'Edit Tipe Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_produk';
        $data['id'] = $id;
        // Get product type data from API - sesuaikan dengan nama fungsi di model
        $product_type = $this->Api_model->get_product_type_by_id($data);
        $this->data['product_type'] = $product_type['success'] ? $product_type['data'] : [];

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
            $data = [
                'id' => $id,
                'code' => $this->input->post('name'),
                'name' => $this->input->post('description'),
                'actionby' => $user_id
            ];
            $response = $this->Api_model->update_product_type($data);
            $message = 'Tipe produk berhasil diperbarui!';
        } else {
            $data = [
                'code' => $this->input->post('name'),
                'name' => $this->input->post('description'),
                'actionby' => $user_id
            ];  // Add new product type - sesuaikan dengan nama fungsi di model
            $response = $this->Api_model->add_product_type($data);
            $message = 'Tipe produk berhasil ditambahkan!';
        }

        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data tipe produk: ' . $response['message']);
        }

        redirect('barang/tipe_produk');
    }

    public function delete_tipe_produk($id)
    {
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = [
            'id' => $id,
            'actionby' => $user_id
        ];

        // Sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->delete_product_type($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Tipe produk berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus tipe produk: ' . $response['message']);
        }

        redirect('barang/tipe_produk');
    }

    // ==================== TIPE SATUAN ====================

    public function tipe_satuan()
    {
        // Set title
        $this->data['title'] = 'Tipe Satuan';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_satuan';

        // Get unit types from API - sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->get_unit_type();
        $this->data['unit_types'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/barang/tipe_satuan/index');
    }

    public function add_tipe_satuan()
    {
        // Set title
        $this->data['title'] = 'Tambah Tipe Satuan';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_satuan';

        // Render view
        $this->render_view('pages/barang/tipe_satuan/form');
    }

    public function edit_tipe_satuan($id)
    {
        // Set title
        $this->data['title'] = 'Edit Tipe Satuan';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'tipe_satuan';
        $data['id'] = $id;
        save_log('edit_tipe_satuan called with ID: ' . $id, 'debug');
        // Get unit type data from API - sesuaikan dengan nama fungsi di model
        $unit_type = $this->Api_model->get_unit_type_by_id($data);
        $this->data['unit_type'] = $unit_type['success'] ? $unit_type['data'] : [];

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
            $data = [
                'id' => $id,
                'code' => $this->input->post('name'),
                'name' => $this->input->post('description'),
                'actionby' => $user_id
            ];
            $response = $this->Api_model->update_unit_type($data);
            $message = 'Tipe produk berhasil diperbarui!';
        } else {
            $data = [
                'code' => $this->input->post('name'),
                'name' => $this->input->post('description'),
                'actionby' => $user_id
            ];  // Add new product type - sesuaikan dengan nama fungsi di model
            $response = $this->Api_model->add_unit_type($data);
            $message = 'Tipe produk berhasil ditambahkan!';
        }


        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data tipe satuan: ' . $response['message']);
        }

        redirect('barang/tipe_satuan');
    }

    public function delete_tipe_satuan($id)
    {
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = [
            'id' => $id,
            'actionby' => $user_id
        ];

        // Sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->delete_unit_type($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Tipe satuan berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus tipe satuan: ' . $response['message']);
        }

        redirect('barang/tipe_satuan');
    }

    // ==================== PRODUK ====================

    public function index()
    {
        // Set title
        $this->data['title'] = 'Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'produk';

        // Get products from API - sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->get_barang();
        $this->data['products'] = $response['success'] ? $response['data'] : [];

        // Get product types for filter - sesuaikan dengan nama fungsi di model
        $product_types = $this->Api_model->get_product_type();
        $this->data['product_types'] = $product_types['success'] ? $product_types['data'] : [];

        // Get unit types for filter - sesuaikan dengan nama fungsi di model
        $unit_types = $this->Api_model->get_unit_type();
        $this->data['unit_types'] = $unit_types['success'] ? $unit_types['data'] : [];

        // Render view
        $this->render_view('pages/barang/produk/index');
    }

    public function add_produk()
    {
        // Set title
        $this->data['title'] = 'Tambah Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'produk';

        // Get product types - sesuaikan dengan nama fungsi di model
        $product_types = $this->Api_model->get_product_type();
        $this->data['product_types'] = $product_types['success'] ? $product_types['data'] : [];

        // Get unit types - sesuaikan dengan nama fungsi di model
        $unit_types = $this->Api_model->get_unit_type();
        $this->data['unit_types'] = $unit_types['success'] ? $unit_types['data'] : [];

        // Render view
        $this->render_view('pages/barang/produk/form');
    }

    public function edit_produk($id)
    {
        // Set title
        $this->data['title'] = 'Edit Produk';
        $this->data['active_menu'] = 'barang';
        $this->data['active_submenu'] = 'produk';

        // Get product data from API - sesuaikan dengan nama fungsi di model
        $product = $this->Api_model->get_barang(['id' => $id]);
        $this->data['product'] = $product['success'] ? $product['data'] : [];

        // Get product types - sesuaikan dengan nama fungsi di model
        $product_types = $this->Api_model->get_product_type();
        $this->data['product_types'] = $product_types['success'] ? $product_types['data'] : [];

        // Get unit types - sesuaikan dengan nama fungsi di model
        $unit_types = $this->Api_model->get_unit_type();
        $this->data['unit_types'] = $unit_types['success'] ? $unit_types['data'] : [];

        // Render view
        $this->render_view('pages/barang/produk/form');
    }

    public function save_produk()
    {
        $id = $this->input->post('id');

        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = [
            'Name' => $this->input->post('name'),
            'code' => $this->input->post('code'),
            'description' => $this->input->post('description'),
            'TypeId' => $this->input->post('product_type_id'),
            'UnitId' => $this->input->post('unit_type_id'),
            'min_stock' => $this->input->post('min_stock'),
            'actionby' => $user_id
        ];
        // code 1
        // name 1
        // product_type_id 1
        // unit_type_id 1
        // min_stock 1
        // description 1
        if ($id) {
            // Update existing product - sesuaikan dengan nama fungsi di model
            $data['id'] = $id;
            $response = $this->Api_model->update_barang($data);
            $message = 'Produk berhasil diperbarui!';
        } else {
            // Add new product - sesuaikan dengan nama fungsi di model
            $response = $this->Api_model->add_barang($data);
            $message = 'Produk berhasil ditambahkan!';
        }

        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data produk: ' . $response['message']);
        }

        redirect('barang');
    }

    public function delete_produk($id)
    {
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = [
            'id' => $id,
            'actionby' => $user_id
        ];

        // Sesuaikan dengan nama fungsi di model
        $response = $this->Api_model->delete_barang($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk: ' . $response['message']);
        }

        redirect('barang');
    }
}