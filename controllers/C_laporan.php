<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_laporan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_laporan'));
    }

    public function index()
    {

      if(($this->session->userdata('ses_username') == null) or ($this->session->userdata('ses_pass') == null))
      {
        header('Location: '.base_url().'login');
      } else {

        if((!empty($_GET['tgl_from'])) && ($_GET['tgl_from']!= "")  )
        {
          $datefrom = $_GET['tgl_from'];
        }
        else
        {
          $datefrom = date('Y-m-01');
        }

        if((!empty($_GET['tgl_to'])) && ($_GET['tgl_to']!= "")  )
        {
          $dateto = $_GET['tgl_to'];
        }
        else
        {
          $dateto = date('Y-m-t');
        }

        if((!empty($_GET['referensi'])) && ($_GET['referensi']!= "")  )
        {
          $referensi = $_GET['referensi'];
        }
        else
        {
          $referensi = '';
        }



        $list_laporan = $this->M_laporan->laporan_keuangan($datefrom,$dateto,$referensi);
        $saldo = $this->M_laporan->laporan_saldo_keuangan($datefrom,$dateto,$referensi);

        if(!empty($saldo))
        {
          $saldo_debit = $saldo->debit;
          $saldo_kredit = $saldo->kredit;
        } else {
          $saldo_debit = 0;
          $saldo_kredit = 0;
        }

        $data = array(
            'list_laporan' => $list_laporan,
            'datefrom' => $datefrom,
            'dateto' => $dateto,
            'saldo_debit' => $saldo_debit,
            'saldo_kredit' => $saldo_kredit,
            'ref' => $referensi,
          );

        $this->load->view('admin/page/c_laporan/laporan_neraca.html',$data);
      }
    }

    function cetak_excel()
    {
      if((!empty($_GET['tgl_from'])) && ($_GET['tgl_from']!= "")  )
      {
        $datefrom = $_GET['tgl_from'];
      }
      else
      {
        $datefrom = date('Y-m-01');
      }

      if((!empty($_GET['tgl_to'])) && ($_GET['tgl_to']!= "")  )
      {
        $dateto = $_GET['tgl_to'];
      }
      else
      {
        $dateto = date('Y-m-t');
      }

      if((!empty($_GET['referensi'])) && ($_GET['referensi']!= "")  )
      {
        $referensi = $_GET['referensi'];
      }
      else
      {
        $referensi = '';
      }

      $list_laporan = $this->M_laporan->laporan_keuangan($datefrom,$dateto,$referensi);
      $saldo = $this->M_laporan->laporan_saldo_keuangan($datefrom,$dateto,$referensi);

      if(!empty($saldo))
      {
        $saldo_debit = $saldo->debit;
        $saldo_kredit = $saldo->kredit;
      } else {
        $saldo_debit = 0;
        $saldo_kredit = 0;
      }

      $data = array(
          'list_laporan' => $list_laporan,
          'datefrom' => $datefrom,
          'dateto' => $dateto,
          'saldo_debit' => $saldo_debit,
          'saldo_kredit' => $saldo_kredit,
          'ref' => $referensi,
        );

      //$this->load->view('admin/page/c_laporan/excel_laporan',$data);
      $this->load->helper('exportexcel');
      $namaFile = "laporan_keuangan.xls";
      $judul = "Laporan Keuangan";
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
      $total_debit = 0;
      $total_kredit = 0;
      $sisa = 0;

      xlsWriteLabel($tablehead, $kolomhead++, "No");
      xlsWriteLabel($tablehead, $kolomhead++, "Kode Transaksi");
      xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
      xlsWriteLabel($tablehead, $kolomhead++, "Nama Transaksi");
      xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
      xlsWriteLabel($tablehead, $kolomhead++, "Referensi");
      xlsWriteLabel($tablehead, $kolomhead++, "Debit");
      xlsWriteLabel($tablehead, $kolomhead++, "Kredit");
      xlsWriteLabel($tablehead, $kolomhead++, "Balance");

      foreach ($list_laporan as $data)
      {
          $kolombody = 0;

          //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
          xlsWriteNumber($tablebody, $kolombody++, $nourut);
          xlsWriteLabel($tablebody, $kolombody++, $data->kode_tran);
          xlsWriteLabel($tablebody, $kolombody++, $data->tgl_bayar);
          xlsWriteLabel($tablebody, $kolombody++, $data->nama_transaksi);
          xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
          xlsWriteLabel($tablebody, $kolombody++, $data->no_ref);
          xlsWriteNumber($tablebody, $kolombody++, $data->debit);
          xlsWriteNumber($tablebody, $kolombody++, $data->kredit);

          $total_debit += $data->debit;
          $total_kredit += $data->kredit;

          $tablebody++;
          $nourut++;
      }

      $sisa = $total_debit - $total_kredit;
      xlsWriteLabel($tablebody, 5,"Total");
      xlsWriteNumber($tablebody, 6,$total_debit);
      xlsWriteNumber($tablebody, 7,$total_kredit);
      xlsWriteNumber($tablebody, 8,$sisa);

      $tablebody++;

      $sisa = $saldo_debit - $saldo_kredit;
      xlsWriteLabel($tablebody, 5,"Saldo");
      xlsWriteNumber($tablebody, 6,$saldo_debit);
      xlsWriteNumber($tablebody, 7,$saldo_kredit);
      xlsWriteNumber($tablebody, 8,$sisa);

      $tablebody++;

      $sisa = ($total_debit+$saldo_debit) - ($total_kredit+$saldo_kredit);
      xlsWriteLabel($tablebody, 5,"Balance");
      xlsWriteNumber($tablebody, 6,$total_debit+$saldo_debit);
      xlsWriteNumber($tablebody, 7,$total_kredit+$saldo_kredit);
      xlsWriteNumber($tablebody, 8,$sisa);


      xlsEOF();
      exit();
    }
}
