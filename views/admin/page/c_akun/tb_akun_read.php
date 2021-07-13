<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Tb_akun Read</h2>
        <table class="table">
	    <tr><td>Id Karyawan</td><td><?php echo $id_karyawan; ?></td></tr>
	    <tr><td>Pertanyaan1</td><td><?php echo $pertanyaan1; ?></td></tr>
	    <tr><td>Jawaban1</td><td><?php echo $jawaban1; ?></td></tr>
	    <tr><td>Pertanyaan2</td><td><?php echo $pertanyaan2; ?></td></tr>
	    <tr><td>Jawaban2</td><td><?php echo $jawaban2; ?></td></tr>
	    <tr><td>Username</td><td><?php echo $username; ?></td></tr>
	    <tr><td>Pass</td><td><?php echo $pass; ?></td></tr>
	    <tr><td>Ket Akun</td><td><?php echo $ket_akun; ?></td></tr>
	    <tr><td>Tgl Insert</td><td><?php echo $tgl_insert; ?></td></tr>
	    <tr><td>Tgl Updt</td><td><?php echo $tgl_updt; ?></td></tr>
	    <tr><td>User Updt</td><td><?php echo $user_updt; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('c_akun') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>