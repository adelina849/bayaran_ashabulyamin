<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_bank extends CI_Controller
{
    function __construct()
    {
         parent::__construct();
         $this->load->model('M_bank');
         $this->load->library('form_validation');
	       $this->load->library('datatables');
    }

    public function index()
    {
      if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
      {
        header('Location: '.base_url().'login');
      } else {
        $this->load->view('admin/page/c_bank/bank.html');
      }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_bank->json();
    }

    public function read($id)
    {
        $row = $this->M_bank->get_by_id($id);
        if ($row) {
            $data = array(
          		'id_bank' => $row->id_bank,
          		'norek' => $row->norek,
          		'nama_bank' => $row->nama_bank,
          		'atas_nama' => $row->atas_nama,
          		'cabang' => $row->cabang,
          		'tgl_pembuatan' => $row->tgl_pembuatan,
          		'ket_bank' => $row->ket_bank,
          	);
            $this->load->view('admin/page/c_bank/view_bank.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bank'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('c_bank/create_action'),
      	    'id_bank' => set_value('id_bank'),
      	    'norek' => set_value('norek'),
      	    'nama_bank' => set_value('nama_bank'),
      	    'atas_nama' => set_value('atas_nama'),
      	    'cabang' => set_value('cabang'),
      	    'tgl_pembuatan' => set_value('tgl_pembuatan'),
      	    'ket_bank' => set_value('ket_bank'),
      	);
        $this->load->view('admin/page/c_bank/input_bank.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');

            $data = array(
          		'norek' => $this->input->post('norek',TRUE),
          		'nama_bank' => $this->input->post('nama_bank',TRUE),
          		'atas_nama' => $this->input->post('atas_nama',TRUE),
          		'cabang' => $this->input->post('cabang',TRUE),
          		'tgl_pembuatan' => $this->input->post('tgl_pembuatan',TRUE),
          		'ket_bank' => $this->input->post('ket_bank',TRUE),
          		'tgl_ins' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_bank->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('bank'));
        }
    }

    public function update($id)
    {
        $row = $this->M_bank->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_bank/update_action'),
            		'id_bank' => set_value('id_bank', $row->id_bank),
            		'norek' => set_value('norek', $row->norek),
            		'nama_bank' => set_value('nama_bank', $row->nama_bank),
            		'atas_nama' => set_value('atas_nama', $row->atas_nama),
            		'cabang' => set_value('cabang', $row->cabang),
            		'tgl_pembuatan' => set_value('tgl_pembuatan', $row->tgl_pembuatan),
            		'ket_bank' => set_value('ket_bank', $row->ket_bank),
            );
            $this->load->view('admin/page/c_bank/input_bank.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bank'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_bank', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'norek' => $this->input->post('norek',TRUE),
          		'nama_bank' => $this->input->post('nama_bank',TRUE),
          		'atas_nama' => $this->input->post('atas_nama',TRUE),
          		'cabang' => $this->input->post('cabang',TRUE),
          		'tgl_pembuatan' => $this->input->post('tgl_pembuatan',TRUE),
          		'ket_bank' => $this->input->post('ket_bank',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_bank->update($this->input->post('id_bank', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('bank'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_bank->get_by_id($id);

        if ($row) {
            $this->M_bank->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('bank'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bank'));
        }
    }

    public function _rules()
    {
      	$this->form_validation->set_rules('norek', 'norek', 'trim|required');
      	$this->form_validation->set_rules('nama_bank', 'nama bank', 'trim|required');
      	$this->form_validation->set_rules('atas_nama', 'atas nama', 'trim|required');
      	$this->form_validation->set_rules('cabang', 'cabang', 'trim|required');
      	$this->form_validation->set_rules('tgl_pembuatan', 'tgl pembuatan', 'trim|required');
      	$this->form_validation->set_rules('ket_bank', 'ket bank', 'trim|required');

      	$this->form_validation->set_rules('id_bank', 'id_bank', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_bank.php */
/* Location: ./application/controllers/C_bank.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-25 05:07:08 */
/* http://harviacode.com */
