<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_tran_siswa extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_tran_siswa','M_kelas','M_siswa','M_kelas_siswa','M_tahun_ajaran','M_kat_bayaran','M_pengurang_bayaran'));
        $this->load->library('form_validation');
	$this->load->library('datatables');
    }

    public function index()
    {
      if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
      {
        header('Location: '.base_url().'login');
      } else {
        $ajaran = $this->M_tahun_ajaran->get_all();
        $kelas = $this->M_kelas->get_all();



        $data = array('list_ajaran'=>$ajaran,
                      'list_kelas'=>$kelas,
                      );

        $this->load->view('admin/page/c_tran_siswa/bayaran.html',$data);
      }
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->M_tran_siswa->json();
    }

    public function list_kelas_siswa_cari()
    {
      $cari = $this->input->post('cari_nik', TRUE);
      $cari_nama = $this->input->post('cari_nama', TRUE);
      $id_kelas = $this->input->post('kelas',TRUE);
      $id_ajaran = $this->input->post('ajaran',TRUE);

      $list_siswa = $this->M_kelas_siswa->list_kelas_siswa_cari($id_ajaran,$id_kelas,$cari,$cari_nama);
      $ajaran_row = $this->M_tahun_ajaran->get_by_id($id_ajaran);

      $list_periode = $this->M_tran_siswa->get_list_periode($ajaran_row->tgl_mulai,$ajaran_row->tgl_akhir);
      $list_semester = $this->M_tran_siswa->get_list_semester($id_ajaran);

      // echo '<pre>';
      // print_r($list_semester);
      // echo '</pre>';

      $total_sisa = 0;

      $c_date = date('Y-m');
      $result = array();

      if(!empty($list_siswa))
      {
        foreach ($list_siswa as $xrow)
        {
          //list bayaran bulanan
           $s_id_siswa = $xrow->id_siswa;

          if(!empty($list_periode))
          {
              foreach ($list_periode as $row) {
                if($row->nama_periode <= $c_date)
                {
                  $r = $this->M_tran_siswa->get_tagihan_per_periode($row->nama_periode, $s_id_siswa,3,$row->nama_periode,$row->nama_periode);
                  $total_sisa += $r->sisa_bayar;
                  //echo $xrow->nama_siswa;
                }
              }
          }



          //list bayaran semester


          if(!empty($list_semester))
          {
            foreach ($list_semester as $row) {
              // code...
            //  if($row->akhir_tgl <= $c_date)
            //  {
                  $s = $this->M_tran_siswa->get_tagihan_per_periode($row->nama_semester, $xrow->id_siswa,2,$row->akhir_tgl,$row->kode_semester);
                  $total_sisa += $s->sisa_bayar;
            //  }
            }
          }

          //list bayaran tahunan
          $batas = substr($ajaran_row->tgl_akhir,0,7);


          $tagihanTahunan = array();
          $t = $this->M_tran_siswa->get_tagihan_per_periode($ajaran_row->nama_ajaran,  $xrow->id_siswa,1,$batas,$ajaran_row->kode_tahun);
          $total_sisa += $t->sisa_bayar;

          //
          //list bayaran tetap
          $tagihanTetap = array();
          $tp = $this->M_tran_siswa->get_tagihan_per_periode($ajaran_row->nama_ajaran,$xrow->id_siswa,4,'2030-12',$ajaran_row->kode_tahun);
          $total_sisa += $tp->sisa_bayar;

          $local = array('id_siswa' => $xrow->id_siswa,
                         'nama_ajaran' => $xrow->nama_ajaran,
                         'nama_kelas' => $xrow->nama_kelas,
                         'kode_siswa' => $xrow->kode_siswa,
                         'nama_siswa' => $xrow->nama_siswa,
                         'sisa_bayar' => $total_sisa,
                        );
          $result[] = $local;
          $total_sisa = 0;

        }
      }
      // echo '<pre>';
      // print_r($result);
      // echo '</pre>';


      if(!empty($result))
      {
        //$result2 = $result->result();
        $no=1;
        $total=0;
        foreach($result as $row)
        {
          echo '<tr>';
          echo '<td class="text-center"><input type="hidden" id="no2_'.$no.'" value="'.$row['id_siswa'].'"  name="no2['.$no.']"/>'.$no.'</td>';
          echo '<td>'.$row['nama_ajaran'].'</td>';
          echo '<td>'.$row['nama_kelas'].'</td>';
          echo '<td>'.$row['kode_siswa'].'</td>';
          echo '<td>'.$row['nama_siswa'].'</td>';
          echo '<td class="text-right">'.number_format($row['sisa_bayar']).'</td>';
          echo '<td><a href="'.base_url().'detail-bayaran/'.$row['id_siswa'].'" id="btn_'.$no.'" class="btn btn-info" type="button">Pilih</a></td>';

          echo '</tr>';
          $no++;
          $total +=$row['sisa_bayar'];
          //print_r($row['id_siswa']);

        }
        echo '<tr>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td><strong>Total</strong></td>';
        echo '<td class="text-right"><strong>'.number_format($total).'</strong></td>';
        echo '<td></td>';
        echo '</tr>';


      } else {
        echo '<tr>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>Tidak ada data!</td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '</tr>';
      }
    }

    public function input()
    {
      $id_siswa = $this->uri->segment(2,0);
      $periode = $this->uri->segment(3,0);

      $tgl = date('Y-m-d');
      $detail = $this->M_siswa->detail_siswa($id_siswa);
      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,3)->nominal;
      $no_pembayaran = $this->M_tran_siswa->get_no_bayar()->no_bayar;

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      $list_periode = $this->M_tran_siswa->get_list_periode($mulai_ajaran,$akhir_ajaran);

      $id_tran = '';

      // $arrTagihan = array();
      // if(!empty($list_periode))
      // {
      //     foreach ($list_periode as $row) {
      //       $r = $this->M_tran_siswa->get_tagihan_per_periode($row->nama_periode, $id_siswa,3);
      //
      //       $arrTagihan[] = $r;
      //     }
      // }

      $list_bulanan = $this->M_tran_siswa->list_bayaran_per_siswa($id_siswa,3,$periode,$id_tran,$periode);

      $data = array('detail'=>$detail,
                    'list_bulanan' => $list_bulanan,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'periode' => $periode,
                    'kode_periode' => $periode,
                    'tgl' => $tgl,
                    'id_tran' => $id_tran,
                    'keterangan' => '',
                    );

      $this->load->view('admin/page/c_tran_siswa/input_transaksi.html',$data);
    }

    public function input_semester()
    {
      $id_siswa = $this->uri->segment(2,0);
      $kode_semester = $this->uri->segment(3,0);

      $tgl = date('Y-m-d');
      $detail = $this->M_siswa->detail_siswa($id_siswa);
      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,2)->nominal;
      $no_pembayaran = $this->M_tran_siswa->get_no_bayar()->no_bayar;

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      $semester = $this->M_tran_siswa->get_semester_by_code($kode_semester,$detail->id_ajaran);

      $id_tran = '';

      $list_bulanan = $this->M_tran_siswa->list_bayaran_per_siswa_semester($id_siswa,2,$semester->akhir_tgl,$semester->mulai_tgl,$id_tran,$semester->kode_semester);

      $data = array('detail'=>$detail,
                    'list_bulanan' => $list_bulanan,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'periode' => $semester->nama_semester,
                    'kode_periode' => $semester->kode_semester,
                    'tgl' => $tgl,
                    'id_tran' => $id_tran,
                    'keterangan' => '',
                    );

      $this->load->view('admin/page/c_tran_siswa/input_transaksi.html',$data);
    }

    public function input_tahunan()
    {
      $id_siswa = $this->uri->segment(2,0);
      $kode_semester = $this->uri->segment(3,0);

      $tgl = date('Y-m-d');
      $detail = $this->M_siswa->detail_siswa($id_siswa);
      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,1)->nominal;
      $no_pembayaran = $this->M_tran_siswa->get_no_bayar()->no_bayar;

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      //$semester = $this->M_tran_siswa->get_semester_by_code($kode_semester,$detail->id_ajaran);

      $id_tran = '';

      $list_bulanan = $this->M_tran_siswa->list_bayaran_per_siswa($id_siswa,1,$detail->tgl_akhir,$id_tran,$detail->kode_tahun);

      $data = array('detail'=>$detail,
                    'list_bulanan' => $list_bulanan,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'periode' => $detail->nama_ajaran,
                    'kode_periode' => $detail->kode_tahun,
                    'tgl' => $tgl,
                    'id_tran' => $id_tran,
                    'keterangan' => '',
                    );

      $this->load->view('admin/page/c_tran_siswa/input_transaksi.html',$data);
    }

    public function input_tetap()
    {
      $id_siswa = $this->uri->segment(2,0);
      $kode_semester = $this->uri->segment(3,0);

      $tgl = date('Y-m-d');
      $detail = $this->M_siswa->detail_siswa($id_siswa);
      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,4)->nominal;
      $no_pembayaran = $this->M_tran_siswa->get_no_bayar()->no_bayar;

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      //$semester = $this->M_tran_siswa->get_semester_by_code($kode_semester,$detail->id_ajaran);

      $id_tran = '';

      $list_bulanan = $this->M_tran_siswa->list_bayaran_per_siswa($id_siswa,4,'2030-12',$id_tran,'2030-12');

      $data = array('detail'=>$detail,
                    'list_bulanan' => $list_bulanan,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'periode' => $detail->nama_ajaran,
                    'kode_periode' => $detail->kode_tahun,
                    'tgl' => $tgl,
                    'id_tran' => $id_tran,
                    'keterangan' => '',
                    );

      $this->load->view('admin/page/c_tran_siswa/input_transaksi.html',$data);
    }


    public function update()
    {
      $id = $this->uri->segment(2,0);
      $row = $this->M_tran_siswa->get_by_id($id);

      $id_siswa = $row->id_siswa;
      $periode = $row->periode;

      $tgl = $row->tgl_bayar;

      $detail = $this->M_siswa->detail_siswa($id_siswa);
      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,3)->nominal;
      $no_pembayaran = $row->kode_tran;

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      $list_periode = $this->M_tran_siswa->get_list_periode($mulai_ajaran,$akhir_ajaran);

      $id_tran = $row->id_tran;

      $list_bulanan = $this->M_tran_siswa->list_bayaran_per_siswa($id_siswa,3,$periode,$id_tran,$periode);

      $data = array('detail'=>$detail,
                    'list_bulanan' => $list_bulanan,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'periode' => $periode,
                    'kode_periode' =>$periode,
                    'tgl' => $tgl,
                    'id_tran' => $id_tran,
                    'keterangan' => $row->keterangan,
                    );

      $this->load->view('admin/page/c_tran_siswa/input_transaksi.html',$data);

    }

    public function update_semester()
    {
      $id = $this->uri->segment(2,0);
      $row = $this->M_tran_siswa->get_by_id($id);

      $id_siswa = $row->id_siswa;
      $periode = $row->periode;

      $tgl = $row->tgl_bayar;


      $detail = $this->M_siswa->detail_siswa($id_siswa);
      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,2)->nominal;
      $no_pembayaran = $row->kode_tran;

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      $semester = $this->M_tran_siswa->get_semester_by_code($periode,$row->id_tahun_ajaran);

      $id_tran = $row->id_tran;

      $list_bulanan = $this->M_tran_siswa->list_bayaran_per_siswa($id_siswa,2,$semester->akhir_tgl,$id_tran,$periode);

      $data = array('detail'=>$detail,
                    'list_bulanan' => $list_bulanan,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'periode' => $periode,
                    'kode_periode' =>$periode,
                    'tgl' => $tgl,
                    'id_tran' => $id_tran,
                    'keterangan' => $row->keterangan,
                    );

      $this->load->view('admin/page/c_tran_siswa/input_transaksi.html',$data);

    }

    public function update_tahunan()
    {
      $id = $this->uri->segment(2,0);
      $row = $this->M_tran_siswa->get_by_id($id);

      $id_siswa = $row->id_siswa;
      $periode = $row->periode;

      $tgl = $row->tgl_bayar;


      $detail = $this->M_siswa->detail_siswa($id_siswa);
      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,1)->nominal;
      $no_pembayaran = $row->kode_tran;

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      //$semester = $this->M_tran_siswa->get_semester_by_code($periode,$row->id_tahun_ajaran);

      $id_tran = $row->id_tran;

      $list_bulanan = $this->M_tran_siswa->list_bayaran_per_siswa($id_siswa,1,$detail->tgl_akhir,$id_tran,$detail->kode_tahun);

      $data = array('detail'=>$detail,
                    'list_bulanan' => $list_bulanan,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'periode' => $detail->nama_ajaran,
                    'kode_periode' => $detail->kode_tahun,
                    'tgl' => $tgl,
                    'id_tran' => $id_tran,
                    'keterangan' => $row->keterangan,
                    );

      $this->load->view('admin/page/c_tran_siswa/input_transaksi.html',$data);

    }

    public function update_tetap()
    {
      $id = $this->uri->segment(2,0);
      $row = $this->M_tran_siswa->get_by_id($id);

      $id_siswa = $row->id_siswa;
      $periode = $row->periode;

      $tgl = $row->tgl_bayar;

      $detail = $this->M_siswa->detail_siswa($id_siswa);
      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,4)->nominal;
      $no_pembayaran = $row->kode_tran;

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      //$semester = $this->M_tran_siswa->get_semester_by_code($periode,$row->id_tahun_ajaran);

      $id_tran = $row->id_tran;

      $list_bulanan = $this->M_tran_siswa->list_bayaran_per_siswa($id_siswa,4,'2030-12',$id_tran,$detail->kode_tahun);

      $data = array('detail'=>$detail,
                    'list_bulanan' => $list_bulanan,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'periode' => $detail->nama_ajaran,
                    'kode_periode' => $detail->kode_tahun,
                    'tgl' => $tgl,
                    'id_tran' => $id_tran,
                    'keterangan' => $row->keterangan,
                    );

      $this->load->view('admin/page/c_tran_siswa/input_transaksi.html',$data);

    }

    public function detail()
    {
      $id_siswa = $this->uri->segment(2,0);
      $list_kat_bayaran = $this->M_kat_bayaran->get_all();

      if((!empty($_GET['id_kat_bayaran'])) && ($_GET['id_kat_bayaran']!= "")  )
      {
        $id_kat_bayaran = $_GET['id_kat_bayaran'];
      } else {
        $id_kat_bayaran = '';
      }

      $no_pembayaran = '';//$this->M_tran_siswa->get_no_bayar()->no_bayar;

      $biaya_pengurang = '';// $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,$id_kat_bayaran)->nominal;

      $detail = $this->M_siswa->detail_siswa($id_siswa);

      $mulai_ajaran = $detail->tgl_mulai;
      $akhir_ajaran = $detail->tgl_akhir;

      $list_periode = $this->M_tran_siswa->get_list_periode($mulai_ajaran,$akhir_ajaran);

      //list bayaran bulanan
      $arrTagihan = array();
      if(!empty($list_periode))
      {
          foreach ($list_periode as $row) {
            $r = $this->M_tran_siswa->get_tagihan_per_periode($row->nama_periode, $id_siswa,3,$row->nama_periode,$row->nama_periode);

            $arrTagihan[] = $r;
          }
      }

      //list bayaran semester
      $tagihanSemester = array();
      $list_semester = $this->M_tran_siswa->get_list_semester($detail->id_ajaran);

      if(!empty($list_semester))
      {
        foreach ($list_semester as $row) {
          // code...
          $s = $this->M_tran_siswa->get_tagihan_per_periode($row->nama_semester, $id_siswa,2,$row->akhir_tgl,$row->kode_semester);
          $tagihanSemester[] = $s;
        }
      }

      //list bayaran tahunan
      $batas = substr($akhir_ajaran,0,7);
      //echo $batas;

      $tagihanTahunan = array();
      $t = $this->M_tran_siswa->get_tagihan_per_periode($detail->nama_ajaran, $id_siswa,1,$batas,$detail->kode_tahun);
      $tagihanTahunan[] = $t;


      //list bayaran tetap
      $tagihanTetap = array();
      $tp = $this->M_tran_siswa->get_tagihan_per_periode($detail->nama_ajaran, $id_siswa,4,'2030-12',$detail->kode_tahun);
      $tagihanTetap[] = $tp;

      $data = array('detail' => $detail,
                    'list_kat_bayaran' => $list_kat_bayaran,
                    'biaya_pengurang' => $biaya_pengurang,
                    'no_pembayaran' => $no_pembayaran,
                    'laporan_bayaran' => $arrTagihan,
                    'tagihan_semester' => $tagihanSemester,
                    'tagihanTahunan' => $tagihanTahunan,
                    'tagihanTetap' => $tagihanTetap,
                );

      $this->load->view('admin/page/c_tran_siswa/detail_bayar.html',$data);
    }

    public function get_no_bayar()
    {
      $no_pembayaran = $this->M_tran_siswa->get_no_bayar()->no_bayar;

      echo '<input type="hidden" name="no_pembayaran" id="no_pembayaran" value="'.$no_pembayaran.'" />';
      echo '<p><strong>No. Pembayaran : '.$no_pembayaran.'</strong></p>';
    }

    public function get_detail_bayar()
    {
      $periode = $this->input->post('periode',TRUE);
      $id_siswa = $this->input->post('id_siswa',TRUE);
      $id_kat_bayaran = $this->input->post('id_kat_bayaran',TRUE);

      $biaya_pengurang = $this->M_pengurang_bayaran->total_pengurang_biaya($id_siswa,$id_kat_bayaran)->nominal;

      $list_bayaran = $this->M_tran_siswa->list_bayaran_per_siswa($id_siswa,$id_kat_bayaran,$periode);
      $total_tunggakan=0;

      $no=1;
      //$list = $list_bayaran->result();
      echo '<div class="row">';
      echo '<div class="col-lg-12 col-md-12">';
      echo '    <div class="table-responsive">';
      echo '        <table class="table table-hover">';
      echo '          <thead>';
      echo '              <tr>';
      echo '                  <th width="10%">#</th>';
      echo '                  <th width="40%">Deskripsi</th>';
      echo '                  <th width="15%">Periode</th>';
      echo '                  <th class="text-right">Subtotal</th>';
      echo '              </tr>';
      echo '          </thead>';
      echo '         <tbody id="list_tagihan">';

      foreach($list_bayaran as $row)
      {

        echo '<tr>';
        echo '<td><input type="hidden" name="id_bayaran['.$no.']" value="'.$row->id_bayaran.'" />'.$no.'</td>';
        echo '<td>'.$row->nama_bayaran.'</td>';
        echo '<td>'.$row->periode.'</td>';
        echo '<td class="text-right">'.number_format($row->nominal).'</td>';
        echo '<input type="hidden" name="nominal_['.$no.']" value="'.$row->nominal.'" />';
        echo '<input type="hidden" name="periode_['.$no.']" value="'.$row->kode_periode.'" />';

        echo '</tr>';

        $total_tunggakan += $row->nominal;

        $no++;
      }

      echo '        </tbody>';
      echo '      </table>';
      echo '  </div>';
      echo '  </div>';
      echo '  <div class="col-md-12">';
      echo '  <div class="pull-right m-t-30 text-right">';
      echo '       <h4>Total: Rp. '.number_format($total_tunggakan).'</h4>';
      echo '       <input type="hidden" name="biaya_pengurang" id="biaya_pengurang" value="'.$biaya_pengurang.'" />';
      echo '       <p>Potongan Biaya : Rp. '.number_format($biaya_pengurang).'</p>';
      echo '       <hr>';
      echo '       <h3><b>Total Pembayaran:</b> Rp. '.number_format($total_tunggakan - $biaya_pengurang).'</h3>';
      echo '   <div class="clearfix"></div>';
      echo '   <hr>';
      echo '  </div>';
      echo '  </div>';
      echo '  <div class="col-md-12">';
      echo '  <div class="form-group">';
      echo '    <textarea name="keterangan" id="keterangan" placeholder="Keterangan" class="form-control" rows="2"></textarea>';
      echo '  </div>';
      echo '  </div>';
      echo '  </div>';
    }

    public function list_transaksi_cetak()
    {
      $id_siswa = $this->input->post('id_siswa',TRUE);
      $periode = $this->input->post('periode',TRUE);
      $id_kat_bayaran = $this->input->post('id_kat_bayaran',TRUE);

      $list_cetak = $this->M_tran_siswa->list_transaksi_cetak($periode,$id_siswa);

      $nama_cetak = '';
      $nama_edit = '';

      if($id_kat_bayaran == '4'){
        $nama_cetak = 'cetak_tetap';
        $nama_edit = 'edit-tetap';
      } else if($id_kat_bayaran == '3'){
        $nama_cetak = 'cetak';
        $nama_edit = 'edit-transaksi';
      } else if($id_kat_bayaran == '2') {
        $nama_cetak = 'cetak_semester';
        $nama_edit = 'edit-semester';
      } else if($id_kat_bayaran == '1') {
        $nama_cetak = 'cetak_tahunan';
        $nama_edit = 'edit-tahunan';
      }

      if(!empty($list_cetak))
      {
          $no=1;
          foreach ($list_cetak as $row) {
            echo '<tr>';
            echo '<td><input type="hidden" name="no_'.$no.'" id="no_'.$no.'" value="'.$row->id_tran.'" />'.$no.'</td>';
            echo '<td>'.$row->kode_tran.'</td>';
            echo '<td>'.$row->tgl_bayar.'</td>';
            echo '<td class="text-right">'.number_format($row->bayar).'</td>';
            echo '<td>
              <a href="'.base_url().'C_tran_siswa/'.$nama_cetak.'/'.$row->id_tran.'" target="_blank"><i class="mdi mdi-printer text-success"></i> Cetak </a>
              <a href="'.base_url().$nama_edit.'/'.$row->id_tran.'"><i class="mdi mdi-table-edit text-warning"></i> Edit </a>
              <a href="'.base_url().'C_tran_siswa/delete/'.$row->id_tran.'" onclick="return confirm("Apakah anda yakin?")"><i class="mdi mdi-delete text-danger"></i> Hapus </a>
            </td>';
            echo '</tr>';
          }
      }

    }

    public function simpan_bayar()
    {
      $local=array();
      $tgl = date('Y-m-d H:i:s');
      $tgl_bayar = $this->input->post('tgl_bayar',TRUE);

      $id = $this->input->post('id_tran',TRUE);

      $id_tahun_ajaran = $this->input->post('id_tahun_ajaran',TRUE);
      $id_kelas = $this->input->post('id_kelas',TRUE);
      $id_siswa = $this->input->post('id_siswa',TRUE);
      $kode_tran = $this->input->post('no_pembayaran',TRUE);
      $potongan = 0;//$this->input->post('biaya_pengurang',TRUE);
      $keterangan = $this->input->post('keterangan',TRUE);

      if($id == '') //insert
      {
        $data = array(
          'id_tahun_ajaran' => $id_tahun_ajaran,
          'id_kelas' => $id_kelas,
          'id_siswa' => $id_siswa,
          'id_bayaran_siswa' => '',
          'kode_tran' => $kode_tran,
          'periode' => $this->input->post('kode_periode',TRUE),
          'tgl_bayar' => $tgl_bayar,
          'potongan' => $potongan,
          'keterangan' => $this->input->post('keterangan',TRUE),
          'tgl_ins' => $tgl,
          'user_updt' => $this->session->userdata('ses_id_akun'),
        );

        $this->M_tran_siswa->insert($data);

        for($i=0; $i < count($this->input->post('id_bayaran')); $i++)
        {
          $data = array(
            'kode_tran' => $kode_tran,
            'id_bayaran' => $this->input->post('id_bayaran',TRUE)[$i],
            'nominal' => $this->input->post('input_bayar',TRUE)[$i],
          );
          $local[] = $data;
        }

        $this->M_tran_siswa->insert_detail($local);

      } else { //update
        $data = array(
          'tgl_bayar' => $tgl_bayar,
          'keterangan' => $this->input->post('keterangan',TRUE),
          'tgl_updt' => $tgl,
          'user_updt' => $this->session->userdata('ses_id_akun'),
        );

        $this->M_tran_siswa->update($id,$data);

        for($j=0; $j < count($this->input->post('id_d_tran')); $j++)
        {
          $data = array(
            'id_d_tran' => $this->input->post('id_d_tran',TRUE)[$j],
            'nominal' => $this->input->post('input_bayar',TRUE)[$j],
          );
          $local[] = $data;
        }

        $this->M_tran_siswa->update_detail('id_d_tran',$local);



      }



      redirect(site_url('detail-bayaran/'.$id_siswa));
    }

    public function cetak($id_tran)
    {

      $list_bayaran = $this->M_tran_siswa->list_bayaran_cetak($id_tran);

      $r = $this->M_tran_siswa->get_tagihan_per_periode(
        $list_bayaran->row(0)->periode,
        $list_bayaran->row(0)->id_siswa,
        3,
        $list_bayaran->row(0)->periode,
        $list_bayaran->row(0)->periode
        );

      $tgl_bayar = $list_bayaran->row(0)->tgl_bayar;
      $kode_siswa = $list_bayaran->row(0)->kode_siswa;
      $nama_siswa = $list_bayaran->row(0)->nama_siswa;

      $periode = $list_bayaran->row(0)->periode;

      $sisa_bayar = $r->sisa_bayar;

      $biaya_pengurang = $list_bayaran->row(0)->potongan;

      $no_bayar = $list_bayaran->row(0)->kode_tran;

      // $data = array('list_bayaran' => $list_bayaran,
      //               'no_bayar' => $no_bayar,
      //               'biaya_pengurang' =>$biaya_pengurang,
      //               'sisa_bayar' => $sisa_bayar,
      //               'kode_siswa' => $kode_siswa,
      //               'nama_siswa' => $nama_siswa,
      //               'tgl_bayar' => $tgl_bayar,
      //               );

      //$this->load->library('pdf');
      //$customPaper = array(0,0,381.89,595.28);
      //$this->pdf->setPaper('A4', 'portrait');
      //$this->pdf->load_view('admin/page/c_tran_siswa/cetak',$data);
      //$this->load->view('admin/page/c_tran_siswa/cetak',$data);

      $this->load->library('fpdf2');

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      // $pdf->SetXY(5, 5);
      $pdf->Image(base_url().'assets/kop.JPG',10,2,200,35,'JPG');
      $pdf->SetFont("Arial","B","10");
      $pdf->setX(10);
      // $pdf->Cell(200,5,'Yayasan Pendidikan Islam Ashabulyamin Pabuaran - Cianjur',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'PONDOK PESANTREN TERPADU',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ASHABULYAMIN',0,1,'C');
      // $pdf->SetFont("Arial","","8");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'Jl. KH. Saleh Rt 004/004 Kelurahan Sayang Kec. Cianjur Kab. Cianjur 43213 HP. 081908453401',0,1,'C');
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ashabulyamin.sch.id',0,1,'C');
      // $pdf->SetLineWidth(10);
      $pdf->Line(200,37,10,37);
      $pdf->Cell(200,30,'',0,1);
      $pdf->Cell(200,5,'Bukti Pembayaran',0,1,'C');
      $pdf->Cell(200,5,'No : '.$no_bayar,0,1,'C');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(10,5,'Tanggal    : '.$tgl_bayar,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'NIK           : '.$kode_siswa,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'Nama       : '.$nama_siswa,0,1,'L');
      $pdf->Cell(105,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(20,6,'No',1,0);
      $pdf->Cell(130,6,'Pembayaran',1,0);
      $pdf->Cell(35,6,'Jumlah',1,1);

      $list_result =  $list_bayaran->result();
      $no=1;
      $total_bayar=0;

      foreach ($list_result as $row) {
        // code...
        $pdf->setX(10);
        $pdf->Cell(20,6,$no,1,0);
        $pdf->Cell(130,6,$row->nama_bayaran,1,0);
        $pdf->Cell(35,6,number_format($row->nominal),1,1,'R');
        $total_bayar += $row->nominal;
        $no++;
      }



      $pdf->Cell(105,3,'',0,1);
      $pdf->setX(140);
      $pdf->Cell(45,6,'Jumlah Bayar :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($total_bayar),0,1,'R');
      $pdf->setX(140);
      $pdf->Cell(45,6,'Sisa Tagihan :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($sisa_bayar),0,1,'R');

      $pdf->Cell(105,20,'',0,1);
      $pdf->setX(150);
      $pdf->Cell(45,5,'Admin,',0,1,'C');
      $pdf->setX(150);
      $pdf->Cell(45,6,$this->session->userdata('ses_nama_karyawan'),0,1,'C');

      $pdf->Output();

    }

    public function cetak_semester($id_tran)
    {

      $list_bayaran = $this->M_tran_siswa->list_bayaran_cetak($id_tran);
      $semester = $this->M_tran_siswa->get_semester_by_code($list_bayaran->row(0)->periode,$list_bayaran->row(0)->id_tahun_ajaran);

      $r = $this->M_tran_siswa->get_tagihan_per_periode(
        $list_bayaran->row(0)->periode,
        $list_bayaran->row(0)->id_siswa,
        2,
        $semester->akhir_tgl,
        $list_bayaran->row(0)->periode
        );

      $tgl_bayar = $list_bayaran->row(0)->tgl_bayar;
      $kode_siswa = $list_bayaran->row(0)->kode_siswa;
      $nama_siswa = $list_bayaran->row(0)->nama_siswa;

      $periode = $list_bayaran->row(0)->periode;

      $sisa_bayar = $r->sisa_bayar;

      $biaya_pengurang = $list_bayaran->row(0)->potongan;

      $no_bayar = $list_bayaran->row(0)->kode_tran;

      $data = array('list_bayaran' => $list_bayaran,
                    'no_bayar' => $no_bayar,
                    'biaya_pengurang' =>$biaya_pengurang,
                    'sisa_bayar' => $sisa_bayar,
                    'kode_siswa' => $kode_siswa,
                    'nama_siswa' => $nama_siswa,
                    'tgl_bayar' => $tgl_bayar,
                    );

      //$this->load->library('pdf');
      //$customPaper = array(0,0,381.89,595.28);
      //$this->pdf->setPaper('A4', 'portrait');
      //$this->pdf->load_view('admin/page/c_tran_siswa/cetak',$data);
      //$this->load->view('admin/page/c_tran_siswa/cetak',$data);

      $this->load->library('fpdf2');

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      // $pdf->SetXY(5, 5);
      $pdf->Image(base_url().'assets/kop.JPG',10,2,200,35,'JPG');
      $pdf->SetFont("Arial","B","10");
      $pdf->setX(10);
      // $pdf->Cell(200,5,'Yayasan Pendidikan Islam Ashabulyamin Pabuaran - Cianjur',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'PONDOK PESANTREN TERPADU',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ASHABULYAMIN',0,1,'C');
      // $pdf->SetFont("Arial","","8");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'Jl. KH. Saleh Rt 004/004 Kelurahan Sayang Kec. Cianjur Kab. Cianjur 43213 HP. 081908453401',0,1,'C');
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ashabulyamin.sch.id',0,1,'C');
      // $pdf->SetLineWidth(10);
      $pdf->Line(200,37,10,37);
      $pdf->Cell(200,30,'',0,1);
      $pdf->Cell(200,5,'Bukti Pembayaran',0,1,'C');
      $pdf->Cell(200,5,'No : '.$no_bayar,0,1,'C');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(10,5,'Tanggal    : '.$tgl_bayar,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'NIK           : '.$kode_siswa,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'Nama       : '.$nama_siswa,0,1,'L');
      $pdf->Cell(105,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(20,6,'No',1,0);
      $pdf->Cell(130,6,'Pembayaran',1,0);
      $pdf->Cell(35,6,'Jumlah',1,1);

      $list_result =  $list_bayaran->result();
      $no=1;
      $total_bayar=0;

      foreach ($list_result as $row) {
        // code...
        $pdf->setX(10);
        $pdf->Cell(20,6,$no,1,0);
        $pdf->Cell(130,6,$row->nama_bayaran,1,0);
        $pdf->Cell(35,6,number_format($row->nominal),1,1,'R');
        $total_bayar += $row->nominal;
        $no++;
      }



      $pdf->Cell(105,3,'',0,1);
      $pdf->setX(140);
      $pdf->Cell(45,6,'Jumlah Bayar :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($total_bayar),0,1,'R');
      $pdf->setX(140);
      $pdf->Cell(45,6,'Sisa Tagihan :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($sisa_bayar),0,1,'R');

      $pdf->Cell(105,20,'',0,1);
      $pdf->setX(150);
      $pdf->Cell(45,5,'Admin,',0,1,'C');
      $pdf->setX(150);
      $pdf->Cell(45,6,$this->session->userdata('ses_nama_karyawan'),0,1,'C');

      $pdf->Output();


    }

    public function cetak_tahunan($id_tran)
    {

      $list_bayaran = $this->M_tran_siswa->list_bayaran_cetak($id_tran);
      //$semester = $this->M_tran_siswa->get_semester_by_code($list_bayaran->row(0)->periode,$list_bayaran->row(0)->id_tahun_ajaran);
      $ajaran = $this->M_tahun_ajaran->get_by_id($list_bayaran->row(0)->id_tahun_ajaran);

      $r = $this->M_tran_siswa->get_tagihan_per_periode(
        $list_bayaran->row(0)->periode,
        $list_bayaran->row(0)->id_siswa,
        1,
        $ajaran->tgl_akhir,
        $list_bayaran->row(0)->periode
        );

      $tgl_bayar = $list_bayaran->row(0)->tgl_bayar;
      $kode_siswa = $list_bayaran->row(0)->kode_siswa;
      $nama_siswa = $list_bayaran->row(0)->nama_siswa;

      $periode = $list_bayaran->row(0)->periode;

      $sisa_bayar = $r->sisa_bayar;

      $biaya_pengurang = $list_bayaran->row(0)->potongan;

      $no_bayar = $list_bayaran->row(0)->kode_tran;

      // $data = array('list_bayaran' => $list_bayaran,
      //               'no_bayar' => $no_bayar,
      //               'biaya_pengurang' =>$biaya_pengurang,
      //               'sisa_bayar' => $sisa_bayar,
      //               'kode_siswa' => $kode_siswa,
      //               'nama_siswa' => $nama_siswa,
      //               'tgl_bayar' => $tgl_bayar,
      //               );

      // $this->load->library('pdf');
      // $customPaper = array(0,0,381.89,595.28);
      // $this->pdf->setPaper('A4', 'portrait');
      // $this->pdf->load_view('admin/page/c_tran_siswa/cetak',$data);
      //$this->load->view('admin/page/c_tran_siswa/cetak',$data);

      $this->load->library('fpdf2');

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      // $pdf->SetXY(5, 5);
      $pdf->Image(base_url().'assets/kop.JPG',10,2,200,35,'JPG');
      $pdf->SetFont("Arial","B","10");
      $pdf->setX(10);
      // $pdf->Cell(200,5,'Yayasan Pendidikan Islam Ashabulyamin Pabuaran - Cianjur',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'PONDOK PESANTREN TERPADU',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ASHABULYAMIN',0,1,'C');
      // $pdf->SetFont("Arial","","8");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'Jl. KH. Saleh Rt 004/004 Kelurahan Sayang Kec. Cianjur Kab. Cianjur 43213 HP. 081908453401',0,1,'C');
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ashabulyamin.sch.id',0,1,'C');
      // $pdf->SetLineWidth(10);
      $pdf->Line(200,37,10,37);
      $pdf->Cell(200,30,'',0,1);
      $pdf->Cell(200,5,'Bukti Pembayaran',0,1,'C');
      $pdf->Cell(200,5,'No : '.$no_bayar,0,1,'C');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(10,5,'Tanggal    : '.$tgl_bayar,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'NIK           : '.$kode_siswa,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'Nama       : '.$nama_siswa,0,1,'L');
      $pdf->Cell(105,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(20,6,'No',1,0);
      $pdf->Cell(130,6,'Pembayaran',1,0);
      $pdf->Cell(35,6,'Jumlah',1,1);

      $list_result =  $list_bayaran->result();
      $no=1;
      $total_bayar=0;

      foreach ($list_result as $row) {
        // code...
        $pdf->setX(10);
        $pdf->Cell(20,6,$no,1,0);
        $pdf->Cell(130,6,$row->nama_bayaran,1,0);
        $pdf->Cell(35,6,number_format($row->nominal),1,1,'R');
        $total_bayar += $row->nominal;
        $no++;
      }



      $pdf->Cell(105,3,'',0,1);
      $pdf->setX(140);
      $pdf->Cell(45,6,'Jumlah Bayar :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($total_bayar),0,1,'R');
      $pdf->setX(140);
      $pdf->Cell(45,6,'Sisa Tagihan :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($sisa_bayar),0,1,'R');

      $pdf->Cell(105,20,'',0,1);
      $pdf->setX(150);
      $pdf->Cell(45,5,'Admin,',0,1,'C');
      $pdf->setX(150);
      $pdf->Cell(45,6,$this->session->userdata('ses_nama_karyawan'),0,1,'C');

      $pdf->Output();


    }

    public function cetak_tetap($id_tran)
    {

      $list_bayaran = $this->M_tran_siswa->list_bayaran_cetak($id_tran);
      //$semester = $this->M_tran_siswa->get_semester_by_code($list_bayaran->row(0)->periode,$list_bayaran->row(0)->id_tahun_ajaran);
      $ajaran = $this->M_tahun_ajaran->get_by_id($list_bayaran->row(0)->id_tahun_ajaran);

      $r = $this->M_tran_siswa->get_tagihan_per_periode(
        $list_bayaran->row(0)->periode,
        $list_bayaran->row(0)->id_siswa,
        4,
        $ajaran->tgl_akhir,
        $list_bayaran->row(0)->periode
        );

      $tgl_bayar = $list_bayaran->row(0)->tgl_bayar;
      $kode_siswa = $list_bayaran->row(0)->kode_siswa;
      $nama_siswa = $list_bayaran->row(0)->nama_siswa;

      $periode = $list_bayaran->row(0)->periode;

      $sisa_bayar = $r->sisa_bayar;

      $biaya_pengurang = $list_bayaran->row(0)->potongan;

      $no_bayar = $list_bayaran->row(0)->kode_tran;

      // $data = array('list_bayaran' => $list_bayaran,
      //               'no_bayar' => $no_bayar,
      //               'biaya_pengurang' =>$biaya_pengurang,
      //               'sisa_bayar' => $sisa_bayar,
      //               'kode_siswa' => $kode_siswa,
      //               'nama_siswa' => $nama_siswa,
      //               'tgl_bayar' => $tgl_bayar,
      //               );
      //
      // $this->load->library('pdf');
      // $customPaper = array(0,0,381.89,595.28);
      // $this->pdf->setPaper('A4', 'portrait');
      // $this->pdf->load_view('admin/page/c_tran_siswa/cetak',$data);
      //$this->load->view('admin/page/c_tran_siswa/cetak',$data);

      $this->load->library('fpdf2');

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      // $pdf->SetXY(5, 5);
      $pdf->Image(base_url().'assets/kop.JPG',10,2,200,35,'JPG');
      $pdf->SetFont("Arial","B","10");
      $pdf->setX(10);
      // $pdf->Cell(200,5,'Yayasan Pendidikan Islam Ashabulyamin Pabuaran - Cianjur',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'PONDOK PESANTREN TERPADU',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ASHABULYAMIN',0,1,'C');
      // $pdf->SetFont("Arial","","8");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'Jl. KH. Saleh Rt 004/004 Kelurahan Sayang Kec. Cianjur Kab. Cianjur 43213 HP. 081908453401',0,1,'C');
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ashabulyamin.sch.id',0,1,'C');
      // $pdf->SetLineWidth(10);
      $pdf->Line(200,37,10,37);
      $pdf->Cell(200,30,'',0,1);
      $pdf->Cell(200,5,'Bukti Pembayaran',0,1,'C');
      $pdf->Cell(200,5,'No : '.$no_bayar,0,1,'C');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(10,5,'Tanggal    : '.$tgl_bayar,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'NIK           : '.$kode_siswa,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'Nama       : '.$nama_siswa,0,1,'L');
      $pdf->Cell(105,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(20,6,'No',1,0);
      $pdf->Cell(130,6,'Pembayaran',1,0);
      $pdf->Cell(35,6,'Jumlah',1,1);

      $list_result =  $list_bayaran->result();
      $no=1;
      $total_bayar=0;

      foreach ($list_result as $row) {
        // code...
        $pdf->setX(10);
        $pdf->Cell(20,6,$no,1,0);
        $pdf->Cell(130,6,$row->nama_bayaran,1,0);
        $pdf->Cell(35,6,number_format($row->nominal),1,1,'R');
        $total_bayar += $row->nominal;
        $no++;
      }



      $pdf->Cell(105,3,'',0,1);
      $pdf->setX(140);
      $pdf->Cell(45,6,'Jumlah Bayar :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($total_bayar),0,1,'R');
      $pdf->setX(140);
      $pdf->Cell(45,6,'Sisa Tagihan :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($sisa_bayar),0,1,'R');

      $pdf->Cell(105,20,'',0,1);
      $pdf->setX(150);
      $pdf->Cell(45,5,'Admin,',0,1,'C');
      $pdf->setX(150);
      $pdf->Cell(45,6,$this->session->userdata('ses_nama_karyawan'),0,1,'C');

      $pdf->Output();
    }

    public function cetak_all($periode,$id_siswa)
    {

      $list_bayaran = $this->M_tran_siswa->periode_bayaran_cetak($periode,$id_siswa);

      $r = $this->M_tran_siswa->get_tagihan_per_periode(
          $list_bayaran->row(0)->periode,
          $list_bayaran->row(0)->id_siswa,
          3,
          $list_bayaran->row(0)->periode,
          $list_bayaran->row(0)->periode
        );


      $kode_siswa = $list_bayaran->row(0)->kode_siswa;
      $nama_siswa = $list_bayaran->row(0)->nama_siswa;

      //$periode = $list_bayaran->row(0)->periode;

      $sisa_bayar = $r->sisa_bayar;

      //$biaya_pengurang = $list_bayaran->row(0)->potongan;

      //$no_bayar = $list_bayaran->row(0)->kode_tran;

      // $data = array('list_bayaran' => $list_bayaran,
      //               //'biaya_pengurang' =>$biaya_pengurang,
      //               'sisa_bayar' => $sisa_bayar,
      //               'kode_siswa' => $kode_siswa,
      //               'nama_siswa' => $nama_siswa,
      //               'periode' => $periode,
      //               );
      //
      // $this->load->library('pdf');
      // $customPaper = array(0,0,381.89,595.28);
      // $this->pdf->setPaper('A4', 'portrait');
      // $this->pdf->load_view('admin/page/c_tran_siswa/cetak_all',$data);
      //$this->load->view('admin/page/c_tran_siswa/cetak_all',$data);

      $this->load->library('fpdf2');

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      // $pdf->SetXY(5, 5);
      $pdf->Image(base_url().'assets/kop.JPG',10,2,200,35,'JPG');
      $pdf->SetFont("Arial","B","10");
      $pdf->setX(10);
      // $pdf->Cell(200,5,'Yayasan Pendidikan Islam Ashabulyamin Pabuaran - Cianjur',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'PONDOK PESANTREN TERPADU',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ASHABULYAMIN',0,1,'C');
      // $pdf->SetFont("Arial","","8");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'Jl. KH. Saleh Rt 004/004 Kelurahan Sayang Kec. Cianjur Kab. Cianjur 43213 HP. 081908453401',0,1,'C');
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ashabulyamin.sch.id',0,1,'C');
      // $pdf->SetLineWidth(10);
      $pdf->Line(200,37,10,37);
      $pdf->Cell(200,30,'',0,1);
      $pdf->Cell(200,5,'Bukti Pembayaran',0,1,'C');
      $pdf->Cell(200,5,'Periode : '.$periode,0,1,'C');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(10,5,'NIK           : '.$kode_siswa,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'Nama       : '.$nama_siswa,0,1,'L');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(20,6,'No',1,0);
      $pdf->Cell(130,6,'Pembayaran',1,0);
      $pdf->Cell(35,6,'Jumlah',1,1);

      $list_result =  $list_bayaran->result();
      $no=1;
      $total_bayar=0;

      foreach ($list_result as $row) {
        // code...
        $pdf->setX(10);
        $pdf->Cell(20,6,$no,1,0);
        $pdf->Cell(130,6,$row->nama_bayaran,1,0);
        $pdf->Cell(35,6,number_format($row->nominal),1,1,'R');
        $total_bayar += $row->nominal;
        $no++;
      }



      $pdf->Cell(105,3,'',0,1);
      $pdf->setX(140);
      $pdf->Cell(45,6,'Jumlah Bayar :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($total_bayar),0,1,'R');
      $pdf->setX(140);
      $pdf->Cell(45,6,'Sisa Tagihan :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($sisa_bayar),0,1,'R');
      $pdf->Cell(105,20,'',0,1);
      $pdf->setX(150);
      $pdf->Cell(45,5,'Bendahara,',0,1,'C');
      $pdf->setX(150);
      $pdf->Cell(45,6,$this->session->userdata('ses_nama_karyawan'),0,1,'C');

      $pdf->Output();
    }

    public function cetak_semester_all($kode_semester,$id_siswa)
    {

      $list_bayaran = $this->M_tran_siswa->periode_bayaran_cetak($kode_semester,$id_siswa);

      $r = $this->M_tran_siswa->get_tagihan_per_periode(
          $list_bayaran->row(0)->periode,
          $list_bayaran->row(0)->id_siswa,
          2,
          $list_bayaran->row(0)->akhir_tgl,
          $kode_semester
        );


      $kode_siswa = $list_bayaran->row(0)->kode_siswa;
      $nama_siswa = $list_bayaran->row(0)->nama_siswa;

      $periode = $list_bayaran->row(0)->nama_semester;

      $sisa_bayar = $r->sisa_bayar;

      //$biaya_pengurang = $list_bayaran->row(0)->potongan;

      //$no_bayar = $list_bayaran->row(0)->kode_tran;

      // $data = array('list_bayaran' => $list_bayaran,
      //               //'biaya_pengurang' =>$biaya_pengurang,
      //               'sisa_bayar' => $sisa_bayar,
      //               'kode_siswa' => $kode_siswa,
      //               'nama_siswa' => $nama_siswa,
      //               'periode' => $list_bayaran->row(0)->nama_semester,
      //               );
      //
      // $this->load->library('pdf');
      // $customPaper = array(0,0,381.89,595.28);
      // $this->pdf->setPaper('A4', 'portrait');
      // $this->pdf->load_view('admin/page/c_tran_siswa/cetak_all',$data);
      //$this->load->view('admin/page/c_tran_siswa/cetak_all',$data);

      $this->load->library('fpdf2');

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      // $pdf->SetXY(5, 5);
      $pdf->Image(base_url().'assets/kop.JPG',10,2,200,35,'JPG');
      $pdf->SetFont("Arial","B","10");
      $pdf->setX(10);
      // $pdf->Cell(200,5,'Yayasan Pendidikan Islam Ashabulyamin Pabuaran - Cianjur',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'PONDOK PESANTREN TERPADU',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ASHABULYAMIN',0,1,'C');
      // $pdf->SetFont("Arial","","8");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'Jl. KH. Saleh Rt 004/004 Kelurahan Sayang Kec. Cianjur Kab. Cianjur 43213 HP. 081908453401',0,1,'C');
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ashabulyamin.sch.id',0,1,'C');
      // $pdf->SetLineWidth(10);
      $pdf->Line(200,37,10,37);
      $pdf->Cell(200,30,'',0,1);
      $pdf->Cell(200,5,'Bukti Pembayaran',0,1,'C');
      $pdf->Cell(200,5,'Periode : '.$periode,0,1,'C');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(10,5,'NIK           : '.$kode_siswa,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'Nama       : '.$nama_siswa,0,1,'L');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(20,6,'No',1,0);
      $pdf->Cell(130,6,'Pembayaran',1,0);
      $pdf->Cell(35,6,'Jumlah',1,1);

      $list_result =  $list_bayaran->result();
      $no=1;
      $total_bayar=0;

      foreach ($list_result as $row) {
        // code...
        $pdf->setX(10);
        $pdf->Cell(20,6,$no,1,0);
        $pdf->Cell(130,6,$row->nama_bayaran,1,0);
        $pdf->Cell(35,6,number_format($row->nominal),1,1,'R');
        $total_bayar += $row->nominal;
        $no++;
      }



      $pdf->Cell(105,3,'',0,1);
      $pdf->setX(140);
      $pdf->Cell(45,6,'Jumlah Bayar :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($total_bayar),0,1,'R');
      $pdf->setX(140);
      $pdf->Cell(45,6,'Sisa Tagihan :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($sisa_bayar),0,1,'R');

      $pdf->Cell(105,20,'',0,1);
      $pdf->setX(150);
      $pdf->Cell(45,5,'Admin,',0,1,'C');
      $pdf->setX(150);
      $pdf->Cell(45,6,$this->session->userdata('ses_nama_karyawan'),0,1,'C');

      $pdf->Output();

    }

    public function cetak_tahunan_all($kode_semester,$id_siswa)
    {

      $list_bayaran = $this->M_tran_siswa->periode_bayaran_cetak($kode_semester,$id_siswa);

      $r = $this->M_tran_siswa->get_tagihan_per_periode(
          $list_bayaran->row(0)->periode,
          $list_bayaran->row(0)->id_siswa,
          1,
          $list_bayaran->row(0)->akhir_tgl,
          $kode_semester
        );


      $kode_siswa = $list_bayaran->row(0)->kode_siswa;
      $nama_siswa = $list_bayaran->row(0)->nama_siswa;

      $periode = $list_bayaran->row(0)->nama_ajaran;

      $sisa_bayar = $r->sisa_bayar;

      //$biaya_pengurang = $list_bayaran->row(0)->potongan;

      //$no_bayar = $list_bayaran->row(0)->kode_tran;

      // $data = array('list_bayaran' => $list_bayaran,
      //               //'biaya_pengurang' =>$biaya_pengurang,
      //               'sisa_bayar' => $sisa_bayar,
      //               'kode_siswa' => $kode_siswa,
      //               'nama_siswa' => $nama_siswa,
      //               'periode' => $list_bayaran->row(0)->nama_semester,
      //               );
      //
      // $this->load->library('pdf');
      // $customPaper = array(0,0,381.89,595.28);
      // $this->pdf->setPaper('A4', 'portrait');
      // $this->pdf->load_view('admin/page/c_tran_siswa/cetak_all',$data);
      //$this->load->view('admin/page/c_tran_siswa/cetak_all',$data);

      $this->load->library('fpdf2');

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      // $pdf->SetXY(5, 5);
      $pdf->Image(base_url().'assets/kop.JPG',10,2,200,35,'JPG');
      $pdf->SetFont("Arial","B","10");
      $pdf->setX(10);
      // $pdf->Cell(200,5,'Yayasan Pendidikan Islam Ashabulyamin Pabuaran - Cianjur',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'PONDOK PESANTREN TERPADU',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ASHABULYAMIN',0,1,'C');
      // $pdf->SetFont("Arial","","8");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'Jl. KH. Saleh Rt 004/004 Kelurahan Sayang Kec. Cianjur Kab. Cianjur 43213 HP. 081908453401',0,1,'C');
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ashabulyamin.sch.id',0,1,'C');
      // $pdf->SetLineWidth(10);
      $pdf->Line(200,37,10,37);
      $pdf->Cell(200,30,'',0,1);
      $pdf->Cell(200,5,'Bukti Pembayaran',0,1,'C');
      $pdf->Cell(200,5,'Periode : '.$periode,0,1,'C');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(10,5,'NIK           : '.$kode_siswa,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'Nama       : '.$nama_siswa,0,1,'L');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(20,6,'No',1,0);
      $pdf->Cell(130,6,'Pembayaran',1,0);
      $pdf->Cell(35,6,'Jumlah',1,1);

      $list_result =  $list_bayaran->result();
      $no=1;
      $total_bayar=0;

      foreach ($list_result as $row) {
        // code...
        $pdf->setX(10);
        $pdf->Cell(20,6,$no,1,0);
        $pdf->Cell(130,6,$row->nama_bayaran,1,0);
        $pdf->Cell(35,6,number_format($row->nominal),1,1,'R');
        $total_bayar += $row->nominal;
        $no++;
      }



      $pdf->Cell(105,3,'',0,1);
      $pdf->setX(140);
      $pdf->Cell(45,6,'Jumlah Bayar :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($total_bayar),0,1,'R');
      $pdf->setX(140);
      $pdf->Cell(45,6,'Sisa Tagihan :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($sisa_bayar),0,1,'R');

      $pdf->Cell(105,20,'',0,1);
      $pdf->setX(150);
      $pdf->Cell(45,5,'Admin,',0,1,'C');
      $pdf->setX(150);
      $pdf->Cell(45,6,$this->session->userdata('ses_nama_karyawan'),0,1,'C');

      $pdf->Output();
    }

    public function cetak_tetap_all($kode_semester,$id_siswa)
    {

      $list_bayaran = $this->M_tran_siswa->periode_bayaran_cetak($kode_semester,$id_siswa);

      $r = $this->M_tran_siswa->get_tagihan_per_periode(
          $list_bayaran->row(0)->periode,
          $list_bayaran->row(0)->id_siswa,
          4,
          $list_bayaran->row(0)->akhir_tgl,
          $kode_semester
        );


      $kode_siswa = $list_bayaran->row(0)->kode_siswa;
      $nama_siswa = $list_bayaran->row(0)->nama_siswa;

      $periode = $list_bayaran->row(0)->nama_ajaran;

      $sisa_bayar = $r->sisa_bayar;

      //$biaya_pengurang = $list_bayaran->row(0)->potongan;

      //$no_bayar = $list_bayaran->row(0)->kode_tran;

      // $data = array('list_bayaran' => $list_bayaran,
      //               //'biaya_pengurang' =>$biaya_pengurang,
      //               'sisa_bayar' => $sisa_bayar,
      //               'kode_siswa' => $kode_siswa,
      //               'nama_siswa' => $nama_siswa,
      //               'periode' => $list_bayaran->row(0)->nama_semester,
      //               );
      //
      // $this->load->library('pdf');
      // $customPaper = array(0,0,381.89,595.28);
      // $this->pdf->setPaper('A4', 'portrait');
      // $this->pdf->load_view('admin/page/c_tran_siswa/cetak_all',$data);
      //$this->load->view('admin/page/c_tran_siswa/cetak_all',$data);

      $this->load->library('fpdf2');

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      // $pdf->SetXY(5, 5);
      $pdf->Image(base_url().'assets/kop.JPG',10,2,200,35,'JPG');
      $pdf->SetFont("Arial","B","10");
      $pdf->setX(10);
      // $pdf->Cell(200,5,'Yayasan Pendidikan Islam Ashabulyamin Pabuaran - Cianjur',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'PONDOK PESANTREN TERPADU',0,1,'C');
      // $pdf->SetFont("Arial","B","12");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ASHABULYAMIN',0,1,'C');
      // $pdf->SetFont("Arial","","8");
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'Jl. KH. Saleh Rt 004/004 Kelurahan Sayang Kec. Cianjur Kab. Cianjur 43213 HP. 081908453401',0,1,'C');
      // $pdf->setX(10);
      // $pdf->Cell(200,5,'ashabulyamin.sch.id',0,1,'C');
      // $pdf->SetLineWidth(10);
      $pdf->Line(200,37,10,37);
      $pdf->Cell(200,30,'',0,1);
      $pdf->Cell(200,5,'Bukti Pembayaran',0,1,'C');
      $pdf->Cell(200,5,'Periode : '.$periode,0,1,'C');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(10,5,'NIK           : '.$kode_siswa,0,1,'L');
      $pdf->setX(10);
      $pdf->Cell(10,5,'Nama       : '.$nama_siswa,0,1,'L');
      $pdf->Cell(200,3,'',0,1);

      $pdf->setX(10);
      $pdf->Cell(20,6,'No',1,0);
      $pdf->Cell(130,6,'Pembayaran',1,0);
      $pdf->Cell(35,6,'Jumlah',1,1);

      $list_result =  $list_bayaran->result();
      $no=1;
      $total_bayar=0;

      foreach ($list_result as $row) {
        // code...
        $pdf->setX(10);
        $pdf->Cell(20,6,$no,1,0);
        $pdf->Cell(130,6,$row->nama_bayaran,1,0);
        $pdf->Cell(35,6,number_format($row->nominal),1,1,'R');
        $total_bayar += $row->nominal;
        $no++;
      }

      $pdf->Cell(105,3,'',0,1);
      $pdf->setX(140);
      $pdf->Cell(45,6,'Jumlah Bayar :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($total_bayar),0,1,'R');
      $pdf->setX(140);
      $pdf->Cell(45,6,'Sisa Tagihan :   ',0,0,'L');
      $pdf->Cell(10,6,'Rp. '.number_format($sisa_bayar),0,1,'R');

      $pdf->Cell(105,20,'',0,1);
      $pdf->setX(150);
      $pdf->Cell(45,5,'Admin,',0,1,'C');
      $pdf->setX(150);
      $pdf->Cell(45,6,$this->session->userdata('ses_nama_karyawan'),0,1,'C');

      $pdf->Output();
    }



    public function delete($id)
    {
        $row = $this->M_tran_siswa->get_by_id($id);

        if ($row) {
            $this->M_tran_siswa->delete($id);
            $this->M_tran_siswa->delete_detail($row->kode_tran);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('detail-bayaran/'.$row->id_siswa));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('detail-bayaran/'.$row->id_siswa));
        }
    }

    public function _rules()
    {
    	$this->form_validation->set_rules('id_tahun_ajaran', 'id tahun ajaran', 'trim|required');
    	$this->form_validation->set_rules('id_kelas', 'id kelas', 'trim|required');
    	$this->form_validation->set_rules('id_siswa', 'id siswa', 'trim|required');
    	$this->form_validation->set_rules('id_bayaran_siswa', 'id bayaran siswa', 'trim|required');
    	$this->form_validation->set_rules('kode_tran', 'kode tran', 'trim|required');
    	$this->form_validation->set_rules('periode', 'periode', 'trim|required');
    	$this->form_validation->set_rules('tgl_bayar', 'tgl bayar', 'trim|required');
    	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
    	$this->form_validation->set_rules('tgl_ins', 'tgl ins', 'trim|required');
    	$this->form_validation->set_rules('tgl_updt', 'tgl updt', 'trim|required');
    	$this->form_validation->set_rules('user_updt', 'user updt', 'trim|required');

    	$this->form_validation->set_rules('id_tran', 'id_tran', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file C_tran_siswa.php */
/* Location: ./application/controllers/C_tran_siswa.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-23 15:57:13 */
/* http://harviacode.com */
