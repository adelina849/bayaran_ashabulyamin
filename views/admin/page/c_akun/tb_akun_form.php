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
        <h2 style="margin-top:0px">Tb_akun <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="bigint">Id Karyawan <?php echo form_error('id_karyawan') ?></label>
            <input type="text" class="form-control" name="id_karyawan" id="id_karyawan" placeholder="Id Karyawan" value="<?php echo $id_karyawan; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Pertanyaan1 <?php echo form_error('pertanyaan1') ?></label>
            <input type="text" class="form-control" name="pertanyaan1" id="pertanyaan1" placeholder="Pertanyaan1" value="<?php echo $pertanyaan1; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Jawaban1 <?php echo form_error('jawaban1') ?></label>
            <input type="text" class="form-control" name="jawaban1" id="jawaban1" placeholder="Jawaban1" value="<?php echo $jawaban1; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Pertanyaan2 <?php echo form_error('pertanyaan2') ?></label>
            <input type="text" class="form-control" name="pertanyaan2" id="pertanyaan2" placeholder="Pertanyaan2" value="<?php echo $pertanyaan2; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Jawaban2 <?php echo form_error('jawaban2') ?></label>
            <input type="text" class="form-control" name="jawaban2" id="jawaban2" placeholder="Jawaban2" value="<?php echo $jawaban2; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Username <?php echo form_error('username') ?></label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Pass <?php echo form_error('pass') ?></label>
            <input type="text" class="form-control" name="pass" id="pass" placeholder="Pass" value="<?php echo $pass; ?>" />
        </div>
	    <div class="form-group">
            <label for="ket_akun">Ket Akun <?php echo form_error('ket_akun') ?></label>
            <textarea class="form-control" rows="3" name="ket_akun" id="ket_akun" placeholder="Ket Akun"><?php echo $ket_akun; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Insert <?php echo form_error('tgl_insert') ?></label>
            <input type="text" class="form-control" name="tgl_insert" id="tgl_insert" placeholder="Tgl Insert" value="<?php echo $tgl_insert; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Updt <?php echo form_error('tgl_updt') ?></label>
            <input type="text" class="form-control" name="tgl_updt" id="tgl_updt" placeholder="Tgl Updt" value="<?php echo $tgl_updt; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">User Updt <?php echo form_error('user_updt') ?></label>
            <input type="text" class="form-control" name="user_updt" id="user_updt" placeholder="User Updt" value="<?php echo $user_updt; ?>" />
        </div>
	    <input type="hidden" name="id_akun" value="<?php echo $id_akun; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('c_akun') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>