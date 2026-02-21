<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends CI_Model
{

    public function get_all()
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('vouchers')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('vouchers', ['id' => $id])->row();
    }

    public function validate($code)
    {
        $voucher = $this->db->get_where('vouchers', ['code' => strtoupper($code)])->row();
        if (!$voucher) {
            return ['valid' => FALSE, 'message' => 'Kode voucher tidak ditemukan.'];
        }
        if (!$voucher->is_active) {
            return ['valid' => FALSE, 'message' => 'Voucher tidak aktif.'];
        }
        if ($voucher->expired_at && date('Y-m-d') > $voucher->expired_at) {
            return ['valid' => FALSE, 'message' => 'Voucher sudah expired.'];
        }
        if ($voucher->quota > 0 && $voucher->used >= $voucher->quota) {
            return ['valid' => FALSE, 'message' => 'Kuota voucher sudah habis.'];
        }
        return ['valid' => TRUE, 'voucher' => $voucher];
    }

    public function apply($code, $subtotal)
    {
        $result = $this->validate($code);
        if (!$result['valid']) {
            return $result;
        }

        $voucher = $result['voucher'];

        if ($subtotal < $voucher->min_order) {
            return [
                'valid' => FALSE,
                'message' => 'Minimum order Rp ' . number_format($voucher->min_order, 0, ',', '.') . ' untuk menggunakan voucher ini.'
            ];
        }

        $discount = 0;
        if ($voucher->type === 'percentage') {
            $discount = $subtotal * ($voucher->value / 100);
            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
        } else {
            $discount = $voucher->value;
        }

        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return [
            'valid' => TRUE,
            'voucher' => $voucher,
            'discount' => $discount,
            'total' => $subtotal - $discount
        ];
    }

    public function increment_usage($id)
    {
        $this->db->set('used', 'used + 1', FALSE);
        $this->db->where('id', $id);
        return $this->db->update('vouchers');
    }

    public function create($data)
    {
        $data['code'] = strtoupper($data['code']);
        $this->db->insert('vouchers', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        if (isset($data['code'])) {
            $data['code'] = strtoupper($data['code']);
        }
        $this->db->where('id', $id);
        return $this->db->update('vouchers', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('vouchers');
    }
}
