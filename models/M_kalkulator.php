<?php

	class M_kalkulator extends CI_Model
	{
		function __construct()
    {
      parent::__construct();
    }

		function grafik_berat($id_member,$datefrom,$dateto)
    {
      $query = $this->db->query("
          SELECT tgl_berat,qty
          FROM tb_berat_badan
          WHERE id_member = '".$id_member."'
            AND tgl_berat BETWEEN '".$datefrom."' AND '".$dateto."'
          ORDER BY tgl_berat ASC
      ");

      if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
    }

		function list_berat($id_member,$offset,$limit)
    {
      $query = $this->db->query("
				SELECT id_berat,tgl_berat,qty
				FROM tb_berat_badan
				WHERE id_member = '".$id_member."'
				ORDER BY tgl_berat DESC
				LIMIT ".$offset.",".$limit.""
			);

      if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
    }

		function simpan($id_member,$tanggal,$berat)
		{
			$cek = $this->db->query("
				SELECT *
				FROM tb_berat_badan
				WHERE id_member = '".$id_member."'
				AND tgl_berat = '".$tanggal."'
			");

			if($cek->num_rows() > 0)
			{
				$query = $this->db->query("
					UPDATE tb_berat_badan
					SET qty = '".$berat."'
					WHERE id_member = '".$id_member."' AND tgl_berat = '".$tanggal."';
				");
			} else {
				$query = $this->db->query("
					INSERT INTO tb_berat_badan (id_member, tgl_berat, qty)
					VALUES
					(
						'".$id_member."',
						'".$tanggal."',
						'".$berat."'
					);
				");
			}
			return $query;
		}

		function update($id_berat,$tanggal,$berat)
		{
			$query = $this->db->query("
				UPDATE tb_berat_badan
				SET tgl_berat = '".$tanggal."',
					qty = '".$berat."'
				WHERE id_berat = '".$id_berat."';
			");

			return $query;
		}

		function hapus($id_berat)
		{
			$query = $this->db->query("
				DELETE FROM tb_berat_badan
				WHERE id_berat = '".$id_berat."';
			");

			return $query;
		}



	}


?>
