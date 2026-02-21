<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    public function login($username, $password)
    {
        $admin = $this->db->get_where('admin', ['username' => $username])->row();
        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        }
        return FALSE;
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('admin', ['id' => $id])->row();
    }

    public function update_password($admin_id, $password_hash)
    {
        $this->db->where('id', $admin_id);
        return $this->db->update('admin', ['password' => $password_hash]);
    }
}
