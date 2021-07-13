<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_tran_siswa extends CI_Model
{

    public $table = 'tb_tran_siswa';
    public $id = 'id_tran';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id_tran,id_tahun_ajaran,id_kelas,id_siswa,id_bayaran_siswa,kode_tran,periode,tgl_bayar,keterangan,tgl_ins,tgl_updt,user_updt');
        $this->datatables->from('tb_tran_siswa');
        //add this line for join
        //$this->datatables->join('table2', 'tb_tran_siswa.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('c_tran_siswa/read/$1'),'Read')." | ".anchor(site_url('c_tran_siswa/update/$1'),'Update')." | ".anchor(site_url('c_tran_siswa/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_tran');
        return $this->datatables->generate();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    function list_bayaran_per_siswa($id_siswa,$id_kat_bayaran,$periode,$id_tran,$kode)
    {
      $query = $this->db->query("
        SELECT COALESCE(C.id_d_tran,'') id_d_tran,COALESCE(C.kode_tran,'') kode_tran,id_siswa,A.id_bayaran,nama_bayaran,A.periode,kode_periode,nominal,
               COALESCE(B.bayar,0) - COALESCE(C.bayar,0) bayar,nominal - (COALESCE(B.bayar,0) - COALESCE(C.bayar,0)) AS sisa_bayar,
               COALESCE(C.bayar,0) input_bayar
        FROM
        (
            SELECT B.id_ajaran,B.id_kelas,A.id_siswa,D.id_bayaran,D.nama_bayaran,DATE_FORMAT('".$periode."-01','%M-%y') AS periode,
                   '".$kode."' kode_periode,nominal
            FROM tb_siswa A
            LEFT JOIN tb_kelas_siswa B
            ON A.id_siswa = B.id_siswa
            LEFT JOIN
            (
            SELECT '".$kode."' periode,B.id_siswa,A.id_bayaran,A.id_tahun_ajaran,A.id_kelas
            FROM tb_bayaran_kelas A
            LEFT JOIN tb_kelas_siswa B
             ON A.id_kelas = B.id_kelas AND A.id_tahun_ajaran = B.id_ajaran
            WHERE DATE_FORMAT(mulai_berlaku,'%Y-%m') <= '".$periode."' AND B.id_siswa = '".$id_siswa."'
            UNION ALL
            SELECT '".$kode."' periode,id_siswa,id_bayaran,id_tahun_ajaran,id_kelas
            FROM tb_bayaran_siswa
            WHERE id_siswa = '".$id_siswa."' AND DATE_FORMAT(mulai_berlaku,'%Y-%m') <= '".$periode."'
            ) C ON B.id_kelas = C.id_kelas AND B.id_ajaran = C.id_tahun_ajaran
            LEFT JOIN tb_bayaran D
            ON C.id_bayaran = D.id_bayaran
            LEFT JOIN tb_kat_bayaran E
            ON D.id_kat_bayaran = E.id_kat_bayaran
            WHERE A.id_siswa = '".$id_siswa."' AND E.id_kat_bayaran = '".$id_kat_bayaran."'
          ) A
          LEFT JOIN
          (
             SELECT B.id_bayaran,id_kat_bayaran,A.periode,SUM(B.nominal) - AVG(potongan) AS bayar
             FROM tb_tran_siswa A
             LEFT JOIN tb_d_tran_siswa B
              ON A.kode_tran = B.kode_tran
             LEFT JOIN tb_bayaran C
              ON B.id_bayaran = C.id_bayaran
             WHERE id_siswa = '".$id_siswa."' AND C.id_kat_bayaran = '".$id_kat_bayaran."' AND periode = '".$kode."'
             GROUP BY B.id_bayaran,id_kat_bayaran,A.periode
          ) B ON A.id_bayaran = B.id_bayaran AND A.kode_periode = B.periode
          LEFT JOIN
          (
             SELECT B.id_d_tran,B.id_bayaran,A.kode_tran,id_kat_bayaran,A.periode,SUM(B.nominal) - AVG(potongan) AS bayar
             FROM tb_tran_siswa A
             LEFT JOIN tb_d_tran_siswa B
              ON A.kode_tran = B.kode_tran
             LEFT JOIN tb_bayaran C
              ON B.id_bayaran = C.id_bayaran
             WHERE id_siswa = '".$id_siswa."' AND C.id_kat_bayaran = '".$id_kat_bayaran."' AND periode = '".$kode."'
             AND id_tran = '".$id_tran."'
             GROUP BY B.id_bayaran,id_kat_bayaran,A.periode,B.id_d_tran,A.kode_tran
          ) C ON A.id_bayaran = C.id_bayaran AND A.kode_periode = C.periode
      ");

      if($query->num_rows() > 0)
      {
        return $query->result();
      } else {
        return false;
      }
    }

    function list_bayaran_per_siswa_semester($id_siswa,$id_kat_bayaran,$periode,$periode2,$id_tran,$kode)
    {
      $query = $this->db->query("
        SELECT COALESCE(C.id_d_tran,'') id_d_tran,COALESCE(C.kode_tran,'') kode_tran,id_siswa,A.id_bayaran,nama_bayaran,A.periode,kode_periode,nominal,
               COALESCE(B.bayar,0) - COALESCE(C.bayar,0) bayar,nominal - (COALESCE(B.bayar,0) - COALESCE(C.bayar,0)) AS sisa_bayar,
               COALESCE(C.bayar,0) input_bayar
        FROM
        (
            SELECT B.id_ajaran,B.id_kelas,A.id_siswa,D.id_bayaran,D.nama_bayaran,DATE_FORMAT('".$periode."-01','%M-%y') AS periode,
                   '".$kode."' kode_periode,nominal
            FROM tb_siswa A
            LEFT JOIN tb_kelas_siswa B
            ON A.id_siswa = B.id_siswa
            LEFT JOIN
            (
            SELECT '".$kode."' periode,B.id_siswa,A.id_bayaran,A.id_tahun_ajaran,A.id_kelas
            FROM tb_bayaran_kelas A
            LEFT JOIN tb_kelas_siswa B
             ON A.id_kelas = B.id_kelas AND A.id_tahun_ajaran = B.id_ajaran
            WHERE DATE_FORMAT(mulai_berlaku,'%Y-%m') <= '".$periode."' AND DATE_FORMAT(mulai_berlaku,'%Y-%m') >= '".$periode2."' AND B.id_siswa = '".$id_siswa."'
            UNION ALL
            SELECT '".$kode."' periode,id_siswa,id_bayaran,id_tahun_ajaran,id_kelas
            FROM tb_bayaran_siswa
            WHERE id_siswa = '".$id_siswa."' AND DATE_FORMAT(mulai_berlaku,'%Y-%m') <= '".$periode."' AND DATE_FORMAT(mulai_berlaku,'%Y-%m') >= '".$periode2."'
            ) C ON B.id_kelas = C.id_kelas AND B.id_ajaran = C.id_tahun_ajaran
            LEFT JOIN tb_bayaran D
            ON C.id_bayaran = D.id_bayaran
            LEFT JOIN tb_kat_bayaran E
            ON D.id_kat_bayaran = E.id_kat_bayaran
            WHERE A.id_siswa = '".$id_siswa."' AND E.id_kat_bayaran = '".$id_kat_bayaran."'
          ) A
          LEFT JOIN
          (
             SELECT B.id_bayaran,id_kat_bayaran,A.periode,SUM(B.nominal) - AVG(potongan) AS bayar
             FROM tb_tran_siswa A
             LEFT JOIN tb_d_tran_siswa B
              ON A.kode_tran = B.kode_tran
             LEFT JOIN tb_bayaran C
              ON B.id_bayaran = C.id_bayaran
             WHERE id_siswa = '".$id_siswa."' AND C.id_kat_bayaran = '".$id_kat_bayaran."' AND periode = '".$kode."'
             GROUP BY B.id_bayaran,id_kat_bayaran,A.periode
          ) B ON A.id_bayaran = B.id_bayaran AND A.kode_periode = B.periode
          LEFT JOIN
          (
             SELECT B.id_d_tran,B.id_bayaran,A.kode_tran,id_kat_bayaran,A.periode,SUM(B.nominal) - AVG(potongan) AS bayar
             FROM tb_tran_siswa A
             LEFT JOIN tb_d_tran_siswa B
              ON A.kode_tran = B.kode_tran
             LEFT JOIN tb_bayaran C
              ON B.id_bayaran = C.id_bayaran
             WHERE id_siswa = '".$id_siswa."' AND C.id_kat_bayaran = '".$id_kat_bayaran."' AND periode = '".$kode."'
             AND id_tran = '".$id_tran."'
             GROUP BY B.id_bayaran,id_kat_bayaran,A.periode,B.id_d_tran,A.kode_tran
          ) C ON A.id_bayaran = C.id_bayaran AND A.kode_periode = C.periode
      ");

      if($query->num_rows() > 0)
      {
        return $query->result();
      } else {
        return false;
      }
    }

    function list_transaksi_cetak($periode,$id_siswa)
    {
        $query = $this->db->query("
          SELECT A.id_tran,A.kode_tran,tgl_bayar,SUM(B.nominal) AS bayar
          FROM tb_tran_siswa A
          LEFT JOIN tb_d_tran_siswa B
          ON A.kode_tran = B.kode_tran
          WHERE periode = '".$periode."' AND A.id_siswa = '".$id_siswa."'
          GROUP BY A.id_tran,A.kode_tran,tgl_bayar
        ");

        if($query->num_rows() > 0)
        {
          return $query->result();
        } else {
          return false;
        }
    }

    function list_bayaran_cetak($id_tran)
    {
      $query= $this->db->query("
        SELECT A.id_tran,A.id_siswa,kode_siswa,A.id_tahun_ajaran,nama_siswa,A.potongan,A.kode_tran,A.periode,A.tgl_bayar,C.nama_bayaran,B.nominal
        FROM tb_tran_siswa A
        LEFT JOIN tb_d_tran_siswa B
          ON A.kode_tran = B.kode_tran
        LEFT JOIN tb_bayaran C
          ON B.id_bayaran = C.id_bayaran
        LEFT JOIN tb_siswa D
          ON A.id_siswa = D.id_siswa
        WHERE id_tran = '".$id_tran."'
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }

    function periode_bayaran_cetak($periode,$id_siswa)
    {
      $query= $this->db->query("
        SELECT A.id_siswa,kode_siswa,nama_siswa,A.periode,nama_semester,nama_ajaran,F.akhir_tgl,C.nama_bayaran,SUM(B.nominal) nominal
        FROM tb_tran_siswa A
        LEFT JOIN tb_d_tran_siswa B
          ON A.kode_tran = B.kode_tran
        LEFT JOIN tb_bayaran C
          ON B.id_bayaran = C.id_bayaran
        LEFT JOIN tb_siswa D
          ON A.id_siswa = D.id_siswa
        LEFT JOIN tb_semester E
          ON A.periode = E.kode_semester
        LEFT JOIN tb_tahun_semester F
          ON E.id_semester = F.id_semester AND A.id_tahun_ajaran = F.id_tahun_ajaran
        LEFT JOIN tb_tahun_ajaran G
          on A.id_tahun_ajaran = G.id_ajaran
        WHERE A.periode = '".$periode."' AND A.id_siswa = '".$id_siswa."'
        GROUP BY A.id_siswa,kode_siswa,nama_siswa,A.periode,nama_semester,F.akhir_tgl,C.nama_bayaran,nama_ajaran
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }

    function get_tagihan_per_periode($periode,$id_siswa,$id_kat_bayaran,$batas,$kode)
    {
      $query = $this->db->query("
          SELECT '".$periode."' as kode_semester,id_kat_bayaran,id_tran,A.id_siswa,periode,biaya,bayar,biaya - bayar AS sisa_bayar,
               CASE WHEN (biaya - bayar) = biaya THEN 'Belum Bayar' WHEN (biaya - bayar) < biaya AND (biaya - bayar) > 0 THEN 'Belum Lunas' ELSE 'Lunas' END AS status_bayar
               -- ,nama_ajaran,nama_kelas,kode_siswa,nama_siswa
          FROM
          (
            SELECT '".$id_kat_bayaran."' AS id_kat_bayaran,D.id_tran,A.id_siswa,A.periode,SUM(COALESCE(nominal,0)) - AVG(COALESCE(C.biaya_pengurang,0)) biaya,COALESCE(bayar,0) bayar
            FROM
            (
             SELECT '".$kode."' periode,B.id_siswa,A.id_bayaran,A.id_tahun_ajaran,A.id_kelas
             FROM tb_bayaran_kelas A
             LEFT JOIN tb_kelas_siswa B
             ON A.id_kelas = B.id_kelas AND A.id_tahun_ajaran = B.id_ajaran
             WHERE DATE_FORMAT(mulai_berlaku,'%Y-%m') <= '".$batas."' AND B.id_siswa = '".$id_siswa."'
             UNION ALL
             SELECT '".$kode."' periode,id_siswa,id_bayaran,id_tahun_ajaran,id_kelas
             FROM tb_bayaran_siswa
             WHERE id_siswa = '".$id_siswa."' AND DATE_FORMAT(mulai_berlaku,'%Y-%m') <= '".$batas."'
            ) A
            LEFT JOIN tb_bayaran B ON A.id_bayaran = B.id_bayaran AND B.id_kat_bayaran = '".$id_kat_bayaran."'
            LEFT JOIN
            (
              SELECT A.id_siswa,SUM(nominal) biaya_pengurang
              FROM tb_bayaran_pengurang A
              LEFT JOIN tb_pengurang_bayaran B
               ON A.id_pengurang = B.id_pengurang
              WHERE A.id_siswa = '".$id_siswa."' AND id_kat_bayaran = '".$id_kat_bayaran."'
            ) C ON A.id_siswa = C.id_siswa
            LEFT JOIN
            (
             SELECT id_kat_bayaran,A.id_tran,A.periode,SUM(B.nominal) - AVG(potongan) AS bayar
             FROM tb_tran_siswa A
             LEFT JOIN tb_d_tran_siswa B
              ON A.kode_tran = B.kode_tran
             LEFT JOIN tb_bayaran C
              ON B.id_bayaran = C.id_bayaran
             WHERE id_siswa = '".$id_siswa."' AND C.id_kat_bayaran = '".$id_kat_bayaran."' AND periode = '".$kode."'
             GROUP BY id_kat_bayaran,A.id_tran,A.periode
            ) D ON A.periode COLLATE utf8_general_ci = D.periode
          ) A
          --  LEFT JOIN tb_kelas_siswa B
          --  ON A.id_siswa = B.id_siswa
          -- LEFT JOIN tb_tahun_ajaran C
          --     ON B.id_ajaran = C.id_ajaran
          -- LEFT JOIN tb_kelas E
          --    ON E.id_kelas = B.id_kelas
          -- LEFT JOIN tb_siswa F
          --    ON F.id_siswa = A.id_siswa
      ");

      if($query->num_rows() > 0)
      {
        return $query->row();
      } else {
        return false;
      }

    }

    function list_laporan_bayaran_siswa($id_siswa,$id_kat_bayaran,$mulai,$akhir)
    {
      $query = $this->db->query("
          SELECT id_kat_bayaran,COALESCE(id_tran,0) id_tran,A.nama_periode,COALESCE(tgl_bayar,'') tgl_bayar,COALESCE(biaya,0) biaya,
                 CASE WHEN COALESCE(biaya,0) <> 0 THEN 'Lunas' ELSE 'Belum Bayar' END AS stat_bayar
          FROM
          (
            SELECT nama_periode FROM tb_periode
            WHERE nama_periode BETWEEN  DATE_FORMAT('".$mulai."','%Y-%m') AND DATE_FORMAT('".$akhir."','%Y-%m')
          ) A
          LEFT JOIN
          (
            SELECT id_kat_bayaran,id_tran,tgl_bayar,A.id_siswa,A.periode,SUM(B.nominal) - AVG(potongan) AS biaya
            FROM tb_tran_siswa A
            LEFT JOIN tb_d_tran_siswa B
            ON A.kode_tran = B.kode_tran
            LEFT JOIN tb_bayaran C
            ON B.id_bayaran = C.id_bayaran
            WHERE id_siswa = '".$id_siswa."' AND C.id_kat_bayaran = '".$id_kat_bayaran."'
          ) B ON A.nama_periode = B.periode
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }

    }

    function get_list_periode($mulai,$akhir)
    {
      $query = $this->db->query("
        SELECT nama_periode
        FROM tb_periode
        WHERE nama_periode BETWEEN  DATE_FORMAT('".$mulai."','%Y-%m') AND DATE_FORMAT('".$akhir."','%Y-%m')
      ");

      if($query->num_rows() > 0)
      {
        return $query->result();
      } else {
        return false;
      }
    }

    function get_list_semester($id_ajaran)
    {
      $query = $this->db->query("
        SELECT * FROM tb_tahun_semester A
        LEFT JOIN tb_semester B
          ON A.id_semester = B.id_semester
        WHERE id_tahun_ajaran = '".$id_ajaran."'
      ");

      if($query->num_rows() > 0)
      {
        return $query->result();
      } else {
        return false;
      }
    }

    function get_semester_by_code($kode,$id_ajaran)
    {
      $query = $this->db->query("
        SELECT * FROM tb_tahun_semester A
        LEFT JOIN tb_semester B
        ON A.id_semester = B.id_semester
        WHERE id_tahun_ajaran = '".$id_ajaran."' AND kode_semester = '".$kode."'
      ");

      if($query->num_rows() > 0)
      {
        return $query->row();
      } else {
        return false;
      }
    }


    function get_no_bayar()
    {
      $query = $this->db->query("
          SELECT CONCAT(ORD,'/ASY/',DATE_FORMAT(NOW(),'%m'),'/',DATE_FORMAT(NOW(),'%y')) AS no_bayar
          FROM
          (
          SELECT CASE
           WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('00',CAST(ORD AS CHAR))
           WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('0',CAST(ORD AS CHAR))
           WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('',CAST(ORD AS CHAR))
           WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
           ELSE CONCAT('000',CAST(ORD AS CHAR))
           END AS ORD
          FROM
          (
           SELECT COALESCE(MAX(CAST(LEFT(kode_tran,5) AS UNSIGNED)) + 1,1) AS ORD
           FROM tb_tran_siswa
           WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
          ) AS A
          ) AS AA
      ");

      if($query->num_rows() > 0)
      {
        return $query->row();
      } else {
        return false;
      }
    }

    function history_transaksi($id_siswa)
    {
        $query = $this->db->query("
            SELECT id_tran,A.kode_tran,tgl_bayar,D.nama_kat_bayaran,SUM(B.nominal) nominal
            FROM tb_tran_siswa A
            LEFT JOIN tb_d_tran_siswa B
            ON A.kode_tran = B.kode_tran
            LEFT JOIN tb_bayaran C
            ON B.id_bayaran = C.id_bayaran
            LEFT JOIN tb_kat_bayaran D
            ON C.id_kat_bayaran = D.id_kat_bayaran
            WHERE id_siswa = '".$id_siswa."'
            GROUP BY id_tran,A.kode_tran,tgl_bayar,D.nama_kat_bayaran
            ORDER BY tgl_bayar
        ");

        if($query->num_rows() > 0)
        {
          return $query->result();
        } else {
          return false;
        }
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id_tran', $q);
	$this->db->or_like('id_tahun_ajaran', $q);
	$this->db->or_like('id_kelas', $q);
	$this->db->or_like('id_siswa', $q);
	$this->db->or_like('id_bayaran_siswa', $q);
	$this->db->or_like('kode_tran', $q);
	$this->db->or_like('periode', $q);
	$this->db->or_like('tgl_bayar', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->or_like('tgl_ins', $q);
	$this->db->or_like('tgl_updt', $q);
	$this->db->or_like('user_updt', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id_tran', $q);
	$this->db->or_like('id_tahun_ajaran', $q);
	$this->db->or_like('id_kelas', $q);
	$this->db->or_like('id_siswa', $q);
	$this->db->or_like('id_bayaran_siswa', $q);
	$this->db->or_like('kode_tran', $q);
	$this->db->or_like('periode', $q);
	$this->db->or_like('tgl_bayar', $q);
	$this->db->or_like('keterangan', $q);
	$this->db->or_like('tgl_ins', $q);
	$this->db->or_like('tgl_updt', $q);
	$this->db->or_like('user_updt', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    function insert_detail($data)
    {
        $this->db->insert_batch('tb_d_tran_siswa', $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id,$id);
        $this->db->update($this->table, $data);
    }

    function update_detail($id, $data)
    {
        $this->db->update_batch('tb_d_tran_siswa', $data,$id);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    function delete_detail($kode)
    {
        $this->db->where('kode_tran', $kode);
        $this->db->delete('tb_d_tran_siswa');
    }

}

/* End of file M_tran_siswa.php */
/* Location: ./application/models/M_tran_siswa.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-23 15:57:13 */
/* http://harviacode.com */
