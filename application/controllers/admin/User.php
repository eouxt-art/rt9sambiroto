<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        // Check login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * List semua user
     */
    public function index($page = 0)
    {
        $data['title'] = 'Data User';
        $data['user'] = $this->session->userdata();
        
        $limit = 10;
        $offset = ($page > 0) ? ($page - 1) * $limit : 0;
        
        $total_users = $this->User_model->count_all();
        $data['users'] = $this->User_model->get_all($limit, $offset);
        
        // Pagination
        $config['base_url'] = site_url('admin/user/index');
        $config['total_rows'] = $total_users;
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
        $this->load->view('admin/user/index', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Form tambah user
     */
    public function add()
    {
        $data['title'] = 'Tambah User';
        $data['user'] = $this->session->userdata();
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/user/add', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses tambah user
     */
    public function proses_add()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/user/add');
        }

        $data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'role' => $this->input->post('role'),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->User_model->create($data)) {
            $this->session->set_flashdata('success', 'User berhasil ditambahkan!');
            redirect('admin/user');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan user!');
            redirect('admin/user/add');
        }
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $data['title'] = 'Edit User';
        $data['user'] = $this->session->userdata();
        $data['user_data'] = $this->User_model->get_by_id($id);
        
        if (!$data['user_data']) {
            show_404();
        }
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/user/edit', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Proses edit user
     */
    public function proses_edit($id)
    {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            show_404();
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/user/edit/' . $id);
        }

        $data = array(
            'email' => $this->input->post('email'),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'role' => $this->input->post('role'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Jika password diisi
        if (!empty($this->input->post('password'))) {
            $data['password'] = $this->input->post('password');
        }

        if ($this->User_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'User berhasil diupdate!');
            redirect('admin/user');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate user!');
            redirect('admin/user/edit/' . $id);
        }
    }

    /**
     * Hapus user
     */
    public function delete($id)
    {
        // Jangan hapus user sendiri
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak bisa menghapus akun sendiri!');
            redirect('admin/user');
        }

        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user!');
        }
        
        redirect('admin/user');
    }
}
