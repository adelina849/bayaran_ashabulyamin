<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_akun extends CI_Controller
{
    function __construct()
    {
         parent::__construct();
         $this->load->model(array('M_akun_model','M_karyawan'));
         $this->load->library('form_validation');
	       $this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('admin/page/c_akun/akun.html');
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_akun_model->json();
    }

    public function read($id)
    {
        $row = $this->M_akun_model->get_by_id($id);
        if ($row) {
            $data = array(
          		'id_akun' => $row->id_akun,
          		'id_karyawan' => $row->id_karyawan,
          		'pertanyaan1' => $row->pertanyaan1,
          		'jawaban1' => $row->jawaban1,
          		'pertanyaan2' => $row->pertanyaan2,
          		'jawaban2' => $row->jawaban2,
          		'username' => $row->username,
          		'pass' => $row->pass,
          		'ket_akun' => $row->ket_akun,
          		'tgl_insert' => $row->tgl_insert,
          		'tgl_updt' => $row->tgl_updt,
          		'user_updt' => $row->user_updt,
          	);
            $this->load->view('admin/page/c_akun/akun.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('akun-setting'));
        }
    }

    public function profile()
    {
      $row = $this->M_karyawan->get_by_id($this->session->userdata('ses_id_karyawan'));
      $row2 = $this->M_akun_model->get_by_id($this->session->userdata('ses_id_akun'));

      $data = array('karyawan'=>$row,
                    'akun'=>$row2,
                    );
      $this->load->view('admin/page/c_akun/profile.html',$data);
    }

    public function create()
    {
        $list_karyawan = $this->M_karyawan->get_all();


        $data = array(
            'button' => 'Create',
            'action' => site_url('c_akun/create_action'),
      	    'id_akun' => set_value('id_akun'),
      	    'id_karyawan' => set_value('id_karyawan'),
      	    'username' => set_value('username'),
      	    'pass' => set_value('pass'),
      	    'ket_akun' => set_value('ket_akun'),
            'list_karyawan' => $list_karyawan,
            'c_karyawan' => '',
      	);
        $this->load->view('admin/page/c_akun/input_akun.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');

            $data = array(
          		'id_karyawan' => $this->input->post('id_karyawan',TRUE),
          		'username' => $this->input->post('username',TRUE),
          		'pass' => md5($this->input->post('pass',TRUE)),
          		'ket_akun' => $this->input->post('ket_akun',TRUE),
          		'tgl_insert' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_akun_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('akun-setting'));
        }
    }

    public function update($id)
    {
        $row = $this->M_akun_model->get_by_id($id);
        $list_karyawan = $this->M_karyawan->get_all();

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_akun/update_action'),
            		'id_akun' => set_value('id_akun', $row->id_akun),
            		'id_karyawan' => set_value('id_karyawan', $row->id_karyawan),
            		'username' => set_value('username', $row->username),
            		'pass' => md5(set_value('pass', $row->pass)),
            		'ket_akun' => set_value('ket_akun', $row->ket_akun),
                'list_karyawan' => $list_karyawan,
                'c_karyawan' => set_value('id_karyawan', $row->id_karyawan),
            );
            $this->load->view('admin/page/c_akun/input_akun.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('akun-setting'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_akun', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');

            $data = array(
          		'id_karyawan' => $this->input->post('id_karyawan',TRUE),
          		'username' => $this->input->post('username',TRUE),
          		'pass' => $this->input->post('pass',TRUE),
          		'ket_akun' => $this->input->post('ket_akun',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_akun_model->update($this->input->post('id_akun', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('akun-setting'));
        }
    }

    public function update_profile()
    {
      $tgl = date('Y-m-d H:i:s');

      $tgl = date('Y-m-d H:i:s');
      $data = array(
        'nama_karyawan' => $this->input->post('nama',TRUE),
        'tlp' => $this->input->post('nohp',TRUE),
        'email' => $this->input->post('email',TRUE),
        'alamat' => $this->input->post('alamat',TRUE),
        'tgl_updt' => $tgl,
        'user_updt' => $this->session->userdata('ses_id_akun'),
      );

      $this->M_karyawan->update($this->input->post('id_karyawan', TRUE), $data);
      $this->session->set_flashdata('message', 'Update Record Success');
      redirect(site_url('profile/'.$this->session->userdata('ses_id_akun')));
    }

    public function update_password()
    {
        $this->_rules2();

        if ($this->form_validation->run() == FALSE) {
            $this->profile();
        } else {
            $tgl = date('Y-m-d H:i:s');

            $data = array(
          		'pass' => md5($this->input->post('password_baru',TRUE)),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_akun_model->update($this->input->post('id_akun', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('profile/'.$this->session->userdata('ses_id_akun')));
        }
    }

    public function delete($id)
    {
        $row = $this->M_akun_model->get_by_id($id);

        if ($row) {
            $this->M_akun_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('akun-setting'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('akun-setting'));
        }
    }

    public function upload_gambar()
    {
      if (!empty($_FILES['foto']['name']))
      {
        $tgl2 = date('Ymd');
        $gen_foto = $tgl2.'-'.$this->input->post('id', TRUE);
        //$gen_foto = "dsdsdsdsfsfsf";
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = $gen_foto.'.'.$ext;

        $this->do_upload($_FILES['foto']['name'],$gen_foto);

        $tgl = date('Y-m-d H:i:s');

        $data = array(
          'avatar' => $foto,
          'tgl_updt' => $tgl,
          'user_updt' => $this->session->userdata('ses_id_akun'),
        );

        $this->M_karyawan->update($this->input->post('id', TRUE), $data);
      }

      redirect(site_url('profile/'.$this->session->userdata('ses_id_akun')));

    }

    public function _rules()
    {
      	$this->form_validation->set_rules('id_karyawan', 'id karyawan', 'trim|required');
      	$this->form_validation->set_rules('username', 'username', 'trim|required');
      	$this->form_validation->set_rules('pass', 'pass', 'trim|required');

      	$this->form_validation->set_rules('id_akun', 'id_akun', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function _rules2()
    {
        $this->form_validation->set_rules('password_baru', 'Password', 'required|min_length[6]|max_length[15]');
        $this->form_validation->set_rules('password_konfirm', 'Password Confirmation', 'required|matches[password_baru]');

      	$this->form_validation->set_rules('id_akun', 'id_akun', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    function do_upload($id,$cek_bfr)
  	{
  		$this->load->library('upload');

  		if($cek_bfr != '')
  		{
  			@unlink('./assets/images/member/'.$cek_bfr);
  		}

  		if (!empty($_FILES['foto']['name']))
  		{
  			$config['upload_path'] = 'assets/images/member/';
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

/* End of file C_akun.php */
/* Location: ./application/controllers/C_akun.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-09-08 11:38:23 */
/* http://harviacode.com */
