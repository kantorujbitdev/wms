<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengaturan_model', 'Pengaturan_model');
    }

    public function index()
    {
        $data['pengaturan'] = $this->Pengaturan_model->get_all();
        $data['page_title'] = 'Pengaturan Aplikasi';
        $data['active_menu'] = 'pengaturan';
        $data['active_submenu'] = 'web';
        $this->render_admin_view('pages/pengaturan/index', $data);
    }

    public function edit($id)
    {
        $item = $this->Pengaturan_model->get_by_id($id);
        if (!$item)
            show_404();

        if ($this->input->post()) {
            $data = [
                'nama_pengaturan' => $this->input->post('nama_pengaturan'),
                'value' => $this->input->post('value'),
            ];
            $this->Pengaturan_model->update($id, $data);
            $this->session->set_flashdata('success', 'Pengaturan berhasil diperbarui');
            redirect('pengaturan');
        }

        $data['item'] = $item;
        $this->render_admin_view('pages/pengaturan/form', $data);
    }
}
