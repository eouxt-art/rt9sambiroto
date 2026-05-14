<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all active gallery
     */
    public function get_aktif($limit = 12, $offset = 0)
    {
        $this->db->select('g.*, u.nama_lengkap as uploader_nama');
        $this->db->from('gallery g');
        $this->db->join('users u', 'g.uploader = u.id', 'left');
        $this->db->where('g.status', 'aktif');
        $this->db->order_by('g.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    /**
     * Get gallery by ID
     */
    public function get_by_id($id)
    {
        $this->db->select('g.*, u.nama_lengkap as uploader_nama');
        $this->db->from('gallery g');
        $this->db->join('users u', 'g.uploader = u.id', 'left');
        $this->db->where('g.id', $id);
        $this->db->where('g.status', 'aktif');
        
        return $this->db->get()->row();
    }

    /**
     * Get all gallery (admin)
     */
    public function get_all($limit = 12, $offset = 0)
    {
        $this->db->select('g.*, u.nama_lengkap as uploader_nama');
        $this->db->from('gallery g');
        $this->db->join('users u', 'g.uploader = u.id', 'left');
        $this->db->order_by('g.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    /**
     * Get gallery by category
     */
    public function get_by_category($kategori, $limit = 12, $offset = 0)
    {
        $this->db->select('g.*, u.nama_lengkap as uploader_nama');
        $this->db->from('gallery g');
        $this->db->join('users u', 'g.uploader = u.id', 'left');
        $this->db->where('g.kategori', $kategori);
        $this->db->where('g.status', 'aktif');
        $this->db->order_by('g.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    /**
     * Count active gallery
     */
    public function count_aktif()
    {
        return $this->db->where('status', 'aktif')->count_all_results('gallery');
    }

    /**
     * Count all gallery
     */
    public function count_all()
    {
        return $this->db->count_all('gallery');
    }

    /**
     * Count by category
     */
    public function count_by_category($kategori)
    {
        return $this->db->where('kategori', $kategori)
                       ->where('status', 'aktif')
                       ->count_all_results('gallery');
    }

    /**
     * Create gallery
     */
    public function create($data)
    {
        return $this->db->insert('gallery', $data);
    }

    /**
     * Update gallery
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('gallery', $data);
    }

    /**
     * Delete gallery
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('gallery');
    }

    /**
     * Increment views
     */
    public function increment_views($id)
    {
        $this->db->set('views', 'views+1', FALSE);
        $this->db->where('id', $id);
        return $this->db->update('gallery');
    }

    /**
     * Get trending gallery
     */
    public function get_trending($limit = 6)
    {
        $this->db->select('g.*, u.nama_lengkap as uploader_nama');
        $this->db->from('gallery g');
        $this->db->join('users u', 'g.uploader = u.id', 'left');
        $this->db->where('g.status', 'aktif');
        $this->db->order_by('g.views', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Get latest gallery
     */
    public function get_latest($limit = 6)
    {
        $this->db->select('g.*, u.nama_lengkap as uploader_nama');
        $this->db->from('gallery g');
        $this->db->join('users u', 'g.uploader = u.id', 'left');
        $this->db->where('g.status', 'aktif');
        $this->db->order_by('g.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

}
