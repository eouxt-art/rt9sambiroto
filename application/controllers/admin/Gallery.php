<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gallery_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('upload');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * List gallery
     */
    public function index($page = 0)
    {
        $data['title'] = 'Galeri Foto';
        $data['user'] = $this->session->userdata();
        
        $limit = 10;
        $offset = ($page > 0) ? ($page - 1) * $limit : 0;
        
        $total = $this->Gallery_model->count_all();
        $data['gallery'] = $this->Gallery_model->get_all($limit, $offset);
        
        // Pagination
        $config['base_url'] = site_url('admin/gallery/index');
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
        $this->load->view('admin/gallery/index', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Form tambah foto
     */
    public function add()
    {
        $data['title'] = 'Tambah Foto';
        $data['user'] = $this->session->userdata();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/gallery/add', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses tambah foto
     */
    public function proses_add()
    {
        $this->form_validation->set_rules('judul', 'Judul', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/gallery/add');
        }

        if (empty($_FILES['foto']['name'])) {
            $this->session->set_flashdata('error', 'Silakan pilih foto!');
            redirect('admin/gallery/add');
        }

        $config['upload_path'] = './uploads/gallery/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '2048';
        $config['encrypt_name'] = TRUE;
        
        $this->upload->initialize($config);
        
        if (!$this->upload->do_upload('foto')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/gallery/add');
        }

        $data = array(
            'judul' => $this->input->post('judul'),
            'kategori' => $this->input->post('kategori'),
            'deskripsi' => $this->input->post('deskripsi'),
            'foto' => $this->upload->data('file_name'),
            'uploader' => $this->session->userdata('user_id'),
            'status' => 'aktif',
            'views' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->Gallery_model->create($data)) {
            $this->session->set_flashdata('success', 'Foto berhasil ditambahkan!');
            redirect('admin/gallery');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan foto!');
            redirect('admin/gallery/add');
        }
    }

    /**
     * Hapus foto
     */
    public function delete($id)
    {
        $gallery = $this->Gallery_model->get_by_id($id);
        if (!$gallery) {
            show_404();
        }

        if ($gallery->foto && file_exists('./uploads/gallery/' . $gallery->foto)) {
            unlink('./uploads/gallery/' . $gallery->foto);
        }

        if ($this->Gallery_model->delete($id)) {
            $this->session->set_flashdata('success', 'Foto berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus foto!');
        }
        
        redirect('admin/gallery');
    }
}
