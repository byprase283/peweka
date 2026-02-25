<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Upload $upload
 * @property Order_model $Order_model
 * @property Product_model $Product_model
 * @property Category_model $Category_model
 * @property Voucher_model $Voucher_model
 * @property Store_model $Store_model
 * @property Settings_model $Settings_model
 * @property CI_Pagination $pagination
 * @property CI_Image_lib $image_lib
 */
class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->model('Voucher_model');
        $this->load->model('Store_model');
        $this->load->library('rajaongkir');

        // Check admin auth (except for views that handle it themselves)
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }
    }

    // ==================
// DASHBOARD
// ==================
    public function index()
    {
        $data['title'] = 'Dashboard - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['total_orders'] = $this->Order_model->count_by_status();
        $data['pending'] = $this->Order_model->count_by_status('pending');
        $data['confirmed'] = $this->Order_model->count_by_status('confirmed');
        $data['shipped'] = $this->Order_model->count_by_status('shipped');
        $data['delivered'] = $this->Order_model->count_by_status('delivered');
        $data['rejected'] = $this->Order_model->count_by_status('rejected');
        $data['revenue'] = $this->Order_model->get_total_revenue();
        $data['recent_orders'] = $this->Order_model->get_all();
        $data['page'] = 'dashboard';

        $this->load->view('admin/layout', $data);
    }

    // ==================
// ORDERS
// ==================
    public function orders($status = NULL)
    {
        $this->load->library('pagination');

        $per_page = 5;
        $total_rows = $this->Order_model->count_by_status($status);

        $page = $this->input->get('page') ?: 1;
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('admin/orders' . ($status ? '/' . $status : ''));
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        // Professional Pagination Styling
        $config['full_tag_open'] = '<nav>
    <ul class="admin-pagination">';
        $config['full_tag_close'] = '</ul>
</nav>';
        $config['first_link'] = '<i class="fas fa-chevron-double-left"></i>';
        $config['last_link'] = '<i class="fas fa-chevron-double-right"></i>';
        $config['next_link'] = 'Next&nbsp;<i class="fas fa-chevron-right"></i>';
        $config['prev_link'] = '<i class="fas fa-chevron-left"></i>&nbsp;Prev';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['title'] = 'Pesanan - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['orders'] = $this->Order_model->get_all($status, $per_page, $offset);
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['page_number'] = $page;
        $data['current_status'] = $status;
        $data['pagination'] = $this->pagination->create_links();
        $data['page'] = 'orders';
        $this->load->view('admin/layout', $data);
    }

    public function order_detail($id)
    {
        $data['order'] = $this->Order_model->get_by_id($id);
        if (!$data['order']) {
            show_404();
        }
        $data['title'] = 'Detail Order #' . $data['order']->order_code . ' - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['page'] = 'order_detail';
        $this->load->view('admin/layout', $data);
    }

    public function confirm($id)
    {
        $this->Order_model->update_status($id, 'confirmed', 'paid');
        $this->session->set_flashdata('success', 'Order berhasil dikonfirmasi.');
        redirect('admin/order/' . $id);
    }

    public function reject($id)
    {
        $notes = $this->input->post('notes') ?: 'Pembayaran ditolak oleh admin.';
        $this->Order_model->update_status($id, 'rejected', 'failed', $notes);
        $this->session->set_flashdata('success', 'Order ditolak.');
        redirect('admin/order/' . $id);
    }

    public function ship($id)
    {
        $tracking_number = $this->input->post('tracking_number');
        $data = [
            'status' => 'shipped',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->input->post('tracking_number') !== NULL) {
            $data['tracking_number'] = $tracking_number;
        }

        $this->Order_model->update($id, $data);
        $this->session->set_flashdata('success', 'Order ditandai sebagai dikirim' . ($tracking_number ? ' dengan resi: ' . $tracking_number : '') . '.');
        redirect('admin/order/' . $id);
    }

    public function deliver($id)
    {
        $this->Order_model->update_status($id, 'delivered', 'paid');
        $this->session->set_flashdata('success', 'Order ditandai sebagai diterima.');
        redirect('admin/order/' . $id);
    }

    public function update_shipping($id)
    {
        $shipping_cost = (float) $this->input->post('shipping_cost');
        $order = $this->Order_model->get_by_id($id);
        if (!$order)
            show_404();

        $total = $order->subtotal - $order->discount + $shipping_cost;

        $this->Order_model->update($id, [
            'shipping_cost' => $shipping_cost,
            'total' => $total,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Biaya ongkir berhasil diperbarui.');
        redirect('admin/order/' . $id);
    }

    public function get_shipping_estimate($id)
    {
        $order = $this->Order_model->get_by_id($id);
        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            return;
        }

        // Standard weight 1kg
        $weight = 1000;
        $couriers = ['jne', 'pos', 'tiki', 'jnt', 'sicepat'];
        $results = [];

        // Fallback origin (default store or first store)
        $store = $this->Store_model->get_by_id($order->store_id) ?: $this->Store_model->get_default();
        $origin = $store ? ($store->subdistrict_id ?: $store->city_id) : 532;
        $originType = ($store && $store->subdistrict_id) ? 'subdistrict' : 'city';

        $destination = $order->subdistrict_id ?: $order->city_id;
        $destinationType = $order->subdistrict_id ? 'subdistrict' : 'city';

        foreach ($couriers as $courier) {
            $cost = $this->rajaongkir->get_cost($origin, $destination, $weight, $courier, $originType, $destinationType);
            if (isset($cost['data'])) {
                $results[$courier] = $cost['data'];
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $results]);
    }

    public function send_wa($id)
    {
        $order = $this->Order_model->get_by_id($id);
        if (!$order) {
            show_404();
        }

        $status_text = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Pembayaran Dikonfirmasi',
            'shipped' => 'Sedang Dikirim',
            'delivered' => 'Telah Diterima',
            'rejected' => 'Ditolak'
        ];

        $track_url = base_url('order/track/' . $order->order_code);
        $message = "Halo {$order->customer_name},\n\n";
        $message .= "Update pesanan " . get_setting('site_name', 'Peweka') . " kamu:\n";
        $message .= "\xf0\x9f\x93\xa6 Order: {$order->order_code}\n";
        $message .= "\xf0\x9f\x93\x8b Status: {$status_text[$order->status]}\n";
        $message .= "\xf0\x9f\x92\xb0 Total: Rp " . number_format($order->total, 0, ',', '.') . "\n\n";
        $message .= "\xf0\x9f\x94\x97 Cek status order:\n{$track_url}\n\n";

        // Add payment link if Midtrans and pending
        if ($order->payment_method === 'midtrans' && $order->status === 'pending') {
            $payment_url = base_url('order/success/' . $order->order_code);
            $message .= "\xf0\x9f\x92\xb3 Link Pembayaran:\n{$payment_url}\n\n";
        }

        $message .= "Terima kasih sudah belanja di " . get_setting('site_name', 'Peweka') . "! \xf0\x9f\x99\x8f\n";
        $message .= "Culture & The Future \xe2\x9c\xa8";

        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $wa_url = 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' . urlencode($message);
        header("Location: " . $wa_url);
        exit;
    }

    // ==================
// PRODUCTS
// ==================
    public function products()
    {
        $this->load->library('pagination');

        $per_page = 5;
        $total_rows = $this->Product_model->count_all(FALSE);

        $page = $this->input->get('page') ?: 1;
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('admin/products');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        // Professional Pagination Styling
        $config['full_tag_open'] = '<nav>
    <ul class="admin-pagination">';
        $config['full_tag_close'] = '</ul>
</nav>';
        $config['first_link'] = '<i class="fas fa-chevron-double-left"></i>';
        $config['last_link'] = '<i class="fas fa-chevron-double-right"></i>';
        $config['next_link'] = 'Next&nbsp;<i class="fas fa-chevron-right"></i>';
        $config['prev_link'] = '<i class="fas fa-chevron-left"></i>&nbsp;Prev';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['title'] = 'Produk - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['products'] = $this->Product_model->get_all(FALSE, NULL, $per_page, $offset);
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['page_number'] = $page;
        $data['pagination'] = $this->pagination->create_links();
        $data['page'] = 'products';
        $this->load->view('admin/layout', $data);
    }

    public function product_create()
    {
        $data['title'] = 'Tambah Produk - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['page'] = 'product_form';
        $data['product'] = NULL;
        $data['categories'] = $this->Category_model->get_all();
        $this->load->view('admin/layout', $data);
    }

    public function product_store()
    {
        $this->form_validation->set_rules('name', 'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('price', 'Harga', 'required|integer');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/product/create');
            return;
        }

        $image_name = 'default.jpg';
        if ($_FILES['image']['name']) {
            $config_upload = [
                'upload_path' => './assets/img/products/',
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size' => 10240, // Increased to 10MB
                'encrypt_name' => TRUE
            ];
            $this->upload->initialize($config_upload);
            if ($this->upload->do_upload('image')) {
                $image_name = $this->upload->data('file_name');
                $resize = $this->_resize_image('./assets/img/products/' . $image_name);
                if ($resize !== TRUE) {
                    $this->session->set_flashdata('error', 'Gambar utama diupload, tapi gagal dioptimasi: ' . $resize);
                }
            } else {
                $upload_error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error', 'Gagal upload gambar utama: ' . $upload_error);
                redirect('admin/product/create');
                return;
            }
        }

        $product_id = $this->Product_model->create([
            'name' => $this->input->post('name'),
            'slug' => $this->_create_slug($this->input->post('name')),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'image' => $image_name,
            'category_id' => $this->input->post('category_id') ?: NULL,
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ]);

        // Add variants
        $sizes = $this->input->post('variant_size');
        $colors = $this->input->post('variant_color');
        $hexes = $this->input->post('variant_hex');
        $stocks = $this->input->post('variant_stock');

        if ($sizes) {
            for ($i = 0; $i < count($sizes); $i++) {
                if (!empty($sizes[$i]) && !empty($colors[$i])) {
                    $this->
                        Product_model->add_variant([
                                'product_id' => $product_id,
                                'size' => $sizes[$i],
                                'color' => $colors[$i],
                                'color_hex' => $hexes[$i] ?: '#000000',
                                'stock' => (int) $stocks[$i]
                            ]);
                }
            }
        }

        // Handle Multiple Images (Gallery)
        if (!empty($_FILES['gallery']['name'][0])) {
            $files_count = count($_FILES['gallery']['name']);
            for ($i = 0; $i < $files_count; $i++) {
                $_FILES['gallery_file']['name'] = $_FILES['gallery']['name'][$i];
                $_FILES['gallery_file']['type'] = $_FILES['gallery']['type'][$i];
                $_FILES['gallery_file']['tmp_name'] = $_FILES['gallery']['tmp_name'][$i];
                $_FILES['gallery_file']['error'] = $_FILES['gallery']['error'][$i];
                $_FILES['gallery_file']['size'] = $_FILES['gallery']['size'][$i];
                $config_gallery = [
                    'upload_path' =>
                        './assets/img/products/',
                    'allowed_types' => 'jpg|jpeg|png|gif|webp',
                    'max_size' => 10240, // Increased to 10MB
                    'encrypt_name' => TRUE
                ];

                $this->upload->initialize($config_gallery);

                if ($this->upload->do_upload('gallery_file')) {
                    $gallery_data = $this->upload->data();
                    $resize = $this->_resize_image('./assets/img/products/' . $gallery_data['file_name']);
                    if ($resize !== TRUE) {
                        $this->session->set_flashdata('error', 'Beberapa gambar galeri gagal dioptimasi: ' . $resize);
                    }
                    $this->Product_model->add_image([
                        'product_id' => $product_id,
                        'image' => $gallery_data['file_name']
                    ]);
                } else {
                    $upload_error = $this->upload->display_errors('', '');
                    $this->session->set_flashdata('error', 'Gagal upload galeri: ' . $upload_error);
                    redirect('admin/product/edit/' . $product_id); // This works for store too if redirected carefully, but let's stick to simple
                    return;
                }
            }
        }

        $this->session->set_flashdata('success', 'Produk berhasil ditambahkan!');
        redirect('admin/products');
    }

    public function product_edit($id)
    {
        $data['title'] = 'Edit Produk - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['product'] = $this->Product_model->get_by_id($id);
        $data['variants'] = $this->Product_model->get_variants($id);
        $data['gallery_images'] = $this->Product_model->get_images($id);
        $data['categories'] = $this->Category_model->get_all();
        if (!$data['product'])
            show_404();
        $data['page'] = 'product_form';
        $this->load->view('admin/layout', $data);
    }

    public function product_update($id)
    {
        $this->form_validation->set_rules('name', 'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('price', 'Harga', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/product/edit/' . $id);
            return;
        }

        $update_data = [
            'name' => $this->input->post('name'),
            'slug' => $this->_create_slug($this->input->post('name'), $id),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'category_id' => $this->input->post('category_id') ?: NULL,
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        if ($_FILES['image']['name']) {
            $config_upload = [
                'upload_path' => './assets/img/products/',
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size' => 10240, // Increased to 10MB
                'encrypt_name' => TRUE
            ];
            $this->upload->initialize($config_upload);
            if ($this->upload->do_upload('image')) {
                $update_data['image'] = $this->upload->data('file_name');
                $resize = $this->_resize_image('./assets/img/products/' . $update_data['image']);
                if ($resize !== TRUE) {
                    $this->session->set_flashdata('error', 'Gambar utama diperbarui, tapi gagal dioptimasi: ' . $resize);
                }
            } else {
                $upload_error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error', 'Gagal upload gambar utama: ' . $upload_error);
                redirect('admin/product/edit/' . $id);
                return;
            }
        }

        $this->Product_model->update($id, $update_data);

        // Update variants - delete and re-add
        $this->Product_model->delete_variants($id);
        $sizes = $this->input->post('variant_size');
        $colors = $this->input->post('variant_color');
        $hexes = $this->input->post('variant_hex');
        $stocks = $this->input->post('variant_stock');

        if ($sizes) {
            for ($i = 0; $i < count($sizes); $i++) {
                if (!empty($sizes[$i]) && !empty($colors[$i])) {
                    $this->
                        Product_model->add_variant([
                                'product_id' => $id,
                                'size' => $sizes[$i],
                                'color' => $colors[$i],
                                'color_hex' => $hexes[$i] ?: '#000000',
                                'stock' => (int) $stocks[$i]
                            ]);
                }
            }
        }

        // Handle Multiple Images (Gallery)
        if (!empty($_FILES['gallery']['name'][0])) {
            $files_count = count($_FILES['gallery']['name']);
            for ($i = 0; $i < $files_count; $i++) {
                $_FILES['gallery_file']['name'] = $_FILES['gallery']['name'][$i];
                $_FILES['gallery_file']['type'] = $_FILES['gallery']['type'][$i];
                $_FILES['gallery_file']['tmp_name'] = $_FILES['gallery']['tmp_name'][$i];
                $_FILES['gallery_file']['error'] = $_FILES['gallery']['error'][$i];
                $_FILES['gallery_file']['size'] = $_FILES['gallery']['size'][$i];
                $config_gallery = [
                    'upload_path' =>
                        './assets/img/products/',
                    'allowed_types' => 'jpg|jpeg|png|gif|webp',
                    'max_size' => 10240, // 10MB
                    'encrypt_name' => TRUE
                ];

                $this->upload->initialize($config_gallery);

                if ($this->upload->do_upload('gallery_file')) {
                    $gallery_data = $this->upload->data();
                    $resize = $this->_resize_image('./assets/img/products/' . $gallery_data['file_name']);
                    if ($resize !== TRUE) {
                        $this->session->set_flashdata('error', 'Beberapa gambar galeri gagal dioptimasi: ' . $resize);
                    }
                    $this->Product_model->add_image([
                        'product_id' => $id,
                        'image' => $gallery_data['file_name']
                    ]);
                } else {
                    $upload_error = $this->upload->display_errors('', '');
                    $this->session->set_flashdata('error', 'Gagal upload galeri: ' . $upload_error);
                    redirect('admin/product/edit/' . $id);
                    return;
                }
            }
        }

        $this->session->set_flashdata('success', 'Produk berhasil diperbarui!');
        redirect('admin/products');
    }

    public function product_delete_image($image_id, $product_id)
    {
        $image = $this->Product_model->get_image_by_id($image_id);
        if ($image) {
            $path = './assets/img/products/' . $image->image;
            if (file_exists($path)) {
                unlink($path);
            }
            $this->Product_model->delete_image($image_id);
            $this->session->set_flashdata('success', 'Gambar berhasil dihapus.');
        }
        redirect('admin/product/edit/' . $product_id);
    }

    public function product_delete($id)
    {
        $this->Product_model->delete($id);
        $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        redirect('admin/products');
    }

    // ==================
    // CATEGORIES
    // ==================
    public function categories()
    {
        $this->load->library('pagination');

        $per_page = 5;
        $total_rows = $this->Category_model->count_all();

        $page = $this->input->get('page') ?: 1;
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('admin/categories');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        // Professional Pagination Styling
        $config['full_tag_open'] = '<nav>
                    <ul class="admin-pagination">';
        $config['full_tag_close'] = '</ul>
                </nav>';
        $config['first_link'] = '<i class="fas fa-chevron-double-left"></i>';
        $config['last_link'] = '<i class="fas fa-chevron-double-right"></i>';
        $config['next_link'] = 'Next&nbsp;<i class="fas fa-chevron-right"></i>';
        $config['prev_link'] = '<i class="fas fa-chevron-left"></i>&nbsp;Prev';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['title'] = 'Kategori - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['categories'] = $this->Category_model->get_all($per_page, $offset);
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['page_number'] = $page;
        $data['pagination'] = $this->pagination->create_links();
        $data['page'] = 'categories';
        $this->load->view('admin/layout', $data);
    }

    public function category_create()
    {
        $data['title'] = 'Tambah Kategori - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['page'] = 'category_form';
        $data['category'] = NULL;
        $this->load->view('admin/layout', $data);
    }

    public function category_store()
    {
        $this->form_validation->set_rules('name', 'Nama Kategori', 'required|trim');
        $this->form_validation->set_rules('slug', 'Slug', 'required|trim|is_unique[categories.slug]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/category/create');
            return;
        }

        $this->Category_model->create([
            'name' => $this->input->post('name'),
            'slug' => $this->input->post('slug')
        ]);

        $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan!');
        redirect('admin/categories');
    }

    public function category_edit($id)
    {
        $data['title'] = 'Edit Kategori - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['category'] = $this->Category_model->get_by_id($id);
        if (!$data['category'])
            show_404();
        $data['page'] = 'category_form';
        $this->load->view('admin/layout', $data);
    }

    public function category_update($id)
    {
        $this->form_validation->set_rules('name', 'Nama Kategori', 'required|trim');

        $original_slug = $this->Category_model->get_by_id($id)->slug;
        if ($this->input->post('slug') != $original_slug) {
            $this->form_validation->set_rules('slug', 'Slug', 'required|trim|is_unique[categories.slug]');
        } else {
            $this->form_validation->set_rules('slug', 'Slug', 'required|trim');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/category/edit/' . $id);
            return;
        }

        $this->Category_model->update($id, [
            'name' => $this->input->post('name'),
            'slug' => $this->input->post('slug')
        ]);

        $this->session->set_flashdata('success', 'Kategori berhasil diperbarui!');
        redirect('admin/categories');
    }

    public function category_delete($id)
    {
        $this->Category_model->delete($id);
        $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        redirect('admin/categories');
    }

    // ==================
    // VOUCHERS
    // ==================
    public function vouchers()
    {
        $this->load->library('pagination');

        $per_page = 5;
        $total_rows = $this->Voucher_model->count_all();

        $page = $this->input->get('page') ?: 1;
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('admin/vouchers');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        // Professional Pagination Styling
        $config['full_tag_open'] = '<nav>
                    <ul class="admin-pagination">';
        $config['full_tag_close'] = '</ul>
                </nav>';
        $config['first_link'] = '<i class="fas fa-chevron-double-left"></i>';
        $config['last_link'] = '<i class="fas fa-chevron-double-right"></i>';
        $config['next_link'] = 'Next&nbsp;<i class="fas fa-chevron-right"></i>';
        $config['prev_link'] = '<i class="fas fa-chevron-left"></i>&nbsp;Prev';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['title'] = 'Voucher - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['vouchers'] = $this->Voucher_model->get_all($per_page, $offset);
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['page_number'] = $page;
        $data['pagination'] = $this->pagination->create_links();
        $data['page'] = 'vouchers';
        $this->load->view('admin/layout', $data);
    }

    public function voucher_create()
    {
        $data['title'] = 'Tambah Voucher - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['page'] = 'voucher_form';
        $data['voucher'] = NULL;
        $this->load->view('admin/layout', $data);
    }

    public function voucher_store()
    {
        $this->form_validation->set_rules('code', 'Kode Voucher', 'required|trim|is_unique[vouchers.code]');
        $this->form_validation->set_rules('type', 'Tipe', 'required');
        $this->form_validation->set_rules('value', 'Nilai', 'required|numeric');
        $this->form_validation->set_rules('quota', 'Kuota', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/voucher/create');
            return;
        }

        $this->Voucher_model->create([
            'code' => $this->input->post('code'),
            'type' => $this->input->post('type'),
            'value' => $this->input->post('value'),
            'min_order' => $this->input->post('min_order') ?: 0,
            'max_discount' => $this->input->post('max_discount') ?: NULL,
            'quota' => $this->input->post('quota'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
            'expired_at' => $this->input->post('expired_at') ?: NULL
        ]);

        $this->session->set_flashdata('success', 'Voucher berhasil ditambahkan!');
        redirect('admin/vouchers');
    }

    public function voucher_edit($id)
    {
        $data['title'] = 'Edit Voucher - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['voucher'] = $this->Voucher_model->get_by_id($id);
        if (!$data['voucher'])
            show_404();
        $data['page'] = 'voucher_form';
        $this->load->view('admin/layout', $data);
    }

    public function voucher_update($id)
    {
        $this->form_validation->set_rules('code', 'Kode Voucher', 'required|trim');
        $this->form_validation->set_rules('type', 'Tipe', 'required');
        $this->form_validation->set_rules('value', 'Nilai', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/voucher/edit/' . $id);
            return;
        }

        $this->Voucher_model->update($id, [
            'code' => $this->input->post('code'),
            'type' => $this->input->post('type'),
            'value' => $this->input->post('value'),
            'min_order' => $this->input->post('min_order') ?: 0,
            'max_discount' => $this->input->post('max_discount') ?: NULL,
            'quota' => $this->input->post('quota'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
            'expired_at' => $this->input->post('expired_at') ?: NULL
        ]);

        $this->session->set_flashdata('success', 'Voucher berhasil diperbarui!');
        redirect('admin/vouchers');
    }

    public function voucher_delete($id)
    {
        $this->Voucher_model->delete($id);
        $this->session->set_flashdata('success', 'Voucher berhasil dihapus');
        redirect('admin/vouchers');
    }

    // ==================
    // SETTINGS / MASTER DATA
    // ==================
    public function settings()
    {
        $this->load->model('Settings_model');
        $data['title'] = 'Pengaturan Toko - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['settings'] = $this->Settings_model->get_settings();
        $data['page'] = 'settings';
        $this->load->view('admin/layout', $data);
    }

    public function settings_update()
    {
        $this->load->model('Settings_model');

        $data = [
            'site_name' => $this->input->post('site_name'),
            'site_description' => $this->input->post('site_description'),
            'site_about' => $this->input->post('site_about'),
            'instagram_url' => $this->input->post('instagram_url'),
            'facebook_url' => $this->input->post('facebook_url'),
            'whatsapp_number' => $this->input->post('whatsapp_number'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
            'theme_color' => $this->input->post('theme_color'),
            'theme_font_heading' => $this->input->post('theme_font_heading'),
            'theme_font_body' => $this->input->post('theme_font_body'),
            'theme_preset' => $this->input->post('theme_preset'),
            'theme_bg_color' => $this->input->post('theme_bg_color'),
            'theme_text_color' => $this->input->post('theme_text_color'),
            'global_discount_percent' => (int) $this->input->post('global_discount_percent'),
            'global_discount_name' => $this->input->post('global_discount_name')
        ];

        // Handle Logo Upload
        if (!empty($_FILES['site_logo']['name'])) {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|svg|webp';
            $config['max_size'] = 2048;
            $config['file_name'] = 'logo_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('site_logo')) {
                $upload_data = $this->upload->data();
                $data['site_logo'] = $upload_data['file_name'];

                // Optional: Delete old logo
                $old_settings = $this->Settings_model->get_settings();
                if ($old_settings->site_logo != 'logo.png' && file_exists('./assets/img/' . $old_settings->site_logo)) {
                    unlink('./assets/img/' . $old_settings->site_logo);
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal upload logo: ' . $this->upload->display_errors('', ''));
                redirect('admin/settings');
            }
        }

        // Handle Favicon Upload
        if (!empty($_FILES['site_favicon']['name'])) {
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|webp|ico';
            $config['max_size'] = 1024;
            $config['file_name'] = 'favicon_' . time();

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('site_favicon')) {
                $upload_data = $this->upload->data();
                $favicon_name = $upload_data['file_name'];

                // Resize Favicon to 32x32
                $resize_config['image_library'] = 'gd2';
                $resize_config['source_image'] = './assets/img/' . $favicon_name;
                $resize_config['maintain_ratio'] = TRUE;
                $resize_config['width'] = 32;
                $resize_config['height'] = 32;

                $this->load->library('image_lib', $resize_config);
                $this->image_lib->resize();

                $data['site_favicon'] = $favicon_name;

                // Optional: Delete old favicon
                $old_settings = $this->Settings_model->get_settings();
                if (
                    $old_settings->site_favicon != 'favicon.png' && file_exists('./assets/img/' .
                        $old_settings->site_favicon)
                ) {
                    unlink('./assets/img/' . $old_settings->site_favicon);
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal upload favicon: ' . $this->upload->display_errors(
                    '',
                    ''
                ));
                redirect('admin/settings');
            }
        }

        $this->Settings_model->update_settings($data);
        $this->session->set_flashdata('success', 'Pengaturan berhasil diperbarui');
        redirect('admin/settings');
    }

    public function change_password()
    {
        $data['title'] = 'Ganti Password - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['page'] = 'change_password';
        $this->load->view('admin/layout', $data);
    }

    public function update_password()
    {
        $this->form_validation->set_rules('old_password', 'Password Lama', 'required');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[5]');
        $this->form_validation->set_rules(
            'confirm_password',
            'Konfirmasi Password Baru',
            'required|matches[new_password]'
        );

        if ($this->form_validation->run() == FALSE) {
            $this->change_password();
            return;
        }

        $admin_id = $this->session->userdata('admin_id');
        $this->load->model('Admin_model');
        $admin = $this->Admin_model->get_by_id($admin_id);

        if ($admin && password_verify($this->input->post('old_password'), $admin->password)) {
            $new_password_hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);
            $this->Admin_model->update_password($admin_id, $new_password_hash);

            $this->session->set_flashdata('success', 'Password berhasil diubah');
            redirect('admin/change_password');
        } else {
            $this->session->set_flashdata('error', 'Password lama salah');
            redirect('admin/change_password');
        }
    }

    // ==================
    // STORES
    // ==================
    public function stores()
    {
        $data['title'] = 'Kelola Toko - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['stores'] = $this->Store_model->get_all();
        $data['page'] = 'stores';
        $this->load->view('admin/layout', $data);
    }

    public function store_edit($id)
    {
        $data['store'] = $this->Store_model->get_by_id($id);
        if (!$data['store']) {
            show_404();
        }

        $provinces_res = $this->rajaongkir->get_provinces();
        $data['provinces'] = isset($provinces_res['data']) ? $provinces_res['data'] : [];

        $data['title'] = 'Edit Toko - ' . get_setting('site_name', 'Peweka') . ' Admin';
        $data['page'] = 'store_form';
        $this->load->view('admin/layout', $data);
    }

    public function store_update($id)
    {
        $this->form_validation->set_rules('name', 'Nama Toko', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/store/edit/' . $id);
            return;
        }

        $data = [
            'name' => $this->input->post('name'),
            'phone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
            'province_id' => $this->input->post('province_id'),
            'province_name' => $this->input->post('province_name'),
            'city_id' => $this->input->post('city_id'),
            'city_name' => $this->input->post('city_name'),
            'district_id' => $this->input->post('district_id'),
            'district_name' => $this->input->post('district_name'),
            'subdistrict_id' => $this->input->post('subdistrict_id'),
            'subdistrict_name' => $this->input->post('subdistrict_name'),
            'is_default' => $this->input->post('is_default') ? 1 : 0
        ];

        // If this is set as default, unset others first
        if ($data['is_default']) {
            $this->Store_model->reset_defaults();
        }

        $this->Store_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data toko berhasil diperbarui!');
        redirect('admin/stores');
    }

    /**
     * Helper to resize image
     * @param string $path Full path to image file
     * @param int $width Max width
     * @param int $height Max height
     */
    private function _resize_image($path, $width = 800, $height = 800)
    {
        // Boost memory and time for large images
        ini_set('memory_limit', '512M');
        set_time_limit(180);

        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['quality'] = 60; // Compress quality (1-100)
        $config['master_dim'] = 'auto';

        if (!isset($this->image_lib)) {
            $this->load->library('image_lib');
        }

        $this->image_lib->initialize($config);

        if (!$this->image_lib->resize()) {
            return $this->image_lib->display_errors();
        }

        $this->image_lib->clear();
        return TRUE;
    }


    private function _create_slug($title, $id = null)
    {
        $this->load->helper('url');
        // Handle slashes and other potentially problematic characters
        $title = str_replace(['/', '\\'], '-', $title);
        $slug = url_title($title, 'dash', TRUE);

        $this->db->where('slug', $slug);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get('products');

        if ($query->num_rows() > 0) {
            $slug = $slug . '-' . time();
        }
        return $slug;
    }

    public function bulk_optimize()
    {
        $dir = './assets/img/products/';
        $files = scandir($dir);
        $count = 0;
        $errors = [];

        foreach ($files as $file) {
            if ($file != "." && $file != ".." && $file != "default.svg" && is_file($dir . $file)) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp'])) {
                    $size = filesize($dir . $file);
                    // Only optimize if larger than 500KB
                    if ($size > 500 * 1024) {
                        $res = $this->_resize_image($dir . $file);
                        if ($res === TRUE) {
                            $count++;
                        } else {
                            $errors[] = "File {$file} gagal: " . $res;
                        }
                    }
                }
            }
        }

        if ($count > 0) {
            $msg = "Berhasil mengoptimasi {$count} gambar.";
            if ($errors) {
                $msg .= " Namun ada beberapa error: " . implode(', ', $errors);
            }
            $this->session->set_flashdata('success', $msg);
        } else {
            $this->session->set_flashdata('info', 'Tidak ada gambar baru yang perlu dioptimasi (semua sudah di bawah 500KB).');
        }

        redirect('admin/settings');
    }
}
