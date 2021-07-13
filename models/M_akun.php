<?php
	class M_akun extends CI_Model
	{

		function __construct()
		{
			parent::__construct();
		}

		function get_no_member()
		{
			$query = $this->db->query(
			"
				SELECT CONCAT(FRMTGL,COALESCE(ORD,'0001')) AS no_member
				FROM
				(
					SELECT CONCAT(Y,M) AS FRMTGL
					 ,CASE
						WHEN ORD >= 10 THEN CONCAT('00',CAST(ORD AS CHAR))
						WHEN ORD >= 100 THEN CONCAT('0',CAST(ORD AS CHAR))
						WHEN ORD >= 1000 THEN CAST(ORD AS CHAR)
						ELSE CONCAT('000',CAST(ORD AS CHAR))
						END AS ORD
					FROM
					(
						SELECT
						CAST(LEFT(NOW(),4) AS CHAR) AS Y,
						CAST(MID(NOW(),6,2) AS CHAR) AS M,
						MID(NOW(),9,2) AS D,
						(MAX(CAST(RIGHT(no_member,4) AS UNSIGNED)) + 1) AS ORD FROM tb_member
					) AS A
				) AS AA
			"
			);

			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}


		function simpan($no_member,$nama_lengkap,$tgl_lahir,$alamat,$hp,$username,$pass,$day_pregan,$berat,$avatar_url)
		{
			$date = date('Y-m-d', strtotime('-'.$day_pregan.' day'));

			$query = $this->db->query("
				INSERT INTO tb_member (
					no_member,
					nama_lengkap,
					tgl_lahir,
					alamat,
					hp,
					username,
					pass,
					start_date,
					day_pregan,
					berat_badan,
					avatar_url,
					tgl_updt
					)
					VALUES
					(
						'".$no_member."',
						'".$nama_lengkap."',
						'".$tgl_lahir."',
						'".$alamat."',
						'".$hp."',
						'".$username."',
						'".$pass."',
						'".$date."',
						'".$day_pregan."',
						'".$berat."',
						'".$avatar_url."',
						NOW()
					);
			");

			if($query)
			{
				return true;
			} else {
				return false;
			}

		}

		function get_cek_login($user,$pass)
		{
			$query = $this->db->get_where('tb_member', array('username' => $user,'pass'=>$pass));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}

		function get_login($user,$pass)
    {
        $query = $this->db->query("
					SELECT id_akun,
					username,
					pass,
					B.id_karyawan,
					B.id_jabatan,
					B.no_karyawan,
					B.nik_karyawan,
					nama_karyawan,
					pnd,
					tlp,
					email,
					avatar,
					avatar_url,
					alamat,
					ket_karyawan
					FROM tb_akun A
					LEFT JOIN tb_karyawan B
					ON A.id_karyawan = B.id_karyawan
					WHERE username = '".$user."' AND pass = '".$pass."'
				");

				if($query->num_rows() > 0)
		    {
		        return $query->row();
		    }
		    else
		    {
		        return false;
		    }
		}



		function get_profile($id_karyawan,$kode_kantor)
		{

			$query = $this->db->query("

			");

			if($query->num_rows() > 0)
      {
          return $query->row();
      }
      else
      {
          return false;
      }
		}

		function update_profile($id_karyawan,$nama,$pnd,$tlp,$email,$alamat,$user,$kode_kantor)
		{
			$query = $this->db->query("

			");
		}

		function cek_password($id_member,$pass)
		{
			$query = $this->db->query("
				SELECT * FROM tb_member
				WHERE id_member = '".$id_member."'
				AND pass = '".$pass."'
			");

			if($query->num_rows() > 0)
      {
          return $query->row();
      }
      else
      {
          return false;
      }
		}

		function update_password($id_member,$pass)
		{
			$query = $this->db->query("
				UPDATE tb_member
				SET pass = '".$pass."'
				WHERE id_member = '".$id_member."';
			");

			if($query)
			{
				return true;
			} else {
				return false;
			}
		}
	}
?>
