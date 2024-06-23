<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // $this->load->model('DataModel');
        $this->load->model('Auth_model');
    }

    public function index()
    {

        $data['tahun'] = $this->Auth_model->getAll('tahun')->result();

        // $this->load->view('layout/head');
        $this->load->view('login', $data);
        // $this->load->view('layout/foot');
    }

    public function masuk()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        // $rules = $this->Auth_model->rules();
        // $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login');
        }

        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $tahun = $this->input->post('tahun', true);

        if ($this->Auth_model->login($username, $password, $tahun)) {
            $user = $this->Auth_model->current_user();
            $akses = $this->db->query("SELECT login FROM akses WHERE tahun = '$tahun' AND lembaga = '$user->lembaga' ");
            if ($user->level != 'lembaga' || ($akses->row('login') === 'Y' && $user->level === 'lembaga')) {
                if ($user->level === 'admin') {
                    $this->session->set_flashdata('ok', 'Login Berhasil, Selamat Datang di Aplikasi SIMKUPADUKA!');
                    redirect('admin');
                } elseif ($user->level === 'account') {
                    $this->session->set_flashdata('ok', 'Login Berhasil, Selamat Datang di Aplikasi SIMKUPADUKA!');
                    redirect('account');
                } elseif ($user->level === 'kasir') {
                    $this->session->set_flashdata('ok', 'Login Berhasil, Selamat Datang di Aplikasi SIMKUPADUKA!');
                    redirect('kasir');
                } elseif ($user->level === 'lembaga') {
                    $this->session->set_flashdata('ok', 'Login Berhasil, Selamat Datang di Aplikasi SIMKUPADUKA!');
                    redirect('lembaga');
                } elseif ($user->level === 'kepala') {
                    $this->session->set_flashdata('ok', 'Login Berhasil, Selamat Datang di Aplikasi SIMKUPADUKA!');
                    redirect('kepala');
                } else {
                    $this->session->set_flashdata('error', 'Akses denied!');
                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('error', 'Anda tidak diizinkan untuk mengakses tahun ajaran ini');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('error', 'Login Gagal, pastikan username dan passwrod benar!');
            redirect('login');
            // echo "
            // <script>
            //     alert('Maaf username atau password salah');
            //     window.location = '" . base_url('login') . "';
            // </script>
            // ";
            // $this->load->view('login');
        }
    }

    public function daftar()
    {
        $this->load->view('daftar');
    }

    public function daftarAct()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $passOk = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'id_user' => $this->uuid->v4(),
            'nama' => strtoupper($this->input->post('nama', true)),
            'jabatan' => $this->input->post('jabatan', true),
            'username' => $username,
            'password' => $passOk,
            'aktif' => 'T',
            'level' => 'adm',

        ];

        $this->Auth_model->tambah('user', $data);
        if ($this->db->affected_rows()) {
            $this->session->set_flashdata('ok', 'Akun sudah dibuat. Silahkan menghubungi admin untuk aktifasi akun anda');
            redirect('login/daftar');
        }
    }

    public function logout()
    {
        // $this->load->model('Auth_model');
        $this->Auth_model->logout();
        $this->session->set_flashdata('ok', 'Anda berhasil logout');
        redirect('login');
    }
}
