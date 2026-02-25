<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{

    public function create($order_data, $items)
    {
        // Generate unique order code if not provided
        if (!isset($order_data['order_code'])) {
            $order_data['order_code'] = 'PWK-' . strtoupper(substr(md5(uniqid()), 0, 8));
        }
        $order_data['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();

        foreach ($items as $item) {
            $item['order_id'] = $order_id;
            $this->db->insert('order_items', $item);

            // Reduce stock
            $this->db->set('stock', 'stock - ' . (int) $item['quantity'], FALSE);
            $this->db->where('id', $item['variant_id']);
            $this->db->update('product_variants');
        }

        return $order_data['order_code'];
    }

    public function get_by_code($code)
    {
        $order = $this->db->get_where('orders', ['order_code' => $code])->row();
        if ($order) {
            $order->items = $this->db->get_where('order_items', ['order_id' => $order->id])->result();
        }
        return $order;
    }

    public function get_all($status = NULL, $limit = NULL, $offset = 0)
    {
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('created_at', 'DESC');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get('orders')->result();
    }

    public function get_by_id($id)
    {
        $order = $this->db->get_where('orders', ['id' => $id])->row();
        if ($order) {
            $order->items = $this->db->get_where('order_items', ['order_id' => $order->id])->result();
        }
        return $order;
    }

    public function update_status($id, $status, $payment_status = NULL, $notes = NULL)
    {
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($payment_status !== NULL) {
            $data['payment_status'] = $payment_status;
        }

        if ($notes !== NULL) {
            $data['notes'] = $notes;
        }
        $this->db->where('id', $id);
        return $this->db->update('orders', $data);
    }

    public function count_by_status($status = NULL)
    {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results('orders');
    }

    public function get_total_revenue()
    {
        $this->db->select_sum('total');
        $this->db->where_in('status', ['confirmed', 'shipped', 'delivered']);
        $result = $this->db->get('orders')->row();
        return $result->total ?: 0;
    }

    public function get_daily_revenue_stats($days = 7)
    {
        $this->db->select("DATE(created_at) as date, SUM(total) as revenue");
        $this->db->where('created_at >=', date('Y-m-d', strtotime("-{$days} days")));
        $this->db->where_in('status', ['confirmed', 'shipped', 'delivered']);
        $this->db->group_by('DATE(created_at)');
        $this->db->order_by('DATE(created_at)', 'ASC');
        return $this->db->get('orders')->result();
    }

    public function get_monthly_revenue_stats($year = NULL)
    {
        if (!$year)
            $year = date('Y');
        $this->db->select("MONTH(created_at) as month, SUM(total) as revenue");
        $this->db->where('YEAR(created_at)', $year);
        $this->db->where_in('status', ['confirmed', 'shipped', 'delivered']);
        $this->db->group_by('MONTH(created_at)');
        $this->db->order_by('MONTH(created_at)', 'ASC');
        return $this->db->get('orders')->result();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('orders', $data);
    }
}
