<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model
{

    public function get_all()
    {
        $this->db->order_by('name', 'ASC');
        return $this->db->get('categories')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('categories', ['id' => $id])->row();
    }

    public function get_by_slug($slug)
    {
        return $this->db->get_where('categories', ['slug' => $slug])->row();
    }

    public function create($data)
    {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
}
