<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Laporan_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * List laporan keuangan
     */
    public function index($page = 0)
    {
        $data['title'] = 'Laporan Keuangan';
        $data['user'] = $this->session->userdata();
        
        $limit = 10;
        $offset = ($page > 0) ? ($page - 1) * $limit : 0;
        
        $total = $this->Laporan_model->count_all();
        $data['laporan'] = $this->Laporan_model->get_all($limit, $offset);
        
        // Pagination
        $config['base_url'] = site_url('admin/laporan/index');
        $config['total_rows'] = $total;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        
        if ($this->session->flashdata('success')) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error')) {
            $data['error'] = $this->session->flashdata('error');
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/laporan/index', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Lihat detail laporan
     */
    public function detail($id)
    {
        $data['title'] = 'Detail Laporan Keuangan';
        $data['user'] = $this->session->userdata();
        $data['laporan'] = $this->Laporan_model->get_by_id($id);
        $data['detail'] = $this->Laporan_model->get_detail($id);
        
        if (!$data['laporan']) {
            show_404();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/laporan/detail', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Form tambah laporan
     */
    public function add()
    {
        $data['title'] = 'Tambah Laporan Keuangan';
        $data['user'] = $this->session->userdata();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/laporan/add', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses tambah laporan
     */
    public function proses_add()
    {
        $this->form_validation->set_rules('bulan', 'Bulan', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/laporan/add');
        }

        $data = array(
            'bulan' => $this->input->post('bulan'),
            'tahun' => $this->input->post('tahun'),
            'keterangan' => $this->input->post('keterangan'),
            'status' => 'aktif',
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->Laporan_model->create($data)) {
            $this->session->set_flashdata('success', 'Laporan berhasil ditambahkan!');
            redirect('admin/laporan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan laporan!');
            redirect('admin/laporan/add');
        }
    }

    /**
     * Form edit laporan
     */
    public function edit($id)
    {
        $data['title'] = 'Edit Laporan Keuangan';
        $data['user'] = $this->session->userdata();
        $data['laporan'] = $this->Laporan_model->get_by_id($id);
        
        if (!$data['laporan']) {
            show_404();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/laporan/edit', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses edit laporan
     */
    public function proses_edit($id)
    {
        $laporan = $this->Laporan_model->get_by_id($id);
        if (!$laporan) {
            show_404();
        }

        $this->form_validation->set_rules('bulan', 'Bulan', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/laporan/edit/' . $id);
        }

        $data = array(
            'bulan' => $this->input->post('bulan'),
            'tahun' => $this->input->post('tahun'),
            'keterangan' => $this->input->post('keterangan'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Laporan_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Laporan berhasil diupdate!');
            redirect('admin/laporan');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate laporan!');
            redirect('admin/laporan/edit/' . $id);
        }
    }

    /**
     * Hapus laporan
     */
    public function delete($id)
    {
        if ($this->Laporan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Laporan berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus laporan!');
        }
        
        redirect('admin/laporan');
    }
}
