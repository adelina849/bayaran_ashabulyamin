<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_bayaran_pengurang extends CI_Model
{

    public $table = 'tb_bayaran_pengurang';
    public $id = 'id_pengurang_siswa';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id_bayaran_siswa,id_tahun_ajaran,id_kelas,id_siswa,id_pengurang,kode_bayaran_siswa,nama_bayaran_siswa,keterangan,tgl_ins,tgl_updt,user_updt');
        $this->datatables->from('tb_bayaran_pengurang');
        //add this line for join
        //$this->datatables->join('table2', 'tb_bayaran_pengurang.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('c_bayaran_pengurang/read/$1'),'Read')." | ".anchor(site_url('c_bayaran_pengurang/update/$1'),'Update')." | ".anchor(site_url('c_bayaran_pengurang/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_bayaran_siswa');
        return $this->datatables->generate();
    }

    function list_bayaran_pengurang($id_ajaran,$id_kelas,$offset,$limit)
    {
      $query = $this->db->query("
          SELECT * FROM tb_bayaran_pengurang A
          LEFT JOIN tb_tahun_ajaran B
          ON A.id_tahun_ajaran = B.id_ajaran
          LEFT JOIN tb_kelas C
          ON A.id_kelas = C.id_kelas
          LEFT JOIN tb_pengurang_bayaran D
          ON A.id_pengurang = D.id_pengurang
          LEFT JOIN tb_siswa E
          ON A.id_siswa = E.id_siswa
          WHERE A.id_tahun_ajaran = '".$id_ajaran."' AND A.id_kelas LIKE '%".$id_kelas."%'
          ORDER BY A.id_tahun_ajaran,A.id_kelas
          LIMIT ".$offset.",".$limit."
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

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id_bayaran_siswa', $q);
	$this->db->or_like('id_tahun_ajaran', $q);
	$this->db->or_like('id_kelas', $q);
	$this->db->or_like('id_siswa', $q);
	$this->db->or_like('id_pengurang', $q);
	$this->db->or_like('kode_bayaran_siswa', $q);
	$this->db->or_like('nama_bayaran_siswa', $q);
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
        $this->db->like('id_bayaran_siswa', $q);
	$this->db->or_like('id_tahun_ajaran', $q);
	$this->db->or_like('id_kelas', $q);
	$this->db->or_like('id_siswa', $q);
	$this->db->or_like('id_pengurang', $q);
	$this->db->or_like('kode_bayaran_siswa', $q);
	$this->db->or_like('nama_bayaran_siswa', $q);
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
        $this->db->insert_batch($this->table, $data);
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

/* End of file M_bayaran_pengurang.php */
/* Location: ./application/models/M_bayaran_pengurang.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-22 06:49:40 */
/* http://harviacode.com */