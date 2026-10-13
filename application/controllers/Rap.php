<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rap extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'RAP';
        $this->data['active_menu'] = 'rap';
        $this->data['active_submenu'] = 'rap';

        // Get raps from API
        $response = $this->Api_model->get_rap(data_login_user());
        $this->data['raps'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/rap/list');
    }
    public function add()
    {
        $this->data['title'] = 'Tambah RAP';
        $this->data['active_menu'] = 'rap';
        $this->data['active_submenu'] = 'rap';

        $proyeks = $this->Api_model->get_gudang(data_login_user(['warehouse_type' => 1]));
        $this->data['proyeks'] = $proyeks['success'] ? $proyeks['data'] : [];

        // Form add HANYA untuk header, tidak ada kategori
        $this->render_view('pages/rap/form');
    }

    public function category_save()
    {
        $rap_id = $this->input->post('rap_id');
        $parent_category_id = $this->input->post('parent_category_id') ?: null;
        $names = $this->input->post('names');

        if (empty($rap_id) || empty($names)) {
            return $this->json_response([
                'success' => false,
                'message' => 'Data tidak lengkap.'
            ]);
        }

        // Ambil header RAP untuk melengkapi payload
        $rapData = data_login_user(['rap_id' => $rap_id]);
        $rap = $this->Api_model->get_rap($rapData);
        $rapHeader = $rap['success'] ? $rap['data'][0] : [];

        $categoryArray = [];
        foreach ($names as $name) {
            $categoryArray[] = ['id' => null, 'name' => $name];
        }

        $data = data_login_user([
            'name' => $rapHeader['rap_name'] ?? '',
            'contract_number' => $rapHeader['contract_number'] ?? '',
            'rap_id' => $rap_id,
            'warehouse_id' => $rapHeader['warehouse_id'] ?? '',
            'parent_category_id' => $parent_category_id,
            'category' => $categoryArray
        ]);

        $response = $this->Api_model->add_rap_category($data);

        return $this->json_response($response);
    }

    public function category_delete()
    {
        $rap_id = $this->input->post('rap_id');
        $category_id = $this->input->post('category_id');

        $data = data_login_user([
            'rap_id' => $rap_id,
            'category_id' => $category_id
        ]);

        $response = $this->Api_model->delete_rap_category($data);

        return $this->json_response($response);
    }

    /**
     * Helper: paksa output JSON murni tanpa dibungkus layout/template
     */
    private function json_response($data)
    {
        $this->output
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
        // return di sini penting supaya method langsung berhenti,
        // tidak lanjut ke $this->render_view() atau hook layout lain
    }

    public function edit($id)
    {
        $this->data['title'] = 'Edit RAP';
        $this->data['active_menu'] = 'rap';
        $this->data['active_submenu'] = 'rap';

        $proyeks = $this->Api_model->get_gudang(data_login_user(['warehouse_type' => 1]));
        $this->data['proyeks'] = $proyeks['success'] ? $proyeks['data'] : [];

        $data = data_login_user(['rap_id' => $id]);

        // Header RAP
        $rap = $this->Api_model->get_rap($data);
        $this->handle_response($rap);
        $this->data['rap'] = $rap['success'] ? $rap['data'][0] : [];

        // Kategori & sub kategori — API sudah mengembalikan bentuk tree via 'sub_category'
        $categoryData = data_login_user(['rap_id' => $id, 'category_id' => null]);
        $categoryResp = $this->Api_model->get_rap_category($categoryData);
        $this->data['categories'] = $categoryResp['success'] ? $categoryResp['data'] : [];

        $this->render_view('pages/rap/edit');
    }

    /**
     * Susun flat list kategori jadi struktur tree berdasarkan parent_category_id
     */
    private function build_category_tree($flat, $parentId = null)
    {
        $tree = [];
        foreach ($flat as $item) {
            if ($item['parent_category_id'] == $parentId) {
                $children = $this->build_category_tree($flat, $item['category_id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }


    public function save()
    {
        $id = $this->input->post('id');

        if ($id) {
            // Prepare data according to API format
            $data = data_login_user([
                'id' => $id,
                'warehouse_id' => $this->input->post('warehouse_id'),
                'name' => $this->input->post('name'),
                'contract_number' => $this->input->post('contract_number'),
            ]);
            // Update existing RAP
            $response = $this->Api_model->update_rap($data);
            $this->handle_response($response, 'RAP berhasil diperbarui!');
        } else {
            $data = data_login_user([
                'warehouse_id' => $this->input->post('warehouse_id'),
                'name' => $this->input->post('name'),
                'contract_number' => $this->input->post('contract_number'),
            ]);
            // Add new RAP
            $response = $this->Api_model->add_rap($data);
            $this->handle_response($response, 'RAP berhasil ditambahkan!');
        }

        redirect('rap');
    }

    public function delete($id)
    {
        // Prepare data according to API format
        $data = data_login_user([
            'rap_id' => $id
        ]);

        $response = $this->Api_model->delete_rap($data);
        $this->handle_response($response, 'RAP berhasil dihapus!');

        redirect('rap');
    }

}