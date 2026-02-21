<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function index()
    {
        $data['title'] = 'Keranjang Belanja - ' . get_setting('site_name', 'Peweka');
        $this->load->view('layout/header', $data);
        $this->load->view('cart/index', $data);
        $this->load->view('layout/footer');
    }
}
