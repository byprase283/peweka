<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    public function login()
    {
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }
        $data['title'] = 'Admin Login - ' . get_setting('site_name', 'Peweka');
        $this->load->view('admin/login', $data);
    }

    public function authenticate()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Username dan password harus diisi.');
            redirect('admin/login');
            return;
        }

        $admin = $this->Admin_model->login(
            $this->input->post('username'),
            $this->input->post('password')
        );

        if ($admin) {
            $this->session->set_userdata([
                'admin_logged_in' => TRUE,
                'admin_id' => $admin->id,
                'admin_name' => $admin->name
            ]);
            redirect('admin');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('admin/login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login');
    }
}
