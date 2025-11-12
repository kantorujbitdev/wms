<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Set title
        $this->data['title'] = 'Customer';
        $this->data['active_menu'] = 'customer';
        $this->data['active_submenu'] = 'customer';

        // Get customers from API
        $response = $this->Api_model->get_customer();
        $this->data['customers'] = $response['success'] ? $response['data'] : [];

        // Render view
        $this->render_view('pages/customer/index');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah Customer';
        $this->data['active_menu'] = 'customer';
        $this->data['active_submenu'] = 'customer';

        // Render view
        $this->render_view('pages/customer/form');
    }

    public function edit($id)
    {
        // Set title
        $this->data['title'] = 'Edit Customer';
        $this->data['active_menu'] = 'customer';
        $this->data['active_submenu'] = 'customer';

        $data['id'] = $id;
        // Get customer data from API
        $customer = $this->Api_model->get_customer_by_id($data);
        $this->data['customer'] = $customer['success'] ? $customer['data'] : [];

        // Render view
        $this->render_view('pages/customer/form');
    }

    public function save()
    {
        $id = $this->input->post('id');

        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = [
            'Name' => $this->input->post('name'),
            'Contact' => $this->input->post('contact'),
            'Phone' => $this->input->post('phone'),
            'Address' => $this->input->post('address'),
            'actionby' => $user_id
        ];

        if ($id) {
            // Update existing customer
            $data['id'] = $id;
            $response = $this->Api_model->update_customer($data);
            $message = 'Customer berhasil diperbarui!';
        } else {
            // Add new customer
            $response = $this->Api_model->add_customer($data);
            $message = 'Customer berhasil ditambahkan!';
        }

        if ($response['success']) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data customer: ' . $response['message']);
        }

        redirect('customer');
    }

    public function delete($id)
    {
        // Get current user ID from session
        $user_id = $this->session->userdata('user_id');

        // Prepare data according to API format
        $data = [
            'id' => $id,
            'actionby' => $user_id
        ];

        $response = $this->Api_model->delete_customer($data);

        if ($response['success']) {
            $this->session->set_flashdata('success', 'Customer berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus customer: ' . $response['message']);
        }

        redirect('customer');
    }
}