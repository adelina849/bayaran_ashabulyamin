<?php

	class M_artikel extends CI_Model
	{
		function __construct()
    {
      parent::__construct();
    }

    function list_artikel_limit($offset,$limit)
    {
      $query = $this->db->query("
        SELECT id_artikel,kode_artikel,judul_artikel,isi_artikel,icon_url,image_url
        FROM tb_artikel
        ORDER BY id_artikel DESC
        LIMIT ".$offset.",".$limit."");

      if($query->num_rows() > 0)
      {
        return $query;
      } else {
        return false;
      }
    }
  }

?>
