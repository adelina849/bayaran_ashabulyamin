<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_tahun_ajaran extends CI_Model
{

    public $table = 'tb_tahun_ajaran';
    public $id = 'id_ajaran';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function get_tahun_aktif()
    {
      $query = $this->db->query("
        SELECT id_ajaran,nama_ajaran
        FROM tb_tahun_ajaran
        WHERE set_aktif = '1'
      ");

      if($query->num_rows() > 0)
      {
        return $query->row();
      } else {
        return false;
      }
    }

    function list_semester_ajaran($id_ajaran)
    {
      $query = $this->db->query("
          SELECT A.id_semester,A.kode_semester,A.nama_semester,COALESCE(B.id_tahun_ajaran,'') id_ajaran,B.mulai_tgl,B.akhir_tgl,
                 COALESCE(B.id_tahun_semester,'')  id_tahun_semester
          FROM tb_semester A
          LEFT JOIN(
            SELECT A.id_semester,A.id_tahun_ajaran ,A.mulai_tgl,A.akhir_tgl,A.id_tahun_semester
            FROM tb_tahun_semester A
            LEFT JOIN tb_tahun_ajaran B
            ON A.id_tahun_ajaran = B.id_ajaran
            LEFT JOIN tb_semester C
            ON A.id_semester = C.id_semester
            WHERE A.id_tahun_ajaran = '".$id_ajaran."' AND A.set_aktif = '1'
          ) B ON A.id_semester = B.id_semester
      ");

      if($query->num_rows() > 0)
      {
        return $query->result();
      } else {
        return false;
      }

    }

    function list_ajaran()
    {
      $query = $this->db->query("
        SELECT id_ajaran,nama_ajaran
        FROM tb_tahun_ajaran
      ");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }

    // datatables
    function json() {
        $this->datatables->select('id_ajaran,kode_tahun,nama_ajaran,keterangan,tgl_ins,tgl_updt,user_updt');
        $this->datatables->from('tb_tahun_ajaran');
        //add this line for join
        //$this->datatables->join('table2', 'tb_tahun_ajaran.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('c_tahun_ajaran/update/$1'),'Update')." | ".anchor(site_url('c_tahun_ajaran/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_ajaran');
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

    function get_by($field,$valu)
    {
        $this->db->where($field, $valu);
        return $this->db->get($this->table)->row();
    }

    function get_tahun_semester_date()
    {
      $query = $this->db->query("
        SELECT * FROM tb_tahun_semester A
        LEFT JOIN tb_tahun_ajaran B
        ON A.id_tahun_ajaran = B.id_ajaran
        WHERE A.mulai_tgl <= DATE_FORMAT(NOW(),'%Y-%m') AND A.akhir_tgl >= DATE_FORMAT(NOW(),'%Y-%m')
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
        $this->db->like('id_ajaran', $q);
	$this->db->or_like('kode_tahun', $q);
	$this->db->or_like('nama_ajaran', $q);
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
        $this->db->like('id_ajaran', $q);
	$this->db->or_like('kode_tahun', $q);
	$this->db->or_like('nama_ajaran', $q);
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

    function insert_tahun_semester($data)
    {
      $this->db->insert_batch('tb_tahun_semester',$data);
    }

    function insert_semester($data)
    {
      $this->db->insert('tb_tahun_semester',$data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    function update_semester($id, $data)
    {
        $this->db->where('id_tahun_semester', $id);
        $this->db->update('tb_tahun_semester', $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    function delete_semester($id)
    {
        $this->db->where('id_tahun_semester', $id);
        $this->db->delete('tb_tahun_semester');
    }
}

/* End of file M_tahun_ajaran.php */
/* Location: ./application/models/M_tahun_ajaran.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-06-29 07:19:50 */
/* http://harviacode.com */
