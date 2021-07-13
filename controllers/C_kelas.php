<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_kelas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_kelas');
        $this->load->library('form_validation');
	       $this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
          $this->load->view('admin/page/ci_kelas/kelas.html');
        }
        //$this->load->view('admin/page/ci_kelas/tb_kelas_list');
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_kelas->json();
    }

    public function input()
    {
      $data = array(
          'button' => 'Create',
          'action' => site_url('c_kelas/create_action'),
          'id_kelas' => set_value('id_kelas'),
          'kode_kelas' => set_value('kode_kelas'),
          'nama_kelas' => set_value('nama_kelas'),
          'keterangan' => set_value('keterangan'),
      );
      $this->load->view('admin/page/ci_kelas/input-kelas.html',$data);
    }

    public function read($id)
    {
        $row = $this->M_kelas->get_by_id($id);
        if ($row) {
            $data = array(
        		'id_kelas' => $row->id_kelas,
        		'kode_kelas' => $row->kode_kelas,
        		'nama_kelas' => $row->nama_kelas,
        		'keterangan' => $row->keterangan
	         );
            $this->load->view('c_kelas/tb_kelas_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('kelas'));
        }
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->input();
        } else {

            $tgl = date('Y-m-d H:i:s');

            $data = array(
            		'kode_kelas' => $this->input->post('kode_kelas',TRUE),
            		'nama_kelas' => $this->input->post('nama_kelas',TRUE),
            		'keterangan' => $this->input->post('keterangan',TRUE),
            		'tgl_ins' => $tgl,
            		'tgl_updt' => $tgl,
            		'user_updt' => $this->session->userdata('ses_id_akun'),
      	    );

            $this->M_kelas->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('kelas'));
        }
    }

    public function update($id)
    {
        $row = $this->M_kelas->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_kelas/update_action'),
            		'id_kelas' => set_value('id_kelas', $row->id_kelas),
            		'kode_kelas' => set_value('kode_kelas', $row->kode_kelas),
            		'nama_kelas' => set_value('nama_kelas', $row->nama_kelas),
            		'keterangan' => set_value('keterangan', $row->keterangan),
	    );
            $this->load->view('admin/page/ci_kelas/input-kelas.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('kelas'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_kelas', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'kode_kelas' => $this->input->post('kode_kelas',TRUE),
          		'nama_kelas' => $this->input->post('nama_kelas',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
              'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
	    );

            $this->M_kelas->update($this->input->post('id_kelas', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('kelas'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_kelas->get_by_id($id);

        if ($row) {
            $this->M_kelas->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('kelas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('kelas'));
        }
    }

    public function _rules()
    {
    	$this->form_validation->set_rules('kode_kelas', 'kode kelas', 'trim|required|is_unique[tb_kelas.kode_kelas]');
    	$this->form_validation->set_rules('nama_kelas', 'nama kelas', 'trim|required');
    	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');


    	$this->form_validation->set_rules('id_kelas', 'id_kelas', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file CI_kelas.php */
/* Location: ./application/controllers/CI_kelas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-06-29 07:12:26 */
/* http://harviacode.com */
