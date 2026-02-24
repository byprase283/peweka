<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model
{

    public function get_all($limit = NULL, $offset = 0)
    {
        $this->db->order_by('name', 'ASC');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get('categories')->result();
    }

    public function count_all()
    {
        return $this->db->count_all_results('categories');
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
