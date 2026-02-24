<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $data['title'] = 'Belanja - ' . get_setting('site_name', 'Peweka');

        // 1. Get Filters from URL
        $filters = [
            'category' => $this->input->get('category'),
            'min_price' => $this->input->get('min_price'),
            'max_price' => $this->input->get('max_price'),
            'color' => $this->input->get('color'),
            'sort' => $this->input->get('sort'),
            'search' => $this->input->get('search')
        ];

        // 2. Pagination Config
        $config['base_url'] = base_url($this->uri->segment(1));
        $config['total_rows'] = $this->Product_model->count_shop_products($filters);
        $config['per_page'] = 9;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE; // Keep filters in pagination links

        // Pagination Styling (Bootstrap/Custom)
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $page = $this->input->get('page') ? $this->input->get('page') : 0;

        // 3. Get Data
        $data['products'] = $this->Product_model->get_shop_products($config['per_page'], $page, $filters);
        $data['categories'] = $this->Category_model->get_all();
        $data['colors'] = $this->Product_model->get_all_colors();
        $data['price_range'] = $this->Product_model->get_price_range();
        $data['pagination'] = $this->pagination->create_links();
        $data['filters'] = $filters;

        $this->load->view('layout/header', $data);
        $this->load->view('shop/index', $data);
        $this->load->view('layout/footer');
    }
}
