<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function antar_gudang()
    {
        // Set title
        $this->data['title'] = 'Penerimaan Antar Gudang';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'antar_gudang';

        // Render view
        $this->render_view('pages/static_page');
    }

    public function from_supplier()
    {
        // Set title
        $this->data['title'] = 'Penerimaan Supplier';
        $this->data['active_menu'] = 'penerimaan';
        $this->data['active_submenu'] = 'supplier';

        // Render view
        $this->render_view('pages/static_page');
    }
}