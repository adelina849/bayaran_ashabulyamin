<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_siswa extends CI_Model
{

    public $table = 'tb_siswa';
    public $id = 'id_siswa';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id_siswa,kode_siswa,nama_siswa,tgl_lahir,jkel,alamat,no_hp,tgl_ins,tgl_updt,user_updt');
        $this->datatables->from('tb_siswa');
        //add this line for join
        //$this->datatables->join('table2', 'tb_siswa.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('c_siswa/read/$1'),'Detail')." | ".anchor(site_url('c_siswa/update/$1'),'Edit')." | ".anchor(site_url('c_siswa/delete/$1'),'Hapus','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_siswa');
        return $this->datatables->generate();
    }

    function list_siswa_unreg($cari)
    {
      $query = $this->db->query("
        SELECT A.id_siswa,A.kode_siswa,A.nama_siswa,A.tgl_lahir,A.jkel
        FROM tb_siswa A
        LEFT JOIN tb_kelas_siswa B
        ON A.id_siswa = B.id_siswa
        WHERE B.id_kelas_siswa IS NULL AND A.nama_siswa LIKE '%".$cari."%'
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }

    function list_siswa($cari)
    {
      $query = $this->db->query("
        SELECT A.id_siswa,A.kode_siswa,A.nama_siswa,A.tgl_lahir,A.jkel
        FROM tb_siswa A
        LEFT JOIN tb_kelas_siswa B
        ON A.id_siswa = B.id_siswa
        WHERE A.kode_siswa LIKE '%".$cari."%'
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }
    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    function detail_siswa($id)
    {
      $query = $this->db->query("
        SELECT A.id_siswa,B.id_ajaran,B.id_kelas,kode_siswa,nama_siswa,kode_tahun,nama_ajaran,nama_kelas,tgl_mulai,tgl_akhir,hutang_awal
        FROM tb_siswa A
        LEFT JOIN tb_kelas_siswa B
          ON A.id_siswa = B.id_siswa
        LEFT JOIN tb_tahun_ajaran C
          ON B.id_ajaran = C.id_ajaran
        LEFT JOIN tb_kelas D
          ON B.id_kelas = D.id_kelas
        WHERE A.id_siswa = '".$id."'
      ");

      if($query->num_rows() > 0)
      {
        return $query->row();
      } else {
        return false;
      }
    }

    function total_siswa()
    {
      $query = $this->db->query("
        SELECT COUNT(*) AS total_siswa
        FROM tb_siswa
      ");
      return $query->row();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    function cek_nis($id_siswa,$kode_siswa)
    {
      $query = $this->db->query("
        SELECT COUNT(*) AS REC FROM tb_siswa
        WHERE kode_siswa = '".$kode_siswa."'
        AND id_siswa <> '".$id_siswa."'
      ");
      if($query->num_rows() > 0) {
        return $query->row();
      } else {
        return false;
      }


    }

    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id_siswa', $q);
      	$this->db->or_like('kode_siswa', $q);
      	$this->db->or_like('nama_siswa', $q);
      	$this->db->or_like('tgl_lahir', $q);
      	$this->db->or_like('jkel', $q);
      	$this->db->or_like('alamat', $q);
      	$this->db->or_like('no_hp', $q);
      	$this->db->or_like('tgl_ins', $q);
      	$this->db->or_like('tgl_updt', $q);
      	$this->db->or_like('user_updt', $q);
      	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function get_siswa_by_kode($kode_siswa)
    {
        $this->db->where('kode_siswa', $kode_siswa);
        return $this->db->get($this->table)->row();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id_siswa', $q);
	$this->db->or_like('kode_siswa', $q);
	$this->db->or_like('nama_siswa', $q);
	$this->db->or_like('tgl_lahir', $q);
	$this->db->or_like('jkel', $q);
	$this->db->or_like('alamat', $q);
	$this->db->or_like('no_hp', $q);
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

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file M_siswa.php */
/* Location: ./application/models/M_siswa.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-06-29 16:43:06 */
/* http://harviacode.com */
