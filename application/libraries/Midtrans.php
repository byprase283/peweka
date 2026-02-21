<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans
{
    protected $CI;
    protected $server_key;
    protected $is_production;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('midtrans');

        $this->server_key = $this->CI->config->item('midtrans_server_key');
        $this->is_production = $this->CI->config->item('midtrans_is_production');
    }

    public function getSnapToken($params)
    {
        $url = $this->is_production
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $auth = base64_encode($this->server_key . ':');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . $auth
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }
}
