<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Komerce_pay
{
    protected $CI;
    protected $api_key;
    protected $is_sandbox;
    protected $base_url;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('rajaongkir', TRUE);

        $this->api_key = $this->CI->config->item('rajaongkir_payment_api_key', 'rajaongkir');
        $this->is_sandbox = $this->CI->config->item('rajaongkir_payment_sandbox', 'rajaongkir');

        $this->base_url = $this->is_sandbox
            ? 'https://api-sandbox.collaborator.komerce.id/user'
            : 'https://api.collaborator.komerce.id/user';
    }

    /**
     * Create a payment transaction
     * Currently defaulting to QRIS for easiest "Online Pay" replacement
     */
    public function create_payment($params)
    {
        $url = $this->base_url . '/api/v1/user/payment/create';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-api-key: ' . $this->api_key
        ]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return (object) [
                'meta' => (object) [
                    'status' => 'error',
                    'message' => 'CURL Error: ' . $err
                ]
            ];
        }

        return json_decode($result);
    }

    public function check_status($payment_id)
    {
        $url = $this->base_url . '/api/v1/user/payment/status/' . $payment_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . $this->api_key
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }
}
