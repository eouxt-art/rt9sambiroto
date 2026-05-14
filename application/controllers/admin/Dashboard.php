<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Artikel_model');
        $this->load->model('Agenda_model');
        $this->load->model('Laporan_model');
        $this->load->model('Warga_model');
        $this->load->model('Gallery_model');
        
        // Check login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Dashboard utama
     */
    public function index()
    {
        $data['title'] = 'Dashboard Admin';
        $data['user'] = $this->session->userdata();
        
        // Statistik
        $data['total_user'] = $this->User_model->count_all();
        $data['total_artikel'] = $this->Artikel_model->count_all();
        $data['total_agenda'] = $this->Agenda_model->count_all();
        $data['total_warga'] = $this->Warga_model->count_all();
        $data['total_gallery'] = $this->Gallery_model->count_all();
        
        // Latest data
        $data['latest_artikel'] = $this->Artikel_model->get_latest(5);
        $data['upcoming_agenda'] = $this->Agenda_model->get_upcoming(5);
        $data['trending_gallery'] = $this->Gallery_model->get_trending(6);
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }
}
