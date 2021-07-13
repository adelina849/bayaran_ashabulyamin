<?php

  class C_admin extends CI_Controller
  {
    public function __construct()
		{
			parent::__construct();

			$this->load->model(array('M_home','M_proyek','M_siswa','M_kelas','M_tahun_ajaran','M_uang_keluar','M_laporan'));
		}

		public function index()
		{
      if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
			{
				header('Location: '.base_url().'login');
			}
			else
			{
        header('Location: '.base_url().'home');
      }


    }

    public function home()
    {
      if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
      {
        header('Location: '.base_url().'login');
      }
      else
      {
        $semester = $this->M_tahun_ajaran->get_tahun_semester_date();

        $uang_keluar = $this->M_uang_keluar->get_by_date($semester->mulai_tgl,$semester->akhir_tgl)->nominal;

        $list_proyek = $this->M_proyek->get_all();
        $total_siswa = $this->M_siswa->total_siswa()->total_siswa;
        $total_kelas = $this->M_kelas->total_kelas()->total_kelas;
        $total_proyek = $this->M_proyek->total_proyek()->total_proyek;

        $tgl = date('Y-m-d');

        $saldo_kas = $this->M_laporan->summary_saldo('2016-01-01',$tgl)->saldo;


        $list_siswa_unreg = $this->M_siswa->list_siswa_unreg('');

        $data = array('list_proyek' => $list_proyek,
                      'total_siswa' => $total_siswa,
                      'total_kelas' => $total_kelas,
                      'total_proyek' => $total_proyek,
                      'uang_keluar' => $uang_keluar,
                      'saldo_kas' => $saldo_kas,
                      'list_siswa_unreg' => $list_siswa_unreg,
                      );

        if($this->session->userdata('ses_auto_start') == '1')
        {
          $this->session->set_flashdata('start','1');
          $this->session->unset_userdata('ses_auto_start');
        }

        $this->load->view('admin/home.html',$data);
      }
    }


  }

 ?>
