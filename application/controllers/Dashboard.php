<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Dashboard';

        // Get summary data from API
        $summary = api_request('GET', 'product', ['action' => 'summary']);
        $this->data['summary'] = $summary['success'] ? $summary['data'] : [];

        // Get recent transactions
        $transactions = $this->Api_model->get_transaksi(['limit' => 5]);
        $this->data['recent_transactions'] = $transactions['success'] ? $transactions['data'] : [];

        // Get low stock items
        $low_stock = $this->Api_model->get_barang(['action' => 'low_stock', 'limit' => 5]);
        $this->data['low_stock_items'] = $low_stock['success'] ? $low_stock['data'] : [];

        // Render view
        $this->render_view('pages/dashboard');
    }
}