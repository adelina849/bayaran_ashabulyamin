<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_uang_keluar extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_uang_keluar_model','M_kat_uang_keluar','M_proyek','M_bank'));
        $this->load->library('form_validation');
	      $this->load->library('datatables');
    }

    public function index()
    {
      if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
      {
        header('Location: '.base_url().'login');
      } else {
        $this->load->view('admin/page/c_uang_keluar/uang_keluar.html');
      }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_uang_keluar_model->json();
    }

    public function read($id)
    {
        $row = $this->M_uang_keluar_model->get_by_id($id);
        $kat_uang_keluar = $this->M_kat_uang_keluar->get_by_id($row->id_kat_uang_keluar);
        $proyek = $this->M_proyek->get_by_id($row->id_proyek);
        $bank = $this->M_bank->get_by_id($row->id_bank);

        if ($row) {
            $data = array(
          		'id_uang_keluar' => $row->id_uang_keluar,
          		'id_kat_uang_keluar' => $row->id_kat_uang_keluar,
          		'id_proyek' => $row->id_proyek,
              'id_bank' => $row->id_bank,
          		'no_uang_keluar' => $row->no_uang_keluar,
          		'nama_uang_keluar' => $row->nama_uang_keluar,
          		'diberikan_kepada' => $row->diberikan_kepada,
          		'diberikan_oleh' => $row->diberikan_oleh,
          		'untuk' => $row->untuk,
          		'nominal' => $row->nominal,
          		'jenis' => $row->jenis,
          		'ket_uang_keluar' => $row->ket_uang_keluar,
          		'tgl_dikeluarkan' => $row->tgl_dikeluarkan,
          		'tgl_diterima' => $row->tgl_diterima,
              'kat_uang_keluar' => $kat_uang_keluar,
              'proyek' => $proyek,
              'bank' => $bank,
          	);
            $this->load->view('admin/page/c_uang_keluar/view_uang_keluar.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('3-uang-keluar'));
        }
    }

    public function create()
    {
        $list_kat = $this->M_kat_uang_keluar->get_all();
        $list_bank = $this->M_bank->get_all();

        $data = array(
            'button' => 'Create',
            'action' => site_url('c_uang_keluar/create_action'),
      	    'id_uang_keluar' => set_value('id_uang_keluar'),
      	    'id_kat_uang_keluar' => set_value('id_kat_uang_keluar'),
      	    'id_proyek' => set_value('id_proyek'),
      	    'no_uang_keluar' => set_value('no_uang_keluar'),
      	    'nama_uang_keluar' => set_value('nama_uang_keluar'),
      	    'diberikan_kepada' => set_value('diberikan_kepada'),
      	    'diberikan_oleh' => set_value('diberikan_oleh'),
      	    'untuk' => set_value('untuk'),
      	    'nominal' => set_value('nominal'),
      	    'jenis' => set_value('jenis'),
      	    'ket_uang_keluar' => set_value('ket_uang_keluar'),
      	    'tgl_dikeluarkan' => set_value('tgl_dikeluarkan'),
      	    'tgl_diterima' => set_value('tgl_diterima'),
            'list_kat' => $list_kat,
            'c_kat' => '',
            'list_bank' => $list_bank,
            'c_bank' => '',
            'c_proyek' => '',
      	);
        $this->load->view('admin/page/c_uang_keluar/input_uang_keluar.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d');
            $data = array(
          		'id_kat_uang_keluar' => $this->input->post('id_kat_uang_keluar',TRUE),
          		'id_proyek' => $this->input->post('id_proyek',TRUE),
              'id_bank' => $this->input->post('id_bank',TRUE),
          		'no_uang_keluar' => $this->input->post('no_uang_keluar',TRUE),
          		'nama_uang_keluar' => $this->input->post('nama_uang_keluar',TRUE),
          		'diberikan_kepada' => $this->input->post('diberikan_kepada',TRUE),
          		'diberikan_oleh' => $this->input->post('diberikan_oleh',TRUE),
          		'untuk' => $this->input->post('untuk',TRUE),
          		'nominal' => $this->input->post('nominal',TRUE),
          		'jenis' => $this->input->post('jenis',TRUE),
          		'ket_uang_keluar' => $this->input->post('ket_uang_keluar',TRUE),
          		'tgl_dikeluarkan' => $this->input->post('tgl_dikeluarkan',TRUE),
          		'tgl_diterima' => $this->input->post('tgl_diterima',TRUE),
          		'tgl_ins' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_uang_keluar_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('3-uang-keluar'));
        }
    }

    public function update($id)
    {
        $row = $this->M_uang_keluar_model->get_by_id($id);
        $list_kat = $this->M_kat_uang_keluar->get_all();
        $list_bank = $this->M_bank->get_all();
        $proyek = $this->M_proyek->get_by_id($row->id_proyek);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_uang_keluar/update_action'),
            		'id_uang_keluar' => set_value('id_uang_keluar', $row->id_uang_keluar),
            		'id_kat_uang_keluar' => set_value('id_kat_uang_keluar', $row->id_kat_uang_keluar),
            		'id_proyek' => set_value('id_proyek', $row->id_proyek),
            		'no_uang_keluar' => set_value('no_uang_keluar', $row->no_uang_keluar),
            		'nama_uang_keluar' => set_value('nama_uang_keluar', $row->nama_uang_keluar),
            		'diberikan_kepada' => set_value('diberikan_kepada', $row->diberikan_kepada),
            		'diberikan_oleh' => set_value('diberikan_oleh', $row->diberikan_oleh),
            		'untuk' => set_value('untuk', $row->untuk),
            		'nominal' => set_value('nominal', $row->nominal),
            		'jenis' => set_value('jenis', $row->jenis),
            		'ket_uang_keluar' => set_value('ket_uang_keluar', $row->ket_uang_keluar),
            		'tgl_dikeluarkan' => set_value('tgl_dikeluarkan', $row->tgl_dikeluarkan),
            		'tgl_diterima' => set_value('tgl_diterima', $row->tgl_diterima),
                'list_kat' => $list_kat,
                'c_kat' => set_value('id_kat_uang_keluar', $row->id_kat_uang_keluar),
                'list_bank' => $list_bank,
                'c_bank' => set_value('id_bank', $row->id_bank),
                'c_proyek' => $proyek->nama_proyek,
            );
            $this->load->view('admin/page/c_uang_keluar/input_uang_keluar.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('3-uang-keluar'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_uang_keluar', TRUE));
        } else {
            $tgl = date('Y-m-d');
            $data = array(
          		'id_kat_uang_keluar' => $this->input->post('id_kat_uang_keluar',TRUE),
          		'id_proyek' => $this->input->post('id_proyek',TRUE),
              'id_bank' => $this->input->post('id_bank',TRUE),
          		'no_uang_keluar' => $this->input->post('no_uang_keluar',TRUE),
          		'nama_uang_keluar' => $this->input->post('nama_uang_keluar',TRUE),
          		'diberikan_kepada' => $this->input->post('diberikan_kepada',TRUE),
          		'diberikan_oleh' => $this->input->post('diberikan_oleh',TRUE),
          		'untuk' => $this->input->post('untuk',TRUE),
          		'nominal' => $this->input->post('nominal',TRUE),
          		'jenis' => $this->input->post('jenis',TRUE),
          		'ket_uang_keluar' => $this->input->post('ket_uang_keluar',TRUE),
          		'tgl_dikeluarkan' => $this->input->post('tgl_dikeluarkan',TRUE),
          		'tgl_diterima' => $this->input->post('tgl_diterima',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
          	);

            $this->M_uang_keluar_model->update($this->input->post('id_uang_keluar', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('3-uang-keluar'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_uang_keluar_model->get_by_id($id);

        if ($row) {
            $this->M_uang_keluar_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('3-uang-keluar'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('3-uang-keluar'));
        }
    }

    public function list_proyek()
    {
      $list_proyek = $this->M_proyek->get_all();
      $no=1;
      if(!empty($list_proyek))
      {
        foreach ($list_proyek as $row) {
          // code...
          echo '<tr>';
          echo '<td class="text-center"><input type="hidden" id="no2_'.$no.'" value="'.$row->id_proyek.'" />'.$no.'</td>';
          echo '<td><input type="hidden" id="nama_'.$no.'" value="'.$row->nama_proyek.'" />'.$row->nama_proyek.'</td>';
          echo '<td>'.$row->penanggung_jawab.'</td>';
          echo '<td>'.number_format($row->nominal).'</td>';
          echo '<td><button onclick="pilihProyek('.$no.')" id="btn_'.$no.'" class="btn btn-info" type="button" data-dismiss="modal">Pilih</button></td>';
          echo '</tr>';

          $no++;
        }
      }

    }

    public function _rules()
    {
      	$this->form_validation->set_rules('id_kat_uang_keluar', 'kat uang keluar', 'trim|required');
      	//$this->form_validation->set_rules('id_proyek', 'proyek', 'trim|required');
        //$this->form_validation->set_rules('id_bank', 'bank', 'trim|required');
      	$this->form_validation->set_rules('no_uang_keluar', 'no uang keluar', 'trim|required');
      	$this->form_validation->set_rules('nama_uang_keluar', 'nama uang keluar', 'trim|required');
      	$this->form_validation->set_rules('diberikan_kepada', 'diberikan kepada', 'trim|required');
      	$this->form_validation->set_rules('diberikan_oleh', 'diberikan oleh', 'trim|required');
      	$this->form_validation->set_rules('untuk', 'untuk', 'trim|required');
      	$this->form_validation->set_rules('nominal', 'nominal', 'trim|required');


      	$this->form_validation->set_rules('id_uang_keluar', 'id_uang_keluar', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_uang_keluar.php */
/* Location: ./application/controllers/C_uang_keluar.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-21 04:56:44 */
/* http://harviacode.com */
