<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_kat_uang_keluar extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_kat_uang_keluar');
        $this->load->library('form_validation');
	$this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
          $this->load->view('admin/page/c_kat_uang_keluar/uang_keluar.html');
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_kat_uang_keluar->json();
    }

    public function read($id)
    {
        $row = $this->M_kat_uang_keluar->get_by_id($id);
        if ($row) {
            $data = array(
		'id_kat_uang_keluar' => $row->id_kat_uang_keluar,
		'id_kat_induk' => $row->id_kat_induk,
		'kode_kat_uang_kelaur' => $row->kode_kat_uang_kelaur,
		'nama_kat_uang_keluar' => $row->nama_kat_uang_keluar,
		'keterangan' => $row->keterangan,
		'tgl_ins' => $row->tgl_ins,
		'tgl_updt' => $row->tgl_updt,
		'user_updt' => $row->user_updt,
	    );
            $this->load->view('c_kat_uang_keluar/tb_kat_uang_keluar_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('c_kat_uang_keluar'));
        }
    }

    public function create()
    {
        $list_kat = $this->M_kat_uang_keluar->get_all();

        $data = array(
            'button' => 'Create',
            'action' => site_url('c_kat_uang_keluar/create_action'),
      	    'id_kat_uang_keluar' => set_value('id_kat_uang_keluar'),
      	    'id_kat_induk' => set_value('id_kat_induk'),
      	    'kode_kat_uang_kelaur' => set_value('kode_kat_uang_kelaur'),
      	    'nama_kat_uang_keluar' => set_value('nama_kat_uang_keluar'),
      	    'keterangan' => set_value('keterangan'),
            'list_kat' => $list_kat,
            'c_kat_uang_keluar' => '',
      	);
        $this->load->view('admin/page/c_kat_uang_keluar/input_uang_keluar.html', $data);
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
          		'kode_kat_uang_kelaur' => $this->input->post('kode_kat_uang_kelaur',TRUE),
          		'nama_kat_uang_keluar' => $this->input->post('nama_kat_uang_keluar',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_ins' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_kat_uang_keluar->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('kat-uang-keluar'));
        }
    }

    public function update($id)
    {
        $row = $this->M_kat_uang_keluar->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_kat_uang_keluar/update_action'),
            		'id_kat_uang_keluar' => set_value('id_kat_uang_keluar', $row->id_kat_uang_keluar),
            		'id_kat_induk' => set_value('id_kat_induk', $row->id_kat_induk),
            		'kode_kat_uang_kelaur' => set_value('kode_kat_uang_kelaur', $row->kode_kat_uang_kelaur),
            		'nama_kat_uang_keluar' => set_value('nama_kat_uang_keluar', $row->nama_kat_uang_keluar),
            		'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('admin/page/c_kat_uang_keluar/input_uang_keluar.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('kat-uang-keluar'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_kat_uang_keluar', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'id_kat_induk' => $this->input->post('id_kat_induk',TRUE),
          		'kode_kat_uang_kelaur' => $this->input->post('kode_kat_uang_kelaur',TRUE),
          		'nama_kat_uang_keluar' => $this->input->post('nama_kat_uang_keluar',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_kat_uang_keluar->update($this->input->post('id_kat_uang_keluar', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('kat-uang-keluar'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_kat_uang_keluar->get_by_id($id);

        if ($row) {
            $this->M_kat_uang_keluar->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('kat-uang-keluar'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('kat-uang-keluar'));
        }
    }

    public function _rules()
    {
    	//$this->form_validation->set_rules('id_kat_induk', 'id kat induk', 'trim|required');
    	$this->form_validation->set_rules('kode_kat_uang_kelaur', 'kode kat uang kelaur', 'trim|required');
    	$this->form_validation->set_rules('nama_kat_uang_keluar', 'nama kat uang keluar', 'trim|required');
    	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

    	$this->form_validation->set_rules('id_kat_uang_keluar', 'id_kat_uang_keluar', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_kat_uang_keluar.php */
/* Location: ./application/controllers/C_kat_uang_keluar.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-09 16:38:19 */
/* http://harviacode.com */
