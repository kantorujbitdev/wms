<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Data_api_model', 'data_api_model');
        $this->check_permission('api_pengaturan', 'view');
    }

    public function index()
    {
        $data['apis'] = $this->data_api_model->get_all();
        $data['title'] = 'Manajemen API';
        $data['active_menu'] = 'pengaturan';
        $data['active_submenu'] = 'api';
        $this->render_admin_view('pages/api/index', $data);
    }

    public function add()
    {
        $this->check_permission('api_pengaturan', 'edit');
        if ($this->input->post()) {
            $data = [
                'nama_api' => $this->input->post('nama_api'),
                'endpoint' => $this->input->post('endpoint'),
                'status_aktif' => $this->input->post('status_aktif') ? 1 : 0
            ];
            $this->data_api_model->insert($data);
            $this->session->set_flashdata('success', 'API berhasil ditambahkan');
            redirect('api');
        }
        $data['title'] = 'Manajemen API - Edit API';
        $data['active_menu'] = 'pengaturan';
        $data['active_submenu'] = 'api';
        $this->render_admin_view('pages/api/form', $data);
    }

    public function edit($id)
    {
        $this->check_permission('api_pengaturan', 'edit');
        $api = $this->data_api_model->get_by_id($id);
        if (!$api) {
            show_404();
        }

        if ($this->input->post()) {
            $data = [
                'nama_api' => $this->input->post('nama_api'),
                'endpoint' => $this->input->post('endpoint'),
                'status_aktif' => $this->input->post('status_aktif') ? 1 : 0
            ];
            $this->data_api_model->update($id, $data);
            $this->session->set_flashdata('success', 'API berhasil diperbarui');
            redirect('api');
        }

        $data['api'] = $api;
        $data['title'] = 'Manajemen API - Edit API';
        $data['active_menu'] = 'pengaturan';
        $data['active_submenu'] = 'api';
        $this->render_admin_view('pages/api/form', $data);
    }

    public function delete($id)
    {
        $this->check_permission('api_pengaturan', 'delete');
        $this->data_api_model->delete($id);
        $this->session->set_flashdata('success', 'API berhasil dihapus');
        redirect('api');
    }
}
