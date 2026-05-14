<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Artikel_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('upload');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * List artikel
     */
    public function index($page = 0)
    {
        $data['title'] = 'Data Artikel';
        $data['user'] = $this->session->userdata();
        
        $limit = 10;
        $offset = ($page > 0) ? ($page - 1) * $limit : 0;
        
        $total = $this->Artikel_model->count_all();
        $data['artikel'] = $this->Artikel_model->get_all($limit, $offset);
        
        // Pagination
        $config['base_url'] = site_url('admin/artikel/index');
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
        $this->load->view('admin/artikel/index', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Form tambah artikel
     */
    public function add()
    {
        $data['title'] = 'Tambah Artikel';
        $data['user'] = $this->session->userdata();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/artikel/add', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses tambah artikel
     */
    public function proses_add()
    {
        $this->form_validation->set_rules('judul', 'Judul', 'required');
        $this->form_validation->set_rules('konten', 'Konten', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/artikel/add');
        }

        $config['upload_path'] = './uploads/artikel/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '2048';
        $config['encrypt_name'] = TRUE;
        
        $this->upload->initialize($config);
        
        $foto = '';
        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data('file_name');
            }
        }

        $data = array(
            'judul' => $this->input->post('judul'),
            'slug' => url_title($this->input->post('judul'), '-', true),
            'konten' => $this->input->post('konten'),
            'kategori' => $this->input->post('kategori'),
            'foto' => $foto,
            'author' => $this->session->userdata('user_id'),
            'status' => 'publish',
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->Artikel_model->create($data)) {
            $this->session->set_flashdata('success', 'Artikel berhasil ditambahkan!');
            redirect('admin/artikel');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan artikel!');
            redirect('admin/artikel/add');
        }
    }

    /**
     * Form edit artikel
     */
    public function edit($id)
    {
        $data['title'] = 'Edit Artikel';
        $data['user'] = $this->session->userdata();
        $data['artikel'] = $this->Artikel_model->get_by_id($id);
        
        if (!$data['artikel']) {
            show_404();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/artikel/edit', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses edit artikel
     */
    public function proses_edit($id)
    {
        $artikel = $this->Artikel_model->get_by_id($id);
        if (!$artikel) {
            show_404();
        }

        $this->form_validation->set_rules('judul', 'Judul', 'required');
        $this->form_validation->set_rules('konten', 'Konten', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/artikel/edit/' . $id);
        }

        $data = array(
            'judul' => $this->input->post('judul'),
            'slug' => url_title($this->input->post('judul'), '-', true),
            'konten' => $this->input->post('konten'),
            'kategori' => $this->input->post('kategori'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Upload foto jika ada
        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path'] = './uploads/artikel/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '2048';
            $config['encrypt_name'] = TRUE;
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('foto')) {
                // Hapus foto lama
                if ($artikel->foto && file_exists('./uploads/artikel/' . $artikel->foto)) {
                    unlink('./uploads/artikel/' . $artikel->foto);
                }
                $data['foto'] = $this->upload->data('file_name');
            }
        }

        if ($this->Artikel_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Artikel berhasil diupdate!');
            redirect('admin/artikel');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate artikel!');
            redirect('admin/artikel/edit/' . $id);
        }
    }

    /**
     * Hapus artikel
     */
    public function delete($id)
    {
        $artikel = $this->Artikel_model->get_by_id($id);
        if (!$artikel) {
            show_404();
        }

        if ($artikel->foto && file_exists('./uploads/artikel/' . $artikel->foto)) {
            unlink('./uploads/artikel/' . $artikel->foto);
        }

        if ($this->Artikel_model->delete($id)) {
            $this->session->set_flashdata('success', 'Artikel berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus artikel!');
        }
        
        redirect('admin/artikel');
    }
}
