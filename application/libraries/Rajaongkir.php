<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rajaongkir
{
    protected $ci;
    protected $api_key;
    protected $use_mock;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->config->load('rajaongkir', TRUE);

        $this->api_key = $this->ci->config->item('rajaongkir_api_key', 'rajaongkir');
        $this->use_mock = $this->ci->config->item('rajaongkir_use_mock', 'rajaongkir');
    }

    public function get_provinces()
    {
        if ($this->use_mock) {
            return [
                "meta" => ["message" => "Mock Data", "code" => 200, "status" => "success"],
                "data" => $this->ci->config->item('rajaongkir_mock_provinces', 'rajaongkir')
            ];
        }
        return $this->_request('destination/province');
    }

    public function get_cities($province_id = null)
    {
        if ($this->use_mock) {
            $mock_cities = $this->ci->config->item('rajaongkir_mock_cities', 'rajaongkir');
            $data = isset($mock_cities[$province_id]) ? $mock_cities[$province_id] : [];
            return [
                "meta" => ["message" => "Mock Data", "code" => 200, "status" => "success"],
                "data" => $data
            ];
        }
        $endpoint = 'destination/city';
        if ($province_id) {
            $endpoint .= '/' . $province_id;
        }
        return $this->_request($endpoint);
    }

    public function get_districts($city_id)
    {
        if ($this->use_mock) {
            $mock_districts = $this->ci->config->item('rajaongkir_mock_districts', 'rajaongkir');
            $data = isset($mock_districts[$city_id]) ? $mock_districts[$city_id] : [];
            return [
                "meta" => ["message" => "Mock Data", "code" => 200, "status" => "success"],
                "data" => $data
            ];
        }
        return $this->_request('destination/district/' . $city_id);
    }

    public function get_subdistricts($district_id)
    {
        if ($this->use_mock) {
            return [
                "meta" => ["message" => "Mock Data", "code" => 200, "status" => "success"],
                "data" => $this->ci->config->item('rajaongkir_mock_subdistricts', 'rajaongkir')
            ];
        }
        return $this->_request('destination/sub-district/' . $district_id);
    }

    public function get_cost($origin, $destination, $weight, $courier, $originType = 'subdistrict', $destinationType = 'subdistrict')
    {
        if ($this->use_mock) {
            $base_cost = 12000;
            if ($courier == 'jne')
                $base_cost = 15000;
            if ($courier == 'pos')
                $base_cost = 10000;

            return [
                "meta" => ["message" => "Mock Data Success", "code" => 200, "status" => "success"],
                "data" => [
                    [
                        "code" => $courier,
                        "name" => strtoupper($courier),
                        "costs" => [
                            [
                                "service" => "REG",
                                "description" => "Layanan Reguler (Mock)",
                                "cost" => [["value" => $base_cost, "etd" => "2-3", "note" => ""]]
                            ],
                            [
                                "service" => "OKE",
                                "description" => "Ongkos Kirim Ekonomis (Mock)",
                                "cost" => [["value" => $base_cost - 3000, "etd" => "4-5", "note" => ""]]
                            ]
                        ]
                    ]
                ]
            ];
        }
        $params = [
            'origin' => $origin,
            'originType' => $originType,
            'destination' => $destination,
            'destinationType' => $destinationType,
            'weight' => $weight,
            'courier' => $courier
        ];
        return $this->_request('calculate/domestic-cost', 'POST', $params);
    }

    public function get_waybill($resi, $courier, $last_phone_number = null)
    {
        if ($this->use_mock) {
            return [
                "meta" => ["message" => "Mock Waybill Data", "code" => 200, "status" => "success"],
                "data" => [
                    "summary" => [
                        "waybill_number" => $resi,
                        "courier_name" => strtoupper($courier),
                        "status" => "DELIVERED"
                    ],
                    "manifest" => [
                        ["manifest_description" => "Paket telah diterima oleh [Sodara Rian - Purwakarta]", "manifest_date" => date('Y-m-d'), "manifest_time" => "14:20"],
                        ["manifest_description" => "Paket sedang dibawa kurir menuju alamat tujuan", "manifest_date" => date('Y-m-d'), "manifest_time" => "09:15"],
                        ["manifest_description" => "Paket telah sampai di Hub Purwakarta", "manifest_date" => date('Y-m-d', strtotime('-1 day')), "manifest_time" => "22:10"],
                        ["manifest_description" => "Paket dalam perjalanan dari Bekasi", "manifest_date" => date('Y-m-d', strtotime('-1 day')), "manifest_time" => "18:30"],
                        ["manifest_description" => "Paket telah di pick up di Bekasi", "manifest_date" => date('Y-m-d', strtotime('-1 day')), "manifest_time" => "13:00"],
                    ]
                ]
            ];
        }

        // Mapping common courier aliases to RajaOngkir codes
        $courier_map = [
            'j&t' => 'jnt',
            'jnt' => 'jnt',
            'jt' => 'jnt',
            'jne' => 'jne',
            'pos' => 'pos',
            'tiki' => 'tiki',
            'sicepat' => 'sicepat',
            'wa' => 'wahana',
            'wahana' => 'wahana',
            'ninja' => 'ninja',
            'lion' => 'lion',
            'idexpress' => 'ide',
            'ide' => 'ide',
            'anteraja' => 'anteraja',
            'sap' => 'sap',
            'first' => 'first',
            'spx' => 'spx',
            'shopee' => 'spx',
            'shopeexpress' => 'spx'
        ];

        $courier_code = strtolower($courier);

        $resi_upper = strtoupper($resi);
        if (strpos($resi_upper, 'SPXID') === 0) {
            return [
                "meta" => ["message" => "Shopee Express (SPX) belum didukung oleh API Komerce Anda.", "code" => 400, "status" => "error"],
                "data" => null
            ];
        }

        if (preg_match('/^[0-9]{12}$/', $resi) || strpos($resi_upper, 'JP') === 0 || strpos($resi_upper, 'JD') === 0 || strpos($resi_upper, 'JX') === 0) {
            $courier_code = 'jnt';
        }

        if (isset($courier_map[$courier_code])) {
            $courier_code = $courier_map[$courier_code];
        }

        $params = [
            'awb' => $resi,
            'courier' => $courier_code
        ];

        if ($last_phone_number) {
            $params['last_phone_number'] = $last_phone_number;
        }
        return $this->_request('track/waybill', 'POST', $params);
    }

    protected function _request($endpoint, $method = 'GET', $params = [])
    {
        $url = "https://rajaongkir.komerce.id/api/v1/" . $endpoint;
        $curl = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                "key: " . $this->api_key,
                "Content-Type: application/x-www-form-urlencoded"
            ],
        ];

        if ($method === 'POST') {
            $options[CURLOPT_POSTFIELDS] = http_build_query($params);
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return [
                "meta" => ["message" => "CURL Error: " . $err, "code" => 500, "status" => "error"],
                "data" => null
            ];
        } else {
            $decoded = json_decode($response, true);
            if ($decoded === null) {
                return [
                    "meta" => ["message" => "Invalid JSON response from API", "code" => 500, "status" => "error"],
                    "raw_response" => $response,
                    "data" => null
                ];
            }
            return $decoded;
        }
    }
}
