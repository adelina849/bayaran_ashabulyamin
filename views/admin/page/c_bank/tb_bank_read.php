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
        <h2 style="margin-top:0px">Tb_bank Read</h2>
        <table class="table">
	    <tr><td>Norek</td><td><?php echo $norek; ?></td></tr>
	    <tr><td>Nama Bank</td><td><?php echo $nama_bank; ?></td></tr>
	    <tr><td>Atas Nama</td><td><?php echo $atas_nama; ?></td></tr>
	    <tr><td>Cabang</td><td><?php echo $cabang; ?></td></tr>
	    <tr><td>Tgl Pembuatan</td><td><?php echo $tgl_pembuatan; ?></td></tr>
	    <tr><td>Ket Bank</td><td><?php echo $ket_bank; ?></td></tr>
	    <tr><td>Tgl Ins</td><td><?php echo $tgl_ins; ?></td></tr>
	    <tr><td>Tgl Updt</td><td><?php echo $tgl_updt; ?></td></tr>
	    <tr><td>User Updt</td><td><?php echo $user_updt; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('c_bank') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>