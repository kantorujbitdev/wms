<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Spreadsheet
{

    public function __construct()
    {
        require_once FCPATH . 'vendor/autoload.php';
    }

    public function load()
    {
        return new PhpSpreadsheet();
    }

    public function writer($spreadsheet)
    {
        return new Xlsx($spreadsheet);
    }
}
