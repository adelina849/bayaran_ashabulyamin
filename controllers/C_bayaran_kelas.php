<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_bayaran_kelas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_bayaran_kelas','M_kelas','M_siswa','M_bayaran','M_tahun_ajaran','M_tran_siswa'));
        $this->load->library('form_validation');
	      $this->load->library('datatables');
    }

    public function index()
    {
      if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
      {
        header('Location: '.base_url().'login');
      } else {
        if((!empty($_GET['ajaran'])) && ($_GET['ajaran']!= "")  )
        {
          $c_ajaran = $_GET['ajaran'];
        } else {
          $c_ajaran = '';
        }

        if((!empty($_GET['kelas'])) && ($_GET['kelas']!= "")  )
        {
          $c_kelas = $_GET['kelas'];
        } else {
          $c_kelas = '';
        }

        $ajaran = $this->M_tahun_ajaran->get_all();
        $kelas = $this->M_kelas->get_all();
        $list_bayaran = $this->M_bayaran->get_all();

        $list_bayaran_kelas = $this->M_bayaran_kelas->list_bayaran_kelas($c_ajaran,$c_kelas,0,100);

        $data = array('list_ajaran'=>$ajaran,
                      'list_kelas'=>$kelas,
                      'list_bayaran_kelas'=>$list_bayaran_kelas,
                      'list_bayaran' => $list_bayaran,
                      );

        $this->load->view('admin/page/c_bayaran_kelas/bayaran_kelas.html', $data);
      }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_bayaran_kelas->json();
    }

    public function read($id)
    {
        $row = $this->M_bayaran_kelas->get_by_id($id);
        if ($row) {
            $data = array(
          		'id_bayaran_kelas' => $row->id_bayaran_kelas,
          		'id_tahun_ajaran' => $row->id_tahun_ajaran,
          		'id_kelas' => $row->id_kelas,
          		'id_bayaran' => $row->id_bayaran,
          		'kode_bayaran_kelas' => $row->kode_bayaran_kelas,
          		'nama_bayaran_kelas' => $row->nama_bayaran_kelas,
          		'keterangan' => $row->keterangan,
          		'tgl_ins' => $row->tgl_ins,
          		'tgl_updt' => $row->tgl_updt,
          		'user_updt' => $row->user_updt,
          	);
            $this->load->view('c_bayaran_kelas/tb_bayaran_kelas_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_bayaran_kelas'));
        }
    }

    public function create()
    {
        $ajaran = $this->M_tahun_ajaran->get_all();
        $kelas = $this->M_kelas->get_all();
        $list_bayaran = $this->M_bayaran->get_all();

        $data = array(
            'button' => 'Create',
            'action' => site_url('c_bayaran_kelas/create_action'),
      	    'id_bayaran_kelas' => set_value('id_bayaran_kelas'),
      	    'list_ajaran' => $ajaran,
      	    'list_kelas' => $kelas,
      	    'list_bayaran' => $list_bayaran,
            'c_ajaran' => '',
            'c_kelas' => '',
            'c_bayaran' => '',
      	    'kode_bayaran_kelas' => set_value('kode_bayaran_kelas'),
      	    'nama_bayaran_kelas' => set_value('nama_bayaran_kelas'),
      	    'keterangan' => set_value('keterangan'),
      	);
        $this->load->view('admin/page/c_bayaran_kelas/input_bayaran_kelas.html', $data);
    }

    public function get_mulai_berlaku()
    {
      $id = $this->input->post('id',TRUE);
      $id_tahun_ajaran = $this->input->post('id_tahun_ajaran',TRUE);

      $tahun_ajaran = $this->M_tahun_ajaran->get_by_id($id_tahun_ajaran);

      $cek_kat = $this->M_bayaran->get_by_id($id)->id_kat_bayaran;
      //echo $cek_kat;

      if($cek_kat == '3') { //jika bulanan
        $list_periode = $this->M_tran_siswa->get_list_periode($tahun_ajaran->tgl_mulai,$tahun_ajaran->tgl_akhir);

        if(!empty($list_periode))
        {
          foreach ($list_periode as $row) {
            // code...
            echo '<option value="'.$row->nama_periode.'">'.$row->nama_periode.'</option>';
          }
        }
      } else if($cek_kat == '2') {   //jika semester
        $list_semester = $this->M_tahun_ajaran->list_semester_ajaran($id_tahun_ajaran);

        if(!empty($list_semester))
        {
          foreach ($list_semester as $row) {
            // code...
            echo '<option value="'.$row->mulai_tgl.'">'.$row->nama_semester.'</option>';
          }
        }
      } else if($cek_kat == '1') {   //jika tahunan
          echo '<option value="'.$tahun_ajaran->tgl_akhir.'">'.$tahun_ajaran->nama_ajaran.'</option>';
      }
    }

    public function cek_data()
    {
       $cek_data = $this->M_bayaran_kelas->cek_ada_data(
         $_POST['id_ajaran'],
         $_POST['id_kelas'],
         $_POST['id_bayaran']
       );

       echo $cek_data;
    }

    public function create_action()
    {
        //$this->_rules();
        $id_bayaran_kelas = $this->input->post('id_bayaran_kelas',TRUE);
        $tgl = date('Y-m-d H:i:s');

        if($id_bayaran_kelas == '')
        {
          $data = array(
            'id_tahun_ajaran' => $this->input->post('id_ajaran',TRUE),
            'id_kelas' => $this->input->post('id_kelas',TRUE),
            'id_bayaran' => $this->input->post('id_bayaran',TRUE),
            'mulai_berlaku' => $this->input->post('mulai_berlaku',TRUE),
            'kode_bayaran_kelas' => $this->input->post('kode_bayaran_kelas',TRUE),
            'nama_bayaran_kelas' => $this->input->post('nama_bayaran_kelas',TRUE),
            'keterangan' => $this->input->post('keterangan',TRUE),
            'tgl_ins' => $tgl,
            'user_updt' => $this->session->userdata('ses_id_akun'),
          );

          $this->M_bayaran_kelas->insert($data);
          $this->session->set_flashdata('message', 'Create Record Success');
        } else {
          $data = array(
            'id_tahun_ajaran' => $this->input->post('id_ajaran',TRUE),
            'id_kelas' => $this->input->post('id_kelas',TRUE),
            'id_bayaran' => $this->input->post('id_bayaran',TRUE),
            'mulai_berlaku' => $this->input->post('mulai_berlaku',TRUE),
            'kode_bayaran_kelas' => $this->input->post('kode_bayaran_kelas',TRUE),
            'nama_bayaran_kelas' => $this->input->post('nama_bayaran_kelas',TRUE),
            'keterangan' => $this->input->post('keterangan',TRUE),
            'tgl_updt' => $tgl,
            'user_updt' => $this->session->userdata('ses_id_akun'),
          );
          $this->M_bayaran_kelas->update($this->input->post('id_bayaran_kelas', TRUE), $data);
          $this->session->set_flashdata('message', 'Update Record Success');
        }
        //if ($this->form_validation->run() == FALSE) {
        //    $this->create();
        //} else {
        echo 'ok';



        //redirect(site_url('2-bayaran-kelas'));
        //}
    }

    public function update($id)
    {
        $row = $this->M_bayaran_kelas->get_by_id($id);
        $ajaran = $this->M_tahun_ajaran->get_all();
        $kelas = $this->M_kelas->get_all();
        $list_bayaran = $this->M_bayaran->get_all();

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_bayaran_kelas/update_action'),
            		'id_bayaran_kelas' => set_value('id_bayaran_kelas', $row->id_bayaran_kelas),
                'list_ajaran' => $ajaran,
                'list_kelas' => $kelas,
                'list_bayaran' => $list_bayaran,
                'c_ajaran' => $row->id_tahun_ajaran,
                'c_kelas' => $row->id_kelas,
                'c_bayaran' => $row->id_bayaran,
            		'kode_bayaran_kelas' => set_value('kode_bayaran_kelas', $row->kode_bayaran_kelas),
            		'nama_bayaran_kelas' => set_value('nama_bayaran_kelas', $row->nama_bayaran_kelas),
            		'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('admin/page/c_bayaran_kelas/input_bayaran_kelas.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_bayaran_kelas'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_bayaran_kelas', TRUE));
        } else {
            $data = array(
          		'id_tahun_ajaran' => $this->input->post('id_tahun_ajaran',TRUE),
          		'id_kelas' => $this->input->post('id_kelas',TRUE),
          		'id_bayaran' => $this->input->post('id_bayaran',TRUE),
          		'kode_bayaran_kelas' => $this->input->post('kode_bayaran_kelas',TRUE),
          		'nama_bayaran_kelas' => $this->input->post('nama_bayaran_kelas',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_ins' => $this->input->post('tgl_ins',TRUE),
          		'tgl_updt' => $this->input->post('tgl_updt',TRUE),
          		'user_updt' => $this->input->post('user_updt',TRUE),
          	);

            $this->M_bayaran_kelas->update($this->input->post('id_bayaran_kelas', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('c_bayaran_kelas'));
        }
    }

    public function delete()
    {
        $id = $this->input->post('id', TRUE);

        $row = $this->M_bayaran_kelas->get_by_id($id);

        if ($row) {
            $this->M_bayaran_kelas->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('2-bayaran-kelas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('2-bayaran-kelas'));
        }
    }

    public function _rules()
    {
    	$this->form_validation->set_rules('id_tahun_ajaran', 'id tahun ajaran', 'trim|required');
    	$this->form_validation->set_rules('id_kelas', 'id kelas', 'trim|required');
    	$this->form_validation->set_rules('id_bayaran', 'id bayaran', 'trim|required');
    	//$this->form_validation->set_rules('kode_bayaran_kelas', 'kode bayaran kelas', 'trim|required');
    	//$this->form_validation->set_rules('nama_bayaran_kelas', 'nama bayaran kelas', 'trim|required');
    	//$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

    	$this->form_validation->set_rules('id_bayaran_kelas', 'id_bayaran_kelas', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_bayaran_kelas.php */
/* Location: ./application/controllers/C_bayaran_kelas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-09 16:38:57 */
/* http://harviacode.com */
