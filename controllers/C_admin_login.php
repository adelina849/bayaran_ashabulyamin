<?php

  class C_admin_login extends CI_Controller
  {
    public function __construct()
		{
			parent::__construct();

      $this->load->helper(array('form','url'));
			$this->load->model(array('M_akun'));
      $this->load->library(array('form_validation'));
		}

		public function index()
		{

      $this->load->view('admin/login.html');

    }

    function cek_login()
		{
			$user = $_POST['username'];
      $pass = $_POST['password'];

      $data_login = $this->M_akun->get_login($user,md5($pass));
  		if(!empty($data_login))
  		{
					if ($data_login->avatar_url <> "")
					{
						$src = $data_login->avatar_url;
					}
					else
					{
						$src = base_url().'assets/images/member/noimage.png';   //tentukan url image
					}

					$member = array(
						'ses_id_akun'  => $data_login->id_akun,
            'ses_id_karyawan'  => $data_login->id_karyawan,
						'ses_username'  => $user,
						'ses_pass'  => base64_encode($pass),
						'ses_nama_karyawan' => $data_login->nama_karyawan,
						'ses_nik_karyawan' => $data_login->nik_karyawan,
						'ses_tlp' => $data_login->tlp,
						'ses_email' => $data_login->email,
						'ses_alamat' => $data_login->alamat,
						'ses_avatar_url' => $src,
            'ses_auto_start' => '1',
					);

					$this->session->set_userdata($member);

					header('Location: '.base_url());
    		}
    		else
    		{
				  $this->session->set_flashdata('msg','Username atau password salah');
				  header('Location: '.base_url().'login');
    		}
		}

    public function daftar()
    {
      $this->load->view('admin/register.html');
    }

    public function do_register()
    {
      $kode_member = $this->M_akun->get_no_member()->no_member;
			$foto = '';

			//validasi form dlu
			$this->form_validation->set_rules('username','Username','required|valid_email|is_unique[tb_member.username]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[15]');
			$this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password]');
      $this->form_validation->set_rules('nama', 'Nama', 'required');
      $this->form_validation->set_rules('hp', 'No. Handphone', 'required');
      $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
      $this->form_validation->set_rules('day_pregan', 'Usia Kehamilan', 'required');
      $this->form_validation->set_rules('berat', 'Berat Badan', 'required');

			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('msg',validation_errors());
				header('Location: '.base_url().'daftar');
			} else {

				if($this->M_akun->simpan
				(
					$kode_member
					,$_POST['nama']
          ,$_POST['tgl_lahir']
          ,''
          ,$_POST['hp']
          ,$_POST['username']
					,md5($_POST['password'])
          ,$_POST['day_pregan']
          ,$_POST['berat']
					,base_url().'assets/images/member/'.$foto
				))
				{
						/* SET SESSION*/
            $data_login = $this->M_akun->get_login($_POST['username'],md5($_POST['password']));
        		if(!empty($data_login))
        		{
      					if ($data_login->avatar_url <> "")
      					{
      						$src = $data_login->avatar_url;
      					}
      					else
      					{
      						$src = base_url().'assets/images/member/noimage.png';   //tentukan url image
      					}

      					$member = array(
      						'ses_public_id_member'  => $data_login->id_member,
      						'ses_public_user'  => $data_login->username,
      						'ses_public_pass'  => base64_encode($data_login->pass),
      						'ses_public_nama_member' => $data_login->nama_lengkap,
      						'ses_public_no_member' => $data_login->no_member,
      						'ses_public_avatar_url' => $src,
      						'ses_public_tgl_lahir' => $data_login->tgl_lahir,
      						'ses_public_alamat' => $data_login->alamat,
      						'ses_public_hp' => $data_login->hp,
                  'ses_auto_start' => '1',
      					);

      					$this->session->set_userdata($member);

      					header('Location: '.base_url());
            }
				} else {
					//gagal simpan
					$this->session->set_flashdata('msg','Something Wrong. Please try again ...');
					header('Location: '.base_url().'daftar');
				}
			}
    }

    function logout()
    {
      $this->session->unset_userdata('ses_id_akun');
      $this->session->unset_userdata('ses_id_karyawan');
      $this->session->unset_userdata('ses_username');
      $this->session->unset_userdata('ses_pass');
      $this->session->unset_userdata('ses_nama_karyawan');
      $this->session->unset_userdata('ses_nik_karyawan');
      $this->session->unset_userdata('ses_tlp');
      $this->session->unset_userdata('ses_email');
      $this->session->unset_userdata('ses_alamat');
      $this->session->unset_userdata('ses_avatar_url');
      $this->session->unset_userdata('ses_auto_start');

      //redirect('index.php/login','location');
      header('Location: '.base_url().'login');
    }
  }

 ?>
