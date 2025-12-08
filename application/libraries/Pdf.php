<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf
{
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->helper('file');
    }

    public function generate($html, $filename = 'document', $output = 'I')
    {
        if (!class_exists('TCPDF')) {
            if (file_exists(FCPATH . 'vendor/autoload.php')) {
                require_once FCPATH . 'vendor/autoload.php';
            } elseif (file_exists(APPPATH . 'third_party/tcpdf/tcpdf.php')) {
                require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
            }
        }

        // Create new PDF document
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('WMS System');
        $pdf->SetTitle($filename);
        $pdf->SetSubject('Surat Jalan');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(10, 1, 10);
        $pdf->SetAutoPageBreak(TRUE, 15);

        // Set watermark
        $pdf->SetAlpha(0.1);

        // Write HTML content
        $pdf->AddPage();
        $pdf->SetAlpha(1); // Reset alpha untuk konten utama

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Write HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $pdf->Output($filename . '.pdf', $output);
    }

    private function download_tcpdf()
    {
        // Download TCPDF jika belum ada
        $tcpdf_dir = APPPATH . 'third_party/tcpdf/';

        if (!is_dir($tcpdf_dir)) {
            mkdir($tcpdf_dir, 0755, true);
        }

        // URL TCPDF
        $tcpdf_url = 'https://raw.githubusercontent.com/tecnickcom/TCPDF/main/tcpdf.php';

        // Download file utama
        $tcpdf_content = @file_get_contents($tcpdf_url);
        if ($tcpdf_content) {
            file_put_contents($tcpdf_dir . 'tcpdf.php', $tcpdf_content);
        }

        // Buat minimal TCPDF jika download gagal
        if (!file_exists($tcpdf_dir . 'tcpdf.php')) {
            $this->create_minimal_tcpdf($tcpdf_dir);
        }
    }

    private function create_minimal_tcpdf($dir)
    {
        // Buat file TCPDF minimal untuk testing
        $minimal_tcpdf = '<?php
// TCPDF minimal untuk WMS
if (!class_exists("TCPDF")) {
    class TCPDF {
        private $margins = [10, 10, 10];
        private $page_break_margin = 15;
        
        public function __construct($orientation = "P", $unit = "mm", $format = "A4", $unicode = true, $encoding = "UTF-8", $diskcache = false) {}
        public function SetCreator($creator) {}
        public function SetAuthor($author) {}
        public function SetTitle($title) {}
        public function SetSubject($subject) {}
        public function setPrintHeader($print) {}
        public function setPrintFooter($print) {}
        public function SetMargins($left, $top, $right = -1) {
            $this->margins = [$left, $top, $right];
        }
        public function SetAutoPageBreak($auto, $margin = 0) {
            $this->page_break_margin = $margin;
        }
        public function AddPage() {}
        public function SetFont($family, $style = "", $size = null) {}
        public function writeHTML($html, $ln = true, $fill = false, $reseth = false, $cell = false, $align = "") {
            echo $html;
        }
        public function Output($name = "", $dest = "I") {
            if ($dest == "D") {
                header("Content-Type: application/pdf");
                header("Content-Disposition: attachment; filename=\"" . $name . "\"");
            }
            exit;
        }
    }
}';

        file_put_contents($dir . 'tcpdf.php', $minimal_tcpdf);
    }
}