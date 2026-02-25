<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function index()
    {
        $data['title'] = 'Produk - ' . get_setting('site_name', 'Peweka');
        $data['products'] = $this->Product_model->get_all(TRUE);
        $this->load->view('layout/header', $data);
        $this->load->view('product/index', $data);
        $this->load->view('layout/footer');
    }

    public function detail($slug)
    {
        $data['product'] = $this->Product_model->get_by_slug($slug);
        if (!$data['product']) {
            show_404();
        }
        $product_id = $data['product']->id;
        $data['variants'] = $this->Product_model->get_variants($product_id);
        $data['images'] = $this->Product_model->get_images($product_id);
        $data['sizes'] = $this->Product_model->get_available_sizes($product_id);
        $data['title'] = $data['product']->name . ' - ' . get_setting('site_name', 'Peweka');

        // Group variants by size then color
        $grouped = [];
        foreach ($data['variants'] as $v) {
            $grouped[$v->size][] = $v;
        }
        $data['grouped_variants'] = $grouped;

        $this->load->view('layout/header', $data);
        $this->load->view('product/detail', $data);
        $this->load->view('layout/footer');
    }
}
