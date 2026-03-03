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

        $warehouse_id_session = $this->session->userdata('warehouse_id');
        $data_login = data_login_user();
        if ($warehouse_id_session == 0 || $warehouse_id_session === null) {
            $stok_response = $this->Api_model->get_stock_all($data_login);
        } else {
            $stok_response = $this->Api_model->get_stock_by_warehous(array_merge($data_login, ['warehouse_id' => $warehouse_id_session]));
        }

        // Get stock data from API
        $stocks = $stok_response['success'] ? $stok_response['data'] : [];

        $this->data['stoks'] = $stocks;

        // Prepare data for charts
        $this->data['chart_data'] = $this->prepare_stock_chart_data($stocks);

        // Get low stock items
        // $low_stock = $this->Api_model->get_barang(data_login_user(['action' => 'low_stock', 'limit' => 5]));
        // $this->data['low_stock_items'] = $low_stock['success'] ? $low_stock['data'] : [];

        // Render view
        $this->render_view('pages/dashboard');
    }


    /**
     * Prepare data for stock charts
     */
    private function prepare_stock_chart_data($stocks)
    {
        $chart_data = [
            'by_warehouse' => [],
            'by_category' => [],
            'stock_status' => [
                'normal' => 0,
                'menipis' => 0,
                'kosong' => 0
            ],
            'top_products' => []
        ];

        // Process each stock item
        foreach ($stocks as $stock) {
            $min_stock = isset($stock['min_stock']) ? (float) $stock['min_stock'] : 0;
            $current_stock = isset($stock['current_stock']) ? (float) $stock['current_stock'] : 0;
            $warehouse_name = $stock['warehouse_name'] ?? 'Unknown';
            $category_name = $stock['product_type_name'] ?? 'Unknown';
            $product_name = $stock['product_name'] ?? 'Unknown';

            // By warehouse
            if (!isset($chart_data['by_warehouse'][$warehouse_name])) {
                $chart_data['by_warehouse'][$warehouse_name] = 0;
            }
            $chart_data['by_warehouse'][$warehouse_name] += $current_stock;

            // By category
            if (!isset($chart_data['by_category'][$category_name])) {
                $chart_data['by_category'][$category_name] = 0;
            }
            $chart_data['by_category'][$category_name] += $current_stock;

            // Stock status
            if ($current_stock <= 0) {
                $chart_data['stock_status']['kosong']++;
            } elseif ($current_stock <= $min_stock) {
                $chart_data['stock_status']['menipis']++;
            } else {
                $chart_data['stock_status']['normal']++;
            }

            // Top products (highest stock)
            $chart_data['top_products'][] = [
                'name' => $product_name,
                'stock' => $current_stock,
                'warehouse' => $warehouse_name
            ];
        }

        // Sort top products by stock descending and take top 10
        usort($chart_data['top_products'], function ($a, $b) {
            return $b['stock'] <=> $a['stock'];
        });
        $chart_data['top_products'] = array_slice($chart_data['top_products'], 0, 10);

        return $chart_data;
    }
}