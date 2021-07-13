<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tb_periode extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tb_periode_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'tb_periode/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tb_periode/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tb_periode/index.html';
            $config['first_url'] = base_url() . 'tb_periode/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tb_periode_model->total_rows($q);
        $tb_periode = $this->Tb_periode_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tb_periode_data' => $tb_periode,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tb_periode/tb_periode_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Tb_periode_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_periode' => $row->id_periode,
		'nama_periode' => $row->nama_periode,
	    );
            $this->load->view('tb_periode/tb_periode_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tb_periode'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tb_periode/create_action'),
	    'id_periode' => set_value('id_periode'),
	    'nama_periode' => set_value('nama_periode'),
	);
        $this->load->view('tb_periode/tb_periode_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'nama_periode' => $this->input->post('nama_periode',TRUE),
	    );

            $this->Tb_periode_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tb_periode'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tb_periode_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tb_periode/update_action'),
		'id_periode' => set_value('id_periode', $row->id_periode),
		'nama_periode' => set_value('nama_periode', $row->nama_periode),
	    );
            $this->load->view('tb_periode/tb_periode_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tb_periode'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_periode', TRUE));
        } else {
            $data = array(
		'nama_periode' => $this->input->post('nama_periode',TRUE),
	    );

            $this->Tb_periode_model->update($this->input->post('id_periode', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tb_periode'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tb_periode_model->get_by_id($id);

        if ($row) {
            $this->Tb_periode_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tb_periode'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tb_periode'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nama_periode', 'nama periode', 'trim|required');

	$this->form_validation->set_rules('id_periode', 'id_periode', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tb_periode.xls";
        $judul = "tb_periode";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Periode");

	foreach ($this->Tb_periode_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_periode);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Tb_periode.php */
/* Location: ./application/controllers/Tb_periode.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-09-17 04:48:23 */
/* http://harviacode.com */