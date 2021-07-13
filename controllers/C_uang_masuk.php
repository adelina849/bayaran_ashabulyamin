<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_uang_masuk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_uang_masuk','M_kat_uang_masuk','M_bank'));
        $this->load->library('form_validation');
	$this->load->library('datatables');
    }

    public function index()
    {
      if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
      {
        header('Location: '.base_url().'login');
      } else {
        $this->load->view('admin/page/c_uang_masuk/uang_masuk.html');
      }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_uang_masuk->json();
    }

    public function read($id)
    {
        $row = $this->M_uang_masuk->get_by_id($id);
        $bank = $this->M_bank->get_by_id($row->id_bank);
        if ($row) {
            $data = array(
          		'id_uang_masuk' => $row->id_uang_masuk,
          		'id_kat_uang_masuk' => $row->id_kat_uang_masuk,
              'id_bank' => $row->id_bank,
          		'no_bukti' => $row->no_bukti,
          		'nama_uang_masuk' => $row->nama_uang_masuk,
          		'terima_dari' => $row->terima_dari,
          		'diterima_oleh' => $row->diterima_oleh,
          		'untuk' => $row->untuk,
          		'nominal' => $row->nominal,
          		'ket_uang_masuk' => $row->ket_uang_masuk,
          		'tgl_uang_masuk' => $row->tgl_uang_masuk,
              'bank' => $bank,
          	);
            $this->load->view('admin/page/c_uang_masuk/view_uang_masuk.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('3-uang-masuk'));
        }
    }

    public function create()
    {
        $list_kat = $this->M_kat_uang_masuk->get_all();
        $list_bank = $this->M_bank->get_all();

        $data = array(
            'button' => 'Create',
            'action' => site_url('C_uang_masuk/create_action'),
      	    'id_uang_masuk' => set_value('id_uang_masuk'),
      	    'id_kat_uang_masuk' => set_value('id_kat_uang_masuk'),
      	    'no_bukti' => set_value('no_bukti'),
      	    'nama_uang_masuk' => set_value('nama_uang_masuk'),
      	    'terima_dari' => set_value('terima_dari'),
      	    'diterima_oleh' => set_value('diterima_oleh'),
      	    'untuk' => set_value('untuk'),
      	    'nominal' => set_value('nominal'),
      	    'ket_uang_masuk' => set_value('ket_uang_masuk'),
      	    'tgl_uang_masuk' => set_value('tgl_uang_masuk'),
            'list_kat' => $list_kat,
            'c_kat' => '',
            'list_bank' => $list_bank,
            'c_bank' => '',
	      );
        $this->load->view('admin/page/c_uang_masuk/input_uang_masuk.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d');

            $data = array(
          		'id_kat_uang_masuk' => $this->input->post('id_kat_uang_masuk',TRUE),
          		'no_bukti' => $this->input->post('no_bukti',TRUE),
              'id_bank' => $this->input->post('id_bank',TRUE),
          		'nama_uang_masuk' => $this->input->post('nama_uang_masuk',TRUE),
          		'terima_dari' => $this->input->post('terima_dari',TRUE),
          		'diterima_oleh' => $this->input->post('diterima_oleh',TRUE),
          		'untuk' => $this->input->post('untuk',TRUE),
          		'nominal' => $this->input->post('nominal',TRUE),
          		'ket_uang_masuk' => $this->input->post('ket_uang_masuk',TRUE),
          		'tgl_uang_masuk' => $this->input->post('tgl_uang_masuk',TRUE),
          		'tgl_ins' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_uang_masuk->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('3-uang-masuk'));
        }
    }

    public function update($id)
    {
        $row = $this->M_uang_masuk->get_by_id($id);
        $list_kat = $this->M_kat_uang_masuk->get_all();
        $list_bank = $this->M_bank->get_all();

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_uang_masuk/update_action'),
            		'id_uang_masuk' => set_value('id_uang_masuk', $row->id_uang_masuk),
            		'id_kat_uang_masuk' => set_value('id_kat_uang_masuk', $row->id_kat_uang_masuk),
            		'no_bukti' => set_value('no_bukti', $row->no_bukti),
            		'nama_uang_masuk' => set_value('nama_uang_masuk', $row->nama_uang_masuk),
            		'terima_dari' => set_value('terima_dari', $row->terima_dari),
            		'diterima_oleh' => set_value('diterima_oleh', $row->diterima_oleh),
            		'untuk' => set_value('untuk', $row->untuk),
            		'nominal' => set_value('nominal', $row->nominal),
            		'ket_uang_masuk' => set_value('ket_uang_masuk', $row->ket_uang_masuk),
            		'tgl_uang_masuk' => set_value('tgl_uang_masuk', $row->tgl_uang_masuk),
                'list_kat' => $list_kat,
                'c_kat' => set_value('untuk', $row->id_kat_uang_masuk),
                'list_bank' => $list_bank,
                'c_bank' => set_value('id_bank', $row->id_bank),
            );
            $this->load->view('admin/page/c_uang_masuk/input_uang_masuk.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('3-uang-masuk'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_uang_masuk', TRUE));
        } else {
            $tgl = date('Y-m-d');

            $data = array(
            		'id_kat_uang_masuk' => $this->input->post('id_kat_uang_masuk',TRUE),
                'id_bank' => $this->input->post('id_bank',TRUE),
            		'no_bukti' => $this->input->post('no_bukti',TRUE),
            		'nama_uang_masuk' => $this->input->post('nama_uang_masuk',TRUE),
            		'terima_dari' => $this->input->post('terima_dari',TRUE),
            		'diterima_oleh' => $this->input->post('diterima_oleh',TRUE),
            		'untuk' => $this->input->post('untuk',TRUE),
            		'nominal' => $this->input->post('nominal',TRUE),
            		'ket_uang_masuk' => $this->input->post('ket_uang_masuk',TRUE),
            		'tgl_uang_masuk' => $this->input->post('tgl_uang_masuk',TRUE),
            		'tgl_updt' => $tgl,
            		'user_updt' => $this->input->post('user_updt',TRUE),
            );

            $this->M_uang_masuk->update($this->input->post('id_uang_masuk', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('3-uang-masuk'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_uang_masuk->get_by_id($id);

        if ($row) {
            $this->M_uang_masuk->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('3-uang-masuk'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('3-uang-masuk'));
        }
    }

    public function _rules()
    {
    	$this->form_validation->set_rules('id_kat_uang_masuk', 'id kat uang masuk', 'trim|required');
    	$this->form_validation->set_rules('no_bukti', 'no bukti', 'trim|required');
    	$this->form_validation->set_rules('nama_uang_masuk', 'nama uang masuk', 'trim|required');
    	$this->form_validation->set_rules('terima_dari', 'terima dari', 'trim|required');
    	$this->form_validation->set_rules('diterima_oleh', 'diterima oleh', 'trim|required');
    	$this->form_validation->set_rules('untuk', 'untuk', 'trim|required');
    	$this->form_validation->set_rules('nominal', 'nominal', 'trim|required');
    	//$this->form_validation->set_rules('ket_uang_masuk', 'ket uang masuk', 'trim|required');
    	$this->form_validation->set_rules('tgl_uang_masuk', 'tgl uang masuk', 'trim|required');

    	$this->form_validation->set_rules('id_uang_masuk', 'id_uang_masuk', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_uang_masuk.php */
/* Location: ./application/controllers/C_uang_masuk.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-21 04:56:31 */
/* http://harviacode.com */
