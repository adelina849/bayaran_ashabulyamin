<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_kat_uang_masuk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_kat_uang_masuk');
        $this->load->library('form_validation');
	       $this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
          $this->load->view('admin/page/c_kat_uang_masuk/uang_masuk.html');
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_kat_uang_masuk->json();
    }

    public function create()
    {
        $list_kat = $this->M_kat_uang_masuk->get_all();


        $data = array(
            'button' => 'Create',
            'action' => site_url('c_kat_uang_masuk/create_action'),
      	    'id_kat_uang_masuk' => set_value('id_kat_uang_masuk'),
      	    'id_kat_induk' => set_value('id_kat_induk'),
      	    'kode_kat_uang_masuk' => set_value('kode_kat_uang_masuk'),
      	    'nama_kat_uang_masuk' => set_value('nama_kat_uang_masuk'),
      	    'keterangan' => set_value('keterangan'),
            'list_kat' => $list_kat,
            'c_kat_uang_masuk' => 'X',
	      );
        $this->load->view('admin/page/c_kat_uang_masuk/input_uang_masuk.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'id_kat_induk' => $this->input->post('id_kat_induk',TRUE),
          		'kode_kat_uang_masuk' => $this->input->post('kode_kat_uang_masuk',TRUE),
          		'nama_kat_uang_masuk' => $this->input->post('nama_kat_uang_masuk',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_ins' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
	          );

            $this->M_kat_uang_masuk->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('kat-uang-masuk'));
        }
    }

    public function update($id)
    {
        $row = $this->M_kat_uang_masuk->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_kat_uang_masuk/update_action'),
            		'id_kat_uang_masuk' => set_value('id_kat_uang_masuk', $row->id_kat_uang_masuk),
            		'id_kat_induk' => set_value('id_kat_induk', $row->id_kat_induk),
            		'kode_kat_uang_masuk' => set_value('kode_kat_uang_masuk', $row->kode_kat_uang_masuk),
            		'nama_kat_uang_masuk' => set_value('nama_kat_uang_masuk', $row->nama_kat_uang_masuk),
            		'keterangan' => set_value('keterangan', $row->keterangan),
                'c_kat_uang_masuk' => $row->id_kat_induk,
            );
            $this->load->view('admin/page/c_kat_uang_masuk/input_uang_masuk.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('kat-uang-masuk'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_kat_uang_masuk', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'id_kat_induk' => $this->input->post('id_kat_induk',TRUE),
          		'kode_kat_uang_masuk' => $this->input->post('kode_kat_uang_masuk',TRUE),
          		'nama_kat_uang_masuk' => $this->input->post('nama_kat_uang_masuk',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_kat_uang_masuk->update($this->input->post('id_kat_uang_masuk', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('kat-uang-masuk'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_kat_uang_masuk->get_by_id($id);

        if ($row) {
            $this->M_kat_uang_masuk->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('kat-uang-masuk'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('kat-uang-masuk'));
        }
    }

    public function _rules()
    {
      	//$this->form_validation->set_rules('id_kat_induk', 'id kat induk', 'trim|required');
      	$this->form_validation->set_rules('kode_kat_uang_masuk', 'kode kat uang masuk', 'trim|required');
      	$this->form_validation->set_rules('nama_kat_uang_masuk', 'nama kat uang masuk', 'trim|required');
      	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

      	$this->form_validation->set_rules('id_kat_uang_masuk', 'id_kat_uang_masuk', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_kat_uang_masuk.php */
/* Location: ./application/controllers/C_kat_uang_masuk.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-09 16:38:35 */
/* http://harviacode.com */
