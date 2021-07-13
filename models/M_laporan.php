<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_laporan extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function laporan_keuangan($tgl_from,$tgl_to,$referensi)
    {
      $query = $this->db->query("
          SELECT id_tran,A.kode_tran,DATE_FORMAT(A.tgl_bayar,'%Y-%m-%d') tgl_bayar,A.nama_transaksi,A.keterangan,no_ref,
               CASE WHEN COALESCE(tot_bayaran,0) <> 0 THEN COALESCE(tot_bayaran,0)
                    WHEN COALESCE(tot_masuk,0) <> 0 THEN COALESCE(tot_masuk,0) ELSE 0 END AS debit,
               CASE WHEN COALESCE(tot_keluar,0) <> 0 THEN COALESCE(tot_keluar,0) ELSE 0 END AS kredit,
               COALESCE(E.nama_karyawan,'') nama_karyawan
          FROM
          (
            SELECT id_tran,kode_tran,tgl_bayar,CONCAT('Bayaran Periode ',periode) AS nama_transaksi,COALESCE(keterangan,'') keterangan,
                   'BAYARAN' no_ref,user_updt
            FROM tb_tran_siswa
            UNION ALL
            SELECT id_uang_masuk,no_bukti,tgl_uang_masuk,nama_uang_masuk,COALESCE(ket_uang_masuk,'') ket_uang_masuk,'UANG MASUK' no_ref,user_updt
            FROM tb_uang_masuk
            UNION ALL
            SELECT id_uang_keluar,no_uang_keluar,tgl_dikeluarkan,nama_uang_keluar,COALESCE(ket_uang_keluar,'') ket_uang_keluar,'UANG KELUAR' no_ref,user_updt
            FROM tb_uang_keluar
          ) A
          LEFT JOIN
          (
            SELECT A.kode_tran,SUM(COALESCE(B.nominal,0)) tot_bayaran
            FROM tb_tran_siswa A
            LEFT JOIN tb_d_tran_siswa B
               ON A.kode_tran = B.kode_tran
            WHERE A.tgl_bayar BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            GROUP BY A.kode_tran,A.periode
          ) B ON A.kode_tran = B.kode_tran
          LEFT JOIN
          (
            SELECT no_bukti,SUM(nominal) tot_masuk
            FROM tb_uang_masuk
            WHERE tgl_uang_masuk BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            GROUP BY no_bukti
          ) C ON A.kode_tran = C.no_bukti
          LEFT JOIN
          (
            SELECT no_uang_keluar,SUM(nominal) tot_keluar
            FROM tb_uang_keluar
            WHERE tgl_dikeluarkan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            GROUP BY no_uang_keluar
          ) D ON A.kode_tran = D.no_uang_keluar
          LEFT JOIN tb_karyawan E ON A.user_updt = E.id_karyawan
          WHERE no_ref LIKE '%".$referensi."%' AND tgl_bayar BETWEEN '".$tgl_from."' AND '".$tgl_to."'
      ");

      if($query->num_rows() > 0)
      {
        return $query->result();
      } else {
        return false;
      }
    }

    function summary_saldo($tgl_from,$tgl_to)
    {
      $query = $this->db->query("
        SELECT SUM(debit) - SUM(kredit) AS saldo
        FROM
        (
          SELECT id_tran,A.kode_tran,DATE_FORMAT(A.tgl_bayar,'%Y-%m-%d') tgl_bayar,A.nama_transaksi,A.keterangan,no_ref,
               CASE WHEN COALESCE(tot_bayaran,0) <> 0 THEN COALESCE(tot_bayaran,0)
                    WHEN COALESCE(tot_masuk,0) <> 0 THEN COALESCE(tot_masuk,0) ELSE 0 END AS debit,
               CASE WHEN COALESCE(tot_keluar,0) <> 0 THEN COALESCE(tot_keluar,0) ELSE 0 END AS kredit,
               COALESCE(E.nama_karyawan,'') nama_karyawan
          FROM
          (
            SELECT id_tran,kode_tran,tgl_bayar,CONCAT('Bayaran Periode ',periode) AS nama_transaksi,COALESCE(keterangan,'') keterangan,
                   'BAYARAN' no_ref,user_updt
            FROM tb_tran_siswa
            UNION ALL
            SELECT id_uang_masuk,no_bukti,tgl_uang_masuk,nama_uang_masuk,COALESCE(ket_uang_masuk,'') ket_uang_masuk,'UANG MASUK' no_ref,user_updt
            FROM tb_uang_masuk
            UNION ALL
            SELECT id_uang_keluar,no_uang_keluar,tgl_dikeluarkan,nama_uang_keluar,COALESCE(ket_uang_keluar,'') ket_uang_keluar,'UANG KELUAR' no_ref,user_updt
            FROM tb_uang_keluar
          ) A
          LEFT JOIN
          (
            SELECT A.kode_tran,SUM(COALESCE(B.nominal,0)) tot_bayaran
            FROM tb_tran_siswa A
            LEFT JOIN tb_d_tran_siswa B
               ON A.kode_tran = B.kode_tran
            WHERE A.tgl_bayar BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            GROUP BY A.kode_tran,A.periode
          ) B ON A.kode_tran = B.kode_tran
          LEFT JOIN
          (
            SELECT no_bukti,SUM(nominal) tot_masuk
            FROM tb_uang_masuk
            WHERE tgl_uang_masuk BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            GROUP BY no_bukti
          ) C ON A.kode_tran = C.no_bukti
          LEFT JOIN
          (
            SELECT no_uang_keluar,SUM(nominal) tot_keluar
            FROM tb_uang_keluar
            WHERE tgl_dikeluarkan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            GROUP BY no_uang_keluar
          ) D ON A.kode_tran = D.no_uang_keluar
          LEFT JOIN tb_karyawan E ON A.user_updt = E.id_karyawan
          WHERE tgl_bayar BETWEEN '".$tgl_from."' AND '".$tgl_to."'
        ) A
      ");

      if($query->num_rows() > 0)
      {
        return $query->row();
      } else {
        return false;
      }
    }

    function laporan_saldo_keuangan($tgl_from,$tgl_to,$referensi)
    {
      $query = $this->db->query("
          SELECT SUM(CASE WHEN COALESCE(tot_bayaran,0) <> 0 THEN COALESCE(tot_bayaran,0)
                WHEN COALESCE(tot_masuk,0) <> 0 THEN COALESCE(tot_masuk,0) ELSE 0 END) AS debit,
                SUM(CASE WHEN COALESCE(tot_keluar,0) <> 0 THEN COALESCE(tot_keluar,0) ELSE 0 END) AS kredit
          FROM
          (
            SELECT kode_tran,tgl_bayar,CONCAT('Bayaran Periode ',periode) AS nama_transaksi,COALESCE(keterangan,'') keterangan,
                   'BAYARAN' no_ref,user_updt
            FROM tb_tran_siswa
            UNION ALL
            SELECT no_bukti,tgl_uang_masuk,nama_uang_masuk,COALESCE(ket_uang_masuk,'') ket_uang_masuk,'UANG MASUK' no_ref,user_updt
            FROM tb_uang_masuk
            UNION ALL
            SELECT no_uang_keluar,tgl_dikeluarkan,nama_uang_keluar,COALESCE(ket_uang_keluar,'') ket_uang_keluar,'UANG KELUAR' no_ref,user_updt
            FROM tb_uang_keluar
          ) A
          LEFT JOIN
          (
            SELECT A.kode_tran,SUM(COALESCE(B.nominal,0)) tot_bayaran
            FROM tb_tran_siswa A
            LEFT JOIN tb_d_tran_siswa B
               ON A.kode_tran = B.kode_tran
            WHERE A.tgl_bayar < '".$tgl_from."'
            GROUP BY A.kode_tran,A.periode
          ) B ON A.kode_tran = B.kode_tran
          LEFT JOIN
          (
            SELECT no_bukti,SUM(nominal) tot_masuk
            FROM tb_uang_masuk
            WHERE tgl_uang_masuk < '".$tgl_from."'
            GROUP BY no_bukti
          ) C ON A.kode_tran = C.no_bukti
          LEFT JOIN
          (
            SELECT no_uang_keluar,SUM(nominal) tot_keluar
            FROM tb_uang_keluar
            WHERE tgl_dikeluarkan < '".$tgl_from."'
            GROUP BY no_uang_keluar
          ) D ON A.kode_tran = D.no_uang_keluar
          LEFT JOIN tb_karyawan E ON A.user_updt = E.id_karyawan
          WHERE no_ref LIKE '%".$referensi."%'
      ");

      if($query->num_rows() > 0)
      {
        return $query->row();
      } else {
        return false;
      }
    }
}
