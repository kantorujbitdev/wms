<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Static_page extends MY_Controller
{
    public function not_found()
    {
        $this->output->set_status_header(404);

        $this->render_view('pages/static_page', [
            'title' => 'Halaman Tidak Ditemukan'
        ]);
    }
}