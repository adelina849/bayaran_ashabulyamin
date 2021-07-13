<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_kelas_siswa extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    function list_kelas_siswa($ajaran,$kelas)
    {
        $query = $this->db->query("
          SELECT id_kelas_siswa, A.id_kelas,C.nama_kelas,A.id_siswa,A.id_ajaran,nama_kelas,
               kode_siswa,nama_siswa,CASE WHEN jkel = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS jkel
          FROM tb_kelas_siswa A
          LEFT JOIN tb_tahun_ajaran B
          ON A.id_ajaran = B.id_ajaran
          LEFT JOIN tb_kelas C
          ON A.id_kelas = C.id_kelas
          LEFT JOIN tb_siswa D
          ON A.id_siswa = D.id_siswa
          WHERE A.id_kelas LIKE '%".$kelas."%' AND A.id_ajaran = '".$ajaran."'
        ");

        if($query->num_rows() > 0)
        {
          return $query;
        } else {
          return false;
        }
    }

    function list_kelas_siswa_cari($ajaran,$kelas,$nik,$nama)
    {
        $query = $this->db->query("
            SELECT A.id_siswa,nama_ajaran,nama_kelas,kode_siswa,nama_siswa
            FROM tb_kelas_siswa A
            LEFT JOIN tb_tahun_ajaran B
            ON A.id_ajaran = B.id_ajaran
            LEFT JOIN tb_kelas C
            ON A.id_kelas = C.id_kelas
            LEFT JOIN tb_siswa D
            ON A.id_siswa = D.id_siswa
            WHERE A.id_ajaran LIKE '%".$ajaran."%'
              AND A.id_kelas LIKE '%".$kelas."%'
              AND D.kode_siswa LIKE '%".$nik."%'
              AND D.nama_siswa LIKE '%".$nama."%'
        ");

        if($query->num_rows() > 0)
        {
          return $query->result();
        } else {
          return false;
        }
    }

    function get_kelas_by_siswa($id_siswa)
    {
        $query = $this->db->query("
            SELECT A.id_ajaran,A.id_kelas,B.nama_kelas,C.nama_ajaran
            FROM tb_kelas_siswa A
            LEFT JOIN tb_kelas B
            ON A.id_kelas = B.id_kelas
            LEFT JOIN tb_tahun_ajaran C
            ON A.id_ajaran = C.id_ajaran
            WHERE id_siswa = '".$id_siswa."'
        ");

        if($query->num_rows() > 0)
        {
          return $query->row();
        } else {
          return false;
        }

    }

    function list_siswa_by_kelas($cari,$id_kelas,$id_ajaran)
    {
      $query = $this->db->query("
        SELECT A.id_siswa,A.kode_siswa,A.nama_siswa,A.tgl_lahir,A.jkel
        FROM tb_siswa A
        LEFT JOIN tb_kelas_siswa B
        ON A.id_siswa = B.id_siswa
        WHERE A.kode_siswa LIKE '%".$cari."%'
              AND B.id_kelas = '".$id_kelas."' AND B.id_ajaran = '".$id_ajaran."'
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }

    function list_siswa_by_bayaran($cari,$id_kelas,$id_ajaran,$id_bayaran)
    {
      $query = $this->db->query("
        SELECT A.id_siswa,A.kode_siswa,A.nama_siswa,A.tgl_lahir,A.jkel
        FROM tb_siswa A
        LEFT JOIN tb_kelas_siswa B
           ON A.id_siswa = B.id_siswa
        LEFT JOIN tb_bayaran_siswa C
           ON A.id_siswa = C.id_siswa AND B.id_kelas = C.id_kelas
              AND B.id_ajaran = C.id_tahun_ajaran AND C.id_bayaran = '".$id_bayaran."'
        WHERE A.kode_siswa LIKE '%".$cari."%'
              AND B.id_kelas = '".$id_kelas."' AND B.id_ajaran = '".$id_ajaran."'
              AND C.id_bayaran IS NULL
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }

    function list_siswa_by_pengurang($cari,$id_kelas,$id_ajaran,$id_pengurang)
    {
      $query = $this->db->query("
        SELECT A.id_siswa,A.kode_siswa,A.nama_siswa,A.tgl_lahir,A.jkel
        FROM tb_siswa A
        LEFT JOIN tb_kelas_siswa B
           ON A.id_siswa = B.id_siswa
        LEFT JOIN tb_bayaran_pengurang C
           ON A.id_siswa = C.id_siswa AND B.id_kelas = C.id_kelas
              AND B.id_ajaran = C.id_tahun_ajaran AND C.id_pengurang = '".$id_pengurang."'
        WHERE A.kode_siswa LIKE '%".$cari."%'
              AND B.id_kelas = '".$id_kelas."' AND B.id_ajaran = '".$id_ajaran."'
              AND C.id_pengurang IS NULL
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }

    function simpan($id_kelas,$id_siswa,$id_ajaran)
    {
      $query = $this->db->query("
        INSERT INTO tb_kelas_siswa
                (id_kelas,
                 id_siswa,
                 id_ajaran,
                 tgl_ins,
                 user_updt)
        VALUES ('".$id_kelas."',
            '".$id_siswa."',
            '".$id_ajaran."',
            NOW(),
            '".$this->session->userdata('ses_id_akun')."');
      ");

      return $query;
    }

    function hapus($id_kelas_siswa)
    {
      $query = $this->db->query("
        DELETE FROM tb_kelas_siswa WHERE id_kelas_siswa = '".$id_kelas_siswa."'
      ");

      return $query;
    }
}
