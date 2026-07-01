<?php
defined('BASEPATH') or exit('No direct script access allowed');

// nilai from_status
// pengiriman dari atau ke customer = 1
// penerimaan dari atau ke supplier = 2
// pengiriman dan pengiriman dari atau ke antar gudang = 3

class Api_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

    }

    public function get_penerimaan($data)
    {
        return api_request('GET', 'penerimaan', $data);
    }
    public function get_penerimaan_by_id($data)
    {
        return api_request('GET', 'penerimaan', $data);
    }
    public function add_penerimaan($data)
    {
        return api_request('POST', 'penerimaan', $data);
    }
    public function update_penerimaan($data)
    {
        return api_request('POST', 'edit penerimaan', $data);
    }

    public function print_penerimaan($data)
    {
        return api_request('POST', 'penerimaan cetak', $data);
    }
    public function print_pengiriman($data)
    {
        return api_request('POST', 'pengiriman cetak', $data);
    }

    public function penerimaan_by_id($data)
    {
        return api_request('GET', 'edit penerimaan', $data);
    }
    public function delete_penerimaan($data)
    {
        return api_request('DELETE', 'penerimaan', $data);
    }

    public function get_list_pengiriman($data)
    {
        return api_request('GET', 'List Kode Pengiriman', $data);
    }

    public function get_list_pengiriman_details($data)
    {
        return api_request('GET', 'Pengiriman Details', $data);
    }

    public function get_pengiriman($data)
    {
        return api_request('GET', 'pengiriman', $data);
    }
    public function get_pengiriman_by_id($data)
    {
        return api_request('GET', 'pengiriman', $data);
    }
    public function add_pengiriman($data)
    {
        return api_request('POST', 'pengiriman', $data);
    }
    public function update_pengiriman($data)
    {
        return api_request('POST', 'edit pengiriman', $data);
    }

    public function pengiriman_by_id($data)
    {
        return api_request('GET', 'edit pengiriman', $data);

    }
    public function history_proyek($data)
    {
        return api_request('GET', 'history proyek', $data);
    }
    public function history_barang($data)
    {
        return api_request('GET', 'history barang', $data);
    }
    public function delete_pengiriman($data)
    {
        return api_request('DELETE', 'pengiriman', $data);
    }

    public function login($data)
    {
        return api_request('POST', 'login', $data);
    }

    // ---- Stock Movement ----
    public function get_card_stok($data)
    {
        return api_request('GET', 'Card Stock', $data);
    }

    // ---- Barang ----
    public function get_barang($data)
    {
        return api_request('GET', 'product', $data);
    }
    public function get_barang_by_id($data)
    {
        return api_request('GET', 'product', $data);
    }
    public function add_barang($data)
    {
        return api_request('POST', 'product', $data);
    }
    public function update_barang($data)
    {
        return api_request('PUT', 'product', $data);
    }
    public function delete_barang($data)
    {
        return api_request('DELETE', 'product', $data);
    }

    // ---- Tipe Produk ----
    public function get_product_type($data)
    {
        return api_request('GET', 'Product Type', $data);
    }
    public function get_product_type_by_id($data)
    {
        return api_request('GET', 'Product Type', $data);
    }
    public function add_product_type($data)
    {
        return api_request('POST', 'Product Type', $data);
    }
    public function update_product_type($data)
    {
        return api_request('PUT', 'Product Type', $data);
    }
    public function delete_product_type($data)
    {
        return api_request('DELETE', 'Product Type', $data);
    }


    // ---- Tipe unit ----
    public function get_unit_type($data)
    {
        return api_request('GET', 'Unit Type', $data);
    }
    public function get_unit_type_by_id($data)
    {
        return api_request('GET', 'Unit Type', $data);
    }
    public function add_unit_type($data)
    {
        return api_request('POST', 'Unit Type', $data);
    }
    public function update_unit_type($data)
    {
        return api_request('PUT', 'Unit Type', $data);
    }
    public function delete_unit_type($data)
    {
        return api_request('DELETE', 'Unit Type', $data);
    }

    // --------- Stok ---------------
    public function get_stock_all($data)
    {
        return api_request('GET', 'stok', $data);
    }
    public function get_stock_by_warehouse($data)
    {
        return api_request('GET', 'stok', $data);
    }

    public function add_stok($data)
    {
        return api_request('POST', 'stok', $data);
    }

    // ---- Gudang ----
    public function get_gudang($data)
    {
        return api_request('GET', 'gudang', $data);
    }
    public function get_all_gudang($data = [])
    {
        return api_request('GET', 'gudang', $data);
    }
    public function get_gudang_id($data)
    {
        return api_request('GET', 'gudang', $data);
    }
    public function get_gudang_id_project($data)
    {
        return api_request('GET', 'gudang', $data);
    }
    public function add_gudang($data)
    {
        return api_request('POST', 'gudang', $data);
    }
    public function update_gudang($data)
    {
        return api_request('PUT', 'gudang', $data);
    }
    public function delete_gudang($data)
    {
        return api_request('DELETE', 'gudang', $data);
    }
    public function get_stok_gudang($id)
    {
        return api_request('GET', 'gudang', [], ['action' => 'stok', 'id' => $id]);
    }

    // ---- Laporan ----
    public function get_laporan_stok($params = [])
    {
        return api_request('GET', 'laporan', [], array_merge(['action' => 'stok'], $params));
    }

    public function get_laporan_masuk($params = [])
    {
        $data = [
            'login_id' => $this->session->userdata('user_id'),
            'login_name' => $this->session->userdata('username'),
        ];
        $final_data = array_merge($data, $params);

        // Mengambil data dari penerimaan (stockin)   
        return api_request('GET', 'penerimaan', $final_data);
    }
    public function get_laporan_keluar($params = [])
    {
        $data = [
            'login_id' => $this->session->userdata('user_id'),
            'login_name' => $this->session->userdata('username'),
        ];
        $final_data = array_merge($data, $params);
        // Mengambil data dari pengiriman (stockout)
        return api_request('GET', 'pengiriman', $final_data);

    }

    public function get_laporan_transaksi($params = [])
    {
        // Menggabungkan data masuk dan keluar
        return api_request('GET', 'laporan', [], array_merge(['action' => 'transaksi'], $params));
    }
    // ---- User ----
    public function get_user($data)
    {
        return api_request('GET', 'user', $data);
    }
    public function get_user_by_id($data)
    {
        return api_request('GET', 'user', $data);
    }
    public function add_user($data)
    {
        return api_request('POST', 'user', $data);
    }
    public function update_user($data)
    {
        return api_request('PUT', 'user', $data);
    }
    public function delete_user($data)
    {
        return api_request('DELETE', 'user', $data);
    }

    // ---- Customer ----
    public function get_customer($data)
    {
        return api_request('GET', 'Customer', $data);
    }
    public function get_customer_by_id($data)
    {
        return api_request('GET', 'Customer', $data);
    }
    public function add_customer($data)
    {
        return api_request('POST', 'Customer', $data);
    }
    public function update_customer($data)
    {
        return api_request('PUT', 'Customer', $data);
    }
    public function delete_customer($data)
    {
        return api_request('DELETE', 'Customer', $data);
    }

    // ---- Supplier ----
    public function get_supplier($data)
    {
        return api_request('GET', 'Supplier', $data);
    }
    public function add_supplier($data)
    {
        return api_request('POST', 'Supplier', $data);
    }
    public function update_supplier($data)
    {
        return api_request('PUT', 'Supplier', $data);
    }
    public function delete_supplier($data)
    {
        return api_request('DELETE', 'Supplier', $data);
    }
}
