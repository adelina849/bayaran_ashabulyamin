<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_bayaran extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_bayaran','M_kat_bayaran'));
        $this->load->library('form_validation');
	      $this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
          $this->load->view('admin/page/c_bayaran/bayaran.html');
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_bayaran->json();
    }

    /*public function read($id)
    {
        $row = $this->M_bayaran->get_by_id($id);
        if ($row) {
            $data = array(
          		'id_bayaran' => $row->id_bayaran,
          		'kode_bayaran' => $row->kode_bayaran,
          		'nama_bayaran' => $row->nama_bayaran,
          		'nominal' => $row->nominal,
          		'keterangan' => $row->keterangan,
          		'tgl_ins' => $row->tgl_ins,
          		'tgl_updt' => $row->tgl_updt,
          		'user_updt' => $row->user_updt,
        	  );
            $this->load->view('c_bayaran/tb_bayaran_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_bayaran'));
        }
    }*/

    public function create()
    {
        $list_kat_bayaran = $this->M_kat_bayaran->get_all();

        $data = array(
            'button' => 'Create',
            'action' => site_url('c_bayaran/create_action'),
      	    'id_bayaran' => set_value('id_bayaran'),
      	    'kode_bayaran' => set_value('kode_bayaran'),
      	    'nama_bayaran' => set_value('nama_bayaran'),
      	    'nominal' => set_value('nominal'),
      	    'keterangan' => set_value('keterangan'),
            'list_kat_bayaran' => $list_kat_bayaran,
            'c_kat_bayaran' => '',
	      );
        $this->load->view('admin/page/c_bayaran/input_bayaran.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');

            $data = array(
                'id_kat_bayaran' => $this->input->post('kat_bayaran',TRUE),
            		'kode_bayaran' => $this->input->post('kode_bayaran',TRUE),
            		'nama_bayaran' => $this->input->post('nama_bayaran',TRUE),
            		'nominal' => $this->input->post('nominal',TRUE),
            		'keterangan' => $this->input->post('keterangan',TRUE),
            		'tgl_ins' => $tgl,
            		'user_updt' => $this->session->userdata('ses_id_akun'),
	          );

            $this->M_bayaran->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('bayaran'));
        }
    }

    public function update($id)
    {
        $row = $this->M_bayaran->get_by_id($id);

        if ($row) {
            $list_kat_bayaran = $this->M_kat_bayaran->get_all();

            $data = array(
                'button' => 'Update',
                'action' => site_url('c_bayaran/update_action'),
            		'id_bayaran' => set_value('id_bayaran', $row->id_bayaran),
            		'kode_bayaran' => set_value('kode_bayaran', $row->kode_bayaran),
            		'nama_bayaran' => set_value('nama_bayaran', $row->nama_bayaran),
            		'nominal' => set_value('nominal', $row->nominal),
            		'keterangan' => set_value('keterangan', $row->keterangan),
                'list_kat_bayaran' => $list_kat_bayaran,
                'c_kat_bayaran' => set_value('id_kat_bayaran', $row->id_kat_bayaran),
            );
            $this->load->view('admin/page/c_bayaran/input_bayaran.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bayaran'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_bayaran', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'kode_bayaran' => $this->input->post('kode_bayaran',TRUE),
          		'nama_bayaran' => $this->input->post('nama_bayaran',TRUE),
          		'nominal' => $this->input->post('nominal',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
              'id_kat_bayaran' => $this->input->post('kat_bayaran',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_bayaran->update($this->input->post('id_bayaran', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('bayaran'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_bayaran->get_by_id($id);

        if ($row) {
            $this->M_bayaran->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('bayaran'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bayaran'));
        }
    }

    public function _rules()
    {
      	$this->form_validation->set_rules('kode_bayaran', 'kode bayaran', 'trim|required');
      	$this->form_validation->set_rules('nama_bayaran', 'nama bayaran', 'trim|required');
      	$this->form_validation->set_rules('nominal', 'nominal', 'trim|required');
      	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

      	$this->form_validation->set_rules('id_bayaran', 'id_bayaran', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_bayaran.php */
/* Location: ./application/controllers/C_bayaran.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-09 02:21:46 */
/* http://harviacode.com */
