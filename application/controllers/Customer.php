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
        $response = $this->Api_model->get_customer(data_login_user());
        $this->data['customers'] = $this->handle_response($response);

        // Render view
        $this->render_view('pages/customer/index');
    }

    public function add()
    {
        // Set title
        $this->data['title'] = 'Tambah Pengguna}';
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

        $data = data_login_user(['id' => $id]);
        // Get customer data from API
        $customer = $this->Api_model->get_customer_by_id($data);
        $this->handle_response($customer);
        $this->data['customer'] = $customer['success'] ? $customer['data'][0] : [];

        // Render view
        $this->render_view('pages/customer/form');
    }

    public function save()
    {
        $id = $this->input->post('id');

        if ($id) {
            // Prepare data according to API format
            $data = data_login_user([
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'person' => $this->input->post('contact'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
            ]);
            // Update existing customer
            $response = $this->Api_model->update_customer($data);
            $message = 'Customer berhasil diperbarui!';
        } else {
            // Prepare data according to API format
            $data = data_login_user([
                'name' => $this->input->post('name'),
                'person' => $this->input->post('contact'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
            ]);
            // Add new customer
            $response = $this->Api_model->add_customer($data);
            $message = 'Customer berhasil ditambahkan!';
        }
        $this->handle_response($response, $message);

        redirect('customer');
    }

    public function delete($id)
    {
        // Prepare data according to API format
        $data = data_login_user([
            'id' => $id
        ]);

        $response = $this->Api_model->delete_customer($data);
        $this->handle_response($response, 'Customer berhasil dihapus!');
        redirect('customer');
    }
}