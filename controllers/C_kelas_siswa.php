<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_kelas_siswa extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_kelas','M_siswa','M_kelas_siswa','M_tahun_ajaran'));
        $this->load->library('form_validation');
	       $this->load->library('datatables');
    }

    public function index()
    {
        if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
        {
          header('Location: '.base_url().'login');
        } else {
        if((!empty($_GET['ajaran'])) && ($_GET['ajaran']!= "")  )
        {
          $c_ajaran = $_GET['ajaran'];
        } else {
          $c_ajaran = '';
        }

        if((!empty($_GET['kelas'])) && ($_GET['kelas']!= "")  )
        {
          $c_kelas = $_GET['kelas'];
        } else {
          $c_kelas = '';
        }

        $ajaran = $this->M_tahun_ajaran->list_ajaran();
        $kelas = $this->M_kelas->list_kelas();

        $list_kelas_siswa = $this->M_kelas_siswa->list_kelas_siswa($c_ajaran,$c_kelas);

          //$list_kelas_siswa = $this->M_kelas_siswa->list_kelas_siswa($ajaran->id_ajaran,)
          $data = array('list_ajaran'=>$ajaran,
                        'list_kelas'=>$kelas,
                        'list_kelas_siswa'=>$list_kelas_siswa
                        );

          $this->load->view('admin/page/c_kelas_siswa/kelas_siswa.html',$data);
        }
    }

    public function list_kelas_by()
    {
      $ajaran = $this->input->post('ajaran',TRUE);
      $kelas = $this->input->post('kelas',TRUE);

      $list_kelas_siswa = $this->M_kelas_siswa->list_kelas_siswa($ajaran,$kelas);
      
      $this->load->view('admin/page/c_kelas_siswa/kelas_siswa.html',$data);
    }

    public function list_siswa_unreg()
    {
      $cari = $this->input->post('cari', TRUE);
      $list_siswa = $this->M_siswa->list_siswa_unreg($cari);

      if(!empty($list_siswa))
      {
        $result = $list_siswa->result();
        $no=1;
        foreach($result as $row)
        {
          echo '<tr>';
          echo '<td class="text-center"><input type="hidden" id="no2_'.$no.'" value="'.$row->id_siswa.'" />'.$no.'</td>';
          echo '<td>'.$row->kode_siswa.'</td>';
          echo '<td>'.$row->nama_siswa.'</td>';
          echo '<td>'.$row->jkel.'</td>';
          echo '<td><button onclick="pilihSiswa('.$no.')" id="btn_'.$no.'" class="btn btn-info" type="button">Pilih</button></td>';
          echo '</tr>';
          $no++;
        }
      } else {
        echo '<tr>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>Tidak ada data!</td>';
        echo '<td></td>';
        echo '</tr>';
      }
    }

    public function list_siswa_kelas()
    {
      $cari = $this->input->post('cari', TRUE);
      $id_kelas = $this->input->post('id_kelas',TRUE);
      $id_ajaran = $this->input->post('id_ajaran',TRUE);

      $list_siswa = $this->M_kelas_siswa->list_siswa_by_kelas($cari,$id_kelas,$id_ajaran);

      if(!empty($list_siswa))
      {
        $result = $list_siswa->result();
        $no=1;
        foreach($result as $row)
        {
          echo '<tr>';
          echo '<td class="text-center"><input type="hidden" id="no2_'.$no.'" value="'.$row->id_siswa.'"  name="no2['.$no.']"/>'.$no.'</td>';
          echo '<td>'.$row->kode_siswa.'</td>';
          echo '<td>'.$row->nama_siswa.'</td>';
          // echo '<td><button onclick="pilihSiswa('.$no.')" id="btn_'.$no.'" class="btn btn-info" type="button">Pilih</button></td>';

          echo '<td><div class="checkbox checkbox-danger">
              <input id="check_'.$no.'" name="checklist['.$no.']" type="checkbox">
              <label for="checkbox0"> Pilih</label>
          </div></td>';
          echo '</tr>';
          $no++;
        }
      } else {
        echo '<tr>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>Tidak ada data!</td>';
        echo '<td></td>';
        echo '</tr>';
      }
    }

    public function list_siswa_kelas_bayaran()
    {
      $cari = $this->input->post('cari', TRUE);
      $id_kelas = $this->input->post('id_kelas',TRUE);
      $id_ajaran = $this->input->post('id_ajaran',TRUE);
      $id_bayaran = $this->input->post('id_bayaran',TRUE);

      $list_siswa = $this->M_kelas_siswa->list_siswa_by_bayaran($cari,$id_kelas,$id_ajaran,$id_bayaran);

      if(!empty($list_siswa))
      {
        $result = $list_siswa->result();
        $no=1;
        foreach($result as $row)
        {

          echo '<tr>';
          echo '<td class="text-center"><input type="hidden" id="no2_'.$no.'" value="'.$row->id_siswa.'"  name="no2['.$no.']"/>'.$no.'</td>';
          echo '<td>'.$row->kode_siswa.'</td>';
          echo '<td>'.$row->nama_siswa.'</td>';
          // echo '<td><button onclick="pilihSiswa('.$no.')" id="btn_'.$no.'" class="btn btn-info" type="button">Pilih</button></td>';
          echo '<td><input type="checkbox" id="check_'.$no.'" name="checklist['.$no.']" /></td>';
          echo '</tr>';
          $no++;
        }
      } else {
        echo '<tr>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>Tidak ada data!</td>';
        echo '<td></td>';
        echo '</tr>';
      }
    }

    public function list_siswa_kelas_pengurang()
    {
      $cari = $this->input->post('cari', TRUE);
      $id_kelas = $this->input->post('id_kelas',TRUE);
      $id_ajaran = $this->input->post('id_ajaran',TRUE);
      $id_pengurang = $this->input->post('id_pengurang',TRUE);

      $list_siswa = $this->M_kelas_siswa->list_siswa_by_pengurang($cari,$id_kelas,$id_ajaran,$id_pengurang);

      if(!empty($list_siswa))
      {
        $result = $list_siswa->result();
        $no=1;
        foreach($result as $row)
        {

          echo '<tr>';
          echo '<td class="text-center"><input type="hidden" id="no2_'.$no.'" value="'.$row->id_siswa.'"  name="no2['.$no.']"/>'.$no.'</td>';
          echo '<td>'.$row->kode_siswa.'</td>';
          echo '<td>'.$row->nama_siswa.'</td>';
          // echo '<td><button onclick="pilihSiswa('.$no.')" id="btn_'.$no.'" class="btn btn-info" type="button">Pilih</button></td>';
          echo '<td><input type="checkbox" id="check_'.$no.'" name="checklist['.$no.']" /></td>';
          echo '</tr>';
          $no++;
        }
      } else {
        echo '<tr>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>Tidak ada data!</td>';
        echo '<td></td>';
        echo '</tr>';
      }
    }

    function simpan()
    {
      $id_ajaran = $this->input->post('id_ajaran', TRUE);
      $id_kelas = $this->input->post('id_kelas', TRUE);
      $id_siswa = $this->input->post('id_siswa', TRUE);

      $this->M_kelas_siswa->simpan(
        $id_kelas,$id_siswa,$id_ajaran
      );

      echo 'ok';

    }

    function hapus()
    {
      $id = $this->input->post('id_kelas_siswa',TRUE);

      $this->M_kelas_siswa->hapus($id);
      echo 'ok';
    }
}
