<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_uang_keluar extends CI_Model
{

    public $table = 'tb_uang_keluar';
    public $id = '';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id_uang_keluar,id_kat_uang_keluar,id_proyek,no_uang_keluar,nama_uang_keluar,diberikan_kepada,diberikan_oleh,untuk,nominal,jenis,ket_uang_keluar,tgl_dikeluarkan,tgl_diterima,tgl_ins,tgl_updt,user_updt');
        $this->datatables->from('tb_uang_keluar');
        //add this line for join
        //$this->datatables->join('table2', 'tb_uang_keluar.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('c_uang_keluar/update/$1'),'Update')." | ".anchor(site_url('c_uang_keluar/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), '');
        return $this->datatables->generate();
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

    function get_by_date($mulai,$akhir)
    {
      $query = $this->db->query("
        SELECT SUM(nominal) nominal
        FROM tb_uang_keluar
        WHERE DATE_FORMAT(tgl_dikeluarkan,'%Y-%m')  BETWEEN '".$mulai."' AND '".$akhir."'
      ");

      if($query->num_rows() > 0)
      {
        return $query->row();
      } else {
        return false;
      }
    }

    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('', $q);
      	$this->db->or_like('id_uang_keluar', $q);
      	$this->db->or_like('id_kat_uang_keluar', $q);
      	$this->db->or_like('id_proyek', $q);
      	$this->db->or_like('no_uang_keluar', $q);
      	$this->db->or_like('nama_uang_keluar', $q);
      	$this->db->or_like('diberikan_kepada', $q);
      	$this->db->or_like('diberikan_oleh', $q);
      	$this->db->or_like('untuk', $q);
      	$this->db->or_like('nominal', $q);
      	$this->db->or_like('jenis', $q);
      	$this->db->or_like('ket_uang_keluar', $q);
      	$this->db->or_like('tgl_dikeluarkan', $q);
      	$this->db->or_like('tgl_diterima', $q);
      	$this->db->or_like('tgl_ins', $q);
      	$this->db->or_like('tgl_updt', $q);
      	$this->db->or_like('user_updt', $q);
      	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('', $q);
      	$this->db->or_like('id_uang_keluar', $q);
      	$this->db->or_like('id_kat_uang_keluar', $q);
      	$this->db->or_like('id_proyek', $q);
      	$this->db->or_like('no_uang_keluar', $q);
      	$this->db->or_like('nama_uang_keluar', $q);
      	$this->db->or_like('diberikan_kepada', $q);
      	$this->db->or_like('diberikan_oleh', $q);
      	$this->db->or_like('untuk', $q);
      	$this->db->or_like('nominal', $q);
      	$this->db->or_like('jenis', $q);
      	$this->db->or_like('ket_uang_keluar', $q);
      	$this->db->or_like('tgl_dikeluarkan', $q);
      	$this->db->or_like('tgl_diterima', $q);
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

/* End of file M_uang_keluar.php */
/* Location: ./application/models/M_uang_keluar.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-21 03:52:59 */
/* http://harviacode.com */