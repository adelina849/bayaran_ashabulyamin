<?php

  /**
   *
   */
  class M_home extends CI_Model
  {

    function __construct()
    {
      parent::__construct();
    }

    function get_week_pregan($id_member)
    {
      $query = $this->db->query("
          SELECT hari,weeksout,CASE WHEN weeksout BETWEEN '1' AND '1' THEN '1'
              WHEN weeksout BETWEEN '2' AND '4' THEN '2-4'
              WHEN weeksout BETWEEN '5' AND '8' THEN '5-8'
              WHEN weeksout BETWEEN '9' AND '12' THEN '9-12'
              WHEN weeksout BETWEEN '13' AND '17' THEN '13-17'
              WHEN weeksout BETWEEN '18' AND '21' THEN '18-21'
              WHEN weeksout BETWEEN '22' AND '26' THEN '22-26'
              WHEN weeksout BETWEEN '27' AND '30' THEN '27-30'
              WHEN weeksout BETWEEN '31' AND '35' THEN '31-35'
              WHEN weeksout BETWEEN '36' AND '41' THEN '36-40' END AS image_week
          FROM
          (
            SELECT DATEDIFF(NOW(),start_date) AS hari,ROUND(DATEDIFF(NOW(), start_date)/7, 0) AS weeksout
            FROM tb_member
            WHERE id_member = '".$id_member."'
          ) A
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
  }


 ?>
