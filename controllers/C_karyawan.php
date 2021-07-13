<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_karyawan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_karyawan','M_jabatan'));
        $this->load->library('form_validation');
	$this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
          $this->load->view('admin/page/c_karyawan/karyawan.html');
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_karyawan->json();
    }

    public function read($id)
    {
        $row = $this->M_karyawan->get_by_id($id);
        if ($row) {
            $data = array(
          		'id_karyawan' => $row->id_karyawan,
          		'id_jabatan' => $row->id_jabatan,
          		'no_karyawan' => $row->no_karyawan,
          		'nik_karyawan' => $row->nik_karyawan,
          		'nama_karyawan' => $row->nama_karyawan,
          		'pnd' => $row->pnd,
          		'tlp' => $row->tlp,
          		'email' => $row->email,
          		'avatar' => $row->avatar,
          		'avatar_url' => $row->avatar_url,
          		'alamat' => $row->alamat,
          		'ket_karyawan' => $row->ket_karyawan,
	          );
            $this->load->view('admin/page/c_karyawan/view_karyawan.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('karyawan'));
        }
    }

    public function create()
    {
        $list_jabatan = $this->M_jabatan->get_all();

        $no_karyawan = $this->M_karyawan->get_no_karyawan()->no_member;

        $data = array(
            'button' => 'Create',
            'action' => site_url('c_karyawan/create_action'),
      	    'id_karyawan' => set_value('id_karyawan'),
      	    'id_jabatan' => set_value('id_jabatan'),
      	    'nik_karyawan' => set_value('nik_karyawan'),
      	    'nama_karyawan' => set_value('nama_karyawan'),
      	    'pnd' => set_value('pnd'),
      	    'tlp' => set_value('tlp'),
      	    'email' => set_value('email'),
      	    'alamat' => set_value('alamat'),
      	    'ket_karyawan' => set_value('ket_karyawan'),
            'list_jabatan' => $list_jabatan,
            'c_jabatan' => '',
            'no_karyawan' => $no_karyawan,
      	);
        $this->load->view('admin/page/c_karyawan/input_karyawan.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'id_jabatan' => $this->input->post('id_jabatan',TRUE),
          		'no_karyawan' => $this->input->post('no_karyawan',TRUE),
          		'nik_karyawan' => $this->input->post('nik_karyawan',TRUE),
          		'nama_karyawan' => $this->input->post('nama_karyawan',TRUE),
          		'pnd' => $this->input->post('pnd',TRUE),
          		'tlp' => $this->input->post('tlp',TRUE),
          		'email' => $this->input->post('email',TRUE),
          		'alamat' => $this->input->post('alamat',TRUE),
          		'ket_karyawan' => $this->input->post('ket_karyawan',TRUE),
          		'tgl_ins' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_karyawan->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('karyawan'));
        }
    }

    public function update($id)
    {
        $row = $this->M_karyawan->get_by_id($id);
        $list_jabatan = $this->M_jabatan->get_all();

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_karyawan/update_action'),
            		'id_karyawan' => set_value('id_karyawan', $row->id_karyawan),
            		'id_jabatan' => set_value('id_jabatan', $row->id_jabatan),
            		'no_karyawan' => set_value('no_karyawan', $row->no_karyawan),
            		'nik_karyawan' => set_value('nik_karyawan', $row->nik_karyawan),
            		'nama_karyawan' => set_value('nama_karyawan', $row->nama_karyawan),
            		'pnd' => set_value('pnd', $row->pnd),
            		'tlp' => set_value('tlp', $row->tlp),
            		'email' => set_value('email', $row->email),
            	//	'avatar' => set_value('avatar', $row->avatar),
            	//	'avatar_url' => set_value('avatar_url', $row->avatar_url),
            		'alamat' => set_value('alamat', $row->alamat),
            		'ket_karyawan' => set_value('ket_karyawan', $row->ket_karyawan),
                'list_jabatan' => $list_jabatan,
                'c_jabatan' => $this->input->post('id_jabatan',TRUE),
	          );
            $this->load->view('admin/page/c_karyawan/input_karyawan.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('karyawan'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_karyawan', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'id_jabatan' => $this->input->post('id_jabatan',TRUE),
          		'no_karyawan' => $this->input->post('no_karyawan',TRUE),
          		'nik_karyawan' => $this->input->post('nik_karyawan',TRUE),
          		'nama_karyawan' => $this->input->post('nama_karyawan',TRUE),
          		'pnd' => $this->input->post('pnd',TRUE),
          		'tlp' => $this->input->post('tlp',TRUE),
          		'email' => $this->input->post('email',TRUE),
          	//	'avatar' => $this->input->post('avatar',TRUE),
          	//	'avatar_url' => $this->input->post('avatar_url',TRUE),
          		'alamat' => $this->input->post('alamat',TRUE),
          		'ket_karyawan' => $this->input->post('ket_karyawan',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_karyawan->update($this->input->post('id_karyawan', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('karyawan'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_karyawan->get_by_id($id);

        if ($row) {
            $this->M_karyawan->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('karyawan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('karyawan'));
        }
    }

    public function _rules()
    {
      	$this->form_validation->set_rules('id_jabatan', 'id jabatan', 'trim|required');
      	$this->form_validation->set_rules('no_karyawan', 'no karyawan', 'trim|required');
      	$this->form_validation->set_rules('nik_karyawan', 'nik karyawan', 'trim|required');
      	$this->form_validation->set_rules('nama_karyawan', 'nama karyawan', 'trim|required');
      	$this->form_validation->set_rules('pnd', 'pnd', 'trim|required');
      	$this->form_validation->set_rules('tlp', 'tlp', 'trim|required');
      	$this->form_validation->set_rules('email', 'email', 'trim|required');
      	//$this->form_validation->set_rules('avatar', 'avatar', 'trim|required');
      	//$this->form_validation->set_rules('avatar_url', 'avatar url', 'trim|required');
      	//$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
      	//$this->form_validation->set_rules('ket_karyawan', 'ket karyawan', 'trim|required');

      	$this->form_validation->set_rules('id_karyawan', 'id_karyawan', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_karyawan.php */
/* Location: ./application/controllers/C_karyawan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-09 04:51:15 */
/* http://harviacode.com */
