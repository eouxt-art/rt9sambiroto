<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Agenda_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * List agenda
     */
    public function index($page = 0)
    {
        $data['title'] = 'Data Agenda';
        $data['user'] = $this->session->userdata();
        
        $limit = 10;
        $offset = ($page > 0) ? ($page - 1) * $limit : 0;
        
        $total = $this->Agenda_model->count_all();
        $data['agenda'] = $this->Agenda_model->get_all($limit, $offset);
        
        // Pagination
        $config['base_url'] = site_url('admin/agenda/index');
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
        $this->load->view('admin/agenda/index', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Form tambah agenda
     */
    public function add()
    {
        $data['title'] = 'Tambah Agenda';
        $data['user'] = $this->session->userdata();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/agenda/add', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses tambah agenda
     */
    public function proses_add()
    {
        $this->form_validation->set_rules('judul', 'Judul Kegiatan', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('waktu', 'Waktu', 'required');
        $this->form_validation->set_rules('tempat', 'Tempat', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/agenda/add');
        }

        $data = array(
            'judul' => $this->input->post('judul'),
            'deskripsi' => $this->input->post('deskripsi'),
            'tanggal' => $this->input->post('tanggal'),
            'waktu' => $this->input->post('waktu'),
            'tempat' => $this->input->post('tempat'),
            'status' => 'aktif',
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->Agenda_model->create($data)) {
            $this->session->set_flashdata('success', 'Agenda berhasil ditambahkan!');
            redirect('admin/agenda');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan agenda!');
            redirect('admin/agenda/add');
        }
    }

    /**
     * Form edit agenda
     */
    public function edit($id)
    {
        $data['title'] = 'Edit Agenda';
        $data['user'] = $this->session->userdata();
        $data['agenda'] = $this->Agenda_model->get_by_id($id);
        
        if (!$data['agenda']) {
            show_404();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/agenda/edit', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses edit agenda
     */
    public function proses_edit($id)
    {
        $agenda = $this->Agenda_model->get_by_id($id);
        if (!$agenda) {
            show_404();
        }

        $this->form_validation->set_rules('judul', 'Judul Kegiatan', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('waktu', 'Waktu', 'required');
        $this->form_validation->set_rules('tempat', 'Tempat', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/agenda/edit/' . $id);
        }

        $data = array(
            'judul' => $this->input->post('judul'),
            'deskripsi' => $this->input->post('deskripsi'),
            'tanggal' => $this->input->post('tanggal'),
            'waktu' => $this->input->post('waktu'),
            'tempat' => $this->input->post('tempat'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Agenda_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Agenda berhasil diupdate!');
            redirect('admin/agenda');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate agenda!');
            redirect('admin/agenda/edit/' . $id);
        }
    }

    /**
     * Hapus agenda
     */
    public function delete($id)
    {
        if ($this->Agenda_model->delete($id)) {
            $this->session->set_flashdata('success', 'Agenda berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus agenda!');
        }
        
        redirect('admin/agenda');
    }
}
