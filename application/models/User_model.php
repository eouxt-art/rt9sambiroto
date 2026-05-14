<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all users
     */
    public function get_all($limit = 10, $offset = 0)
    {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get('users')->result();
    }

    /**
     * Get user by ID
     */
    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get('users')->row();
    }

    /**
     * Get user by username
     */
    public function get_by_username($username)
    {
        return $this->db->where('username', $username)->get('users')->row();
    }

    /**
     * Authenticate user
     */
    public function authenticate($username, $password)
    {
        $user = $this->get_by_username($username);
        
        if ($user && password_verify($password, $user->password)) {
            if ($user->is_active) {
                return $user;
            }
        }
        
        return false;
    }

    /**
     * Count total users
     */
    public function count_all()
    {
        return $this->db->count_all('users');
    }

    /**
     * Create new user
     */
    public function create($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        return $this->db->insert('users', $data);
    }

    /**
     * Update user
     */
    public function update($id, $data)
    {
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    /**
     * Check if username exists
     */
    public function username_exists($username, $exclude_id = null)
    {
        $this->db->where('username', $username);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        return $this->db->count_all_results('users') > 0;
    }

    /**
     * Check if email exists
     */
    public function email_exists($email, $exclude_id = null)
    {
        $this->db->where('email', $email);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        return $this->db->count_all_results('users') > 0;
    }

}
