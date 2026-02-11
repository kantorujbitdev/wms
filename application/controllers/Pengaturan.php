<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengaturan_model', 'Pengaturan_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->check_permission('pengaturan', 'view');
    }
    // percobaan commit push  github

    public function index()
    {
        $data['pengaturan'] = $this->Pengaturan_model->get_all();
        $data['title'] = 'Pengaturan Aplikasi';
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
            if ($item['is_image'] == 'true') {
                // Handle image upload
                if (!empty($_FILES['image']['name'])) {
                    $config['upload_path'] = FCPATH . 'uploads/';
                    $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                    $config['max_size'] = 2048; // 2MB
                    $config['encrypt_name'] = TRUE;

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('image')) {
                        $upload_data = $this->upload->data();
                        $image_path = 'uploads/' . $upload_data['file_name'];

                        $data = [
                            'value' => $image_path
                        ];

                        // Log perubahan
                        $user = $this->session->userdata('username') ?? 'Unknown';
                        save_log("🖼️ User {$user} memperbarui gambar pengaturan [{$item['nama_pengaturan']}] ke {$image_path}", 'success');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('pengaturan/edit/' . $id);
                    }
                } else {
                    // Tidak ada gambar baru
                    $data = [
                        'value' => $this->input->post('current_image')
                    ];
                }
            } else {
                // Handle text value
                $this->form_validation->set_rules('value', 'Value', 'required');

                if ($this->form_validation->run() == FALSE) {
                    $data['item'] = $item;
                    $data['title'] = 'Pengaturan Aplikasi - Edit Pengaturan';
                    $data['active_menu'] = 'pengaturan';
                    $data['active_submenu'] = 'web';
                    $this->render_admin_view('pages/pengaturan/form', $data);
                    return;
                }

                $data = [
                    'value' => $this->input->post('value')
                ];

                // Log perubahan text
                $user = $this->session->userdata('username') ?? 'Unknown';
                save_log("✏️ User {$user} memperbarui nilai pengaturan [{$item['nama_pengaturan']}] ke '{$data['value']}'", 'success');
            }

            $this->Pengaturan_model->update($id, $data);
            $this->session->set_flashdata('success', 'Pengaturan berhasil diperbarui');
            redirect('pengaturan');
        }

        $data['item'] = $item;
        $data['title'] = 'Pengaturan Aplikasi - Edit Pengaturan';
        $data['active_menu'] = 'pengaturan';
        $data['active_submenu'] = 'web';
        $this->render_admin_view('pages/pengaturan/form', $data);
    }


}