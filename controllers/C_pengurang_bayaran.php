<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_pengurang_bayaran extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_pengurang_bayaran','M_kat_bayaran'));
        $this->load->library('form_validation');
	$this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
          $this->load->view('admin/page/c_pengurang_bayaran/pengurang_bayaran.html');
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_pengurang_bayaran->json();
    }

    public function create()
    {
        $list_kat_bayaran = $this->M_kat_bayaran->get_all();

        $data = array(
            'button' => 'Create',
            'action' => site_url('c_pengurang_bayaran/create_action'),
      	    'id_pengurang' => set_value('id_pengurang'),
      	    'kode_pengurang' => set_value('kode_pengurang'),
      	    'nama_pengurang' => set_value('nama_pengurang'),
      	    'nominal' => set_value('nominal'),
      	    'keterangan' => set_value('keterangan'),
            'c_jenis' => '',
            'list_kat_bayaran' => $list_kat_bayaran,
            'c_kat_bayaran' => '',
      	);
        $this->load->view('admin/page/c_pengurang_bayaran/input_pengurang_bayaran.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'kode_pengurang' => $this->input->post('kode_pengurang',TRUE),
          		'nama_pengurang' => $this->input->post('nama_pengurang',TRUE),
          		'nominal' => $this->input->post('nominal',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
              'jenis_pengurang' => $this->input->post('jenis_pengurang',TRUE),
              'id_kat_bayaran' => $this->input->post('id_kat_bayaran',TRUE),
          		'tgl_ins' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
      	    );

            $this->M_pengurang_bayaran->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('pengurang-bayaran'));
        }
    }

    public function update($id)
    {
        $row = $this->M_pengurang_bayaran->get_by_id($id);
        $list_kat_bayaran = $this->M_kat_bayaran->get_all();

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_pengurang_bayaran/update_action'),
            		'id_pengurang' => set_value('id_pengurang', $row->id_pengurang),
            		'kode_pengurang' => set_value('kode_pengurang', $row->kode_pengurang),
            		'nama_pengurang' => set_value('nama_pengurang', $row->nama_pengurang),
                'id_kat_bayaran' => set_value('id_kat_bayaran', $row->id_kat_bayaran),
            		'nominal' => set_value('nominal', $row->nominal),
            		'keterangan' => set_value('keterangan', $row->keterangan),
                'c_jenis' => set_value('jenis_pengurang', $row->jenis_pengurang),
                'list_kat_bayaran' => $list_kat_bayaran,
                'c_kat_bayaran' => set_value('id_kat_bayaran', $row->id_kat_bayaran),
            );
            $this->load->view('admin/page/c_pengurang_bayaran/input_pengurang_bayaran.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pengurang-bayaran'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_pengurang', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'kode_pengurang' => $this->input->post('kode_pengurang',TRUE),
          		'nama_pengurang' => $this->input->post('nama_pengurang',TRUE),
          		'nominal' => $this->input->post('nominal',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
              'jenis_pengurang' => $this->input->post('jenis_pengurang',TRUE),
              'id_kat_bayaran' => $this->input->post('id_kat_bayaran',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_pengurang_bayaran->update($this->input->post('id_pengurang', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('pengurang-bayaran'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_pengurang_bayaran->get_by_id($id);

        if ($row) {
            $this->M_pengurang_bayaran->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('pengurang-bayaran'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('pengurang-bayaran'));
        }
    }

    public function _rules()
    {
      	$this->form_validation->set_rules('kode_pengurang', 'kode pengurang', 'trim|required');
      	$this->form_validation->set_rules('nama_pengurang', 'nama pengurang', 'trim|required');
      	$this->form_validation->set_rules('nominal', 'nominal', 'trim|required');
      	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

      	$this->form_validation->set_rules('id_pengurang', 'id_pengurang', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_pengurang_bayaran.php */
/* Location: ./application/controllers/C_pengurang_bayaran.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-09 05:58:17 */
/* http://harviacode.com */
