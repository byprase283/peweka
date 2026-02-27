<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('Product_model');
        $this->load->model('Voucher_model');
        $this->load->model('Store_model');
        $this->load->library('rajaongkir');
        $this->load->library('midtrans');
        $this->load->library('komerce_pay');
        $this->load->config('midtrans');
    }

    public function process_cart()
    {
        $cart_data = $this->input->post('cart_data');
        if (!$cart_data) {
            redirect('cart');
        }

        $this->session->set_userdata('checkout_cart', json_decode($cart_data));
        redirect('checkout');
    }

    public function checkout()
    {
        $cart = $this->session->userdata('checkout_cart');

        if (!$cart || empty($cart)) {
            redirect('cart');
        }

        $data['title'] = 'Checkout - ' . get_setting('site_name', 'Peweka');
        $data['items'] = $cart;

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item->product_price * $item->quantity;
        }
        $data['subtotal'] = $subtotal;

        // Load provinces and stores
        $provinces_res = $this->rajaongkir->get_provinces();
        $data['provinces'] = isset($provinces_res['data']) ? $provinces_res['data'] : [];
        $data['stores'] = $this->Store_model->get_all();
        $data['default_store'] = $this->Store_model->get_default();

        $this->load->view('layout/header', $data);
        $this->load->view('order/checkout', $data);
        $this->load->view('layout/footer');
    }

    // AJAX: Get cities by province
    public function get_cities($province_id)
    {
        $cities = $this->rajaongkir->get_cities($province_id);
        header('Content-Type: application/json');
        echo json_encode($cities);
    }

    // AJAX: Get districts by city
    public function get_districts($city_id)
    {
        $districts = $this->rajaongkir->get_districts($city_id);
        header('Content-Type: application/json');
        echo json_encode($districts);
    }

    // AJAX: Get subdistricts by district
    public function get_subdistricts($district_id)
    {
        $subdistricts = $this->rajaongkir->get_subdistricts($district_id);
        header('Content-Type: application/json');
        echo json_encode($subdistricts);
    }

    // AJAX: Get shipping cost
    public function get_shipping_cost()
    {
        $destination = $this->input->post('subdistrict_id') ?: $this->input->post('city_id');
        $destinationType = $this->input->post('subdistrict_id') ? 'subdistrict' : 'city';

        $weight = 1000; // Grams
        $courier = $this->input->post('courier');

        // Get origin from selected store, or fallback to default
        $origin_id = $this->input->post('origin_id');
        $origin = 532; // Default fallback
        $originType = 'city'; // Default fallback type

        if ($origin_id) {
            $store = $this->Store_model->get_by_id($origin_id);
            if ($store) {
                if (!empty($store->subdistrict_id)) {
                    $origin = $store->subdistrict_id;
                    $originType = 'subdistrict';
                } else if (!empty($store->city_id)) {
                    $origin = $store->city_id;
                    $originType = 'city';
                }
            }
        }

        $cost = $this->rajaongkir->get_cost($origin, $destination, $weight, $courier, $originType, $destinationType);

        // Always add debug info for troubleshooting
        $cost['debug_params'] = [
            'origin' => $origin,
            'originType' => $originType,
            'destination' => $destination,
            'destinationType' => $destinationType,
            'weight' => $weight,
            'courier' => $courier
        ];

        header('Content-Type: application/json');
        echo json_encode($cost);
    }

    public function store()
    {
        $this->form_validation->set_rules('customer_name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('customer_phone', 'No. WhatsApp', 'required|trim');
        $this->form_validation->set_rules('customer_address', 'Alamat', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('checkout');
            return;
        }

        // Handle payment method and status
        $payment_method = $this->input->post('payment_method') ?: 'transfer';
        $payment_status = 'pending';

        if ($payment_method === 'cod') {
            $payment_status = 'processing';
        }

        // Handle file upload (only for transfer)
        $payment_proof = NULL;
        if ($payment_method === 'transfer') {
            $raw_path = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'payments' . DIRECTORY_SEPARATOR;
            if (!is_dir($raw_path)) {
                mkdir($raw_path, 0775, TRUE);
            }
            @chmod($raw_path, 0775);

            $config_upload = [
                'upload_path' => $raw_path,
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size' => 2048,
                'encrypt_name' => TRUE
            ];

            if (!is_writable($config_upload['upload_path'])) {
                $user = function_exists('posix_getpwuid') && function_exists('posix_geteuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user();
                $this->session->set_flashdata('error', 'Folder upload pembayaran tidak dapat ditulisi: ' . $config_upload['upload_path'] . '<br>User PHP: <b>' . $user . '</b><br><b>SOLUSI aaPanel:</b> Buka File Manager, Klik Kanan folder tersebut -> Permissions -> Set 775 dan pastikan Owner adalah <b>www</b>.');
                redirect('checkout');
                return;
            }

            $this->upload->initialize($config_upload);

            if (!$this->upload->do_upload('payment_proof')) {
                $this->session->set_flashdata('error', 'Gagal upload bukti: ' . $this->upload->display_errors('', ''));
                redirect('checkout');
                return;
            }
            $upload_data = $this->upload->data();
            $payment_proof = $upload_data['file_name'];
        }

        $cart = $this->session->userdata('checkout_cart');
        if (!$cart || empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang kosong atau sesi berakhir.');
            redirect('cart');
            return;
        }

        $quantity = (int) $this->input->post('quantity');
        $shipping_cost = (float) $this->input->post('shipping_cost') ?: (float) $this->input->post('service');

        $courier = $this->input->post('courier');
        $service = $this->input->post('service_name') ?: $this->input->post('service');

        // Logic for Pickup and COD ELD
        if ($payment_method === 'pickup') {
            $courier = 'Ambil ke Toko';
            $service = 'Ambil di Toko';
            $shipping_cost = 0;
        } elseif ($payment_method === 'cod') {
            $courier = 'Kurir Lokal';
            $service = 'COD (Bayar di Tempat)';
            $shipping_cost = 0;
        }

        $subtotal = 0;
        $items = [];
        foreach ($cart as $item) {
            // Re-verify price from Variant DB to ensure it's up to date and correct for the selected size
            $db_variant = $this->Product_model->get_variant_by_id($item->variant_id);
            if (!$db_variant) {
                $this->session->set_flashdata('error', 'Varian produk ' . $item->product_name . ' tidak ditemukan.');
                redirect('cart');
                return;
            }

            // Check Stock Availability
            if ($db_variant->stock < $item->quantity) {
                $this->session->set_flashdata('error', 'Maaf, stok ' . $item->product_name . ' (' . $item->size . ') tidak mencukupi. Sisa stok: ' . $db_variant->stock);
                redirect('cart');
                return;
            }

            $real_price = $db_variant->price;

            $item_subtotal = $real_price * $item->quantity;
            $subtotal += $item_subtotal;
            $items[] = [
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'product_name' => $item->product_name,
                'size' => $item->size,
                'color' => $item->color,
                'price' => $real_price,
                'quantity' => $item->quantity,
                'subtotal' => $item_subtotal
            ];
        }

        // Midtrans Snap Token
        $discount = 0;
        $voucher_id = NULL;
        $voucher_code = NULL;

        // Apply voucher
        $voucher_input = $this->input->post('voucher_code');
        if (!empty($voucher_input)) {
            $voucher_result = $this->Voucher_model->apply($voucher_input, $subtotal);
            if ($voucher_result['valid']) {
                $discount = $voucher_result['discount'];
                $voucher_id = $voucher_result['voucher']->id;
                $voucher_code = $voucher_result['voucher']->code;
                $this->Voucher_model->increment_usage($voucher_id);
            }
        }

        $order_data = [
            'store_id' => $this->input->post('origin_id'),
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'customer_name' => $this->input->post('customer_name'),
            'customer_phone' => $this->input->post('customer_phone'),
            'customer_address' => $this->input->post('customer_address'),
            'province_id' => $this->input->post('province_id'),
            'city_id' => $this->input->post('city_id'),
            'district_id' => $this->input->post('district_id'),
            'subdistrict_id' => $this->input->post('subdistrict_id'),
            'province' => $this->input->post('province'),
            'city' => $this->input->post('city'),
            'district' => $this->input->post('district'),
            'subdistrict' => $this->input->post('subdistrict'),
            'courier' => $courier,
            'service' => $service,
            'shipping_cost' => $shipping_cost,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $subtotal - $discount + $shipping_cost,
            'voucher_code' => $voucher_code,
            'payment_proof' => $payment_proof,
            'status' => 'pending',
            'snap_token' => NULL
        ];

        // Pre-generate order_code for consistent ID
        $order_code = 'PWK-' . strtoupper(substr(md5(uniqid()), 0, 8));
        $order_data['order_code'] = $order_code;

        // Payment Implementation
        if ($payment_method === 'midtrans') {
            // Build Item Details
            $midtrans_items = [];
            foreach ($items as $item) {
                $midtrans_items[] = [
                    'id' => $item['variant_id'],
                    'price' => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'name' => substr($item['product_name'], 0, 50)
                ];
            }

            // Add Shipping as item
            if ($shipping_cost > 0) {
                $midtrans_items[] = [
                    'id' => 'SHIPPING',
                    'price' => (int) $shipping_cost,
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim (' . $courier . ' ' . $service . ')'
                ];
            }

            // Add Discount as negative item
            if ($discount > 0) {
                $midtrans_items[] = [
                    'id' => 'VOUCHER',
                    'price' => -(int) $discount,
                    'quantity' => 1,
                    'name' => 'Potongan Voucher (' . $voucher_code . ')'
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order_code,
                    'gross_amount' => (int) $order_data['total'],
                ],

                'item_details' => $midtrans_items,
                'customer_details' => [
                    'first_name' => $order_data['customer_name'],
                    'email' => 'customer@peweka.id', // Placeholder as not in DB yet
                    'phone' => $order_data['customer_phone'],
                    'billing_address' => [
                        'first_name' => $order_data['customer_name'],
                        'phone' => $order_data['customer_phone'],
                        'address' => $order_data['customer_address'],
                        'city' => $order_data['city'],
                        'country_code' => 'IDN'
                    ],
                    'shipping_address' => [
                        'first_name' => $order_data['customer_name'],
                        'phone' => $order_data['customer_phone'],
                        'address' => $order_data['customer_address'],
                        'city' => $order_data['city'],
                        'country_code' => 'IDN'
                    ]
                ],
                'callbacks' => [
                    'finish' => base_url('order/track/' . $order_code),
                    'error' => base_url('order/track/' . $order_code),
                    'unfinish' => base_url('order/track/' . $order_code)
                ]
            ];
            $snap = $this->midtrans->getSnapToken($params);
            if (isset($snap->token)) {
                $order_data['snap_token'] = $snap->token;
            }
        } elseif ($payment_method === 'komerce') {
            // Komerce Pay Logic
            $params = [
                'order_id' => 'PWK-' . time() . '-' . rand(100, 999),
                'payment_type' => 'qris',
                'amount' => (int) $order_data['total'],
                'customer' => [
                    'name' => $order_data['customer_name'],
                    'email' => $order_data['customer_phone'] . '@peweka.com',
                    'phone' => $order_data['customer_phone'],
                ],
                'items' => array_map(function ($item) {
                    return [
                        'name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'price' => (int) $item['price']
                    ];
                }, $items)
            ];

            $res = $this->komerce_pay->create_payment($params);
            if (isset($res->data->payment_url)) {
                $order_data['snap_token'] = $res->data->payment_url;
            } else if (isset($res->data->token)) {
                $pay_url = $this->config->item('rajaongkir_payment_sandbox', 'rajaongkir')
                    ? 'https://pay-sandbox.komerce.id/'
                    : 'https://pay.komerce.id/';
                $order_data['snap_token'] = $pay_url . $res->data->token;
            }
        }

        $created_code = $this->Order_model->create($order_data, $items);
        if (!$created_code) {
            $this->session->set_flashdata('error', 'Maaf, stok baru saja habis dibeli pelanggan lain. Silakan periksa kembali keranjang Anda.');
            redirect('cart');
            return;
        }

        $this->session->unset_userdata('checkout_cart');

        // Return order code for success page
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => true, 'order_code' => $order_code]);
        } else {
            redirect('order/success/' . $order_code);
        }
    }

    public function success($code)
    {
        $order = $this->Order_model->get_by_code($code);
        if (!$order) {
            show_404();
        }

        $data['order'] = $order;
        $data['snap_token'] = $order->snap_token;
        $data['midtrans_client_key'] = $this->config->item('midtrans_client_key');
        $data['title'] = 'Order Berhasil - ' . get_setting('site_name', 'Peweka');

        // Auto-redirect if already paid (especially for Midtrans flow)
        if ($order->payment_method === 'midtrans' && $order->status !== 'pending') {
            redirect('order/track/' . $order->order_code);
            return;
        }

        // Proactive Verification: If redirected back from Midtrans or just visiting, 
        // check real-time status if we still think it's pending.
        if ($order->payment_method === 'midtrans' && $order->status === 'pending') {
            $this->load->library('midtrans');
            $status_resp = $this->midtrans->getStatus($order->order_code);

            if (isset($status_resp->transaction_status)) {
                $status = $status_resp->transaction_status;
                if ($status === 'settlement' || $status === 'capture') {
                    $this->Order_model->update_status($order->id, 'confirmed', 'paid');
                    redirect('order/track/' . $order->order_code);
                    return;
                } else if ($status === 'expire' || $status === 'cancel' || $status === 'deny') {
                    $notes = ($status === 'expire') ? 'Waktu pembayaran telah habis (Expired).' : (($status === 'cancel') ? 'Pembayaran dibatalkan.' : 'Pembayaran ditolak.');
                    $this->Order_model->update_status($order->id, 'rejected', 'failed', $notes);
                    $this->Order_model->restore_stock_by_order($order->id);
                    redirect('order/track/' . $order->order_code);
                    return;
                }
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('order/success', $data);
        $this->load->view('layout/footer');
    }

    public function track($code)
    {
        $order = $this->Order_model->get_by_code($code);
        if (!$order) {
            show_404();
        }

        $data['order'] = $order;
        $data['title'] = 'Tracking Order - ' . get_setting('site_name', 'Peweka');

        // Provide payment details OR proactive check if still pending
        if ($order->payment_method === 'midtrans' && $order->status === 'pending') {
            $this->load->library('midtrans');
            $status_resp = $this->midtrans->getStatus($order->order_code);

            if (isset($status_resp->transaction_status)) {
                $status = $status_resp->transaction_status;
                if ($status === 'settlement' || $status === 'capture') {
                    $this->Order_model->update_status($order->id, 'confirmed', 'paid');
                    // Refresh current order object for the view
                    $order = $this->Order_model->get_by_code($code);
                    $data['order'] = $order;
                } else if ($status === 'expire' || $status === 'cancel' || $status === 'deny') {
                    $notes = ($status === 'expire') ? 'Waktu pembayaran telah habis (Expired).' : (($status === 'cancel') ? 'Pembayaran dibatalkan.' : 'Pembayaran ditolak.');
                    $this->Order_model->update_status($order->id, 'rejected', 'failed', $notes);
                    // Refresh current order object for the view
                    $order = $this->Order_model->get_by_code($code);
                    $data['order'] = $order;
                } else {
                    $data['snap_token'] = $order->snap_token;
                    $data['midtrans_client_key'] = $this->config->item('midtrans_client_key');
                }
            } else {
                $data['snap_token'] = $order->snap_token;
                $data['midtrans_client_key'] = $this->config->item('midtrans_client_key');
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('order/track', $data);
        $this->load->view('layout/footer');
    }

    public function apply_voucher()
    {
        $code = $this->input->post('code');
        $subtotal = (float) $this->input->post('subtotal');

        $result = $this->Voucher_model->apply($code, $subtotal);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function notification()
    {
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if (!$result) {
            return;
        }

        $order_code = $result->order_id;
        $status_code = $result->status_code;
        $gross_amount = $result->gross_amount;
        $server_key = $this->config->item('midtrans_server_key');

        $signature = hash("sha512", $order_code . $status_code . $gross_amount . $server_key);

        if ($signature !== $result->signature_key) {
            log_message('error', 'Midtrans Notification: Invalid Signature for Order ' . $order_code);
            return;
        }

        $transaction_status = $result->transaction_status;
        $fraud_status = isset($result->fraud_status) ? $result->fraud_status : null;

        $order = $this->Order_model->get_by_code($order_code);
        if (!$order) {
            log_message('error', 'Midtrans Notification: Order not found ' . $order_code);
            return;
        }

        $new_status = null;
        if ($transaction_status == 'capture') {
            if ($fraud_status == 'challenge') {
                $new_status = 'pending';
            } else if ($fraud_status == 'accept') {
                $new_status = 'confirmed';
            }
        } else if ($transaction_status == 'settlement') {
            $new_status = 'confirmed';
        } else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
            $new_status = 'rejected';
            $failure_notes = [
                'cancel' => 'Pembayaran dibatalkan.',
                'deny' => 'Pembayaran ditolak oleh sistem payment gateway.',
                'expire' => 'Waktu pembayaran telah habis (Expired).'
            ];
            $notes = isset($failure_notes[$transaction_status]) ? $failure_notes[$transaction_status] : 'Pembayaran gagal: ' . $transaction_status;
        } else if ($transaction_status == 'pending') {
            $new_status = 'pending';
        }

        if ($new_status) {
            $payment_status = ($new_status === 'confirmed') ? 'paid' : ($new_status === 'rejected' ? 'failed' : 'pending');
            $this->Order_model->update_status($order->id, $new_status, $payment_status, isset($notes) ? $notes : 'Midtrans Status: ' . $transaction_status);

            // Restore stock if it was previously deducted and now rejected
            if ($new_status === 'rejected') {
                $this->Order_model->restore_stock_by_order($order->id);
            }

            log_message('info', 'Midtrans Notification: Order ' . $order_code . ' status updated to ' . $new_status);
        }

        echo "OK";
    }

    public function track_history($code)
    {
        $order = $this->Order_model->get_by_code($code);
        if (!$order) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan']);
            return;
        }

        if (!$order->tracking_number || !$order->courier) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Informasi pengiriman belum tersedia']);
            return;
        }

        // Ambil 5 digit terakhir nomor WA untuk validasi JNE (Komerce)
        $last_phone = null;
        if (!empty($order->customer_phone)) {
            $clean_phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
            $last_phone = substr($clean_phone, -5);
        }

        $waybill = $this->rajaongkir->get_waybill($order->tracking_number, $order->courier, $last_phone);

        log_message('error', 'Tracking Request - AWB: ' . $order->tracking_number . ' Courier: ' . $order->courier . ' Phone: ' . $last_phone);
        log_message('error', 'RajaOngkir Response: ' . json_encode($waybill));

        header('Content-Type: application/json');
        if (isset($waybill['data']) && !empty($waybill['data'])) {
            echo json_encode(['success' => true, 'data' => $waybill['data']]);
        } else {
            $msg = 'Gagal mengambil data pelacakan';
            if (isset($waybill['meta']['message'])) {
                $msg = $waybill['meta']['message'];
            } else if (isset($waybill['raw_response']) && !empty($waybill['raw_response'])) {
                $msg = 'API Error: ' . strip_tags(substr($waybill['raw_response'], 0, 100));
            }
            echo json_encode(['success' => false, 'message' => $msg]);
        }
    }
}
