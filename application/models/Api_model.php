<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

    }

    // ---------------------------------------------------------------------
    // 🔹 Bagian berikut hanya pembungkus endpoint (lebih ringkas & DRY)
    // ---------------------------------------------------------------------

    public function login($data)
    {
        return api_request('POST', 'login', $data);
    }

    // ---- Barang ----
    public function get_barang($params = [])
    {
        return api_request('GET', 'product', [], $params);
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
    public function get_product_type($params = [])
    {
        return api_request('GET', 'Product Type', [], $params);
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
    public function get_unit_type($params = [])
    {
        return api_request('GET', 'Unit Type', [], $params);
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


    // ---- Gudang ----
    public function get_gudang($params = [])
    {
        return api_request('GET', 'gudang', [], $params);
    }
    public function add_gudang($data)
    {
        return api_request('POST', 'gudang', $data);
    }
    public function update_gudang($id, $data)
    {
        return api_request('PUT', 'gudang', $data, ['id' => $id]);
    }
    public function delete_gudang($data)
    {
        return api_request('DELETE', 'gudang', $data);
    }
    public function get_stok_gudang($id)
    {
        return api_request('GET', 'gudang', [], ['action' => 'stok', 'id' => $id]);
    }

    // ---- Transaksi ----
    public function add_transaksi_masuk($data)
    {
        return api_request('POST', 'transaksi', $data, ['action' => 'masuk']);
    }
    public function add_transaksi_keluar($data)
    {
        return api_request('POST', 'transaksi', $data, ['action' => 'keluar']);
    }
    public function add_transfer_stok($data)
    {
        return api_request('POST', 'transaksi', $data, ['action' => 'transfer']);
    }
    public function get_transaksi($params = [])
    {
        return api_request('GET', 'transaksi', [], $params);
    }
    public function delete_transaksi($id)
    {
        return api_request('DELETE', 'transaksi', [], ['id' => $id]);
    }

    // ---- Laporan ----
    public function get_laporan_stok($params = [])
    {
        return api_request('GET', 'laporan', [], array_merge(['action' => 'stok'], $params));
    }
    public function get_laporan_masuk($params = [])
    {
        return api_request('GET', 'laporan', [], array_merge(['action' => 'masuk'], $params));
    }
    public function get_laporan_keluar($params = [])
    {
        return api_request('GET', 'laporan', [], array_merge(['action' => 'keluar'], $params));
    }

    // ---- User ----
    public function get_user($params = [])
    {
        return api_request('GET', 'user', [], $params);
    }
    public function get_user_by_id($id)
    {
        return api_request('GET', 'user', [
            'id' => $id
        ]);
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

}
