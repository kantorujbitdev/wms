<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['page'] = 'dashboard';

        // Get summary data from API
        $data['summary'] = $this->api_model->request('GET', 'summary');

        // Get recent transactions
        $data['recent_transactions'] = $this->api_model->request('GET', 'transactions/recent');

        // Get low stock items
        $data['low_stock'] = $this->api_model->request('GET', 'items/lowstock');

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('pages/dashboard', $data);
        $this->load->view('layouts/footer', $data);
    }
}