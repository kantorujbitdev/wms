<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Check if user has permission to access reports
        $role = $this->session->userdata('role');
        if ($role != 'admin' && $role != 'supervisor') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['title'] = 'Laporan';
        $data['page'] = 'laporan';

        // Get warehouses from API
        $data['warehouses'] = $this->api_model->request('GET', 'warehouses');

        // Get items from API
        $data['items'] = $this->api_model->request('GET', 'items');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/laporan', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function stock()
    {
        $data['title'] = 'Laporan Stok';
        $data['page'] = 'laporan';

        $warehouse_id = $this->input->get('warehouse_id');
        $item_id = $this->input->get('item_id');

        $params = [];
        if ($warehouse_id)
            $params['warehouse_id'] = $warehouse_id;
        if ($item_id)
            $params['item_id'] = $item_id;

        // Get stock report from API
        $data['stock_report'] = $this->api_model->request('GET', 'reports/stock', $params);

        // Get warehouses from API
        $data['warehouses'] = $this->api_model->request('GET', 'warehouses');

        // Get items from API
        $data['items'] = $this->api_model->request('GET', 'items');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/laporan_stok', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function transaction_in()
    {
        $data['title'] = 'Laporan Barang Masuk';
        $data['page'] = 'laporan';

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $warehouse_id = $this->input->get('warehouse_id');
        $item_id = $this->input->get('item_id');

        $params = [];
        if ($start_date)
            $params['start_date'] = $start_date;
        if ($end_date)
            $params['end_date'] = $end_date;
        if ($warehouse_id)
            $params['warehouse_id'] = $warehouse_id;
        if ($item_id)
            $params['item_id'] = $item_id;

        // Get transaction in report from API
        $data['transaction_report'] = $this->api_model->request('GET', 'reports/transactions/in', $params);

        // Get warehouses from API
        $data['warehouses'] = $this->api_model->request('GET', 'warehouses');

        // Get items from API
        $data['items'] = $this->api_model->request('GET', 'items');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/laporan_masuk', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function transaction_out()
    {
        $data['title'] = 'Laporan Barang Keluar';
        $data['page'] = 'laporan';

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $warehouse_id = $this->input->get('warehouse_id');
        $item_id = $this->input->get('item_id');

        $params = [];
        if ($start_date)
            $params['start_date'] = $start_date;
        if ($end_date)
            $params['end_date'] = $end_date;
        if ($warehouse_id)
            $params['warehouse_id'] = $warehouse_id;
        if ($item_id)
            $params['item_id'] = $item_id;

        // Get transaction out report from API
        $data['transaction_report'] = $this->api_model->request('GET', 'reports/transactions/out', $params);

        // Get warehouses from API
        $data['warehouses'] = $this->api_model->request('GET', 'warehouses');

        // Get items from API
        $data['items'] = $this->api_model->request('GET', 'items');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/laporan_keluar', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function export_stock()
    {
        $warehouse_id = $this->input->get('warehouse_id');
        $item_id = $this->input->get('item_id');

        $params = [];
        if ($warehouse_id)
            $params['warehouse_id'] = $warehouse_id;
        if ($item_id)
            $params['item_id'] = $item_id;

        // Get stock report from API
        $data['stock_report'] = $this->api_model->request('GET', 'reports/stock', $params);

        // Load the export library
        $this->load->library('export');

        // Export to Excel
        $this->export->to_excel($data['stock_report'], 'laporan_stok_' . date('Y-m-d'));
    }

    public function export_transaction_in()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $warehouse_id = $this->input->get('warehouse_id');
        $item_id = $this->input->get('item_id');

        $params = [];
        if ($start_date)
            $params['start_date'] = $start_date;
        if ($end_date)
            $params['end_date'] = $end_date;
        if ($warehouse_id)
            $params['warehouse_id'] = $warehouse_id;
        if ($item_id)
            $params['item_id'] = $item_id;

        // Get transaction in report from API
        $data['transaction_report'] = $this->api_model->request('GET', 'reports/transactions/in', $params);

        // Load the export library
        $this->load->library('export');

        // Export to Excel
        $this->export->to_excel($data['transaction_report'], 'laporan_barang_masuk_' . date('Y-m-d'));
    }

    public function export_transaction_out()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $warehouse_id = $this->input->get('warehouse_id');
        $item_id = $this->input->get('item_id');

        $params = [];
        if ($start_date)
            $params['start_date'] = $start_date;
        if ($end_date)
            $params['end_date'] = $end_date;
        if ($warehouse_id)
            $params['warehouse_id'] = $warehouse_id;
        if ($item_id)
            $params['item_id'] = $item_id;

        // Get transaction out report from API
        $data['transaction_report'] = $this->api_model->request('GET', 'reports/transactions/out', $params);

        // Load the export library
        $this->load->library('export');

        // Export to Excel
        $this->export->to_excel($data['transaction_report'], 'laporan_barang_keluar_' . date('Y-m-d'));
    }
}