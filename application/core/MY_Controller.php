<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    protected $data = array();
    protected $user = null;

    public function __construct()
    {
        parent::__construct();

        // Load wording file
        include(APPPATH . 'views/layouts/wording.php'); // hasilnya $wording array
        $this->data['wording'] = $wording;

        // Load model dan library yang diperlukan
        $this->load->model('Api_model');
        $this->load->library('session');
        $this->load->helper('url');

        // Cek session
        $this->check_session();

        // Set data umum untuk semua view
        $this->set_common_data();
    }

    /**
     * Fungsi untuk mengecek session user
     */
    protected function check_session()
    {
        // Kecuali untuk halaman login
        $allowed_controllers = array('auth');

        if (!in_array($this->router->class, $allowed_controllers)) {
            // Cek apakah user sudah login
            if (!$this->session->userdata('logged_in')) {
                // Simpan URL yang diakses sebelum redirect
                $this->session->set_userdata('redirect_url', current_url());

                // Redirect ke halaman login
                redirect('auth');
            } else {
                // Ambil data user dari session
                $this->user = $this->session->userdata();
            }
        }
    }

    /**
     * Fungsi untuk render view dengan layout
     * 
     * @param string $view Nama view yang akan ditampilkan
     * @param array $data Data yang akan dikirim ke view
     * @param bool $return Apakah mengembalikan view sebagai string
     * @return mixed
     */
    protected function render_view($view, $data = array(), $return = FALSE)
    {
        // Merge data dengan data umum
        $data = array_merge($this->data, $data);

        // Load views
        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/notif', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('layouts/footer', $data);

        if ($return) {
            // If return is true, we need to capture the output
            // This is more complex and typically not needed for regular views
            return $this->output->get_output();
        }
    }

    /**
     * Fungsi untuk render view dengan layout admin
     * 
     * @param string $view Nama view yang akan ditampilkan
     * @param array $data Data yang akan dikirim ke view
     * @param bool $return Apakah mengembalikan view sebagai string
     * @return mixed
     */
    protected function render_admin_view($view, $data = array(), $return = FALSE)
    {
        // Cek apakah user adalah admin
        if ($this->user['role'] != 'admin') {
            // Set flash message
            $this->session->set_flashdata('error', 'You do not have permission to access this page.');

            // Redirect ke dashboard
            redirect('dashboard');
        }

        // Render view biasa
        return $this->render_view($view, $data, $return);
    }

    /**
     * Fungsi untuk render JSON response
     * 
     * @param array $data Data yang akan dikirim
     * @param int $status HTTP status code
     */
    protected function render_json($data, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    /**
     * Fungsi untuk cek permission user
     * 
     * @param string|array $roles Role yang diizinkan
     * @return bool
     */
    protected function check_permission($roles)
    {
        if (is_array($roles)) {
            return in_array($this->user['role'], $roles);
        } else {
            return $this->user['role'] == $roles;
        }
    }

    /**
     * Fungsi untuk set data umum yang akan digunakan di semua view
     */
    protected function set_common_data()
    {
        // Set title default
        $this->data['title'] = $this->config->item('app_name');

        // Set user data
        $this->data['user'] = $this->user;

        // Set active menu berdasarkan controller
        $this->data['active_menu'] = strtolower($this->router->class);

        // Set active submenu berdasarkan method
        $this->data['active_submenu'] = strtolower($this->router->method);

    }
}