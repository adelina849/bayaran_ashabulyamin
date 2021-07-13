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
        <h2 style="margin-top:0px">Tb_uang_masuk Read</h2>
        <table class="table">
	    <tr><td>Id Kat Uang Masuk</td><td><?php echo $id_kat_uang_masuk; ?></td></tr>
	    <tr><td>No Bukti</td><td><?php echo $no_bukti; ?></td></tr>
	    <tr><td>Nama Uang Masuk</td><td><?php echo $nama_uang_masuk; ?></td></tr>
	    <tr><td>Terima Dari</td><td><?php echo $terima_dari; ?></td></tr>
	    <tr><td>Diterima Oleh</td><td><?php echo $diterima_oleh; ?></td></tr>
	    <tr><td>Untuk</td><td><?php echo $untuk; ?></td></tr>
	    <tr><td>Nominal</td><td><?php echo $nominal; ?></td></tr>
	    <tr><td>Ket Uang Masuk</td><td><?php echo $ket_uang_masuk; ?></td></tr>
	    <tr><td>Tgl Uang Masuk</td><td><?php echo $tgl_uang_masuk; ?></td></tr>
	    <tr><td>Tgl Ins</td><td><?php echo $tgl_ins; ?></td></tr>
	    <tr><td>Tgl Updt</td><td><?php echo $tgl_updt; ?></td></tr>
	    <tr><td>User Updt</td><td><?php echo $user_updt; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('c_uang_masuk') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>