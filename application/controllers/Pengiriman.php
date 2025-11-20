<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengiriman extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function antar_gudang()
    {
        // Set title
        $this->data['title'] = 'Pengiriman Antar Gudang';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'antar_gudang';

        // Render view
        $this->render_view('pages/static_page');
    }

    public function penggunaan()
    {
        // Set title
        $this->data['title'] = 'Pengiriman Penggunaan';
        $this->data['active_menu'] = 'pengiriman';
        $this->data['active_submenu'] = 'penggunaan';

        // Render view
        $this->render_view('pages/static_page');
    }
}