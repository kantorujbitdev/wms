<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Laporan';

        // Render view
        $this->render_view('pages/laporan/index');
    }

    public function stok()
    {
        // Set title
        $this->data['title'] = 'Laporan Stok';

        // Get parameters from filter
        $params = [
            'category' => $this->input->get('category'),
            'warehouse_id' => $this->input->get('warehouse_id'),
            'status' => $this->input->get('status')
        ];

        // Get stock report from API
        $response = $this->Api_model->get_laporan_stok($params);
        $this->data['stock_report'] = $response['success'] ? $response['data'] : [];

        // Get categories from API
        $categories = $this->Api_model->get_barang(['action' => 'categories']);
        $this->data['categories'] = $categories['success'] ? $categories['data'] : [];

        // Get warehouses from API
        $warehouses = $this->Api_model->get_gudang();
        $this->data['warehouses'] = $warehouses['success'] ? $warehouses['data'] : [];

        // Render view
        $this->render_view('pages/laporan/stok');
    }

    public function masuk()
    {
        // Set title
        $this->data['title'] = 'Laporan Barang Masuk';

        // Get parameters from filter
        $params = [
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
            'item_id' => $this->input->get('item_id'),
            'warehouse_id' => $this->input->get('warehouse_id')
        ];

        // Get in report from API
        $response = $this->Api_model->get_laporan_masuk($params);
        $this->data['in_report'] = $response['success'] ? $response['data'] : [];

        // Get items from API
        $items = $this->Api_model->get_barang();
        $this->data['items'] = $items['success'] ? $items['data'] : [];

        // Get warehouses from API
        $warehouses = $this->Api_model->get_gudang();
        $this->data['warehouses'] = $warehouses['success'] ? $warehouses['data'] : [];

        // Render view
        $this->render_view('pages/laporan/masuk');
    }

    public function keluar()
    {
        // Set title
        $this->data['title'] = 'Laporan Barang Keluar';

        // Get parameters from filter
        $params = [
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
            'item_id' => $this->input->get('item_id'),
            'warehouse_id' => $this->input->get('warehouse_id')
        ];

        // Get out report from API
        $response = $this->Api_model->get_laporan_keluar($params);
        $this->data['out_report'] = $response['success'] ? $response['data'] : [];

        // Get items from API
        $items = $this->Api_model->get_barang();
        $this->data['items'] = $items['success'] ? $items['data'] : [];

        // Get warehouses from API
        $warehouses = $this->Api_model->get_gudang();
        $this->data['warehouses'] = $warehouses['success'] ? $warehouses['data'] : [];

        // Render view
        $this->render_view('pages/laporan/keluar');
    }

    public function export_stok()
    {
        // Get parameters from filter
        $params = [
            'category' => $this->input->get('category'),
            'warehouse_id' => $this->input->get('warehouse_id'),
            'status' => $this->input->get('status')
        ];

        // Get stock report from API
        $response = $this->Api_model->get_laporan_stok($params);

        if ($response['success']) {
            // Create CSV content
            $csv_content = "Kode Barang,Nama Barang,Kategori,Satuan,Stok,Stok Minimum,Gudang\n";

            foreach ($response['data'] as $item) {
                $csv_content .= "{$item['code']},{$item['name']},{$item['category']},{$item['unit']},{$item['current_stock']},{$item['min_stock']},{$item['warehouse_name']}\n";
            }

            // Set headers for download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="laporan_stok_' . date('Y-m-d') . '.csv"');

            echo $csv_content;
            exit;
        } else {
            $this->session->set_flashdata('error', 'Gagal mengekspor laporan stok!');
            redirect('laporan/stok');
        }
    }

    public function export_masuk()
    {
        // Get parameters from filter
        $params = [
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
            'item_id' => $this->input->get('item_id'),
            'warehouse_id' => $this->input->get('warehouse_id')
        ];

        // Get in report from API
        $response = $this->Api_model->get_laporan_masuk($params);

        if ($response['success']) {
            // Create CSV content
            $csv_content = "Tanggal,Kode Barang,Nama Barang,Jumlah,Gudang,Catatan\n";

            foreach ($response['data'] as $item) {
                $csv_content .= "{$item['date']},{$item['item_code']},{$item['item_name']},{$item['quantity']},{$item['warehouse_name']},{$item['notes']}\n";
            }

            // Set headers for download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="laporan_barang_masuk_' . date('Y-m-d') . '.csv"');

            echo $csv_content;
            exit;
        } else {
            $this->session->set_flashdata('error', 'Gagal mengekspor laporan barang masuk!');
            redirect('laporan/masuk');
        }
    }

    public function export_keluar()
    {
        // Get parameters from filter
        $params = [
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
            'item_id' => $this->input->get('item_id'),
            'warehouse_id' => $this->input->get('warehouse_id')
        ];

        // Get out report from API
        $response = $this->Api_model->get_laporan_keluar($params);

        if ($response['success']) {
            // Create CSV content
            $csv_content = "Tanggal,Kode Barang,Nama Barang,Jumlah,Gudang,Catatan\n";

            foreach ($response['data'] as $item) {
                $csv_content .= "{$item['date']},{$item['item_code']},{$item['item_name']},{$item['quantity']},{$item['warehouse_name']},{$item['notes']}\n";
            }

            // Set headers for download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="laporan_barang_keluar_' . date('Y-m-d') . '.csv"');

            echo $csv_content;
            exit;
        } else {
            $this->session->set_flashdata('error', 'Gagal mengekspor laporan barang keluar!');
            redirect('laporan/keluar');
        }
    }
}