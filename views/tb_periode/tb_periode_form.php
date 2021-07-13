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
        <h2 style="margin-top:0px">Tb_periode <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nama Periode <?php echo form_error('nama_periode') ?></label>
            <input type="text" class="form-control" name="nama_periode" id="nama_periode" placeholder="Nama Periode" value="<?php echo $nama_periode; ?>" />
        </div>
	    <input type="hidden" name="id_periode" value="<?php echo $id_periode; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tb_periode') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>