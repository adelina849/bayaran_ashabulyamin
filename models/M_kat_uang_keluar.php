<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_kat_uang_keluar extends CI_Model
{

    public $table = 'tb_kat_uang_keluar';
    public $id = 'id_kat_uang_keluar';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id_kat_uang_keluar,id_kat_induk,kode_kat_uang_kelaur,nama_kat_uang_keluar,keterangan,tgl_ins,tgl_updt,user_updt');
        $this->datatables->from('tb_kat_uang_keluar');
        //add this line for join
        //$this->datatables->join('table2', 'tb_kat_uang_keluar.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('c_kat_uang_keluar/update/$1'),'Update')." | ".anchor(site_url('c_kat_uang_keluar/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_kat_uang_keluar');
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

    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id_kat_uang_keluar', $q);
	$this->db->or_like('id_kat_induk', $q);
	$this->db->or_like('kode_kat_uang_kelaur', $q);
	$this->db->or_like('nama_kat_uang_keluar', $q);
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
        $this->db->like('id_kat_uang_keluar', $q);
	$this->db->or_like('id_kat_induk', $q);
	$this->db->or_like('kode_kat_uang_kelaur', $q);
	$this->db->or_like('nama_kat_uang_keluar', $q);
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

/* End of file M_kat_uang_keluar.php */
/* Location: ./application/models/M_kat_uang_keluar.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-09 16:38:19 */
/* http://harviacode.com */
