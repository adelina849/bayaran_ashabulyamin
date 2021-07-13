<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_proyek extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_proyek');
        $this->load->library('form_validation');
	$this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
          $this->load->view('admin/page/c_proyek/proyek.html');
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_proyek->json();
    }

    public function read($id)
    {
        $row = $this->M_proyek->get_by_id($id);
        if ($row) {
            $data = array(
          		'id_proyek' => $row->id_proyek,
          		'kode_proyek' => $row->kode_proyek,
          		'nama_proyek' => $row->nama_proyek,
          		'penanggung_jawab' => $row->penanggung_jawab,
          		'tgl_mulai' => $row->tgl_mulai,
          		'tgl_selesai' => $row->tgl_selesai,
          		'nominal' => $row->nominal,
          		'keterangan' => $row->keterangan,
          	);
            $this->load->view('admin/page/c_proyek/view_proyek.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('proyek'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('c_proyek/create_action'),
      	    'id_proyek' => set_value('id_proyek'),
      	    'kode_proyek' => set_value('kode_proyek'),
      	    'nama_proyek' => set_value('nama_proyek'),
      	    'penanggung_jawab' => set_value('penanggung_jawab'),
      	    'tgl_mulai' => set_value('tgl_mulai'),
      	    'tgl_selesai' => set_value('tgl_selesai'),
      	    'nominal' => set_value('nominal'),
      	    'keterangan' => set_value('keterangan'),
      	);
        $this->load->view('admin/page/c_proyek/input_proyek.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'kode_proyek' => $this->input->post('kode_proyek',TRUE),
          		'nama_proyek' => $this->input->post('nama_proyek',TRUE),
          		'penanggung_jawab' => $this->input->post('penanggung_jawab',TRUE),
          		'tgl_mulai' => $this->input->post('tgl_mulai',TRUE),
          		'tgl_selesai' => $this->input->post('tgl_selesai',TRUE),
          		'nominal' => $this->input->post('nominal',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_ins' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_proyek->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('proyek'));
        }
    }

    public function update($id)
    {
        $row = $this->M_proyek->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_proyek/update_action'),
            		'id_proyek' => set_value('id_proyek', $row->id_proyek),
            		'kode_proyek' => set_value('kode_proyek', $row->kode_proyek),
            		'nama_proyek' => set_value('nama_proyek', $row->nama_proyek),
            		'penanggung_jawab' => set_value('penanggung_jawab', $row->penanggung_jawab),
            		'tgl_mulai' => set_value('tgl_mulai', $row->tgl_mulai),
            		'tgl_selesai' => set_value('tgl_selesai', $row->tgl_selesai),
            		'nominal' => set_value('nominal', $row->nominal),
            		'keterangan' => set_value('keterangan', $row->keterangan),
	          );
            $this->load->view('admin/page/c_proyek/input_proyek.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('proyek'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_proyek', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'kode_proyek' => $this->input->post('kode_proyek',TRUE),
          		'nama_proyek' => $this->input->post('nama_proyek',TRUE),
          		'penanggung_jawab' => $this->input->post('penanggung_jawab',TRUE),
          		'tgl_mulai' => $this->input->post('tgl_mulai',TRUE),
          		'tgl_selesai' => $this->input->post('tgl_selesai',TRUE),
          		'nominal' => $this->input->post('nominal',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
	          );

            $this->M_proyek->update($this->input->post('id_proyek', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('c_proyek'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_proyek->get_by_id($id);

        if ($row) {
            $this->M_proyek->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('proyek'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('proyek'));
        }
    }

    public function _rules()
    {
    	$this->form_validation->set_rules('kode_proyek', 'kode proyek', 'trim|required');
    	$this->form_validation->set_rules('nama_proyek', 'nama proyek', 'trim|required');
    	$this->form_validation->set_rules('penanggung_jawab', 'penanggung jawab', 'trim|required');
    	$this->form_validation->set_rules('tgl_mulai', 'tgl mulai', 'trim|required');
    	$this->form_validation->set_rules('tgl_selesai', 'tgl selesai', 'trim|required');
    	$this->form_validation->set_rules('nominal', 'nominal', 'trim|required');
    	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

    	$this->form_validation->set_rules('id_proyek', 'id_proyek', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_proyek.php */
/* Location: ./application/controllers/C_proyek.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-09 02:22:52 */
/* http://harviacode.com */
