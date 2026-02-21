<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_setting')) {
    function get_setting($key, $default = '')
    {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        $settings = $CI->Settings_model->get_settings();

        if ($settings && isset($settings->$key)) {
            return $settings->$key;
        }

        return $default;
    }
}
