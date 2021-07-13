<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_semester extends CI_Model
{

    public $table = 'tb_semester';
    public $id = 'id_semester';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id_semester,kode_semester,nama_semester,set_aktif,tgl_ins,tgl_updt,user_updt');
        $this->datatables->from('tb_semester');
        //add this line for join
        //$this->datatables->join('table2', 'tb_semester.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('c_semester/update/$1'),'Update')." | ".anchor(site_url('c_semester/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_semester');
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
        $this->db->like('id_semester', $q);
	$this->db->or_like('kode_semester', $q);
	$this->db->or_like('nama_semester', $q);
	$this->db->or_like('set_aktif', $q);
	$this->db->or_like('tgl_ins', $q);
	$this->db->or_like('tgl_updt', $q);
	$this->db->or_like('user_updt', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id_semester', $q);
	$this->db->or_like('kode_semester', $q);
	$this->db->or_like('nama_semester', $q);
	$this->db->or_like('set_aktif', $q);
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

/* End of file M_semester.php */
/* Location: ./application/models/M_semester.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-25 07:48:58 */
/* http://harviacode.com */