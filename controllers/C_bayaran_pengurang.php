<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_bayaran_pengurang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_bayaran_siswa','M_bayaran_kelas','M_kelas','M_siswa','M_pengurang_bayaran','M_tahun_ajaran','M_bayaran_pengurang'));
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
        $list_pengurang = $this->M_pengurang_bayaran->get_all();

        $list_bayaran_pengurang = $this->M_bayaran_pengurang->list_bayaran_pengurang($c_ajaran,$c_kelas,0,100);

        $data = array('list_ajaran'=>$ajaran,
                      'list_kelas'=>$kelas,
                      'list_bayaran_pengurang'=>$list_bayaran_pengurang,
                      'list_pengurang' => $list_pengurang,
                      );

          $this->load->view('admin/page/c_bayaran_pengurang/bayaran_pengurang.html',$data);
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_bayaran_pengurang->json();
    }

    public function read($id)
    {
        $row = $this->M_bayaran_pengurang->get_by_id($id);
        if ($row) {
            $data = array(
        		'id_bayaran_siswa' => $row->id_bayaran_siswa,
        		'id_tahun_ajaran' => $row->id_tahun_ajaran,
        		'id_kelas' => $row->id_kelas,
        		'id_siswa' => $row->id_siswa,
        		'id_pengurang' => $row->id_pengurang,
        		'kode_bayaran_siswa' => $row->kode_bayaran_siswa,
        		'nama_bayaran_siswa' => $row->nama_bayaran_siswa,
        		'keterangan' => $row->keterangan,
        		'tgl_ins' => $row->tgl_ins,
        		'tgl_updt' => $row->tgl_updt,
        		'user_updt' => $row->user_updt,
        	  );
            $this->load->view('admin/page/c_bayaran_pengurang/input_bayaran_pengurang.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_bayaran_pengurang'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('c_bayaran_pengurang/create_action'),
      	    'id_bayaran_siswa' => set_value('id_bayaran_siswa'),
      	    'id_tahun_ajaran' => set_value('id_tahun_ajaran'),
      	    'id_kelas' => set_value('id_kelas'),
      	    'id_siswa' => set_value('id_siswa'),
      	    'id_pengurang' => set_value('id_pengurang'),
      	    'kode_bayaran_siswa' => set_value('kode_bayaran_siswa'),
      	    'nama_bayaran_siswa' => set_value('nama_bayaran_siswa'),
      	    'keterangan' => set_value('keterangan'),
      	    'tgl_ins' => set_value('tgl_ins'),
      	    'tgl_updt' => set_value('tgl_updt'),
      	    'user_updt' => set_value('user_updt'),
      	);
        $this->load->view('c_bayaran_pengurang/tb_bayaran_pengurang_form', $data);
    }

    public function create_action()
    {
      $local=array();
      $tgl = date('Y-m-d H:i:s');
      //$this->_rules();
      $isCek = false;
      //print_r($this->input->post('checklist',TRUE));
      for ($i=1; $i <= count($this->input->post('no2')) ; $i++) {
          if (!empty($this->input->post('checklist')[$i])) {
            $isCek = true;
            $data = array(
              'id_tahun_ajaran' => $this->input->post('id_tahun_ajaran',TRUE),
              'id_kelas' => $this->input->post('id_kelas',TRUE),
              'id_siswa' => $this->input->post('no2',TRUE)[$i],
              'id_pengurang' => $this->input->post('id_pengurang',TRUE),
              'kode_bayaran_siswa' => $this->input->post('kode_bayaran_siswa',TRUE),
              'nama_bayaran_siswa' => $this->input->post('nama_bayaran_siswa',TRUE),
              'keterangan' => $this->input->post('keterangan',TRUE),
              'tgl_ins' => $tgl,
              'user_updt' => $this->session->userdata('ses_id_akun'),
            );
            $local[] = $data;
          }


       }

      // echo '<pre>';
      // print_r($local);
      // echo '</pre>';
      if($isCek)
      {
          $this->M_bayaran_pengurang->insert($local);
      }
      

      redirect(site_url('2-bayaran-pengurang').'?'.http_build_query($_GET));

    }

    public function update($id)
    {
        $row = $this->M_bayaran_pengurang->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_bayaran_pengurang/update_action'),
		'id_bayaran_siswa' => set_value('id_bayaran_siswa', $row->id_bayaran_siswa),
		'id_tahun_ajaran' => set_value('id_tahun_ajaran', $row->id_tahun_ajaran),
		'id_kelas' => set_value('id_kelas', $row->id_kelas),
		'id_siswa' => set_value('id_siswa', $row->id_siswa),
		'id_pengurang' => set_value('id_pengurang', $row->id_pengurang),
		'kode_bayaran_siswa' => set_value('kode_bayaran_siswa', $row->kode_bayaran_siswa),
		'nama_bayaran_siswa' => set_value('nama_bayaran_siswa', $row->nama_bayaran_siswa),
		'keterangan' => set_value('keterangan', $row->keterangan),
		'tgl_ins' => set_value('tgl_ins', $row->tgl_ins),
		'tgl_updt' => set_value('tgl_updt', $row->tgl_updt),
		'user_updt' => set_value('user_updt', $row->user_updt),
	    );
            $this->load->view('c_bayaran_pengurang/tb_bayaran_pengurang_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_bayaran_pengurang'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_bayaran_siswa', TRUE));
        } else {
            $data = array(
		'id_tahun_ajaran' => $this->input->post('id_tahun_ajaran',TRUE),
		'id_kelas' => $this->input->post('id_kelas',TRUE),
		'id_siswa' => $this->input->post('id_siswa',TRUE),
		'id_pengurang' => $this->input->post('id_pengurang',TRUE),
		'kode_bayaran_siswa' => $this->input->post('kode_bayaran_siswa',TRUE),
		'nama_bayaran_siswa' => $this->input->post('nama_bayaran_siswa',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'tgl_ins' => $this->input->post('tgl_ins',TRUE),
		'tgl_updt' => $this->input->post('tgl_updt',TRUE),
		'user_updt' => $this->input->post('user_updt',TRUE),
	    );

            $this->M_bayaran_pengurang->update($this->input->post('id_bayaran_siswa', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('c_bayaran_pengurang'));
        }
    }

    public function delete()
    {
        $id = $_POST['id'];
        $row = $this->M_bayaran_pengurang->get_by_id($id);

        if ($row) {
            $this->M_bayaran_pengurang->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('c_bayaran_pengurang'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_bayaran_pengurang'));
        }
    }

    public function _rules()
    {
	$this->form_validation->set_rules('id_tahun_ajaran', 'id tahun ajaran', 'trim|required');
	$this->form_validation->set_rules('id_kelas', 'id kelas', 'trim|required');
	$this->form_validation->set_rules('id_siswa', 'id siswa', 'trim|required');
	$this->form_validation->set_rules('id_pengurang', 'id pengurang', 'trim|required');
	$this->form_validation->set_rules('kode_bayaran_siswa', 'kode bayaran siswa', 'trim|required');
	$this->form_validation->set_rules('nama_bayaran_siswa', 'nama bayaran siswa', 'trim|required');
	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
	$this->form_validation->set_rules('tgl_ins', 'tgl ins', 'trim|required');
	$this->form_validation->set_rules('tgl_updt', 'tgl updt', 'trim|required');
	$this->form_validation->set_rules('user_updt', 'user updt', 'trim|required');

	$this->form_validation->set_rules('id_bayaran_siswa', 'id_bayaran_siswa', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_bayaran_pengurang.php */
/* Location: ./application/controllers/C_bayaran_pengurang.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-22 06:49:40 */
/* http://harviacode.com */
