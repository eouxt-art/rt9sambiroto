<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warga extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Warga_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * List warga
     */
    public function index($page = 0)
    {
        $data['title'] = 'Data Warga';
        $data['user'] = $this->session->userdata();
        
        $limit = 10;
        $offset = ($page > 0) ? ($page - 1) * $limit : 0;
        
        $total = $this->Warga_model->count_all();
        $data['warga'] = $this->Warga_model->get_all($limit, $offset);
        
        // Pagination
        $config['base_url'] = site_url('admin/warga/index');
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
        $this->load->view('admin/warga/index', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Form tambah warga
     */
    public function add()
    {
        $data['title'] = 'Tambah Data Warga';
        $data['user'] = $this->session->userdata();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/warga/add', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses tambah warga
     */
    public function proses_add()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'required|numeric');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('no_kk', 'No. KK', 'required|numeric');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/warga/add');
        }

        $data = array(
            'nik' => $this->input->post('nik'),
            'nama' => $this->input->post('nama'),
            'no_kk' => $this->input->post('no_kk'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'status' => $this->input->post('status'),
            'pekerjaan' => $this->input->post('pekerjaan'),
            'pendidikan' => $this->input->post('pendidikan'),
            'alamat' => $this->input->post('alamat'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->Warga_model->create($data)) {
            $this->session->set_flashdata('success', 'Data warga berhasil ditambahkan!');
            redirect('admin/warga');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data warga!');
            redirect('admin/warga/add');
        }
    }

    /**
     * Form edit warga
     */
    public function edit($id)
    {
        $data['title'] = 'Edit Data Warga';
        $data['user'] = $this->session->userdata();
        $data['warga'] = $this->Warga_model->get_by_id($id);
        
        if (!$data['warga']) {
            show_404();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/warga/edit', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses edit warga
     */
    public function proses_edit($id)
    {
        $warga = $this->Warga_model->get_by_id($id);
        if (!$warga) {
            show_404();
        }

        $this->form_validation->set_rules('nik', 'NIK', 'required|numeric');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('no_kk', 'No. KK', 'required|numeric');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/warga/edit/' . $id);
        }

        $data = array(
            'nik' => $this->input->post('nik'),
            'nama' => $this->input->post('nama'),
            'no_kk' => $this->input->post('no_kk'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'status' => $this->input->post('status'),
            'pekerjaan' => $this->input->post('pekerjaan'),
            'pendidikan' => $this->input->post('pendidikan'),
            'alamat' => $this->input->post('alamat'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Warga_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Data warga berhasil diupdate!');
            redirect('admin/warga');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate data warga!');
            redirect('admin/warga/edit/' . $id);
        }
    }

    /**
     * Hapus warga
     */
    public function delete($id)
    {
        if ($this->Warga_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data warga berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data warga!');
        }
        
        redirect('admin/warga');
    }
}
