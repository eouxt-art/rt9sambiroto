<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Artikel_model');
        $this->load->model('Agenda_model');
        $this->load->model('Warga_model');
        $this->load->model('Gallery_model');
    }

    /**
     * Homepage
     */
    public function index()
    {
        $data['title'] = 'RT 9 Desa Sambiroto';
        $data['latest_artikel'] = $this->Artikel_model->get_aktif(6);
        $data['upcoming_agenda'] = $this->Agenda_model->get_upcoming(5);
        $data['trending_gallery'] = $this->Gallery_model->get_trending(6);
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/home', $data);
        $this->load->view('frontend/footer');
    }

    /**
     * List artikel
     */
    public function artikel()
    {
        $data['title'] = 'Artikel - RT 9 Sambiroto';
        $data['artikel'] = $this->Artikel_model->get_aktif(12);
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/artikel', $data);
        $this->load->view('frontend/footer');
    }

    /**
     * Detail artikel
     */
    public function artikel_detail($slug)
    {
        $data['artikel'] = $this->Artikel_model->get_by_slug($slug);
        
        if (!$data['artikel']) {
            show_404();
        }

        $data['title'] = $data['artikel']->judul . ' - RT 9 Sambiroto';
        $this->Artikel_model->increment_views($data['artikel']->id);
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/artikel_detail', $data);
        $this->load->view('frontend/footer');
    }

    /**
     * List agenda
     */
    public function agenda()
    {
        $data['title'] = 'Agenda - RT 9 Sambiroto';
        $data['agenda'] = $this->Agenda_model->get_upcoming();
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/agenda', $data);
        $this->load->view('frontend/footer');
    }

    /**
     * List galeri
     */
    public function galeri()
    {
        $data['title'] = 'Galeri - RT 9 Sambiroto';
        $data['gallery'] = $this->Gallery_model->get_aktif(12);
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/galeri', $data);
        $this->load->view('frontend/footer');
    }

    /**
     * Laporan keuangan
     */
    public function laporan()
    {
        $data['title'] = 'Laporan Keuangan - RT 9 Sambiroto';
        $this->load->model('Laporan_model');
        $data['laporan'] = $this->Laporan_model->get_aktif();
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/laporan', $data);
        $this->load->view('frontend/footer');
    }

    /**
     * Data warga
     */
    public function warga()
    {
        $data['title'] = 'Data Warga - RT 9 Sambiroto';
        $data['warga'] = $this->Warga_model->get_all();
        $data['total_warga'] = $this->Warga_model->count_all();
        
        $this->load->view('frontend/header', $data);
        $this->load->view('frontend/warga', $data);
        $this->load->view('frontend/footer');
    }
}
