<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_siswa extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_siswa','M_kelas','M_kelas_siswa','M_tahun_ajaran','M_tran_siswa'));
        $this->load->library('form_validation');
	$this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
          $this->load->view('admin/page/c_siswa/siswa.html');
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_siswa->json();
    }

    public function read($id)
    {
        $row_detail = $this->M_siswa->get_by_id($id);
        $detail = $this->M_siswa->detail_siswa($id);

        $list_transaksi = $this->M_tran_siswa->history_transaksi($id);

        $mulai_ajaran = $detail->tgl_mulai;
        $akhir_ajaran = $detail->tgl_akhir;

        $list_periode = $this->M_tran_siswa->get_list_periode($mulai_ajaran,$akhir_ajaran);

        $list_semester = $this->M_tran_siswa->get_list_semester($detail->id_ajaran);

        //list bayaran bulanan
        $c_date = date('Y-m');
        $total_sisa_bulanan = 0;

        if(!empty($list_periode))
        {
            foreach ($list_periode as $row) {
              if($row->nama_periode <= $c_date)
              {
                $r = $this->M_tran_siswa->get_tagihan_per_periode($row->nama_periode, $id,3,$row->nama_periode,$row->nama_periode);
                $total_sisa_bulanan += $r->sisa_bayar;
                //echo $xrow->nama_siswa;
              }
            }
        }

        //list bayaran semester
        $total_sisa_semester = 0;

        if(!empty($list_semester))
        {
          foreach ($list_semester as $row) {
            // code...
          //  if($row->akhir_tgl <= $c_date)
          //  {
                $s = $this->M_tran_siswa->get_tagihan_per_periode($row->nama_semester, $id,2,$row->akhir_tgl,$row->kode_semester);
                $total_sisa_semester += $s->sisa_bayar;
          //  }
          }
        }

        //list bayaran tahunan
        $batas = substr($akhir_ajaran,0,7);

        $total_sisa_tahunan = 0;
        $tagihanTahunan = array();
        $t = $this->M_tran_siswa->get_tagihan_per_periode($detail->nama_ajaran,  $id,1,$batas,$detail->kode_tahun);
        $total_sisa_tahunan += $t->sisa_bayar;

        //
        //list bayaran tetap
        $total_sisa_tetap = 0;
        $tagihanTetap = array();
        $tp = $this->M_tran_siswa->get_tagihan_per_periode($detail->nama_ajaran,$id,4,'2030-12',$detail->kode_tahun);
        $total_sisa_tetap += $tp->sisa_bayar;

        print_r($total_sisa_bulanan);
        print_r($total_sisa_semester);
        print_r($total_sisa_tahunan);
        print_r($total_sisa_tetap);

        if ($row_detail) {
            $data = array(
          		'id_siswa' => $row_detail->id_siswa,
          		'kode_siswa' => $row_detail->kode_siswa,
          		'nama_siswa' => $row_detail->nama_siswa,
              'nama_kelas' => $detail->nama_kelas,
          		'tgl_lahir' => $row_detail->tgl_lahir,
          		'jkel' => $row_detail->jkel,
              'hutang_awal' => $row_detail->hutang_awal,
          		'alamat' => $row_detail->alamat,
              'avatar' => $row_detail->avatar,
          		'no_hp' => $row_detail->no_hp,
              'list_transaksi' => $list_transaksi,
              'total_sisa_bulanan' => $total_sisa_bulanan,
              'total_sisa_semester' => $total_sisa_semester,
              'total_sisa_tahunan' => $total_sisa_tahunan,
              'total_sisa_tetap' => $total_sisa_tetap,
	           );
            $this->load->view('admin/page/c_siswa/view-siswa.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('siswa'));
        }
    }

    public function create()
    {
       $list_kelas = $this->M_kelas->list_kelas();

       $ajaran = $this->M_tahun_ajaran->get_all();

        $data = array(
            'button' => 'Create',
            'action' => site_url('c_siswa/create_action'),
      	    'id_siswa' => set_value('id_siswa'),
      	    'kode_siswa' => set_value('kode_siswa'),
      	    'nama_siswa' => set_value('nama_siswa'),
      	    'tgl_lahir' => set_value('tgl_lahir'),
      	    'jkel' => set_value('jkel'),
            'hutang_awal' => set_value('hutang_awal'),
      	    'alamat' => set_value('alamat'),
      	    'no_hp' => set_value('no_hp'),
            'list_kelas' => $list_kelas,
            'ajaran' => $ajaran,
            'c_kelas' => '',
            'c_ajaran' => '',
      	);
        $this->load->view('admin/page/c_siswa/input-siswa.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');

            $tgl2 = date('Ymd');
            $gen_foto = $tgl2.'-'.$this->input->post('kode_siswa',TRUE);
            //$gen_foto = "dsdsdsdsfsfsf";
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto = $gen_foto.'.'.$ext;

            $data = array(
            		'kode_siswa' => $this->input->post('kode_siswa',TRUE),
            		'nama_siswa' => $this->input->post('nama_siswa',TRUE),
            		'tgl_lahir' => $this->input->post('tgl_lahir',TRUE),
            		'jkel' => $this->input->post('jkel',TRUE),
            		'alamat' => $this->input->post('alamat',TRUE),
                'hutang_awal' => $this->input->post('hutang_awal',TRUE),
            		'no_hp' => $this->input->post('no_hp',TRUE),
                'avatar' => $foto,
            		'tgl_ins' => $tgl,
            		'user_updt' => $this->session->userdata('ses_id_akun'),
      	    );

            $this->M_siswa->insert($data);

            $this->do_upload($_FILES['foto']['name'],$gen_foto);

            //ambil id dari data siswa yang sudah di insert
            $id_siswa = $this->M_siswa->get_siswa_by_kode($this->input->post('kode_siswa',TRUE))->id_siswa;

            //simpan siswa di kelas jika ada nilai kelas
            if(!empty($this->input->post('kelas',TRUE))) {
              $this->M_kelas_siswa->simpan(
                $this->input->post('kelas',TRUE),
                $id_siswa,
                $this->input->post('ajaran',TRUE)
              );
            }

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('siswa'));
        }
    }

    public function update($id)
    {
        $row = $this->M_siswa->get_by_id($id);
        $list_kelas = $this->M_kelas->list_kelas();
        $ajaran = $this->M_tahun_ajaran->get_all();

        $kelas = $this->M_kelas_siswa->get_kelas_by_siswa($id);
        if(!empty($kelas))
        {
          $id_kelas = $kelas->id_kelas;
          $id_ajaran = $kelas->id_ajaran;
        } else {
          $id_kelas = '';
          $id_ajaran = '';
        }

        if ($row) {

            $data = array(
                'button' => 'Update',
                'action' => site_url('c_siswa/update_action'),
            		'id_siswa' => set_value('id_siswa', $row->id_siswa),
            		'kode_siswa' => set_value('kode_siswa', $row->kode_siswa),
            		'nama_siswa' => set_value('nama_siswa', $row->nama_siswa),
            		'tgl_lahir' => set_value('tgl_lahir', $row->tgl_lahir),
            		'jkel' => set_value('jkel', $row->jkel),
                'hutang_awal' => set_value('hutang_awal', $row->hutang_awal),
            		'alamat' => set_value('alamat', $row->alamat),
            		'no_hp' => set_value('no_hp', $row->no_hp),
                'c_kelas' => $id_kelas,
                'ajaran' => $ajaran,
                'c_ajaran' => $id_ajaran,
                'list_kelas' => $list_kelas,
        	    );
            $this->load->view('admin/page/c_siswa/input-siswa.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('siswa'));
        }
    }

    public function update_action()
    {
        $this->_rules_update();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_siswa', TRUE));
        } else {

            $cek_nis = $this->M_siswa->cek_nis($this->input->post('id_siswa', TRUE),$this->input->post('kode_siswa',TRUE))->REC;

            if($cek_nis == 0)
            {
              $tgl = date('Y-m-d H:i:s');
              $data = array(
  		          'kode_siswa' => $this->input->post('kode_siswa',TRUE),
            		'nama_siswa' => $this->input->post('nama_siswa',TRUE),
            		'tgl_lahir' => $this->input->post('tgl_lahir',TRUE),
            		'jkel' => $this->input->post('jkel',TRUE),
                'hutang_awal' => $this->input->post('hutang_awal',TRUE),
            		'alamat' => $this->input->post('alamat',TRUE),
            		'no_hp' => $this->input->post('no_hp',TRUE),
            		'tgl_updt' => $tgl,
            		'user_updt' => $this->session->userdata('ses_id_akun'),
            	);

              $this->M_siswa->update($this->input->post('id_siswa', TRUE), $data);
              $this->session->set_flashdata('message', 'Update Record Success');
              redirect(site_url('siswa'));
            } else {
              $this->session->set_flashdata('message', '<span class="text-danger">NIS Siswa Sudah Terpakai</span>');
              $this->update($this->input->post('id_siswa', TRUE));
            }
        }
    }

    public function delete($id)
    {
        $row = $this->M_siswa->get_by_id($id);

        if ($row) {
            $this->M_siswa->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('siswa'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('siswa'));
        }
    }

    public function _rules()
    {
      	$this->form_validation->set_rules('kode_siswa', 'kode siswa', 'trim|required|is_unique[tb_siswa.kode_siswa]');
      	$this->form_validation->set_rules('nama_siswa', 'nama siswa', 'trim|required');
      	$this->form_validation->set_rules('tgl_lahir', 'tgl lahir', 'trim|required');
      	$this->form_validation->set_rules('jkel', 'jkel', 'trim|required');
      	$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
      	$this->form_validation->set_rules('no_hp', 'no hp', 'trim|required');

      	$this->form_validation->set_rules('id_siswa', 'id_siswa', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function _rules_update()
    {
      	$this->form_validation->set_rules('kode_siswa', 'kode siswa', 'trim|required');
      	$this->form_validation->set_rules('nama_siswa', 'nama siswa', 'trim|required');
      	$this->form_validation->set_rules('tgl_lahir', 'tgl lahir', 'trim|required');
      	$this->form_validation->set_rules('jkel', 'jkel', 'trim|required');
      	$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
      	$this->form_validation->set_rules('no_hp', 'no hp', 'trim|required');

      	$this->form_validation->set_rules('id_siswa', 'id_siswa', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }


    function do_upload($id,$cek_bfr)
  	{
  		$this->load->library('upload');

  		if($cek_bfr != '')
  		{
  			@unlink('./assets/images/siswa/'.$cek_bfr);
  		}

  		if (!empty($_FILES['foto']['name']))
  		{
  			$config['upload_path'] = 'assets/images/siswa/';
  			$config['allowed_types'] = 'gif|jpg|png';
  			$config['max_size']	= '2024';
  			//$config['max_widtd']  = '300';
  			//$config['max_height']  = '300';
  			$config['file_name']	= $cek_bfr;
  			$config['overwrite']	= true;


  			$this->upload->initialize($config);

  			//Upload file 1
  			if ($this->upload->do_upload('foto'))
  			{
  				$hasil = $this->upload->data();
  			}
  		}
  	}

}

/* End of file C_siswa.php */
/* Location: ./application/controllers/C_siswa.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-06-29 16:43:06 */
/* http://harviacode.com */
