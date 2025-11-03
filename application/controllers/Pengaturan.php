<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check if user is admin
        if ($this->user['role'] != 'Admin') {
            $this->session->set_flashdata('error', 'You do not have permission to access this page.');
            redirect('dashboard');
        }
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Pengaturan';

        // Get settings from API
        $response = $this->Api_model->get_pengaturan();
        $this->data['settings'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/pengaturan/index');
    }

    public function save()
    {
        $data = [
            'api_base_url' => $this->input->post('api_base_url'),
            'api_timeout' => $this->input->post('api_timeout'),
            'items_per_page' => $this->input->post('items_per_page'),
            'app_name' => $this->input->post('app_name'),
            'app_logo' => $this->input->post('app_logo')
        ];

        $response = $this->Api_model->update_pengaturan($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Pengaturan berhasil disimpan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan pengaturan!');
        }

        redirect('pengaturan');
    }
}