<?php
defined('BASEPATH') or exit('No direct script access allowed');
// Import namespace PHP Spreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Laporan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_permission('laporan', 'view');
    }

    public function stok()
    {
        $this->check_permission('laporan', 'view');
        // Set title
        $this->data['title'] = 'Laporan Stok';
        $this->data['active_menu'] = 'laporan';
        $this->data['active_submenu'] = 'laporan_stok';

        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');
        $data = data_login_user();

        // Get filter parameters
        $filter_warehouse = $this->input->get('warehouse_id');
        $filter_product = $this->input->get('product_id');
        $filter_category = $this->input->get('category_id');
        $filter_status = $this->input->get('status'); // stok_normal, stok_menipis, stok_kosong

        // Get warehouses for filter (superadmin can see all)
        if ($user_role == 'superadmin') {
            $warehouse_response = $this->Api_model->get_all_gudang($data);
        } else {
            $warehouse_response = $this->Api_model->get_gudang($data);
        }
        $this->data['warehouses'] = $this->handle_response($warehouse_response);

        // Get all products for filter
        $products_response = $this->Api_model->get_barang($data);
        $this->data['products'] = $this->handle_response($products_response);

        // Get product categories for filter
        $categories_response = $this->Api_model->get_product_type($data);
        $this->data['categories'] = $this->handle_response($categories_response);
        // Prepare filter data for API
        $filter_data = $data;

        // Apply warehouse filter
        if ($filter_warehouse) {
            $filter_data['warehouse_id'] = $filter_warehouse;
        } elseif ($warehouse_id && $user_role != 'superadmin') {
            // Non-superadmin default to their warehouse
            $filter_data['warehouse_id'] = $warehouse_id;
        }

        // Apply product filter
        if ($filter_product) {
            $filter_data['product_id'] = $filter_product;
        }

        // Apply category filter
        if ($filter_category) {
            $filter_data['category_id'] = $filter_category;
        }

        // Get stock data from API
        $stok_response = $this->Api_model->get_stock_all($filter_data);
        $stocks = $this->handle_response($stok_response);

        $this->data['stoks'] = $stocks;

        // Pass filter values back to view
        $this->data['filter_warehouse_id'] = $filter_warehouse;
        $this->data['filter_product_id'] = $filter_product;
        $this->data['filter_category_id'] = $filter_category;
        $this->data['filter_status'] = $filter_status;
        $this->data['user_role'] = $user_role;
        $this->data['user_warehouse_id'] = $warehouse_id;
        $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');

        // Render view
        $this->render_view('pages/laporan/stok');
    }

    public function stok_card()
    {
        $this->check_permission('laporan', 'view');
        // Set title
        $this->data['title'] = 'Stock Card';
        $this->data['active_menu'] = 'laporan';
        $this->data['active_submenu'] = 'laporan_stok_card';

        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');
        $data = data_login_user();

        // Get filter parameters
        $filter_warehouse = $this->input->get('warehouse_id');
        $filter_product = $this->input->get('stock_id');
        $filter_date_start = $this->input->get('date_start');
        $filter_date_end = $this->input->get('date_end');

        // Get warehouses for filter (superadmin can see all)
        if ($user_role == 'superadmin') {
            $warehouse_response = $this->Api_model->get_all_gudang($data);
            $products_response = $this->Api_model->get_stock_all($data);
            $this->data['warehouses'] = $this->handle_response($warehouse_response);
        } else {
            // $warehouse_response = $this->Api_model->get_gudang($data);
            // $this->data['warehouses'] = $this->handle_response($warehouse_response);
            $products_response = $this->Api_model->get_stock_by_warehous(data_login_user(['warehouse_id' => $warehouse_id]));
        }

        // Get all products for filter
        // $products_response = $this->Api_model->get_stock_all($data);
        $this->data['products'] = $this->handle_response($products_response);

        // Prepare filter data for API
        $filter_data = $data;

        // Apply filters
        if ($filter_warehouse) {
            $filter_data['warehouse_id'] = $filter_warehouse;
        } elseif ($warehouse_id && $user_role != 'superadmin') {
            // Non-superadmin default to their warehouse
            $filter_data['warehouse_id'] = $warehouse_id;
        }

        if ($filter_product) {
            $filter_data['stock_id'] = $filter_product;
        }

        if ($filter_date_start) {
            $filter_data['date_start'] = $filter_date_start;
        } else {
            // Default: start of current month
            $filter_data['date_start'] = date('Y-m-01');
        }

        if ($filter_date_end) {
            $filter_data['date_end'] = $filter_date_end;
        } else {
            // Default: current date
            $filter_data['date_end'] = date('Y-m-d');
        }

        // Get stock card data from API
        $stock_card_response = $this->Api_model->get_card_stok($filter_data);
        $stock_cards = [];

        if (isset($stock_card_response['success']) && $stock_card_response['success']) {
            $stock_cards = $stock_card_response['data'];
        }

        $this->data['stock_cards'] = $stock_cards;

        // Pass filter values back to view
        $this->data['filter_warehouse_id'] = $filter_warehouse;
        $this->data['filter_stock_id'] = $filter_product;
        $this->data['filter_date_start'] = $filter_date_start;
        $this->data['filter_date_end'] = $filter_date_end;
        $this->data['user_role'] = $user_role;
        $this->data['user_warehouse_id'] = $warehouse_id;
        $this->data['user_warehouse_name'] = $this->session->userdata('warehouse_name');

        // Render view
        $this->render_view('pages/laporan/stok_card');
    }

    public function export_stok_card()
    {
        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');
        $data = data_login_user();

        // Get filter parameters
        $filter_warehouse = $this->input->get('warehouse_id');
        $filter_product = $this->input->get('stock_id');
        $filter_date_start = $this->input->get('date_start');
        $filter_date_end = $this->input->get('date_end');

        // Prepare filter data for API
        $filter_data = $data;

        // Apply filters
        if ($filter_warehouse) {
            $filter_data['warehouse_id'] = $filter_warehouse;
        } elseif ($warehouse_id && $user_role != 'superadmin') {
            $filter_data['warehouse_id'] = $warehouse_id;
        }

        if ($filter_product) {
            $filter_data['stock_id'] = $filter_product;
        }

        if ($filter_date_start) {
            $filter_data['date_start'] = $filter_date_start;
        } else {
            $filter_data['date_start'] = date('Y-m-01');
        }

        if ($filter_date_end) {
            $filter_data['date_end'] = $filter_date_end;
        } else {
            $filter_data['date_end'] = date('Y-m-d');
        }

        // Get stock card data from API
        $response = $this->Api_model->get_card_stok($filter_data);

        if ($response['success'] && !empty($response['data'])) {
            // Create new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set title
            $sheet->setCellValue('A1', 'STOCK CARD REPORT');
            $sheet->mergeCells('A1:I1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Set period info
            $sheet->setCellValue('A2', 'Periode: ' . $filter_data['date_start'] . ' s/d ' . $filter_data['date_end']);
            $sheet->mergeCells('A2:I2');
            $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Set headers sesuai dengan view
            $headers = [
                'No',
                'Tanggal',
                'No. Referensi',
                'Kode Barang',
                'Nama Barang',
                'Qty',
                'Stok Awal',
                'Stok Akhir',
                'Ket'
            ];

            $sheet->fromArray($headers, NULL, 'A4');

            // Style headers
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ]
            ];
            $sheet->getStyle('A4:I4')->applyFromArray($headerStyle);

            // Fill data sesuai dengan view
            $row = 5;
            $no = 1;

            foreach ($response['data'] as $item) {
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $item['movement_date'] ?? '');
                $sheet->setCellValue('C' . $row, $item['movement_refno'] ?? '');
                $sheet->setCellValue('D' . $row, $item['product_code'] ?? '');
                $sheet->setCellValue('E' . $row, $item['product_name'] ?? '');

                // Format Qty dengan tanda + atau - berdasarkan movement_type
                $qty_value = ($item['movement_type'] ?? '') == '1' ? '+' . ($item['qty'] ?? '0') : '-' . ($item['qty'] ?? '0');
                $sheet->setCellValue('F' . $row, $qty_value);

                $sheet->setCellValue('G' . $row, $item['begin_stock'] ?? '0');
                $sheet->setCellValue('H' . $row, $item['last_stock'] ?? '0');
                $sheet->setCellValue('I' . $row, $item['movement_note'] ?? '');

                // Apply styling untuk Qty berdasarkan movement_type
                $qty_cell = 'H' . $row;
                if (($item['movement_type'] ?? '') == '1') {
                    // MASUK - warna hijau
                    $sheet->getStyle($qty_cell)->getFont()->getColor()->setARGB('FF006400'); // Dark Green
                } else {
                    // KELUAR - warna merah
                    $sheet->getStyle($qty_cell)->getFont()->getColor()->setARGB('FFFF0000'); // Red
                }

                // Center align untuk kolom tertentu
                $center_align_columns = ['A', 'F', 'G', 'H'];
                foreach ($center_align_columns as $col) {
                    $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Bold untuk Stok Akhir
                $sheet->getStyle('F' . $row)->getFont()->setBold(true);
                $sheet->getStyle('G' . $row)->getFont()->setBold(true);
                $sheet->getStyle('H' . $row)->getFont()->setBold(true);

                // Apply borders to data rows
                $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ]
                ]);

                $row++;
                $no++;
            }

            // Tambahkan total transaksi di footer
            $footer_row = $row + 1;
            $sheet->setCellValue('A' . $footer_row, 'Total Transaksi:');
            $sheet->mergeCells('A' . $footer_row . ':E' . $footer_row);
            $sheet->getStyle('A' . $footer_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('A' . $footer_row)->getFont()->setBold(true);

            $sheet->setCellValue('F' . $footer_row, count($response['data']));
            $sheet->mergeCells('F' . $footer_row . ':I' . $footer_row);
            $sheet->getStyle('F' . $footer_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $footer_row)->getFont()->setBold(true);

            // Apply borders untuk footer
            $sheet->getStyle('A' . $footer_row . ':I' . $footer_row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ]
            ]);

            // Auto size columns
            foreach (range('A', 'I') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Set wrap text untuk kolom yang mungkin panjang
            $sheet->getStyle('C')->getAlignment()->setWrapText(true); // No. Referensi
            $sheet->getStyle('E')->getAlignment()->setWrapText(true); // Nama Barang

            // Set headers for download
            $filename = 'stock_card_' . date('Ymd_His') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            $this->session->set_flashdata('error', 'Tidak ada data stock card untuk diekspor!');
            redirect('laporan/stok_card');
        }
    }

    public function export_stok()
    {
        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');
        $data = data_login_user();

        // Get filter parameters
        $filter_warehouse = $this->input->get('warehouse_id');
        $filter_product = $this->input->get('product_id');
        $filter_category = $this->input->get('category_id');
        $filter_status = $this->input->get('status');

        // Prepare filter data
        $filter_data = $data;

        if ($filter_warehouse) {
            $filter_data['warehouse_id'] = $filter_warehouse;
        } elseif ($warehouse_id && $user_role != 'superadmin') {
            $filter_data['warehouse_id'] = $warehouse_id;
        }

        if ($filter_product) {
            $filter_data['product_id'] = $filter_product;
        }

        if ($filter_category) {
            $filter_data['category_id'] = $filter_category;
        }

        // Get stock report from API
        $response = $this->Api_model->get_stock_all($filter_data);

        if ($response['success'] && !empty($response['data'])) {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator($this->session->userdata('name') ?: 'WMS System')
                ->setLastModifiedBy($this->session->userdata('name') ?: 'WMS System')
                ->setTitle('Laporan Stok Barang')
                ->setSubject('Laporan Stok Barang per Gudang')
                ->setDescription('Dokumen ini berisi laporan stok barang per gudang')
                ->setKeywords('stok barang gudang laporan')
                ->setCategory('Laporan');

            // Set default font
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

            // =================== TITLE AND HEADER ===================
            $sheet->setCellValue('A1', 'LAPORAN STOK BARANG');
            $sheet->mergeCells('A1:I1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Company/System Info
            $sheet->setCellValue('A2', 'Sistem Warehouse Management');
            $sheet->mergeCells('A2:I2');
            $sheet->getStyle('A2')->getFont()->setSize(11);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Filter Info
            $row = 4;
            $sheet->setCellValue('A' . $row, 'Tanggal Export: ' . date('d-m-Y H:i:s'));

            // Get filter names if ID provided
            $filter_info = [];

            if ($filter_warehouse) {
                $warehouse_name = $this->get_warehouse_name($filter_warehouse);
                $sheet->setCellValue('A' . ($row + 1), 'Gudang: ' . $warehouse_name);
                $filter_info[] = 'Gudang: ' . $warehouse_name;
            } else {
                $sheet->setCellValue('A' . ($row + 1), 'Semua Gudang');
            }

            if ($filter_product) {
                $product_name = $this->get_product_name($filter_product);
                $sheet->setCellValue('A' . ($row + 2), 'Barang: ' . $product_name);
                $filter_info[] = 'Barang: ' . $product_name;
            }

            if ($filter_category) {
                $category_name = $this->get_category_name($filter_category);
                $sheet->setCellValue('A' . ($row + 3), 'Kategori: ' . $category_name);
                $filter_info[] = 'Kategori: ' . $category_name;
            }

            if ($filter_status) {
                $status_text = $this->get_status_text($filter_status);
                $sheet->setCellValue('A' . ($row + 4), 'Status: ' . $status_text);
                $filter_info[] = 'Status: ' . $status_text;
            }

            // =================== TABLE HEADER ===================
            $header_row = max($row + 6, 10);

            // Header sesuai dengan view (9 kolom)
            $headers = [
                'No',
                'Kode Barang',
                'Nama Barang',
                'Kategori',
                'Satuan',
                'Stok Tersedia',
                'Gudang',
                'Status',
                'Keterangan'
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $header_row, $header);
                $sheet->getColumnDimension($col)->setAutoSize(true);

                // Style header
                $sheet->getStyle($col . $header_row)
                    ->getFont()->setBold(true);
                $sheet->getStyle($col . $header_row)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $header_row)
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4F81BD');
                $sheet->getStyle($col . $header_row)
                    ->getFont()->getColor()->setRGB('FFFFFF');
                $sheet->getStyle($col . $header_row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $col++;
            }

            // =================== TABLE DATA ===================
            $data_row = $header_row + 1;
            $no = 1;
            $total_stock = 0;

            foreach ($response['data'] as $item) {
                $min_stock = 0; // Sesuai dengan view yang menggunakan $min_stock = 0
                $current_stock = isset($item['current_stock']) ? (float) $item['current_stock'] : 0;
                $total_stock += $current_stock;

                // Determine status sesuai dengan view
                $status = 'Normal';
                $status_class = 'success';
                if ($current_stock <= 0) {
                    $status = 'Kosong';
                    $status_class = 'danger';
                } elseif ($current_stock <= $min_stock) {
                    $status = 'Menipis';
                    $status_class = 'warning';
                }

                // Fill data sesuai dengan urutan view
                $sheet->setCellValue('A' . $data_row, $no);
                $sheet->setCellValue('B' . $data_row, $item['product_code'] ?? '');
                $sheet->setCellValue('C' . $data_row, $item['product_name'] ?? '');
                $sheet->setCellValue('D' . $data_row, $item['type_name'] ?? ($item['product_type_name'] ?? '-'));
                $sheet->setCellValue('E' . $data_row, $item['unit_code'] ?? ($item['unit_name'] ?? ''));
                $sheet->setCellValue('F' . $data_row, $current_stock);
                $sheet->setCellValue('G' . $data_row, $item['warehouse_name'] ?? '-');
                $sheet->setCellValue('H' . $data_row, $status);
                $sheet->setCellValue('I' . $data_row, $item['product_note'] ?? '-');

                // Number format for stock column
                $sheet->getStyle('F' . $data_row)->getNumberFormat()->setFormatCode('#,##0.00');

                // Apply styling untuk status berdasarkan warna
                $status_color = '92D050'; // Green default (Normal)
                if ($status_class == 'danger') {
                    $status_color = 'FF0000'; // Red (Kosong)
                } elseif ($status_class == 'warning') {
                    $status_color = 'FFC000'; // Orange (Menipis)
                }

                $sheet->getStyle('H' . $data_row)
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($status_color);
                $sheet->getStyle('H' . $data_row)
                    ->getFont()->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('H' . $data_row)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Warna teks untuk stok yang rendah
                if ($current_stock <= $min_stock) {
                    $sheet->getStyle('F' . $data_row)
                        ->getFont()->setBold(true);
                    if ($status_class == 'danger') {
                        $sheet->getStyle('F' . $data_row)->getFont()->getColor()->setRGB('FF0000');
                    } elseif ($status_class == 'warning') {
                        $sheet->getStyle('F' . $data_row)->getFont()->getColor()->setRGB('FF9900');
                    }
                }

                // Add borders to all cells
                $sheet->getStyle('A' . $data_row . ':I' . $data_row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Alignment untuk kolom tertentu
                $sheet->getStyle('A' . $data_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
                $sheet->getStyle('F' . $data_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Stok Tersedia

                $data_row++;
                $no++;
            }

            // =================== FOOTER TOTAL ===================
            $footer_row = $data_row + 1;

            // "Total:" di kolom E (colspan dari A-E sesuai view)
            $sheet->setCellValue('E' . $footer_row, 'Total:');
            $sheet->getStyle('E' . $footer_row)
                ->getFont()->setBold(true);
            $sheet->getStyle('E' . $footer_row)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Total stok di kolom F
            $sheet->setCellValue('F' . $footer_row, $total_stock);
            $sheet->getStyle('F' . $footer_row)
                ->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('F' . $footer_row)
                ->getFont()->setBold(true);
            $sheet->getStyle('F' . $footer_row)
                ->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E2EFDA');
            $sheet->getStyle('F' . $footer_row)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Add borders untuk footer
            $sheet->getStyle('E' . $footer_row . ':F' . $footer_row)
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // =================== STATISTICS ===================
            $stats_row = $footer_row + 3;
            $sheet->setCellValue('A' . $stats_row, 'STATISTIK STOK');
            $sheet->mergeCells('A' . $stats_row . ':C' . $stats_row);
            $sheet->getStyle('A' . $stats_row)
                ->getFont()->setBold(true)->setSize(12);

            $stats_row++;

            // Calculate statistics
            $stok_normal = 0;
            $stok_menipis = 0;
            $stok_kosong = 0;

            foreach ($response['data'] as $item) {
                $min_stock = 0;
                $current_stock = isset($item['current_stock']) ? (float) $item['current_stock'] : 0;

                if ($current_stock <= 0) {
                    $stok_kosong++;
                } elseif ($current_stock <= $min_stock) {
                    $stok_menipis++;
                } else {
                    $stok_normal++;
                }
            }

            $sheet->setCellValue('A' . $stats_row, 'Stok Normal:');
            $sheet->setCellValue('B' . $stats_row, $stok_normal);
            $sheet->getStyle('A' . $stats_row)->getFont()->setBold(true);

            $stats_row++;
            $sheet->setCellValue('A' . $stats_row, 'Stok Menipis:');
            $sheet->setCellValue('B' . $stats_row, $stok_menipis);
            $sheet->getStyle('A' . $stats_row)->getFont()->setBold(true);

            $stats_row++;
            $sheet->setCellValue('A' . $stats_row, 'Stok Kosong:');
            $sheet->setCellValue('B' . $stats_row, $stok_kosong);
            $sheet->getStyle('A' . $stats_row)->getFont()->setBold(true);

            $stats_row++;
            $sheet->setCellValue('A' . $stats_row, 'Total Item:');
            $sheet->setCellValue('B' . $stats_row, count($response['data']));
            $sheet->getStyle('A' . $stats_row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $stats_row)->getFont()->setBold(true);

            // =================== FOOTER USER INFO ===================
            $user_row = $stats_row + 3;
            $sheet->setCellValue('A' . $user_row, 'Diexport oleh:');
            $sheet->getStyle('A' . $user_row)->getFont()->setItalic(true);

            $sheet->setCellValue('B' . $user_row, $this->session->userdata('name') ?: 'System');
            $sheet->getStyle('B' . $user_row)->getFont()->setBold(true);

            $user_row++;
            $sheet->setCellValue('A' . $user_row, 'Lever:');
            $sheet->getStyle('A' . $user_row)->getFont()->setItalic(true);

            $sheet->setCellValue('B' . $user_row, $this->session->userdata('role') ?: 'User');
            $sheet->getStyle('B' . $user_row)->getFont()->setBold(true);

            // =================== SET COLUMN WIDTHS ===================
            $sheet->getColumnDimension('A')->setWidth(8);  // No
            $sheet->getColumnDimension('B')->setWidth(15); // Kode Barang
            $sheet->getColumnDimension('C')->setWidth(30); // Nama Barang
            $sheet->getColumnDimension('D')->setWidth(20); // Kategori
            $sheet->getColumnDimension('E')->setWidth(10); // Satuan
            $sheet->getColumnDimension('F')->setWidth(15); // Stok Tersedia
            $sheet->getColumnDimension('G')->setWidth(25); // Gudang
            $sheet->getColumnDimension('H')->setWidth(12); // Status
            $sheet->getColumnDimension('I')->setWidth(30); // Keterangan

            // Set auto filter
            $sheet->setAutoFilter('A' . $header_row . ':I' . ($data_row - 1));

            // =================== OUTPUT ===================
            $filename = 'Laporan_Stok_' . date('Ymd_His') . '.xlsx';

            // Redirect output to a client's web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            $this->session->set_flashdata('error', 'Tidak ada data stok untuk diekspor!');
            redirect('laporan/stok');
        }
    }

    // Helper function to get warehouse name
    private function get_warehouse_name($warehouse_id)
    {
        $data = data_login_user(['warehouse_id' => $warehouse_id]);
        $response = $this->Api_model->get_gudang_id($data);
        if ($response['success'] && isset($response['data'][0])) {
            return $response['data'][0]['warehouse_name'];
        }
        return 'Tidak Diketahui';
    }

    // Helper function to get product name
    private function get_product_name($product_id)
    {
        $data = data_login_user(['product_id' => $product_id]);
        $response = $this->Api_model->get_barang_by_id($data);
        if ($response['success'] && isset($response['data'][0])) {
            return $response['data'][0]['product_name'];
        }
        return 'Tidak Diketahui';
    }

    // Helper function to get category name
    private function get_category_name($category_id)
    {
        $data = data_login_user(['product_type_id' => $category_id]);
        $response = $this->Api_model->get_product_type_by_id($data);
        if ($response['success'] && isset($response['data'][0])) {
            return $response['data'][0]['product_type_name'];
        }
        return 'Tidak Diketahui';
    }

    // Helper function to get status text
    private function get_status_text($status)
    {
        $statuses = [
            'stok_normal' => 'Stok Normal',
            'stok_menipis' => 'Stok Menipis',
            'stok_kosong' => 'Stok Kosong'
        ];
        return $statuses[$status] ?? 'Semua Status';
    }

    public function masuk()
    {
        $this->check_permission('laporan', 'view');
        // Set title
        $this->data['title'] = 'Laporan Barang Masuk';
        $this->data['active_menu'] = 'laporan';
        $this->data['active_submenu'] = 'laporan_masuk';

        // Get user data
        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');
        $data = data_login_user();

        // Get parameters from filter
        $filter_date_from = $this->input->get('date_from') ?: null;
        $filter_date_to = $this->input->get('date_to') ?: null;
        $filter_product_id = $this->input->get('product_id') ?: null;
        $filter_warehouse_id = $this->input->get('warehouse_id') ?: null;
        $filter_supplier_id = $this->input->get('supplier_id') ?: null;

        // Prepare filter data for API
        $params = [
            'date_from' => $filter_date_from,
            'date_to' => $filter_date_to,
            'product_id' => $filter_product_id,
            'warehouse_id' => $filter_warehouse_id,
            'supplier_id' => $filter_supplier_id
        ];

        // Jika bukan superadmin, default ke warehouse user
        if ($user_role != 'superadmin' && empty($filter_warehouse_id)) {
            $params['warehouse_id'] = $warehouse_id;
        }

        // Get in report from API
        $response = $this->Api_model->get_laporan_masuk($params);
        $this->data['in_report'] = $response['success'] ? $response['data'] : [];

        // Get items from API
        $items = $this->Api_model->get_barang(data_login_user());
        $this->data['products'] = $this->handle_response($items);

        // Get suppliers from API
        $suppliers_response = $this->Api_model->get_supplier(data_login_user());
        $this->data['suppliers'] = $this->handle_response($suppliers_response);

        // Get warehouses from API
        if ($user_role == 'superadmin') {
            $warehouses = $this->Api_model->get_all_gudang(data_login_user());
        } else {
            $warehouses = $this->Api_model->get_gudang(data_login_user());
        }
        $this->data['warehouses'] = $this->handle_response($warehouses);

        // Pass filter values to view
        $this->data['filter_date_from'] = $filter_date_from;
        $this->data['filter_date_to'] = $filter_date_to;
        $this->data['filter_product_id'] = $filter_product_id;
        $this->data['filter_warehouse_id'] = $filter_warehouse_id;
        $this->data['filter_supplier_id'] = $filter_supplier_id;
        $this->data['user_role'] = $user_role;

        // Render view
        $this->render_view('pages/laporan/masuk');
    }

    public function keluar()
    {
        $this->check_permission('laporan', 'view');
        // Set title
        $this->data['title'] = 'Laporan Barang Keluar';
        $this->data['active_menu'] = 'laporan';
        $this->data['active_submenu'] = 'laporan_keluar';

        // Get user data
        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');
        $data = data_login_user();

        // Get parameters from filter
        $filter_date_from = $this->input->get('date_from') ?: null;
        $filter_date_to = $this->input->get('date_to') ?: null;
        $filter_product_id = $this->input->get('product_id') ?: null;
        $filter_warehouse_id = $this->input->get('warehouse_id') ?: null;
        $filter_customer_id = $this->input->get('customer_id') ?: null;
        $filter_to_status = $this->input->get('to_status') ?: null; // 1=ke pengguna, 3=antar gudang

        // Prepare filter data for API
        $params = [
            'date_start' => $filter_date_from,
            'date_end' => $filter_date_to,
            'product_id' => $filter_product_id,
            'warehouse_id' => $filter_warehouse_id,
            'customer_id' => $filter_customer_id,
            'to_status' => $filter_to_status
        ];

        // Jika bukan superadmin, default ke warehouse user
        if ($user_role != 'superadmin' && empty($filter_warehouse_id)) {
            $params['warehouse_id'] = $warehouse_id;
        }

        // Get out report from API
        $response = $this->Api_model->get_laporan_keluar($params);
        $this->data['out_report'] = $response['success'] ? $response['data'] : [];

        // Get items from API
        $items = $this->Api_model->get_barang(data_login_user());
        $this->data['products'] = $items['success'] ? $items['data'] : [];

        // Get customers from API
        $customers_response = $this->Api_model->get_customer(data_login_user());
        $this->data['customers'] = $customers_response['success'] ? $customers_response['data'] : [];

        // Get warehouses from API
        if ($user_role == 'superadmin') {
            $warehouses = $this->Api_model->get_all_gudang(data_login_user());
        } else {
            $warehouses = $this->Api_model->get_gudang(data_login_user());
        }
        $this->data['warehouses'] = $warehouses['success'] ? $warehouses['data'] : [];

        // Pass filter values to view
        $this->data['filter_date_from'] = $filter_date_from;
        $this->data['filter_date_to'] = $filter_date_to;
        $this->data['filter_product_id'] = $filter_product_id;
        $this->data['filter_warehouse_id'] = $filter_warehouse_id;
        $this->data['filter_customer_id'] = $filter_customer_id;
        $this->data['filter_to_status'] = $filter_to_status;
        $this->data['user_role'] = $user_role;

        // Render view
        $this->render_view('pages/laporan/keluar');
    }

    public function export_masuk()
    {
        $this->check_permission('laporan', 'export');

        // Get user data
        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');

        // Get parameters from filter
        $filter_date_from = $this->input->get('date_from');
        $filter_date_to = $this->input->get('date_to');
        $filter_product_id = $this->input->get('product_id');
        $filter_warehouse_id = $this->input->get('warehouse_id');
        $filter_supplier_id = $this->input->get('supplier_id');

        // Prepare filter data for API
        $params = [
            'date_from' => $filter_date_from,
            'date_to' => $filter_date_to,
            'product_id' => $filter_product_id,
            'warehouse_id' => $filter_warehouse_id,
            'supplier_id' => $filter_supplier_id
        ];

        // Jika bukan superadmin, default ke warehouse user
        if ($user_role != 'superadmin' && empty($filter_warehouse_id)) {
            $params['warehouse_id'] = $warehouse_id;
        }

        // Get in report from API
        $response = $this->Api_model->get_laporan_masuk($params);

        if ($response['success'] && !empty($response['data'])) {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator($this->session->userdata('name') ?: 'WMS System')
                ->setLastModifiedBy($this->session->userdata('name') ?: 'WMS System')
                ->setTitle('Laporan Barang Masuk')
                ->setSubject('Laporan Barang Masuk per Gudang')
                ->setDescription('Dokumen ini berisi laporan barang masuk per gudang')
                ->setKeywords('barang masuk gudang laporan')
                ->setCategory('Laporan');

            // Set default font
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

            // =================== TITLE AND HEADER ===================
            $sheet->setCellValue('A1', 'LAPORAN BARANG MASUK');
            $sheet->mergeCells('A1:K1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Company/System Info
            $sheet->setCellValue('A2', 'Sistem Warehouse Management');
            $sheet->mergeCells('A2:K2');
            $sheet->getStyle('A2')->getFont()->setSize(11);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Filter Info
            $row = 4;
            $sheet->setCellValue('A' . $row, 'Tanggal Export: ' . date('d-m-Y H:i:s'));

            $row++;
            if ($filter_date_from || $filter_date_to) {
                $date_range = 'Periode: ';
                $date_range .= $filter_date_from ? date('d-m-Y', strtotime($filter_date_from)) : '';
                $date_range .= $filter_date_from && $filter_date_to ? ' s/d ' : '';
                $date_range .= $filter_date_to ? date('d-m-Y', strtotime($filter_date_to)) : '';
                $sheet->setCellValue('A' . $row, $date_range);
                $row++;
            }

            if ($filter_warehouse_id) {
                $warehouse_name = $this->get_warehouse_name($filter_warehouse_id);
                $sheet->setCellValue('A' . $row, 'Gudang: ' . $warehouse_name);
                $row++;
            } elseif ($user_role != 'superadmin') {
                $warehouse_name = $this->session->userdata('warehouse_name');
                $sheet->setCellValue('A' . $row, 'Gudang: ' . $warehouse_name);
                $row++;
            }

            // =================== TABLE HEADER ===================
            $header_row = $row + 1;
            $headers = [
                'No',
                'Tanggal',
                'Kode Transaksi',
                'Kode Barang',
                'Nama Barang',
                'Jumlah',
                'Satuan',
                'Supplier',
                'Gudang',
                'Keterangan',
                'Dibuat Oleh'
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $header_row, $header);

                // Style header
                $sheet->getStyle($col . $header_row)
                    ->getFont()->setBold(true);
                $sheet->getStyle($col . $header_row)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $header_row)
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4F81BD');
                $sheet->getStyle($col . $header_row)
                    ->getFont()->getColor()->setRGB('FFFFFF');
                $sheet->getStyle($col . $header_row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $col++;
            }

            // =================== TABLE DATA ===================
            $data_row = $header_row + 1;
            $no = 1;
            $total_qty = 0;

            foreach ($response['data'] as $item) {
                $qty = isset($item['qty']) ? (float) $item['qty'] : 0;
                $total_qty += $qty;

                // Fill data
                $sheet->setCellValue('A' . $data_row, $no);
                $sheet->setCellValue('B' . $data_row, date('d-m-Y', strtotime($item['stockin_date'] ?? '')));
                $sheet->setCellValue('C' . $data_row, $item['stockin_code'] ?? '');
                $sheet->setCellValue('D' . $data_row, $item['product_code'] ?? '');
                $sheet->setCellValue('E' . $data_row, $item['product_name'] ?? '');
                $sheet->setCellValue('F' . $data_row, $qty);
                $sheet->setCellValue('G' . $data_row, $item['unit_name'] ?? '');
                $sheet->setCellValue('H' . $data_row, $item['supplier_name'] ?? '-');
                $sheet->setCellValue('I' . $data_row, $item['warehouse_name'] ?? '-');
                $sheet->setCellValue('J' . $data_row, $item['stockin_note'] ?? '-');
                $sheet->setCellValue('K' . $data_row, $item['user_name'] ?? '-');

                // Number format for quantity
                $sheet->getStyle('F' . $data_row)->getNumberFormat()->setFormatCode('#,##0.00');

                // Add borders to all cells
                $sheet->getStyle('A' . $data_row . ':K' . $data_row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $data_row++;
                $no++;
            }

            // =================== SUMMARY ===================
            $summary_row = $data_row + 2;

            $sheet->setCellValue('E' . $summary_row, 'TOTAL QUANTITY:');
            $sheet->getStyle('E' . $summary_row)
                ->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle('E' . $summary_row)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->setCellValue('F' . $summary_row, $total_qty);
            $sheet->getStyle('F' . $summary_row)
                ->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('F' . $summary_row)
                ->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle('F' . $summary_row)
                ->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E2EFDA');

            // =================== STATISTICS ===================
            $stats_row = $summary_row + 2;
            $sheet->setCellValue('A' . $stats_row, 'STATISTIK');
            $sheet->mergeCells('A' . $stats_row . ':C' . $stats_row);
            $sheet->getStyle('A' . $stats_row)
                ->getFont()->setBold(true)->setSize(12);

            $stats_row++;
            $sheet->setCellValue('A' . $stats_row, 'Total Transaksi:');
            $sheet->setCellValue('B' . $stats_row, count($response['data']));
            $sheet->getStyle('A' . $stats_row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $stats_row)->getFont()->setBold(true);

            // =================== SET COLUMN WIDTHS ===================
            $sheet->getColumnDimension('A')->setWidth(8);   // No
            $sheet->getColumnDimension('B')->setWidth(12);  // Tanggal
            $sheet->getColumnDimension('C')->setWidth(20);  // Kode Transaksi
            $sheet->getColumnDimension('D')->setWidth(15);  // Kode Barang
            $sheet->getColumnDimension('E')->setWidth(30);  // Nama Barang
            $sheet->getColumnDimension('F')->setWidth(12);  // Jumlah
            $sheet->getColumnDimension('G')->setWidth(10);  // Satuan
            $sheet->getColumnDimension('H')->setWidth(25);  // Supplier
            $sheet->getColumnDimension('I')->setWidth(20);  // Gudang
            $sheet->getColumnDimension('J')->setWidth(30);  // Keterangan
            $sheet->getColumnDimension('K')->setWidth(20);  // Dibuat Oleh

            // Set auto filter
            $last_row = $data_row - 1;
            if ($last_row > $header_row) {
                $sheet->setAutoFilter('A' . $header_row . ':K' . $last_row);
            }

            // =================== OUTPUT ===================
            $filename = 'Laporan_Barang_Masuk_' . date('Ymd_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            $this->session->set_flashdata('error', 'Tidak ada data barang masuk untuk diekspor!');
            redirect('laporan/masuk');
        }
    }

    public function export_keluar()
    {
        $this->check_permission('laporan', 'export');

        // Get user data
        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');

        // Get parameters from filter
        $filter_date_from = $this->input->get('date_from');
        $filter_date_to = $this->input->get('date_to');
        $filter_product_id = $this->input->get('product_id');
        $filter_warehouse_id = $this->input->get('warehouse_id');
        $filter_customer_id = $this->input->get('customer_id');
        $filter_to_status = $this->input->get('to_status');

        // Prepare filter data for API
        $params = [
            'date_from' => $filter_date_from,
            'date_to' => $filter_date_to,
            'product_id' => $filter_product_id,
            'warehouse_id' => $filter_warehouse_id,
            'customer_id' => $filter_customer_id,
            'to_status' => $filter_to_status
        ];

        // Jika bukan superadmin, default ke warehouse user
        if ($user_role != 'superadmin' && empty($filter_warehouse_id)) {
            $params['warehouse_id'] = $warehouse_id;
        }

        // Get out report from API
        $response = $this->Api_model->get_laporan_keluar($params);

        if ($response['success'] && !empty($response['data'])) {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator($this->session->userdata('name') ?: 'WMS System')
                ->setLastModifiedBy($this->session->userdata('name') ?: 'WMS System')
                ->setTitle('Laporan Barang Keluar')
                ->setSubject('Laporan Barang Keluar per Gudang')
                ->setDescription('Dokumen ini berisi laporan barang keluar per gudang')
                ->setKeywords('barang keluar gudang laporan')
                ->setCategory('Laporan');

            // Set default font
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

            // =================== TITLE AND HEADER ===================
            $sheet->setCellValue('A1', 'LAPORAN BARANG KELUAR');
            $sheet->mergeCells('A1:L1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Company/System Info
            $sheet->setCellValue('A2', 'Sistem Warehouse Management');
            $sheet->mergeCells('A2:L2');
            $sheet->getStyle('A2')->getFont()->setSize(11);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Filter Info
            $row = 4;
            $sheet->setCellValue('A' . $row, 'Tanggal Export: ' . date('d-m-Y H:i:s'));

            $row++;
            if ($filter_date_from || $filter_date_to) {
                $date_range = 'Periode: ';
                $date_range .= $filter_date_from ? date('d-m-Y', strtotime($filter_date_from)) : '';
                $date_range .= $filter_date_from && $filter_date_to ? ' s/d ' : '';
                $date_range .= $filter_date_to ? date('d-m-Y', strtotime($filter_date_to)) : '';
                $sheet->setCellValue('A' . $row, $date_range);
                $row++;
            }

            if ($filter_warehouse_id) {
                $warehouse_name = $this->get_warehouse_name($filter_warehouse_id);
                $sheet->setCellValue('A' . $row, 'Gudang: ' . $warehouse_name);
                $row++;
            } elseif ($user_role != 'superadmin') {
                $warehouse_name = $this->session->userdata('warehouse_name');
                $sheet->setCellValue('A' . $row, 'Gudang: ' . $warehouse_name);
                $row++;
            }

            // =================== TABLE HEADER ===================
            $header_row = $row + 1;
            $headers = [
                'No',
                'Tanggal',
                'Kode Transaksi',
                'Kode Barang',
                'Nama Barang',
                'Jumlah',
                'Satuan',
                'Tujuan',
                'Jenis',
                'Gudang',
                'Keterangan',
                'Dibuat Oleh'
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $header_row, $header);

                // Style header
                $sheet->getStyle($col . $header_row)
                    ->getFont()->setBold(true);
                $sheet->getStyle($col . $header_row)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col . $header_row)
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4F81BD');
                $sheet->getStyle($col . $header_row)
                    ->getFont()->getColor()->setRGB('FFFFFF');
                $sheet->getStyle($col . $header_row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $col++;
            }

            // =================== TABLE DATA ===================
            $data_row = $header_row + 1;
            $no = 1;
            $total_qty = 0;

            foreach ($response['data'] as $item) {
                $qty = isset($item['qty']) ? (float) $item['qty'] : 0;
                $total_qty += $qty;

                // Determine jenis pengiriman
                $jenis = '-';
                if ($item['to_status'] == '1') {
                    $jenis = 'Ke Pengguna';
                } elseif ($item['to_status'] == '3') {
                    $jenis = 'Antar Gudang';
                }

                // Fill data
                $sheet->setCellValue('A' . $data_row, $no);
                $sheet->setCellValue('B' . $data_row, date('d-m-Y', strtotime($item['stockout_date'] ?? '')));
                $sheet->setCellValue('C' . $data_row, $item['stockout_code'] ?? '');
                $sheet->setCellValue('D' . $data_row, $item['product_code'] ?? '');
                $sheet->setCellValue('E' . $data_row, $item['product_name'] ?? '');
                $sheet->setCellValue('F' . $data_row, $qty);
                $sheet->setCellValue('G' . $data_row, $item['unit_name'] ?? '');
                $sheet->setCellValue('H' . $data_row, $item['to_name'] ?? '-');
                $sheet->setCellValue('I' . $data_row, $jenis);
                $sheet->setCellValue('J' . $data_row, $item['warehouse_name'] ?? '-');
                $sheet->setCellValue('K' . $data_row, $item['stockout_note'] ?? '-');
                $sheet->setCellValue('L' . $data_row, $item['user_name'] ?? '-');

                // Number format for quantity
                $sheet->getStyle('F' . $data_row)->getNumberFormat()->setFormatCode('#,##0.00');

                // Add borders to all cells
                $sheet->getStyle('A' . $data_row . ':L' . $data_row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $data_row++;
                $no++;
            }

            // =================== SUMMARY ===================
            $summary_row = $data_row + 2;

            $sheet->setCellValue('E' . $summary_row, 'TOTAL QUANTITY:');
            $sheet->getStyle('E' . $summary_row)
                ->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle('E' . $summary_row)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->setCellValue('F' . $summary_row, $total_qty);
            $sheet->getStyle('F' . $summary_row)
                ->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('F' . $summary_row)
                ->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle('F' . $summary_row)
                ->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E2EFDA');

            // =================== STATISTICS ===================
            $stats_row = $summary_row + 2;
            $sheet->setCellValue('A' . $stats_row, 'STATISTIK');
            $sheet->mergeCells('A' . $stats_row . ':C' . $stats_row);
            $sheet->getStyle('A' . $stats_row)
                ->getFont()->setBold(true)->setSize(12);

            $stats_row++;
            $sheet->setCellValue('A' . $stats_row, 'Total Transaksi:');
            $sheet->setCellValue('B' . $stats_row, count($response['data']));
            $sheet->getStyle('A' . $stats_row)->getFont()->setBold(true);
            $sheet->getStyle('B' . $stats_row)->getFont()->setBold(true);

            // Count by jenis
            $ke_pengguna = 0;
            $antar_gudang = 0;
            foreach ($response['data'] as $item) {
                if ($item['to_status'] == '1') {
                    $ke_pengguna++;
                } elseif ($item['to_status'] == '3') {
                    $antar_gudang++;
                }
            }

            $stats_row++;
            $sheet->setCellValue('A' . $stats_row, 'Ke Pengguna:');
            $sheet->setCellValue('B' . $stats_row, $ke_pengguna);

            $stats_row++;
            $sheet->setCellValue('A' . $stats_row, 'Antar Gudang:');
            $sheet->setCellValue('B' . $stats_row, $antar_gudang);

            // =================== SET COLUMN WIDTHS ===================
            $sheet->getColumnDimension('A')->setWidth(8);   // No
            $sheet->getColumnDimension('B')->setWidth(12);  // Tanggal
            $sheet->getColumnDimension('C')->setWidth(20);  // Kode Transaksi
            $sheet->getColumnDimension('D')->setWidth(15);  // Kode Barang
            $sheet->getColumnDimension('E')->setWidth(30);  // Nama Barang
            $sheet->getColumnDimension('F')->setWidth(12);  // Jumlah
            $sheet->getColumnDimension('G')->setWidth(10);  // Satuan
            $sheet->getColumnDimension('H')->setWidth(25);  // Tujuan
            $sheet->getColumnDimension('I')->setWidth(15);  // Jenis
            $sheet->getColumnDimension('J')->setWidth(20);  // Gudang
            $sheet->getColumnDimension('K')->setWidth(30);  // Keterangan
            $sheet->getColumnDimension('L')->setWidth(20);  // Dibuat Oleh

            // Set auto filter
            $last_row = $data_row - 1;
            if ($last_row > $header_row) {
                $sheet->setAutoFilter('A' . $header_row . ':L' . $last_row);
            }

            // =================== OUTPUT ===================
            $filename = 'Laporan_Barang_Keluar_' . date('Ymd_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            $this->session->set_flashdata('error', 'Tidak ada data barang keluar untuk diekspor!');
            redirect('laporan/keluar');
        }
    }
    public function transaksi()
    {
        $this->check_permission('laporan', 'view');
        // Set title
        $this->data['title'] = 'Laporan Transaksi';
        $this->data['active_menu'] = 'laporan';
        $this->data['active_submenu'] = 'laporan_transaksi';

        $user_role = $this->session->userdata('role');
        $warehouse_id = $this->session->userdata('warehouse_id');
        $data = data_login_user();

        // Get filter parameters
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        $product_id = $this->input->get('product_id');
        $filter_warehouse = $this->input->get('warehouse_id');
        $transaction_type = $this->input->get('transaction_type'); // masuk/keluar

        // Prepare filter data for API
        $filter_data = $data;

        // Date filter
        if ($date_from) {
            $filter_data['date_from'] = $date_from;
        }
        if ($date_to) {
            $filter_data['date_to'] = $date_to;
        }

        // Warehouse filter
        if ($filter_warehouse) {
            $filter_data['warehouse_id'] = $filter_warehouse;
        } elseif ($warehouse_id && $user_role != 'superadmin') {
            $filter_data['warehouse_id'] = $warehouse_id;
        }

        // Product filter
        if ($product_id) {
            $filter_data['product_id'] = $product_id;
        }

        // Transaction type filter
        if ($transaction_type) {
            $filter_data['transaction_type'] = $transaction_type;
        }

        // Get combined report from API (both masuk and keluar)
        $response_in = $this->Api_model->get_laporan_masuk($filter_data);
        $response_out = $this->Api_model->get_laporan_keluar($filter_data);

        $transactions = [];

        // Process masuk (penerimaan)
        if ($response_in['success']) {
            foreach ($response_in['data'] as $item) {
                $item['type'] = 'MASUK';
                $item['type_text'] = 'Barang Masuk';
                $transactions[] = $item;
            }
        }

        // Process keluar (pengiriman)
        if ($response_out['success']) {
            foreach ($response_out['data'] as $item) {
                $item['type'] = 'KELUAR';
                $item['type_text'] = 'Barang Keluar';
                $transactions[] = $item;
            }
        }

        // Sort by date
        usort($transactions, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $this->data['transactions'] = $transactions;

        // Get products for filter dropdown
        $products_response = $this->Api_model->get_barang($data);
        $this->data['products'] = $products_response['success'] ? $products_response['data'] : [];

        // Get warehouses for filter dropdown
        if ($user_role == 'superadmin') {
            $warehouses_response = $this->Api_model->get_all_gudang($data);
        } else {
            $warehouses_response = $this->Api_model->get_gudang($data);
        }
        $this->data['warehouses'] = $warehouses_response['success'] ? $warehouses_response['data'] : [];

        // Pass filter values back to view
        $this->data['filter_date_from'] = $date_from;
        $this->data['filter_date_to'] = $date_to;
        $this->data['filter_product_id'] = $product_id;
        $this->data['filter_warehouse_id'] = $filter_warehouse;
        $this->data['filter_transaction_type'] = $transaction_type;
        $this->data['user_role'] = $user_role;
        $this->data['user_warehouse_id'] = $warehouse_id;

        // Render view
        $this->render_view('pages/laporan/transaksi');
    }

}