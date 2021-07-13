<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_tahun_ajaran extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_tahun_ajaran','M_semester'));
        $this->load->library('form_validation');
	      $this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {

          $this->load->view('admin/page/c_tahun_ajaran/tahun_ajaran.html');
        }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_tahun_ajaran->json();
    }

    public function create()
    {
        $list_semester_ajaran = $this->M_tahun_ajaran->list_semester_ajaran('');

        $data = array(
            'button' => 'Create',
            'action' => site_url('c_tahun_ajaran/create_action'),
      	    'id_ajaran' => set_value('id_ajaran'),
      	    'kode_tahun' => set_value('kode_tahun'),
      	    'tgl_mulai' => set_value('tgl_mulai'),
            'tgl_akhir' => set_value('tgl_akhir'),
            'nama_ajaran' => set_value('nama_ajaran'),
      	    'keterangan' => set_value('keterangan'),
            'list_semester_ajaran' =>$list_semester_ajaran,
	      );
        $this->load->view('admin/page/c_tahun_ajaran/input_tahun.html', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $tgl = date('Y-m-d H:i:s');
            $local=array();

            $data = array(
          		'kode_tahun' => $this->input->post('kode_tahun',TRUE),
          		'nama_ajaran' => $this->input->post('nama_ajaran',TRUE),
              'tgl_mulai' => $this->input->post('tgl_mulai',TRUE),
              'tgl_akhir' => $this->input->post('tgl_akhir',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_ins' => $tgl,
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
	           );

            $this->M_tahun_ajaran->insert($data);

            $id_tahun_ajaran = $this->M_tahun_ajaran->get_by('kode_tahun',$this->input->post('kode_tahun',TRUE))->id_ajaran;

            for ($i=1; $i <= count($this->input->post('id_semester')) ; $i++) {
                if (!empty($this->input->post('checklist')[$i])) {
                  $data2 = array(
                    'id_tahun_ajaran' => $id_tahun_ajaran,
                    'id_semester' => $this->input->post('id_semester')[$i],
                    'mulai_tgl' => $this->input->post('mulai_tgl')[$i],
                    'akhir_tgl' => $this->input->post('akhir_tgl')[$i],
                    'tgl_ins' => $tgl,
                    'user_updt' => $this->session->userdata('ses_id_akun'),
                  );

                  $local[] = $data2;
                }
            }
            // echo '<pre>';
            // print_r($local);
            // echo '<pre>';
            $this->M_tahun_ajaran->insert_tahun_semester($local);

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tahun-ajaran'));
        }
    }

    public function update($id)
    {
        $row = $this->M_tahun_ajaran->get_by_id($id);
        $list_semester_ajaran = $this->M_tahun_ajaran->list_semester_ajaran($row->id_ajaran);
        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('c_tahun_ajaran/update_action'),
            		'id_ajaran' => set_value('id_ajaran', $row->id_ajaran),
            		'kode_tahun' => set_value('kode_tahun', $row->kode_tahun),
            		'nama_ajaran' => set_value('nama_ajaran', $row->nama_ajaran),
                'tgl_mulai' => set_value('tgl_mulai', $row->tgl_mulai),
                'tgl_akhir' => set_value('tgl_akhir', $row->tgl_akhir),
            		'keterangan' => set_value('keterangan', $row->keterangan),
                'list_semester_ajaran' =>$list_semester_ajaran,

      	    );

            $this->load->view('admin/page/c_tahun_ajaran/input_tahun.html', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tahun-ajaran'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_ajaran', TRUE));
        } else {
            $tgl = date('Y-m-d H:i:s');
            $data = array(
          		'kode_tahun' => $this->input->post('kode_tahun',TRUE),
          		'nama_ajaran' => $this->input->post('nama_ajaran',TRUE),
              'tgl_mulai' => $this->input->post('tgl_mulai',TRUE),
              'tgl_akhir' => $this->input->post('tgl_akhir',TRUE),
          		'keterangan' => $this->input->post('keterangan',TRUE),
          		'tgl_updt' => $tgl,
          		'user_updt' => $this->session->userdata('ses_id_akun'),
	           );

            $this->M_tahun_ajaran->update($this->input->post('id_ajaran', TRUE), $data);


            //update multi
            for ($i=1; $i <= count($this->input->post('id_semester')) ; $i++) {
              if($this->input->post('id_tahun_semester')[$i] == '')
              {
                if (!empty($this->input->post('checklist')[$i])) {   //insert
                  $data2 = array(
                    'id_tahun_ajaran' => $this->input->post('id_ajaran', TRUE),
                    'id_semester' => $this->input->post('id_semester')[$i],
                    'mulai_tgl' => $this->input->post('mulai_tgl')[$i],
                    'akhir_tgl' => $this->input->post('akhir_tgl')[$i],
                    'tgl_ins' => $tgl,
                    'user_updt' => $this->session->userdata('ses_id_akun'),
                  );

                  //$local[] = $data2;
                  $this->M_tahun_ajaran->insert_semester($data2);
                }
              } else {
                if (!empty($this->input->post('checklist')[$i])) { //update
                  $data3 = array(
                    'mulai_tgl' => $this->input->post('mulai_tgl')[$i],
                    'akhir_tgl' => $this->input->post('akhir_tgl')[$i],
                  );

                  $this->M_tahun_ajaran->update_semester($this->input->post('id_tahun_semester')[$i],$data3);
                } else {  //delete
                  $this->M_tahun_ajaran->delete_semester($this->input->post('id_tahun_semester')[$i]);
                }
              }
            }

            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tahun-ajaran'));
        }
    }

    public function delete($id)
    {
        $row = $this->M_tahun_ajaran->get_by_id($id);

        if ($row) {
            $this->M_tahun_ajaran->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tahun-ajaran'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tahun-ajaran'));
        }
    }

    public function _rules()
    {
      	$this->form_validation->set_rules('kode_tahun', 'kode tahun', 'trim|required');
      	$this->form_validation->set_rules('nama_ajaran', 'nama ajaran', 'trim|required');
        $this->form_validation->set_rules('tgl_mulai', 'nama ajaran', 'trim|required');
        $this->form_validation->set_rules('tgl_akhir', 'nama ajaran', 'trim|required');
      	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

      	$this->form_validation->set_rules('id_ajaran', 'id_ajaran', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file C_tahun_ajaran.php */
/* Location: ./application/controllers/C_tahun_ajaran.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-06-29 07:19:50 */
/* http://harviacode.com */
