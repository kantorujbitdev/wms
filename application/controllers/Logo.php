<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logo extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Logo_model', 'logo_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->check_permission('logo_pengaturan', 'view');
    }

    public function index()
    {
        $data['logo'] = $this->logo_model->get_all();
        $data['title'] = 'Logo Management';
        $data['active_menu'] = 'pengaturan';
        $data['active_submenu'] = 'logo';
        $this->render_admin_view('pages/logo/index_logo', $data);
    }

    public function add()
    {
        $this->check_permission('logo_pengaturan', 'edit');

        if ($this->input->post()) {

            $this->form_validation->set_rules(
                'nama_pt',
                'Nama Perusahaan',
                'required|trim'
            );

            if ($this->form_validation->run() == FALSE) {

                $data['title'] = 'Tambah Logo';
                $this->render_admin_view('pages/logo/form', $data);
                return;
            }

            if (empty($_FILES['logo']['name'])) {

                $this->session->set_flashdata(
                    'error',
                    'Logo wajib diupload'
                );

                redirect('logo/add');
            }

            $upload = $this->upload_logo();

            if (!$upload['success']) {

                $this->session->set_flashdata(
                    'error',
                    $upload['message']
                );

                redirect('logo/add');
            }

            $insert = [
                'nama_pt' => $this->input->post('nama_pt', true),
                'logo' => $upload['path'],
                'status_aktif' => $this->input->post('status_aktif') ? 1 : 0
            ];

            $this->logo_model->insert($insert);

            $user = $this->session->userdata('username');

            save_log(
                "➕ User {$user} menambahkan logo perusahaan {$insert['nama_pt']}",
                'success'
            );

            $this->session->set_flashdata(
                'success',
                'Logo berhasil ditambahkan'
            );

            redirect('logo');
        }

        $data['title'] = 'Tambah Logo';
        $data['active_menu'] = 'pengaturan';
        $data['active_submenu'] = 'logo';

        $this->render_admin_view('pages/logo/form', $data);
    }

    public function edit($id)
    {
        $item = $this->logo_model->get_by_id($id);

        if (!$item) {
            show_404();
        }

        if ($this->input->post()) {

            $this->form_validation->set_rules(
                'nama_pt',
                'Nama Perusahaan',
                'required|trim'
            );

            if ($this->form_validation->run() == FALSE) {

                $data['item'] = $item;
                $data['title'] = 'Edit Logo';

                $this->render_admin_view(
                    'pages/logo/form',
                    $data
                );

                return;
            }

            $update = [
                'nama_pt' => $this->input->post('nama_pt', true),
                'status_aktif' => $this->input->post('status_aktif') ? 1 : 0
            ];

            if (!empty($_FILES['logo']['name'])) {

                $upload = $this->upload_logo();

                if (!$upload['success']) {

                    $this->session->set_flashdata(
                        'error',
                        $upload['message']
                    );

                    redirect('logo/edit/' . $id);
                }

                // hapus file lama
                if (!empty($item['logo'])) {

                    $old_file = FCPATH . $item['logo'];

                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }

                $update['logo'] = $upload['path'];
            }

            $this->logo_model->update($id, $update);
            $user = $this->session->userdata('username');
            save_log(
                "✏️ User {$user} mengubah logo perusahaan {$update['nama_pt']}",
                'success'
            );

            $this->session->set_flashdata(
                'success',
                'Data berhasil diperbarui'
            );

            redirect('logo');
        }

        $data['item'] = $item;
        $data['title'] = 'Edit Logo';
        $data['active_menu'] = 'pengaturan';
        $data['active_submenu'] = 'logo';

        $this->render_admin_view(
            'pages/logo/form',
            $data
        );
    }

    private function upload_logo()
    {
        if (!is_dir('./uploads/logo')) {
            mkdir('./uploads/logo', 0777, true);
        }

        $config['upload_path'] = './uploads/logo/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = true;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('logo')) {
            return [
                'success' => false,
                'message' => $this->upload->display_errors()
            ];
        }

        $upload = $this->upload->data();

        return [
            'success' => true,
            'path' => 'uploads/logo/' . $upload['file_name']
        ];
    }
}