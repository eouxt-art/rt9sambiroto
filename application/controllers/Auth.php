<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    /**
     * Login page
     */
    public function login()
    {
        // Redirect jika sudah login
        if ($this->session->userdata('user_id')) {
            redirect('admin/dashboard');
        }

        $data['title'] = 'Login - RT 9 Sambiroto';
        $this->load->view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function proses_login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Username dan Password harus diisi!');
            redirect('auth/login');
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->User_model->authenticate($username, $password);

        if ($user) {
            $session_data = array(
                'user_id' => $user->id,
                'username' => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'role' => $user->role,
                'logged_in' => TRUE
            );
            $this->session->set_userdata($session_data);
            $this->session->set_flashdata('success', 'Login berhasil!');
            redirect('admin/dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau Password salah!');
            redirect('auth/login');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Logout berhasil!');
        redirect('auth/login');
    }
}
