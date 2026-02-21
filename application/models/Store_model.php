<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends CI_Model
{
    public function get_all()
    {
        return $this->db->get('stores')->result();
    }

    public function get_default()
    {
        return $this->db->get_where('stores', ['is_default' => 1])->row();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('stores', ['id' => $id])->row();
    }

    public function update($id, $data)
    {
        return $this->db->update('stores', $data, ['id' => $id]);
    }
}
