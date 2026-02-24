<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
    }

    public function index()
    {
        $category_slug = $this->input->get('category');

        $data['title'] = get_setting('site_name', 'Peweka') . ' - Culture & The Future';
        $data['categories'] = $this->Category_model->get_all();
        $data['active_category'] = $category_slug;
        $data['products'] = $this->Product_model->get_all(TRUE, $category_slug, 12);

        $this->load->view('layout/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('layout/footer');
    }
}
